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
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by  
 * the Free Software Foundation; either version 3 of the License, or (at your option) any later version.
 *                                                                       
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of        
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details:
 *                                                                       
 * http://www.gnu.org/copyleft/gpl.html      
 */
$base_url   = dirname(__FILE__).'/../';
include($base_url.'setup.php');  //Läd Klassen, DB Zugriff und Funktionen
include(dirname(__FILE__).'/../login-check.php');  //check login status and reset idletimer
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
                    $omega                        = new Omega();
                    $reference                    = $omega->getReference('enabling_objective', $ena_objective->id);
                    $header                       = 'Ziel bearbeiten';           
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

$content = '<form id="form_enabling_objective" method="post" action="../share/processors/fp_enablingObjective.php">
 <div class="form-horizontal"><div class="form-group">   
<input type="hidden" name="curriculum_id" id="curriculum_id" value="'.$curriculum_id.'"/>
<input type="hidden" name="terminal_objective_id" id="terminal_objective_id" value="'.$terminal_objective_id.'"/> 
<input type="hidden" name="enabling_objective_id" id="enabling_objective_id" value="'.$enabling_objective_id.'"/> 
<input type="hidden" name="func" id="func" value="'.$func.'"/>'; 
$content .= Form::input_textarea('enabling_objective', 'Ziel', $enabling_objective, $error, 'z.B. ');
$content .= Form::input_textarea('description', 'Beschreibung', $description, $error, 'z.B. ');
$content .= Form::input_text('reference', 'OMEGA Link', $reference, $error, 'Beschreibung');

$intervals = new Interval();
$content .= Form::input_select('repeat_interval', 'Ziel wiederholen?',$intervals->getIntervals(), 'description', 'repeat_interval', $repeat_interval, $error );

$f_content = '';
if ($func == 'edit'){ 
    $f_content .= '<button type="submit" class="btn btn-primary fa fa-check-circle-o pull-right" onclick="document.getElementById(\'form_enabling_objective\').submit();"> Ziel aktualisieren</button>';
} else {
    $f_content .= '<button type="submit" class="btn btn-primary fa fa-plus pull-right" onclick="document.getElementById(\'form_enabling_objective\').submit();"> Ziel hinzufügen</button>';
}
$content .= '</div></div></form>';
$html     = Form::modal(array('title'     => $header,
                              'content'   => $content, 
                              'f_content' => $f_content));  
echo json_encode(array('html'=> $html));