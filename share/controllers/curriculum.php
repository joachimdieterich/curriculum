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

if(isset($_GET['reset']) OR (isset($_POST['reset'])) OR (isset($_POST['new_curriculum']))){
    resetPaginator('curriculumPaginator'); 
}

if (isset($_GET['function'])){
    switch ($_GET['function']) {
        case "new":     checkCapabilities('curriculum:add',     $USER->role_id);     //USER berechtigt?
                        $TEMPLATE->assign('showForm',           true);
                        $TEMPLATE->assign('c_subject_id',       null);         // müssen gesetzt sein um unnötige if-Bedingungen zu vermeiden
                        $TEMPLATE->assign('c_icon_id',          null);         // müssen gesetzt sein um unnötige if-Bedingungen zu vermeiden
                        $TEMPLATE->assign('c_semester_id',      null);         // müssen gesetzt sein um unnötige if-Bedingungen zu vermeiden
                        $TEMPLATE->assign('c_institution_id',   null);         // müssen gesetzt sein um unnötige if-Bedingungen zu vermeiden
                        $TEMPLATE->assign('c_grade_id',   null);         // müssen gesetzt sein um unnötige if-Bedingungen zu vermeiden
                        $TEMPLATE->assign('c_schooltype_id',   null);         // müssen gesetzt sein um unnötige if-Bedingungen zu vermeiden
                        $TEMPLATE->assign('c_state_id',   null);         // müssen gesetzt sein um unnötige if-Bedingungen zu vermeiden
            break;
        case "edit":    $TEMPLATE->assign('showForm', true);

                        $new_curriculum          = new Curriculum(); 
                        $new_curriculum->id      = filter_input(INPUT_GET, 'c_id', FILTER_VALIDATE_INT);
                        if ($new_curriculum->id != null) {
                            $new_curriculum->load();
                        }
                        assign_to_template($new_curriculum, 'c_');
                        $TEMPLATE->assign('editBtn', true);
            break;
        default: break;
    }
}

if ($_POST){
    $new_curriculum = new Curriculum();
    switch ($_POST) {
        case isset($_POST['add']):
        case isset($_POST['update']):   if (isset($_POST['c_id'])){
                                            $new_curriculum->id         = filter_input(INPUT_POST, 'c_id',          FILTER_VALIDATE_INT);
                                        }
                                        $new_curriculum->curriculum     = filter_input(INPUT_POST, 'c_curriculum',  FILTER_SANITIZE_STRING);
                                        $new_curriculum->description    = filter_input(INPUT_POST, 'c_description', FILTER_SANITIZE_STRING);  
                                        $new_curriculum->subject_id     = filter_input(INPUT_POST, 'c_subject',     FILTER_VALIDATE_INT);
                                        $new_curriculum->grade_id       = filter_input(INPUT_POST, 'c_grade',       FILTER_VALIDATE_INT);
                                        $new_curriculum->schooltype_id  = filter_input(INPUT_POST, 'c_schooltype',  FILTER_VALIDATE_INT);
                                        $new_curriculum->state_id       = filter_input(INPUT_POST, 'c_state',       FILTER_VALIDATE_INT);
                                        $new_curriculum->country_id     = filter_input(INPUT_POST, 'c_country',     FILTER_VALIDATE_INT);
                                        $new_curriculum->icon_id        = filter_input(INPUT_POST, 'c_icon',        FILTER_VALIDATE_INT);
                                        $new_curriculum->creator_id     = $USER->id;  
                                        $gump = new Gump();             /* Validation */
                                        $_POST = $gump->sanitize($_POST);//sanitize $_POST
                                        $gump->validation_rules(array(
                                        'c_curriculum'     => 'required',
                                        'c_description'    => 'required',
                                        'c_subject'        => 'required',
                                        'c_grade'          => 'required',
                                        'c_schooltype'     => 'required',
                                        'c_state'          => 'required',
                                        'c_country'        => 'required',
                                        'c_icon'           => 'required'
                                        ));
                                        $validated_data = $gump->run($_POST);
                                        if($validated_data === false) {/* validation failed */
                                            assign_to_template($new_curriculum);    
                                            $TEMPLATE->assign('v_error', $gump->get_readable_errors());     
                                            $TEMPLATE->assign('showForm', true); 
                                            if (isset($_POST['update'])){ $TEMPLATE->assign('showForm', true); }
                                        } else {/* validation successful */    
                                            if (isset($_POST['add']))   { $new_curriculum->add(); }
                                            if (isset($_POST['update'])){ $new_curriculum->update();}            
                                        }  
            break;

        default: break;
    }    
}
/*******************************************************************************
 * END POST / GET
 */
$TEMPLATE->assign('page_title', 'Lehrpläne verwalten'); 
$curricula = new Curriculum(); 

// Markierte Zeilen assignen - sonst funktioniert der Paginator nicht
if (isset($_SESSION["PaginatorID"])) {           
    $TEMPLATE->assign('selectedID', str_replace('/','',$_SESSION["PaginatorID"])); //Setzt den ausgewählten Wert in der Curriculumtabelle
}

$countries = new State('DE');                                                   //Load country 
$TEMPLATE->assign('countries',      $countries->load($INSTITUTION->country_id));
$TEMPLATE->assign('states',         $countries->getStates());

$schooltypes = new Schooltype();                                                // Load schooltype 
$TEMPLATE->assign('schooltypes',    $schooltypes->getSchooltypes());
            
$grades = new Grade();                                                          // Load grades
$grades->institution_id             = $USER->institutions;
$TEMPLATE->assign('grades',         $grades->getGrades());

$subjects = new Subject();                                                      // Load subjects
$subjects->institution_id           = $USER->institutions;
$TEMPLATE->assign('subjects',       $subjects->getSubjects());
 
$icons = new File();                                                            // Load icons
$TEMPLATE->assign('icons',          $icons->getFiles('context', 5));


$p_options = array('delete' => array('onclick'      => "del('curriculum',__id__, $USER->id);", 
                                     'capability'   => checkCapabilities('curriculum:delete', $USER->role_id, false)),
                   'add'    => array('href'         => 'index.php?action=view&function=addObjectives&curriculum=__id__', 
                                     'capability'   => checkCapabilities('curriculum:addObjectives', $USER->role_id, false)),
                   'edit'   => array('href'         => 'index.php?action=curriculum&function=edit&c_id=__id__'),
                                     'capability'   => checkCapabilities('curriculum:update', $USER->role_id, false),);
$p_config  = array('curriculum'  => 'Lehrplan', 
                   'description' => 'Beschreibung', 
                   'subject'     => 'Fach',
                   'grade'       => 'Klassenstufe',
                   'schooltype'  => 'Schultyp',
                   'state'       => 'Bundesland/Region',
                   'de'          => 'Land',
                   'p_options'   => $p_options);
setPaginator('curriculumP', $TEMPLATE, $curricula->getCurricula('user', $USER->id, 'curriculumP'), 'cu_val', 'index.php?action=curriculum', $p_config);