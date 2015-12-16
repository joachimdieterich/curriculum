<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package core
 * @filename getBadge.php
 * @copyright 2015 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2015.06.01 17:38
 * @license: 
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by  
 * the Free Software Foundation; either version 3 of the License, or(at your option) any later version.
 *                                                                       
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details:
 *                                                                       
 * http://www.gnu.org/copyleft/gpl.html      
 */
$base_url   = dirname(__FILE__).'/../';
include($base_url.'setup.php');  //Läd Klassen, DB Zugriff und Funktionen

global $USER;
$USER           = $_SESSION['USER'];
$slug           = new Badges();    
if (filter_input(INPUT_GET, 'enablingObjectiveID', FILTER_VALIDATE_INT)) {
    $badge_slug = $slug->getBadgeSlug(filter_input(INPUT_GET, 'terminalObjectiveID', FILTER_VALIDATE_INT), filter_input(INPUT_GET, 'enablingObjectiveID', FILTER_VALIDATE_INT));    
} else {
    $badge_slug = $slug->getBadgeSlug(filter_input(INPUT_GET, 'terminalObjectiveID', FILTER_VALIDATE_INT));
}
if ($badge_slug){
    $connection = new BadgekitConnection();
    $badge      = $connection->getBadge('badgekit', $badge_slug);
} else { $badge = false; }
echo '<div class="messageboxClose"  onclick="closePopup();"></div><div class="contentheader">Badge</div>
      <div id="popupcontent">';
if (!$badge){
    echo 'Es gibt kein Badge zum gewählten Lernziel.<p><label></label><input type="submit" value="OK" onclick="closePopup()"></p>';
} else {
    echo '<div class="floatright"><img src="'.$badge->imageUrl.'" height="150px"> </div>
    <p><label class="badge_label"><strong>Details</strong></label></p> 
    <p><label class="badge_label">Name:</label>'.$badge->name.'</p> 
    <p><label class="badge_label">Beschreibung:</label>'.$badge->earnerDescription.'</p>
    <p><label class="badge_label">Kriterien:</label><a href="'.$badge->criteriaUrl.'" target="_blank">anzeigen</a></p>
    <p><label class="badge_label">Erstellungsdatum:</label>'.$badge->created.'</p>
    <br>
    <p><label class="badge_label"><strong>Herausgeber</strong></label></p>        
    <p><label class="badge_label">Name:</label>'.$badge->system->name.'</p>
    <p><label class="badge_label">URL:</label>'.$badge->system->url.'</p> 
    <p><label class="badge_label">Email:</label>'.$badge->system->email.'</p> 
    <div class="materialseperator"></div>
    <p class="center"><input type="submit" name="Submit" value="Fenster schließen" onclick="closePopup()"/></p>';
}  
echo '</div></div>';