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
$TEMPLATE->assign('page_title',  'Lernstand eintragen');
$TEMPLATE->assign('breadcrumb',  array('Lernstand eintragen' => 'index.php?action=objectives'));
$courses = new Course();
if(isset($_GET['reset']) OR (isset($_POST['reset']))){
    resetPaginator('userPaginator');            //used to reset / set userPaginator when calling from mail
}

$showuser                   = false;            //zurücksetzen
$show_course                = false;            // zurücksetzen
$selected_curriculum        = (isset($_GET['course']) && trim($_GET['course'] != '') ? $_GET['course'] : '_'); //'_' ist das Trennungszeichen 
$selected_curriculumforURL  = $selected_curriculum;

if (isset($_GET['p_select'])){
    unset($_SESSION['SmartyPaginate']['userPaginator']['pagi_selection']);
    SmartyPaginate::setSelection($_GET['p_select'], 'userPaginator');
}
$selected_user_id           = SmartyPaginate::_getSelection('userPaginator'); 
$TEMPLATE->assign('selected_user_id',               $selected_user_id);
$TEMPLATE->assign('selected_curriculum',            $selected_curriculum); 
$TEMPLATE->assign('selected_certificate_template',  filter_input(INPUT_GET, 'certificate_template', FILTER_VALIDATE_INT));

list ($selected_curriculum, $selected_group) = explode('_', $selected_curriculum); //$selected_curriculum enthält curriculumid_groupid (zb. 32_24) wenn nur '_' gesetzt ist werden beide variabeln ''
$TEMPLATE->assign('sel_curriculum', $selected_curriculum); //only selected curriculum without group
$TEMPLATE->assign('sel_group_id',   $selected_group); //only selected group without curriculum
 
if (isset($_POST['printCertificate'])){
    $TEMPLATE->assign('sel_curriculum',                 $_POST['sel_curriculum']);
    $TEMPLATE->assign('sel_group_id',                   $_POST['sel_group_id']); 
    $TEMPLATE->assign('selected_certificate_template',  $_POST['certificate_template']); 
    if ($_POST['certificate_template'] != '-1'){
        $pdf = new Pdf();
        $pdf->user_id       =  $selected_user_id;
        $pdf->curriculum_id =  $_POST['sel_curriculum'];
        $certificate        =  new Certificate();
        $certificate->id    = $_POST['certificate_template'];
        $certificate->load();
        $pdf->template      = $certificate->template;
        $pdf->generate_certificate_from_template('from_template');
    } else {
        $PAGE->message[]    = array('message' => 'Zertifikatvorlage muss gewählt werden', 'icon' => 'fa-files-o text-warning');
    }
} 

// load curriculum of actual user 
//error_log(json_encode($selected_user_id));
if ($selected_curriculum != '' AND $selected_user_id != '' AND isset($selected_user_id[0])) {
    if (count($selected_user_id) > 1){
        $terminal_objectives = new TerminalObjective();         //load terminal objectives
        $TEMPLATE->assign('terminalObjectives', $terminal_objectives->getObjectives('curriculum', $selected_curriculum));
        $enabling_objectives = new EnablingObjective();         //load enabling objectives
        $TEMPLATE->assign('enabledObjectives', $enabling_objectives->getObjectives('group', $selected_curriculum, $selected_user_id));
        $show_course         = true; // setzen
    } else {
        $user   = new User(); 
        $user->load('id', $selected_user_id[0], false);
        $TEMPLATE->assign('user', $user);
        $group  = new Group();   
        $TEMPLATE->assign('group', $group->getGroups('course', $selected_group));
        $terminal_objectives = new TerminalObjective();         //load terminal objectives
        $TEMPLATE->assign('terminalObjectives', $terminal_objectives->getObjectives('curriculum', $selected_curriculum));
        $enabling_objectives = new EnablingObjective();         //load enabling objectives
        $enabling_objectives->curriculum_id = $selected_curriculum;
        $TEMPLATE->assign('enabledObjectives', $enabling_objectives->getObjectives('user', $selected_user_id[0]));
        $show_course         = true; // setzen    
    }     
    
}    
// load user list
if ($selected_curriculum != '') {  
    $course_user        = new User();
    $course_user->id    = $USER->id;
    $users              = $course_user->getUsers('course', 'userPaginator', $selected_curriculum, $selected_group);
    if (is_array($users)){
        foreach ($users as $value) {                         //erzeuge id Liste der user
            $list[]     = $value->id;
        }
        $TEMPLATE->assign('userlist', implode(',', $list));  
    } else {
        $showuser  = true;
    }  
    $p_options     = array('mailnew'   => array('onclick'       => 'formloader(\'mail\', \'gethelp\', __id__);',
                                                   'capability' => checkCapabilities('mail:postMail', $USER->role_id, false),
                                                   'icon'       => 'fa fa-envelope',
                                                   'tooltip'    => 'Nachricht schreiben'));
    $t_config      = array('table_id'  => array('id'         => 'contentsmalltable'),
                           'page'      => array('onclick'    => 'checkrow(\'page\', \'userPaginator\', \'index.php?action=objectives&course=\'+document.getElementById(\'course\').value+\'&certificate_template=\'+document.getElementById(\'certificate_template\').value);'),
                           'all'       => array('onclick'    => 'checkrow(\'all\', \'userPaginator\', \'index.php?action=objectives&course=\'+document.getElementById(\'course\').value+\'&certificate_template=\'+document.getElementById(\'certificate_template\').value);'),
                           'checkbox'  => array('onclick'    => 'checkrow(\'__id__\', \'userPaginator\', \'index.php?action=objectives&course=\'+document.getElementById(\'course\').value+\'&certificate_template=\'+document.getElementById(\'certificate_template\').value);'),
                           'td'        => array('onclick'    => 'checkrow(\'__id__\', \'userPaginator\', \'index.php?action=objectives&course=\'+document.getElementById(\'course\').value+\'&certificate_template=\'+document.getElementById(\'certificate_template\').value);'));
    if(checkCapabilities('dashboard:globalAdmin', $USER->role_id, false)){
    $p_config      = array('id'        => 'checkbox',
                           'username'  => 'Benutzername', 
                           'firstname' => 'Vorname', 
                           'lastname'  => 'Nachname',
                           'completed' => 'Fortschritt',
                           'role_name' => 'Rolle',
                           'online'    => 'Status', /* status test */
                           'p_search'  => array('username', 'firstname', 'lastname'),
                           'p_options' => $p_options,
                           't_config'  => $t_config);
    } else {
       $p_config   = array('id'        => 'checkbox',
                           'username'  => 'Benutzername', 
                           'firstname' => 'Vorname', 
                           'lastname'  => 'Nachname',
                           'completed' => 'Fortschritt',
                           'role_name' => 'Rolle',
                           'p_search'  => array('username', 'firstname', 'lastname'),
                           'p_options' => $p_options,
                           't_config'  => $t_config); 
    }
    setPaginator('userPaginator', $TEMPLATE, $users, 'results', 'index.php?action=objectives&course='.$selected_curriculumforURL, $p_config); //set Paginator    
    /*course book*/
    $sel_course     = $courses->getCourseId($selected_curriculum, $selected_group);
    $coursebook     = new CourseBook();
    $TEMPLATE->assign('coursebook',      $coursebook->get('course', $sel_course->id) );
}
/*******************************************************************************
 * END POST / GET  
 */    
$TEMPLATE->assign('showuser',                 $showuser);
$TEMPLATE->assign('show_course',              $show_course);

if(checkCapabilities('user:userListComplete', $USER->role_id, false)){          // Load courses
    $TEMPLATE->assign('courses',              $courses->getCourse('admin_semester', $USER->id));  
}
if(checkCapabilities('user:userList',         $USER->role_id, false)){
    $TEMPLATE->assign('courses',              $courses->getCourse('teacher_semester', $USER->semester_id));  // abhängig von USER->my_semester id --> s. Select in objectives.tpl, 
}
$certificate                 = new Certificate();                               // Load certificate_templates
$certificate->institution_id = $USER->institutions;
$TEMPLATE->assign('certificate_templates', $certificate->getCertificates());

$box_bg = array('0' => 'bg-white',
                '' => 'bg-white',
                'x0' => 'bg-red',
                '0x' => 'bg-white',
                '1x' => 'bg-white',
                '2x' => 'bg-white',
                '3x' => 'bg-white',
                '00' => 'bg-red',
                '10' => 'bg-red',
                '20' => 'bg-red',
                '30' => 'bg-red',
                'x1' => 'bg-green',
                '1' => 'bg-green',
                '01' => 'bg-green',
                '11' => 'bg-green',
                '21' => 'bg-green',
                '31' => 'bg-green',
                'x2' => 'bg-orange',
                '02' => 'bg-orange',
                '2' => 'bg-orange',
                '12' => 'bg-orange',
                '22' => 'bg-orange',
                '32' => 'bg-orange',
                'x3' => 'bg-white',
                '3' => 'bg-white',
                '03' => 'bg-white',
                '13' => 'bg-white',
                '23' => 'bg-white',
                '33' => 'bg-white',
                );
$TEMPLATE->assign('box_bg',$box_bg);

$content = new Content();
$TEMPLATE->assign('cur_content', array('label'=>'Hinweise zum Lehrplan', 'entrys'=> $content->get('curriculum', $selected_curriculum)));