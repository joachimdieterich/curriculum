<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
 * FormProcessor
 * @package core
 * @filename fp_courseBook.php
 * @copyright 2016 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2016.05.10 19:48
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
$course_book                = new CourseBook();
$course_book->topic         = filter_input(INPUT_POST, 'topic', FILTER_UNSAFE_RAW);   //--> to get html  // security???                       
$course_book->description   = filter_input(INPUT_POST, 'description', FILTER_UNSAFE_RAW);   //--> to get html  // security???                       

$gump = new Gump();    /* Validation */
$_POST = $gump->sanitize($_POST);       //sanitize $_POST
//$course_book->event_id      = null;
$course_book->course_id     = $_POST['course_id'];
$course_book->timerange     = $_POST['timerange'];
$course_book->task_id       = null;
$course_book->creator_id    = $USER->id;
        

$gump->validation_rules(array(
'topic'             => 'required'
));
$validated_data = $gump->run($_POST);
if($validated_data === false) {/* validation failed */
    $_SESSION['FORM']->form      = 'courseBook';
    foreach($course_book as $key => $value){
        $_SESSION['FORM']->$key  = $value;
        //error_log($key.': '.$_SESSION['FORM']->$key);
    } 
    $_SESSION['FORM']->error     = $gump->get_readable_errors();
    $_SESSION['FORM']->func      = $_POST['func'];
} else {
    if ($_POST['func'] == 'edit'){
        $course_book->id         = $_POST['id'];
        $course_book->update();
    }  else {
        $course_book->add(); 
    }
    $_SESSION['FORM']            = null;                     // reset Session Form object 
}

header('Location:'.$_SESSION['PAGE']->target_url);