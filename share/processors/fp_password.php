<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
 * FormProcessor
 * @package core
 * @filename fp_password.php
 * @copyright 2016 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2016.05.28 9:15
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

global $USER, $CFG;
$USER           = $_SESSION['USER'];
if (!isset($_SESSION['PAGE']->target_url)){     //if target_url is not set -> use last PAGE url
    $_SESSION['PAGE']->target_url       = $_SESSION['PAGE']->url;
}
$user           = new USER();
$gump           = new Gump();    /* Validation */
$_POST          = $gump->sanitize($_POST);       //sanitize $_POST

$user->username = $_POST['username']; 
$user->password = md5($_POST['oldpassword']); 
$password       = $_POST['password']; 
$confirm        = $_POST['confirm']; 

if (!$user->checkLoginData()){
    $_SESSION['FORM']->id      = null;
    $_SESSION['FORM']->form    = 'password';
    $_SESSION['FORM']->error   = array('oldpassword' => array('message' => array(0 => 'Das alte Kennwort ist falsch.')));
    $_SESSION['FORM']->func    = $_POST['func'];
} else if ($password != $confirm){
    $_SESSION['FORM']->id      = null;
    $_SESSION['FORM']->form    = 'password';
    $_SESSION['FORM']->error   = array('password' => array('message' => array(0 => 'Kennwörter stimmen nicht überein.')),
                                       'confirm' => array('message' => array(0 => 'Kennwörter stimmen nicht überein.')));
    $_SESSION['FORM']->func    = $_POST['func'];
} else {
    // todo alle Regeln definieren
    $gump->validation_rules(array(
    'password'          => 'required|min_len,8',
    'confirm'           => 'required|min_len,8'
    ));
    $validated_data = $gump->run($_POST);
    if($validated_data === false) {/* validation failed */
        $_SESSION['FORM'] = new stdClass();
        $_SESSION['FORM']->form      = 'password'; 
        $_SESSION['FORM']->error     = $gump->get_readable_errors();
        $_SESSION['FORM']->func      = $_POST['func'];
    } else {
        if ($user->changePassword(md5($password))){
            $_SESSION['PAGE']->message[] = 'Passwort erfolgreich geändert';
        }
        $_SESSION['FORM']            = null;                     // reset Session Form object 
    }
}

header('Location:'.$_SESSION['PAGE']->target_url);