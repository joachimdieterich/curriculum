<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
 * FormProcessor
 * @package core
 * @filename fp_absent.php
 * @copyright 2016 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2016.05.25 09:17
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
$user                = new User();

$gump = new Gump();    /* Validation */
$_POST = $gump->sanitize($_POST);       //sanitize $_POST

$absent_id           = $_POST['absent_id']; 
$reference_id        = $_POST['reference_id']; 
$user_list           = $
$task->creator_id    = $USER->id;
        
$gump->validation_rules(array(
'absent_id'             => 'required'
));
$validated_data = $gump->run($_POST);
if($validated_data === false) {/* validation failed */
    $_SESSION['FORM']->form      = 'absent';
    foreach($task as $key => $value){
        $_SESSION['FORM']->$key  = $value;
        //error_log($key.': '.$_SESSION['FORM']->$key);
    } 
    $_SESSION['FORM']->error     = $gump->get_readable_errors();
    $_SESSION['FORM']->func      = $_POST['func'];
} else {
    if ($_POST['func'] == 'edit'){
        /*$task->id         = $_POST['task_id'];
        $task->update();*/
    }  else {
       /* $task->id = $task->add(); 
        
        if (filter_input(INPUT_POST, 'reference_id', FILTER_VALIDATE_INT)){
            $context = new Context();
            $context->resolve('context', $_POST['func']);
            $task->enrol($context->context_id, filter_input(INPUT_POST, 'reference_id', FILTER_VALIDATE_INT)); 
        }*/
    }
    $_SESSION['FORM']            = null;                     // reset Session Form object 
}

header('Location:'.$_SESSION['PAGE']->target_url);