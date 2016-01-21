<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package core
 * @filename password.php
 * @copyright 2013 Joachim Dieterich
 * @author Joachim Dieterich
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
global $USER, $PAGE, $TEMPLATE;

switch (filter_input(INPUT_GET, 'login', FILTER_UNSAFE_RAW)) {
    /*case 'first':   //wird über die login.php gesetzt
                 $PAGE->message[] ="Willkommen auf curriculum! Sie melden sich das erste mal auf curriculum an. Bitte ändern Sie daher das vorgegebene Passwort um unbefugten Zugriff zu vermeiden.";
        break;*/   
    case 'changePW': //wird über die login.php gesetzt
                $oldpassword        = $USER->getPassword(); 
                $TEMPLATE->assign('oldpassword', $oldpassword);
                $PAGE->message[]    = 'Bitte ändern Sie das bestehende Passwort.';
        break;

    default:
        break;
}

$form           = new HTML_QuickForm2('password', 'post', 'action=index.php?action=password');               // Instantiate the HTML_QuickForm2 object
$form->addDataSource(new HTML_QuickForm2_DataSource_Array(array('username' => $USER->username )));

$fieldset       = $form->addElement('fieldset');
$username       = $fieldset->addElement('text', 'username', array('size' => 40, 'maxlength' => 255, 'readonly' => 'readonly'))
                       ->setLabel('Benutzername');
$oldpassword    = $fieldset->addElement('password',  'oldpassword', array('size' => 40, 'maxlength' => 255, 'id' => 'oldpassword'))
                       ->setLabel('Altes Passwort');
$password       = $fieldset->addElement('password', 'password', array('size' => 40, 'maxlength' => 255))
                       ->setLabel('Passwort');
$confirm        = $fieldset->addElement('password', 'password', array('size' => 40, 'maxlength' => 255))
                       ->setLabel('Passwort bestätigen');
$fieldset->addElement('submit', null, array('value' => 'Passwort ändern'));

// Define filters and validation rules
$oldpassword->addRule('required', 'Bitte altes Passwort eingeben');
$password->addRule('required', 'Bitte Passwort eingeben');
$confirm->addRule('required', 'Bitte Passwort bestätigen');

if ($form->validate()) {// Try to validate a form
    //if (null !== $oldpassword->getValue()){ $oldpassword = md5($oldpassword->getValue()); } // login via webservice --> in dieser Version nicht verwendet
    if ($password->getValue() == $confirm->getValue()){
        if ($USER->changePassword(md5($password->getValue()))){
            header('Location:index.php?action=dashboard');
        } 
    } else {
        $PAGE->message[] ='Passwörter stimmen nicht überein.'; 
    }   
}

$TEMPLATE->assign('pw_form',     $form);     // assign the form
$TEMPLATE->assign('page_title', 'Passwort ändern');