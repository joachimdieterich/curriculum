<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename f_coursebook.php
* @copyright 2016 Joachim Dieterich
* @author Joachim Dieterich
* @date 2016.05.09 09:08
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
global $CFG, $USER, $COURSE;
$USER          = $_SESSION['USER'];
$COURSE        = $_SESSION['COURSE'];

$cur           = new Curriculum();
/*Variablen anlegen -> vermeidet unnötige if-Abfragen im Formular*/
$id            = null;
$topic         = null; 
$description   = null;
$creation_time = null;
$creator_id    = null;
if (isset($COURSE->id)){
    $course_id = $COURSE->id;
}
$timerange     = null;

/* user_list */
$teacher_list  = null;
$present_list  = null;
$absent_list   = null;

/* task */
$task_id       = null; 
$task          = null; 

$func          = $_GET['func'];

$error         =   null;
$object        = file_get_contents("php://input");
$data          = json_decode($object, true);
if (is_array($data)) {
    foreach ($data as $key => $value){
        $$key = $value;
    }
}
            
if (isset($_GET['func'])){
    switch ($_GET['func']) {
        case "new":     checkCapabilities('coursebook:add',    $USER->role_id, false, true);
                        $header = 'Kursbucheintrag hinzufügen';          
            break;
        case "edit":    checkCapabilities('coursebook:update', $USER->role_id, false, true);
                        $header = 'Kursbucheintrag aktualisieren';
                        $course_book = new CourseBook();
                        $course_book->load('cb_id', filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT));
                        foreach ($course_book as $key => $value){
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

$content  ='<form id="form_courseBook"  class="form-horizontal" role="form" method="post" action="../share/processors/fp_courseBook.php"';
if (isset($currentUrlId)){ $content .= $currentUrlId; }
$content .= '">
<input type="hidden" name="func" id="func" value="'.$func.'"/>
<input id="id" name="id" type="text" class="invisible" ';
if (isset($id)) { $content .= 'value="'.$id.'"';} $content .= '>';
$content .= Form::input_textarea('topic', 'Thema', $topic, $error, 'Stundenthema');
$content .= Form::input_textarea('description', 'Beschreibung', $description, $error, 'Beschreibung');

$courses = new Course(); 
if(checkCapabilities('user:userListComplete', $USER->role_id, false, true)){
    $courses = $courses->getCourse('admin', $USER->id);  
} else if(checkCapabilities('user:userList', $USER->role_id, false, true)){
    $courses = $courses->getCourse('teacher', $USER->id);  // abhängig von USER->my_semester id --> s. Select in objectives.tpl, 
}                                               // Load schooltype 
$content .= Form::input_select('course_id', 'Kurs / Klasse', $courses, 'course', 'course_id', $course_id , $error);
$content .= Form::input_date(array('id'=>'timerange', 'label' => 'Dauer' , 'time' => $timerange, 'error' => $error, 'placeholder' => '', $type = 'date'));
$content .= '</form>';
$footer   = '<button type="submit" class="btn btn-primary pull-right" onclick="document.getElementById(\'form_courseBook\').submit();"><i class="fa fa-floppy-o margin-r-5"></i>'.$header.'</button> ';
   
$html     = Form::modal(array('title'     => $header,
                              'content'   => $content, 
                              'f_content' => $footer));

$script = "<!-- daterangepicker -->
        <script id='modal_script'>
        $.getScript('".$CFG->smarty_template_dir_url."plugins/daterangepicker/daterangepicker.js', function (){
        $('.datepicker').daterangepicker({timePicker: true, timePickerIncrement: 1, timePicker24Hour: true, locale: {format: 'DD.MM.YYYY HH:mm'}});
        });</script>";
echo json_encode(array('html'=>$html, 'script'=> $script));