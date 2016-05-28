<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename login.php
* @copyright  2013 Joachim Dieterich  {@link http://www.joachimdieterich.de}
* @date 2013.03.08 13:26
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
global $TEMPLATE; 
//print_r($_SERVER);
$user       = new User();
$message    = '';

if(filter_input(INPUT_POST, 'login', FILTER_UNSAFE_RAW)) {
    $user->username = (filter_input(INPUT_POST,     'username', FILTER_UNSAFE_RAW));     
    $user->password = (md5(filter_input(INPUT_POST, 'password', FILTER_UNSAFE_RAW)));
    $TEMPLATE->assign('username', $user->username);                 // Benutzername bei falschem Passwort automatisch einsetzen.
    
    if($user->checkLoginData()) { 
        session_destroy();                                          // Verhindert, dass eine bestehende Session genutzt wird --> verursacht Probleme (token / uploadframe)
        session_start();
        
        $_SESSION['username']   = $user->username;
        $_SESSION['timein']     = time();
        $user->load('username', $user->username, true);
        
        $user->setLastLogin();
        
        //Nutzungsbedingungen akzeptiert?
        if ($user->checkTermsOfUse() == false){
           header('Location:../share/request/getTermsofUse.php'); exit();
        }
        route($user); 
    } else { 
        $PAGE->message[] =  'Benutzername bzw. Passwort falsch.';   
    }  
    
} else if (filter_input(INPUT_POST, 'terms', FILTER_UNSAFE_RAW)){
    switch (filter_input(INPUT_POST, 'Submit', FILTER_UNSAFE_RAW)) {
        case 'Ja':  $user->load('username', $_SESSION['username'], true);
                    $user->acceptTerms();
                    route($user);
            break;
        case 'Nein':header('Location:index.php?action=logout'); 
            break;
        default:
            break;
    }
}


$TEMPLATE->assign('page_title',     'Login');
$TEMPLATE->assign('breadcrumb',  array('Login' => 'index.php?action=login'));
$TEMPLATE->assign('message',        $message);

function route($usr){
    $confirmed = $usr->getConfirmed();
    switch ($confirmed) {
        case 1:     header('Location:index.php?action=dashboard'); 
            break;
        case 2:     //header('Location:index.php?action=password&login=first');//ab Version 0.5 BETA nicht verwendet // --> 1. Login nach erfolgreichem Registrieren
            break;
        case 3:     $_SESSION['FORM']->id      = null;
                    $_SESSION['FORM']->form    = 'password';
                    $_SESSION['FORM']->func    = 'changePW';
                    header('Location:index.php?action=dashboard');
            
            break;
        case 4:     //$PAGE->message[] = 'Ihr Account wurde noch nicht durch den Administrator freigegeben. Bitte probieren Sie es später noch einmal.'; //wurde für die PL Version herausgenommen // --> noch nicht freigegeben
            break; 
        default:    break;
    }   
}