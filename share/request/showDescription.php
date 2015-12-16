<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package core
 * @filename showDescription.php
 * @copyright 2015 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2015.05.31 19:51
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

if (filter_input(INPUT_GET, 'enablingObjectiveID', FILTER_VALIDATE_INT)) {
    $ena    = new EnablingObjective();
    $ena->id= filter_input(INPUT_GET, 'enablingObjectiveID', FILTER_VALIDATE_INT);
    $ena->load();
    
} 
$ter        = new TerminalObjective();
$ter->id    = filter_input(INPUT_GET, 'terminalObjectiveID', FILTER_VALIDATE_INT);
$ter->load();

echo '<div class="messageboxClose" onclick="closePopup();"></div><div class="contentheader">Beschreibung</div>
      <div id="popupcontent">';
if ($ter->description){
    echo '<lable></label><p class="materialtxt">'.$ter->description;
} 

if (isset($ena->description)){ 
        echo '<br>'.$ena->description;
} 

if (!isset($ena->description) && !isset($ter->description)){    
        echo '<p class="materialtxt"><p>Keine Beschreibung vorhanden</p>';
}
    
echo '</p><div class="materialseperator"></div><div class="space-top"></div>
      <input type="submit" name="Submit" value="Fenster schließen" onclick="closePopup()"/>
      </div></div>';