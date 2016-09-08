<?php
/**
* Event
* 
* @abstract This file is part of curriculum - http://www.joachimdieterich.de
* @package core
* @filename event.class.php
* @copyright 2016 Joachim Dieterich
* @author Joachim Dieterich
* @date 2016.05.09 10:48
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
class Event {

    public $id;
    public $event; 
    public $description; 
    public $course_id;
    public $group_id;
    public $user_id;
    public $context_id;
    public $repeat_id;
    public $sequence;
    public $reminder_interval;
    public $timestart;
    public $timeend;
    public $timerange;
    public $status;
    public $creation_time; 
    public $creator_id; 
    /* event_subscriptions */
    public $url;
   
    public function __construct($id = null) {
        if ($id != null){
            $this->load('id', $id); 
        }
    }

    public function add(){
        global $USER;
        checkCapabilities('event:add', $USER->role_id);
        list ($this->timestart, $this->timeend) = explode(' - ',$this->timerange); // copy timestart and timeend from timerage
        $this->timestart = date('Y-m-d G:i:s', strtotime($this->timestart));
        $this->timeend   = date('Y-m-d G:i:s', strtotime($this->timeend));
        //var_dump($this);
        $db = DB::prepare('INSERT INTO event (event,description,course_id,group_id,user_id,context_id,repeat_id,sequence,reminder_interval,timestart,timeend,status,creation_time,creator_id) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
        return $db->execute(array($this->event, $this->description, $this->course_id, $this->group_id, $this->user_id, $this->context_id, $this->repeat_id, $this->sequence, $this->reminder_interval, $this->timestart, $this->timeend, $this->status, $this->creation_time, $USER->id));
    }
    
    public function update(){
        global $USER;
        checkCapabilities('event:update', $USER->role_id);
        list ($this->timestart, $this->timeend) = explode(' - ',$this->timerange); // copy timestart and timeend from timerage
        $this->timestart = date('Y-m-d G:i:s', strtotime($this->timestart));
        $this->timeend   = date('Y-m-d G:i:s', strtotime($this->timeend));
        $db = DB::prepare('UPDATE event SET event = ?,description = ?,course_id = ?,group_id = ?,user_id = ?,context_id = ?,repeat_id = ?,sequence = ?,reminder_interval = ?,timestart = ?,timeend = ?,status = ?,creation_time = ? WHERE id = ?');
        return $db->execute(array($this->event, $this->description, $this->course_id, $this->group_id, $this->user_id, $this->context_id, $this->repeat_id, $this->sequence, $this->reminder_interval, $this->timestart, $this->timeend, $this->status, $this->creation_time, $this->id));
    }
    
    public function delete(){
        global $USER;
        checkCapabilities('event:delete', $USER->role_id);
        $db = DB::prepare('DELETE FROM event WHERE id = ?');
        return $db->execute(array($this->id));
    } 
    
    public function load($dependency = 'id', $value = null){
        if (isset($value)){ $v = $value; } else { $v = $this->id; }
        $db = DB::prepare('SELECT * FROM event WHERE '.$dependency.' = ?');
        $db->execute(array($v));
        $result = $db->fetchObject();
        if ($result){
            $this->id                = $result->id;
            $this->event             = $result->event;
            $this->description       = $result->description;
            $this->course_id         = $result->course_id;
            $this->group_id          = $result->group_id;
            $this->user_id           = $result->user_id;
            $this->context_id        = $result->context_id;
            $this->repeat_id         = $result->repeat_id;
            $this->sequence          = $result->sequence;
            $this->reminder_interval = $result->reminder_interval;
            $this->timestart         = $result->timestart;
            $this->timeend           = $result->timeend;
            $this->timerange         = date('d.m.Y G:i', strtotime($this->timestart)) .' - '. date('d.m.Y G:i', strtotime($result->timeend));
            $this->status            = $result->status;
            $this->creation_time     = $result->creation_time;
            $this->creator_id        = $result->creator_id;
            
            return true;
        } else { 
            return false; 
        }
    }
    
    /**
     * Get all availible Grades of current institution
     * @return array of Grade objects 
     */
    public function get($dependency = 'course', $id = null, $paginator = '', $limit = 5){
        global $USER;
        if ($id == null){
            $id = $this->id;
        }
        $order_param = orderPaginator($paginator, array('event'         => 'ev',
                                                        'description'   => 'ev')); 
        
        switch ($dependency) {
            case 'course':  $db = DB::prepare('SELECT ev.*
                                                    FROM event AS ev
                                                    WHERE ev.course_id = ? '.$order_param );
                            $db->execute(array($id));
                break;
            case 'user':    $db = DB::prepare('SELECT ev.*
                                                    FROM event AS ev
                                                    WHERE ev.creator_id = ? '.$order_param );
                            $db->execute(array($id));
                break;
            case 'upcoming':$db = DB::prepare('SELECT ev.*
                                                    FROM event AS ev
                                                    WHERE ev.creator_id = ? AND timestart >= NOW() ORDER BY timestart ASC LIMIT ?'.$order_param );
                            $db->execute(array($id, $limit));
                break;

            default:
                break;
        }
        
        $entrys = array();                      //Array of entrys
        while($result = $db->fetchObject()) { 
                $this->id                = $result->id;
                $this->event             = $result->event;
                $this->description       = $result->description;
                $this->course_id         = $result->course_id;
                $this->group_id          = $result->group_id;
                $this->user_id           = $result->user_id;
                $this->context_id        = $result->context_id;
                $this->repeat_id         = $result->repeat_id;
                $this->sequence          = $result->sequence;
                $this->reminder_interval = $result->reminder_interval;
                $this->timestart         = $result->timestart;
                $this->timeend           = $result->timeend;
                $this->status            = $result->status;
                $this->creation_time     = $result->creation_time;
                $this->creator_id        = $result->creator_id;
                $this->summary           = $this->event.', '.$this->timestart.' - '.$this->timeend;
                $entrys[]                = clone $this;        //it has to be clone, to get the object and not the reference
        } 
        
        return $entrys;
    }
    
    /**
    * function used during the install process to set up creator id to new admin
    * @return boolean
    */
    public function dedicate(){ // only use during install
        $db = DB::prepare('UPDATE event SET  creator_id = ?');        
        return $db->execute(array($this->creator_id));
    }
}