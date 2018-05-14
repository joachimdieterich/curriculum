<?php 
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename calendar.php
* @copyright 2016 Joachim Dieterich
* @author Joachim Dieterich
* @date 2016.05.11 21:06
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

$TEMPLATE->assign('page_title', 'Kalender'); 
$TEMPLATE->assign('breadcrumb',  array('Kalender' => 'index.php?action=calendar'));
$LOG->add($USER->id, 'view', $PAGE->url, 'calendar'); 

$e      = new Event();
$events = array();
$ev     = new stdClass();
foreach ($e->get('user', $USER->id) as $value) {
    $ev->id               = $value->id;
    $ev->title            = $value->event;
    $ev->start            = $value->timestart;
    $ev->end              = $value->timeend;
    $ev->backgroundColor  = "#00c0ef";
    $ev->borderColor      = "#00c0ef";
    $events[]             = clone $ev;
}

 
$course     = new Course();
$ids        = $course->getCourseIds('user', $USER->id);
$schedule   = new Schedule();
$schedules  = $schedule->get('course', $ids);
//error_log(json_encode($schedules));
foreach ($schedules as $value) {
    
    //{"id":"1","schedule":"1. Stunde","description":"Raum 0","context_id":"17","reference_id":"141","repeat_id":"2",
    //"date_start":"2017-01-27","date_end":"2019-01-27","time_start":"09:00:00","duration":"09:45:00","creation_time":"2018-01-27 16:19:09","creator_id":"102"}
    $ev->id               = $value->id;
    $ev->title            = $value->schedule;
    $ev->dowstart         = $value->date_start;
    $ev->dowend           = $value->date_end;
    $ev->dow              = '['.$value->dow.']';
    $ev->start            = $value->time_start;
    $ev->end              = $value->time_end;
    $ev->resourceId       = $value->reference_id;
    $group                = new Group();
    $group->
    $ev->backgroundColor  = "#00c0ef";
    $ev->borderColor      = "#00c0ef";
    $events[]             = clone $ev;
}
//error_log(json_encode($events));
$TEMPLATE->assign('events', json_encode($events));       
  