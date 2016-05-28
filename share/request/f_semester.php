<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package core
 * @filename f_semester.php
 * @copyright 2016 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2016.05.15 20:22
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

global $USER, $CFG;
$USER                   = $_SESSION['USER'];

$semester_id     = '';
$semester        = '';
$description     = '';
$error           = '';
$institution_id  = '';
$timerange       = '';
$sem_obj         = new Semester(); 
$func            = $_GET['func'];

switch ($func) {
    case 'edit':    $sem_obj->id        = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);  // edit case: id == ena_id
                    $semester_id        = $sem_obj->id;
                    $sem_obj->load();                                 //Läd die bestehenden Daten aus der db
                    foreach ($sem_obj as $key => $value){
                        $$key = $value;
                        //error_log($key. ': '.$value);
                    }
                    $header                       = 'Lernzeitraum aktualisieren';           
        break;
    case 'new':     $header                       = 'Lernzeitraum hinzufügen';
        break;
    
    default:
        break;
}
/* if validation failed */
if (is_object($_SESSION['FORM'])) {
    foreach ($_SESSION['FORM'] as $key => $value){
        $$key = $value;
    }
}

$content = '<form id="form_semester" method="post" action="../share/processors/fp_semester.php">
 <div class="form-horizontal"><div class="form-group">   
<input type="hidden" name="semester_id" id="semester_id" value="'.$semester_id.'"/>
<input type="hidden" name="func" id="func" value="'.$func.'"/>'; 
$content .= Form::input_text('semester', 'Lernzeitrum', $semester, $error, 'z. B. Schuljahr 2015/16');
$content .= Form::input_text('description', 'Beschreibung', $description, $error, 'Beschreibung');
$content .= Form::input_date(array('id'=>'timerange', 'label' => 'Dauer' , 'time' => $timerange, 'error' => $error, 'placeholder' => '', $type = 'date'));
$content .= Form::input_select('institution_id', 'Institution', $USER->institutions, 'institution', 'institution_id', $institution_id , $error);
$f_content = '';
if ($func == 'edit'){ 
    $f_content .= '<button type="submit" class="btn btn-primary fa fa-check-circle-o pull-right" onclick="document.getElementById(\'form_semester\').submit();"> '.$header.'</button>';
} else {
    $f_content .= '<button type="submit" class="btn btn-primary fa fa-plus pull-right" onclick="document.getElementById(\'form_semester\').submit();"> '.$header.'</button>';
}
$content .= '</div></div></form>';
$html     = Form::modal(array('title'     => $header,
                              'content'   => $content, 
                              'f_content' => $f_content));  

$script = "<!-- daterangepicker -->
        <script id='modal_script'>
        $.getScript('".$CFG->base_url ."public/assets/templates/AdminLTE-2.3.0/plugins/daterangepicker/daterangepicker.js', function (){
        $('.datepicker').daterangepicker({timePicker: true, timePickerIncrement: 1, timePicker24Hour: true, locale: {format: 'DD.MM.YYYY HH:mm'}});
        });</script>";
echo json_encode(array('html'=>$html, 'script'=> $script));