<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename teacherUserImport.php
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
global $CFG, $USER, $TEMPLATE, $PAGE, $INSTITUTION;
  
if(isset($_FILES['datei']['name'])){    //Wenn Datei ausgewählt wurde ...                   
    if($_FILES['datei']['size'] <  $INSTITUTION->institution_csv_size) {
        move_uploaded_file($_FILES['datei']['tmp_name'], $CFG->document_root.'assets/tmp/'.$_FILES['datei']['name']); 
        
        $new_userlist = new User();
        $new_userlist->creator_id = $USER->id;
        $result = $new_userlist->import($_POST['institution'],$CFG->document_root.'assets/tmp/'.$_FILES['datei']['name']);
        
        if ($result === true) {
           $PAGE->message[] = 'Benutzerliste erfolgreich importiert';
        } else {
           foreach ($result AS $error) {
            $PAGE->message[] = 'Benutzer: '.$error['username'].' Error: '.array2str($error['error']);
           }
        }         
        unlink($CFG->document_root.'assets/tmp/'.$_FILES['datei']['name']);    //TMP datei Löschen
        
    } else {           
        $PAGE->message[] = "Die Datei darf nicht größer als ".$INSTITUTION->institution_csv_size." MB sein ";
    }
} 
/********************************************************************************
 * END POST / GET  
 */  
$TEMPLATE->assign('teacherUserImport', 'Benutzerkonten importieren');
$TEMPLATE->assign('page_message', $PAGE->message);
$TEMPLATE->assign('filesize', round(convertByteToMb($INSTITUTION->institution_csv_size),2));

$new_users = new User(); 
$newusers = $new_users->newUsers($USER->id);
setPaginator('newUsersPaginator', $TEMPLATE, $newusers, 'results', 'index.php?action=teacherUserImport'); //set Paginator
?>