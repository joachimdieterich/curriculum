<?php
/**
* Printer class
* 
* @abstract This file is part of curriculum - http://www.joachimdieterich.de
* @package core
* @filename printer.class.php
* @copyright 2016 Joachim Dieterich
* @author Joachim Dieterich
* @date 2016.09.27 11:02
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
class Printer {
    
    public static function coursebook($params){
        global $USER;
        foreach($params as $key => $val) {
            $$key = $val;
        }
        $content = '';
        foreach ($coursebook as $cb) {
            $content .= element('print_coursebook', $cb);
            $content .= Printer::task(array('task' => $cb->task));
            if (checkCapabilities('absent:update', $USER->role_id, false)){
                $content .= Printer::absent(array('absent' => $cb->absent_list)).'<hr>';
            }
        }
        return $content;
        
    }
    
    public static function task($params){
        foreach($params as $key => $val) {
            $$key = $val;
        }
        $content = '<br><strong>Aufgaben</strong><ul>';
        foreach ($task as $tsk) {
            $content .= '<li>'.element('print_task', $tsk).'</li>';
        }
        $content .= '</ul>';
        return $content; 
    }
    
    public static function absent($params){
        foreach($params as $key => $val) {
            $$key = $val;
        }
        
        $content = '<br><strong>Abwesend</strong><ul>';
        foreach ($absent as $ub) {
            $content   .= '<li>'.element('print_absent',$ub).'</li>';
        }
        $content .= '</ul>';
        return $content;
    }
    
    public static function mail($params){
        global $CFG;
        foreach($params as $key => $val) {
            $$key = $val;
        }
        
        $content = '';
        foreach ($mail as $m) {
            $sender         = new User();        
            $sender->id     = $m->sender_id;
            if ($sender->exist()){                      //if User was deleted --> false + set first/lastname to "Gelöschter User
                $sender->load('id', $m->sender_id, false);
            } 

            $receiver       = new User();
            $receiver->id   = $m->receiver_id;
            if ($receiver->exist()){
                $receiver->load('id', $m->receiver_id, false);
            } 
            $thumbs = Render::link($m->message, 'message');
            $content .= '<div class="mailbox-read-info">
                    <div class="pull-left image" style="margin-right:10px;">
                        <img src="'.$CFG->access_file.$sender->avatar.'" style="height:40px;" class="img-circle" alt="User Image">
                    </div>
                    <h3>'.$m->subject.'</h3>
                    <h5>Von: '.$sender->firstname.' '.$sender->lastname.' ('.$sender->username.')<span class="mailbox-read-time pull-right">'.$m->creation_time.'</span></h5>
                  </div><!-- /.mailbox-read-info -->
                  
                  <div class="mailbox-read-message">
                    <p>'.$m->message.'</p>
                  </div><!-- /.mailbox-read-message -->
                
                  <ul class="mailbox-attachments clearfix">';
                    $content .= Render::thumb(array('file_list' => $thumbs)).' </ul>';
        }
        return $content; 
    }
    
}