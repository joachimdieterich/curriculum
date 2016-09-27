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
            $content .= '<p><strong>'.$cb->curriculum.'</strong><div> Eintrag von '.$cb->creator.' am '.$cb->creation_time.'<br><h4>'.$cb->topic.'</h4> 
                                 '.$cb->description.'</div></p>'.Printer::task(array('task' => $cb->task));
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
            $content .= '<li><span class="text"><strong> '.$tsk->task.'</strong></span><br>
                      <small class="text small">Abgabe am '.$tsk->timeend.'</small>
                      <br><span class="text small">'.$tsk->description.'</span></li>';
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
            $content .= '<li><span class="text"><strong> '.$ub->user->firstname.' '.$ub->user->lastname.' ('.$ub->user->username.')</strong></span><br>';
                switch ($ub->status) {
                            case 0:  $content .= '<small class="text small">unentschuldigt</small>';
                                break;
                            case 1:  $content .= '<small class="text small">entschuldigt am '.$ub->done.'</small>';      
                                break;

                            default:
                                break;
                        }
             $content .= '<br><span class="text small">'.$ub->reason.'</span></li>';
        }
        $content .= '</ul>';
        return $content;
    }
    
}