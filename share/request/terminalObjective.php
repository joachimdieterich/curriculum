<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package core
 * @filename editTerminalObjective.php
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

global $USER;
$USER       = $_SESSION['USER'];
$terminal_objective     = new TerminalObjective(); 

if (filter_input(INPUT_GET, 'edit', FILTER_VALIDATE_BOOLEAN)){ 
    $terminal_objective->id = filter_input(INPUT_GET, 'terminalObjectiveID', FILTER_VALIDATE_INT);
    $terminal_objective->load();                                 //Läd die bestehenden Daten aus der db
    $omega  = new Omega();
    $reference = $omega->getReference('terminal_objective', $terminal_objective->id);
    $header = 'Thema bearbeiten';
} else {
    $terminal_objective->curriculum_id  = filter_input(INPUT_GET, 'curriculumID', FILTER_VALIDATE_INT);
    $header = 'Thema hinzufügen';
}
echo '<div class="messageboxClose" onclick="closePopup();"></div><div class="contentheader">Thema bearbeiten</div>
<div id="popupcontent">
<form method="post" action="index.php?action=view&function=addObjectives">
<input type="hidden" name="id" id="id" value="';if (isset($terminal_objective->id)){echo $terminal_objective->id;} echo '"/> 
<input type="hidden" name="curriculum_id" id="curriculum_id" value="'.$terminal_objective->curriculum_id.'"/> 
<p><label>Thema: </label><input class="inputlarge" name="terminal_objective" value="';if (isset($terminal_objective->terminal_objective)){echo $terminal_objective->terminal_objective;} echo '"/></p>
<p><label>Beschreibung: </label><textarea id="tmce_editor" name="description">';
if (isset($terminal_objective->description)){
    echo $terminal_objective->description;}
echo'</textarea></p>
<p><label>OMEGA Link: </label><input  name="reference" value="';if (isset($reference)){echo $reference;} echo '"/></p>';
if (filter_input(INPUT_GET, 'edit', FILTER_VALIDATE_BOOLEAN)){   
    echo '<p><label>Farbe: </label><input name="color" type="color" value="'.$terminal_objective->color.'" style="border:0; border-right:20px solid '.$terminal_objective->color.'"/></p>
          <p><label></label><input type="submit" name="update_terminal_objective" value="Thema aktualisieren" /></p>';
} else {
    echo '<p><label>Farbe: </label><input class="inputlarge" name="color" type="color"/></p>
          <p><label></label><input type="submit" name="add_terminal_objective" value="Thema hinzufügen" /></p>';
} 
echo '</form></div></div>';