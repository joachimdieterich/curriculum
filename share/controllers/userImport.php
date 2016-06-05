<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename userImport.php
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
global $CFG, $USER, $TEMPLATE, $PAGE, $INSTITUTION;
 
if(isset($_FILES['datei']['name'])){                                                                                    //Wenn Datei ausgewählt wurde ...                   
    if($_FILES['datei']['size'] <  $INSTITUTION->csv_size) {                                                            //Dateigröße prüfen
        move_uploaded_file($_FILES['datei']['tmp_name'], $CFG->curriculumdata_root.'temp/'.$_FILES['datei']['name']);   //Datei auf Server kopieren
        $new_userlist               = new User();
        $new_userlist->creator_id   = $USER->id;                                                                         
        $new_userlist->import(filter_input(INPUT_POST, 'institution_id', FILTER_VALIDATE_INT),$CFG->curriculumdata_root.'temp/'.$_FILES['datei']['name']); //Importieren
        unlink($CFG->curriculumdata_root.'temp/'.$_FILES['datei']['name']);                                             //TEMP-Datei löschen  
    } else {   
        $PAGE->message[] = array('message' => 'Die Datei darf nicht größer als '.$INSTITUTION->csv_size.' MB sein', 'icon' => 'fa-user text-success');//Datei zu groß
    }
} 
/********************************************************************************
 * END POST / GET  
 */  
$TEMPLATE->assign('page_title', 'Benutzerkonten importieren');
$TEMPLATE->assign('breadcrumb',  array('Benutzerverwaltung' => 'index.php?action=user', 
                                       'Benutzerkonten importieren' => 'index.php?action=userImport'));
$TEMPLATE->assign('filesize',   round(convertByteToMb($INSTITUTION->csv_size),2));

$new_users = new User();
$p_options =    $p_config =   array('id'         => 'checkbox',
                                    'username'   => 'Benutzername', 
                                    'firstname'  => 'Vorname', 
                                    'lastname'   => 'Nachname', 
                                    'email'      => 'Email', 
                                    'postalcode' => 'PLZ', 
                                    'city'       => 'Ort', 
                                    'state_id'   => 'Bundesland', 
                                    'country_id' => 'Land', 
                                    'role_name'  => 'Rolle', 
                                    'p_options'  => array());
setPaginator('newUsersPaginator', $TEMPLATE, $new_users->newUsers($USER->id, 'newUsersPaginator'), 'nusr_val', 'index.php?action=userImport', $p_config);