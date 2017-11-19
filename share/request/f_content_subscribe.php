<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename f_content_subscribe.php
* @copyright 2017 Joachim Dieterich
* @author Joachim Dieterich
* @date 2017.11.19 09:26
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
$USER        = $_SESSION['USER'];

$ct          = new Content();
/*Variablen anlegen -> vermeidet unnötige if-Abfragen im Formular*/
$id          = null;
$title       = null; 
$content     = null;
$context     = null;
$creator_id  = null;
$label_save      = isset($_GET['label_save']) ? $_GET['label_save'] : 'Hinweise übernehmen';
$content_id  = null;
$footer      = '';
$options     = '';
$func        = $_GET['func'];
$error       =   null;
$object      = file_get_contents("php://input");
$data        = json_decode($object, true);
if (is_array($data)) {
    foreach ($data as $key => $value){
        $$key = $value;   
    }
}
            

if (isset($_GET['func'])){
    switch ($_GET['func']) {
        case "curriculum":  checkCapabilities('content:add',    $USER->role_id);
                            if (null !== filter_input(INPUT_GET, 'context', FILTER_SANITIZE_STRING)){
                                $context_id     = $_SESSION['CONTEXT'][filter_input(INPUT_GET, 'context', FILTER_SANITIZE_STRING)]->context_id;
                            } else {
                                $context_id     = filter_input(INPUT_GET, 'context_id', FILTER_VALIDATE_INT);
                            }
                            $reference_id   = filter_input(INPUT_GET, 'reference_id', FILTER_VALIDATE_INT);
                            $add = true; 
                            $label_header   = $label_save;
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

$html    ='<form id="form_content_subscribe"  class="form-horizontal" role="form" method="post" action="../share/processors/fp_content_subscribe.php"';
if (isset($currentUrlId)){ $html .= $currentUrlId; }
$html   .= '">
<input id="func" name="func" type="hidden" value="'.$func.'"/>
<input id="context_id" type="hidden" name="context_id" value="'.$context_id.'"/>
<input id="reference_id" type="hidden" name="reference_id" value="'.$reference_id.'"/>
<input id="id" name="id" type="text" class="invisible" ';
if (isset($id)) { $html .= 'value="'.$id.'"';} $html .= '>';
$html     .= Form::info(array('id' => 'ref_info', 'content' => 'Bitte wählen Sie den Lehrplan, aus die Hinweise übernommen werden sollen aus'));
$cur          = new Curriculum();
$curriculum   = $cur->getCurricula('user', $USER->id);
if ($id == null) {
    $curriculum_id = $curriculum[0]->id;        
    $html .= Form::input_select('curriculum_id', 'Lehrplan', $curriculum, 'curriculum', 'id', $curriculum_id , $error, 'getMultipleValues([\'content\', this.value, \'content_id\', \'content\']);');//
} else {
    $html .= Form::input_select('curriculum_id', 'Lehrplan', $curriculum, 'curriculum', 'id', $curriculum_id , $error, '','', 'col-sm-3', 'col-sm-9', 'disabled="disabled"');
}

$con      = new Content();
$html .= Form::input_select_multiple(array('id' => 'content_id', 'label' => 'Hinweise', 'select_data' => $con->get('curriculum', $curriculum_id), 'select_label' => 'title', 'select_value' =>'id', 'input' => $content_id , 'error' => $error));

$c        = new Context();
$html .=  Form::input_select('file_context', 'Sichtbarkeit', $c->get(), 'description', 'id', $context , $error);


$html   .= '</form>';
$footer .= '<button type="submit" class="btn btn-primary pull-right" onclick="document.getElementById(\'form_content_subscribe\').submit();"><i class="fa fa-floppy-o margin-r-5"></i>'.$label_save.'</button>';

$html_form   = Form::modal(array('target'    => 'null',
                                   'title'     => $label_header.$options,
                                   'content'   => $html, 
                                   'f_content' => $footer));

echo json_encode(array('html'=>$html_form));