<?php 
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename courseBook.php
* @copyright 2016 Joachim Dieterich
* @author Joachim Dieterich
* @date 2016.05.07 09:52
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

$TEMPLATE->assign('page_title', 'Kursbuch'); 
$TEMPLATE->assign('breadcrumb',  array('Kursbuch' => 'index.php?action=courseBook'));
$LOG->add($USER->id, 'view', $PAGE->url, 'courseBook'); 
    
if (checkCapabilities('menu:readCourseBook', $USER->role_id)){
    $coursebook = new CourseBook();
    
    
    $p_config =   array('id'        => 'checkbox', 
                  'creation_time'   => 'Datum/Zeit', 
                  'topic'           => 'Thema',
                  'description'     => 'Erläuterungen',
                  'date'            => 'Datum',
                  'duration'        => 'Dauer',
                  'timeunit_id'     => 'Zeiteinheit',
                  'creator_id'      => 'Eingetragen von');
    setPaginator('cbP', $TEMPLATE, $coursebook->get(), 'cb_val', 'index.php?action=courseBook', $p_config);
    $TEMPLATE->assign('coursebook', $coursebook->get()); 
} 