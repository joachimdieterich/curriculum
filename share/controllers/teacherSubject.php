<?php
/**
*  This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename teacherSubject.php
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

global $USER, $PAGE, $TEMPLATE; 

if (isset($_GET['function']) AND rolepermission(curPageName(), $USER->role_id)) {
     switch ($_GET['function']) {
        case "newSubject": 
                $TEMPLATE->assign('showSubjectForm', true); 
        default: break;
     }
}

if (isset($_GET['edit'])) { // subject editieren 
    $TEMPLATE->assign('showSubjectForm', true);
    $TEMPLATE->assign('showeditSubjectForm', true);
    
    $edit_subject = new Subject();
    $edit_subject->id = $_GET['id'];
    $edit_subject->load();
     
    $TEMPLATE->assign('id',             $edit_subject->id);
    $TEMPLATE->assign('subject',        $edit_subject->subject);                
    $TEMPLATE->assign('subject_short',  $edit_subject->subject_short); 
    $TEMPLATE->assign('description',    $edit_subject->description);
}

// Formular für neuen Rollentyp anzeigen
if($_POST){
    switch ($_POST) {
        case isset($_POST['newSubject']):
                    $TEMPLATE->assign('showSubjectForm', true); 
            break;
        
        case isset($_POST['addSubject']):
        case isset($_POST['updateSubject']):
                    $new_subject = new Subject();
                    if (isset($_POST['id'])){
                    $new_subject->id             = $_POST['id'];    
                    }
                    $new_subject->subject        = $_POST['subject'];
                    $new_subject->subject_short  = $_POST['subject_short'];     
                    $new_subject->description    = $_POST['description']; 
                    $new_subject->creator_id     = $USER->id;
                    $new_subject->institution_id = $_POST['institution']; //um $USER->institutions muss einauswahlfeld geben, wenn man in mehr als einer institution eingeschrieben ist, damit fach eindeutig zugeordnet wird
                    
                    $gump = new Gump(); /* Validation */
                    $gump->validation_rules(array(
                    'subject'         => 'required',
                    'subject_short'   => 'required|max_len,5',
                    'description'     => 'required'
                    ));
                    $validated_data = $gump->run($_POST);
                    
                    if($validated_data === false) {/* validation failed */
                            foreach($new_subject as $key => $value){
                            $TEMPLATE->assign($key, $value);
                            } 
                            $TEMPLATE->assign('v_error', $gump->get_readable_errors());     
                            $TEMPLATE->assign('showSubjectForm', true); 
                        } else {/* validation successful */
                            if (isset($_POST['addSubject'])){
                                $new_subject->add();
                            }
                            if (isset($_POST['updateSubject'])){
                                $new_subject->update($USER->id);
                            }       
                    }       
            break;
        
        default:
            break;
    }
}

/*******************************************************************************
 * END POST / GET 
 */
$subject = new Subject();
$subject->institution_id = $USER->institutions["id"];
setPaginator('subjectsPaginator', $TEMPLATE, $subject->getSubjects(), 'subject_list', 'index.php?action=teacherSubject'); //set Paginator   
    
$TEMPLATE->assign('teacherSubject', 'Fächer verwalten');
$TEMPLATE->assign('page_message', $PAGE->message);
         