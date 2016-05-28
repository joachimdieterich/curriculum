<?php 
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename calendar.php
* @copyright 2016 Joachim Dieterich
* @author Joachim Dieterich
* @date 2016.05.11 21:06
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
global $USER, $TEMPLATE, $PAGE, $LOG;

$TEMPLATE->assign('page_title', 'Kalender'); 
$TEMPLATE->assign('breadcrumb',  array('Kalender' => 'index.php?action=calendar'));
$LOG->add($USER->id, 'view', $PAGE->url, 'calendar'); 

$e = new Event();

$events = array();
$ev     = new stdClass();
foreach ($e->get('user', $USER->id) as $value) {
    $ev->id               = $value->id;
    $ev->title            = $value->event;
    $ev->start            = $value->timestart;
    $ev->end              = $value->timeend;
    $ev->backgroundColor  = "#00c0ef";
    $ev->borderColor      = "#00c0ef";
    $events[]             = clone $ev;
}
$TEMPLATE->assign('events', json_encode($events));       