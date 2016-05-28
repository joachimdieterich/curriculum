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
* This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by  
* the Free Software Foundation; either version 3 of the License, or (at your option) any later version.
*                                                                       
* This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of        
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details:
*                                                                       
* http://www.gnu.org/copyleft/gpl.html      
*/
global $CFG, $USER, $PAGE, $TEMPLATE, $INSTITUTION;

$currentUser    = new User(); 
if(isset($_GET['function'])) {
    switch ($_GET['function']) {
        case 'new':         checkCapabilities('user:addUser',    $USER->role_id);
                            loadSelectData('Benutzer anlegen');
            break;
        case 'editUser':    checkCapabilities('user:updateUser', $USER->role_id);
                            $currentUser->load('id',             filter_input(INPUT_GET,  'userID', FILTER_VALIDATE_INT));
                            loadSelectData('Profil bearbeiten',  $currentUser, true);
                break;
        case 'edit':        checkCapabilities('user:update',    $USER->role_id);
                            $currentUser->load('id',            $USER->id);
                            loadSelectData('Profil bearbeiten', $currentUser, true);
                break;
        default:            
            break;
    }
} 

/*******************************************************************************
 * If $_POST is set 
 */
if($_POST) {    
            $new_user = new User();
            if (isset($_POST['edit'])){
                $new_user->id           = filter_input(INPUT_POST, 'p_id',    FILTER_VALIDATE_INT);
            } else {
                $new_user->password     = filter_input(INPUT_POST, 'p_password',    FILTER_UNSAFE_RAW);
            }
            $new_user->username         = filter_input(INPUT_POST, 'p_username',    FILTER_UNSAFE_RAW);
            $new_user->firstname        = filter_input(INPUT_POST, 'p_firstname',   FILTER_UNSAFE_RAW);
            $new_user->lastname         = filter_input(INPUT_POST, 'p_lastname',    FILTER_UNSAFE_RAW);
            $new_user->email            = filter_input(INPUT_POST, 'p_email',       FILTER_VALIDATE_EMAIL); 
            $new_user->postalcode       = filter_input(INPUT_POST, 'p_postalcode',  FILTER_UNSAFE_RAW);
            $new_user->city             = filter_input(INPUT_POST, 'p_city',        FILTER_UNSAFE_RAW);
            $new_user->state_id         = filter_input(INPUT_POST, 'p_state_id',    FILTER_VALIDATE_INT);
            $new_user->country_id       = filter_input(INPUT_POST, 'p_country_id',  FILTER_VALIDATE_INT);

            if (isset($_POST['p_confirmed'])) {
                    $new_user->confirmed= 3; //Passwortänderung wird gesetzt == 3 //Wird bei der Anmeldung überprüft
            } else {
                    $new_user->confirmed= 1; //Nutzer wird freigegeben //Passwort kann auch über das Profil geändert werden
            }
            
            if ($_POST['p_avatar_id']){ 
                $new_user->avatar_id    =  filter_input(INPUT_POST, 'p_avatar_id',   FILTER_VALIDATE_INT);
            } else {
                $new_user->avatar_id    =  '';
            }
            $new_user->role_id          = filter_input(INPUT_POST, 'p_role_id',      FILTER_VALIDATE_INT);
            $new_user->paginator_limit  = $CFG->paginator_limit;
            $new_user->acc_days         = $CFG->acc_days;
            //$new_user->semester_id      = $INSTITUTION->semester_id;
            $new_user->creator_id       = $USER->id;
            
            $gump   = new Gump();
            $_POST  = $gump->sanitize($_POST);           //sanitize $_POST
            if (isset($_POST['edit'])){  // don't validate password
                $gump->validation_rules(array(
                    'p_username'          => 'required|max_len,100|min_len,3',
                    'p_firstname'         => 'required|max_len,100',
                    'p_lastname'          => 'required|max_len,100',
                    'p_email'             => 'required|valid_email'
                ));
            } else {           
                $gump->validation_rules(array(
                    'p_username'          => 'required|max_len,100|min_len,3',
                    'p_firstname'         => 'required|max_len,100',
                    'p_lastname'          => 'required|max_len,100',
                    'p_email'             => 'required|valid_email',
                    'p_password'          => 'required|max_len,100|min_len,6'
                ));
            }
            
            $validated_data = $gump->run($_POST);
            
            if($validated_data === false) {/* validation failed */
                assign_to_template($new_user, 'p_');
                $TEMPLATE->assign('v_error',        $gump->get_readable_errors());   
                if (isset($_POST['add'])) { loadSelectData('Benutzer anlegen', $new_user); }
                if (isset($_POST['edit'])){ loadSelectData('Profil bearbeiten', $new_user, true); }
            } else {/* validation successgul */ 
                if (isset($_POST['add'])){
                    if ($new_user->add(filter_input(INPUT_POST, 'p_institution_id', FILTER_VALIDATE_INT), filter_input(INPUT_POST, 'p_group_id', FILTER_VALIDATE_INT))){ 
                        $new_user->load('username', $new_user->username);
                        loadSelectData('Benutzer anlegen');//(next)new user
                    } else {  $PAGE->message[]    = 'Der Benutzer konnte nicht angelegt werden.'; }     
                }
                if (isset($_POST['edit'])){
                    if ($new_user->update()){
                        loadSelectData('');//--> sonst Meldungen: undefinierte var. in der Konsole
                        if ($new_user->id == $USER->id)      { session_reload_user(); }        // reload session data for user if updated user == session user
                        if ($PAGE->previous_action == 'user'){ header('Location:index.php?action=user'); 
                        } else                               { header('Location:index.php?action=dashboard');}
                    } 
                }  
            } 
}
/*******************************************************************************
 * load user if no $_POST and $_GET data 
 */
if (checkCapabilities('user:listNewUsers', $USER->role_id, false)){
    $new_users  = new User(); 
    if ($new_users->newUsers($USER->id)){
        $p_options =    $p_config =   array('id'         => 'checkbox',
                                    'username'   => 'Benutzername', 
                                    'firstname'  => 'Vorname', 
                                    'lastname'   => 'Nachname', 
                                    'email'      => 'Email', 
                                    'postalcode' => 'PLZ', 
                                    'city'       => 'Ort', 
                                    'state_id'   => 'Bundesland', 
                                    'country_id' => 'Land', 
                                    'role_name'  => 'Rolle', 
                                    'p_options'  => array());
        setPaginator('newUsersPaginator', $TEMPLATE, $new_users->newUsers($USER->id, 'newUsersPaginator'), 'nusr_val', 'index.php?action=profile', $p_config);
    }
}
/**
 * load countries and schooltypes
 * @global object $TEMPLATE 
 */
function loadSelectData($page_title, $user = false, $readonly = false){
    global $CFG, $TEMPLATE, $USER; 
    $TEMPLATE->assign('page_title',         $page_title);
    $TEMPLATE->assign('breadcrumb',  array($page_title => 'index.php?action=profile'));
    $TEMPLATE->assign('readonly',           $readonly);
    
    if (!$user){ //keine ID übergeben
        $state_id   =                       $CFG->standard_state;
        $country_id =                       $CFG->standard_country;
        $TEMPLATE->assign('p_id',           false);
        $TEMPLATE->assign('p_country_id',   $CFG->standard_country);
        $TEMPLATE->assign('p_role_id',      $CFG->standard_role);
        $TEMPLATE->assign('p_avatar',       $CFG->standard_avatar);
        // Group für direkte Einschreibungen 
        $group = new Group();
        $TEMPLATE->assign('group',              $group->getGroups('institution', $USER->institutions[0]->institution_id)); //Gruppen der angezeigten Institution laden
        $TEMPLATE->assign('p_group_id',         $state_id);
    } else {
        $country_id =                       $user->country_id;
        $state_id   =                       $user->state_id;
        assign_to_template($user,    'p_');
    }
    $country        = new State(); 
    $TEMPLATE->assign('countries',          $country->getCountries());
    $TEMPLATE->assign('state',              $country->getStates('profile', $country_id));
    $TEMPLATE->assign('p_state_id',         $state_id);

    $schooltype     = new Schooltype();
    $TEMPLATE->assign('schooltype',         $schooltype->getSchooltypes());
    $roles          = new Roles(); 
    $TEMPLATE->assign('roles',              $roles->get());
}