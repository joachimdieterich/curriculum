<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename dashboard.php
* @copyright  2013 Joachim Dieterich  {@link http://www.joachimdieterich.de}
* @date 2013.03.08 13:26
* @license:  
*
* This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by  
* the Free Software Foundation; either version 3 of the License, or (at your option) any later version.
*                                                                       
* This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of        
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details:
*                                                                       
* http://www.gnu.org/copyleft/gpl.html      
*/
global $USER, $PAGE, $TEMPLATE, $LOG;
$TEMPLATE->assign('page_title', 'Startseite'); 

$acc_obj        = new EnablingObjective();
$TEMPLATE->assign('enabledObjectives', $acc_obj->getLastEnablingObjectives()); /* Load last accomplished Objectives */

$institution    = new Institution();
$TEMPLATE->assign('myInstitutions', $institution->getInstitutions('user', null, $USER->id)); /* Institution / Schulen laden */
$institution->id = $USER->institution_id;
$TEMPLATE->assign('bulletinBoard', $institution->getBulletinBoard());

$groups         = new Group(); 
$TEMPLATE->assign('myClasses', $groups->getGroups('user', $USER->id));

$TEMPLATE->assign('myClasses', $groups->getGroups('user', $USER->id));

$cron           = new Cron(); 
$cron->detectExpiredObjective();      // Überprüft einmal pro Tag ob Ziele abgelaufen sind.

if (checkCapabilities('dashboard:globalAdmin', $USER->role_id, false) OR checkCapabilities('dashboard:institutionalAdmin', $USER->role_id, false)){/* Shows additional information depending on user role */
    $TEMPLATE->assign('cronjob', 'Es wurde zuletzt am '.$cron->check_cronjob().' geprüft, ob Ziele abgelaufen sind.<br>');
}

if (checkCapabilities('menu:readMessages', $USER->role_id, false)){
    $update = new Mail();
    
    //$update->updateDB(); --> updates old db to 9.1 -> checkboxes under solutions
    
    $mail = new Mailbox();
    
    $mail->loadNewMessages($USER->id);
    if (isset($mail->inbox)){
        $TEMPLATE->assign('mails', $mail->inbox);
    }
}

$LOG->add($USER->id, 'view', $PAGE->url,  'Browser: '.$PAGE->browser. ' View: '.$PAGE->url); /* add log */