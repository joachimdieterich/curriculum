<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package core
 * @filename institution.php
 * @copyright 2014 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2014.10.13 08:26
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
global $CFG, $USER, $TEMPLATE, $INSTITUTION;

$TEMPLATE->assign('page_title',  'Institutionen');
$TEMPLATE->assign('breadcrumb',  array('Institutionen' => 'index.php?action=institution'));
$state                         = new State;  

$p_options = array('delete' => array('onclick'    => "del('institution',__id__, $USER->id);", 
                                     'capability' => checkCapabilities('institution:delete', $USER->role_id, false),
                                     'icon'       => 'fa fa-minus'),
                    'edit'  => array('onclick'    => "formloader('institution', 'edit',__id__);",
                                     'capability' => checkCapabilities('institution:update', $USER->role_id, false),
                                     'icon'       => 'fa fa-edit'));
$p_view =   array('id'            => 'checkbox', 
                  'institution'   => 'Institution', 
                  'description'   => 'Beschreibung', 
                  'schooltype_id' => 'Schultyp',
                  'state_id'      => 'Bundesland/Region',
                  'de'            => 'Land',
                  'creation_time' => 'Erstellungsdatum',
                  'username'      => 'Administrator',
                  'p_options'     => $p_options);
$institution = new Institution();
setPaginator('institutionP', $TEMPLATE, $institution->getInstitutions('all', 'institutionP'), 'in_val', 'index.php?action=institution', $p_view); //set Paginator   