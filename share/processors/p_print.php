<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename p_print.php
* @copyright 2016 Joachim Dieterich
* @author Joachim Dieterich
* @date 2016.09.25 14:54
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
global $USER;
$USER   = $_SESSION['USER'];
$func   = filter_input(INPUT_GET, 'func',  FILTER_SANITIZE_STRING);
$id     = filter_input(INPUT_GET, 'val',   FILTER_SANITIZE_STRING); // kein INT --> System ID -1
switch ($func) {
    case "certificate":         $t = new Certificate();         break;
    case "curriculum":          $t = new Curriculum();          break;
    case "grade":               $t = new Grade();               break;
    case "group":               $t = new Group();               
                                /*$t->id   = intval($id);
                                $t->load();
                                */
                                $content = Render::table(array(  'header' => array('id'   => 'id', 
                                                'group' => 'Gruppe', 
                                                'description' => 'Beschreibung', 
                                                'grade'         => '(Klassen)stufe',  
                                                'semester'      => 'Lernzeitraum',
                                                'institution'   => 'Institution / Schule',
                                                'creation_time' => 'Erstellungsdatum',
                                                'username'      => 'Erstellt von'), 
                                              'data' => $t->getGroups('group', $USER->id,'groupP'),
                                              'width_class'   => 'row col-xs-12 col-sm-6 col-md-3 col-lg-3',
                                              'style'         => 'padding-left: 20px; padding-right: 20px;',
                                              'table_class'   => 'table table-bordered'));
        break;
    case "role":                $t = new Roles();               break;      
    case "semester":            $t = new Semester();            break;
    case "subject":             $t = new Subject();             break;
    case "user":                $t = new User();                break;
    case "institution":         $t = new Institution();         break;
    case "message":             $t = new Mail();                break;
    case "enablingObjectives":  $t = new EnablingObjective();   break;
    case "terminalObjectives":  $t = new TerminalObjective();   break;
    case "task":                $t = new Task();                break;
    case "courseBook":          $t       = new CourseBook();       
                                $t->id   = intval($id);
                                $t->load();
                                $content = Printer::coursebook(array('coursebook' => array($t)));
        break;
    default: break;
}


//error_log($content);
$_SESSION['PAGE']->print          = new stdClass();
$_SESSION['PAGE']->print->content = $content;