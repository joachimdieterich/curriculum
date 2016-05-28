<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
 * FormProcessor
 * @package core
 * @filename fp_enablingObjective.php
 * @copyright 2016 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2016.04.16 10:28
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

global $USER, $CFG;
$USER   = $_SESSION['USER'];
if (!isset($_SESSION['PAGE']->target_url)){     //if target_url is not set -> use last PAGE url
    $_SESSION['PAGE']->target_url       = $_SESSION['PAGE']->url;
}
$enabling_objective                     = new EnablingObjective();
$enabling_objective->enabling_objective = filter_input(INPUT_POST, 'enabling_objective', FILTER_UNSAFE_RAW);   //--> to get html  // security???                       
$enabling_objective->description        = filter_input(INPUT_POST, 'description', FILTER_UNSAFE_RAW);   //--> to get html  // security???                       

$gump = new Gump();    /* Validation */
$_POST = $gump->sanitize($_POST);       //sanitize $_POST

$enabling_objective->terminal_objective_id  = $_POST['terminal_objective_id'];
$enabling_objective->curriculum_id          = $_POST['curriculum_id'];
$enabling_objective->repeat_interval        = $_POST['repeat_interval'];
$enabling_objective->creator_id             = $USER->id;

$gump->validation_rules(array(
'curriculum_id'             => 'required',
'enabling_objective'        => 'required'
));
$validated_data = $gump->run($_POST);
if($validated_data === false) {/* validation failed */
    $_SESSION['FORM']->form      = 'enablingObjective';
    foreach($enabling_objective as $key => $value){
        $_SESSION['FORM']->$key  = $value;
        //error_log($key.': '.$_SESSION['FORM']->$key);
    } 
    $_SESSION['FORM']->error     = $gump->get_readable_errors();
    $_SESSION['FORM']->func      = $_POST['func'];
} else {
    if ($_POST['func'] == 'edit'){
        $enabling_objective->id  = $_POST['enabling_objective_id'];
        $enabling_objective->update();
        $ena_id                  = $enabling_objective->id; 
    } else {
        $ena_id                  = $enabling_objective->add(); 
    }
    $_SESSION['anchor']          = 'ena_'.$ena_id;           // set anchor to jump to new terminal objective
    $_SESSION['FORM']            = null;                     // reset Session Form object
    $curriculum                  = $_POST['curriculum_id'];   
}
$omega = new Omega();
if (filter_input(INPUT_POST, 'reference', FILTER_UNSAFE_RAW)){
    $omega->setReference('enabling_objective', $ena_id, filter_input(INPUT_POST, 'reference', FILTER_UNSAFE_RAW));
} else {
    $omega->setReference('enabling_objective', $ena_id, ''); // damit update übernommen wird
}

header('Location:'.$_SESSION['PAGE']->target_url);