<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename role.php
* @copyright 2013 Joachim Dieterich
* @author Joachim Dieterich
* @date 2013.11.17 17:18
* @license: 
*
* This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by  
* the Free Software Foundation; either version 3 of the License, or (at your option) any later version.                                   
*                                                                       
* This program is distributed in the hope that it will be useful,  but WITHOUT ANY WARRANTY; without even the implied warranty of        
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details:                          
*                                                                       
* http://www.gnu.org/copyleft/gpl.html      
*/
global $USER, $PAGE, $TEMPLATE;

$TEMPLATE->assign('page_title', 'Benutzerrollen');
$TEMPLATE->assign('breadcrumb',  array('Benutzerrollen' => 'index.php?action=role'));
$role = new Roles();

$p_options = array('delete' => array('onclick' => "del('role',__id__, $USER->id);", 
                                     'capability' => checkCapabilities('role:delete', $USER->role_id, false),
                                     'icon'         => 'fa fa-minus'),
                    'edit'  => array('onclick' => "formloader('role','edit',__id__);",
                                     'capability' => checkCapabilities('role:update', $USER->role_id, false),
                                     'icon'         => 'fa fa-edit'));
$p_config =   array('id'          => 'checkbox',
                    'role'        => 'Rolle', 
                  'description'   => 'Beschreibung', 
                  'p_options'     => $p_options);
setPaginator('roleP', $TEMPLATE, $role->get('roleP'), 'ro_val', 'index.php?action=role', $p_config); //set Paginator