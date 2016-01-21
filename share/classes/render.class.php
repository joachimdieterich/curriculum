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
    
    public static function link($string, $context){
        global $c;
        $c = $context;
        return preg_replace_callback('/<link id="(\d+)"><\/link>/i', 
            function($r){
                global $c;
                $file = new File(); 
                ;
                return $file->renderFile($r[1],$c);
            }, $string);
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
    
    public static function popup($titel, $content, $url = false, $btn = 'OK') {
        echo '<div class="contentheader">'.$titel.'</div>
            <div id="popupcontent" style="max-height: 600px !important;">'
            .$content.'  
            </div>';
          if ($btn == 'OK') {
              if (!$url) { $url = $_SESSION['PAGE']->url; }
              echo '<p style="padding-left:10px;"><label></label><input type="submit" value="OK" onclick="location.href='."'".$url."'".'"></p>';
          }
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
                                    echo '<div class="space-left"><input style="width:40px;" type="text" name="'.$value->id.'">';
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
            $r .= $file->getLicence($file->licence).'\')" onmouseout="exitpreviewFile(\''.$ID_Postfix.'\')">';
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

}
