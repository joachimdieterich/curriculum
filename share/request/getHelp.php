<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package core
 * @filename getHelp.php
 * @copyright 2015 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2015.06.01 17:00
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
$base_url               = dirname(__FILE__).'/../';
include($base_url.'setup.php');  //Läd Klassen, DB Zugriff und Funktionen

global $USER;
$USER                   = $_SESSION['USER'];

$enabling_objective     = new EnablingObjective();
$enabling_objective->id = filter_input(INPUT_GET, 'enablingObjectiveID', FILTER_VALIDATE_INT);
$enabling_objective->load();
$result                 = $enabling_objective->getAccomplishedUsers(filter_input(INPUT_GET, 'group', FILTER_VALIDATE_INT));

echo '<div class="messageboxClose" onclick="closePopup();"></div><div class="contentheader">Hilfe</div>
      <div id="popupcontent">';
if ($result){
echo 'Folgende Benutzer haben das Lernziel: <br><br>"',$enabling_objective->enabling_objective,'"<br><br> bereits erreicht und können dir helfen:<br><br>';

$users                  = new User();
    if (count($result)> 10){$max = 10;} else {$max = count($result);}
    for($i = 0; $i < $max; $i++) {
      $users->load('id', $result[$i],false);
      echo $users->username, ': <a href="index.php?action=messages&function=shownewMessage&help_request=true&receiver_id=',$users->id,'&subject=',$enabling_objective->id,'">Benutzer kontaktieren</a><br>';
    }
} else {
    echo 'Leider gibt es keinen Benutzer, der dieses Lernziel erreicht hat';
}
echo '<br><input type="submit" name="Submit" value="Fenster schließen" onclick="closePopup()"/></div></div>';