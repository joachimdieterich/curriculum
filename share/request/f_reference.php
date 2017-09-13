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
include(dirname(__FILE__).'/../login-check.php');  //check login status and reset idletimer
global $CFG, $USER,$TEMPLATE;
$USER           = $_SESSION['USER'];
$func           = $_GET['func'];
/*Variablen anlegen -> vermeidet unnötige if-Abfragen im Formular*/
$id             = null; 
$title          = null; 
$description    = null; 
$curriculum_id  = null; 
$objectives     = null;
$error          = null;
$object         = file_get_contents("php://input");
$data           = json_decode($object, true);
if (is_array($data)) {
    foreach ($data as $key => $value){
        $$key = $value;
    }
}
            
if (isset($_GET['func'])){
    switch ($_GET['func']) {
        case "new":     checkCapabilities('reference:add',    $USER->role_id);
                        $header = 'Referenz verknüpfen';            
                        $context_id     = $_SESSION['CONTEXT'][filter_input(INPUT_GET, 'context', FILTER_SANITIZE_STRING)]->context_id;
                        $reference_id   = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
            break;
        case "edit":    checkCapabilities('reference:update',    $USER->role_id);
                        $header   = 'Referenz bearbeiten';
                        $edit     = true; 

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
$content     .= Form::info(array('id' => 'ref_info', 'content' => 'Hier können Querverweise zu anderen Lehrplänen gemacht werden.'));
$cur          = new Curriculum();
$curriculum   = $cur->getCurricula('user', $USER->id);

if ($id == null) {
    $curriculum_id = $curriculum[0]->id;        
    $content .= Form::input_select('curriculum_id', 'Lehrplan', $curriculum, 'curriculum', 'id', $curriculum_id , $error, 'getValues(\'objectives\', this.value, \'objective_id\');');
} else {
    $content .= Form::input_select('curriculum_id', 'Lehrplan', $curriculum, 'curriculum', 'id', $curriculum_id , $error, '','', 'col-sm-3', 'col-sm-9', 'disabled="disabled"');
}
$ena      = new EnablingObjective();
$ena->curriculum_id = $curriculum_id;
$content .= Form::input_select_multiple(array('id' => 'objective_id', 'label' => 'Kompetenzen/ Lernziele', 'select_data' => $ena->getObjectives('curriculum', $curriculum_id), 'select_label' => 'enabling_objective', 'select_value' => 'id', 'input' => $objectives, 'error' => $error)); 
$content .= '</form>';
$footer   = '<button type="submit" class="btn btn-primary pull-right" onclick="document.getElementById(\'form_reference\').submit();"><i class="fa fa-floppy-o margin-r-5"></i>'.$header.'</button>'; 
$html     = Form::modal(array('title'     => $header,
                              'content'   => $content, 
                              'f_content' => $footer));

echo json_encode(array('html' => $html));