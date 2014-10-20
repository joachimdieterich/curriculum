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
global $USER, $TEMPLATE, $PAGE, $INSTITUTION;

if(isset($_GET['reset']) OR (isset($_POST['reset'])) OR (isset($_POST['new_curriculum']))){
    resetPaginator('groupsPaginator'); 
    resetPaginator('userPaginator'); 
    resetPaginator('curriculumPaginator'); 
}

if (isset($_GET['function'])){
    switch ($_GET['function']) {
        case "new_curriculum":  loadeditFormData($TEMPLATE);
            break; 
        case "edit":            loadeditFormData($TEMPLATE, $_GET['edit_curriculum_id']);
                                $TEMPLATE->assign('showeditCurriculumForm', true);
            break;
        default: break;
    }
}

if ($_POST){
    $new_curriculum = new Curriculum();
    switch ($_POST) {
        case isset($_POST['add']):
        case isset($_POST['update']):   if (isset($_POST['edit_curriculum_id'])){
                                            $new_curriculum->id             = $_POST['edit_curriculum_id'];    
                                        }
                                        $new_curriculum->curriculum     = $_POST['curriculum'];    
                                        $new_curriculum->description    = $_POST['description'];  
                                        $new_curriculum->subject_id     = $_POST['subject'];
                                        $new_curriculum->grade_id       = $_POST['grade'];  
                                        $new_curriculum->schooltype_id  = $_POST['schooltype'];  
                                        $new_curriculum->state_id       = $_POST['state'];  
                                        $new_curriculum->country_id     = $_POST['country'];  
                                        $new_curriculum->icon_id        = $_POST['icon'];  
                                        $new_curriculum->creator_id     = $USER->id;  
                                        $gump = new Gump(); /* Validation */
                                        $gump->validation_rules(array(
                                        'curriculum'     => 'required',
                                        'description'    => 'required',
                                        'subject'        => 'required',
                                        'grade'          => 'required',
                                        'schooltype'     => 'required',
                                        'state'          => 'required',
                                        'country'        => 'required',
                                        'icon'           => 'required'
                                        ));
                                        $validated_data = $gump->run($_POST);
                                        if($validated_data === false) {/* validation failed */
                                                foreach($new_curriculum as $key => $value){
                                                $TEMPLATE->assign($key, $value);
                                                } 
                                                $TEMPLATE->assign('v_error', $gump->get_readable_errors());     
                                                $TEMPLATE->assign('edit_form', true); 
                                                if (isset($_POST['update'])){
                                                    $TEMPLATE->assign('edit_form', true);
                                                }
                                            } else {/* validation successful */
                                                if (isset($_POST['add'])){
                                                    $new_curriculum->add();
                                                }
                                                if (isset($_POST['update'])){
                                                    $new_curriculum->update();
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

$curricula = new Curriculum(); 

/**
 * load edit form data
 * @param array $TEMPLATE
 * @param int $check 
 */
function loadeditFormData ($TEMPLATE, $check = null) {
    $TEMPLATE->assign('edit_form', true);
    
    
    $new_curriculum = new Curriculum(); 
    $new_curriculum->id = $check; 
    if ($check != null) {
        $new_curriculum->load();
    }
    
    $TEMPLATE->assign('edit_curriculum_id', $new_curriculum->id);
    $TEMPLATE->assign('curriculum',         $new_curriculum->curriculum);                
    $TEMPLATE->assign('description',        $new_curriculum->description);
    $TEMPLATE->assign('subject_id',         $new_curriculum->subject_id);
    $TEMPLATE->assign('icon_id',            $new_curriculum->icon_id);
    $TEMPLATE->assign('grade_id',           $new_curriculum->grade_id);
    $TEMPLATE->assign('schooltype_id',      $new_curriculum->schooltype_id);
    $TEMPLATE->assign('state_id',           $new_curriculum->state_id);
    $TEMPLATE->assign('country_id',         $new_curriculum->country_id);
}

// Markierte Zeilen assignen - sonst funktioniert der Paginator nicht
if (isset($_SESSION["PaginatorID"])) {           
    $TEMPLATE->assign('selectedID', str_replace('/','',$_SESSION["PaginatorID"])); //Setzt den ausgewählten Wert in der Curriculumtabelle
}
$TEMPLATE->assign('page_message',   $PAGE->message);
$TEMPLATE->assign('page_title', 'Lehrpläne verwalten');  //$Page Title

//Load country 
$countries = new State('DE'); 

$TEMPLATE->assign('countries',      $countries->load($INSTITUTION->institution_standard_country));
$TEMPLATE->assign('states',         $countries->getStates());

// Load schooltype 
$schooltypes = new Schooltype(); 
$TEMPLATE->assign('schooltypes',    $schooltypes->getSchooltypes());
            
// Load grades
$grades = new Grade(); 
$grades->institution_id             = $USER->institutions["id"];
$TEMPLATE->assign('grades',         $grades->getGrades());

// Load subjects
$subjects = new Subject(); 
$subjects->institution_id           = $USER->institutions["id"];
$TEMPLATE->assign('subjects',       $subjects->getSubjects());
 
// Load icons
$icons = new File();
$TEMPLATE->assign('icons',          $icons->getFiles('context', 5));

// Load curriculum
$curriculum_list = $curricula->getCurricula('user', $USER->id);
setPaginator('curriculumPaginator', $TEMPLATE, $curriculum_list, 'results', 'index.php?action=curriculum'); //set Paginator    
?>