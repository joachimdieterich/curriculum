<?php
/**
*  This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename subject.php
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

global $USER, $PAGE, $TEMPLATE; 
/*if (isset($_GET['function'])) {
     switch ($_GET['function']) {
        case 'new':   checkCapabilities('subject:add',    $USER->role_id);      // USER berechtigt?
                                    $TEMPLATE->assign('showForm',       true); 
             break;
        case 'edit':  checkCapabilities('subject:update', $USER->role_id);      // USER berechtigt?
                                    $TEMPLATE->assign('showForm',       true);
                                    $TEMPLATE->assign('editBtn',        true);

                                    $edit_subject       = new Subject();
                                    $edit_subject->id   = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
                                    $edit_subject->load();
                                    $TEMPLATE->assign('institution_id', $edit_subject->institution_id);
                                    assign_to_template($edit_subject);
        default: break;
    }
}

// Formular für neuen Rollentyp anzeigen
if($_POST){
    switch ($_POST) {
        case isset($_POST['add']):      
        case isset($_POST['update']):   
                                        $new_subject                 = new Subject();
                                        if (isset($_POST['id'])){
                                            $new_subject->id         = filter_input(INPUT_POST, 'id',           FILTER_VALIDATE_INT);    
                                        }
                                        $new_subject->subject        = filter_input(INPUT_POST, 'subject',      FILTER_UNSAFE_RAW);
                                        $new_subject->subject_short  = filter_input(INPUT_POST, 'subject_short',FILTER_UNSAFE_RAW);
                                        $new_subject->description    = filter_input(INPUT_POST, 'description',  FILTER_UNSAFE_RAW);
                                        $new_subject->creator_id     = $USER->id;
                                        $new_subject->institution_id = filter_input(INPUT_POST, 'institution_id',  FILTER_VALIDATE_INT); //um $USER->institutions muss einauswahlfeld geben, wenn man in mehr als einer institution eingeschrieben ist, damit fach eindeutig zugeordnet wird

                                        $gump = new Gump();                        
                                        $_POST = $gump->sanitize($_POST);           //sanitize $_POST
                                        $gump->validation_rules(array(
                                        'subject'         => 'required',
                                        'subject_short'   => 'required|max_len,5',
                                        'description'     => 'required'
                                        ));
                                        $validated_data = $gump->run($_POST);

                                        if($validated_data === false) {            
                                            assign_to_template($new_subject);     
                                            $TEMPLATE->assign('v_error', $gump->get_readable_errors());     
                                            $TEMPLATE->assign('showForm', true); 
                                        } else {
                                            if (isset($_POST['add']))   { $new_subject->add(); }
                                            if (isset($_POST['update'])){ $new_subject->update($USER->id); }       
                                        }       
            break;
        default: break;
    }
}*/
/*******************************************************************************
 * END POST / GET 
 */
$TEMPLATE->assign('page_title', 'Fächer');
$TEMPLATE->assign('breadcrumb',  array('Fächer' => 'index.php?action=subject'));
$subject                    = new Subject();
$subject->institution_id    = $USER->institutions;
$p_options = array('delete' => array('onclick'    => "del('subject',__id__, $USER->id);", 
                                     'capability' => checkCapabilities('subject:delete', $USER->role_id, false),
                                     'icon'       => 'fa fa-minus'),
                    'edit'  => array('onclick'    => "formloader('subject','edit',__id__);",
                                     'capability' => checkCapabilities('subject:update', $USER->role_id, false),
                                     'icon'       => 'fa fa-edit'));
$p_config =   array('id'         => 'checkbox',
                    'subject'       => 'Fach', 
                  'subject_short' => 'Kürzel',
                  'description'   => 'Beschreibung', 
                  'institution'   => 'Institution', 
                  'p_options'     => $p_options);
setPaginator('subjectP', $TEMPLATE, $subject->getSubjects('subjectP'), 'su_val', 'index.php?action=subject', $p_config); 