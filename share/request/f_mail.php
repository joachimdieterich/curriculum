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
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by  
 * the Free Software Foundation; either version 3 of the License, or (at your option) any later version.
 *                                                                       
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of        
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details:
 *                                                                       
 * http://www.gnu.org/copyleft/gpl.html      
 */
$base_url   = dirname(__FILE__).'/../';
include($base_url.'setup.php');  //Läd Klassen, DB Zugriff und Funktionen
require ($base_url.'login-check.php');  
global $CFG, $USER, $COURSE;
$USER           = $_SESSION['USER'];
$func           = $_GET['func'];
$mail_id        = null;
$receiver_id        = null;
$group_id       = null;
$subject        = null;
$message_text   = null;

$error          =   null;
$object = file_get_contents("php://input");
$data = json_decode($object, true);
if (is_array($data)) {
    foreach ($data as $key => $value){
        $$key = $value;
    }
}
            
if (isset($func)){
    switch ($func) {
        case 'new':      checkCapabilities('mail:postMail', $USER->role_id);
                         $header            = 'Nachricht schreiben';
                         $add               = true;
        break;
        case 'reply':
        case 'forward':  checkCapabilities('mail:postMail', $USER->role_id);
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
                         $add               = true;
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

$html ='<div class="modal-dialog" style="overflow-y: initial !important;">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closePopup()"><span aria-hidden="true">×</span></button>
              <h4 class="modal-title">'.$header.'</h4>
            </div>
            <div class="modal-body" style="max-height: 500px; overflow-y: auto;">';
$html .='<form id="form_mail"  class="form-horizontal" role="form" method="post" action="../share/processors/fp_mail.php"';

if (isset($currentUrlId)){ $html .= $currentUrlId; }
$html .= '"><input type="hidden" name="mail_id" id="mail_id" value="'.$mail_id.'"/>
            <input type="hidden" name="func" id="func" value="'.$func.'"/>';
$html .= '<div class="nav-tabs-custom">';
$html .= '<ul class="nav nav-tabs">
          <li class="active"><a href="#persons" data-toggle="tab" aria-expanded="false" onclick="toggle([\'persons\', \'add_person\'], [\'groups\', \'add_group\']);">Personen</a></li>
          <li class=""><a href="#groups" data-toggle="tab" aria-expanded="true" onclick="toggle([\'groups\', \'add_group\'], [\'persons\', \'add_person\']);">Gruppen</a></li>';    
$html .='</ul>
          <div class="tab-content">
            <div class="tab-pane active" id="persons">
            '. Form::input_select('receiver_id', 'Empfänger', $USER->getGroupMembers(), 'firstname, lastname', 'id', $receiver_id , $error) .'
            </div><!-- /.tab-pane -->';
$html .= '<div class="tab-pane" id="groups">';
$groups = new Group();
$html .= Form::input_select('group_id', 'Empfänger', $groups->getGroups('group', $USER->id), 'group, institution', 'id', $group_id , $error);
$html .= '</div><!-- /.tab-pane -->';
$html .= Form::input_text('subject', 'Betreff', $subject, $error);
$html .= Form::input_textarea('message_text', 'Nachricht', $message_text, $error, 'Sehr geehrter Empfänger ...');
$html .= '</div><!-- /.modal-body -->
          <div class="modal-footer">   
          <button id="add_person" name="add_person" type="submit" class="btn btn-primary glyphicon glyphicon-ok pull-right" > '.$header.'</button> 
          <button id="add_group" name="add_group" type="submit" class="btn btn-primary glyphicon glyphicon-ok pull-right hidden" > '.$header.'</button> ';
              
$html .=  '</div><!-- /.tab-content -->
            </div></form></div><!-- /.modal-content -->
           </div><!-- /.modal-dialog -->';

echo json_encode(array('html'=>$html));