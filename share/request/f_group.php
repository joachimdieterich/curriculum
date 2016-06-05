<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package core
 * @filename f_group.php
 * @copyright 2016 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2016.05.28 17:32
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
$func           = $_GET['func'];
$group_id       = null;
$group          = null;
$description    = null;
$grade_id       = null;
$semester_id    = null;
$institution_id = null;
$assumeUsers    = true;

$error          =   null;
$object = file_get_contents("php://input");
$data = json_decode($object, true);
if (is_array($data)) {
    foreach ($data as $key => $value){
        $$key = $value;
    }
}
            
if (isset($func)){
    switch ($func) {
        case 'new':      checkCapabilities('groups:add',         $USER->role_id);
                         $header            = 'Lerngruppe hinzufügen';
                         $add               = true;
        break;
        case 'semester': checkCapabilities('groups:changeSemester',         $USER->role_id);
                         $header            = 'Lernzeitraum ändern';
                         $change_semester   = true;
                         $group_id          = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);  // edit case: id == ena_id
            break;
        case "edit":     checkCapabilities('groups:update', $USER->role_id);
                         $header            = 'Lerngruppe bearbeiten';
                         $edit              = true;  
                         $gr_obj            = new Group();
                         $gr_obj->id        = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);  // edit case: id == ena_id
                         $group_id          = $gr_obj->id;
                         $gr_obj->load();                                 //Läd die bestehenden Daten aus der db
                         foreach ($gr_obj as $key => $value){
                             $$key = $value;
                             //error_log($key. ': '.$value);
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

$html ='<div class="modal-dialog" style="overflow-y: initial !important;">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closePopup()"><span aria-hidden="true">×</span></button>
              <h4 class="modal-title">'.$header.'</h4>
            </div>
            <div class="modal-body" style="max-height: 500px; overflow-y: auto;">';
$html .='<form id="form_group"  class="form-horizontal" role="form" method="post" action="../share/processors/fp_group.php"';

if (isset($currentUrlId)){ $html .= $currentUrlId; }
$html .= '"><input type="hidden" name="group_id" id="group_id" value="'.$group_id.'"/>
            <input type="hidden" name="func" id="func" value="'.$func.'"/>';

$html .= Form::input_text('group', 'Lerngruppe', $group, $error);
$html .= Form::input_text('description', 'Beschreibung', $description, $error);
$grades                     = new Grade();                                      //Load Grades
$grades->institution_id     = $USER->institutions; 
$html .= Form::input_select('grade_id', 'Klassenstufe', $grades->getGrades(), 'grade', 'id', $grade_id , $error);
$semesters                  = new Semester();                                   //Load Semesters
$semesters->institution_id  = $USER->institutions; 
$html .= Form::input_select('semester_id', 'Lernzeitraum', $semesters->getSemesters(), 'semester', 'id', $semester_id , $error);
$html .= Form::input_select('institution_id', 'Institution', $USER->institutions, 'institution', 'institution_id', $institution_id , $error);
if (isset($change_semester)){
    $html .= Form::info('p_group', ' ', 'Um eine leere Lerngruppe zu erstellen, Haken entfernen.');
    $html .= Form::input_checkbox('assumeUsers', 'Personen übernehmen', $assumeUsers , $error);
}
$html .= '</div><!-- /.modal-body -->
          <div class="modal-footer">';
          if (isset($edit)){
              $html .= '<button name="update" type="submit" class="btn btn-primary glyphicon glyphicon-saved pull-right" onclick="document.getElementById(\'form_group\').submit();"> '.$header.'</button>'; 
          } 
          if (isset($change_semester)){
              $html .= '<button id="change" name="change" type="submit" class="btn btn-primary glyphicon glyphicon-ok pull-right" onclick="document.getElementById(\'form_group\').submit();"> '.$header.'</button> ';
          }    
          if (isset($add)){
              $html .= '<button id="add" name="add" type="submit" class="btn btn-primary glyphicon glyphicon-ok pull-right" onclick="document.getElementById(\'form_group\').submit();"> '.$header.'</button> ';
          }    
$html .=  '</div></form></div><!-- /.modal-content -->
           </div><!-- /.modal-dialog -->';

echo json_encode(array('html'=>$html));