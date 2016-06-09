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
* The MIT License (MIT)
* Copyright (c) 2012 Joachim Dieterich http://www.curriculumonline.de
* 
* Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), 
* to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, 
* and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
* 
* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
* 
* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF 
* MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, 
* DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR 
* THE USE OR OTHER DEALINGS IN THE SOFTWARE.
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