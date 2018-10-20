<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package plugins
* @filename f_add_reference.php
* @copyright 2018 Joachim Dieterich
* @author Joachim Dieterich
* @date 2018.10.08 10:22
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
$base_url  = '../../../../';
include($base_url.'setup.php');  //Läd Klassen, DB Zugriff und Funktionen
include($base_url.'login-check.php');  //check login status and reset idletimer
global $USER, $CFG;

$USER   = $_SESSION['USER'];
$func   = $_GET['func'];
$header = 'Edusharing Object verknüpfen';
$content_type = 'FILES';
$property     = 'ccm:competence_digital2';
$value        = '';
$context      = 1;
$reference_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$error  = null;
if (isset($func)){
    switch ($func) {
        case 'terminal_objective':  $t                          = new TerminalObjective();
                                    $t->id                      = $reference_id;
                                    $t->load();
                                    $file_context_reference_id  = $t->curriculum_id;
                                    
            break;          
        case 'enabling_objective':  $e                          = new EnablingObjective();
                                    $e->id                      = $reference_id;
                                    $e->load();
                                    $file_context_reference_id  = $e->curriculum_id;
            break;

        default:
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

switch ($context) {
    case 1:     $c              = new Curriculum();
                $c->id          = $file_context_reference_id;
                $c->load();
                $file_context_reference = array($c);
                $select_label   = 'curriculum';
                
        break;
    case 2:     //todo
        break;
    case 3:     //todo
        break;  
    case 4:     //todo
        break;

    default:
        break;
}
//* Load webservice form from plugin*/
$content   ='<form id="form_plugin_repository_add_reference" class="form-horizontal" role="form" method="post" action="../share/plugins/repository/edusharing/processors/fp_add_reference.php"';
if (isset($currentUrlId)){ $content .= $currentUrlId; }
$content  .= '"><input type="hidden" name="func" id="func" value="'.$func.'"/>';
if (isset($reference_id)){
$content  .= '<input type="hidden" name="reference_id" id="reference_id" value="'.$reference_id.'"/> ';
}
$content   .= Form::info(array('id' => '', 'content' => 'Bitte gewünschte Linkparameter eingeben:'));
/* Type selector*/
$content_type_obj = generate_select_object( array('Dateien'                 => 'FILES',
                                                'Ordner'                    => 'FOLDERS',
                                                'Dateien und Ordner'        => 'FOLDERS',
                                                'Sammlungen'                => 'COLLECTIONS',
                                                'Werkzeugberechtigungen?'   => 'TOOLSPERMISSION',
                                                'Alle'                      => 'ALL'));
$content .= Form::input_select('content_type', 'Objekttyp', $content_type_obj, 'label', 'value', $content_type , $error);
$content .= Form::input_text('property', 'Suchbereich', $property, $error);
$content .= Form::input_text('value', 'Edusharing-ID (Suchbereich)', $value, $error, 'z.B. 11990503');
$ct = new Context();
$content .= Form::input_select('file_context', 'Freigabe-Level', $ct->get(), 'description', 'id', $context , $error, 'getValues(\'file_context_reference\', this.value, \'file_context_reference_id\');');
$content .= Form::input_select_multiple(array('id' => 'file_context_reference_id', 'label' => 'Freigabe-Referenz', 'select_data' => $file_context_reference, 'select_label' => $select_label, 'select_value' => 'id', 'input' => array($file_context_reference_id), 'error' => $error)); 

$content .= '</form>';
$footer   = '<button type="submit" class="btn btn-primary pull-right" onclick="document.getElementById(\'form_plugin_repository_add_reference\').submit();"><i class="fa fa-floppy-o margin-r-5"></i>'.$header.'</button>'; 

$html      = Form::modal(array('title' => $header,
                          'content'   => $content, 
                          'f_content' => $footer));  

echo json_encode(array('html'=> $html));