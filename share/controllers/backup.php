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
$TEMPLATE->assign('breadcrumb',  array('Sicherungen' => 'index.php?action=backup'));
$TEMPLATE->assign('page_title', 'Sicherungen erstellen');

$backups            = new File();

/* load backups and courses */
if (checkCapabilities('backup:getAllBackups', $USER->role_id, false)) {                          // Administrators
    $backup_list    = $backups->getFiles('context', 8, 'fileBackupPaginator');  
} else if (checkCapabilities('backup:getMyBackups', $USER->role_id, false)) {                    // Teacher and Tutor
    $backup_list    = $backups->getFiles('backup',  $USER->id, 'fileBackupPaginator');
} 

$TEMPLATE->assign('web_backup_path', $CFG->web_backup_path);  

$p_options = array('download'     => array('href' => "../share/accessfile.php?id=__id__",
                                           'icon' => 'fa fa-download'),
                   'xml'          => array('href' => "../share/accessfile.php?id=__id__&type=xml",
                                           'icon' => 'fa fa-file-code-o')); 
$p_config = array('id' => 'checkbox',
                  'title'         => 'Titel', 
                  'description'   => 'Beschreibung',
                  'creation_time' => 'Datum',
                  'author'        => 'Erstellt durch',
                  'p_options'     => $p_options);
setPaginator('fileBackupPaginator', $TEMPLATE, $backup_list, 'fb_val', 'index.php?action=backup', $p_config);      