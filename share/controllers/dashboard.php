<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename dashboard.php
* @copyright  2013 Joachim Dieterich  {@link http://www.joachimdieterich.de}
* @date 2013.03.08 13:26
* @license:  
*
* This program is free software; you can redistribute it and/or modify 
* it under the terms of the GNU General Public License as published by  
* the Free Software Foundation; either version 3 of the License, or     
* (at your option) any later version.                                   
*                                                                       
* This program is distributed in the hope that it will be useful,       
* but WITHOUT ANY WARRANTY; without even the implied warranty of        
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the         
* GNU General Public License for more details:                          
*                                                                       
* http://www.gnu.org/copyleft/gpl.html      
*/
global $USER, $PAGE, $TEMPLATE, $LOG;
  /** Load last accomplished Objectives */
  include ('./../lang/'.$USER->language.'/dashboard.php');                      //includes language pack
  
  
  
  $acc_objectives = new EnablingObjective();
  $TEMPLATE->assign('enabledObjectives', $acc_objectives->getLastEnablingObjectives()); 
  $institution = new Institution();
  $cronjob = new Cron(); 
  
  /** Assign Institution / Schulen laden */
  $TEMPLATE->assign('myInstitutions', $institution->getInstitutionsByUserID($USER->id));
  $groups = new Group(); 
  $TEMPLATE->assign('myClasses', $groups->getGroups('user', $USER->id));
    
  /** Shows additional information depending on user role */
  if (checkCapabilities('dashboard:globalAdmin', $USER->role_id, false)){
        /** Load new registered institutions */
        $institution = new Institution();
        $new_instituions =  $institution->getNewInsitutions();
        if($new_instituions){
            $PAGE->message[] = 'Es wurden '.$new_instituions.' neue Institution(en) registriert. Sie können diese unter "Freigabe" bestätigen';
        }  
        /**Load new registered users */
        $new_users = $USER->getNewUsers();
        if ($new_users){
            $PAGE->message[] = 'Es wurden '.$new_users.' neue Benutzer registriert. Sie können diese unter "Freigabe" bestätigen';
        }
        if ($cronjob->check_cronjob()){
            $TEMPLATE->assign('cronjob', 'Es wurde zuletzt am '.$cronjob->creation_time.' geprüft, ob Ziele abgelaufen sind.<br>');//Check last Cronjob execution
        }
  } else if (checkCapabilities('dashboard:institutionalAdmin', $USER->role_id, false)){
        /** Load new registered users */
        $new_users = $USER->getNewUsers('institution', $USER->institutions);
        if ($new_users){
            $PAGE->message[] = 'Es wurden '.$new_users.' neue Benutzer registriert. Sie können diese unter "Freigabe" bestätigen';                
        }
        if ($cronjob->check_cronjob()){
            $TEMPLATE->assign('cronjob', 'Es wurde zuletzt am '.$cronjob->creation_time.' geprüft, ob Ziele abgelaufen sind.<br>');//Check last Cronjob execution
        }  
  }
 
  
/** assign messages */
if (isset($PAGE->message)){
    $TEMPLATE->assign('page_message', $PAGE->message);
}

/** add log */
$LOG->add($USER->id, 'view', $PAGE->url,  'dashboard'); 
?>