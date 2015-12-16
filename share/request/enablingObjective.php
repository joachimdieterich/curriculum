<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package core
 * @filename enablingObjective.php
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

global $USER;
$USER       = $_SESSION['USER'];
$enabling_objective     = new EnablingObjective();
if (filter_input(INPUT_GET, 'edit', FILTER_VALIDATE_BOOLEAN)){
    $enabling_objective->id                     = filter_input(INPUT_GET, 'enablingObjectiveID', FILTER_VALIDATE_INT);
    $enabling_objective->load();    //Läd die bestehenden Daten aus der db
    $omega  = new Omega();
    $reference = $omega->getReference('enabling_objective',$enabling_objective->id);
    $header = 'Ziel bearbeiten';
} else {
    $enabling_objective->curriculum_id          = filter_input(INPUT_GET, 'curriculumID', FILTER_VALIDATE_INT);
    $enabling_objective->terminal_objective_id  = filter_input(INPUT_GET, 'terminalObjectiveID', FILTER_VALIDATE_INT);
    $enabling_objective->repeat_interval        = -1;
    $header = 'Ziel hinzufügen';
}
$help = "curriculumdocs('http://docs.joachimdieterich.de/index.php?title=Ziele');";
echo'<div class="messageboxClose" onclick="closePopup();"></div><div class="contentheader">'.$header.'<input class="curriculumdocsbtn space-left" type="button" name="help" onclick="'.$help.'"/></div>
    <div id="popupcontent"><form method="post" action="index.php?action=view&function=addObjectives">
    <input type="hidden" name="id" id="id" value="';if (isset($enabling_objective->id)){echo $enabling_objective->id;} echo '"/> 
    <input type="hidden" name="curriculum_id" id="curriculum_id" value="';if (isset($enabling_objective->curriculum_id)){echo $enabling_objective->curriculum_id;} echo '"/> 
    <input type="hidden" name="terminal_objective_id" id="terminal_objective_id" value="';if (isset($enabling_objective->terminal_objective_id)){echo $enabling_objective->terminal_objective_id;} echo '"/> 
    <p><label>Ziel: </label><input class="inputlarge" name="enabling_objective" value="';if (isset($enabling_objective->enabling_objective)){echo $enabling_objective->enabling_objective;} echo '"/></p>
    <p><label>Beschreibung: </label><textarea id="tmce_editor" name="description">';
if (isset($enabling_objective->description)){
    echo $enabling_objective->description;}
echo'</textarea></p>';
// Wiederholungen
echo '<p><label >Wiederholung? </label><input class="centervertical" type="checkbox" name="repeat" ';
if ($enabling_objective->repeat_interval != -1){echo'checked';}
echo ' onchange="checkbox_addForm(this.checked,';
echo "'block',";// Wiederholungen
echo "'interval'";
echo ');"/></p><p id="interval" ';
if ($enabling_objective->repeat_interval == -1){echo ' style="display:none;"';}
echo' ><label>Interval: </label>
    <select id="rep_interval"  class="centervertical" name="rep_interval" > 
    <option value="1" data-skip="1"'; if ($enabling_objective->repeat_interval == 1){echo'selected';}   echo '>täglich</option>
    <option value="2" data-skip="1"'; if ($enabling_objective->repeat_interval == 7){echo'selected';}   echo '>wöchentlich</option>
    <option value="3" data-skip="1"'; if ($enabling_objective->repeat_interval == 30){echo'selected';}  echo '>jeden Monat</option>
    <option value="4" data-skip="1"'; if ($enabling_objective->repeat_interval == 182){echo'selected';} echo '>jedes Halbjahr</option>
    <option value="5" data-skip="1"'; if ($enabling_objective->repeat_interval == 365){echo'selected';} echo '>jedes Jahr</option>
    </select></p>  
<p><label>OMEGA Link: </label><input  name="reference" value="';if (isset($reference)){echo $reference;} echo '"/></p>';
if (filter_input(INPUT_GET, 'edit', FILTER_VALIDATE_BOOLEAN)){    
    echo '<p><label></label><input type="submit" name="update_enabling_objective" value="Ziel aktualisieren" /></p>';
} else {
    echo '<p><label></label><input type="submit" name="add_enabling_objective" value="Ziel hinzufügen" /></p>';
}
    echo '</form></div></div>';