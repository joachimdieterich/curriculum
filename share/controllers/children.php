<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename children.php
* @copyright 2017 Joachim Dieterich
* @author Joachim Dieterich
* @date 2017.06.18 17:08
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

$TEMPLATE->assign('breadcrumb',  array('Lernstand (Kinder)' => 'index.php?action=objectives'));
$courses = new Course();

if (isset($_GET['curriculum_id']) && trim($_GET['curriculum_id'] != '')){
    $selected_curriculum_id     = $_GET['curriculum_id']; 
    $TEMPLATE->assign('selected_curriculum_id', $selected_curriculum_id); 
} else {
    $TEMPLATE->assign('selected_curriculum_id', ''); 
}

$selected_user_id = $_SESSION['USER']->child_selected;
if (isset($selected_user_id)){
    $user                   = new User(); 
    $user->load('id', $selected_user_id);
    $TEMPLATE->assign('user', $user);
    $TEMPLATE->assign('courses', $user->enrolments);  // abhängig von USER->my_semester id --> s. Select in objectives.tpl, 
    $TEMPLATE->assign('selected_user_id',               $selected_user_id);
} 

// load curriculum of actual child 
if (isset($selected_curriculum_id)) {
    $terminal_objectives = new TerminalObjective();         //load terminal objectives
    $TEMPLATE->assign('terminalObjectives', $terminal_objectives->getObjectives('curriculum', $selected_curriculum_id));
    $enabling_objectives = new EnablingObjective();         //load enabling objectives
    $enabling_objectives->curriculum_id = $selected_curriculum_id;
    $TEMPLATE->assign('enabledObjectives', $enabling_objectives->getObjectives('user', $selected_user_id));
}  

$TEMPLATE->assign('page_title',  'Lernstand ('.$user->firstname.' '.$user->lastname.')');