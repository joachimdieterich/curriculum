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
$error          = null;
$object         = file_get_contents("php://input");
$data           = json_decode($object, true);
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
                         $id          = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);  // edit case: id == ena_id
            break;
        case "edit":     checkCapabilities('groups:update', $USER->role_id);
                         $header            = 'Lerngruppe bearbeiten';
                         $edit              = true;  
                         $gr_obj            = new Group();
                         $gr_obj->id        = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);  // edit case: id == ena_id
                         $gr_obj->load();                                 //Läd die bestehenden Daten aus der db
                         foreach ($gr_obj as $key => $value){
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

$content ='<form id="form_group"  class="form-horizontal" role="form" method="post" action="../share/processors/fp_group.php"';
if (isset($currentUrlId)){ $content .= $currentUrlId; }
$content .= '">';
if (isset($id)){
    $content .= '<input type="hidden" name="group_id" id="group_id" value="'.$id.'"/>';    
}
$content .= '<input type="hidden" name="func" id="func" value="'.$func.'"/>';
$content .= Form::input_text('group', 'Lerngruppe', $group, $error);
$content .= Form::input_text('description', 'Beschreibung', $description, $error);
$grades                     = new Grade();                                      //Load Grades
$grades->institution_id     = $USER->institutions; 
$content .= Form::input_select('grade_id', 'Klassenstufe', $grades->getGrades(), 'grade', 'id', $grade_id , $error);
$semesters                  = new Semester();                                   //Load Semesters
$semesters->institution_id  = $USER->institutions; 
$content .= Form::input_select('semester_id', 'Lernzeitraum', $semesters->getSemesters(), 'semester, institution', 'id', $semester_id , $error);
$content .= Form::input_select('institution_id', 'Institution', $USER->institutions, 'institution', 'institution_id', $institution_id , $error);
if (isset($change_semester)){
    $content .= Form::info('p_group', ' ', 'Um eine leere Lerngruppe zu erstellen, Haken entfernen.');
    $content .= Form::input_checkbox('assumeUsers', 'Personen übernehmen', $assumeUsers , $error);
}
$content .= '</div></form>';
$f_content = '';
if (isset($edit)){
    $f_content .= '<button name="update" type="submit" class="btn btn-primary glyphicon glyphicon-saved pull-right" onclick="document.getElementById(\'form_group\').submit();"> '.$header.'</button>'; 
} 
if (isset($change_semester)){
    $f_content .= '<button id="change" name="change" type="submit" class="btn btn-primary glyphicon glyphicon-ok pull-right" onclick="document.getElementById(\'form_group\').submit();"> '.$header.'</button> ';
}    
if (isset($add)){
    $f_content .= '<button id="add" name="add" type="submit" class="btn btn-primary glyphicon glyphicon-ok pull-right" onclick="document.getElementById(\'form_group\').submit();"> '.$header.'</button> ';
}    
$html     = Form::modal(array('title'     => $header,
                              'content'   => $content, 
                              'f_content' => $f_content));

echo json_encode(array('html'=>$html));