<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
 * FormProcessor
 * @package core
 * @filename fp_terminalObjective.php
 * @copyright 2015 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2016.06.03 10:28
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
$terminal_objective                     = new TerminalObjective();

$purify                                 = HTMLPurifier_Config::createDefault();
$purify->set('Core.Encoding', 'UTF-8'); // replace with your encoding
$purify->set('HTML.Doctype', 'HTML 4.01 Transitional'); // replace with your doctype
$purifier                               = new HTMLPurifier($purify);

$terminal_objective->terminal_objective = $purifier->purify(filter_input(INPUT_POST, 'terminal_objective', FILTER_UNSAFE_RAW));   
$terminal_objective->description        = $purifier->purify(filter_input(INPUT_POST, 'description', FILTER_UNSAFE_RAW));   
 
$gump = new Gump();    /* Validation */
$_POST = $gump->sanitize($_POST);       //sanitize $_POST
$terminal_objective->curriculum_id      = $_POST['curriculum_id'];

$terminal_objective->color              = $_POST['color'];
$terminal_objective->creator_id         = $USER->id;

$gump->validation_rules(array(
'terminal_objective'         => 'required'
));
$validated_data = $gump->run($_POST);
if($validated_data === false) {/* validation failed */
    $_SESSION['FORM']->form     = 'terminalObjective';
    foreach($terminal_objective as $key => $value){
        $_SESSION['FORM']->$key = $value;
    } 
    $_SESSION['FORM']->error = $gump->get_readable_errors();
    $_SESSION['FORM']->func  = $_POST['func'];
} else {
    if ($_POST['func'] == 'edit'){
        $terminal_objective->id = $_POST['terminal_objective_id'];
        $terminal_objective->update();
        $ter_id = $terminal_objective->id;
    } else {
        $ter_id = $terminal_objective->add();   
    }
    $_SESSION['anchor'] = 'ter_'.$ter_id;           // set anchor to jump to new terminal objective
    $_SESSION['FORM']   = null; // reset Session Form object
    $curriculum         = $_POST['curriculum_id'];   
}
$omega              = new Omega();
if (filter_input(INPUT_POST, 'reference', FILTER_UNSAFE_RAW)){
    $omega->setReference('terminal_objective', $ter_id, filter_input(INPUT_POST, 'reference', FILTER_UNSAFE_RAW));
} else {
    $omega->setReference('terminal_objective', $ter_id, ''); // to process update
}

header('Location:'.$_SESSION['PAGE']->target_url);