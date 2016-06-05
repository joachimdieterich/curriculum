<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package core
 * @filename grade.php
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
global $USER, $TEMPLATE;
$TEMPLATE->assign('breadcrumb',  array('Klassenstufen' => 'index.php?action=grade'));
$TEMPLATE->assign('page_title', 'Klassenstufen');  
                                       
/****** END POST / GET ******/
$grade                  = new Grade();
$grade->institution_id  = $USER->institutions;

$p_options = array('delete' => array('onclick'    => "del('grade',__id__, $USER->id);", 
                                     'capability' => checkCapabilities('grade:delete', $USER->role_id, false),
                                     'icon'       => 'fa fa-minus'),
                    'edit'  => array('onclick'    => "formloader('grade','edit',__id__);",
                                     'capability' => checkCapabilities('grade:update', $USER->role_id, false),
                                     'icon'       => 'fa fa-edit'));
$p_config =   array('id'         => 'checkbox',
                    'grade'       => 'Klassenstufe', 
                    'description' => 'Beschreibung', 
                    'institution' => 'Institution', 
                    'p_options'   => $p_options);
setPaginator('gradeP', $TEMPLATE, $grade->getGrades('gradeP'), 'gr_val', 'index.php?action=grade', $p_config); 