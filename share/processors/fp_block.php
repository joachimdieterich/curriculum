<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* FormProcessor
* @package core
* @filename fp_block.php
* @copyright 2016 Joachim Dieterich
* @author Joachim Dieterich
* @date 2016.06.17 10:38
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
include(dirname(__FILE__).'/../setup.php');  // Klassen, DB Zugriff und Funktionen
include(dirname(__FILE__).'/../login-check.php');  //check login status and reset idletimer
global $USER, $CFG;
$USER                  = $_SESSION['USER'];
if (!isset($_SESSION['PAGE']->target_url)){     //if target_url is not set -> use last PAGE url
    $_SESSION['PAGE']->target_url       = $_SESSION['PAGE']->url;
}
$block = new Block();

if (isset($_POST['html_block'])){
$purify                = HTMLPurifier_Config::createDefault();
$purify->set('Core.Encoding', 'UTF-8'); // replace with your encoding
$purify->set('HTML.Doctype', 'HTML 4.01 Transitional'); // replace with your doctype
$purifier              =    new HTMLPurifier($purify);
$block->configdata     = $purifier->purify(filter_input(INPUT_POST, 'html_block', FILTER_UNSAFE_RAW));
}

$gump                  = new Gump();    /* Validation */
$_POST                 = $gump->sanitize($_POST);       //sanitize $_POST

$block->name           = $_POST['name']; 
$block->block_id       = $_POST['block_id']; 
$block->context_id     = $_POST['context_id']; 
$block->region         = $_POST['region']; 
$block->weight         = 0;//todo $_POST['weight']; 
$block->role_id        = $_POST['role_id']; 
$block->institution_id = $USER->institution_id;                                 //set institution_id to current id

if (isset($_POST['moodle_block'])){
    $block->configdata    = $_POST['moodle_block']; 
}
$gump->validation_rules(array(
'block_id'        => 'required',
'name'         => 'required'
));
$validated_data = $gump->run($_POST);
if($validated_data === false) {/* validation failed */
    $_SESSION['FORM'] = new stdClass();
    $_SESSION['FORM']->form      = 'block'; 
    foreach($block as $key => $value){
        $_SESSION['FORM']->$key  = $value;
    }
    $_SESSION['FORM']->error     = $gump->get_readable_errors(); 
    $_SESSION['FORM']->func      = $_POST['func'];
} else {
    if ($_POST['func'] == 'edit'){
        $block->id                =  $_POST['id'];
        $block->update();
        $_SESSION['PAGE']->message[] = array('message' => 'Block erfolgreich geändert', 'icon' => 'fa-pencil-square text-success');
        $_SESSION['FORM']            = null;                     // reset Session Form object 
    } else {
        $block->context_id = 11;    //todo --> selector to use more contexts
        $block->region     ='';     //not used yet
        $block->institution_id = $USER->institution_id;
        $block->add();
        
        $_SESSION['PAGE']->message[] = array('message' => 'Block erfolgreich hinzugefügt', 'icon' => 'fa-pencil-square text-success');
        $_SESSION['FORM']            = null;                     // reset Session Form object 
    }
    
}

header('Location:'.$_SESSION['PAGE']->target_url);