<?php
/**
* This file is part of curriculum - http://www.joachimdieterich.de
* Strings for component 'adminGrade', language 'de', branch 'CURRICULUM_1_BEAT'
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

 $TEMPLATE->assign('str_adminGrade', 'Klassenstufen verwalten'); 
 $TEMPLATE->assign('str_adminGrade_headline', 'Hier können neue Klassenstufen angelegt sowie bestehende Klassenstufen gelöscht werden'); 
 $TEMPLATE->assign('str_adminGrade_addGradeName', 'Klassenstufen-Name:');  
 $TEMPLATE->assign('str_description', 'Beschreibung');
 $TEMPLATE->assign('str_adminGrade_addbtn', 'Klassenstufe hinzufügen');
 
 //GradePaginator
 $TEMPLATE->assign('str_adminGrade_pagItem', 'Datensätze (Klassenstufen)');
 $TEMPLATE->assign('str_adminGrade_pagTo', 'bis');
 
//Table Headings
 $TEMPLATE->assign('str_adminGrade_Grade', 'Klassenstufen-Name'); 
 $TEMPLATE->assign('str_adminGrade_delbtn', 'Markierte Klassenstufe(n) löschen');
?>