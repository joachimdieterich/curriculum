<?php
/**
*  This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename profile.php
* @copyright 2013 Joachim Dieterich
* @author Joachim Dieterich
* @date 2013.03.08 13:26
* @license: 
* 
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
global $CFG, $USER, $PAGE, $TEMPLATE;
$TEMPLATE->assign('page_title', 'Profil bearbeiten');
$currentUser = new User(); 

/*******************************************************************************
 * If $_GET is set 
 */
if(isset($_GET['function'])) {
    switch ($_GET['function']) {
        case 'editUser': if(checkCapabilities('user:updateUser', $USER->role_id)){
                            $currentUser->id = $_GET['userID'];
                            $currentUser->load('id', $currentUser->id);      
                        }
                        break;
        default:        break;
    }
}
  
/*******************************************************************************
 * If $_POST is set 
 */
if($_POST) {    
        /*set currentUser */
        $currentUser->id         = $_POST['userID'];
        $currentUser->username   = $_POST['username'];
        $currentUser->firstname  = $_POST['firstname'];
        $currentUser->lastname   = $_POST['lastname'];
        $currentUser->email      = $_POST['email'];
        $currentUser->postalcode = $_POST['postalcode'];
        $currentUser->city       = $_POST['city'];
        $currentUser->state_id   = $_POST['state'];
        $currentUser->country_id = $_POST['country'];
        $currentUser->avatar_id  = $_POST['avatar_id'];
        $currentUser->role_id    = $_POST['role_id'];
        //var_dump($currentUser);
        $validated_data = $currentUser->validate();

        if($validated_data === true) { /* validation successful */
            if (file_exists($CFG->avatar_root.$currentUser->avatar)) {                 // avatar exists?
            } else {
                $PAGE->message[] =  $CFG->avatar_root.$currentUser->avatar.' existiert nicht';
            }
            if ($currentUser->update()){
                /* reload session data for user if updated user == session user*/
                if ($currentUser->id == $USER->id){
                    session_reload_user();  
                }
                if ($PAGE->previous_action == 'user'){                     
                    header('Location:index.php?action=user');
                } else {
                    header('Location:index.php?action=dashboard');
                }
            }        
        } else { /* validation failed */
            $TEMPLATE->assign('v_error', $validated_data); 
        }      
    $TEMPLATE->assign('page_message', $PAGE->message);  	
}

/*******************************************************************************
 * load user if no $_POST and $_GET data 
 */
if (empty($currentUser->id)){
    $currentUser->load('username', $USER->username);     
}
/*  assign $currentUser to $TEMPLATE */
foreach($currentUser as $key => $value){
   $TEMPLATE->assign($key, $value);
} 
        
$country = new State(); 
$countries = $country->getCountries();
$TEMPLATE->assign('countries', $countries);
$TEMPLATE->assign('states', $country->getStates('profile', $currentUser->country_id));
?>