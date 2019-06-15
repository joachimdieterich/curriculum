<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename f_role.php
* @copyright 2016 Joachim Dieterich
* @author Joachim Dieterich
* @date 2016.05.28 21:51
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
global $USER, $CFG;
$USER           = $_SESSION['USER'];
//$role_id        = null;
$role           = null;
$description    = null;
$error          = null; 
$role_obj       = new Roles(); 
$func           = $_GET['func'];

switch ($func) {
    case 'new':     $header           = 'Benutzerrolle hinzufügen';
                    $role_obj         = new Capability();
                    $capabilities     = $role_obj->getCapabilities($CFG->settings->standard_role);     
        break;
    case 'edit':    $edit_role        = new Roles();
                    $edit_role->load('id', filter_input(INPUT_GET, 'id', FILTER_UNSAFE_RAW), true);                                     //load capabilities // INPUT_GET kein INT da Systemrolle -1
                    $role_id          = $edit_role->id;
                    extract($edit_role);
                    $header           = 'Benutzerrolle aktualisieren';           
        break;
    default:
        break;
}
/* if validation failed */
if (isset($_SESSION['FORM'])){
    if (is_object($_SESSION['FORM'])) {
        extract($_SESSION['FORM']);
    }
}

$content = '<form id="form_role" method="post" action="../share/processors/fp_role.php">';
if (isset($role_id)){
    $content .= '<input type="hidden" name="role_id" id="role_id" value="'.$role_id.'"/>';
}
$content .= '<input type="hidden" name="func" id="func" value="'.$func.'"/>'; 
$content .= Form::input_text('role', 'Rollenname', $role, $error, 'z. B. Schul-Administrator');
$content .= Form::input_text('description', 'Beschreibung', $description, $error, 'Beschreibung');
$section = '';
foreach ($capabilities as $key => $value) {
    $pos = strpos($value->capability, ":");

    if ($section != substr($value->capability, 0, $pos)){
        $content .= '<div class="form-group">
                     <label class="control-label col-sm-3" for=""></label>
                     <div class="col-sm-9"><h4>'.substr($value->capability, 0, $pos).'</h4></div></div>';
    }
    if (isset($_SESSION['FORM']->{$value->capability})){$value->permission = 1;} // if validation failed get permission from session
    $content .= Form::input_switch($value->capability, $value->name, $value->permission, $error, true, 'col-sm-6', 'col-sm-6');
    $section = substr($value->capability, 0, $pos);
}
$content .= '</form>';
$footer   = '<button type="submit" class="btn btn-primary pull-right" onclick="document.getElementById(\'form_role\').submit();"><i class="fa fa-floppy-o margin-r-5"></i>'.$header.'</button>';

$html     = Form::modal(array('title'     => $header,
                              'content'   => $content, 
                              'f_content' => $footer));  

echo json_encode(array('html'=>$html));