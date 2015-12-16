<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package core
 * @filename setAccObjectives.php
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
$USER                       = $_SESSION['USER'];
$enabling_objectives        = new EnablingObjective();
$enabling_objectives->id    = filter_input(INPUT_GET, 'enablingObjectiveID', FILTER_VALIDATE_INT); 
$enabling_objectives->load();
$users = explode(",", filter_input(INPUT_GET, 'userID'));
foreach ($users as $value) {
    $enabling_objectives->setAccomplishedStatus('teacher', $value, filter_input(INPUT_GET, 'creatorID', FILTER_VALIDATE_INT), filter_input(INPUT_GET, 'statusID', FILTER_VALIDATE_INT));         
}  