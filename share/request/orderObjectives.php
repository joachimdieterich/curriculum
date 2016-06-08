<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package core
 * @filename orderObjectives.php
 * @copyright 2015 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2015.06.01 17:54
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
include(dirname(__FILE__).'/../login-check.php');  //check login status and reset idletimer
global $USER;

$USER       = $_SESSION['USER'];
$func       = $_GET['func'];
switch ($func) {
    case 'enabling_objective':  $enabling_objective         = new EnablingObjective();
                                $enabling_objective->id     = filter_input(INPUT_GET, 'id',  FILTER_VALIDATE_INT);
                                $enabling_objective->load();
                                $enabling_objective->order(filter_input(INPUT_GET, 'order', FILTER_SANITIZE_STRING));
        break;
    case 'terminal_objective':  $terminal_objective         = new TerminalObjective();
                                $terminal_objective->id     = filter_input(INPUT_GET, 'id',  FILTER_VALIDATE_INT);
                                $terminal_objective->load();
                                $terminal_objective->order(filter_input(INPUT_GET, 'order', FILTER_SANITIZE_STRING));                   
        break;

    default:
        break;
}