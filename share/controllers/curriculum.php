<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename curriculum.php
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
global $USER, $TEMPLATE, $PAGE, $INSTITUTION;
$curriculum = new Curriculum();
$TEMPLATE->assign('page_title', 'Lehrpläne verwalten'); 
$TEMPLATE->assign('breadcrumb',  array('Lehrpläne' => 'index.php?action=curriculum'));

if(isset($_GET['reset']) OR (isset($_POST['reset'])) OR (isset($_POST['new_curriculum']))){
    resetPaginator('curriculumP'); 
}

/*******************************************************************************
 * END POST / GET
 */


$p_options = array('delete' => array('onclick'      => "del('curriculum',__id__, $USER->id);", 
                                     'capability'   => checkCapabilities('curriculum:delete', $USER->role_id, false),
                                     'icon'         => 'fa fa-minus'),
                   'add'    => array('href'         => 'index.php?action=view&function=addObjectives&curriculum_id=__id__', 
                                     'capability'   => checkCapabilities('curriculum:addObjectives', $USER->role_id, false),
                                     'icon'         => 'fa fa-plus'),
                   'edit'   => array('onclick'         => "formloader('curriculum','edit',__id__);",
                                     'capability'   => checkCapabilities('curriculum:update', $USER->role_id, false),
                                     'icon'         => 'fa fa-edit'));
$p_config  = array('id'         => 'checkbox',
                   'curriculum'  => 'Lehrplan', 
                   'description' => 'Beschreibung', 
                   'subject'     => 'Fach',
                   'grade'       => 'Klassenstufe',
                   'schooltype'  => 'Schultyp',
                   'state'       => 'Bundesland/Region',
                   /*'de'          => 'Land',*/
                   'p_options'   => $p_options);
setPaginator('curriculumP', $TEMPLATE, $curriculum->getCurricula('user', $USER->id, 'curriculumP'), 'cu_val', 'index.php?action=curriculum', $p_config);