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
* This program is free software; you can redistribute it and/or modify 
* it under the terms of the GNU General Public License as published by  
* the Free Software Foundation; either version 3 of the License, or     
* (at your option) any later version.                                   
*                                                                       
* This program is distributed in the hope that it will be useful,       
* but WITHOUT ANY WARRANTY; without even the implied warranty of        
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the         
* GNU General Public License for more details:                          
*                                                                       
* http://www.gnu.org/copyleft/gpl.html      
*/
global $USER, $PAGE, $TEMPLATE;

switch ($PAGE->login) {
    case 'first':   //wird über die login.php gesetzt
                 $PAGE->message[] ="Willkommen auf curriculum! Sie melden sich das erste mal auf curriculum an. Bitte ändern Sie daher das vorgegebene Passwort um unbefugten Zugriff zu vermeiden."; //??? Texte in globale language datei
        break;
    
    case 'webservice': //wird über die login.php gesetzt
                //$oldpassword = $USER->getPassword($USER->username, 'authenticate'); // login via webservice: get old password
                $oldpassword = $USER->getPassword(); // login via webservice: get old password //not tested yet mit PDO
                $TEMPLATE->assign('oldpassword', $oldpassword);
                $TEMPLATE->assign('webservice', 'Willkommen auf curriculum! Sie melden sich das erste mal auf curriculum an. Um den automatischen Anmeldevorgang abzuschließen müssen sie  ein Zugangskennwort festlegen. Mit diesem können sie sich in Zukunft auch direkt auf curriculum anmelden ');
        break;

    default:
        break;
}

if($_POST) {
    $gump = new Gump();
    $gump->validation_rules(array(
    'oldpassword'    => 'required',
    'password'       => 'required|max_len,100|min_len,6',
    'confirm'        => 'required|max_len,100|min_len,6',
    ));
    $validated_data = $gump->run($_POST);
    
    if($validated_data === false) {                                             // validation failed
        $TEMPLATE->assign('v_error', $gump->get_readable_errors());        
    } else {                                                                    // validation sucessful 
        if (isset($_POST['oldpassword'])){
           $oldpassword = md5($_POST['oldpassword']); 
        } 
        if ($_POST['password'] == $_POST['confirm']){
           if ($USER->changePassword(md5($_POST['password']))){
                header('Location:index.php?action=dashboard');
            } 
        } else {
           $PAGE->message[] ='Passwörter stimmen nicht überein.'; 
        }   
    }
}

$TEMPLATE->assign('page_title', 'Passwort ändern');
?>