<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* FormProcessor
* @package core
* @filename fp_wallet.php
* @copyright 2016 Joachim Dieterich
* @author Joachim Dieterich
* @date 2016.12.28 16:55
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
$USER            = $_SESSION['USER'];
if (!isset($_SESSION['PAGE']->target_url)){     //if target_url is not set -> use last PAGE url
    $_SESSION['PAGE']->target_url       = $_SESSION['PAGE']->url;
}

$wallet = new Wallet(); 

$gump                  = new Gump();    /* Validation */
$_POST                 = $gump->sanitize($_POST);       //sanitize $_POST
// todo alle Regeln definieren
$gump->validation_rules(array(
'title'         => 'required',
'description'   => 'required',   
'curriculum_id' => 'required',
'timerange'     => 'required',
'objective_id'  => 'required'
));

$validated_data  = $gump->run($_POST);
if($validated_data === false) {/* validation failed */
    $_SESSION['FORM']            = new stdClass();
    $_SESSION['FORM']->form      = 'wallet';
    foreach($_POST as $key => $value){                                         
        $_SESSION['FORM']->$key  = $value;
    }
    $_SESSION['FORM']->error     = $gump->get_readable_errors();
    $_SESSION['FORM']->func      = $_POST['func']; 
} else {
    if (isset($_POST['id'])){
        $wallet->id      = filter_input(INPUT_POST, 'id',          FILTER_VALIDATE_INT);
    }
    $wallet->title       = filter_input(INPUT_POST, 'title',       FILTER_SANITIZE_STRING);    
    $wallet->description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);    
    $wallet->curriculum_id   = $_POST['curriculum_id'];
    $wallet->file_id     = filter_input(INPUT_POST, 'file_id',     FILTER_VALIDATE_INT);
    $wallet->subject_id  = filter_input(INPUT_POST, 'subject_id',  FILTER_VALIDATE_INT);
    $wallet->timerange   = filter_input(INPUT_POST, 'timerange',   FILTER_UNSAFE_RAW);
    $wallet->objectives  = $_POST['objective_id'];
    
    switch ($_POST['func']) {
        case 'new':     if ($wallet->add()){
                            $_SESSION['PAGE']->message[] = array('message' => 'Sammelmappe hinzufgefügt', 'icon' => 'fa-newspaper-o text-success');
                        }               
            break;
        case 'edit':    if ($wallet->update()){
                            $_SESSION['PAGE']->message[] = array('message' => 'Sammelmappe erfolgreich aktualisiert', 'icon' => 'fa-newspaper-o text-success');
                        }
            break;

        default:
            break;
    }
    $_SESSION['FORM']            = null;                     // reset Session Form object 
}

header('Location:'.$_SESSION['PAGE']->target_url);