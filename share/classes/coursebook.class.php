<?php
/**
 * CourseBook
 * 
 * @abstract This file is part of curriculum - http://www.joachimdieterich.de
 * @package core
 * @filename coursebook.class.php
 * @copyright 2016 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2016.05.07 10:48
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
class CourseBook {

    public $id;
    public $topic; 
    public $description; 
    public $creation_time; 
    public $creator_id; 
    public $creator; 
    public $course_id;
    public $event_id;
    //public $event;
    public $timestart;
    public $timeend;
    public $timerange;
    
    /* user_list */
    public $teacher_list;
    public $present_list;
    public $absent_list;
    
    /* task */
    public $task; 
   
    public function add(){
        global $USER;
        checkCapabilities('coursebook:add', $USER->role_id);
        list ($this->timestart, $this->timeend) = explode(' - ',$this->timerange); // copy timestart and timeend from timerage
        $this->timestart = date('Y-m-d G:i:s', strtotime($this->timestart));
        $this->timeend   = date('Y-m-d G:i:s', strtotime($this->timeend));
        $db = DB::prepare('INSERT INTO course_book (topic,description,event_id,timestart,timeend,course_id,creator_id) VALUES (?,?,?,?,?,?,?)');
        return $db->execute(array($this->topic, $this->description, $this->event_id, $this->timestart, $this->timeend, $this->course_id, $this->creator_id));
    }
    
    public function update(){
        global $USER;
        checkCapabilities('coursebook:update', $USER->role_id);
        list ($this->timestart, $this->timeend) = explode(' - ',$this->timerange); // copy timestart and timeend from timerage
        $this->timestart = date('Y-m-d G:i:s', strtotime($this->timestart));
        $this->timeend   = date('Y-m-d G:i:s', strtotime($this->timeend));
        $db = DB::prepare('UPDATE course_book SET topic = ?, description = ?, event_id = ?, timestart = ?, timeend = ?, course_id = ?, creator_id = ? WHERE cb_id = ?');
        return $db->execute(array($this->topic, $this->description, $this->event_id, $this->timestart, $this->timeend, $this->course_id, $this->creator_id, $this->id));
    }
    
    public function delete(){
        global $USER;
        checkCapabilities('coursebook:delete', $USER->role_id);
        $db = DB::prepare('DELETE FROM course_book WHERE cb_id = ?');
        return $db->execute(array($this->id));
    } 
    
    public function load($dependency = 'cb_id', $value = null){
        if (isset($value)){ $v = $value; } else { $v = $this->id; }
        $db = DB::prepare('SELECT * FROM course_book WHERE '.$dependency.' = ?');
        $db->execute(array($v));
        $result     = $db->fetchObject();
        $user       = new User();
        $absent    = new Absent();
        if ($result){
            $this->id            = $result->cb_id;
            $this->topic         = $result->topic;
            $this->description   = $result->description;
            $this->creation_time = $result->creation_time;
            $this->creator_id    = $result->creator_id;
            $this->creator       = $user->resolveUserId($result->creator_id);
            $this->course_id     = $result->course_id;
            /*$this->event_id    = $result->event_id;
            if (isset($this->event_id)){
                $this->event     = new Event($this->event_id);
                $this->timerange = $this->event->timerange;
            }*/
            $this->timestart     = $result->timestart;
            $this->timeend       = $result->timeend;
            $this->timerange     = date('d.m.Y G:i', strtotime($this->timestart)) .' - '. date('d.m.Y G:i', strtotime($result->timeend));
            $this->teacher_list  = '';
            $this->present_list  = '';
            $absent->cb_id      = $this->id;
            $this->absent_list   = $absent->get();
            //$this->task_id       = $result->task_id;
            $t                   = new Task();
            $this->task          = $t->get('coursebook', $this->id);
            
            return true;                                                        
        } else { 
            return false; 
        }
    }
    
    /**
     * Get all availible Grades of current institution
     * @return array of Grade objects 
     */
    public function get($dependency = 'user', $id = null, $paginator = ''){
        global $USER;
        $order_param = orderPaginator($paginator, array('topic'         => 'cb',
                                                        'description'   => 'cb')); 
        $entrys = array();                      //Array of grades
        switch ($dependency) {
            case 'user':    $db = DB::prepare('SELECT cb.cb_id
                                                FROM course_book AS cb
                                                WHERE cb.creator_id = ? '.$order_param );
                            $db->execute(array($USER->id));
                break;
            case 'course':  $db = DB::prepare('SELECT cb.cb_id
                                                FROM course_book AS cb
                                                WHERE cb.course_id = ? '.$order_param );
                            $db->execute(array($id));
                break;

            default:
                break;
        }
        
        
        while($result = $db->fetchObject()) { 
                $this->id            = $result->cb_id;
                $this->load();
                $entrys[]            = clone $this;        //it has to be clone, to get the object and not the reference
        } 
        
        return $entrys;
    }
    
    
    /**
    * function used during the install process to set up creator id to new admin
    * @return boolean
    */
    public function dedicate(){ // only use during install
        $db = DB::prepare('UPDATE course_book SET  creator_id = ?');        
        return $db->execute(array($this->creator_id));
    }
}