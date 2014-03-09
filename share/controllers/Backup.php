<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package core
 * @filename Backup.php
 * @copyright 2013 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2013.03.08 13:26
 * @license: 
 *
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
global $CFG, $USER, $TEMPLATE, $PAGE;

if(isset($_GET['reset']) OR (isset($_POST['reset']))){
    resetPaginator('fileBackupPaginator');            
}
$backup = new Backup();
$selected_curriculum = (isset($_GET['course']) && trim($_GET['course'] != '') ? $_GET['course'] : '_'); //'_' ist das Trennungszeichen 
$TEMPLATE->assign('selected_curriculum', $selected_curriculum);

if (isset($_GET['course'])) {// create new backup
    list ($selected_curriculum, $selected_group) = explode('_', $selected_curriculum); //$selected_curriculum enthält curriculumid_groupid (zb. 32_24) wenn nur '_' gesetzt ist werden beide variabeln ''
    $backup_url = $CFG->backup_url;                             //URL in der das imscc File erscheinen soll
    $zipfile = newBackup($backup_url, $selected_curriculum, $USER->id);          //Erstellt ein neues Backup
    $zipURL = $CFG->web_backup_url . '' . $zipfile;
    $PAGE->message[] = 'Backup <strong>"' . $zipfile . '"</strong> wurde erfolgreich erstellt.';
    $TEMPLATE->assign('zipURL', $zipURL);                            //ZipURL bereitstellen
}
/*********************************************************************************
 * END POST / GET 
 */

$TEMPLATE->assign('page_title', 'Sicherungen erstellen');

$courses = new Course(); //Load Courses
if ($USER->role_id == 3 OR $USER->role_id == 2) { // 3 = Rolle Lehrer, 2 = Tutor //Bedingung Lehrer müssen in die Klasse eingeschrieben sein, oder sie erstellt haben
    $TEMPLATE->assign('courses', $courses->getCourse('teacher', $USER->id));     
} else if ($USER->role_id == 4 OR $USER->role_id == 1) {
    $TEMPLATE->assign('courses', $courses->getCourse('admin', $USER->id));    
}

// Backups laden
if ($USER->role_id == 4 OR $USER->role_id == 3 OR $USER->role_id == 2) { // 3 = Rolle Lehrer, 2 = Tutor //Bedingung Lehrer müssen in die Klasse eingeschrieben sein, oder sie erstellt haben
    $backup_list = $backup->load('teacher');
} else
if ($USER->role_id == 1) {
    $backup_list = $backup->load('admin');
}

setPaginator('fileBackupPaginator', $TEMPLATE, $backup_list, 'results', 'index.php?action=backup'); //set Paginator    
$zipURL = $CFG->web_backup_url;
$TEMPLATE->assign('web_backup_url', $zipURL); //keine Datensätze vorhanden

$TEMPLATE->assign('page_message', $PAGE->message);
?>