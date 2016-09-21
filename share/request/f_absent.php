<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename f_absent.php
* @copyright 2016 Joachim Dieterich
* @author Joachim Dieterich
* @date 2016.05.25 08:58
* @license: 
*
* The MIT License (MIT)
* Copyright (c) 2012 Joachim Dieterich http://www.curriculumonline.de
* 
* Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), 
* to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, 
* and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
* 
* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
* 
* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF 
* MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, 
* DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR 
* THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/
$base_url   = dirname(__FILE__).'/../';
include($base_url.'setup.php');  //Läd Klassen, DB Zugriff und Funktionen
include(dirname(__FILE__).'/../login-check.php');  //check login status and reset idletimer
global $CFG, $USER, $COURSE;
$USER           = $_SESSION['USER'];
$COURSE         = $_SESSION['COURSE'];

/*Variablen anlegen -> vermeidet unnötige if-Abfragen im Formular*/
$absent_id      = null;
$reason         = null;
$user_list      = null;
$done           = null;
$func           = $_GET['func'];

$error          =   null;
$object         = file_get_contents("php://input");
$data           = json_decode($object, true);
if (is_array($data)) {
    foreach ($data as $key => $value){
        $$key = $value;
    }
}
            
if (isset($_GET['func'])){
    switch ($_GET['func']) {
        case "coursebook":  $reference_id =  filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        case "new":         checkCapabilities('absent:add',    $USER->role_id);
                            $header       = 'Fehlende Person(en) erfassen ';
                            $add          = true;           
                            $course       = new Course();
                            if (isset($reference_id)){
                                $cb       = new CourseBook();
                                $cb->load('cb_id', $reference_id);
                                $members  = $course->members($cb->course_id);
                            }
            break;
        case "edit":        checkCapabilities('absent:update', $USER->role_id);
                            $header     = 'Anwesenheitsliste aktualisieren';
                            $edit       = true; 
                            $abs         = new Absent();
                            $abs->load('id', filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT));
                            $absent_id   = $abs->id;
                            foreach ($abs as $key => $value){
                                if (!is_object($value)){
                                    $$key = $value;
                                }
                            }
                            $reference_id = $cb_id; // to get reference 
                        
            break;
        default: break;
    }
}

/* if validation failed, get formdata from session*/
if (isset($_SESSION['FORM'])){
    if (is_object($_SESSION['FORM'])) {
        foreach ($_SESSION['FORM'] as $key => $value){
            $$key = $value;
        }
    }
}
   
$content .='<form id="form_absent"  class="form-horizontal" role="form" method="post" action="../share/processors/fp_absent.php"';

if (isset($currentUrlId)){ $content .= $currentUrlId; }
$content .= '"><input type="hidden" name="reference_id" id="reference_id" value="'.$reference_id.'"/>
<input type="hidden" name="func" id="func" value="'.$func.'"/>';
if (isset($absent_id)){
$content .= '<input type="hidden" name="absent_id" id="absent_id" value="'.$absent_id.'"/> ';
$content .= '<input type="hidden" name="user_id" id="user_id" value="'.$user_id.'"/> ';
$content .= Form::input_text('username', 'Benutzername', $user, $error,'','text',null, null, 'col-sm-3','col-sm-9', true);
}
$content .= Form::input_text('reason', 'Fehlgrund', $reason, $error, 'z. B. Krankmeldung');
if (!isset($absent_id)){
$content .= Form::input_select_multiple('user_list', 'Kursmitglieder', $members, 'firstname, lastname', 'id', $user_list, $error );
}
$content .= Form::input_checkbox('status', 'Entschuldigt', $status, $error);
$content .= '</div></form>';
$f_content = '';
if (isset($edit)){
    $f_content .= '<button name="update" type="submit" class="btn btn-primary glyphicon glyphicon-saved pull-right" onclick="document.getElementById(\'form_absent\').submit();"> '.$header.'</button>'; 
} 
if (isset($add)){
    $f_content .= '<button id="add" name="add" type="submit" class="btn btn-primary glyphicon glyphicon-ok pull-right" onclick="document.getElementById(\'form_absent\').submit();"> '.$header.'</button> ';
}    
$html     = Form::modal(array('title'     => $header,
                              'content'   => $content, 
                              'f_content' => $f_content));

$script = "<!-- daterangepicker -->
        <script id='modal_script'>
        $.getScript('".$CFG->base_url ."public/assets/templates/AdminLTE-2.3.0/plugins/daterangepicker/daterangepicker.js', function (){
        //$('.color-picker').colorpicker();
        $('.datepicker').daterangepicker({timePicker: true, timePickerIncrement: 1, timePicker24Hour: true, locale: {format: 'DD.MM.YYYY HH:mm'}});
        });</script>";
echo json_encode(array('html'=>$html, 'script'=> $script));