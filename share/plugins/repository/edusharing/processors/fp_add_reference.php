<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* FormProcessor
* @package plugin
* @filename fp_add_reference.php
* @copyright 2018 Joachim Dieterich
* @author Joachim Dieterich
* @date 2018.10.08 11:15
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
if (!isset($_SESSION['PAGE']->target_url)){     //if target_url is not set -> use last PAGE url
    $_SESSION['PAGE']->target_url       = $_SESSION['PAGE']->url;
}

$gump           = new Gump();    /* Validation */
$_POST          = $gump->sanitize($_POST);       //sanitize $_POST
$context_id     = $_SESSION['CONTEXT'][$_POST['func']]->id;

$reference_id               = $_POST['reference_id'];
$content_type               = $_POST['content_type'];
$property                   = $_POST['property'];
$value                      = $_POST['value'];
$file_context               = $_POST['file_context'];       
$file_context_reference_id  = $_POST['file_context_reference_id'];       

$gump->validation_rules(array(
'func'                      => 'required',
'reference_id'              => 'required',
'content_type'              => 'required',
'property'                  => 'required',
'value'                     => 'required',
'file_context'              => 'required', 
'file_context_reference_id' => 'required'
));
$validated_data = $gump->run($_POST);
if($validated_data === false) {/* validation failed */
    $_SESSION['FORM']                               = new stdClass();
    $_SESSION['FORM']->form                         = 'plugin_repository_add_reference';
    
    $_SESSION['FORM']->context_id                   = $context_id;
    $_SESSION['FORM']->reference_id                 = $reference_id;
    $_SESSION['FORM']->content_type                 = $_POST['content_type'];
    $_SESSION['FORM']->property                     = $_POST['property'];
    $_SESSION['FORM']->value                        = $_POST['value'];
    $_SESSION['FORM']->file_context                 = $_POST['file_context'];
    $_SESSION['FORM']->file_context_reference_id    = $_POST['file_context_reference_id'];
    
    $_SESSION['FORM']->error                        = $gump->get_readable_errors();
    $_SESSION['FORM']->func                         = $_POST['func'];
} else {
        $edu  = new repository_plugin_edusharing();
        $edu->set_link_to_curriculum_db($context_id, $reference_id, $content_type, $property, $value, $file_context, $file_context_reference_id);
        $_SESSION['PAGE']->message[] = array('message' => 'Edusharing-Objekt verknüpft', 'icon' => 'fa-external-link-square text-success');
    
    $_SESSION['FORM']            = null;                     // reset Session Form object 
}

header('Location:'.$_SESSION['PAGE']->target_url);