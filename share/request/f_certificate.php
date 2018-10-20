<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename f_certificate.php
* @copyright 2016 Joachim Dieterich
* @author Joachim Dieterich
* @date 2016.05.26 17:44: 
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
$cert_id           = null;
$certificate       = null; 
$description       = null;
$institution_id    = null;
$curriculum_id     = null;
$template          = null;
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
        case "coursebook":  $reference_id =  filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        case "new":         checkCapabilities('certificate:add',    $USER->role_id, false, true);
                            $header     = 'Zertifikat hinzufügen';
            break;
        case "edit":        checkCapabilities('certificate:update', $USER->role_id, false, true);
                            $header     = 'Zertifikat aktualisieren';
                            $cert       = new Certificate();
                            $cert->id   = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
                            $cert->load(); 
                            $cert_id   = $cert->id;
                            foreach ($cert as $key => $value){
                                if (!is_object($value)){
                                    $$key = $value;
                                }
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

$content   ='<form id="form_certificate"  class="form-horizontal" role="form" method="post" action="../share/processors/fp_certificate.php"';
if (isset($currentUrlId)){ $content .= $currentUrlId; }
$content  .= '"><input type="hidden" name="func" id="func" value="'.$func.'"/>';
if (isset($cert_id)){
$content  .= '<input type="hidden" name="cert_id" id="cert_id" value="'.$cert_id.'"/> ';
}
if (isset($reference_id)){
$content  .= '<input type="hidden" name="reference_id" id="reference_id" value="'.$reference_id.'"/> ';
}
$content  .= Form::input_text('certificate', 'Zertifikat', $certificate, $error, 'z. B. MedienkomP@ss Zertifikat');
$content  .= Form::input_text('description', 'Beschreibung', $description, $error, 'Beschreibung');
$content  .= Form::input_select('institution_id', 'Institution', $USER->institutions, 'institution', 'institution_id', $institution_id , $error);
$curriculum = new Curriculum();
$cur_array              = $curriculum->getCurricula('user', $USER->id, 'curriculumP');
$cur_global             = new Curriculum();
$cur_global->id         = 0; // global certificate
$cur_global->curriculum = 'globales Zertifikat';
$cur_array = array_merge(array($cur_global), $cur_array); //add entry to select list 
$content  .= Form::input_select('curriculum_id', 'Lehrplan', $cur_array , 'curriculum', 'id', $curriculum_id , $error);
$content  .= Form::input_textarea('template', 'Zertifikat-Vorlage', $template, $error);
$content  .= Form::info(array('id' => 'info', 'label' => 'Felder', 'content' => '*&lt;!--Vorname--&gt;, *&lt;!--Nachname--&gt;</br> 
                                            *&lt;!--Start--&gt;, *&lt;!--Ende--&gt</br>
                                             &lt;!--Ort--&gt;, &lt;!--Datum--&gt;, &lt;!--Unterschrift--&gt;</br>
                                             &lt;!--Thema--&gt;, &lt;!--Ziel--&gt;</br>
                                             &lt;!--Ziel_mit_Hilfe_erreicht--&gt;,  &lt;!--Ziel_erreicht--&gt;, &lt;!--Ziel_offen--&gt;</br>
                                             &lt;ziel status="[1]" class="[objective_green row]" &gt;&lt;/ziel&gt;</br>
                                             &lt;!--Bereich{terminal_objective_id,...}--&gt;HTML&lt;!--/Bereich--&gt;'));
$content  .= '</form>';
$footer    = '<button type="submit" class="btn btn-primary pull-right" onclick="document.getElementById(\'form_certificate\').submit();"><i class="fa fa-floppy-o margin-r-5"></i>'.$header.'</button>'; 
   
$html      = Form::modal(array('title'     => $header,
                              'content'   => $content, 
                              'f_content' => $footer));

echo json_encode(array('html'=>$html));