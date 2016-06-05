<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
 * FormProcessor
 * @package core
 * @filename fp_semester.php
 * @copyright 2016 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2016.05.15 20:33
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
$USER   = $_SESSION['USER'];
if (!isset($_SESSION['PAGE']->target_url)){     //if target_url is not set -> use last PAGE url
    $_SESSION['PAGE']->target_url       = $_SESSION['PAGE']->url;
}
$semester                     = new Semester();                      

$gump = new Gump();    /* Validation */
$_POST = $gump->sanitize($_POST);       //sanitize $_POST

$semester->semester         = $_POST['semester'];
$semester->description      = $_POST['description'];
$semester->timerange        = $_POST['timerange'];
$semester->institution_id   = $_POST['institution_id'];
$semester->creator_id       = $USER->id;

$gump->validation_rules(array(
'semester'             => 'required'
));
$validated_data = $gump->run($_POST);
if($validated_data === false) {/* validation failed */
    $_SESSION['FORM']->form      = 'semester';
    foreach($enabling_objective as $key => $value){
        $_SESSION['FORM']->$key  = $value;
        //error_log($key.': '.$_SESSION['FORM']->$key);
    } 
    $_SESSION['FORM']->error     = $gump->get_readable_errors();
    $_SESSION['FORM']->func      = $_POST['func'];
} else {
    if ($_POST['func'] == 'edit'){
        $semester->id  = $_POST['semester_id'];
        $semester->update();
    } else {
        $semester->add(); 
    }
    $_SESSION['FORM']            = null;                     // reset Session Form object
}

header('Location:'.$_SESSION['PAGE']->target_url);