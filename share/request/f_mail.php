<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename f_mail.php
* @copyright 2016 Joachim Dieterich
* @author Joachim Dieterich
* @date 2016.05.29 08:17
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
$base_url   = dirname(__FILE__).'/../';
include($base_url.'setup.php');  //Läd Klassen, DB Zugriff und Funktionen
require ($base_url.'login-check.php');  
global $CFG, $USER, $COURSE;
$USER           = $_SESSION['USER'];
$func           = $_GET['func'];
$mail_id        = null;
$receiver_id    = null;
$group_id       = null;
$signature_id   = null;
$subject        = null;
$message_text   = null;
$error          = null;
$object         = file_get_contents("php://input");
$data           = json_decode($object, true);
if (is_array($data)) {
    foreach ($data as $key => $value){
        $$key = $value;
    }
}
            
if (isset($func)){
    switch ($func) {
        case 'gethelp': $subject           = $USER->firstname.' '.$USER->lastname.' braucht Deine Hilfe.';
                        $receiver_id       = filter_input(INPUT_GET, 'id',    FILTER_UNSAFE_RAW);
        case 'new':     checkCapabilities('mail:postMail', $USER->role_id, false, true);
                        $header            = 'Nachricht schreiben';
        case 'new-to':  checkCapabilities('mail:postMail', $USER->role_id, false, true);
                        $header            = 'Nachricht schreiben';
                        $receiver_id       = filter_input(INPUT_GET, 'id',    FILTER_UNSAFE_RAW);
        break;
        case 'reply':
        case 'forward': checkCapabilities('mail:postMail', $USER->role_id, false, true);
                        $header            = 'Nachricht beantworten';
                        $mail_obj          = new Mail();
                        $mail_obj->id      = filter_input(INPUT_GET, 'id',    FILTER_UNSAFE_RAW);
                        $mail_obj->loadMail($mail_obj->id, false);
                        $mail_id           = $mail_obj->id;
                        if ($func == 'reply'){
                           $receiver_id       = $mail_obj->sender_id;
                        }
                        $subject           = 'Re: '.$mail_obj->subject;
                        $message_text      = '<br><blockquote>Am '.$mail_obj->creation_time.' schrieb '.$USER->resolveUserId($mail_obj->sender_id).':'.$mail_obj->message;
        break;
        
        default: break;
    }
}

/* if validation failed, get formdata from session*/
if (isset($_SESSION['FORM'])){
    if (is_object($_SESSION['FORM'])) {
        foreach ($_SESSION['FORM'] as $key => $value){
            $$key = $value;
        }
    }
}

$content = '<form id="form_mail"  class="form-horizontal" role="form" method="post" action="../share/processors/fp_mail.php">
            <input type="hidden" name="mail_id" id="mail_id" value="'.$mail_id.'"/>
            <input type="hidden" name="func" id="func" value="'.$func.'"/>';
$content .= '<div class="nav-tabs-custom">';
$content .= '<ul class="nav nav-tabs">
          <li id="nav_tab_users" class="active"><a href="#tab_users" data-toggle="tab" aria-expanded="false" onclick="toggle([\'tab_users\', \'add_person_btn\'], [\'tab_groups\', \'add_group_btn\']);">Personen</a></li>
          <li id="nav_tab_groups" class=""><a href="#tab_groups" data-toggle="tab" aria-expanded="true" onclick="toggle([\'tab_groups\',  \'add_group_btn\'], [\'tab_users\', \'add_person_btn\']);">Gruppen</a></li>';    
$content .='</ul>
          <div class="tab-content">
            <div id="tab_users" class="tab-pane active">
            '. Form::input_select('receiver_id', 'Empfänger', $USER->getGroupMembers(), 'firstname, lastname', 'id', $receiver_id , $error) .'
            <input id="add_person" name="add_person" type="submit" class="hidden"/></div><!-- /.tab-pane -->';
$content .= '<div id="tab_groups" class="tab-pane">';
$groups = new Group();
$content .= Form::input_select('group_id', 'Empfänger', $groups->getGroups('group', $USER->id), 'group, institution', 'id', $group_id , $error);
$content .= '<input id="add_group" name="add_group" type="submit" class="hidden"/></div><!-- /.tab-pane -->';
$content .= Form::input_text('subject', 'Betreff', $subject, $error);
$content .= Form::input_textarea('message_text', 'Nachricht', $message_text, $error, 'Sehr geehrter Empfänger ...');
$s        = new Content();
$signature = $s->get('signature', $USER->id);
if (isset($signature[0]->content)){
    $content .= Form::input_select('signature_id', 'Signatur', $signature, 'title', 'id', $signature_id , $error);
}
$content .= '</div></div></form>';
$footer   = '<button id="add_person_btn" type="submit" class="btn btn-primary pull-right" onclick="$(\'#add_person\').click();" ><i class="fa fa-paper-plane margin-r-5"></i>'.$header.'</button> 
             <button id="add_group_btn"type="submit" class="btn btn-primary pull-right hidden" onclick="$(\'#add_group\').click();" ><i class="fa fa-paper-plane margin-r-5"></i>'.$header.'</button> ';
              
$html     = Form::modal(array('title'     => $header,
                              'content'   => $content, 
                              'f_content' => $footer));  

echo json_encode(array('html'=>$html));