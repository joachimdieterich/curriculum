<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename f_reference.php
* @copyright 2017 Joachim Dieterich
* @author Joachim Dieterich
* @date 2017.09.09 13:45
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
global $CFG, $USER,$TEMPLATE;
$USER           = $_SESSION['USER'];
$func           = $_GET['func'];
/*Variablen anlegen -> vermeidet unnötige if-Abfragen im Formular*/
$id             = null; 
$title          = null; 
$description    = null; 
$curriculum_id  = null; 
$context        = null; 
$terminal_objective_id = null;
$enabling_objective_id = null;
$error          = null;
$object         = file_get_contents("php://input");
$data           = json_decode($object, true);
if (is_array($data)) {
    foreach ($data as $key => $value){
        $$key = $value;
    }
}

$cur        = new Curriculum();
$curriculum = $cur->getCurricula('user', $USER->id);
$ter        = new TerminalObjective();
$ena        = new EnablingObjective();
if (isset($_GET['func'])){
    switch ($_GET['func']) {
        case "new":     checkCapabilities('reference:add',    $USER->role_id, false, true);
                        $header = 'Referenz verknüpfen';            
                        $context_id     = $_SESSION['CONTEXT'][filter_input(INPUT_GET, 'context', FILTER_SANITIZE_STRING)]->context_id;
                        $reference_id   = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
                        switch (filter_input(INPUT_GET, 'context', FILTER_SANITIZE_STRING)) {
                            case 'enabling_objective':  $obj        = new EnablingObjective();
                                                        $obj->id    = $reference_id;
                                                        $obj->load();
                                                        $type       = 'enabling_objective';
                                break;
                            case 'terminal_objective':  $obj        = new TerminalObjective();
                                                        $obj->id    = $reference_id;
                                                        $obj->load();
                                                        $type       = 'terminal_objective';
                                break;

                            default:
                                break;
                        }
                        
                        $cur->id    = $obj->curriculum_id;
                        $cur->load();
                        $grade_id   = $cur->grade_id;
                        $curriculum_id = $curriculum[0]->id; 

                        $ter->curriculum_id = $curriculum_id;
                        $terminal_objectives = $ter->getObjectives('curriculum', $curriculum_id);
                        
                        $ena->curriculum_id  = $curriculum_id;
                        $enabling_objectives = $ena->getObjectives('curriculum', $curriculum_id);
            break;
            case "edit":    checkCapabilities('reference:update',    $USER->role_id, false, true);
                        $context_id     = filter_input(INPUT_GET, 'context_id', FILTER_VALIDATE_INT); 
                        $reference_id   = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT); 
                        $header   = 'Referenz bearbeiten';
                        $edit     = true; 
                        $id       = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
                        $r        = new Reference();
                        $r->load('id', $id);
                        $curriculum_id          = $r->curriculum_object->id;
                        $cur->id    = $curriculum_id;
                        $cur->load();
                        $terminal_objective_id  = $r->terminal_object->id;
                        $terminal_objectives = $ter->getObjectives('curriculum', $curriculum_id);
                        $enabling_objective_id  = $r->enabling_object->id;
                        $enabling_objectives = $ena->getObjectives('terminal_objective', $terminal_objective_id);
                        
                        $grade_id               = $r->grade_id;
                        $description            = $r->content_object->content;
                        $context                = $r->context_id;
                        

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

$content ='<form id="form_reference" class="form-horizontal" role="form" method="post" action="../share/processors/fp_reference.php"';
if (isset($currentUrlId)){ $content .= $currentUrlId; }
$content .= '"><input type="hidden" name="func" id="func" value="'.$func.'"/>';
$content .= '<input id="context_id" name="context_id" type="text" class="invisible" value="'.$context_id.'">
             <input id="reference_id" name="reference_id" type="text" class="invisible" value="'.$reference_id.'">';
if (isset($id)) {                                                               // only set id input field if set! prevents error on validation form reload
    $content .= '<input id="id" name="id" type="text" class="invisible" value="'.$id.'">';
}

switch ($type) {
    case 'enabling_objective': $type_text = "der Kompetenz";
        break;
    case 'terminal_objective': $type_text = "dem Thema bzw. Kompetenzbereich"; 
        break;
}
$content     .= Form::info(array('id' => 'ref_info', 'content' => 'Bezug mit '.$type_text.' herstellen. <strong>'.$obj->$type.'</strong>'));

if ($id == null) {
    $curriculum_id = $curriculum[0]->id;        
    $content .= Form::input_select('curriculum_id', 'Lehrplan', $curriculum, 'curriculum', 'id', $curriculum_id , $error, 'getMultipleValues([\'objectives\', this.value, \'terminal_objective_id\', \'terminal_objective\'], [\'objectives\', this.value, \'enabling_objective_id\', \'enabling_objective_from_curriculum\']);');//
} else {
    $content .= Form::input_select('curriculum_id', 'Lehrplan', $curriculum, 'curriculum', 'id', $curriculum_id , $error, '','', 'col-sm-3', 'col-sm-9', 'disabled="disabled"');
}


$content .= Form::input_select('terminal_objective_id', 'Thema / Kompetenzbereich', $terminal_objectives, 'terminal_objective', 'id', $terminal_objective_id , $error, 'getValues(\'objectives\', this.value, \'enabling_objective_id\', \'enabling_objective_from_terminal_objective\');');
$content .= Form::input_select_multiple(array('id' => 'enabling_objective_id', 'label' => 'Kompetenzen', 'select_data' => $enabling_objectives, 'select_label' => 'enabling_objective', 'select_value' => 'id', 'input' => array($enabling_objective_id), 'error' => $error)); 

$grades   = new Grade();    //Load Grades
$content .= Form::info(array('id' => 'grade_info', 'content' => 'Im folgendenden Feld kann falls nötig die Klassenstufe präzisiert werden.'));
$content .= Form::input_select('grade_id', 'Klassenstufe', $grades->getGrades('institution',$USER->institution_id), 'grade, institution', 'id', $grade_id , $error);
$content .= Form::info(array('id' => 'grade_info', 'content' => 'Hier können Anregungen zur Umsetzung eingetragen werden.'));
$content .= Form::input_textarea('description', 'Hinweise', $description, $error, '');

$c        = new Context();
$content .=  Form::input_select('file_context', 'Sichtbarkeit', $c->get(), 'description', 'id', $context , $error);

$content .= '</form>';
$footer   = '<button type="submit" class="btn btn-primary pull-right" onclick="document.getElementById(\'form_reference\').submit();"><i class="fa fa-floppy-o margin-r-5"></i>'.$header.'</button>'; 
$html     = Form::modal(array('title'     => $header,
                              'content'   => $content, 
                              'f_content' => $footer));

echo json_encode(array('html' => $html));