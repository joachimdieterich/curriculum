<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package core
 * @filename delete.php
 * @copyright 2015 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2015.06.03 10:28
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
include(dirname(__FILE__).'/../setup.php');  // Klassen, DB Zugriff und Funktionen

global $USER;
$USER   = $_SESSION['USER'];
$db     = filter_input(INPUT_GET, 'db',           FILTER_SANITIZE_STRING);
$id     = filter_input(INPUT_GET, 'id',           FILTER_SANITIZE_STRING); // kein INT --> System ID -1
switch ($db) {
    case "certificate":         $t = new Certificate();         break;
    case "curriculum":          $t = new Curriculum();          break;
    case "grade":               $t = new Grade();               break;
    case "group":               $t = new Group();               break;
    case "role":                $t = new Roles();               break;      
    case "semester":            $t = new Semester();            break;
    case "subject":             $t = new Subject();             break;
    case "user":                $t = new User();                break;
    case "institution":         $t = new Institution();         break;
    case "message":             $t = new Mail();                break;
    
    case "enablingObjectives":  $t = new EnablingObjective();   break;
    case "terminalObjectives":  $t = new TerminalObjective();   break;
    case "task":                $t = new Task();                break;
    default: break;
}

$t->id      = $id;

if ($t->delete()){
    $html = Render::popup('Information', 'Datensatz wurde erfolgreich gelöscht.');
    echo json_encode(array('html'=>$html, 'class'=>'modal-info'));
    
        
} else { 
    $html = Render::popup('Information', 'Datensatz konnte nicht gelöscht werden.');
    echo json_encode(array('html'=>$html, 'class'=>'modal-danger'));   
}