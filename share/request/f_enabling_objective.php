<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename f_enablingObjective.php
* @copyright 2015 Joachim Dieterich
* @author Joachim Dieterich
* @date 2015.09.27 14:46
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
include_once(dirname(__FILE__).'/../login-check.php');  //check login status and reset idletimer
global $USER, $CFG;
$USER                   = $_SESSION['USER'];
$enabling_objective_id  = '';
$enabling_objective     = '';
$description            = '';
$curriculum_id          = '';
$terminal_objective_id  = '';
$reference              = '';
$repeat_interval        = 0;
$error                  = '';
$ena_objective          = new EnablingObjective(); 
$func                   = $_GET['func'];

switch ($func) {
    case 'edit':    $ena_objective->id            = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);  // edit case: id == ena_id
                    $enabling_objective_id        = $ena_objective->id;
                    $ena_objective->load();                                 //Läd die bestehenden Daten aus der db
                    foreach ($ena_objective as $key => $value){
                        $$key = $value;
                    }
                    if (isset($CFG->repository)){ // prüfen, ob Repository Plugin vorhanden ist.
                        $reference                = $CFG->repository->getReference('enabling_objective', $ena_objective->id);
                    }
                    $header                       = 'Ziel aktualisieren';           
        break;
    case 'new':     $ter_objective                = new TerminalObjective();
                    $ter_objective->id            = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT); // new case: id == ter_id
                    $ter_objective->load();
                    $curriculum_id                = $ter_objective->curriculum_id;
                    $terminal_objective_id        = $ter_objective->id;
                    $header                       = 'Ziel hinzufügen';
        break;
    
    default:
        break;
}

/* if validation failed, get formdata from session*/
if (isset($_SESSION['FORM'])){
    if (is_object($_SESSION['FORM'])) {
        foreach ($_SESSION['FORM'] as $key => $value){
            $$key = $value;
        }
    }
}

$content = '<form id="form_enabling_objective" method="post" action="../share/processors/fp_enabling_objective.php">
<input type="hidden" name="curriculum_id" id="curriculum_id" value="'.$curriculum_id.'"/>
<input type="hidden" name="terminal_objective_id" id="terminal_objective_id" value="'.$terminal_objective_id.'"/> 
<input type="hidden" name="enabling_objective_id" id="enabling_objective_id" value="'.$enabling_objective_id.'"/> 
<input type="hidden" name="func" id="func" value="'.$func.'"/>'; 
$content .= Form::input_textarea('enabling_objective', 'Ziel', $enabling_objective, $error, 'z.B. ');
$content .= Form::input_textarea('description', 'Beschreibung', $description, $error, 'z.B. ');
$content .= Form::input_text('reference', 'Externe Referenz', $reference, $error, 'Beschreibung');

$intervals = new Interval();
$content .= Form::input_select('repeat_interval', 'Ziel wiederholen?',$intervals->getIntervals(), 'description', 'repeat_interval', $repeat_interval, $error );
$content .= '</form>';
$footer   = '<button type="submit" class="btn btn-primary pull-right" onclick="document.getElementById(\'form_enabling_objective\').submit();"><i class="fa fa-floppy-o margin-r-5"></i>'.$header.'</button>';

$html     = Form::modal(array('title'     => $header,
                              'content'   => $content, 
                              'f_content' => $footer));  
echo json_encode(array('html'=> $html));