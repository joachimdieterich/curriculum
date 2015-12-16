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
* This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by  
* the Free Software Foundation; either version 3 of the License, or (at your option) any later version.                                   
*                                                                       
* This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of        
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details:                          
*                                                                       
* http://www.gnu.org/copyleft/gpl.html      
*/
global $CFG, $USER, $TEMPLATE, $PAGE;

if (filter_input(INPUT_GET, 'reset', FILTER_UNSAFE_RAW)){ resetPaginator('fileBackupPaginator'); }

$selected_curriculum        = filter_input(INPUT_GET, 'course', FILTER_UNSAFE_RAW);                 // course -->  curriculumid_groupid 
if ($selected_curriculum) {    
    $backup                 = new Backup();
    list ($selected_curriculum, $selected_group) = explode('_', $selected_curriculum);              // $selected_group bisher hier nicht benutzt. evtl. f. Backups der Einreichungen nutzbar.
    $zipfile = $backup->add($selected_curriculum);                                                             // create new backup 
    $TEMPLATE->assign('zipURL', $CFG->web_backup_path . $selected_curriculum.'/' . $zipfile);     //aktuelle zip bereitstellen
    //$TEMPLATE->assign('selected_curriculum', $selected_curriculum);
}
/*********************************************************************************
 * END POST / GET 
 */

$TEMPLATE->assign('page_title', 'Sicherungen erstellen');

$courses            = new Course(); //load Courses
$backups            = new File();
/* load backups and courses */
if (checkCapabilities('backup:getAllBackups', $USER->role_id, false)) {                                 //Administrators
    $backup_list    = $backups->getFiles('context', 8, 'fileBackupPaginator');
    $TEMPLATE->assign('courses', $courses->getCourse('admin', $USER->id));
} else if (checkCapabilities('backup:getMyBackups', $USER->role_id, false)) {                          // Teacher and Tutor
    $backup_list    = $backups->getFiles('backup', $USER->id, 'fileBackupPaginator');
    $TEMPLATE->assign('courses', $courses->getCourse('teacher', $USER->id));     
} 
$TEMPLATE->assign('web_backup_path', $CFG->web_backup_path);  


$p_config =   array('title'       => 'Titel', 
                  'description'   => 'Beschreibung',
                  'creation_time' => 'Datum',
                  'author'        => 'Erstellt durch');
setPaginator('backupP', $TEMPLATE, $backup_list, 'fb_val', 'index.php?action=backup', $p_config);   
                                       