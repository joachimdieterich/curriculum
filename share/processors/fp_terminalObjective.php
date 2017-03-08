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
$terminal_objective                     = new TerminalObjective();

$purify                                 = HTMLPurifier_Config::createDefault();
$purify->set('Core.Encoding', 'UTF-8'); // replace with your encoding
$purify->set('HTML.Doctype', 'HTML 4.01 Transitional'); // replace with your doctype
$purifier                               = new HTMLPurifier($purify);

$terminal_objective->terminal_objective = $purifier->purify(filter_input(INPUT_POST, 'terminal_objective', FILTER_UNSAFE_RAW));   
$terminal_objective->description        = $purifier->purify(filter_input(INPUT_POST, 'description', FILTER_UNSAFE_RAW));   
 
$gump = new Gump();    /* Validation */
$_POST = $gump->sanitize($_POST);       //sanitize $_POST
$terminal_objective->curriculum_id      = filter_input(INPUT_POST, 'curriculum_id', FILTER_VALIDATE_INT);
$terminal_objective->color              = filter_input(INPUT_POST, 'color',         FILTER_SANITIZE_STRING);

$gump->validation_rules(array(
'terminal_objective'         => 'required'
));
$validated_data = $gump->run($_POST);
if($validated_data === false) {/* validation failed */
    $_SESSION['FORM']           = new stdClass();
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
if (isset($CFG->repository)){ // prüfen, ob Repository Plugin vorhanden ist.
    $repo = $CFG->repository;
}
if (filter_input(INPUT_POST, 'reference', FILTER_UNSAFE_RAW)){
    $repo->setReference('terminal_objective', $ter_id, filter_input(INPUT_POST, 'reference', FILTER_UNSAFE_RAW));
} else {
    $repo->setReference('terminal_objective', $ter_id, ''); // to process update
}

header('Location:'.$_SESSION['PAGE']->target_url);