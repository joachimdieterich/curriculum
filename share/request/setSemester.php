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

$_SESSION['USER']->semester_id    = filter_input(INPUT_GET, 'semester_id', FILTER_VALIDATE_INT);      // Neuer Lernzeitraum übernehmen
/*$TEMPLATE->assign('my_semester_id', $_SESSION['USER']->semester_id); */
$change_semester      = new Semester($_SESSION['USER']->semester_id);
$us = new User();                                                                                     // $USER hier noch nicht verfügbar
$us->id = $_SESSION['USER']->id;
$us->setSemester($_SESSION['USER']->semester_id);
$_SESSION['USER'] = NULL;                                                                             // Beim Wechsel des Lerzeitraumes muss Session neu geladen werden, damit die entsprechende Rolle geladen wird.