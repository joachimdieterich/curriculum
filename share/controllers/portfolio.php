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

$TEMPLATE->assign('page_title', 'Timeline / Portfolio'); 
$TEMPLATE->assign('breadcrumb',  array('Timeline / Portfolio' => 'index.php?action=portfolio'));
$artefacts = new Portfolio();
$TEMPLATE->assign('artefact', $artefacts->getArtefacts()); 
//object_to_array($artefacts->getArtefacts());
$LOG->add($USER->id, 'view', $PAGE->url, 'Portfolio'); 
