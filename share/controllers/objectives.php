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
$selected_curriculum_id     = (isset($_GET['course']) && trim($_GET['course'] != '') ? $_GET['course'] : '_'); //'_' ist das Trennungszeichen 
$selected_curriculumforURL  = $selected_curriculum_id;

if (isset($_GET['p_select'])){
    unset($_SESSION['SmartyPaginate']['userPaginator']['pagi_selection']);
    SmartyPaginate::setSelection($_GET['p_select'], 'userPaginator');
}

$selected_user_id           = SmartyPaginate::_getSelection('userPaginator'); 
$TEMPLATE->assign('selected_user_id',               $selected_user_id);
$TEMPLATE->assign('selected_curriculum_id',         $selected_curriculum_id); 
$TEMPLATE->assign('selected_certificate_template',  filter_input(INPUT_GET, 'certificate_template', FILTER_VALIDATE_INT));

list ($selected_curriculum_id, $selected_group) = explode('_', $selected_curriculum_id); //$selected_curriculum_id enthält curriculumid_groupid (zb. 32_24) wenn nur '_' gesetzt ist werden beide variabeln ''
$TEMPLATE->assign('sel_curriculum', $selected_curriculum_id); //only selected curriculum without group
$TEMPLATE->assign('sel_group_id',   $selected_group); //only selected group without curriculum
  

if(isset($_SESSION['PAGE']->config['tab']) AND $selected_curriculum_id != '' AND !isset($_GET['ajax'])){
    $TEMPLATE->assign($_SESSION['PAGE']->config['tab'],  true);
} else {
    $TEMPLATE->assign('f_userlist',  true);
}
// load user list
if ($selected_curriculum_id != '' AND !isset($_GET['ajax'])) {    
    $course_user        = new User();
    $course_user->id    = $USER->id;
    $users              = $course_user->getUsers('course', 'userPaginator', $selected_curriculum_id, $selected_group);
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
                           'page'      => array('onclick'    => 'checkrow(\'page\', \'userPaginator\', \'false\', \'index.php?action=objectives&course=\'+document.getElementById(\'course\').value+\'&certificate_template=\'+document.getElementById(\'certificate_template\').value);'),
                           'all'       => array('onclick'    => 'checkrow(\'all\', \'userPaginator\', \'false\', \'index.php?action=objectives&course=\'+document.getElementById(\'course\').value+\'&certificate_template=\'+document.getElementById(\'certificate_template\').value);'),
                           'checkbox'  => array('onclick'    => 'checkrow(\'__id__\', \'userPaginator\', \'false\', \'index.php?action=objectives&course=\'+document.getElementById(\'course\').value+\'&certificate_template=\'+document.getElementById(\'certificate_template\').value);'),
                           'td'        => array('onclick'    => 'checkrow(\'__id__\', \'userPaginator\', \'true\', \'index.php?action=objectives&course=\'+document.getElementById(\'course\').value+\'&p_select=__id__&certificate_template=\'+document.getElementById(\'certificate_template\').value);'));
    if(checkCapabilities('dashboard:globalAdmin', $USER->role_id, false)){
    $p_config      = array('id'        => 'checkbox',
                           'username'  => 'Benutzername', 
                           'firstname' => 'Vorname', 
                           'lastname'  => 'Nachname',
                           'completed' => 'Fortschritt',
                           'role_name' => 'Rolle',
                           /*'online'    => 'Status',  status test */
                           'p_search'  => array('username', 'firstname', 'lastname'),
                           'p_options' => $p_options,
                           't_config'  => $t_config);
    } else {
      if (checkCapabilities('user:shortUserList', $USER->role_id, false)){
          $p_config   = array('id'        => 'checkbox',
                              'username'  => 'Benutzername',
                              'firstname' => 'Vorname',
                              'lastname'  => 'Nachname',
                              'completed' => 'Fortschritt',
                           /* 'role_name' => 'Rolle',  */
                              'p_search'  => array('username', 'firstname', 'lastname'),
                              'p_options' => $p_options,
                              't_config'  => $t_config);
      }
      else {
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
    }
    setPaginator('userPaginator', $users, 'results', 'index.php?action=objectives&course='.$selected_curriculumforURL, $p_config); //set Paginator    
    /*course book*/
    $sel_course     = $courses->getCourseId($selected_curriculum_id, $selected_group);
    $coursebook     = new CourseBook();
    $TEMPLATE->assign('coursebook',      $coursebook->get('course', $sel_course->id) );
} 

// load curriculum of actual user 
if ($selected_curriculum_id != '' AND $selected_user_id != '' AND isset($selected_user_id[0])) {
    if (count($selected_user_id) > 1){
        $terminal_objectives = new TerminalObjective();         //load terminal objectives
        $TEMPLATE->assign('terminalObjectives', $terminal_objectives->getObjectives('curriculum', $selected_curriculum_id));
        $enabling_objectives = new EnablingObjective();         //load enabling objectives
        $TEMPLATE->assign('enabledObjectives', $enabling_objectives->getObjectives('group', $selected_curriculum_id, $selected_user_id));
        $show_course         = true; // setzen
    } else {
        $user                = new User(); 
        $user->load('id', $selected_user_id[0], false);
        $TEMPLATE->assign('user', $user);
        $group               = new Group();   
        $TEMPLATE->assign('group', $group->getGroups('course', $selected_group));
        $terminal_objectives = new TerminalObjective();         //load terminal objectives
        $TEMPLATE->assign('terminalObjectives', $terminal_objectives->getObjectives('curriculum', $selected_curriculum_id));
        $enabling_objectives = new EnablingObjective();         //load enabling objectives
        $enabling_objectives->curriculum_id = $selected_curriculum_id;
        $TEMPLATE->assign('enabledObjectives', $enabling_objectives->getObjectives('user', $selected_user_id[0]));
        $show_course         = true; // setzen    
    }     
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

$content = new Content();
$TEMPLATE->assign('cur_content', array('label'=>'Digitalisierte Texte des Lehrplans', 'entrys'=> $content->get('curriculum', $selected_curriculum_id)));