<?php 
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename courseBook.php
* @copyright 2016 Joachim Dieterich
* @author Joachim Dieterich
* @date 2016.05.07 09:52
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
global $USER, $TEMPLATE, $PAGE, $LOG;

$TEMPLATE->assign('page_title', 'Kursbuch'); 
$TEMPLATE->assign('breadcrumb',  array('Kursbuch' => 'index.php?action=courseBook'));
$LOG->add($USER->id, 'view', $PAGE->url, 'courseBook'); 

$date = '';
if (isset($_GET['date'])){
    $date = date('Y-m-d G:i:s', strtotime($_GET['date']));
}

if (checkCapabilities('menu:readCourseBook', $USER->role_id)){
    $coursebook = new CourseBook();
    $p_config   =   array('topic'   => 'Thema', 
                    'description'   => 'Beschreibung', 
                    'creation_time' => 'Datum', 
                    'curriculum_id' => 'Lehrplan_ID', 
                    'curriculum'    => 'Lehrplan');
    setPaginator('coursebookP', $coursebook->get('user', $USER->id, $date, true, 'coursebookP'), 'coursebook', 'index.php?action=courseBook', $p_config); //set Paginator   
} 