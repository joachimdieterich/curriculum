<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename task.php
* @copyright 2017 Joachim Dieterich
* @author Joachim Dieterich
* @date 2017.08.20 08:04
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
global $PAGE, $USER, $TEMPLATE;
checkCapabilities('menu:readTasks', $USER->role_id);
$TEMPLATE->assign('breadcrumb',  array('Aufgaben' => 'index.php?action=task'));
$TEMPLATE->assign('page_title', 'Aufgaben');  
$institution = new Institution();
$TEMPLATE->assign('myInstitutions', $institution->getInstitutions('user', null, $USER->id));
if (checkCapabilities('menu:readCourseBook', $USER->role_id)){
$coursebook = new CourseBook();
    $TEMPLATE->assign('coursbook', $coursebook->get('user', null, null, true));
}

$courses = new Course();
if(checkCapabilities('user:userListComplete', $USER->role_id, false)){          // Load courses
    $TEMPLATE->assign('courses',              $courses->getCourse('admin_semester', $USER->id));  
}
if(checkCapabilities('user:userList',         $USER->role_id, false)){
    $TEMPLATE->assign('courses',              $courses->getCourse('teacher_semester', $USER->semester_id));  // abhängig von USER->my_semester id --> s. Select in objectives.tpl, 
}

$groups     = new Group();
$group_list = $groups->getGroups('institution', $USER->institution_id);   // Load groups --> only load groups of current institution to prevent enroling to groups of foreign institutions
$TEMPLATE->assign('groups_array', $group_list);  


$search = false;
if (isset($_POST) ){
    if (isset($_POST['search'])){
        $search = filter_input(INPUT_POST, 'search', FILTER_SANITIZE_STRING);
        $TEMPLATE->assign('task_reset', true); 
    }
}
/*$help   = new Help();
$TEMPLATE->assign('help', $help->get($search));   */