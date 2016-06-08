<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package core
 * @filename f_grade.php
 * @copyright 2016 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2016.05.28 21:28
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

$grade_id       = null;
$grade          = null;
$description    = null;
$institution_id = null;
$error          = null; 
$grade_obj      = new Grade(); 
$func           = $_GET['func'];

switch ($func) {
    case 'edit':    $grade_obj->id     = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);  // edit case: id == ena_id
                    $grade_id          = $grade_obj->id;
                    $grade_obj->load();                                 //Läd die bestehenden Daten aus der db
                    foreach ($grade_obj as $key => $value){
                        $$key = $value;
                    }
                    $header                       = 'Klassenstufe aktualisieren';           
        break;
    case 'new':     $header                       = 'Klassenstufe hinzufügen';
                    
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

$content = '<form id="form_grade" method="post" action="../share/processors/fp_grade.php">
 <div class="form-horizontal"><div class="form-group">   
<input type="hidden" name="grade_id" id="grade_id" value="'.$grade_id.'"/>
<input type="hidden" name="func" id="func" value="'.$func.'"/>'; 
$content .= Form::input_text('grade', 'Klassenstufe', $grade, $error, 'z. B. 7. Klasse');
$content .= Form::input_text('description', 'Beschreibung', $description, $error, 'Beschreibung');
$content .= Form::input_select('institution_id', 'Institution', $USER->institutions, 'institution', 'institution_id', $institution_id , $error);
$f_content = '';
if ($func == 'edit'){ 
    $f_content .= '<button type="submit" class="btn btn-primary fa fa-check-circle-o pull-right" onclick="document.getElementById(\'form_grade\').submit();"> '.$header.'</button>';
} else {
    $f_content .= '<button type="submit" class="btn btn-primary fa fa-plus pull-right" onclick="document.getElementById(\'form_grade\').submit();"> '.$header.'</button>';
}
$content .= '</div></div></form>';
$html     = Form::modal(array('title'     => $header,
                              'content'   => $content, 
                              'f_content' => $f_content));  

echo json_encode(array('html'=>$html));