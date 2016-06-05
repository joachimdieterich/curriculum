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
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by  
 * the Free Software Foundation; either version 3 of the License, or (at your option) any later version.
 *                                                                       
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of        
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details:
 *                                                                       
 * http://www.gnu.org/copyleft/gpl.html      
 */
$base_url   = dirname(__FILE__).'/../';
include($base_url.'setup.php');  //Läd Klassen, DB Zugriff und Funktionen
include(dirname(__FILE__).'/../login-check.php');  //check login status and reset idletimer
global $CFG, $USER, $COURSE;
$USER           = $_SESSION['USER'];
$COURSE         = $_SESSION['COURSE'];


/*Variablen anlegen -> vermeidet unnötige if-Abfragen im Formular*/
$event_id          = null;
$event             = null; 
$description       = null;
$creation_time     = null;
$creator_id        = null;
/*if (isset($COURSE->id)){
$course_id         = $COURSE->id;
}*/
$group_id          = null;
$user_id           = null;
$context_id        = null;
$repeat_id         = null;
$sequence          = null;
$reminder_interval = null;
$timestart         = null;
$timeend           = null;
$timerange         = null;
$status            = null;

$func                   = $_GET['func'];

$error                  =   null;
$object = file_get_contents("php://input");
$data = json_decode($object, true);
if (is_array($data)) {
    foreach ($data as $key => $value){
        $$key = $value;
        error_log($key.': '.$value);
    }
}
            
if (isset($_GET['func'])){
    switch ($_GET['func']) {
        case "new":     checkCapabilities('event:add',    $USER->role_id);
                        $header     = 'Termin hinzufügen';
                        $add        = true;              
            break;
        case "edit":    checkCapabilities('event:update', $USER->role_id);
                        $header     = 'Termin aktualisieren';
                        $edit       = true; 
                        $ev         = new Event();
                        $ev->load('id', filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT));
                        $event_id   = $ev->id;
                        foreach ($ev as $key => $value){
                            if (!is_object($value)){
                                $$key = $value;
                                error_log($key. ': '.$value);
                            }
                        }
                        
            break;
        default: break;
    }
}

/* if validation failed, get formdata from session*/
if (is_object($_SESSION['FORM'])) {
    foreach ($_SESSION['FORM'] as $key => $value){
        $$key = $value;
    }
}

$html ='<div class="modal-dialog" style="overflow-y: initial !important;">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closePopup()"><span aria-hidden="true">×</span></button>
              <h4 class="modal-title">'.$header.'</h4>
            </div>
            <div class="modal-body" style="max-height: 500px; overflow-y: auto;">';
   
$html .='<form id="form_event"  class="form-horizontal" role="form" method="post" action="../share/processors/fp_event.php"';

if (isset($currentUrlId)){ $html .= $currentUrlId; }
$html .= '">
<input type="hidden" name="func" id="func" value="'.$func.'"/>';
if (isset($event_id)){
$html .= '<input type="hidden" name="event_id" id="event_id" value="'.$event_id.'"/> ';
}
$html .= Form::input_text('event', 'Termin', $event, $error, 'z. B. Treffen in der Aula');
$html .= Form::input_textarea('description', 'Beschreibung', $description, $error, 'Beschreibung');
/*
$courses = new Course(); 
if(checkCapabilities('user:userListComplete', $USER->role_id, false)){
    $courses = $courses->getCourse('admin', $USER->id);  
} else if(checkCapabilities('user:userList', $USER->role_id, false)){
    $courses = $courses->getCourse('teacher', $USER->id);  // abhängig von USER->my_semester id --> s. Select in objectives.tpl, 
}                                               // Load schooltype 
$html       .= Form::input_select('course_id', 'Kurs / Klasse', $courses, 'course', 'course_id', $course_id , $error);

$g           = new Group();
$html       .= Form::input_select('group_id', 'Gruppe', $g->getGroups('user',$USER->id), 'group', 'id', $group_id , $error);

$u           = new User();
$html       .= Form::input_select('user_id', 'Gruppe', $u->userList(), 'firstname, lastname', 'id', $group_id , $error);*/
$html       .= Form::input_date(array('id'=>'timerange', 'label' => 'Dauer' , 'time' => $timerange, 'error' => $error, 'placeholder' => '', $type = 'date'));

$html       .= '</div><!-- /.modal-body -->
            <div class="modal-footer">';
            if (isset($edit)){
                $html .= '<button name="update" type="submit" class="btn btn-primary glyphicon glyphicon-saved pull-right" onclick="document.getElementById(\'form_event\').submit();">'.$header.'</button>'; 
            } 
            if (isset($add)){
                $html .= '<button id="add" name="add" type="submit" class="btn btn-primary glyphicon glyphicon-ok pull-right" onclick="document.getElementById(\'form_event\').submit();">'.$header.'</button> ';
            }    
$html .=  '</div></form></div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->';

$script = "<!-- daterangepicker -->
        <script id='modal_script'>
        $.getScript('".$CFG->base_url ."public/assets/templates/AdminLTE-2.3.0/plugins/daterangepicker/daterangepicker.js', function (){
        //$('.color-picker').colorpicker();
        $('.datepicker').daterangepicker({timePicker: true, timePickerIncrement: 1, timePicker24Hour: true, locale: {format: 'DD.MM.YYYY HH:mm'}});
        });</script>";
echo json_encode(array('html'=>$html, 'script'=> $script));
