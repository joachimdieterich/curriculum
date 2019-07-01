<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename f_event.php
* @copyright 2016 Joachim Dieterich
* @author Joachim Dieterich
* @date 2016.05.12 09:37
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
include_once(dirname(__FILE__).'/../login-check.php');  //check login status and reset idletimer
global $CFG, $USER;//, $COURSE;
$USER           = $_SESSION['USER'];
/*if (isset($_SESSION['COURSE'])){
    $COURSE         = $_SESSION['COURSE'];
}*/


/*Variablen anlegen -> vermeidet unnötige if-Abfragen im Formular*/
$event_id          = null;
$event             = null; 
$description       = null;
$creation_time     = null;
$creator_id        = null;
if (isset($COURSE->id)){
$course_id         = $COURSE->id;
} else {
  $course_id       = 0;  
}
$group_id          = 0;
$user_id           = $USER->id;
$context_id        = 1;
$repeat_id         = null;
$sequence          = null;
$reminder_interval = null;
$timestart         = null;
$timeend           = null;
$timerange         = null;
$status            = null;
$footer             = '';
$func              = $_GET['func'];
$error             =   null;
$object            = file_get_contents("php://input");
$data = json_decode($object, true);
if (is_array($data)) {
    extract($data);
}
            
if (isset($_GET['func'])){
    switch ($_GET['func']) {
        case "new":     checkCapabilities('event:add',    $USER->role_id, false, true);
                        $header     = 'Termin hinzufügen';        
            break;
        case "edit":    checkCapabilities('event:update', $USER->role_id, false, true);
                        $header     = 'Termin aktualisieren';
                        $ev         = new Event();
                        $ev->load('id', filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT));
                        $event_id   = $ev->id;
                        foreach ($ev as $key => $value){
                            if (!is_object($value)){
                                $$key = $value;
                            }
                        }                        
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
   
$content ='<form id="form_event"  class="form-horizontal" role="form" method="post" action="../share/processors/fp_event.php">
            <input type="hidden" name="func" id="func" value="'.$func.'"/>
            <input type="hidden" name="context_id" id="context_id" value="'.$context_id.'"/>';
if (isset($event_id)){
    $content .= '<input type="hidden" name="event_id" id="event_id" value="'.$event_id.'"/> ';
}

$content .= Form::input_text('event', 'Termin', $event, $error, 'z. B. Treffen in der Aula');
$content .= Form::input_textarea('description', 'Beschreibung', $description, $error, 'Beschreibung');
$content .= Form::input_date(array('id'=>'timerange', 'label' => 'Dauer' , 'time' => $timerange, 'error' => $error, 'placeholder' => '', $type = 'date'));
$content .= '</form>';
if ($_GET['func'] == 'edit'){
    $footer   = '<button type="submit" class="btn btn-danger pull-left" onclick="processor(\'delete\', \'event\', \''.$event_id.'\');"><i class="fa fa-trash margin-r-5"></i> Termin löschen</button>';
}
$footer   .= '<button type="submit" class="btn btn-primary pull-right" onclick="document.getElementById(\'form_event\').submit();"><i class="fa fa-floppy-o margin-r-5"></i>'.$header.'</button>';   
$html     = Form::modal(array('title'     => $header,
                              'content'   => $content, 
                              'f_content' => $footer));

$script = "<!-- daterangepicker -->
        <script id='modal_script'>
        $.getScript('".$CFG->smarty_template_dir_url."/plugins/daterangepicker/daterangepicker.js', function (){
        //$('.color-picker').colorpicker();
        $('.datepicker').daterangepicker({timePicker: true, timePickerIncrement: 1, timePicker24Hour: true, locale: {format: 'DD.MM.YYYY HH:mm'}});
        });</script>";
echo json_encode(array('html'=>$html, 'script'=> $script));