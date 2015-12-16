<?php
/**
*  This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename semester.php
* @copyright 2013 Joachim Dieterich
* @author Joachim Dieterich
* @date 2013.03.08 13:26
* @license: 
* This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by  
* the Free Software Foundation; either version 3 of the License, or (at your option) any later version.                                   
*                                                                       
* This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of        
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details:                          
*                                                                       
* http://www.gnu.org/copyleft/gpl.html      
*/
global $USER, $TEMPLATE;

if (isset($_GET['function'])) {
     switch ($_GET['function']) {
        case 'new':     checkCapabilities('semester:add',   $USER->role_id);      // USER berechtigt?
                        $TEMPLATE->assign('showForm',       true); 
            break;
        case 'edit':    checkCapabilities('semester:update',$USER->role_id);      // USER berechtigt?
                        $TEMPLATE->assign('showForm',       true);
                        $TEMPLATE->assign('editBtn',        true);

                        $edit_semester      = new Semester();
                        $edit_semester->id  = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
                        $edit_semester->load();
                        $TEMPLATE->assign('institution_id', $edit_semester->institution_id);
                        assign_to_template($edit_semester);               
            break;
        default: break;
    }
}

             
if($_POST ){
    $new_semester                 = new Semester();
    if (isset($_POST['id'])){
        $new_semester->id         = filter_input(INPUT_POST, 'id',          FILTER_VALIDATE_INT);
    }
    $new_semester->semester       = filter_input(INPUT_POST, 'semester',    FILTER_SANITIZE_STRING);
    $new_semester->description    = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING); 
    $new_semester->begin          = filter_input(INPUT_POST, 'begin',       FILTER_UNSAFE_RAW); 
    $new_semester->end            = filter_input(INPUT_POST, 'end',         FILTER_UNSAFE_RAW);
    $new_semester->creator_id     = $USER->id;  
    $new_semester->institution_id = filter_input(INPUT_POST, 'institution_id', FILTER_VALIDATE_INT);

    $gump   = new Gump();                 /* Validation */
    $_POST  = $gump->sanitize($_POST);   //sanitize $_POST
    $gump->validation_rules(array(
    'semester'        => 'required',
    'description'     => 'required',
    'begin'           => 'required',
    'end'             => 'required'
    ));
    $validated_data = $gump->run($_POST);

    if($validated_data === false) {/* validation failed */
        assign_to_template($new_semester);    
        $TEMPLATE->assign('v_error',    $gump->get_readable_errors());     
        $TEMPLATE->assign('showForm',   true); 
    } else {/* validation successful */
        if (isset($_POST['add']))       { $new_semester->add(); }
        if (isset($_POST['update']))    { $new_semester->update(); }       
    }   
}
/*******************************************************************************
 * END POST / GET 
 */ 

$TEMPLATE->assign('page_title', 'Lernzeiträume verwalten');
$semesters                  = new Semester();
$semesters->institution_id  = $USER->institutions; 

$p_options = array('delete' => array('onclick' => "del('subject',__id__, $USER->id);", 
                                     'capability' => checkCapabilities('semester:delete', $USER->role_id, false)),
                    'edit'  => array('href'    => 'index.php?action=semester&function=edit&id=__id__'),
                                     'capability' => checkCapabilities('semester:update', $USER->role_id, false));
$p_config =   array('semester'    => 'Lerzeitraum', 
                  'description'   => 'Beschreibung',
                  'institution'   => 'Institution',
                  'begin'         => 'Lernzeitraum-Beginn',
                  'end'           => 'Lernzeitraum-Ende',
                  'creation_time' => 'Erstellungsdatum',
                  'username'      => 'Erstellt von',
                  'p_options'     => $p_options);
setPaginator('semesterP', $TEMPLATE, $semesters->getSemesters('semesterP'), 'se_val', 'index.php?action=semester', $p_config); 