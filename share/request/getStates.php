<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package core
 * @filename getStates.php
 * @copyright 2015 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2015.06.03 10:12
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

$state      = new State(filter_input(INPUT_GET, 'country_id', FILTER_VALIDATE_INT));
$states     = $state->getStates();

if (filter_input(INPUT_GET, 'name', FILTER_SANITIZE_STRING)){
    echo '<label>Bundesland: </label><select name="'.filter_input(INPUT_GET, 'name', FILTER_SANITIZE_STRING).'">';
} else {
    echo '<label>Bundesland: </label><select name="state">';
}
for($i = 0; $i < count($states); $i++) {  
  echo  '<option label="'.$states[$i]->state.'" value="'.$states[$i]->id.'"';
  if (filter_input(INPUT_GET, 'name', FILTER_SANITIZE_STRING)){
     if ($states[$i]->id == filter_input(INPUT_GET, 'state_id', FILTER_VALIDATE_INT)) {
        echo ' selected="selected"'; 
     }
  }
  echo '>'.$states[$i]->state.'</option>';
}
echo '</select>';