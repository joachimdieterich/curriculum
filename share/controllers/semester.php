<?php
/**
*  This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename semester.php
* @copyright 2013 Joachim Dieterich
* @author Joachim Dieterich
* @date 2013.03.08 13:26
* @license: 
* This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by  
* the Free Software Foundation; either version 3 of the License, or (at your option) any later version.                                   
*                                                                       
* This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of        
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details:                          
*                                                                       
* http://www.gnu.org/copyleft/gpl.html      
*/
global $USER, $TEMPLATE;

$TEMPLATE->assign('page_title', 'Lernzeiträume');
$TEMPLATE->assign('breadcrumb',  array('Lernzeiträume' => 'index.php?action=semester'));
$semesters                  = new Semester();
$semesters->institution_id  = $USER->institutions; 

$p_options = array('delete' => array('onclick'    => "del('semester',__id__, $USER->id);", 
                                     'capability' => checkCapabilities('semester:delete', $USER->role_id, false),
                                     'icon'       => 'fa fa-minus'),
                    'edit'  => array('onclick'    => "formloader('semester','edit',__id__);", 
                                     'capability' => checkCapabilities('semester:update', $USER->role_id, false),
                                     'icon'       => 'fa fa-edit'));
$p_config = array('id'            => 'checkbox',
                  'semester'      => 'Lerzeitraum', 
                  'description'   => 'Beschreibung',
                  'institution'   => 'Institution',
                  'begin'         => 'Lernzeitraum-Beginn',
                  'end'           => 'Lernzeitraum-Ende',
                  'creation_time' => 'Erstellungsdatum',
                  'username'      => 'Erstellt von',
                  'p_options'     => $p_options);
setPaginator('semesterP', $TEMPLATE, $semesters->getSemesters('semesterP'), 'se_val', 'index.php?action=semester', $p_config); 