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
global $USER, $CFG;
$USER           = $_SESSION['USER'];
$role_id        = null;
$role           = null;
$description    = null;
$error          = null; 
$role_obj       = new Roles(); 
$func           = $_GET['func'];

switch ($func) {
    case 'new':     $header                       = 'Benutzerrolle hinzufügen';
                    $role_obj         = new Capability();
                    $capabilities     = $role_obj->getCapabilities(0);     
        break;
    case 'edit':    $edit_role        = new Roles();
                    $edit_role->id    = filter_input(INPUT_GET, 'id', FILTER_UNSAFE_RAW); // kein INT da Systemrolle -1
                    $edit_role->load();
                    $role_id          = $edit_role->id;
                    foreach ($edit_role as $key => $value){
                        $$key = $value;
                        //error_log($key. ': '.$value);
                    }
                    $header                       = 'Benutzerrolle aktualisieren';           
        break;
    
    
    default:
        break;
}
/* if validation failed */
if (isset($_SESSION['FORM'])){
    if (is_object($_SESSION['FORM'])) {
        foreach ($_SESSION['FORM'] as $key => $value){
            $$key = $value;
        }
    }
}

$content = '<form id="form_role" method="post" action="../share/processors/fp_role.php">
            <div class="form-horizontal"><div class="form-group">   
            <input type="hidden" name="role_id" id="role_id" value="'.$role_id.'"/>
            <input type="hidden" name="func" id="func" value="'.$func.'"/>'; 
$content .= Form::input_text('role', 'Rollenname', $role, $error, 'z. B. Schul-Administrator');
$content .= Form::input_text('description', 'Beschreibung', $description, $error, 'Beschreibung');
$section = '';
foreach ($capabilities as $key => $value) {
    $pos = strpos($value->capability, ":");

    if ($section != substr($value->capability, 0, $pos)){
        $content .= '<div class="form-group">
                     <label class="control-label col-sm-6" for=""></label>
                     <div class="col-sm-6"><h4>'.substr($value->capability, 0, $pos).'</h4></div></div>';
                
    }
    $content .= Form::input_switch($value->capability, $value->name, $value, $error, 'col-sm-6', 'col-sm-6');
    $section = substr($value->capability, 0, $pos);
}
$f_content = '';
if ($func == 'edit'){ 
    $f_content .= '<button type="submit" class="btn btn-primary fa fa-check-circle-o pull-right" onclick="document.getElementById(\'form_role\').submit();"> '.$header.'</button>';
} else {
    $f_content .= '<button type="submit" class="btn btn-primary fa fa-plus pull-right" onclick="document.getElementById(\'form_role\').submit();"> '.$header.'</button>';
}
$content .= '</div></div></form>';
$html     = Form::modal(array('title'     => $header,
                              'content'   => $content, 
                              'f_content' => $f_content));  

echo json_encode(array('html'=>$html));