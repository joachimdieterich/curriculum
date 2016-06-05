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
$absent_id      = null;
$reason         = null;
$user_list      = null;
$done           = null;
$func           = $_GET['func'];

$error          =   null;
$object = file_get_contents("php://input");
$data = json_decode($object, true);
if (is_array($data)) {
    foreach ($data as $key => $value){
        $$key = $value;
        //error_log('input: '.$key.': '.$value);
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
                                    //error_log($key. ': '.$value);
                                }
                            }
                            $reference_id = $cb_id; // to get reference 
                        
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
   
$html .='<form id="form_absent"  class="form-horizontal" role="form" method="post" action="../share/processors/fp_absent.php"';

if (isset($currentUrlId)){ $html .= $currentUrlId; }
$html .= '"><input type="hidden" name="reference_id" id="reference_id" value="'.$reference_id.'"/>
<input type="hidden" name="func" id="func" value="'.$func.'"/>';
if (isset($absent_id)){
$html .= '<input type="hidden" name="absent_id" id="absent_id" value="'.$absent_id.'"/> ';
$html .= '<input type="hidden" name="user_id" id="user_id" value="'.$user_id.'"/> ';
$html .= Form::input_text('username', 'Benutzername', $user, $error,'','text',null, null, 'col-sm-3','col-sm-9', true);
}
$html .= Form::input_text('reason', 'Fehlgrund', $reason, $error, 'z. B. Krankmeldung');
if (!isset($absent_id)){
$html .= Form::input_select_multiple('user_list', 'Kursmitglieder', $members, 'firstname, lastname', 'id', $user_list, $error );
}
$html .= Form::input_checkbox('status', 'Entschuldigt', $status, $error);

$html       .= '</div><!-- /.modal-body -->
            <div class="modal-footer">';
            if (isset($edit)){
                $html .= '<button name="update" type="submit" class="btn btn-primary glyphicon glyphicon-saved pull-right" onclick="document.getElementById(\'form_absent\').submit();"> '.$header.'</button>'; 
            } 
            if (isset($add)){
                $html .= '<button id="add" name="add" type="submit" class="btn btn-primary glyphicon glyphicon-ok pull-right" onclick="document.getElementById(\'form_absent\').submit();"> '.$header.'</button> ';
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
