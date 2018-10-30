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
include_once(dirname(__FILE__).'/../login-check.php');  //check login status and reset idletimer
global $CFG, $USER, $COURSE;
$USER           = $_SESSION['USER'];
$func           = $_GET['func'];
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
        case "changePW":    $info       = true;
        case "edit":        checkCapabilities('user:resetPassword', $USER->role_id, false, true);
                            $header     = 'Kennwort ändern';
                            $username   = $USER->username;      
            break;
        case "reset":       checkCapabilities('user:resetPassword', $USER->role_id, false, true);
                            $header     = 'Kennwort zurücksetzen';
                            $user       = new User();
                            if (is_int($_GET['id'])){
                                $user->load('id', filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT));
                                $username   = $user->username;    
                            } else {
                                $username = $USER->username;
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

$content = '<form id="form_password"  class="form-horizontal" role="form" method="post" action="../share/processors/fp_password.php"';

if (isset($currentUrlId)){ $content .= $currentUrlId; }
$content .= '"><input type="hidden" name="func" id="func" value="'.$func.'"/>';

if (isset($info)){
    $content .= Form::info(array('id' => 'p_rule', 'content' => 'Ihr Kennwort wurde neu angelegt bzw. zurückgesetzt. Bitte ändern Sie daher das Kennwort um unbefugten Zugriff auf Ihre Daten zu vermeiden.'));
}
$content .= Form::input_text('username', 'Benutzername', $username, $error,'','text',null, null, 'col-sm-3','col-sm-9', true);
$content .= Form::info(array('id' => 'p_rule', 'content' => 'Das Kennwort muss ...<br>- mind. 8 Zeichen lang sein<br>- mind. 1 Großbuchstaben <br>- mind. 1 Kleinbuchstaben<br>- mind. 1 Zahl<br>- mind. 1 Sonderzeichen <br> enthalten. '));
if ($func != 'reset'){
    $content .= Form::input_text('oldpassword', 'Altes Kennwort', null, $error, '','password');
}
$content .= Form::input_text('password', 'Kennwort', null, $error, '','password');
if ($func != 'reset'){
    $content .= Form::input_text('confirm', 'Kennwort bestätigen', null, $error, '','password');
}
$content.= '</form>';
$footer  = '<button type="submit" class="btn btn-primary pull-right" onclick="document.getElementById(\'form_password\').submit();"><i class="fa fa-floppy-o margin-r-5"></i>'.$header.'</button> ';
   
$html    = Form::modal(array('title'     => $header,
                             'content'   => $content, 
                             'f_content' => $footer)); 

echo json_encode(array('html'=>$html));