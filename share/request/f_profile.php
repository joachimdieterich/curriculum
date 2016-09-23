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
$base_url   = dirname(__FILE__).'/../';
include($base_url.'setup.php');  //Läd Klassen, DB Zugriff und Funktionen
include(dirname(__FILE__).'/../login-check.php');  //check login status and reset idletimer
global $CFG, $USER;
$user           = new User(); 
$USER           = $_SESSION['USER'];
$func           = $_GET['func'];
$avatar         = null;
$user_id        = null;
$avatar_id      = $CFG->standard_avatar_id;;
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
                         }
        break;
        
        case "edit":     checkCapabilities('user:update',    $USER->role_id);
                         $header            = 'Mein Profil aktualisieren';
                         $edit              = true;  
                         $user->load('id',    $USER->id);
                         $user_id           = $USER->id;//Läd die bestehenden Daten aus der db
                         foreach ($user as $key => $value){
                             $$key = $value;
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

$content  = '<form id="form_profile"  class="form-horizontal" role="form" method="post" action="../share/processors/fp_profile.php"';
if (isset($currentUrlId)){ $html .= $currentUrlId; }
$content .= '"><input type="hidden" name="func" id="func" value="'.$func.'"/>
            <input type="hidden" name="user_id" id="user_id" value="'.$user_id.'"/>
            <input type="hidden" name="avatar_id" id="avatar_id" value="'.$avatar_id.'"/>';
if ($func == 'new'){
    $content .= Form::input_text('usr', 'Benutzername', $username, $error);
} else {
    $content .= '<div class="col-xs-3"></div><div class="col-xs-9">'
            . '<a href="'.$CFG->request_url .'uploadframe.php?&context=avatar&target=avatar_id&format=0&multiple=false" class="nyroModal">'
            . '<img id="avatar" style="height:100px; margin-left: -5px; padding-bottom:10px;" src="'.$CFG->access_id_url.$avatar_id.'" alt="Profilfoto">'
            . '</a></div>';
    $content .= Form::input_text('usr', 'Benutzername', $username, $error,'','text',null, null, 'col-sm-3','col-sm-9', true);
}
$content .= Form::input_text('firstname', 'Vorname', $firstname, $error);
$content .= Form::input_text('lastname', 'Nachname', $lastname, $error);
$content .= Form::input_text('email', 'Email', $email, $error);
$content .= Form::input_text('postalcode', 'PLZ', $postalcode, $error);
$content .= Form::input_text('city', 'Ort', $city, $error);
$cs       = new State($country_id);                                                   //Load country   
$content .= Form::input_select('country_id', 'Land', $cs->getCountries(), 'de', 'id', $country_id , $error, 'getValues(\'state\', this.value, \'state_id\');');
$content .= Form::input_select('state_id', 'Bundesland/Region', $cs->getStates('profile',$country_id), 'state', 'id', $state_id , $error);
if ($func == 'new' OR $func == 'editUser'){
$content .= Form::input_text('pw', 'Kennwort', null, $error, '','password');
$content .= Form::input_checkbox('show_pw', 'Passwort anzeigen', $show_pw, $error, 'checkbox', 'unmask(\'pw\', this.checked);');
$content .= Form::input_checkbox('confirm', 'Passwortänderung', $confirm, $error );
}
if ($func == 'new'){
    $roles    = new Roles();
    $content .= Form::input_select('role_id', 'Rolle', $roles->get(), 'role', 'id', $role_id , $error); 
    $content .= Form::input_select('institution_id', 'Institution', $USER->institutions, 'institution', 'institution_id', $institution_id , $error, 'getValues(\'group\', this.value, \'group_id\');');
    $group    = new Group();
    $content .= Form::input_select('group_id', 'Lerngruppe', $group->getGroups('institution', $USER->institution_id), 'group', 'id', $group_id , $error); 
}
$content .= '</div></form>';

$f_content = '<button name="submit" type="submit" class="btn btn-primary glyphicon glyphicon-saved pull-right" onclick="document.getElementById(\'form_profile\').submit();"> '.$header.'</button>';    

$script = '<script id=\'modal_script\'>
        $(function() {
            $(\'.nyroModal\').nyroModal();
            $(\'#popup_generate\').nyroModal();
        });
        $(\'#avatar_id\').change(\'input\', function() {
            document.getElementById("avatar").src = "'.$CFG->access_id_url.'"+document.getElementById("avatar_id").value;
        });
        </script>';

$html     = Form::modal(array('title'     => $header,
                              'content'   => $content, 
                              'f_content' => $f_content));  
echo json_encode(array('html'=>$html, 'script' => $script));