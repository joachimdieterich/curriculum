<?php
/** 
 * Nachrichtensystem 
 * This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package core
 * @filename messages.php
 * @copyright 2013 Joachim Dieterich
 * @author Joachim Dieterich
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
global $PAGE, $USER, $TEMPLATE;
$TEMPLATE->assign('breadcrumb',  array('Nachrichten' => 'index.php?action=messages'));
$mailbox = new Mailbox();
$TEMPLATE->assign('timestamp', time());  //hack, damit bei wiederholten anklicken eines postfachs die funktion detect_reload() nicth ausgelöst wird

if (isset($_GET['function'])){
    switch ($_GET['function']) {
        case 'showInbox':       $TEMPLATE->assign('showInbox', true); inbox($mailbox);
                                if (filter_input(INPUT_GET, 'id',    FILTER_VALIDATE_INT)){
                                    $TEMPLATE->assign('load_id', filter_input(INPUT_GET, 'id',    FILTER_VALIDATE_INT));
                                }
            break;
        case 'showOutbox':      $TEMPLATE->assign('showOutbox', true); outbox($mailbox);
            break;
        case 'shownewMessage':  $TEMPLATE->assign('shownewMessage', true);
                                if(isset($_GET['help_request'])) {
                                     $TEMPLATE->assign('receiver_id', filter_input(INPUT_GET, 'receiver_id',    FILTER_VALIDATE_INT));
                                     $enabling_objective            = new EnablingObjective();
                                     $enabling_objective->id        = filter_input(INPUT_GET, 'subject',        FILTER_VALIDATE_INT);
                                     $enabling_objective->load();
                                     $TEMPLATE->assign('subject', 'Benutzer '.$USER->username.' braucht Hilfe beim erreichen des Ziels: '.$enabling_objective->enabling_objective);
                                }
                                if(isset($_GET['answer'])) {
                                     $TEMPLATE->assign('receiver_id', filter_input(INPUT_GET, 'receiver_id',    FILTER_VALIDATE_INT));
                                     $TEMPLATE->assign('subject',     filter_input(INPUT_GET, 'subject',        FILTER_UNSAFE_RAW));
                                } 
                                
                                
            break;
        default:  break;
    }
}

if ($_POST){
    if(isset($_POST['showInbox']))      { $TEMPLATE->assign('showInbox', true);     inbox($mailbox);}
    if(isset($_POST['showOutbox']))     { $TEMPLATE->assign('showOutbox', true);    outbox($mailbox);}
    if(isset($_POST['shownewMessage'])) { $TEMPLATE->assign('shownewMessage',       true); }
    if(isset($_POST['sendMessage'])) {
        $newMail = new Mail();
        $newMail->receiver_id   = filter_input(INPUT_POST, 'receiver_id',           FILTER_VALIDATE_INT);
        $newMail->sender_id     = $USER->id;
        $newMail->subject       = filter_input(INPUT_POST, 'subject',               FILTER_SANITIZE_STRING);
        $newMail->message       = filter_input(INPUT_POST, 'message_text',          FILTER_UNSAFE_RAW);
        
       /**
        * Validation 
        */
        $gump   = new Gump();
        $_POST  = $gump->sanitize($_POST);           //sanitize $_POST
        $gump->validation_rules(array(
        'subject'       => 'required',
        'message_text'  => 'required'
        ));
        $validated_data = $gump->run($_POST);

        if($validated_data === false) {
            assign_to_template($newMail);
            $TEMPLATE->assign('v_error',        $gump->get_readable_errors());     
            $TEMPLATE->assign('shownewMessage', true); 
        } else {
            if ($newMail->postMail()){ $PAGE->message[] = 'Nachricht an erfolgreich gesendet.'; }
        }                 
    }           
} 

/*******************************************************************************
 * End POST / GET
 */

$TEMPLATE->assign('page_title', 'Nachrichten');    

/**
 * Load userlist 
 */
$TEMPLATE->assign('class_members', $USER->getGroupMembers()); //'class:members' ist eigentlich überflüssig --> es wird default benutzt

/**
 * Gesendete Nachrichten laden
 * @global object $USER
 * @global object $TEMPLATE
 * @param object $mailbox
 */
function outbox($mailbox){
    global $USER, $TEMPLATE;
    $mailbox->loadOutbox($USER->id);
    setPaginator('outboxPaginator', $TEMPLATE, $mailbox->outbox, 'outbox', 'index.php?action=messages&function=showOutbox'); //set Paginator    
}

/**
 * Eingehende Nachrichten laden
 * @global object $USER
 * @global object $TEMPLATE
 * @param object $mailbox
 */
function inbox($mailbox){
    global $USER, $TEMPLATE;
    $mailbox->loadInbox($USER->id);
    setPaginator('inboxPaginator', $TEMPLATE, $mailbox->inbox, 'inbox', 'index.php?action=messages&function=showInbox'); //set Paginator    
}

/**
 * load users Deleted messages --> not used yet
 */
/*function deletbox($mailbox){
    global $USER, $TEMPLATE;
    $mailbox->loadDeletedMessages($USER->id);
    setPaginator('deleted_messagesPaginator', $TEMPLATE, $mailbox->deleted_messages, 'deleted', 'index.php?action=messages&function=showDeleted'); //set Paginator    
}*/