<?php 
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename Log.php
* @copyright 2013 Joachim Dieterich
* @author Joachim Dieterich
* @date 2013.03.08 13:26
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

$TEMPLATE->assign('page_title', 'Berichte'); 
$TEMPLATE->assign('breadcrumb',  array('Berichte' => 'index.php?action=log'));
$LOG->add($USER->id, 'view', $PAGE->url, 'Log'); 
    
if (checkCapabilities('menu:readLog', $USER->role_id)){
    $p_config =   array('id'        => 'checkbox', 
                  'creation_time'   => 'Datum/Zeit', 
                  'ip'              => 'IP',
                  'user_id'         => 'Username',
                  'action'          => 'Aktion',
                  'url'             => 'URL',
                  'info'            => 'Info');
    setPaginator('logP', $TEMPLATE, $LOG->getLogs('logP'), 'lo_val', 'index.php?action=log', $p_config);
    $TEMPLATE->assign('ccs_page_log', true); 
} 