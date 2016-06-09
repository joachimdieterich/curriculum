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
global $USER, $TEMPLATE, $PAGE;
$TEMPLATE->assign('page_title',     'Lernstand eintragen');
$TEMPLATE->assign('breadcrumb',  array('Lernstand eintragen' => 'index.php?action=objectives'));
$courses = new Course();
if(isset($_GET['reset']) OR (isset($_POST['reset']))){
    resetPaginator('userPaginator');            
}

$showuser       = false;  //zurücksetzen
$show_course    = false; // zurücksetzen

$selected_curriculum        = (isset($_GET['course']) && trim($_GET['course'] != '') ? $_GET['course'] : '_'); //'_' ist das Trennungszeichen 
$selected_curriculumforURL  = $selected_curriculum;
$selected_user_id           = explode(',',(isset($_GET['userPaginator_sel_id']) && trim($_GET['userPaginator_sel_id'] != '') ? $_GET['userPaginator_sel_id'] : '')); //generates array
$TEMPLATE->assign('selected_curriculum',            $selected_curriculum); 
$TEMPLATE->assign('selected_user_id',               $selected_user_id);
$TEMPLATE->assign('selected_certificate_template',  filter_input(INPUT_GET, 'certificate_template', FILTER_VALIDATE_INT));

list ($selected_curriculum, $selected_group) = explode('_', $selected_curriculum); //$selected_curriculum enthält curriculumid_groupid (zb. 32_24) wenn nur '_' gesetzt ist werden beide variabeln ''
$TEMPLATE->assign('sel_curriculum', $selected_curriculum); //only selected curriculum without group
$TEMPLATE->assign('sel_group_id',   $selected_group); //only selected group without curriculum
if (isset($_POST['printCertificate'])){
        $TEMPLATE->assign('sel_curriculum',                 $_POST['sel_curriculum']);
        $TEMPLATE->assign('selected_user_id',               explode(',',$_POST['sel_user_id']));
        $TEMPLATE->assign('sel_group_id',                   $_POST['sel_group_id']); 
        $TEMPLATE->assign('selected_certificate_template',  $_POST['certificate_template']); 
    if ($_POST['certificate_template'] != '-1'){
        $pdf = new Pdf();
        $pdf->user_id =         explode(',', $_POST['sel_user_id']);
        $pdf->curriculum_id =   $_POST['sel_curriculum'];
        $certificate =          new Certificate();
        $certificate->id        = $_POST['certificate_template'];
        
        $certificate->load();
        $pdf->template = $certificate->template;
        $pdf->generate_certificate_from_template('from_template');
    } else {
        $PAGE->message[] = array('message' => 'Zertifikatvorlage muss gewählt werden', 'icon' => 'fa-files-o text-warning');
    }
} 
if ($selected_curriculum != '' AND $selected_user_id != '' AND $selected_user_id[0] !== '') {
    if (count($selected_user_id) > 1){
        $terminal_objectives = new TerminalObjective();         //load terminal objectives
        $TEMPLATE->assign('terminalObjectives', $terminal_objectives->getObjectives('curriculum', $selected_curriculum));

        $enabling_objectives                = new EnablingObjective();         //load enabling objectives
        $enabling_objectives->curriculum_id = $selected_curriculum;
        
        $TEMPLATE->assign('enabledObjectives', $enabling_objectives->getObjectives('group', $selected_curriculum, $selected_user_id));

        $show_course = true; // setzen
    } else {
        $user   = new User(); 
        $user->load('id', $selected_user_id[0]);
        $TEMPLATE->assign('user', $user);

        $group  = new Group();   
        $TEMPLATE->assign('group', $group->getGroups('course', $selected_group));

        $terminal_objectives = new TerminalObjective();         //load terminal objectives
        $TEMPLATE->assign('terminalObjectives', $terminal_objectives->getObjectives('curriculum', $selected_curriculum));

        $enabling_objectives = new EnablingObjective();         //load enabling objectives
        $enabling_objectives->curriculum_id = $selected_curriculum;
        $TEMPLATE->assign('enabledObjectives', $enabling_objectives->getObjectives('user', $selected_user_id[0]));

        $show_course = true; // setzen    
    }       
}    
// load curriculum of actual user 
if ($selected_curriculum != '') {    
    $course_user        = new User();
    $course_user->id    = $USER->id;
    $users              = $course_user->getUsers('course', 'userPaginator', $selected_curriculum, $selected_group);
    
    if (is_array($users)){
        foreach ($users as $value) {                         //erzeuge id Liste der user
            $list[] = $value->id;
        }
        $TEMPLATE->assign('userlist', implode(',', $list));  
        
        $user_id_list = array_map(function($user) { return $user->id; }, $users); 
        /*if ($selected_user_id == 'all'){
            $TEMPLATE->assign('allUsers', $user_id_list);
        }*/
        $p_options = array('mailnew'   => array('href'     => 'index.php?action=messages&function=shownewMessage&subject=-&receiver_id=__id__&answer=true',
                                        'capability'    => checkCapabilities('mail:postMail', $USER->role_id, false),
                                        'icon'         => 'fa fa-send'));
        $t_config  = array('table_id'  => array('id'    => 'contentsmalltable'),
                           /*'tr'        => array('class' => )*/
                           'checkbox'  => array('onclick'  => 'checkrow(\'__id__\', \'id[]\', \'userPaginator\', \'index.php?action=objectives&course=\'+document.getElementById(\'course\').value+\'&paginator=userPaginator&certificate_template=\'+document.getElementById(\'certificate_template\').value);'),
                           'td'        => array('onclick'  => 'window.location.assign(\'index.php?action=objectives&course=\'+document.getElementById(\'course\').value+\'&paginator=userPaginator&userPaginator_sel_id=__id__&certificate_template=\'+document.getElementById(\'certificate_template\').value);'));
        $p_config  = array('id'        => 'checkbox',
                           'username'  => 'Benutzername', 
                           'firstname' => 'Vorname', 
                           'lastname'  => 'Nachname',
                           'completed' => 'Fortschritt',
                           'role_name' => 'Rolle',
                           'p_options' => $p_options,
                           't_config'  => $t_config);
        //setPaginator('userPaginator', $TEMPLATE, $users, 'results', 'index.php?action=objectives&course='.$selected_curriculumforURL); //set Paginator    
        setPaginator('userPaginator', $TEMPLATE, $users, 'results', 'index.php?action=objectives&course='.$selected_curriculumforURL, $p_config); //set Paginator    
        //User-Solutions laden
        $files = new File(); 
        $TEMPLATE->assign('addedSolutions', $files->getSolutions('course', $user_id_list, $selected_curriculum)); 
    } else {
        $showuser = true;
    }  
    /*course book*/
    $sel_course = $courses->getCourseId($selected_curriculum, $selected_group);
    $coursebook = new CourseBook();
    $TEMPLATE->assign('coursebook',      $coursebook->get('course', $sel_course->id) );
}
/*******************************************************************************
 * END POST / GET  
 */    
$TEMPLATE->assign('showuser',       $showuser);
$TEMPLATE->assign('show_course',    $show_course);


// Load courses
 
if(checkCapabilities('user:userListComplete', $USER->role_id, false)){
    $TEMPLATE->assign('courses',        $courses->getCourse('admin', $USER->id));  
}
if(checkCapabilities('user:userList', $USER->role_id, false)){
    $TEMPLATE->assign('courses',        $courses->getCourse('teacher', $USER->id));  // abhängig von USER->my_semester id --> s. Select in objectives.tpl, 
}

// Load certificate_templates
$certificate =                      new Certificate(); 
$certificate->institution_id =      $USER->institutions;

$TEMPLATE->assign('certificate_templates', $certificate->getCertificates());
