<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package core
 * @filename getGroups.php
 * @copyright 2015 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2015.11.24 10:12
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
$USER   = $_SESSION['USER'];

$group  = new Group();
$groups = $group->getGroups('institution', filter_input(INPUT_GET, 'institution_id', FILTER_VALIDATE_INT));

if (filter_input(INPUT_GET, 'name', FILTER_SANITIZE_STRING)){
    echo '<label>Lerngruppe: </label><select name="'.filter_input(INPUT_GET, 'name', FILTER_SANITIZE_STRING).'">';
} else {
    echo '<label>Lerngruppe: </label><select name="group">';
}
for($i = 0; $i < count($groups); $i++) {  
  echo  '<option label="'.$groups[$i]->group.'" value="'.$groups[$i]->id.'"';
  if (filter_input(INPUT_GET, 'name', FILTER_SANITIZE_STRING)){
     if ($groups[$i]->id == filter_input(INPUT_GET, 'group_id', FILTER_VALIDATE_INT)) {
        echo ' selected="selected"'; 
     }
  }
  echo '>'.$groups[$i]->group.'</option>';
}
echo '</select>';