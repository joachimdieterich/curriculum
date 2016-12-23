<?php
/** 
* This file is part of curriculum - http://www.joachimdieterich.de
* Shibboleth authentification
* 
* @package auth_shibboleth
* @filename index.php
* @copyright 2016 Joachim Dieterich
* @author Joachim Dieterich
* @date 2016.06.12 09:20
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

$base_url   = dirname(__FILE__).'/../../../';
include($base_url.'setup.php');  //Läd Klassen, DB Zugriff und Funktionen

global $CFG;
/* for test purposes - todo: get config from plugin settings*/
$shib_config = new stdClass();

$shib_config->username  = 'shib_uname';
$shib_config->firstname = 'shib_ufirstname';
$shib_config->lastname  = 'shib_ulastname';
$shib_config->email     = 'shib_uemail';
$shib_config->role_id   = 'shib_urole_id';
$shib_config->idp       = 'Shib-Identity-Provider';

$user                   = new User();
if ($user->exists('username', $_SERVER[$shib_config->username])){
    session_destroy();                                          // Verhindert, dass eine bestehende Session genutzt wird --> verursacht Probleme (token / uploadframe)
    session_start();

    $_SESSION['username']   = $_SERVER[$shib_config->username];
    $_SESSION['timein']     = time();
    $user->load('username', $_SERVER[$shib_config->username], true);

    $user->setLastLogin();

    //Nutzungsbedingungen akzeptiert?
    if ($user->checkTermsOfUse() == false){
       header('Location:../../../../public/index.php?action=terms'); exit();
    }
    header('Location:../../../../public/index.php?action=dashboard');
} 