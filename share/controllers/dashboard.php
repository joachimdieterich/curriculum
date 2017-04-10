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
$TEMPLATE->assign('breadcrumb',  array('Startseite' => 'index.php?action=dashboard'));

$acc_obj         = new EnablingObjective();
$TEMPLATE->assign('enabledObjectives',  $acc_obj->getObjectiveStatusChanges()); /* Load last accomplished Objectives */

$institution     = new Institution();
$TEMPLATE->assign('myInstitutions',     $institution->getStatistic($USER->id)); /* Institution / Schulen laden */
$institution->id = $USER->institution_id;
$TEMPLATE->assign('bulletinBoard',      $institution->getBulletinBoard());

$groups          = new Group(); 
$TEMPLATE->assign('myClasses',          $groups->getGroups('user', $USER->id));

/*get upcoming events*/
$upcoming_events = new Event();
$TEMPLATE->assign('upcoming_events',    $upcoming_events->get('upcoming', $USER->id, '', 5));
$upcoming_tasks  = new Task();
$TEMPLATE->assign('upcoming_tasks',     $upcoming_tasks->get('upcoming', $USER->id));
/*get statistic*/
$TEMPLATE->assign('stat_users_online',  $USER->usersOnline($USER->institutions));  
$statistics = new Statistic();
$TEMPLATE->assign('stat_acc_all',       $statistics->getAccomplishedObjectives('all'));  
$TEMPLATE->assign('stat_acc_today',     $statistics->getAccomplishedObjectives('today'));  
$TEMPLATE->assign('stat_users_today',   $statistics->getUsersOnline('today'));  
$TEMPLATE->assign('stat_user_all',      $statistics->getAccomplishedObjectives('user_all'));  

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

$box_bg = array('0' => 'white','' => 'white','x0' => 'red','0x' => 'white','1x' => 'white','2x' => 'white','3x' => 'white',
                '00' => 'red','10' => 'red','20' => 'red','30' => 'red',
                'x1' => 'green','1' => 'green','01' => 'green','11' => 'green','21' => 'green','31' => 'green',
                'x2' => 'orange','02' => 'orange','2' => 'orange','12' => 'orange','22' => 'orange','32' => 'orange',
                'x3' => 'white','3' => 'white','03' => 'white','13' => 'white','23' => 'white','33' => 'white',
                );
$TEMPLATE->assign('box_bg',$box_bg);

$accordion = array(array("header" => "Accordion 01", "body" => "Text 1"),
                   array("header" => "Accordion 02", "body" => "Text 2"),
                   array("header" => "Accordion 03", "body" => "Text 3")
                  );
$TEMPLATE->assign('accordion',  $accordion);

$LOG->add($USER->id, 'view', $PAGE->url,  'Browser: '.$PAGE->browser); /* add log */