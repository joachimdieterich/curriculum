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
global $USER, $CFG;
$USER           = $_SESSION['USER'];

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
                    extract($grade_obj);
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
        extract($_SESSION['FORM']);
    }
}

$content = '<form id="form_grade" method="post" action="../share/processors/fp_grade.php">
<input type="hidden" name="grade_id" id="grade_id" value="'.$grade_id.'"/>
<input type="hidden" name="func" id="func" value="'.$func.'"/>'; 
$content .= Form::input_text('grade', 'Klassenstufe', $grade, $error, 'z. B. 7. Klasse');
$content .= Form::input_text('description', 'Beschreibung', $description, $error, 'Beschreibung');
$institutions       = $USER->institutions;
if(checkCapabilities('curriculum:addglobalentries', $USER->role_id, false)){ // set for global ADMIN!
    $ins                 = new stdClass();
    $ins->institution_id = 0; 
    $ins->institution    = 'globale(s) Klassenstufe/Lernalter';
    $institutions[]      = $ins;
}
$content .= Form::input_select('institution_id', 'Institution', $institutions, 'institution', 'institution_id', $institution_id , $error);
$content .= '</form>';
$footer   = '<button type="submit" class="btn btn-primary pull-right" onclick="document.getElementById(\'form_grade\').submit();"><i class="fa fa-floppy-o margin-r-5"></i>'.$header.'</button>';

$html     = Form::modal(array('title'     => $header,
                              'content'   => $content, 
                              'f_content' => $footer));  

echo json_encode(array('html'=>$html));