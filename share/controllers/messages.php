<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package core
 * @filename messages.php
 * @copyright 2013 Joachim Dieterich
 * @author Joachim Dieterich
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
global $PAGE, $USER, $TEMPLATE;

$TEMPLATE->assign('timestamp', time());  //hack, damit bei wiederholten anklicken eines postfachs die funktion detect_reload() nicth ausgelöst wird

if ($_GET){
   if(isset($_GET['showInbox']))   { $TEMPLATE->assign('showInbox', true); } 
   if(isset($_GET['showOutbox']))  { $TEMPLATE->assign('showOutbox', true);} 
   if(isset($_GET['shownewMessage']))  { //for Help requests
       $TEMPLATE->assign('shownewMessage', true);
       if(isset($_GET['help_request'])) {
            $TEMPLATE->assign('receiver_id', $_GET['receiver_id']);
            $enabling_objective = new EnablingObjective();
            $enabling_objective->id = $_GET['subject'];
            $enabling_objective->load();
            $TEMPLATE->assign('subject', 'Benutzer '.$USER->username.' braucht Hilfe beim Lernziel: '.$enabling_objective->enabling_objective);
       }
   } 
}

if ($_POST){
    if(isset($_POST['showInbox']))      { $TEMPLATE->assign('showInbox', true); }
    if(isset($_POST['showOutbox']))     { $TEMPLATE->assign('showOutbox', true); }
    if(isset($_POST['shownewMessage'])) { $TEMPLATE->assign('shownewMessage', true); }
    if(isset($_POST['sendMessage'])) {
        $newMail = new Mail();
        $newMail->receiver_id   = $_POST['receiver_id'];
        $newMail->sender_id     = $USER->id;
        $newMail->subject       = $_POST['subject'];
        $newMail->message       = $_POST['message_text'];
        
       /**
        * Validation 
        */
        $gump = new Gump();
        $gump->validation_rules(array(
        'subject'     => 'required',
        'message_text'     => 'required'
        ));
        $validated_data = $gump->run($_POST);

        if($validated_data === false) {
            foreach($newMail as $key => $value){
            $TEMPLATE->assign($key, $value);
            } 
            $TEMPLATE->assign('v_error', $gump->get_readable_errors());     
            $TEMPLATE->assign('shownewMessage', true); 
        } else {
            if ($newMail->postMail()){
                $PAGE->message[] = 'Nachricht an erfolgreich gesendet.';     
            }
        }                 
    }           
} 

/*******************************************************************************
 * End POST / GET
 */

$mailbox = new Mailbox();

/**
 * load users Outbox 
 */
$mailbox->loadOutbox($USER->id);
setPaginator('outboxPaginator', $TEMPLATE, $mailbox->outbox, 'outbox', 'index.php?action=messages&showOutbox=showOutbox'); //set Paginator    

/**
 * load users Inbox 
 */
$mailbox->loadInbox($USER->id);
setPaginator('inboxPaginator', $TEMPLATE, $mailbox->inbox, 'inbox', 'index.php?action=messages&showInbox=showInbox'); //set Paginator    

/**
 * load users Deleted messages --> not used yet
 */
$mailbox->loadDeletedMessages($USER->id);
setPaginator('deleted_messagesPaginator', $TEMPLATE, $mailbox->deleted_messages, 'deleted', 'index.php?action=messages&showDeleted=showDeleted'); //set Paginator    

/**
 * Load userlist 
 */
if ($USER->getGroupMembers()){
    $TEMPLATE->assign('class_members', $USER->getGroupMembers());
} else {
    $TEMPLATE->assign('class_members', false);
}

/**
 * setContenttitle & log
 */
$TEMPLATE->assign('page_title', 'Nachrichten');    
$TEMPLATE->assign('page_message', $PAGE->message);
?>