<?php
/**
* This file is part of curriculum - http://www.joachimdieterich.de
* Strings for component 'adminGrade', language 'en', branch 'CURRICULUM_1_BEAT'
*
* @package   adminGrade
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
global $TEMPLATE;

$TEMPLATE->assign('str_adminGrade',                 'Manage grades'); 
$TEMPLATE->assign('str_adminGrade_headline',        'Here you can create new grades and delete existing grades.'); 
$TEMPLATE->assign('str_adminGrade_addGradeName',    'Grades name:');  
$TEMPLATE->assign('str_description',                'Description');
$TEMPLATE->assign('str_adminGrade_addbtn',          'Add new grade');

//GradePaginator
$TEMPLATE->assign('str_adminGrade_pagItem',         'Records (grades)');
$TEMPLATE->assign('str_adminGrade_pagTo',           'to');

//Table Headings
$TEMPLATE->assign('str_adminGrade_Grade',           'Grades name'); 
$TEMPLATE->assign('str_adminGrade_delbtn',          'Delete marked grade(s)');
?>