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

$user       = new User();
$message    = '';
$form       = new HTML_QuickForm2('Login');               // Instantiate the HTML_QuickForm2 object
$fieldset   = $form->addElement('fieldset');
$username   = $fieldset->addElement('text', 'username', array('size' => 40, 'maxlength' => 255, 'id' => 'username'))
                       ->setLabel('Anmeldename');
$password   = $fieldset->addElement('password', 'password', array('size' => 40, 'maxlength' => 255))
                       ->setLabel('Passwort');
$fieldset->addElement('submit', null, array('value' => 'Anmelden'));

// Define filters and validation rules
$username->addFilter('trim');
$username->addRule('required', 'Bitte Anmeldenamen eingeben');
$password->addRule('required', 'Bitte Passwort eingeben');

if (filter_input(INPUT_POST, 'terms', FILTER_UNSAFE_RAW)){          // Nutzungsbedingungen akzeptiert
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
} else if ($form->validate()) {// Try to validate a form
   $user->username = $username->getValue();
   $user->password = md5($password->getValue());
   
   if($user->checkLoginData()) { 
        session_destroy();                                          // Verhindert, dass eine bestehende Session genutzt wird --> verursacht Probleme (token / uploadframe)
        session_start();
        
        $_SESSION['username']   = $user->username;
        $_SESSION['timein']     = time();
        $user->load('username', $user->username, true);
        
        $user->setLastLogin();
        
        if ($user->checkTermsOfUse() == false){ //Nutzungsbedingungen akzeptiert?
           header('Location:../share/request/getTermsofUse.php'); exit();
        }
        route($user); 
    } else { 
        $PAGE->message[] =  'Benutzername bzw. Passwort falsch.';   
    }  
}

$TEMPLATE->assign('login_form', $form);     // assign the form
$TEMPLATE->assign('page_title',     'Login');
$TEMPLATE->assign('message',        $message);

function route($usr){
    $confirmed = $usr->getConfirmed();
    switch ($confirmed) {
        case 1:     header('Location:index.php?action=dashboard'); 
            break;
        case 2:     //header('Location:index.php?action=password&login=first');//ab Version 0.5 BETA nicht verwendet // --> 1. Login nach erfolgreichem Registrieren
            break;
        case 3:     header('Location:index.php?action=password&login=changePW'); 
            break;
        case 4:     //$PAGE->message[] = 'Ihr Account wurde noch nicht durch den Administrator freigegeben. Bitte probieren Sie es später noch einmal.'; //wurde für die PL Version herausgenommen // --> noch nicht freigegeben
            break; 
        default:    break;
    }   
}