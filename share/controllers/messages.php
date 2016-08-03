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
global $PAGE, $USER, $TEMPLATE;
$TEMPLATE->assign('breadcrumb',  array('Nachrichten' => 'index.php?action=messages'));
$mailbox = new Mailbox();
$TEMPLATE->assign('timestamp', time());                                         //hack, damit bei wiederholten anklicken eines postfachs die funktion detect_reload() nicht ausgelöst wird
$TEMPLATE->assign('index', 0);                                                  // set default
    
if (isset($_GET['function'])){
    switch ($_GET['function']) {
        case 'showInbox':       $TEMPLATE->assign('showInbox', true); inbox($mailbox);
                                if (filter_input(INPUT_GET, 'id',    FILTER_VALIDATE_INT)){
                                    $TEMPLATE->assign('load_id', filter_input(INPUT_GET, 'id',    FILTER_VALIDATE_INT));
                                }
                                $TEMPLATE->assign('mailbox_func', 'showInbox'); //todo: use mailbox_func instead of if isset showInbox....
            break;
        case 'showOutbox':      $TEMPLATE->assign('showOutbox', true); outbox($mailbox);
                                $TEMPLATE->assign('mailbox_func', 'showOutbox');
            break;
        /*case 'shownewMessage':  if (strpos($PAGE->previous_url, 'shownewMessage') !== false) { //prevent reloading dialog when it was opend over $_GET parameter
                                    $_SESSION['FORM']              = new stdClass();
                                    $_SESSION['FORM']->form        = 'mail'; 
                                    $_SESSION['FORM']->func        = 'new'; 
                                    $_SESSION['FORM']->subject     = filter_input(INPUT_GET, 'subject',    FILTER_SANITIZE_STRING);
                                    $_SESSION['FORM']->receiver_id = filter_input(INPUT_GET, 'receiver_id',    FILTER_VALIDATE_INT);
                                }  */ 
                                
        default:  break;
    }
    if (filter_input(INPUT_GET, 'index',    FILTER_VALIDATE_INT)){
        $TEMPLATE->assign('index', filter_input(INPUT_GET, 'index',    FILTER_VALIDATE_INT));
    }
}

if ($_POST){
    if(isset($_POST['showInbox']))      { $TEMPLATE->assign('showInbox', true);     inbox($mailbox);}
    if(isset($_POST['showOutbox']))     { $TEMPLATE->assign('showOutbox', true);    outbox($mailbox);}
    if(isset($_POST['shownewMessage'])) { 
        $TEMPLATE->assign('shownewMessage',       true); 
    }
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
            if ($newMail->postMail()){ $PAGE->message[] = array('message' => 'Nachricht an erfolgreich gesendet.', 'icon' => 'fa-envelope-o text-success');  }
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
    $TEMPLATE->assign('outbox', $mailbox->outbox); 
    //setPaginator('outboxPaginator', $TEMPLATE, $mailbox->outbox, 'outbox', 'index.php?action=messages&function=showOutbox'); //set Paginator    
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
    $TEMPLATE->assign('inbox', $mailbox->inbox); 
    //setPaginator('inboxPaginator', $TEMPLATE, $mailbox->inbox, 'inbox', 'index.php?action=messages&function=showInbox'); //set Paginator    
}

/**
 * load users Deleted messages --> not used yet
 */
/*function deletbox($mailbox){
    global $USER, $TEMPLATE;
    $mailbox->loadDeletedMessages($USER->id);
    setPaginator('deleted_messagesPaginator', $TEMPLATE, $mailbox->deleted_messages, 'deleted', 'index.php?action=messages&function=showDeleted'); //set Paginator    
}*/