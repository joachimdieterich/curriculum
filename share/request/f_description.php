<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package core
 * @filename f_description.php
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
include(dirname(__FILE__).'/../login-check.php');  //check login status and reset idletimer
global $USER;
$USER       = $_SESSION['USER'];
$func       = $_GET['func'];

$ter        = new TerminalObjective();
switch ($func) {
    case 'terminal_objective':  $ter->id    = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        break;
    case 'enabling_objective':  $ena        = new EnablingObjective();
                                $ena->id    = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
                                $ena->load();  
                                $ter->id    = $ena->terminal_objective_id;                       
        break;

    default:
        break;
}

$ter->load();
$content    = '';
if ($ter->description){         $content .= $ter->description; } 
if (isset($ena->description)){  $content .= '<br>'.$ena->description; } 
if (!isset($ena->description) && !isset($ter->description)){    
  $content .= 'Keine Beschreibung vorhanden';
}

$html = Form::modal(array('title'   => 'Beschreibung',
                          'content' => $content));

echo json_encode(array('html'=>$html));