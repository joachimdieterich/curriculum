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
include(dirname(__FILE__).'/../setup.php');  // Klassen, DB Zugriff und Funktionen
include(dirname(__FILE__).'/../login-check.php');  //check login status and reset idletimer
global $USER, $CFG;
$USER   = $_SESSION['USER'];
if (!isset($_SESSION['PAGE']->target_url)){     //if target_url is not set -> use last PAGE url
    $_SESSION['PAGE']->target_url       = $_SESSION['PAGE']->url;
}
$absent              = new Absent();

$gump = new Gump();    /* Validation */
$_POST = $gump->sanitize($_POST);       //sanitize $_POST

$absent->cb_id       = $_POST['reference_id']; 
if (isset($_POST['absent_id'])){
    $absent->id          = $_POST['absent_id']; 
}
$absent->reason      = $_POST['reason'];
if (isset($_POST['user_list'])){
    $user_list           = $_POST['user_list'];
}
if (isset($_POST['status'])){
    $absent->status  = 1;
    $absent->done    = date("Y-m-d H:i:s");
} else {
    $absent->status  = 0;
    $absent->done    = date("Y-m-d H:i:s");
}
if (isset($_POST['user_id'])){
    $absent->user_id        = $_POST['user_id'];
}
        
$gump->validation_rules(array(
'reason'             => 'required'
));
$validated_data = $gump->run($_POST);
if($validated_data === false) {/* validation failed */
    $_SESSION['FORM'] = new stdClass();
    $_SESSION['FORM']->form      = 'absent';
    foreach($absent as $key => $value){
        $_SESSION['FORM']->$key  = $value;
    } 
    $_SESSION['FORM']->user_list = $user_list;
    $_SESSION['FORM']->error     = $gump->get_readable_errors();
    $_SESSION['FORM']->func      = $_POST['func'];
} else {
    if ($_POST['func'] == 'edit'){
        $absent->id         = $_POST['absent_id'];
        $absent->update();
    }  else {
        foreach ($user_list as $value) {
            $absent->user_id = $value;
            if ($absent->add()){
                $_SESSION['PAGE']->message[] = array('message' => $USER->resolveUserId($value).' als abwesend eingetragen.', 'icon' => 'fa-envelope-o text-success');
            } else {
                $_SESSION['PAGE']->message[] = array('message' => $USER->resolveUserId($value).' konnte nicht als abwesend eingetragen werden.', 'icon' => 'fa-envelope-o text-danger');
            }
        }
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