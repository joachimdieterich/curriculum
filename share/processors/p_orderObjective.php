<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename p_orderObjective.php
* @copyright 2015 Joachim Dieterich
* @author Joachim Dieterich
* @date 2016.12.08 08:03
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
$base_url   = dirname(__FILE__).'/../';
include($base_url.'setup.php');  //Läd Klassen, DB Zugriff und Funktionen
include(dirname(__FILE__).'/../login-check.php');  //check login status and reset idletimer
global $USER;

$USER       = $_SESSION['USER'];
$func       = $_GET['func'];
$object     = file_get_contents("php://input");
$data       = json_decode($object, true);
if (is_array($data)) {
    foreach ($data as $key => $value){
        $$key = $value;
    }
}
switch ($func) {
    case 'enabling_objective':  $enabling_objective         = new EnablingObjective();
                                $enabling_objective->id     = filter_input(INPUT_GET, 'val',  FILTER_VALIDATE_INT);
                                $enabling_objective->load();
                                $enabling_objective->order(filter_input(INPUT_GET, 'order', FILTER_SANITIZE_STRING));
                                $_SESSION['anchor'] = 'ena_'.$enabling_objective->id;
        break;                   
    case 'terminal_objective':  $terminal_objective         = new TerminalObjective();
                                $terminal_objective->id     = filter_input(INPUT_GET, 'val',  FILTER_VALIDATE_INT);
                                $terminal_objective->load();
                                $terminal_objective->order(filter_input(INPUT_GET, 'order', FILTER_SANITIZE_STRING));  
                                $_SESSION['anchor'] = 'ter_'.$terminal_objective->id; 
        break;
    default:
        break;
}