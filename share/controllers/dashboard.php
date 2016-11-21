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
global $USER, $PAGE, $TEMPLATE, $LOG;
$TEMPLATE->assign('page_title', 'Startseite'); 
$TEMPLATE->assign('breadcrumb',  array('Dashboard' => 'index.php?action=dashboard'));

$acc_obj        = new EnablingObjective();
$TEMPLATE->assign('enabledObjectives', $acc_obj->getObjectiveStatusChanges()); /* Load last accomplished Objectives */

$institution    = new Institution();
$TEMPLATE->assign('myInstitutions', $institution->getStatistic($USER->id)); /* Institution / Schulen laden */
$institution->id = $USER->institution_id;
$TEMPLATE->assign('bulletinBoard', $institution->getBulletinBoard());

$groups         = new Group(); 
$TEMPLATE->assign('myClasses', $groups->getGroups('user', $USER->id));

/* Load blocks*/
$blocks         = new Block();
$blocks->context_id = 11; //== dashboard todo get value from context table
$blocks->institution_id = $USER->institution_id;
$TEMPLATE->assign('blocks', $blocks->get());
//$cron           = new Cron(); 
//$cron->detectExpiredObjective();      // Überprüft einmal pro Tag ob Ziele abgelaufen sind.

if (checkCapabilities('dashboard:globalAdmin', $USER->role_id, false) OR checkCapabilities('dashboard:institutionalAdmin', $USER->role_id, false)){/* Shows additional information depending on user role */
    //$TEMPLATE->assign('cronjob', 'Es wurde zuletzt am '.$cron->check_cronjob().' geprüft, ob Ziele abgelaufen sind.<br>');
}

$box_bg = array('0' => 'bg-white','' => 'bg-white','x0' => 'bg-red','0x' => 'bg-white','1x' => 'bg-white','2x' => 'bg-white','3x' => 'bg-white',
                '00' => 'bg-red','10' => 'bg-red','20' => 'bg-red','30' => 'bg-red',
                'x1' => 'bg-green','1' => 'bg-green','01' => 'bg-green','11' => 'bg-green','21' => 'bg-green','31' => 'bg-green',
                'x2' => 'bg-orange','02' => 'bg-orange','2' => 'bg-orange','12' => 'bg-orange','22' => 'bg-orange','32' => 'bg-orange',
                'x3' => 'bg-white','3' => 'bg-white','03' => 'bg-white','13' => 'bg-white','23' => 'bg-white','33' => 'bg-white',
                );
$TEMPLATE->assign('box_bg',$box_bg);

$LOG->add($USER->id, 'view', $PAGE->url,  'Browser: '.$PAGE->browser. ' View: '.$PAGE->url); /* add log */