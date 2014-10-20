<?php
/**
* This file is part of curriculum - http://www.joachimdieterich.de
* Strings for component 'dashboard', language 'de', branch 'CURRICULUM_1_BEAT'
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

 $TEMPLATE->assign('str_dashboard', 'Startseite'); 
 $TEMPLATE->assign('str_achievments_headline', 'Erfolge');  
 $TEMPLATE->assign('str_achievments_txt1', 'Hier siehst du, welche Ziele du in den letzten <strong>'.$USER->acc_days.'</strong> Tagen erreicht hast.'); 
 $TEMPLATE->assign('str_achievments_txt2', 'In den letzten <strong>'.$USER->acc_days.'</strong> Tagen hast du keine Ziele abgeschlossen.'); 
 $TEMPLATE->assign('str_institution_headline', 'Meine Institutionen / Schulen');  
 $TEMPLATE->assign('str_institution_notassigned', 'Sie sind in keiner Institution / Schule eingeschrieben.');  
 
 //Table Headings
 $TEMPLATE->assign('str_institution', 'Institution'); 
 $TEMPLATE->assign('str_description', 'Beschreibung');
 $TEMPLATE->assign('str_institution_schooltype', 'Schultyp');
 $TEMPLATE->assign('str_institution_state', 'Bundesland / Region');
 $TEMPLATE->assign('str_institution_countries', 'Land');
 $TEMPLATE->assign('str_creationtime', 'Erstellungsdatum');
 $TEMPLATE->assign('str_creator', 'Administrator');

 $TEMPLATE->assign('str_classes_headline', 'Meine Lerngruppen / Klassen');
 $TEMPLATE->assign('str_classes_notassigned', 'Sie sind in keiner Lerngruppe / Klasse eingeschrieben.'); 
 
  //Table Headings
 $TEMPLATE->assign('str_classes_classes', 'Lerngruppe');
 $TEMPLATE->assign('str_classes_class', 'Klassenstufe');
 $TEMPLATE->assign('str_classes_semester', 'Schuljahr');
         
 $TEMPLATE->assign('str_curriculum_headline', 'Meine aktuellen Lehrpläne');
 $TEMPLATE->assign('str_curriculum_notassigned', 'Sie sind in keinem Curriculum eingeschrieben.'); 
 
 $TEMPLATE->assign('str_oldcurriculum_headline', 'Meine alten Lehrpläne');
 $TEMPLATE->assign('str_oldcurriculum_notavailable', 'Diese Funktion ist in dieser Version nicht verfügbar.');

 $TEMPLATE->assign('str_manuals',                       'Anleitungen');
 $TEMPLATE->assign('str_manuals_institution',           'Dokumentation - Institutions-Administrator');
 $TEMPLATE->assign('str_manuals_teacher',               'Dokumentation - Lehrer');
 $TEMPLATE->assign('str_manuals_student',               'Dokumentation - Student');
?>