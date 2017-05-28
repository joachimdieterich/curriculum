<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename dashboard.php
* @copyright  2013 Joachim Dieterich  {@link http://www.joachimdieterich.de}
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
global $USER, $PAGE, $TEMPLATE, $LOG, $CONTEXT;
$TEMPLATE->assign('page_title', 'Startseite'); 
$TEMPLATE->assign('breadcrumb',  array('Startseite' => 'index.php?action=dashboard'));

$institution->id        = $USER->institution_id;
$TEMPLATE->assign('bulletinBoard',      $institution->getBulletinBoard());

/* Load blocks*/
/*accomplished_obj/institutions/groups/upcoming events/statistic --> now realized as blocks*/
$blocks                 = new Block();
$blocks->context_id     = $CONTEXT['dashboard']->id; //== dashboard todo get value from context table
$blocks->institution_id = $USER->institution_id;
$TEMPLATE->assign('blocks', $blocks->get());
/*$cron                 = new Cron(); 
$cron->detectExpiredObjective();      // Überprüft einmal pro Tag ob Ziele abgelaufen sind.
$TEMPLATE->assign('cronjob', 'Es wurde zuletzt am '.$cron->check_cronjob().' geprüft, ob Ziele abgelaufen sind.');*/
$LOG->add($USER->id, 'view', $PAGE->url,  'Browser: '.$PAGE->browser); /* add log */