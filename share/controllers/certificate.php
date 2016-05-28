<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package core
 * @filename certificate.php
 * @copyright 2013 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2014.07.30 22:43
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
global $PAGE, $USER, $TEMPLATE;
$TEMPLATE->assign('breadcrumb',  array('Zertifikate' => 'index.php?action=certificate'));
$TEMPLATE->assign('page_title', 'Zertifikat einrichten');   

$certificates = new Certificate();
$certificates->institution_id = $USER->institutions;

$p_options = array('delete' => array('onclick'    => "del('certificate',__id__, $USER->id);", 
                                     'capability' => checkCapabilities('certificate:delete', $USER->role_id, false),
                                     'icon'       => 'fa fa-minus'),
                    'edit'  => array('onclick'    => "formloader('certificate','edit',__id__);",
                                     'capability' => checkCapabilities('certificate:update', $USER->role_id, false),
                                     'icon'       => 'fa fa-edit'));
$p_config =  array('id'           => 'checkbox',
                    'certificate' => 'Titel des Zertifikat-Vorlage', 
                  'description'   => 'Beschreibung', 
                  'institution'   => 'Institution', 
                  'creation_time' => 'Erstellungs-Datum',
                  'username'      => 'Erstellt von',
                  'p_options'     => $p_options);
setPaginator('certificateP', $TEMPLATE, $certificates->getCertificates('certificateP'), 'ct_val', 'index.php?action=certificate', $p_config); //set Paginator