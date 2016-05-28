<?php
/**
 * Render class
 * 
 * @abstract This file is part of curriculum - http://www.joachimdieterich.de
 * @package core
 * @filename render.class.php
 * @copyright 2015 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2015.11.28 18:05
 * @license 
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by  
 * the Free Software Foundation; either version 3 of the License, or (at your option) any later version.                                   
 *                                                                       
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of        
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details:                          
 *                                                                       
 * http://www.gnu.org/copyleft/gpl.html      
 */
class Render {
   
    public static function accomplish($string, $student, $teacher){
        global $s, $t;
        $s = $student;
        $t = $teacher;
       
        return preg_replace_callback('/<accomplish id="(\d+)"><\/accomplish>/i', 
            function($r){ 
                global $s, $t; 
                $ena = new EnablingObjective(); 
                return Render::accCheckboxes($r[1], $s, $t);
            }, $string);     
    }
    
     public static function accCheckboxes($id, $student, $teacher){
        global $USER;
        if ($USER->id != $student OR $student == $teacher){ // 2. Bedingung bewirkt, dass als Lehrer eigene Einreichungen bewerten kann --> für Demonstration der Plattform wichtig
            $ena       = new EnablingObjective();
            $ena->id   = $id;
            $ena->getObjectives('enabling_objective_status', $student); // get status of objective
            $red       = 'checkredbtn';
            $green     = 'checkgreenbtn';
            $orange    = 'checkorangebtn';
            $white     = 'checkwhitebtn';
            switch ($ena->accomplished_status_id) {
                case 0: $red    = 'checkactiveredbtn';
                    break;
                case 1: $green  = 'checkactivegreenbtn';
                    break;
                case 2: $orange = 'checkactiveorangebtn';
                    break;
                case 3: $white  = 'checkactivewhitebtn';
                    break;

                default:
                    break;
            }
            return '<input id="'.$id.'_green" class="space-left '.$green.' pointer_hand" type="button" name="setAccStatus1" onclick="setAccomplishedObjectivesBySolution('.$teacher.', '.$student.', '.$id.', 1)">'
            . '     <input id="'.$id.'_orange" class="space-left '.$orange.' pointer_hand" type="button" name="setAccStatus2" onclick="setAccomplishedObjectivesBySolution('.$teacher.', '.$student.', '.$id.', 2)">'
            . '     <input id="'.$id.'_white" class="space-left '.$white.' pointer_hand" type="button" name="setAccStatus3" onclick="setAccomplishedObjectivesBySolution('.$teacher.', '.$student.', '.$id.', 3)">'
            . '     <input id="'.$id.'_red" class="space-left '.$red.' pointer_hand" type="button" name="setAccStatus0" onclick="setAccomplishedObjectivesBySolution('.$teacher.', '.$student.', '.$id.', 0)">';
        }
    }
    
    public static function link_old($string, $context){
        global $c;
        $c = $context;
        return preg_replace_callback('/<link id="(\d+)"><\/link>/i', 
            function($r){
                global $c;
                $file = new File(); 
                
                return $file->renderFile($r[1],$c);
            }, $string);
    }
    public static function link($string, $context){
        global $c, $thumb_list;
        $c = $context;
         preg_match_all('/<link id="(\d+)"><\/link>/i', $string, $hits, PREG_SET_ORDER); 
         //var_dump($hits);
         $list = array();
         foreach ($hits as $h){
             $list[] = $h[1];
         }
         return $list;
            /*function($r){
                global $c, $thumb_list;
                $file = new File(); 
                $file->load($context);
                return $file->renderFile($r[1],$c);
            }, );*/
    }
    
    public static function thumb($file_list){
        global $CFG;
        $file = new File();
        $html = '';
        foreach ($file_list as $f) {
            //var_dump($f);
            $file->id = $f;
            $file->load();
            /* check if img*/ 
            switch ($file->type) {
                case '.pdf': 
                case '.bmp':    
                case '.gif':       
                case '.png':    
                case '.svg':    
                case '.jpeg':    
                case '.jpg':    if ($file->getThumb() == false){ $url = $file->getFileUrl(); } else { $url = $file->getThumb();}
                                $html .= '<li>
                                    <span class="mailbox-attachment-icon has-img"><img src="'.$url.'" style="min-width:100%;" alt="Attachment"></span>
                                    <div class="mailbox-attachment-info">
                                      <a href="#" class="mailbox-attachment-name" style="word-wrap: break-word;"><i class="fa fa-paperclip"></i> '.$file->filename.'</a>
                                      <span class="mailbox-attachment-size">
                                        '.$file->getHumanFileSize().'
                                        <a href="'.$file->getFileUrl().'" class="btn btn-default btn-xs pull-right"><i class="fa fa-cloud-download"></i></a>
                                      </span>
                                    </div>
                                </li>';
                    break;

                default:        $html .= '<li>
                                    <span class="mailbox-attachment-icon"><i class="'.resolveFileType($file->type).'"></i></span>
                                    <div class="mailbox-attachment-info">
                                      <a href="#" class="mailbox-attachment-name" style="word-wrap: break-word;"><i class="fa fa-paperclip"></i> '.$file->filename.'</a>
                                      <span class="mailbox-attachment-size">
                                        '.$file->getHumanFileSize().'
                                        <a href="'.$file->getFileUrl().'" class="btn btn-default btn-xs pull-right"><i class="fa fa-cloud-download"></i></a>
                                      </span>
                                    </div>
                                </li>';
                    break;
            }
        }
        
        return $html;
    }
    
    public static function mail_old ($mail, $box = null){
        global $CFG;
        
        $sender         = new User();        
        $sender->id     = $mail->sender_id;
        if ($sender->exist()){                      //if User was deleted --> false + set first/lastname to "Gelöschter User
            $sender->load('id', $mail->sender_id, false);
        } 
        
        $receiver       = new User();
        $receiver->id   = $mail->receiver_id;
        if ($receiver->exist()){
            $receiver->load('id', $mail->receiver_id, false);
        } 
        echo '<div class="border-box mail">
              <div class="floatleft"><p class="mailheader"><strong>'.$sender->firstname.' '.$sender->lastname.' ('.$sender->username.')</strong>
              <p class="mailheader">An: '.$receiver->firstname.' '.$receiver->lastname.' ('.$receiver->username.')</p>
              <p class="mailheader">'.$mail->subject.'</div>
              <div class="floatright">'.$mail->creation_time.'<img class="floatright" style="height:60px;" src="'.$CFG->access_file.$sender->avatar.'"/>';
        if ($box != null){    
            echo ' <a class="deletebtn floatright" type="button" name="delete" onclick="del(\'message\', '. $mail->id.', \''.$box.'\')"></a>';
        }
        echo '</div>'; 
        $mail->message = Render::link($mail->message, 'message');
        $mail->message = Render::accomplish($mail->message, $sender->id, $receiver->id);
        echo '<div style="margin-top:70px" class="space-top mail-correction"><span class="line-top"></span>'.$mail->message.'</div></div>';
    }
    public static function mail ($mail, $box = null){
        global $CFG;
        
        $sender         = new User();        
        $sender->id     = $mail->sender_id;
        if ($sender->exist()){                      //if User was deleted --> false + set first/lastname to "Gelöschter User
            $sender->load('id', $mail->sender_id, false);
        } 
        
        $receiver       = new User();
        $receiver->id   = $mail->receiver_id;
        if ($receiver->exist()){
            $receiver->load('id', $mail->receiver_id, false);
        } 
        $thumbs = Render::link($mail->message, 'message');
        $mail->message = Render::accomplish($mail->message, $sender->id, $receiver->id);
        echo '<div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Nachricht</h3>
                  <div class="box-tools pull-right" >
                    <div class="btn-group">
                      
                      <button class="btn btn-default btn-sm" data-toggle="tooltip" title="Reply"><i class="fa fa-reply"></i> Antworten</button>
                      <button class="btn btn-default btn-sm" data-toggle="tooltip" title="Forward"><i class="fa fa-share"></i> Weiterleiten</button>
                    </div><!-- /.btn-group -->
                    <button class="btn btn-default btn-sm" data-toggle="tooltip" title="Print"><i class="fa fa-print"></i> Drucken</button>
                    <div class="btn-group">
                        <button class="btn btn-default btn-sm" data-toggle="tooltip" title="Previous"><i class="fa fa-chevron-left"></i></button>
                        <button class="btn btn-default btn-sm" data-toggle="tooltip" title="Next"><i class="fa fa-chevron-right"></i></button>
                    </div><!-- /.btn-group -->
                    <button class="btn btn-default btn-sm" data-toggle="tooltip" title="Delete" onclick="del(\'message\', '. $mail->id.', \''.$box.'\')"><i class="fa fa-trash-o"></i> Löschen</button>
                  </div>
                
                </div><!-- /.box-header -->
                <div class="box-body no-padding">
                  <div class="mailbox-read-info">
                    <div class="pull-left image" style="margin-right:10px;">
                        <img src="'.$CFG->access_file.$sender->avatar.'" style="height:40px;" class="img-circle" alt="User Image">
                    </div>
                    <h3>'.$mail->subject.'</h3>
                    <h5>Von: '.$sender->firstname.' '.$sender->lastname.' ('.$sender->username.')<span class="mailbox-read-time pull-right">'.$mail->creation_time.'</span></h5>
                  </div><!-- /.mailbox-read-info -->
                  
                  <div class="mailbox-read-message">
                    <p>'.$mail->message.'</p>
                  </div><!-- /.mailbox-read-message -->
                </div><!-- /.box-body -->
                <div class="box-footer">
                  <ul class="mailbox-attachments clearfix">';
                    echo Render::thumb($thumbs);      
          echo '  </ul>
                </div><!-- /.box-footer -->
                <!--div class="box-footer">
                  <div class="pull-right">
                    <button class="btn btn-default"><i class="fa fa-reply"></i> Reply</button>
                    <button class="btn btn-default"><i class="fa fa-share"></i> Forward</button>
                  </div>
                  <button class="btn btn-default"><i class="fa fa-trash-o"></i> Delete</button>
                  <button class="btn btn-default"><i class="fa fa-print"></i> Print</button>
                </div--><!-- /.box-footer -->
              </div><!-- /. box -->';
        
    }
    
    public static function popup($titel, $content, $url = false, $btn = 'OK') {
        $html= '<div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" ';
                    if ($btn == 'OK') {
                        $html.= 'onclick="location.href='."'".$url."'".'"';
                    } else {
                        $html.= 'onclick="closePopup();"';
                    }
         $html.= '><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">'.$titel.'</h4>
                  </div>
                  <div class="modal-body scroll">
                    <p>'.$content.'</p>
                  </div>
                  <div class="modal-footer">
                    <!--<button type="button" class="btn btn-outline pull-left" data-dismiss="modal" onclick="closePopup();">Schließen</button>-->';
                    if ($btn == 'OK') {
                        if (!$url) { $url = $_SESSION['PAGE']->url; }
                        $html .= '<button type="button" class="btn btn-outline" onclick="location.href='."'".$url."'".'">OK</button>';
                    }
          $html .= '  </div>
                </div>
              </div>';
          return $html;
    } 
    
    public static function quiz($question, $attempt = false, $correction = false){
        
        if (isset($question)){
            if (!$attempt){
                echo '<p class="space-left">Bitte wähle die richtige Antwort aus.</p><br>'; 
            }
            
            echo '<form id="ajax_quiz_form" action="" name="addQuestion" method="post">';
            
            foreach ($question as $value) {
                echo '<h3>'.$value->question.'</h3><br>';
                switch ($value->type) {
                    case 0: foreach ($value->answers as $a) {
                                if ($attempt){
                                    echo '<div class="space-left';
                                    if ($attempt[$value->id] == 1 AND $a->correct == 1){ echo ' right-block ';}
                                    if ($attempt[$value->id] == 1 AND $a->correct == 0){ echo ' wrong-block ';}
                                    if ($attempt[$value->id] == 0 AND $a->correct == 1){ echo ' right-box ';}
                                    echo '"><input style="width:40px;" type="radio" name="'.$value->id.'" ';
                                    if ($attempt[$value->id] == 1) { echo 'checked="checked"';}
                                    echo 'value="1">Wahr';
                                
                                    if ($attempt[$value->id] == 1 AND $a->correct == 1){
                                        echo '<div class="floatright">Richtig</div>';
                                    }  else if ($attempt[$value->id] == 1 AND $a->correct == 0){
                                        echo '<div class="floatright">Falsch</div>';
                                    }  
                                } else {
                                    echo '<div class="space-left"><input style="width:40px;" type="radio" name="'.$value->id.'" value="1">Richtig';
                                }
                                echo '</div>';
                                if ($attempt){
                                    echo '<div class="space-left ';
                                    if ($attempt[$value->id] == 0 AND $a->correct == 0){ echo ' right-block ';}
                                    if ($attempt[$value->id] == 0 AND $a->correct == 1){ echo ' wrong-block ';}
                                    if ($attempt[$value->id] == 1 AND $a->correct == 0){ echo ' right-box ';}
                                    echo '"><input style="width:40px;" type="radio" name="'.$value->id.'" ';
                                    if ($attempt[$value->id] == 0) { echo 'checked="checked"';}
                                    echo 'value="0">Falsch';
                                    if ($attempt[$value->id] == 0 AND $a->correct == 0){
                                        echo '<div class="floatright">Richtig</div>';
                                    }   else if ($attempt[$value->id] == 0 AND $a->correct == 1){
                                        echo '<div class="floatright">Falsch</div>';
                                    }    
                                } else {
                                    echo '<div class="space-left"><input style="width:40px;" type="radio" name="'.$value->id.'" value="0">Falsch';
                                }
                                echo '</div>';
                            }
                        break;
                    case 1: foreach ($value->answers as $a) {
                                if ($attempt){
                                    echo '<div class="space-left ';
                                    if ($attempt[$value->id] == $a->id AND $a->correct == 1){ echo ' right-block '; }
                                    if ($attempt[$value->id] == $a->id AND $a->correct == 0){ echo ' wrong-block '; }
                                    if ($attempt[$value->id] != $a->id AND $a->correct == 1){ echo ' right-box '; }
                                    echo '"><input style="width:40px;" type="radio" name="'.$value->id.'" value="'.$a->id.'" ';
                                    if ($attempt[$value->id] == $a->id) { echo 'checked="checked"';}
                                    echo '>'.$a->answer;
                                    if ($attempt[$value->id] == $a->id AND $a->correct == 1){
                                        echo '<div class="floatright">Richtig</div>';
                                    }   else if ($attempt[$value->id] == $a->id AND $a->correct != 1){
                                        echo '<div class="floatright">Falsch</div>';
                                    } 
                                } else {
                                    echo '<div class="space-left"><input style="width:40px;" type="radio" name="'.$value->id.'" value="'.$a->id.'">'.$a->answer;
                                }
                                echo'</div>';
                            }
                        break;
                    case 2: foreach ($value->answers as $a) {
                                if ($attempt){
                                    echo '<div class="space-left ';
                                    if ($attempt[$value->id] == $a->answer){ echo ' right-block '; }
                                    if ($attempt[$value->id] != $a->answer ){ echo ' wrong-block '; }
                                    echo '"><input style="width:40px;" type="text" name="'.$value->id.'" value="'.$attempt[$value->id].'" >';
                                    if ($attempt[$value->id] == $a->answer ){
                                        echo '<div class="floatright">Richtig</div>';
                                    }   else if ($attempt[$value->id] != $a->answer ){
                                        echo '<div class="floatright">Richtige Antwort ist<strong> '.$a->answer.'</strong></div>';
                                    } 
                                } else {
                                    echo '<div class="space-left"><input style="width:40px;" type="text" name="'.$value->id.'" onkeydown="if (event.keyCode == 13) {event.preventDefault();}">';
                                }
                                echo'</div>';
                            }
                        break;
                    case 3: echo '<div id="div2" class="floatright border-box" style="height:30px;width:90px;" ondrop="drop_answer(event, '.$value->id.')" ondragover="allowDrop(event)"></div>';
                            echo '<input style="width:40px;" type="hidden" name="'.$value->id.'" value="">';
                            foreach ($value->answers as $a) {
                                if ($attempt){
                                    echo '<div class="space-left ';
                                    if ($attempt[$value->id] == $a->answer){ echo ' right-block '; }
                                    if ($attempt[$value->id] != $a->answer ){ echo ' wrong-block '; }
                                    echo '"><input style="width:40px;" type="text" name="'.$value->id.'" value="'.$attempt[$value->id].'" >';
                                    if ($attempt[$value->id] == $a->answer ){
                                        echo '<div class="floatright">Richtig</div>';
                                    }   else if ($attempt[$value->id] != $a->answer ){
                                        echo '<div class="floatright">Richtige Antwort ist<strong> '.$a->answer.'</strong></div>';
                                    } 
                                } else {
                                    echo '<div class="space-left">';
                                    echo '<div id="div_'.$a->id.'" class="border-box" style="height:30px;width:90px;" ondrop="drop(event)" ondragover="allowDrop(event)">
                                    <img src="'.$a->answer.'" draggable="true" ondragstart="drag(event, '.$value->id.')" id="'.$a->id.'" width="88" height="31">
                                  </div>';
                                }
                                echo '</div>';
                            }
                            
                        break;

                    default:
                        break;
                }
                echo '<br>';
            } 
            if (!$attempt){
                echo '<div class="border-box" onclick="sendForm(\'ajax_quiz_form\', \'evaluateQuiz.php\')">Quiz beenden</div>';
            } else {
                echo '<input value="Fenster schließen" type="submit">';
            }
            echo '</form><div id="ajax_quiz_form_result"></div>';
        } else {
            echo '<p class="materialtxt"><p class="space-left">Keine Quiz vorhanden</p>';
        }
    }
    
    
    public static function filenail($files, $ID_Postfix, $i = false, $preview = false, $delete = false, $link = false){
        global $CFG;
        if(is_array($files)){
            $file = $files[$i];
        } else {
            $file = $files;
        }
        if ($link == true){
            $r = '<div class="filesingle filenail" id="row'.$ID_Postfix.''.$file->id.'" onclick="javascript:location.href=\''.$CFG->access_file_url.''.$file->context_path.''.$file->path.''.rawurlencode($file->filename).'\'" ';
        } else {
            $r = '<div class="filelist filenail" id="row'.$ID_Postfix.''.$file->id.'" onclick="checkfile(\''.$ID_Postfix.''.$file->id.'\')" ';
        }
        if ($preview == true){            
            $r .= 'onmouseover="previewFile(\''.$CFG->access_file_url.$file->context_path.''.$file->path.'\', ';
            $r .= '\''.rawurlencode($file->filename).'\', ';
            $r .= '\''.$ID_Postfix.'\', \'';
            if  ($file->title != '')        { $r .= $file->title;        }  $r .= '\', \'';
            if  ($file->description != '')  { $r .= $file->description;  }  $r .= '\', \'';  
            if  ($file->author != '')       { $r .= $file->author;       }  $r .= '\', \''; 
            $r .= $file->getLicense($file->license).'\')" onmouseout="exitpreviewFile(\''.$ID_Postfix.'\')">';
        }
        
        $r .= '<a id="href_a_'.$file->id.'" href="'.$CFG->access_file_url.''.$file->context_path.''.$file->path.''.rawurlencode($file->filename).'"  target="_blank">';
        if ($link == false){               
            $r .= '<div class="downloadbtn floatleft"></div>';
            
        } 
        $r .= '</a>';
        if ($delete == true){
            $r .= '<div class="deletebtn floatright" style="margin-right: -4px !important; " onclick="deleteFile(\''.$file->id.'\')"></div>';
        }
        
        $r .=   '<div class="'.ltrim ($file->type, '.').'_btn filelisticon" ></div>
                 <div id="href_'.$file->id.'" class="filelink">'.$file->filename.'</div>
                 <input class="invisible" type="checkbox" id="'.$ID_Postfix.''.$file->id.'" name="id'.$ID_Postfix.'[]" value='.$file->id.' />
                 </div>';
        return $r;
    }

    public static function courseBook($coursebook, $cur_id = null, $id='coursebook'){
        if (isset($cur_id)){
            $curriculum = new Curriculum();
            $curriculum->id = $cur_id; 
            $curriculum->load();
        }
        $r       = '<div style="overflow:hidden;"><div id="'.$id.'" style="overflow:auto;">';
        $r      .= '<ul  class="timeline" >';
        $p_date  = '';
        foreach ($coursebook as $cb) {
            if ($p_date != date("d.m.Y", strtotime($cb->creation_time))){ //only print time label if last artefact timestamp neq this timestamp
                $r      .= '<li class="time-label">
                                <span class="bg-red">
                                
                                    '.date("d.m.Y", strtotime($cb->creation_time)).'
                                </span>
                            </li>';
                $p_date = date("d.m.Y", strtotime($cb->creation_time));
            }
            $r      .= '<li>
                        <i class="fa fa-check bg-green"></i>
                        <div class="timeline-item">
                          <span class="time" onclick="formloader(\'coursebook\',\'edit\','.$cb->id.');"><i class="fa fa-edit"></i></span>    
                          <span class="time"><i class="fa fa-clock-o"></i> '.$cb->creation_time.'</span>
                          
                          <h3 class="timeline-header"><a href="#">';
                          if (isset($curriculum->curriculum)){
                          $r      .=     $curriculum->curriculum;
                          }  
            $r      .= '                </a> '.$cb->creator.'</h3>
                          <div class="timeline-body">
                              Eintrag
                              <h4>'.$cb->topic.'</h4> 
                             '.$cb->description.'
                          </div>
                          
                          <!--div class="timeline-footer"-->';
            $r      .=    Render::todoList($cb->task, 'coursebook', $cb->id);
            $r      .=    Render::absentListe($cb->absent_list, 'coursebook', $cb->id);
            /*              foreach ($cb->task as $tsk) {
            $r      .=    '<a class="btn btn-primary btn-xs" onclick="formloader(\'task\',\'edit\', '.$tsk->id.')">'.$tsk->task.'</a>';
                          }
            $r      .= '   <a class="btn btn-primary btn-xs" onclick="formloader(\'task\',\'coursebook\', '.$cb->id.')"><i class="fa fa-plus"></i> Aufgabe hinzufügen</a></div>*/
            $r      .= ' </div>
                      </li>';

        }
        $r      .= '    <li>
                            <i class="fa fa-clock-o bg-gray" onclick="formloader(\'coursebook\',\'new\');"></i>
                        </li>';
        
        $r      .=  '</ul><!-- timleline -->
                    </div></div>';
        return $r;
                
    }
    
    public static function todoList($task, $context, $reference_id){
        $r       = '<ul class="todo-list ui-sortable">';
                 foreach ($task as $tsk) {
        $r       .= ' <li>
                      <!--span class="handle ui-sortable-handle">
                        <i class="fa fa-ellipsis-v"></i>
                        <i class="fa fa-ellipsis-v"></i>
                      </span>
                      <input type="checkbox" value="" name=""-->
                      <span class="text">'.$tsk->task.'</span>
                      <small class="label label-primary pull-right"><i class="fa fa-clock-o"></i>'.$tsk->timeend.'</small>
                      <br><span class="text small">'.$tsk->description.'</span>
                      <div class="tools">
                        <i class="fa fa-edit" onclick="formloader(\'task\',\'edit\', '.$tsk->id.')"></i>
                        <i class="fa fa-trash-o" onclick="del(\'task\', '.$tsk->id.')"></i>
                      </div>
                    </li>';
                 }
                 
                    
        $r       .= '<li><a class="btn btn-primary btn-xs" onclick="formloader(\'task\',\''.$context.'\', '.$reference_id.')"><i class="fa fa-plus"></i> Aufgabe hinzufügen</a></li> </ul>';
        return $r;
    }
    
    public static function absentListe($absent, $context, $reference_id){
        global $CFG;
        $r      = '<div style="padding:10px;"><span>Abwesend<span><ul class="products-list product-list-in-box ui-sortable-handle">';
                  foreach ($absent as $ub) {
        $r     .= '<li class="item" >
                      <div class="product-img">
                        <img src="'.$CFG->access_id_url.$ub->user->avatar_id.'" alt="Product Image">
                      </div>
                      <div class="product-info">
                        <a href="javascript::;" class="product-title">'.$ub->user->firstname.' '.$ub->user->lastname.' ('.$ub->user->username.')'; 
                        switch ($ub->status) {
                            case 0: $r     .= '<span class="label label-danger pull-right"> unentschuldigt </span>';
                                break;
                            case 1: $r     .= '<span class="label label-success pull-right">'.$ub->done.'</span>';      
                                break;

                            default:
                                break;
                        }
                            
        $r     .= '     </a>
                        
                        <span class="product-description">'.$ub->reason.'</span>
                        <span class="pull-right"><i class="fa fa-edit" onclick="formloader(\'absent\',\'edit\', '.$ub->id.')"></i>
                        <i class="fa fa-trash-o" onclick="del(\'absent\', '.$ub->id.')"></i></span>
                      </div>
                     
                    </li><!-- /.item -->';
                  }
                    
        $r     .= '<li><a class="btn btn-primary btn-xs" onclick="formloader(\'absent\',\''.$context.'\', '.$reference_id.')"><i class="fa fa-plus"></i> Fehlende Personen erfassen</a></li> </ul></div>';
        return $r;
    }
}
