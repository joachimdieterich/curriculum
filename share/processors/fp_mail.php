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
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by  
 * the Free Software Foundation; either version 3 of the License, or (at your option) any later version.                                   
 *                                                                       
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of        
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details:                          
 *                                                                       
 * http://www.gnu.org/copyleft/gpl.html      
 */
include(dirname(__FILE__).'/../setup.php');  // Klassen, DB Zugriff und Funktionen
include(dirname(__FILE__).'/../login-check.php');  //check login status and reset idletimer
global $USER, $CFG;
$USER           = $_SESSION['USER'];
if (!isset($_SESSION['PAGE']->target_url)){     //if target_url is not set -> use last PAGE url
    $_SESSION['PAGE']->target_url       = $_SESSION['PAGE']->url;
}
$mail              = new Mail();
$gump              = new Gump();    /* Validation */
$mail->message     = $_POST['message_text'];  
$_POST             = $gump->sanitize($_POST);       //sanitize $_POST


$mail->receiver_id = $_POST['receiver_id']; 
$group_id          = $_POST['group_id']; 
$mail->sender_id   = $USER->id;
$mail->subject     = $_POST['subject']; 

// todo alle Regeln definieren
$gump->validation_rules(array(
'subject'          => 'required',
'message_text'    => 'required'    
));
$validated_data = $gump->run($_POST);
if($validated_data === false) {/* validation failed */
    $_SESSION['FORM'] = new stdClass();
    $_SESSION['FORM']->form      = 'mail'; 
    foreach($mail as $key => $value){
        $_SESSION['FORM']->$key = $value;
    }
    $_SESSION['FORM']->error     = $gump->get_readable_errors();
    $_SESSION['FORM']->func      = $_POST['func'];
} else {
    if (isset($_POST['add_person'])){
        if ($mail->postMail()){
        $_SESSION['PAGE']->message[] = array('message' => 'Nachricht an '.$USER->resolveUserId($mail->receiver_id).'gesendet', 'icon' => 'fa-envelope-o text-success');
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