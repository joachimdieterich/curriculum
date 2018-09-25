<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* FormProcessor
* @package core
* @filename fp_foreknowledge.php
* @copyright 2016 Joachim Dieterich
* @author Daniel Behr
* @date 2018.09.18 16:28
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

$gump               = new Gump();    /* Validation */
$_POST              = $gump->sanitize($_POST);       //sanitize $_POST


header('Location:'.$_SESSION['PAGE']->target_url);

error_log("LOGGO");
if (isset($_POST['enable'])){
    foreach ($_POST['enable'] as $ena_id) { 
       if (! Foreknowledge::checkForeknowledge($ena_id, $_SESSION['CONTEXT']['enabling_objective']->id, $_POST['qualification_id'], $_SESSION['CONTEXT']['enabling_objective']->id)){
           Foreknowledge::insertEnablObjective($ena_id, $_POST['qualification_id']);
       }
    } 
}else{
    if (! Foreknowledge::checkForeknowledge($_POST['terminal'], $_SESSION['CONTEXT']['terminal_objective']->id, $_POST['qualification_id'], $_SESSION['CONTEXT']['enabling_objective']->id)){
           Foreknowledge::insertTerminalObjective($_POST['terminal'], $_POST['qualification_id']);
       }
}