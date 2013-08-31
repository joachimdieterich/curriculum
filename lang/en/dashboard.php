<?php
/**
* This file is part of curriculum - http://www.joachimdieterich.de
* Strings for component 'dashboard', language 'en', branch 'CURRICULUM_1_BEAT'
*
* @package   language
* @copyright 2012 onwards Joachim Dieterich  {@link http://www.joachimdieterich.de}
* @license   
* This program is free software; you can redistribute it and/or modify 
* it under the terms of the GNU General Public License as published by  
* the Free Software Foundation; either version 3 of the License, or     
* (at your option) any later version.                                   
*                                                                       
* This program is distributed in the hope that it will be useful,       
* but WITHOUT ANY WARRANTY; without even the implied warranty of        
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the         
* GNU General Public License for more details:                          
*                                                                       
* http://www.gnu.org/copyleft/gpl.html      
*/ 

global $TEMPLATE, $USER;

$TEMPLATE->assign('str_dashboard',                      'Dashboard'); 
$TEMPLATE->assign('str_achievments_headline',           'Achievments');  
$TEMPLATE->assign('str_achievments_txt1',               'You have achieved the following goals during the last <strong>'.$USER->acc_days.'</strong> days.'); 
$TEMPLATE->assign('str_achievments_txt2',               'You have not completed any goals during the last <strong>'.$USER->acc_days.'</strong> days.'); 
$TEMPLATE->assign('str_institution_headline',           'My Institutions / Schools'); 
$TEMPLATE->assign('str_institution_notassigned',        'You are not enrolled in an institution / a school.');

//Table Headings
$TEMPLATE->assign('str_institution',                    'Institution'); 
$TEMPLATE->assign('str_description',                    'Description');
$TEMPLATE->assign('str_institution_schooltype',         'Type of school');
$TEMPLATE->assign('str_institution_state',              'State / Region');
$TEMPLATE->assign('str_institution_countries',          'Country');
$TEMPLATE->assign('str_creationtime',                   'Creation date');
$TEMPLATE->assign('str_creator',                        'Admin');

$TEMPLATE->assign('str_classes_headline',               'My learning groups / Classes');
$TEMPLATE->assign('str_classes_notassigned',            'You are not enrolled in a learning group / a class.');

//Table Headings
$TEMPLATE->assign('str_classes_classes',                'Learning groups / Classes');
$TEMPLATE->assign('str_classes_class',                  'Class Level');
$TEMPLATE->assign('str_classes_semester',               'School semester');

$TEMPLATE->assign('str_curriculum_headline',            'My current curricula');
$TEMPLATE->assign('str_curriculum_notassigned',         'You are not enrolled in a curriculum.'); 

$TEMPLATE->assign('str_oldcurriculum_headline',         'My current curricula');
$TEMPLATE->assign('str_oldcurriculum_notavailable',     'This feature is not available in this version.');

$TEMPLATE->assign('str_manuals',                       'Manuals');
$TEMPLATE->assign('str_manuals_institution',           'Manual - Admin (Institution)');
$TEMPLATE->assign('str_manuals_teacher',               'Manual - Teacher');
$TEMPLATE->assign('str_manuals_student',               'Manual - Student');
?>