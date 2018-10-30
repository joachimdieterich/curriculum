<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* FormProcessor
* @package core
* @filename fp_mail.php
* @copyright 2016 Joachim Dieterich
* @author Joachim Dieterich
* @date 2016.05.29 09:18
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
$USER           = $_SESSION['USER'];
if (!isset($_SESSION['PAGE']->target_url)){     //if target_url is not set -> use last PAGE url
    $_SESSION['PAGE']->target_url       = $_SESSION['PAGE']->url;
}
$mail              = new Mail();

$purify            = HTMLPurifier_Config::createDefault();
$purify->set('Core.Encoding', 'UTF-8'); // replace with your encoding
$purify->set('HTML.Doctype', 'HTML 4.01 Transitional'); // replace with your doctype
$purifier          = new HTMLPurifier($purify);
$mail->message     = $purifier->purify(filter_input(INPUT_POST, 'message_text', FILTER_UNSAFE_RAW));
if (isset($_POST['signature_id'])){
    $signature     = new Content();
    $signature->load('id', $_POST['signature_id']);
    $mail->message .= '<br>'.$signature->content; 
}
$gump              = new Gump();    /* Validation */
$_POST             = $gump->sanitize($_POST);       //sanitize $_POST
$mail->receiver_id = $_POST['receiver_id']; 
$group_id          = $_POST['group_id']; 
$mail->sender_id   = $USER->id;
$mail->subject     = $_POST['subject']; 

// todo alle Regeln definieren
$gump->validation_rules(array(
'subject'         => 'required',
'message_text'    => 'required'    
));
$validated_data = $gump->run($_POST);
if($validated_data === false) {/* validation failed */
    $_SESSION['FORM']            = new stdClass();
    $_SESSION['FORM']->form      = 'mail'; 
    foreach($mail as $key => $value){
        $_SESSION['FORM']->$key = $value;
    }
    $_SESSION['FORM']->error     = $gump->get_readable_errors();
    $_SESSION['FORM']->func      = $_POST['func'];
} else {
    if (isset($_POST['add_person'])){
        if ($mail->postMail()){
        $_SESSION['PAGE']->message[] = array('message' => 'Nachricht an '.$USER->resolveUserId($mail->receiver_id).' gesendet', 'icon' => 'fa-envelope-o text-success');
        }
    }
    if (isset($_POST['add_group'])){
        if ($mail->postMail('group',$_POST['group_id'])){
        $_SESSION['PAGE']->message[] = array('message' => 'Nachricht an Gruppe gesendet', 'icon' => 'fa-envelope-o text-success');
        }
    }      
    $_SESSION['FORM']            = null;                     // reset Session Form object 
}

header('Location:'.$_SESSION['PAGE']->target_url);
