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
            $_SESSION['PAGE']->message[] = array('message' => 'Passwort erfolgreich geändert', 'icon' => 'fa-key text-success');
        }
        $_SESSION['FORM']            = null;                     // reset Session Form object 
    }
}

header('Location:'.$_SESSION['PAGE']->target_url);