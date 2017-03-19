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
class CourseBook {

    public $id;
    public $topic; 
    public $description; 
    public $creation_time; 
    public $creator_id; 
    public $creator; 
    public $course_id;
    public $curriculum_id;
    public $curriculum;
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
        $db = DB::prepare('INSERT INTO course_book (topic,description,timestart,timeend,course_id,creator_id) VALUES (?,?,?,?,?,?)');
        return $db->execute(array($this->topic, $this->description,  $this->timestart, $this->timeend, $this->course_id, $USER->id));
    }
    
    public function update(){
        global $USER;
        checkCapabilities('coursebook:update', $USER->role_id);
        list ($this->timestart, $this->timeend) = explode(' - ',$this->timerange); // copy timestart and timeend from timerage
        $this->timestart = date('Y-m-d G:i:s', strtotime($this->timestart));
        $this->timeend   = date('Y-m-d G:i:s', strtotime($this->timeend));
        $db = DB::prepare('UPDATE course_book SET topic = ?, description = ?, timestart = ?, timeend = ?, course_id = ? WHERE cb_id = ?');
        return $db->execute(array($this->topic, $this->description,  $this->timestart, $this->timeend, $this->course_id,$this->id));
    }
    
    public function delete(){
        global $USER, $LOG;
        checkCapabilities('coursebook:delete', $USER->role_id);
        $this->load();
        $LOG->add($USER->id, 'coursebook.class.php', dirname(__FILE__), 'Delete coursebook: '.$this->topic.', course_id: '.$this->course_id.' creator_id: '.$this->creator_id);
        $db = DB::prepare('DELETE FROM course_book WHERE cb_id = ? AND creator_id = ?');
        return $db->execute(array($this->id, $USER->id));
    } 
    
    public function load($dependency = 'cb_id', $value = null){
        if (isset($value)){ $v = $value; } else { $v = $this->id; }
        $db = DB::prepare('SELECT cb.*, cu.curriculum, ce.curriculum_id FROM course_book AS cb, curriculum_enrolments AS ce, curriculum AS cu 
                                                         WHERE cb.'.$dependency.' = ? AND cb.course_id = ce.id AND cu.id = ce.curriculum_id');
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
            $this->curriculum_id = $result->curriculum_id;
            $this->curriculum    = $result->curriculum;
            $this->timestart     = $result->timestart;
            $this->timeend       = $result->timeend;
            $this->timerange     = date('d.m.Y G:i', strtotime($this->timestart)) .' - '. date('d.m.Y G:i', strtotime($result->timeend));
            $this->teacher_list  = '';
            $this->present_list  = '';
            $absent->cb_id       = $this->id;
            $this->absent_list   = $absent->get();
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
    public function get($dependency = 'user', $id = null, $date= null){
        global $USER;
        /*$order_param = orderPaginator($paginator, array('topic'         => 'cb',
                                                        'description'   => 'cb')); */
        if ($date != null){
            $order_param = $date;
        } else { 
            $order_param = 0; 
        }
        $entrys = array();                      //Array of grades
        switch ($dependency) {
            case 'user':    $db = DB::prepare('SELECT cb.cb_id FROM course_book AS cb, curriculum_enrolments AS ce, groups_enrolments AS ge
                                                        WHERE  cb.course_id = ce.id
                                                        AND ge.group_id = ce.group_id
                                                        AND ge.status = 1
                                                        AND ge.user_id = ?
                                                        AND cb.timestart >= ? 
                                                       ORDER BY cb.timestart ASC');
                            $db->execute(array($USER->id, $order_param));
                break;
            case 'course':  $db = DB::prepare('SELECT cb.cb_id
                                                FROM course_book AS cb
                                                WHERE cb.course_id = ? ' );
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