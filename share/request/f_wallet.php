<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename f_wallet.php
* @copyright 2016 Joachim Dieterich
* @author Joachim Dieterich
* @date 2016.12.28 15:04
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
global $CFG, $USER,$TEMPLATE;
$USER           = $_SESSION['USER'];
$func           = $_GET['func'];
/*Variablen anlegen -> vermeidet unnötige if-Abfragen im Formular*/
$id             = null; 
$title          = null; 
$description    = null; 
$file_id        = $CFG->settings->standard_avatar_id;
$event_id       = null;
$curriculum_id  = null; 
$objectives     = null;
$user_list_id   = null;
$subject_id     = null;
$timerange      = null;
$error          = null;
$object         = file_get_contents("php://input");
$data           = json_decode($object, true);
if (is_array($data)) {
    foreach ($data as $key => $value){
        $$key = $value;
    }
}
            
if (isset($_GET['func'])){
    switch ($_GET['func']) {
        case "new":     checkCapabilities('wallet:add',    $USER->role_id);
                        $header = 'Sammelmappe anlegen';            
            break;
        case "edit":    checkCapabilities('wallet:update',    $USER->role_id);
                        $header   = 'Sammelmappe bearbeiten';
                        $edit     = true; 
                        $w        = new Wallet(filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT));
                        foreach ($w as $key => $value){
                             $$key = $value;
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

$content ='<form id="form_wallet" class="form-horizontal" role="form" method="post" action="../share/processors/fp_wallet.php"';
if (isset($currentUrlId)){ $content .= $currentUrlId; }
$content .= '"><input type="hidden" name="func" id="func" value="'.$func.'"/>';
if (isset($id)) {                                                               // only set id input field if set! prevents error on validation form reload
     $content .= '<input id="id" name="id" type="text" class="invisible" value="'.$id.'">';
}
$content .= Form::input_text('title', 'Titel', $title, $error, 'Name der Sammelmappe');
$content .= Form::input_text('description', 'Beschreibung', $description, $error, 'Aufgabenstellung zur Sammelmappe');
$content .= '<input type="hidden" name="file_id" id="file_id" value="'.$file_id.'"/>';
 // id have to be set to add image
$content .= '<div class="col-xs-3"></div><div class="col-xs-9">'
            . '<a href="'.$CFG->smarty_template_dir_url.'renderer/uploadframe.php?context=userFiles&target=file_id&ref_id='.$id.'&format=0&modal=true" class="nyroModal">';
            if ($id != null) {
                $content .= '<img id="icon" style="height:100px; margin-left: -5px; padding-bottom:10px;" src="'.$CFG->access_id_url.$file_id.'" >';
            } else {
                $content .= '<img id="icon" style="height:100px; margin-left: -5px; padding-bottom:10px;" src="'.$CFG->access_id_url.$file_id.'" >
                             <i class="fa fa-plus"></i> Bild hinzufügen';
            }
$content .= '</a></div>';
$curriculum   = new Curriculum();
$curriculum    = $curriculum->getCurricula('user', $USER->id);

if ($id == null) {
    $curriculum_id = $curriculum[0]->id;        
    $content .= Form::input_select('curriculum_id', 'Lehrplan', $curriculum, 'curriculum', 'id', $curriculum_id , $error, 'getValues(\'objectives\', this.value, \'objective_id\');');
} else {
    $content .= Form::input_select('curriculum_id', 'Lehrplan', $curriculum, 'curriculum', 'id', $curriculum_id , $error, '','', 'col-sm-3', 'col-sm-9', 'disabled="disabled"');
}

/* todo: are subgroups "Schülerauswahl" necessary?*/
/*$content .= Form::input_select_multiple(array('id' => 'user_list', 'label' => 'Schülerauswahl', 'select_data' => $members, 'select_label' => 'firstname, lastname', 'select_value' => 'id', 'input' => $user_list, 'error' => $error));*/
/* Fächer */ 
$subjects                   = new Subject();                                                      
$subjects->institution_id   = $USER->institutions;
$content .= Form::input_select('subject_id', 'Fach', $subjects->getSubjects(), 'subject, institution', 'id', $subject_id , $error);
$content .= Form::input_date(array('id'=>'timerange', 'label' => 'Zeitraum' , 'time' => $timerange, 'error' => $error, 'placeholder' => '', $type = 'date'));
$ena      = new EnablingObjective();
$ena->curriculum_id = $curriculum_id;
$content .= Form::input_select_multiple(array('id' => 'objective_id', 'label' => 'Kompetenzen/ Lernziele', 'select_data' => $ena->getObjectives('curriculum', $curriculum_id), 'select_label' => 'enabling_objective', 'select_value' => 'id', 'input' => $objectives, 'error' => $error)); 
$content .= '</form>';
$footer   = '<button type="submit" class="btn btn-primary pull-right" onclick="document.getElementById(\'form_wallet\').submit();"><i class="fa fa-floppy-o margin-r-5"></i>'.$header.'</button>'; 

$html     = Form::modal(array('title'     => $header,
                              'content'   => $content, 
                              'f_content' => $footer));

$script = '<script id=\'modal_script\'>
        $(function() {
            $(\'.nyroModal\').nyroModal({
            callbacks: {
                beforeShowBg: function(){
                    $(\'body\').css(\'overflow\', \'hidden\');
                       
                },
                afterHideBg: function(){
                    $(\'body\').css(\'overflow\', \'\');
                 
                },
                afterShowCont: function(nm) {
                    $(\'.scroll_list\').height($(\'.modal\').height()-150);
                }   
            }
        });
            $(\'#popup_generate\').nyroModal();
        });
        $(\'#file_id\').change(\'input\', function() {
            document.getElementById("icon").src = "'.$CFG->access_id_url.'"+document.getElementById("file_id").value;
        });
        $.getScript(\''.$CFG->smarty_template_dir_url.'plugins/daterangepicker/daterangepicker.js\', function (){
        $(\'.datepicker\').daterangepicker({timePicker: true, timePickerIncrement: 1, timePicker24Hour: true, locale: {format: \'DD.MM.YYYY HH:mm\'}});
        });
        </script>';
echo json_encode(array('html' => $html, 'script' => $script));