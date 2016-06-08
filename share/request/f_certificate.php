<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package core
 * @filename f_certificate.php
 * @copyright 2016 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2016.05.26 17:44
 * @license: 
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by  
 * the Free Software Foundation; either version 3 of the License, or (at your option) any later version.
 *                                                                       
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of        
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details:
 *                                                                       
 * http://www.gnu.org/copyleft/gpl.html      
 */
$base_url   = dirname(__FILE__).'/../';
include($base_url.'setup.php');  //Läd Klassen, DB Zugriff und Funktionen
include(dirname(__FILE__).'/../login-check.php');  //check login status and reset idletimer
global $CFG, $USER, $COURSE;
$USER           = $_SESSION['USER'];


/*Variablen anlegen -> vermeidet unnötige if-Abfragen im Formular*/
$cert_id           = null;
$certificate       = null; 
$description       = null;
$institution_id    = null;
$template          = null;

$func              = $_GET['func'];

$error             =   null;
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
        case "new":         checkCapabilities('certificate:add',    $USER->role_id);
                            $header     = 'Zertifikat hinzufügen';
                            $add        = true;              
            break;
        case "edit":        checkCapabilities('certificate:update', $USER->role_id);
                            $header     = 'Zertifikat aktualisieren';
                            $edit       = true; 
                            
                            $cert       = new Certificate();
                            $cert->id   = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
                            $cert->load(); 
                            $cert_id   = $cert->id;
                            foreach ($cert as $key => $value){
                                if (!is_object($value)){
                                    $$key = $value;
                                    //error_log($key. ': '.$value);
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

$html ='<div class="modal-dialog" style="overflow-y: initial !important;">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closePopup()"><span aria-hidden="true">×</span></button>
              <h4 class="modal-title">'.$header.'</h4>
            </div>
            <div class="modal-body" style="max-height: 500px; overflow-y: auto;">';
   
$html .='<form id="form_certificate"  class="form-horizontal" role="form" method="post" action="../share/processors/fp_certificate.php"';

if (isset($currentUrlId)){ $html .= $currentUrlId; }
$html .= '">
<input type="hidden" name="func" id="func" value="'.$func.'"/>';
if (isset($cert_id)){
$html .= '<input type="hidden" name="cert_id" id="cert_id" value="'.$cert_id.'"/> ';
}
if (isset($reference_id)){
$html .= '<input type="hidden" name="reference_id" id="reference_id" value="'.$reference_id.'"/> ';
}
$html .= Form::input_text('certificate', 'Zertifikat', $certificate, $error, 'z. B. MedienkomP@ss Zertifikat');
$html .= Form::input_text('description', 'Beschreibung', $description, $error, 'Beschreibung');
$html .= Form::input_select('institution_id', 'Institution', $USER->institutions, 'institution', 'institution_id', $institution_id , $error);
$html .= Form::input_textarea('template', 'Zertifikat-Vorlage', $template, $error);
$html .= Form::info('info', 'Felder:', '*&lt;!--Vorname--&gt;, *&lt;!--Nachname--&gt;</br> 
                                            *&lt;!--Start--&gt;, *&lt;!--Ende--&gt</br>
                                             &lt;!--Ort--&gt;, &lt;!--Datum--&gt;, &lt;!--Unterschrift--&gt;</br>
                                             &lt;!--Thema--&gt;, &lt;!--Ziel--&gt;</br>
                                             &lt;!--Ziel_mit_Hilfe_erreicht--&gt;,  &lt;!--Ziel_erreicht--&gt;, &lt;!--Ziel_offen--&gt;</br>
                                             &lt;ziel status="[1]" class="[objective_green row]" &gt;&lt;/ziel&gt;</br>
                                             &lt;!--Bereich{terminal_objective_id,...}--&gt;HTML&lt;!--/Bereich--&gt;');

$html       .= '</div><!-- /.modal-body -->
            <div class="modal-footer">';
            if (isset($edit)){
                $html .= '<button name="update" type="submit" class="btn btn-primary glyphicon glyphicon-saved pull-right" onclick="document.getElementById(\'form_certificate\').submit();"> '.$header.'</button>'; 
            } 
            if (isset($add)){
                $html .= '<button id="add" name="add" type="submit" class="btn btn-primary glyphicon glyphicon-ok pull-right" onclick="document.getElementById(\'form_certificate\').submit();"> '.$header.'</button> ';
            }    
$html .=  '</div></form></div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->';

echo json_encode(array('html'=>$html));