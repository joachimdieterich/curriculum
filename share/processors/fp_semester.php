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
include_once(dirname(__FILE__).'/../setup.php');  // Klassen, DB Zugriff und Funktionen
include_once(dirname(__FILE__).'/../login-check.php');  //check login status and reset idletimer
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
//$semester->creator_id       = $USER->id; // now set in add function

$gump->validation_rules(array(
'semester'             => 'required'
));
$validated_data = $gump->run($_POST);
if($validated_data === false) {/* validation failed */
    $_SESSION['FORM']            = new stdClass();
    $_SESSION['FORM']->form      = 'semester';
    foreach($semester as $key => $value){
        $_SESSION['FORM']->$key  = $value;
    } 
    $_SESSION['FORM']->error     = $gump->get_readable_errors();
    $_SESSION['FORM']->func      = $_POST['func'];
} else {
    if ($_POST['func'] == 'edit'){
        $semester->id  = $_POST['semester_id'];
        $semester->update();
        $_SESSION['PAGE']->message[] = array('message' => 'Semester aktualisiert', 'icon' => 'fa-history text-success');
    } else {
        $semester->add(); 
        SmartyPaginate::setTotal(SmartyPaginate::getTotal('semesterP')+1, 'semesterP');
        $_SESSION['PAGE']->target_url = SmartyPaginate::getLastPageIndexURL('semesterP'); //jump to new entry in list
        $_SESSION['PAGE']->message[] = array('message' => 'Semester hinzufgefügt', 'icon' => 'fa-history text-success');
    }
    $_SESSION['FORM']            = null;                     // reset Session Form object
}

header('Location:'.$_SESSION['PAGE']->target_url);