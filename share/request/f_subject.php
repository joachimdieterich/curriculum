<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package core
 * @filename f_semester.php
 * @copyright 2016 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2016.05.15 21:33
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
global $USER, $CFG;
$USER                   = $_SESSION['USER'];

$subject_id      = '';
$subject         = '';
$subject_short   = '';
$description     = '';
$error           = '';
$institution_id  = '';
$sub_obj         = new Subject(); 
$func            = $_GET['func'];

switch ($func) {
    case 'edit':    $sub_obj->id        = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);  // edit case: id == ena_id
                    $subject_id        = $sub_obj->id;
                    $sub_obj->load();                                 //Läd die bestehenden Daten aus der db
                    foreach ($sub_obj as $key => $value){
                        $$key = $value;
                        //error_log($key. ': '.$value);
                    }
                    $header                       = 'Fach aktualisieren';           
        break;
    case 'new':     $header                       = 'Fach hinzufügen';
        break;
    
    default:
        break;
}

/* if validation failed, get formdata from session*/
if (isset($_SESSION['FORM'])){
    if (is_object($_SESSION['FORM'])) {
        foreach ($_SESSION['FORM'] as $key => $value){
            $$key = $value;
        }
    }
}

$content = '<form id="form_subject" method="post" action="../share/processors/fp_subject.php">
 <div class="form-horizontal"><div class="form-group">   
<input type="hidden" name="subject_id" id="subjectr_id" value="'.$subject_id.'"/>
<input type="hidden" name="func" id="func" value="'.$func.'"/>'; 
$content .= Form::input_text('subject', 'Fach', $subject, $error, 'z. B. Mathematik');
$content .= Form::input_text('subject_short', 'Kürzel', $subject_short, $error, 'z. B. MA');
$content .= Form::input_text('description', 'Beschreibung', $description, $error, 'Beschreibung');
$content .= Form::input_select('institution_id', 'Institution', $USER->institutions, 'institution', 'institution_id', $institution_id , $error);
$f_content = '';
if ($func == 'edit'){ 
    $f_content .= '<button type="submit" class="btn btn-primary fa fa-check-circle-o pull-right" onclick="document.getElementById(\'form_subject\').submit();"> '.$header.'</button>';
} else {
    $f_content .= '<button type="submit" class="btn btn-primary fa fa-plus pull-right" onclick="document.getElementById(\'form_subject\').submit();"> '.$header.'</button>';
}
$content .= '</div></div></form>';
$html     = Form::modal(array('title'     => $header,
                              'content'   => $content, 
                              'f_content' => $f_content));  

echo json_encode(array('html'=>$html));