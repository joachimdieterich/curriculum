<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package core
 * @filename f_terminalObjective.php
 * @copyright 2015 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2015.06.01 17:09
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

global $USER, $CFG;
$USER                   = $_SESSION['USER'];
$terminal_objective_id  = '';
$terminal_objective     = '';
$description            = '';
$curriculum_id          = '';
$reference              = '';
$color                  = '#3cc95b';
$error                  = '';
$ter_objective          = new TerminalObjective(); 
$func                   = $_GET['func'];
switch ($func) {
    case 'edit':    $ter_objective->id            = $_GET['id'];
                    $terminal_objective_id        = $ter_objective->id;
                    $ter_objective->load();                                 //Läd die bestehenden Daten aus der db
                    foreach ($ter_objective as $key => $value){
                        $$key = $value;
                    }
                    $omega                        = new Omega();
                    $reference                    = $omega->getReference('terminal_objective', $ter_objective->id);
                    $header                       = 'Thema bearbeiten';           
        break;
    case 'new':     $ter_objective->curriculum_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
                    $curriculum_id                = $ter_objective->curriculum_id;
                    $header                       = 'Thema hinzufügen';
        break;
    
    default:
        break;
}

if (is_object($_SESSION['FORM'])) {
    foreach ($_SESSION['FORM'] as $key => $value){
        $$key = $value;
    }
}

$content = '<form id="form_terminal_objective" method="post" action="../share/processors/fp_terminalObjective.php">
 <div class="form-horizontal"><div class="form-group">   
<input type="hidden" name="terminal_objective_id" id="terminal_objective_id" value="'.$terminal_objective_id.'"/> 
<input type="hidden" name="curriculum_id" id="curriculum_id" value="'.$curriculum_id.'"/>
<input type="hidden" name="func" id="func" value="'.$func.'"/>'; 
$content .= Form::input_textarea('terminal_objective', 'Thema', $terminal_objective, $error, 'z.B. ');
$content .= Form::input_textarea('description', 'Beschreibung', $description, $error, 'z.B. ');
$content .= Form::input_text('reference', 'OMEGA Link', $reference, $error, 'Beschreibung');
$content .= Form::input_color(array('id' => 'color', 'rgb' => $color, 'error' => $error));

$f_content = '';
if ($func == 'edit'){ 
    $f_content .= '<button id="update_terminal_objective" name="update_terminal_objective" type="submit" class="btn btn-primary fa fa-check-circle-o pull-right" onclick="document.getElementById(\'form_terminal_objective\').submit();"> Thema aktualisieren</button>';
} else {
    $f_content .= '<button id="add_terminal_objective" name="add_terminal_objective" type="submit" class="btn btn-primary fa fa-plus pull-right" onclick="document.getElementById(\'form_terminal_objective\').submit();"> Thema hinzufügen</button>';
}
$content .= '</div></div></form>';
$html     = Form::modal(array('title'     => $header,
                              'content'   => $content, 
                              'f_content' => $f_content));  

$script = "<!-- daterangepicker -->
       <script id='modal_script'>alert('test');
        $.getScript('".$CFG->base_url ."public/assets/templates/AdminLTE-2.3.0/plugins/colorpicker/bootstrap-colorpicker.min.js', function (){
        $('.color-picker').colorpicker();
        });</script>";
echo json_encode(array('html'=> $html, 'script' => $script));