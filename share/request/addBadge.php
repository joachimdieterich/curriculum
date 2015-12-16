<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package core
 * @filename addBadge.php
 * @copyright 2015 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2015.06.01 17:38
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
$base_url = dirname(__FILE__).'/../';
include($base_url.'setup.php');  //Läd Klassen, DB Zugriff und Funktionen

global $USER;
$USER = $_SESSION['USER'];

echo '<div class="messageboxClose"  onclick="closePopup();"></div>   
<div class="contentheader">Badge erstellen</div>
<div id="popupcontent">
    <form method="post" action="index.php?action=view&function=addObjectives">
      <div class="line">
        Die Daten des Badges, die beim Öffnen angezeigt werden.
      </div>                                    
        <p><label>Bild: <span class="red">*</span> </label><input  id="badge_image" name="badge_image" Auswählen...  onclick="tb_show(';
        echo "'','../share/request/uploadframe.php?userID=".filter_input(INPUT_GET, 'userID', FILTER_VALIDATE_INT)."&context=badge&target=badge_image&format=2&multiple=false&TB_iframe=true&width=710&modal=true')";
        echo '" href="#" class="thickbox"/>
        <input type="hidden" name="terminal_objective_id" id="terminal_objective_id" value="'.filter_input(INPUT_GET, 'terminalObjectiveID', FILTER_VALIDATE_INT).'"/>';
      if (filter_input(INPUT_GET, 'enablingObjectiveID', FILTER_VALIDATE_INT) == 'notset') {   
            echo '<input type="hidden" name="enabling_objective_id" id="enabling_objective_id" value="'.filter_input(INPUT_GET, 'enablingObjectiveID', FILTER_VALIDATE_INT).'"/>';
        } else {
            echo '<input type="hidden" name="enabling_objective_id" id="enabling_objective_id" value="0"/>';
        }
echo '<p><label>Name <span class="red">*</span></label><input type="text" name="badge_name" id="badge_name" pattern="^[^\r\n]+$" maxlength="128"></p> 
      <p><label>Badge-Typ <span class="red">*</span></label> <select id="badge_type" name="badge_type">
        <option value="1">Badge</option>
        <option value="2">Milestone Badge</option>
        <option value="3">Master Badge</option>
       </select></p>
      <p><label>Beschreibung <span class="red">*</span></label>
        <textarea name="badge_description" id="badge_description" rows="2" maxlength="1000"></textarea>
      </p>
      <p>
        <label>Kriterien <span class="red">*</span></label>
        <textarea name="badge_criteria" id="badge_criteria" rows="10" maxlength="250000" ></textarea>
      </p>
      <p>
        <label></label><input type="submit" name="add_badge" value="Badge erstellen" />
      </p>
    </form>
</div>'; 