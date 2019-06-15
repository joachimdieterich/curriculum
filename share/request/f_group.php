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
$USER           = $_SESSION['USER'];
$func           = $_GET['func'];
$group_id       = null;
$group          = null;
$description    = null;
$grade_id       = null;
$semester_id    = null;
$institution_id = null;
$assumeUsers    = true;
$error          = null;
$object         = file_get_contents("php://input");
$data           = json_decode($object, true);
if (is_array($data)) {
    extract($data);
}
            
if (isset($func)){
    switch ($func) {
        case 'new':      checkCapabilities('groups:add',         $USER->role_id, false, true);
                         $header            = 'Lerngruppe hinzufügen';
        break;
        case 'semester': checkCapabilities('groups:changeSemester',         $USER->role_id, false, true);
                         $header            = 'Lernzeitraum ändern';
                         $change_semester   = true;
                         $id          = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);  // edit case: id == ena_id
            break;
        case "edit":     checkCapabilities('groups:update', $USER->role_id, false, true);
                         $header            = 'Lerngruppe bearbeiten';
                         $gr_obj            = new Group();
                         $gr_obj->id        = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);  // edit case: id == ena_id
                         $gr_obj->load();                                 //Läd die bestehenden Daten aus der db
                         extract($gr_obj);
            break;
        default: break;
    }
}

/* if validation failed, get formdata from session*/
if (isset($_SESSION['FORM'])){
    if (is_object($_SESSION['FORM'])) {
        extract($_SESSION['FORM']);
    }
}

$content ='<form id="form_group"  class="form-horizontal" role="form" method="post" action="../share/processors/fp_group.php">';
if (isset($id)){
    $content .= '<input type="hidden" name="group_id" id="group_id" value="'.$id.'"/>';    
}
$content .= '<input type="hidden" name="func" id="func" value="'.$func.'"/>';
$content .= Form::input_text('group', 'Lerngruppe', $group, $error);
$content .= Form::input_text('description', 'Beschreibung', $description, $error);   
if ($institution_id == null){
    $institution_id = $USER->institution_id; //get id of first institution to load proper semesterlist
}
$content .= Form::input_select('institution_id', 'Institution', $USER->institutions, 'institution', 'id', $institution_id , $error, 'getMultipleValues([\'semester\', this.value, \'semester_id\'], [\'grade\', this.value, \'grade_id\']);');

$semesters                  = new Semester();  //Load Semesters
$semesters->institution_id  = $institution_id;
$content .= Form::input_select('semester_id', 'Lernzeitraum', $semesters->getSemesters('institution',$institution_id), 'semester', 'id', $semester_id , $error);

$grades                     = new Grade();                                      //Load Grades
$grades->institution_id     = $USER->institutions; 
$content .= Form::input_select('grade_id', 'Klassenstufe', $grades->getGrades('institution',$institution_id), 'grade, institution', 'id', $grade_id , $error);
if (isset($change_semester)){
    $content .= Form::info(array('id' => 'p_group', 'content' => 'Um eine leere Lerngruppe zu erstellen, Haken entfernen.'));
    $content .= Form::input_checkbox('assumeUsers', 'Personen übernehmen', $assumeUsers , $error);
}
$content .= '</form>';
$footer   = '<button type="submit" class="btn btn-primary pull-right" onclick="document.getElementById(\'form_group\').submit();"><i class="fa fa-floppy-o margin-r-5"></i>'.$header.'</button> ';  
$html     = Form::modal(array('title'     => $header,
                              'content'   => $content, 
                              'f_content' => $footer));

echo json_encode(array('html'=>$html));