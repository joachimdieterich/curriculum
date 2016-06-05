<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package core
 * @filename expelFromInstitution.php
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
$base_url           = dirname(__FILE__).'/../';
include($base_url.'setup.php');  //Läd Klassen, DB Zugriff und Funktionen
include(dirname(__FILE__).'/../login-check.php');  //check login status and reset idletimer
global $USER;
$USER               = $_SESSION['USER'];

$current_user       = new User();
$current_user->id   = filter_input(INPUT_GET, 'userID', FILTER_VALIDATE_INT);
if ($current_user->expelFromInstitution(filter_input(INPUT_GET, 'institutionID', FILTER_VALIDATE_INT))){
    Render::popup('Information', '<p>Benutzer wurde erfolgreich ausgeschrieben.</p>'); 
} else { 
    Render::popup('Information', '<p>Datensatz konnte nicht gefunden werden.</p>'); 
}