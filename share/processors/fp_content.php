<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* FormProcessor
* @package core
* @filename fp_content.php
* @copyright 2016 Joachim Dieterich
* @author Joachim Dieterich
* @date 2016.11.17 14:50
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
include_once(dirname(__FILE__).'/../setup.php');  // Klassen, DB Zugriff und Funktionen
include_once(dirname(__FILE__).'/../login-check.php');  //check login status and reset idletimer
global $USER, $CFG;
$USER   = $_SESSION['USER'];
if (!isset($_SESSION['PAGE']->target_url)){     //if target_url is not set -> use last PAGE url
    $_SESSION['PAGE']->target_url       = $_SESSION['PAGE']->url;
}
$content                = new Content();
$purify = HTMLPurifier_Config::createDefault();
$purify->set('Attr.EnableID', true);
$purify->set('Core.Encoding', 'UTF-8'); // replace with your encoding
$purify->set('HTML.Doctype', 'HTML 4.01 Transitional'); // replace with your doctype
$purify->set('HTML.DefinitionID', '1'); //enable Quote tag DO NOT CHANGE
$purify->set('Cache.DefinitionImpl', null); // TODO: remove this later!
$purify->set('HTML.DefinitionRev', 1);
// allow name and id attributes

$def = $purify->maybeGetRawHTMLDefinition();
/* http://htmlpurifier.org/docs/enduser-customize.html */
$def->addElement('quote', 'Block', 'Optional:Flow', 'Common', array('id' => 'Text')); //DO NOT CHANGE!! 

$purifier               = new HTMLPurifier($purify);
//error_log(filter_input(INPUT_POST, 'content', FILTER_UNSAFE_RAW));
//$content->content       = $purifier->purify(filter_input(INPUT_POST, 'content', FILTER_UNSAFE_RAW));
//TODO
$content->content       = filter_input(INPUT_POST, 'content', FILTER_UNSAFE_RAW);//$purifier->purify(filter_input(INPUT_POST, 'content', FILTER_UNSAFE_RAW));
///error_log($content->content);

$gump = new Gump();    /* Validation */
$_POST = $gump->sanitize($_POST);       //sanitize $_POST
$content->context_id    = $_POST['context_id'];
if (isset($_POST['file_context'])){
    $content->file_context  = $_POST['file_context'];
} else {
    $content->file_context  = 4; // if file_context is not set, file_context == private (4)
}
$content->reference_id  = $_POST['reference_id'];
$content->title         = $_POST['title'];

$gump->validation_rules(array(
'title' => 'required',
'content' => 'required'
));
$validated_data = $gump->run($_POST);
if($validated_data === false) {/* validation failed */
    $_SESSION['FORM']            = new stdClass();
    $_SESSION['FORM']->form      = 'content';
    foreach($content as $key => $value){
        $_SESSION['FORM']->$key  = $value;
    } 
    if (isset($_POST['id'])){
        $_SESSION['FORM']->id = $_POST['id'];
    }
    $_SESSION['FORM']->error     = $gump->get_readable_errors();
    $_SESSION['FORM']->func      = $_POST['func'];
} else {
    if ($_POST['func'] == 'edit'){
        $content->id         = $_POST['id'];
        checkCapabilities('content:update', $USER->role_id); //has to be done here --> content class is used in wallet with permission wallet:update
        $content->update();
    }  else {
        checkCapabilities('content:add', $USER->role_id);   //has to be done here --> content class is used in wallet with permission wallet:add
        $content->add(); 
    }
    switch ($content->context_id) {
        case 4: 
        case 12:    $_SESSION['anchor'] = 'ena_'.$content->reference_id; // set anchor to jump to new enabling objective
            break;
        case 27:    $_SESSION['anchor'] = 'ter_'.$content->reference_id; // set anchor to jump to new terminal objective


            break;

        default:
            break;
    }
    $_SESSION['FORM']            = null;                     // reset Session Form object 
}

header('Location:'.$_SESSION['PAGE']->target_url);