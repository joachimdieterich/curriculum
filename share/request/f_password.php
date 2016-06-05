<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package core
 * @filename f_password.php
 * @copyright 2016 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2016.05.28 08:49
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
global $CFG, $USER, $COURSE;
$USER           = $_SESSION['USER'];
$func              = $_GET['func'];

$error             =   null;
$object = file_get_contents("php://input");
$data = json_decode($object, true);
if (is_array($data)) {
    foreach ($data as $key => $value){
        $$key = $value;
    }
}
            
if (isset($func)){
    switch ($func) {
        case "changePW":    $info = true;
        case "edit":        checkCapabilities('user:resetPassword', $USER->role_id);
                            $header     = 'Kennwort ändern';
                            $edit       = true; 
                            $username   = $USER->username;      
            break;
        default: break;
    }
}

/* if validation failed, get formdata from session*/
if (is_object($_SESSION['FORM'])) {
    foreach ($_SESSION['FORM'] as $key => $value){
        $$key = $value;
    }
}

$html ='<div class="modal-dialog" style="overflow-y: initial !important;">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closePopup()"><span aria-hidden="true">×</span></button>
              <h4 class="modal-title">'.$header.'</h4>
            </div>
            <div class="modal-body" style="max-height: 500px; overflow-y: auto;">';
$html .='<form id="form_password"  class="form-horizontal" role="form" method="post" action="../share/processors/fp_password.php"';

if (isset($currentUrlId)){ $html .= $currentUrlId; }
$html .= '"><input type="hidden" name="func" id="func" value="'.$func.'"/>';

if (isset($info)){
    $html .= Form::info('p_rule', ' ', 'Ihr Kennwort wurde neu angelegt bzw. zurückgesetzt. Bitte ändern Sie daher das Kennwort um unbefugten Zugriff auf Ihre Daten zu vermeiden.');
}
$html .= Form::input_text('username', 'Benutzername', $username, $error,'','text',null, null, 'col-sm-4','col-sm-7', true);
$html .= Form::info('p_rule', ' ', 'Das Kennwort muss ...<br>- mind. 8 Zeichen lang sein<br>- mind. 1 Großbuchstaben <br>- mind. 1 Kleinbuchstaben<br>- mind. 1 Zahl<br>- mind. 1 Sonderzeichen <br> enthalten. ');
$html .= Form::input_text('oldpassword', 'Altes Kennwort', null, $error, '','password');
$html .= Form::input_text('password', 'Kennwort', null, $error, '','password');
$html .= Form::input_text('confirm', 'Kennwort bestätigen', null, $error, '','password');
$html .= '</div><!-- /.modal-body -->
          <div class="modal-footer">';
          if (isset($edit)){
              $html .= '<button name="update" type="submit" class="btn btn-primary glyphicon glyphicon-saved pull-right" onclick="document.getElementById(\'form_password\').submit();"> '.$header.'</button>'; 
          } 
          if (isset($add)){
              $html .= '<button id="add" name="add" type="submit" class="btn btn-primary glyphicon glyphicon-ok pull-right" onclick="document.getElementById(\'form_password\').submit();"> '.$header.'</button> ';
          }    
$html .=  '</div></form></div><!-- /.modal-content -->
           </div><!-- /.modal-dialog -->';

echo json_encode(array('html'=>$html));