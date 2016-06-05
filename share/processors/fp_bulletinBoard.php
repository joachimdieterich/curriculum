<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
 * FormProcessor
 * @package core
 * @filename fp_bulletinBoard.php
 * @copyright 2016 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2016.05.28 12:15
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
$bulletinBoard = new Institution();

$purify        = HTMLPurifier_Config::createDefault();
$purify->set('Core.Encoding', 'UTF-8'); // replace with your encoding
$purify->set('HTML.Doctype', 'HTML 4.01 Transitional'); // replace with your doctype
$purifier      = new HTMLPurifier($purify);
$bb_text       = $purifier->purify(filter_input(INPUT_POST, 'text', FILTER_UNSAFE_RAW));

$gump          = new Gump();    /* Validation */
$_POST         = $gump->sanitize($_POST);       //sanitize $_POST

$bb_title      = $_POST['title']; 
$bulletinBoard->id = $USER->institution_id; //todo use input_select in b_bulletinBoard.php
$gump->validation_rules(array(
'title'        => 'required',
'text'         => 'required'
));
$validated_data = $gump->run($_POST);
if($validated_data === false) {/* validation failed */
    $_SESSION['FORM'] = new stdClass();
    $_SESSION['FORM']->form      = 'bulletinBoard'; 
    $_SESSION['FORM']->error     = $gump->get_readable_errors();
    $_SESSION['FORM']->func      = $_POST['func'];
} else {
    $bulletinBoard->setBulletinBoard($bb_title, $bb_text);
    $_SESSION['PAGE']->message[] = array('message' => 'Pinnwand erfolgreich geändert', 'icon' => 'fa-newspaper-o text-success');
    $_SESSION['FORM']            = null;                     // reset Session Form object 
}

header('Location:'.$_SESSION['PAGE']->target_url);