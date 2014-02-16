<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package core
 * @filename teacherGroups.php
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
global $USER, $TEMPLATE, $PAGE;

$selectedCurriculum = (isset($_GET['curriculum']) && $_GET['curriculum'] != '' ? $_GET['curriculum'] : '_'); //'_' ist das Trennungszeichen 
$TEMPLATE->assign('selectedCurriculum', $selectedCurriculum);
$curriculum = new Curriculum();

if (isset($_GET['function'])) {
     switch ($_GET['function']) {
        case "edit": 
                loadeditFormData ($TEMPLATE, $_GET['group_id']);  
                break; 
        case "semester": 
                loadeditFormData ($TEMPLATE, $_GET['group_id']);
                $TEMPLATE->assign('new_semester_form', true); 
                break; 
        case "showUsers": 
                $TEMPLATE->assign('showenroledUsers', true); 
                $TEMPLATE->assign('selected_group_id', $_GET['group_id']); 
                $users = new User();
                $resultUser = $users->userList('group', $_GET['group_id']);  
                if (isset($resultUser)){
                    setPaginator('userPaginator', $TEMPLATE, $resultUser, 'userResults', 'index.php?action=teacherGroups&function=showUsers&group_id='.$_GET['group_id']); //set Paginator    
                }
                break;
        case "expel_group": 
        case "showCurriculum": 
                // expel group
                if ($_GET['function'] == "expel_group"){
                    $group = new Group();
                    $group->id = $_GET['group_id'];
                    $group->load();
                    if ($group->expel($USER->id, $_GET['curriculumID'])) {
                        $curriculum->id = $_GET['curriculumID'];
                        $curriculum->load();
                        $PAGE->message[] = 'Lerngruppe <strong>'.$group->group.'</strong> wurde erfolgreich aus <strong>'.$curriculum->curriculum.'</strong> ausgeschrieben.';  
                    }
                }
                // Load curriculum list
                $TEMPLATE->assign('showenroledCurriculum', true); 
                $curricula = new Curriculum();
                $resultCurriculum = $curricula->getCurricula('group', $_GET['group_id']);
                if (isset($resultCurriculum)){
                    setPaginator('curriculumList', $TEMPLATE, $resultCurriculum, 'resultscurriculumList', 'index.php?action=teacherGroups&function=showCurriculum&group_id='.$_GET['group_id']); //set Paginator    
                }
                $TEMPLATE->assign('selected_group_id', $_GET['group_id']);
                break;        
       
        case "new_group": 
                $TEMPLATE->assign('new_group_form', true); 
                $TEMPLATE->assign('group_id', '');
                $TEMPLATE->assign('group', '');                
                $TEMPLATE->assign('description', '');
                $TEMPLATE->assign('grade_id', '');
                $TEMPLATE->assign('semester_id', '');
                $TEMPLATE->assign('institution_id', '');
                //addLog($USER->id, 'view', curPageURL(), 'new_group_form'); //Addlog
            break;
        
        default:
                break;
     }
}

if($_POST){ 
    $group = new Group();
    if (isset($_POST['curriculum'])){
        $curriculum->id = $_POST['curriculum'];
        $curriculum->load();
    }
    switch ($_POST) {
        case isset($_POST['enrole_group']):  foreach ($_POST['id'] as $check ) { 
                                                if($check == "none") {          
                                                    if (count($_POST['id']) == 1){
                                                        $PAGE->message[] = 'Es muss mindestens eine Lerngruppe ausgewählt werden!';
                                                    }	
                                                } else {
                                                    $group->id = $check;
                                                    $group->load();
                                                    if($group->checkEnrolment($_POST['curriculum'], $status = '1') > 0) { 
                                                        $PAGE->message[] = 'Die Lerngruppe <strong>'.$group->group.'</strong> ist bereits in <strong>'.$curriculum->curriculum.'</strong> eingeschrieben.';
                                                    } else {
                                                        $group->enrol($USER->id, $_POST['curriculum']);
                                                        $PAGE->message[] = 'Die Lergruppe <strong>'.$group->group.'</strong> wurde erfolgreich in <strong>'.$curriculum->curriculum.'</strong> eingeschrieben.';  
                                                    }
                                                }        
                                            }
            break;
        case isset($_POST['expel_group']):  foreach ( $_POST['id'] as $check ) { 
                                                if($check == "none") {
                                                    if (count($_POST['id']) == 1){
                                                        $PAGE->message[] = 'Es muss mindestens eine Lerngruppe ausgewählt werden!';
                                                        }	
                                                } else {
                                                    $group->id = $check;
                                                    $group->load();
                                                    if ($group->expel($USER->id, $_POST['curriculum'])) {
                                                        $PAGE->message[] = 'Lerngruppe <strong>'.$group->group.'</strong> wurde erfolgreich aus <strong>'.$curriculum->curriculum.'</strong> ausgeschrieben.';  
                                                    }   
                                                }        
                                            } 
            break;
        /* Delete multiple groups - not supportet yet
         * case isset($_POST['delete_group']): foreach ( $_POST['id'] as $check ) { 
                                                if($check == "none") {
                                                    if (count($_POST['id']) == 1){
                                                    $PAGE->message[] = 'Es muss mindestens eine Lerngruppe ausgewählt werden!';
                                                    }
                                                } else {
                                                    $group->id = $check;
                                                    $group->load();
                                                    if ($group->delete()){
                                                        $PAGE->message[] = 'Lerngruppe <strong>('.$group->group.')</strong> wurde erfolgreich gelöscht!';
                                                    }
                                                } 
                                            }
         break; 
         */

        case isset($_POST['add_group']):
        case isset($_POST['update_group']):
        case isset($_POST['change_semester']):
                                            if (isset($_POST['edit_group_id'])){
                                            $group->id             = $_POST['edit_group_id'];    
                                            }
                                            $group->group          = $_POST['group'];    
                                            $group->description    = $_POST['description'];  
                                            $group->grade_id       = $_POST['grade'];  
                                            $group->semester_id    = $_POST['semester'];  
                                            $group->creator_id     = $USER->id;  
                                            $group->institution_id = $_POST['institution'];

                                            $gump = new Gump(); /* Validation */
                                            $gump->validation_rules(array(
                                            'group'        => 'required',
                                            'description'  => 'required',
                                            'grade'        => 'required',
                                            'semester'     => 'required'
                                            ));
                                            $validated_data = $gump->run($_POST);
                                            
                                            if($validated_data === false) {/* validation failed */
                                                    foreach($group as $key => $value){
                                                    $TEMPLATE->assign($key, $value);
                                                    } 
                                                    $TEMPLATE->assign('v_error', $gump->get_readable_errors());     
                                                    $TEMPLATE->assign('new_group_form', true); 
                                                    if (isset($_POST['update_group'])){
                                                        $TEMPLATE->assign('edit_group_form', true);
                                                    }
                                                } else {/* validation successful */
                                                    if (isset($_POST['add_group'])){
                                                        $group->add();
                                                    }
                                                    if (isset($_POST['update_group'])){
                                                        $group->update();
                                                    }       
                                                    if (isset($_POST['change_semester'])){
                                                        if ($group->add('semester')) { //assume group members
                                                           if (isset($_POST['assumeUsers'])){
                                                               $group->changeSemester();
                                                               } 
                                                        }
                                                        $PAGE->message[] = 'Bei der Klasse <strong>('.$group->group.')</strong> wurde der Lernzeitraum erfolgreich geändert!';
                                                    }       
                                            }  
            break;
                                           
        default:
            break;
            
            
    }
    session_reload_user(); // --> get the changes immediately 
    $TEMPLATE->assign('page_message', $PAGE->message);    
}
/*******************************************************************************
 * END POST / GET 
 */

/**
 * load edit form data
 * @param array $TEMPLATE
 * @param int $check 
 */
function loadeditFormData ($TEMPLATE, $check) {
    $TEMPLATE->assign('new_group_form', true);
    $TEMPLATE->assign('edit_group_form', true);
    
    $group = new Group();
    $group->id = $check;
    $group->load();

    $TEMPLATE->assign('group_id',       $group->id);
    $TEMPLATE->assign('group',          $group->group);                
    $TEMPLATE->assign('description',    $group->description);
    $TEMPLATE->assign('grade_id',       $group->grade_id);
    $TEMPLATE->assign('semester_id',    $group->semester_id);
    $TEMPLATE->assign('institution_id', $group->institution_id);  
} 

//setContenttitle
$TEMPLATE->assign('teacherGroups', 'Lerngruppen verwalten');  

//Load curricula
$curricula = new Curriculum();
$result = $curricula->getCurricula('creator', $USER->id); 
$TEMPLATE->assign('curriculum_list', $result);

//Load Grades
$grades = new Grade();
$grades->institution_id = $USER->institutions["id"]; 
$TEMPLATE->assign('grade', $grades->getGrades());

//Load Semesters
$semesters = new Semester();
$semesters->institution_id = $USER->institutions["id"]; 
$TEMPLATE->assign('semester', $semesters->getSemesters());

//Gruppe laden
$groups = new Group();
$group_list = $groups->getGroups('group', $USER->id);
setPaginator('groupsPaginator', $TEMPLATE, $group_list, 'results', 'index.php?action=teacherGroups'); //set Paginator
?>