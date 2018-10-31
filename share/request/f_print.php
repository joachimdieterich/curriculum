<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename f_print.php
* @copyright 2017 Joachim Dieterich
* @author Joachim Dieterich
* @date 2017.10.17 15:50
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
global $USER;
$USER               = $_SESSION['USER'];
$func               = $_GET['func'];
$error              = null; 
$context_id         = null; 
$reference_id       = null; 
$print_curriculum   = null; 
$print_content      = null; 
$print_glossar      = null;
$print_files        = null; 
$print_reference    = null;
$print_material     = null; 

$content            = '';
$id                 = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$object             = file_get_contents("php://input");
$data               = json_decode($object, true);
if (is_array($data)) {
    foreach ($data as $key => $value){
        $$key = $value;   
    }
}

switch ($func) {
    case 'curriculum': $header = 'Lehrplan drucken';
                       
        break;
   
    default:
        break;
}

$content ='<form id="form_print" class="form-horizontal" role="form" method="post" action="../share/processors/fp_print.php"';
if (isset($currentUrlId)){ $content .= $currentUrlId; }
$content .= '"><input type="hidden" name="func" id="func" value="'.$func.'"/>';
$content .= '<input id="context_id" name="context_id" type="text" class="invisible" value="'.$context_id.'">
             <input id="reference_id" name="reference_id" type="text" class="invisible" value="'.$reference_id.'">';
if (isset($id)) {                                                               // only set id input field if set! prevents error on validation form reload
    $content .= '<input id="id" name="id" type="text" class="invisible" value="'.$id.'">';
}

$content  .= Form::info(array('id' => 'info', 'label' => '', 'content' => 'Bitte wählen Sie aus, was gedruckt werden soll:'));
$content  .= Form::input_checkbox('print_curriculum', 'Lehrplanraster', $print_curriculum, $error);
$content  .= Form::input_checkbox('print_curriculum_matrix', 'Lehrplanmatrix (Anordnung wie in curriculum)', $print_curriculum, $error);
$content  .= Form::input_checkbox('print_content', 'Digitalisierte Texte des Lehrplans ', $print_content, $error);
$content  .= Form::input_checkbox('print_glossar', 'Glossar', $print_glossar, $error);
$content  .= Form::input_checkbox('print_files', 'Dateien zum Lehrplan', $print_files, $error);
$content  .= Form::input_checkbox('print_reference', 'Lehrplanbezüge', $print_reference, $error);
$content  .= Form::input_checkbox('print_material', 'Materialien', $print_material, $error);


$content .= '</form>';
$footer   = '<button type="submit" class="btn btn-primary pull-right" onclick="document.getElementById(\'form_print\').submit();closePopup(\'null\');"><i class="fa fa-floppy-o margin-r-5"></i>'.$header.'</button>'; 

$html = Form::modal(array('target'      => 'null',
                          'title'       => $header,
                          'content'     => $content, 
                          'f_content'   => $footer));

echo json_encode(array('html'=>$html));