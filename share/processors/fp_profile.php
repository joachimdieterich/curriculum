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
$user                 = new User();
$gump                 = new Gump();    /* Validation */
$_POST                = $gump->sanitize($_POST);       //sanitize $_POST

if ($_POST['func'] != 'getData'){
    $user->username       = $_POST['usr']; 
    $user->firstname      = $_POST['firstname']; 
    $user->lastname       = $_POST['lastname']; 
    $user->email          = $_POST['email']; 
    $user->postalcode     = $_POST['postalcode']; 
    $user->city           = $_POST['city']; 
    $user->country_id     = $_POST['country_id']; 
    $user->state_id       = $_POST['state_id']; 
    $user->paginator_limit  = $CFG->settings->paginator_limit;
    $user->acc_days         = $CFG->settings->acc_days;
}

if (isset($_POST['confirm'])){
    $user->confirmed= 3; //Passwortänderung wird gesetzt == 3 //Wird bei der Anmeldung überprüft
} else {
    $user->confirmed= 1; //Nutzer wird freigegeben //Passwort kann auch über das Profil geändert werden
}
if (isset($_POST['pw'])){
    $user->password        = $_POST['pw']; 
}
if (isset($_POST['show_pw'])){
    $show_pw               = $_POST['show_pw'];
}
if ($_POST['avatar_id']){ 
    $user->avatar_id    =  filter_input(INPUT_POST, 'avatar_id',   FILTER_VALIDATE_INT);
} else {
    $user->avatar_id    =  $CFG->settings->standard_avatar_id;
}

switch ($_POST['func']) {
    case 'edit':     $user->avatar_id       = $_POST['avatar_id']; 
    case 'editUser': $user->id              = $_POST['user_id'];       
        break;
    case 'new':      $user->role_id         = $_POST['role_id'];  
                     $user->institution_id  = $_POST['institution_id'];  
                     $user->group_id        = $_POST['group_id']; 
        break;
    case 'getData':
                    $user->avatar_id        = $_POST['avatar_id'];
                    $user->id               = $_POST['user_id'];

    default:
        break;
}

if ($_POST['func'] == 'edit' OR $_POST['func'] == 'editUser'){  // don't validate password
    $gump->validation_rules(array(
        'usr'          => 'required|max_len,100|min_len,3',
        'firstname'         => 'required|max_len,100',
        'lastname'          => 'required|max_len,100',
        'email'             => 'required|valid_email'
    ));
} else {
        if ($_POST['func'] != 'getData'){
        $gump->validation_rules(array(
            'usr'          => 'required|max_len,100|min_len,3',
            'firstname'         => 'required|max_len,100',
            'lastname'          => 'required|max_len,100',
            'email'             => 'required|valid_email',
            'pw'          => 'required|max_len,100|min_len,6'
        ));
    }
}
$validated_data = $gump->run($_POST);
if($validated_data === false) {/* validation failed */
    $_SESSION['FORM']        = new stdClass();
    $_SESSION['FORM']->form  = 'profile'; 
    foreach($user as $key => $value){
        $_SESSION['FORM']->$key  = $value;
    }
    $_SESSION['FORM']->error = $gump->get_readable_errors();
    $_SESSION['FORM']->func  = $_POST['func'];
} else {
    switch ($_POST['func']) {
        case 'new':      if ($user->add(filter_input(INPUT_POST, 'institution_id', FILTER_VALIDATE_INT), filter_input(INPUT_POST, 'group_id', FILTER_VALIDATE_INT))){
                            $_SESSION['PAGE']->message[] = array('message' => 'Benutzer hinzufgefügt', 'icon' => 'fa-user text-success');
                            SmartyPaginate::setTotal(SmartyPaginate::getTotal('userP')+1, 'userP');
                            $_SESSION['PAGE']->target_url = SmartyPaginate::getLastPageIndexURL('userP'); //jump to new entry in list
                         }               
            break;
        case 'editUser': 
        case 'edit':     if ($user->update()){
                            if ($user->id == $USER->id){                        // eigenes Profil --> reload session
                                session_reload_user();
                            }
                            $_SESSION['PAGE']->message[] = array('message' => 'Benutzer erfolgreich aktualisiert', 'icon' => 'fa-user text-success');
                         }
            break;
        case 'getData':
            $u = new Userdata();
            $u->make();
            $_SESSION['PAGE']->message[] = array('message' => 'Ihre Daten werden zusammengefasst und sind demnächst in "Meine Dateien" verfügbar', 'icon' => 'fa-user text-success');
            break;
        default:
            break;
    }

    $_SESSION['FORM']            = null;                     // reset Session Form object 
}

header('Location:'.$_SESSION['PAGE']->target_url);