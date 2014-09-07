<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename objectives.php
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

if(isset($_GET['reset']) OR (isset($_POST['reset']))){
    resetPaginator('userPaginator');            
}

$showuser = false;  //zurücksetzen
$show_course = false; // zurücksetzen

$selected_curriculum = (isset($_GET['course']) && trim($_GET['course'] != '') ? $_GET['course'] : '_'); //'_' ist das Trennungszeichen 
$selected_curriculumforURL = $selected_curriculum;
$selected_user_id = (isset($_GET['userID']) && trim($_GET['userID'] != '') ? $_GET['userID'] : '');
$TEMPLATE->assign('selected_curriculum', $selected_curriculum);
$TEMPLATE->assign('selected_user_id', $selected_user_id);
list ($selected_curriculum, $selected_group) = explode('_', $selected_curriculum); //$selected_curriculum enthält curriculumid_groupid (zb. 32_24) wenn nur '_' gesetzt ist werden beide variabeln ''
$TEMPLATE->assign('sel_curriculum', $selected_curriculum); //only selected curriculum without group
$TEMPLATE->assign('sel_group_id', $selected_group); //only selected curriculum without group
if (isset($_POST['printCertificate'])){
    $pdf = new Pdf();
    $pdf->user_id = $_POST['sel_user_id'];
    $pdf->curriculum_id = $_POST['sel_curriculum'];
    $TEMPLATE->assign('sel_curriculum', $_POST['sel_curriculum']);
    $TEMPLATE->assign('selected_user_id', $_POST['sel_user_id']);
    $TEMPLATE->assign('sel_group_id', $_POST['sel_group_id']); 

    $pdf->generate_pdf();
}
if (isset($_POST['printAllCertificate'])){
    $pdf = new Pdf();
    $pdf->user_id = $_POST['sel_user_id'];
    $pdf->curriculum_id = $_POST['sel_curriculum'];
    
    $pdf->group_id = $_POST['sel_group_id'];
    $TEMPLATE->assign('sel_curriculum', $_POST['sel_curriculum']);
    $TEMPLATE->assign('selected_user_id', $_POST['sel_user_id']);
    $TEMPLATE->assign('sel_group', $_POST['sel_group_id']); 
    $pdf->generate_pdf();
}
 
if ($selected_curriculum != '' AND $selected_user_id != '') {
    $user = new User(); 
    $user->load('id', $selected_user_id);
    $TEMPLATE->assign('user', $user);
    
    $group = new Group();   
    $TEMPLATE->assign('group', $group->getGroups('course', $selected_group));
      
    $terminal_objectives = new TerminalObjective();         //load terminal objectives
    $TEMPLATE->assign('terminalObjectives', $terminal_objectives->getObjectives('curriculum', $selected_curriculum));
    
    $enabling_objectives = new EnablingObjective();         //load enabling objectives
    $enabling_objectives->curriculum_id = $selected_curriculum;
    $TEMPLATE->assign('enabledObjectives', $enabling_objectives->getObjectives('user', $selected_user_id));
    
    $show_course = true; // setzen
}    
// load curriculum of actual user 
if ($selected_curriculum != '') {    
    $course_user = new User();
    $course_user->id = $USER->id;
    $users = $course_user->getUsers('course', $selected_curriculum);
    if (is_array($users)){
        $user_id_list = array_map(function($user) { return $user->id; }, $users); 
        setPaginator('userPaginator', $TEMPLATE, $users, 'results', 'index.php?action=objectives&course='.$selected_curriculumforURL); //set Paginator    
        //User-Solutions laden
        $files = new File(); 
        $TEMPLATE->assign('addedSolutions', $files->getSolutions('course', $selected_curriculum, $user_id_list)); 
    } else {
        $showuser = true;
    }  
}
/*******************************************************************************
 * END POST / GET  
 */    
$TEMPLATE->assign('showuser', $showuser);
$TEMPLATE->assign('show_course', $show_course);
$TEMPLATE->assign('page_message', $PAGE->message);
$TEMPLATE->assign('page_title', 'Lernstand eintragen');

// Load courses
$courses = new Course(); 
$TEMPLATE->assign('courses', $courses->getCourse('admin', $USER->id));     
$TEMPLATE->assign('page_message', $PAGE->message);
?>