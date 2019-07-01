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
$USER            = $_SESSION['USER'];

$semester_id     = '';
$semester        = '';
$description     = '';
$error           = '';
$institution_id  = '';
$timerange       = '';
$sem_obj         = new Semester(); 
$func            = $_GET['func'];

switch ($func) {
    case 'edit':    if(checkCapabilities('curriculum:addglobalentries', $USER->role_id, true, true)){ // set for global ADMIN!
                        $sem_obj->id        = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);  // edit case: id == ena_id
                        $semester_id        = $sem_obj->id;
                        $sem_obj->load();                                 //Läd die bestehenden Daten aus der db
                        foreach ($sem_obj as $key => $value){
                            $$key = $value;
                        }
                        $header                       = 'Lernzeitraum aktualisieren';         
                    }
        break;
    case 'new':     $header                       = 'Lernzeitraum hinzufügen';
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

$content = '<form id="form_semester" method="post" action="../share/processors/fp_semester.php">
<input type="hidden" name="semester_id" id="semester_id" value="'.$semester_id.'"/>
<input type="hidden" name="func" id="func" value="'.$func.'"/>'; 
$content .= Form::input_text('semester', 'Lernzeitraum', $semester, $error, 'z. B. Schuljahr 2015/16');
$content .= Form::input_text('description', 'Beschreibung', $description, $error, 'Beschreibung');
$content .= Form::input_date(array('id'=>'timerange', 'label' => 'Dauer' , 'time' => $timerange, 'error' => $error, 'placeholder' => '', $type = 'date'));
$institutions       = $USER->institutions;
if(checkCapabilities('curriculum:addglobalentries', $USER->role_id, false)){ // set for global ADMIN!
    $ins                 = new stdClass();
    $ins->id = 0; 
    $ins->institution    = 'globaler Lernzeitraum';
    $institutions[]      = $ins;
}
$content .= Form::input_select('institution_id', 'Institution', $institutions, 'institution', 'id', $institution_id , $error);
$content .= '</form>';
$footer   = '<button type="submit" class="btn btn-primary fa fa-plus pull-right" onclick="document.getElementById(\'form_semester\').submit();"> '.$header.'</button>';
$html     = Form::modal(array('title'     => $header,
                              'content'   => $content, 
                              'f_content' => $footer));  

$script = "<!-- daterangepicker -->
        <script id='modal_script'>
        $.getScript('".$CFG->smarty_template_dir_url."plugins/daterangepicker/daterangepicker.js', function (){
        $('.datepicker').daterangepicker({timePicker: true, timePickerIncrement: 1, timePicker24Hour: true, locale: {format: 'DD.MM.YYYY HH:mm'}});
        });</script>";
echo json_encode(array('html'=>$html, 'script'=> $script));