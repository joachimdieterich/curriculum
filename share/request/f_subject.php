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
$base_url        = dirname(__FILE__).'/../';
include($base_url.'setup.php');  //Läd Klassen, DB Zugriff und Funktionen
include_once(dirname(__FILE__).'/../login-check.php');  //check login status and reset idletimer
global $USER, $CFG;
$USER            = $_SESSION['USER'];

$subject_id      = '';
$subject         = '';
$subject_short   = '';
$description     = '';
$error           = '';
$institution_id  = '';
$sub_obj         = new Subject(); 
$func            = $_GET['func'];

switch ($func) {
    case 'edit':    $sub_obj->id       = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);  // edit case: id == ena_id
                    $subject_id        = $sub_obj->id;
                    $sub_obj->load();                                 //Läd die bestehenden Daten aus der db
                    foreach ($sub_obj as $key => $value){
                        $$key = $value;
                    }
                    $header            = 'Fach aktualisieren';           
        break;
    case 'new':     $header            = 'Fach hinzufügen';
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
<input type="hidden" name="subject_id" id="subject_id" value="'.$subject_id.'"/>
<input type="hidden" name="func" id="func" value="'.$func.'"/>'; 
$content .= Form::input_text('subject', 'Fach', $subject, $error, 'z. B. Mathematik');
$content .= Form::input_text('subject_short', 'Kürzel', $subject_short, $error, 'z. B. MA');
$content .= Form::input_text('description', 'Beschreibung', $description, $error, 'Beschreibung');
$institutions       = $USER->institutions;
if(checkCapabilities('curriculum:addglobalentries', $USER->role_id, false)){ // set for global ADMIN!
    $ins                 = new stdClass();
    $ins->institution_id = 0; 
    $ins->institution    = 'globales Fach';
    $institutions[]      = $ins;
}
$content .= Form::input_select('institution_id', 'Institution', $institutions, 'institution', 'institution_id', $institution_id , $error);

$content .= '</form>';
$footer   = '<button type="submit" class="btn btn-primary pull-right" onclick="document.getElementById(\'form_subject\').submit();"><i class="fa fa-floppy-o margin-r-5"></i>'.$header.'</button>';

$html     = Form::modal(array('title'     => $header,
                              'content'   => $content, 
                              'f_content' => $footer));  

echo json_encode(array('html'=>$html));