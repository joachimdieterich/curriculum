<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package core
 * @filename f_profile.php
 * @copyright 2016 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2016.05.29 11:27
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
$base_url   = dirname(__FILE__).'/../';
include($base_url.'setup.php');  //Läd Klassen, DB Zugriff und Funktionen
include(dirname(__FILE__).'/../login-check.php');  //check login status and reset idletimer
global $CFG, $USER;
$user           = new User(); 
$USER           = $_SESSION['USER'];
$func           = $_GET['func'];
$avatar         = null;
$user_id        = null;
$avatar_id      = null;
$username       = null;
$firstname      = null;
$lastname       = null; 
$email          = null;
$postalcode     = null; 
$city           = null;
$country_id     = $CFG->standard_country; 
$state_id       = $CFG->standard_state;
$password       = null; 
$show_pw        = null; 
$confirm        = null;
$role_id        = $CFG->standard_role;
$group_id       = null;
$institution_id = null;
$error          = null;
$object         = file_get_contents("php://input");
$data           = json_decode($object, true);
if (is_array($data)) {
    foreach ($data as $key => $value){
        $$key = $value;
    }
}
            
if (isset($func)){
    switch ($func) {
        case 'new':      checkCapabilities('user:addUser',    $USER->role_id);
                         $header            = 'Benutzer anlegen';
                         $add               = true;
        break;
        case 'editUser': checkCapabilities('user:updateUser',    $USER->role_id);
                         $header            = 'Profil bearbeiten';
                         $edituser          = true;
                         $user->load('id', filter_input(INPUT_GET,  'id', FILTER_VALIDATE_INT));
                         $user_id           = filter_input(INPUT_GET,  'id', FILTER_VALIDATE_INT);
                         foreach ($user as $key => $value){
                             $$key = $value;
                             //error_log($key. ': '.$value);
                         }
        break;
        
        case "edit":     checkCapabilities('user:update',    $USER->role_id);
                         $header            = 'Mein Profil aktualisieren';
                         $edit              = true;  
                         $user->load('id',    $USER->id);
                         $user_id           = $USER->id;//Läd die bestehenden Daten aus der db
                         foreach ($user as $key => $value){
                             $$key = $value;
                             //error_log($key. ': '.$value);
                         }
            break;
        default: break;
    }
}

/* if validation failed, get formdata from session*/
if (isset($_SESSION['FORM'])){
    if (is_object($_SESSION['FORM'])) {
        foreach ($_SESSION['FORM'] as $key => $value){
            $$key = $value;
        }
    }
}

$html ='<div class="modal-dialog" style="overflow-y: initial !important;">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closePopup()"><span aria-hidden="true">×</span></button>
              <h4 class="modal-title">'.$header.'</h4>
            </div>
            <div class="modal-body" style="max-height: 500px; overflow-y: auto;">';
$html .='<form id="form_profile"  class="form-horizontal" role="form" method="post" action="../share/processors/fp_profile.php"';

if (isset($currentUrlId)){ $html .= $currentUrlId; }
$html .= '"><input type="hidden" name="func" id="func" value="'.$func.'"/>
            <input type="hidden" name="user_id" id="user_id" value="'.$user_id.'"/>
            <input type="hidden" name="avatar_id" id="avatar_id" value="'.$avatar_id.'"/>';
if ($func == 'new'){
    $html .= Form::input_text('username', 'Benutzername', $username, $error);
} else {
    /* onclick="tb_show('','../share/request/uploadframe.php?userID={$my_id}&last_login={$my_last_login}&context=avatar&target=p_avatar_id&format=0&multiple=false&modal=true&TB_iframe=true&width=710')" href="#" class="thickbox"*/
    $html .= '<div class="col-xs-4"></div><div class="col-xs-6">'
            . '<a href="'.$CFG->request_url .'uploadframe.php?&context=avatar&target=avatar_id&format=0&multiple=false" class="nyroModal">'
            . '<img id="avatar" style="height:100px; margin-left: -5px; padding-bottom:10px;" src="'.$CFG->access_file.$avatar.'" alt="Profilfoto">'
            . '</a></div>';
    $html .= Form::input_text('username', 'Benutzername', $username, $error,'','text',null, null, 'col-sm-4','col-sm-7', true);
}
$html .= Form::input_text('firstname', 'Vorname', $firstname, $error);
$html .= Form::input_text('lastname', 'Nachname', $lastname, $error);
$html .= Form::input_text('email', 'Email', $email, $error);
$html .= Form::input_text('postalcode', 'PLZ', $postalcode, $error);
$html .= Form::input_text('city', 'Ort', $city, $error);
$countries   = new State($country_id);                                                   //Load country   
$html .= Form::input_select('country_id', 'Land', $countries->getCountries(), 'de', 'id', $country_id , $error, 'getStates(this.value, \'state_id\');');
$html .= Form::input_select('state_id', 'Bundesland/Region', $countries->getStates('profile',$country_id), 'state', 'id', $state_id , $error);
if ($func == 'new' OR $func == 'editUser'){
$html .= Form::input_text('password', 'Kennwort', null, $error, '','password');
$html .= Form::input_checkbox('show_pw', 'Passwort anzeigen', $show_pw, $error, 'checkbox', 'unmask(\'password\', this.checked);');
$html .= Form::input_checkbox('confirm', 'Passwortänderung', $confirm, $error );
}
if ($func == 'new'){
    $roles = new Roles();
    $html .= Form::input_select('role_id', 'Rolle', $roles->get(), 'role', 'id', $role_id , $error); 
    $html .= Form::input_select('institution_id', 'Institution', $USER->institutions, 'institution', 'institution_id', $institution_id , $error);
    $group = new Group();
    $html .= Form::input_select('group_id', 'Lerngruppe', $group->getGroups('institution', $USER->institution_id), 'group', 'id', $group_id , $error); 
}

$html .= '</div><!-- /.modal-body -->
          <div class="modal-footer">';
          $html .= '<button name="submit" type="submit" class="btn btn-primary glyphicon glyphicon-saved pull-right" onclick="document.getElementById(\'form_profile\').submit();"> '.$header.'</button>';    
$html .=  '</div></form></div><!-- /.modal-content -->
           </div><!-- /.modal-dialog -->';
$script = '<script type="text/javascript">
        $(function() {
            $(\'.nyroModal\').nyroModal();
            $(\'#popup_generate\').nyroModal();
        });
        </script>';
echo json_encode(array('html'=>$html, 'script' => $script));