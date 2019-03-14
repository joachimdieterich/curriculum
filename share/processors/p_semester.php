<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename setSemester.php
* @copyright 2015 Joachim Dieterich
* @author Joachim Dieterich
* @date 2015.06.03 10:28
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
$_SESSION['USER']->semester_id    = filter_input(INPUT_GET, 'val', FILTER_VALIDATE_INT);      // Neuer Lernzeitraum übernehmen
/*$TEMPLATE->assign('my_semester_id', $_SESSION['USER']->semester_id); */
//$change_semester      = new Semester($_SESSION['USER']->semester_id);
$us = new User();                                                                                     // $USER hier noch nicht verfügbar
$us->id = $_SESSION['USER']->id;
$us->setSemester($_SESSION['USER']->semester_id);
$_SESSION['USER'] = NULL;                                                                             // Beim Wechsel des Lernzeitraumes muss Session neu geladen werden, damit die entsprechende Rolle geladen wird.
session_reload_user();