<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename curriculum.php
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
global $USER, $TEMPLATE, $PAGE, $INSTITUTION;
$curriculum = new Curriculum();
$TEMPLATE->assign('page_title', 'Lehrpläne verwalten'); 
$TEMPLATE->assign('breadcrumb',  array('Lehrpläne' => 'index.php?action=curriculum'));

if(isset($_GET['reset']) OR (isset($_POST['reset'])) OR (isset($_POST['new_curriculum']))){
    resetPaginator('curriculumP'); 
}

if ($_POST){
    switch ($_POST) {
        case isset($_POST['import']):   if (isset($_POST['importFileName'])){
                                            $file = $CFG->backup_root.'tmp/'. filter_input(INPUT_POST, 'importFileName', FILTER_UNSAFE_RAW);
                                        }
        case isset($_POST['add']):
        case isset($_POST['update']):    if (isset($_POST['id'])){
                                            $curriculum->id         = filter_input(INPUT_POST, 'id',          FILTER_VALIDATE_INT);
                                        }
                                        $curriculum->curriculum     = filter_input(INPUT_POST, 'curriculum',  FILTER_SANITIZE_STRING);
                                        $curriculum->description    = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);  
                                        $curriculum->subject_id     = filter_input(INPUT_POST, 'subject_id',     FILTER_VALIDATE_INT);
                                        $curriculum->grade_id       = filter_input(INPUT_POST, 'grade_id',       FILTER_VALIDATE_INT);
                                        $curriculum->schooltype_id  = filter_input(INPUT_POST, 'schooltype_id',  FILTER_VALIDATE_INT);
                                        $curriculum->state_id       = filter_input(INPUT_POST, 'state_id',       FILTER_VALIDATE_INT);
                                        $curriculum->country_id     = filter_input(INPUT_POST, 'country_id',     FILTER_VALIDATE_INT);
                                        $curriculum->icon_id        = filter_input(INPUT_POST, 'icon_id',        FILTER_VALIDATE_INT);
                                        $curriculum->creator_id     = $USER->id;  
                                        $gump = new Gump();             /* Validation */
                                        $_POST = $gump->sanitize($_POST);//sanitize $_POST
                                        $gump->validation_rules(array(
                                        'curriculum'     => 'required',
                                        'description'    => 'required',
                                        'subject_id'     => 'required',
                                        'grade_id'       => 'required',
                                        'schooltype_id'  => 'required',
                                        'state_id'       => 'required',
                                        'country_id'     => 'required',
                                        'icon_id'        => 'required'
                                        ));
                                        $validated_data = $gump->run($_POST);
                                        if($validated_data === false) {/* validation failed */
                                            $curriculum->error = $gump->get_readable_errors();
                                            $TEMPLATE->assign('form_data', $curriculum); /* daten für das formular bereitstellen*/
                                            $TEMPLATE->assign('form_function', $_GET['function']);
                                            //$TEMPLATE->assign('v_error', $gump->get_readable_errors());     
                                            //assign_to_template($curriculum, '');    
                                            //$TEMPLATE->assign('showForm', true); 
                                            
                                        } else {/* validation successful */    
                                            if (isset($_POST['add']))   { $curriculum->add(); }
                                            if (isset($_POST['update'])){ $curriculum->update();}            
                                            if (isset($_POST['import'])){ $curriculum->import($file);}            
                                        }  
            break;
        
            break;
        default: break;
    }    
}
/*******************************************************************************
 * END POST / GET
 */


$p_options = array('delete' => array('onclick'      => "del('curriculum',__id__, $USER->id);", 
                                     'capability'   => checkCapabilities('curriculum:delete', $USER->role_id, false),
                                     'icon'         => 'fa fa-minus'),
                   'add'    => array('href'         => 'index.php?action=view&function=addObjectives&curriculum_id=__id__', 
                                     'capability'   => checkCapabilities('curriculum:addObjectives', $USER->role_id, false),
                                     'icon'         => 'fa fa-plus'),
                   'edit'   => array('onclick'         => "loadForm('curriculum','edit',null,__id__);",
                                     'capability'   => checkCapabilities('curriculum:update', $USER->role_id, false),
                                     'icon'         => 'fa fa-edit'));
$p_config  = array('id'         => 'checkbox',
                   'curriculum'  => 'Lehrplan', 
                   'description' => 'Beschreibung', 
                   'subject'     => 'Fach',
                   'grade'       => 'Klassenstufe',
                   'schooltype'  => 'Schultyp',
                   'state'       => 'Bundesland/Region',
                   /*'de'          => 'Land',*/
                   'p_options'   => $p_options);
setPaginator('curriculumP', $TEMPLATE, $curriculum->getCurricula('user', $USER->id, 'curriculumP'), 'cu_val', 'index.php?action=curriculum', $p_config);