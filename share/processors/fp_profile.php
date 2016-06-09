<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
 * FormProcessor
 * @package core
 * @filename fp_profile.php
 * @copyright 2016 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2016.05.29 14:11
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
include(dirname(__FILE__).'/../login-check.php');  //check login status and reset idletimer
global $USER, $CFG;
$USER           = $_SESSION['USER'];
if (!isset($_SESSION['PAGE']->target_url)){     //if target_url is not set -> use last PAGE url
    $_SESSION['PAGE']->target_url       = $_SESSION['PAGE']->url;
}
$user                 = new User();
$gump                 = new Gump();    /* Validation */
$_POST                = $gump->sanitize($_POST);       //sanitize $_POST



$user->username       = $_POST['username']; 
$user->firstname      = $_POST['firstname']; 
$user->lastname       = $_POST['lastname']; 
$user->email          = $_POST['email']; 
$user->postalcode     = $_POST['postalcode']; 
$user->city           = $_POST['city']; 
$user->country_id     = $_POST['country_id']; 
$user->state_id       = $_POST['state_id']; 
$user->paginator_limit  = $CFG->paginator_limit;
$user->acc_days         = $CFG->acc_days;
$user->creator_id       = $USER->id;

if (isset($_POST['confirm'])){
    $user->confirmed= 3; //Passwortänderung wird gesetzt == 3 //Wird bei der Anmeldung überprüft
} else {
    $user->confirmed= 1; //Nutzer wird freigegeben //Passwort kann auch über das Profil geändert werden
}
if (isset($_POST['password'])){
    $user->password        = $_POST['password']; 
}
if (isset($_POST['show_pw'])){
    $show_pw               = $_POST['show_pw'];
}
if ($_POST['avatar_id']){ 
    $user->avatar_id    =  filter_input(INPUT_POST, 'avatar_id',   FILTER_VALIDATE_INT);
} else {
    $user->avatar_id    =  '';
}

switch ($_POST['func']) {
    case 'edit':     $user->avatar_id       = $_POST['avatar_id']; 
    case 'editUser': $user->id              = $_POST['user_id'];       
        break;
    case 'new':      $user->role_id         = $_POST['role_id'];  
                     $user->institution_id  = $_POST['institution_id'];  
                     $user->group_id        = $_POST['group_id']; 
        break;

    default:
        break;
}

if ($_POST['func'] == 'new' OR $_POST['func'] == 'editUser'){  // don't validate password
    $gump->validation_rules(array(
        'username'          => 'required|max_len,100|min_len,3',
        'firstname'         => 'required|max_len,100',
        'lastname'          => 'required|max_len,100',
        'email'             => 'required|valid_email'
    ));
} else {           
    $gump->validation_rules(array(
        'username'          => 'required|max_len,100|min_len,3',
        'firstname'         => 'required|max_len,100',
        'lastname'          => 'required|max_len,100',
        'email'             => 'required|valid_email',
        'password'          => 'required|max_len,100|min_len,6'
    ));
}
$validated_data = $gump->run($_POST);
if($validated_data === false) {/* validation failed */
    $_SESSION['FORM'] = new stdClass();
    $_SESSION['FORM']->form  = 'profile'; 
    $_SESSION['FORM']->error = $gump->get_readable_errors();
    foreach($user as $key => $value){
        $_SESSION['FORM']->$key  = $value;
    }
    $_SESSION['FORM']->func  = $_POST['func'];
} else {
    switch ($_POST['func']) {
        case 'new':      if ($user->add(filter_input(INPUT_POST, 'institution_id', FILTER_VALIDATE_INT), filter_input(INPUT_POST, 'group_id', FILTER_VALIDATE_INT))){
                            $_SESSION['PAGE']->message[] = array('message' => 'Benutzer hinzufgefügt', 'icon' => 'fa-user text-success');
                         }               
            break;
        case 'editUser': 
        case 'edit':     if ($user->update()){
                            $_SESSION['PAGE']->message[] = array('message' => 'Benutzer erfolgreich aktualisiert', 'icon' => 'fa-user text-success');
                         }
            break;
        
        default:
            break;
    }

    $_SESSION['FORM']            = null;                     // reset Session Form object 
}

header('Location:'.$_SESSION['PAGE']->target_url);