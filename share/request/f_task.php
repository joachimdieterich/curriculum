<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename f_task.php
* @copyright 2016 Joachim Dieterich
* @author Joachim Dieterich
* @date 2016.05.18 08:58
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
$task_id        = null;
$task           = null; 
$description    = null;
$creation_time  = null;
$creator_id     = null;
$timestart      = null;
$timeend        = null;
$timerange      = null;

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
        case "new":         checkCapabilities('task:add',    $USER->role_id);
                            $header     = 'Aufgabe hinzufügen';             
            break;
        case "edit":        checkCapabilities('task:update', $USER->role_id);
                            $header     = 'Aufgabe aktualisieren';
                            $tsk        = new Task();
                            $tsk->load('id', filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT));
                            $task_id    = $tsk->id;
                            foreach ($tsk as $key => $value){
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

$content  ='<form id="form_task"  class="form-horizontal" role="form" method="post" action="../share/processors/fp_task.php"';
if (isset($currentUrlId)){ $content .= $currentUrlId; }
$content .= '">
<input type="hidden" name="func" id="func" value="'.$func.'"/>';
if (isset($task_id)){
    $content .= '<input type="hidden" name="task_id" id="task_id" value="'.$task_id.'"/> ';
}
if (isset($reference_id)){
$content .= '<input type="hidden" name="reference_id" id="reference_id" value="'.$reference_id.'"/> ';
}
$content .= Form::input_text('task', 'Aufgabe', $task, $error, 'z. B. Erstellen einer Mindmap zum Thema Pressefreiheit');
$content .= Form::input_textarea('description', 'Beschreibung', $description, $error, 'Beschreibung');
$content .= Form::input_date(array('id'=>'timerange', 'label' => 'Zeitraum zum Erledigen' , 'time' => $timerange, 'error' => $error, 'placeholder' => '', $type = 'date'));
$content .= '</form>';
$footer   = '<button type="submit" class="btn btn-primary pull-right" onclick="document.getElementById(\'form_task\').submit();"><i class="fa fa-floppy-o margin-r-5"></i>'.$header.'</button> ';

$script = "<!-- daterangepicker -->
        <script id='modal_script'>
        $.getScript('".$CFG->smarty_template_dir_url."plugins/daterangepicker/daterangepicker.js', function (){
        //$('.color-picker').colorpicker();
        $('.datepicker').daterangepicker({timePicker: true, timePickerIncrement: 1, timePicker24Hour: true, locale: {format: 'DD.MM.YYYY HH:mm'}});
        });</script>";

$html     = Form::modal(array('title'     => $header,
                              'content'   => $content, 
                              'f_content' => $footer));  

echo json_encode(array('html'=>$html, 'script'=> $script));