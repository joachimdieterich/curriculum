<?php
/**
*  This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename profileAdmin.php
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
global $CFG, $USER, $TEMPLATE, $PAGE, $INSTITUTION; 

$TEMPLATE->assign('page_title', 'Benutzer anlegen');
$TEMPLATE->assign('newUserAvatar', "noprofile.jpg"); //std. avatar

if($_POST) {
    switch ($_POST) {
    case isset($_POST['addUser']):
            $new_user = new User();
            $new_user->username         = $_POST['username'];
            $new_user->firstname        = $_POST['firstname'];
            $new_user->lastname         = $_POST['lastname'];
            $new_user->email            = $_POST['email'];
            $new_user->postalcode       = $_POST['postalcode'];
            $new_user->city             = $_POST['city'];
            $new_user->state_id         = $_POST['state'];
            $new_user->country_id       = $_POST['country'];
            $new_user->password         = $_POST['password'];
            $new_user->avatar_id        = '';
            if ($_POST['avatar']) {
                if (file_exists($CFG->avatar_url.''.$_POST['avatar_id'])){
                    $new_user->avatar_id= $_POST['avatar_id'];
                } 
            }
            if (isset($_POST['confirmed'])) {
                    $new_user->confirmed    = 3; //Passwortänderung wird gesetzt == 3 //Wird bei der Anmeldung überprüft
            } else {
                    $new_user->confirmed    = 1; //Nutzer wird freigegeben //Passwort kann auch über das Profil geändert werden
            }
            $new_user->creator_id           = $USER->id;
            $validated_data = $new_user->validate();
            if($validated_data === true) {/* validation successful */

                if ($new_user->add()){ 
                    $new_user->load('username', $new_user->username);
                    $new_user->enroleToInstitution($_POST['institution']);
                    $institution = new Institution($_POST['institution']); 
                    $PAGE->message[] = '<strong>'.$new_user->username.'</strong> erfolgreich in '.$institution->institution.' eingeschrieben.';
                    $PAGE->message[] = 'Der Benutzer <strong>'.$new_user->username.'</strong> wurde erfolgreich angelegt.';
                } else {
                    $PAGE->message[] = 'Der Benutzer konnte nicht angelegt werden. Bitte wählen sie einen anderen Benutzernamen.';
                }
            } else {/* validation failed */    
                foreach($new_user as $key => $value){
                $TEMPLATE->assign($key, $value);
            } 
            $TEMPLATE->assign('v_error', $validated_data);     
            $TEMPLATE->assign('showUserForm', true);
            }
            break;    

    default: break; 
    }
}
                     
/*******************************************************************************
 * END POST / GET 
 */       
        
$TEMPLATE->assign('standardrole', $INSTITUTION->institution_standard_role);
$TEMPLATE->assign('page_message', $PAGE->message);	
$institution = new Institution();
$TEMPLATE->assign('myInstitutions', $institution->getInstitutions('user', $USER->id));
$country = new State(); 
$countries = $country->getCountries();
$TEMPLATE->assign('countries', $countries);
$TEMPLATE->assign('states', $country->getStates('profile', $USER->country_id));
$TEMPLATE->assign('country_id', $USER->country_id);
$TEMPLATE->assign('state_id', $USER->state_id);
$new_users = new User(); 
$newusers = $new_users->newUsers($USER->id);

setPaginator('usersPaginator', $TEMPLATE, $newusers, 'results', 'index.php?action=profileAdmin'); //set Paginator
?>