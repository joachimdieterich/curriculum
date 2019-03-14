<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename f_generate_certificate.php
* @copyright 2017 Joachim Dieterich
* @author Joachim Dieterich
* @date 2017.05.27 12:35 
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


/*Variablen anlegen -> vermeidet unnötige if-Abfragen im Formular*/
$certificate_id    = null;
$curriculum_id     = null;
$deliver           = null;
$func              = $_GET['func'];
$error             = null;
$object            = file_get_contents("php://input");
$data              = json_decode($object, true);
if (is_array($data)) {
    foreach ($data as $key => $value){
        $$key = $value;
    }
}
            
if (isset($func)){
    switch ($func) {
        default:    $header                      = 'Lernstand bzw. Zertifikat drucken';
                    $certificate                 = new Certificate();                               // Load certificate_templates
                    $certificate->institution_id = $USER->institutions;
                    $certificate->curriculum_id  = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
                    $certificates                = $certificate->getCertificates();     
            break;
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

$content   ='<form id="form_generate_certificate" class="form-horizontal" role="form" method="post" action="../share/processors/fp_generate_certificate.php">
             <input type="hidden" name="func" id="func" value="'.$func.'"/>';
$content  .= '<input type="hidden" name="curriculum_id" id="curriculum_id" value="'.$certificate->curriculum_id.'"/> ';
$content  .= Form::input_select('certificate_id', 'Druckvorlage', $certificates , 'certificate, institution', 'id', $certificate_id , $error);
$content  .= Form::input_text('date', 'Ausgabedatum', date("d.m.Y"), $error, 'z. B. 22.01.2017');
$content  .= Form::info(array('id' => 'info', 'label' => 'Hinweis', 'content' => 'Wen Sie die folgende Checkbox aktivieren, wird das Zertifikat  dem Schüler / Student zusätzlich im PDF bereitgestellt und kann von diesem heruntergeladen werden.'));
$content  .= Form::input_checkbox('deliver', 'Datei für Lerner bereitstellen?', $deliver, $error);
$content  .= '</form>';
$footer    = '<button type="submit" class="btn btn-primary pull-right" onclick="document.getElementById(\'form_generate_certificate\').submit();closePopup(\'null\');"><i class="fa fa-floppy-o margin-r-5"></i>'.$header.'</button>'; 
   
$html      = Form::modal(array('title'     => $header,
                              'content'   => $content, 
                              'f_content' => $footer));

echo json_encode(array('html'=>$html));