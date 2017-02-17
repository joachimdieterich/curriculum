<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* FormProcessor
* @package core
* @filename fp_wallet_content.php
* @copyright 2016 Joachim Dieterich
* @author Joachim Dieterich
* @date 2016.12.30 14:59
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
$USER            = $_SESSION['USER'];
if (!isset($_SESSION['PAGE']->target_url)){     //if target_url is not set -> use last PAGE url
    $_SESSION['PAGE']->target_url       = $_SESSION['PAGE']->url;
}
if (isset($_POST['html'])){  //get html from form bevor gump validation
    $content                = new Content();
    $purify = HTMLPurifier_Config::createDefault();
    $purify->set('Core.Encoding', 'UTF-8'); // replace with your encoding
    $purify->set('HTML.Doctype', 'HTML 4.01 Transitional'); // replace with your doctype

    $purifier               = new HTMLPurifier($purify);
    $content->content       = $purifier->purify(filter_input(INPUT_POST, 'html', FILTER_UNSAFE_RAW));
}

$wallet_content = new WalletContent(); 

$gump           = new Gump();    /* Validation */
$_POST          = $gump->sanitize($_POST);       //sanitize $_POST
// todo alle Regeln definieren
$gump->validation_rules(array(
'wallet_id'   => 'required',
'width_class' => 'required',   
'position'    => 'required',
'row_id'      => 'required'
));

$validated_data  = $gump->run($_POST);
if($validated_data === false) {/* validation failed */
    $_SESSION['FORM']            = new stdClass();
    $_SESSION['FORM']->form      = 'wallet_content';
    foreach($_POST as $key => $value){                                         
        $_SESSION['FORM']->$key  = $value;
    }
    $_SESSION['FORM']->error     = $gump->get_readable_errors();
    $_SESSION['FORM']->func      = $_POST['func']; 
} else {
    if (isset($_POST['id'])){
        $wallet_content->id      = filter_input(INPUT_POST, 'id',          FILTER_VALIDATE_INT);
    }
    $wallet_content->title       = filter_input(INPUT_POST, 'title',       FILTER_SANITIZE_STRING);    
    $wallet_content->wallet_id   = filter_input(INPUT_POST, 'wallet_id',   FILTER_VALIDATE_INT);  
    switch ($_POST['type']) {
        case 'file':    $f       = new File();
                        $f->load(filter_input(INPUT_POST, 'file_id',    FILTER_VALIDATE_INT));
                        $wallet_content->reference_id = $f->id;
                        $wallet_content->context_id   = $f->context_id;
            break;
        case 'html':    $wallet_content->context_id   = filter_input(INPUT_POST, 'context_id',   FILTER_VALIDATE_INT);  
                        $content->file_context        = 1; // == global --> not used here but needed for db
                        $content->reference_id        = $wallet_content->wallet_id;
                        $content->title               = $wallet_content->title;
                        if ($_POST['func'] == 'edit'){
                            $content->id                  = $_POST['content_id'];
                            $wallet_content->reference_id = $content->id;
                            checkCapabilities('wallet:workon', $USER->role_id);   //has to be done here --> content class normally uses permission content:add
                            $content->update();
                        }  else {
                            checkCapabilities('wallet:workon', $USER->role_id);   //has to be done here --> content class normally uses permission  content:add
                            $wallet_content->reference_id = $content->add(false); 
                        }
            break;

        default:
            break;
    }
    $wallet_content->width_class = filter_input(INPUT_POST, 'width_class', FILTER_SANITIZE_STRING); 
    $wallet_content->position    = filter_input(INPUT_POST, 'position',    FILTER_SANITIZE_STRING); 
    $wallet_content->row_id      = filter_input(INPUT_POST, 'row_id',    FILTER_VALIDATE_INT);
    $wallet_content->order_id    = filter_input(INPUT_POST, 'order_id',    FILTER_VALIDATE_INT);
    switch ($_POST['func']) {
        case 'new_file':
        case 'new_content': if ($wallet_content->add()){
                                $_SESSION['PAGE']->message[] = array('message' => 'Medium hinzufgefügt', 'icon' => 'fa-file-o text-success');
                            }               
            break;
        case 'edit':        if ($wallet_content->update()){
                                $_SESSION['PAGE']->message[] = array('message' => 'Medium erfolgreich aktualisiert', 'icon' => 'fa-file-o text-success');
                            }
            break;

        default:
            break;
    }
    $_SESSION['FORM']            = null;                     // reset Session Form object 
}

header('Location:'.$_SESSION['PAGE']->target_url);