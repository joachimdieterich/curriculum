<?php
/**
* Tasks
* 
* @abstract This file is part of curriculum - http://www.joachimdieterich.de
* @package core
* @filename task.class.php
* @copyright 2016 Joachim Dieterich
* @author Joachim Dieterich
* @date 2016.05.18 08:23
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
class Task {

    public $id;
    public $task; 
    public $description; 
    public $creation_time; 
    public $creator_id; 
    public $creator; 
    public $timestart;
    public $timeend;
    public $timerange;
    
    public $accomplished;
    
   
    public function add(){
        global $USER;
        checkCapabilities('task:add', $USER->role_id);
        list ($this->timestart, $this->timeend) = explode(' - ',$this->timerange); // copy timestart and timeend from timerage
        $this->timestart = date('Y-m-d G:i:s', strtotime($this->timestart));
        $this->timeend   = date('Y-m-d G:i:s', strtotime($this->timeend));
        $db = DB::prepare('INSERT INTO task (task,description,timestart,timeend,creator_id) VALUES (?,?,?,?,?)');
        $db->execute(array($this->task, $this->description, $this->timestart, $this->timeend, $USER->id));
        return DB::lastInsertId(); //returns id 
    }
    
    public function update(){
        global $USER;
        checkCapabilities('task:update', $USER->role_id);
        list ($this->timestart, $this->timeend) = explode(' - ',$this->timerange); // copy timestart and timeend from timerage
        $this->timestart = date('Y-m-d G:i:s', strtotime($this->timestart));
        $this->timeend   = date('Y-m-d G:i:s', strtotime($this->timeend));
        $db = DB::prepare('UPDATE task SET task = ?, description = ?, timestart = ?, timeend = ? WHERE id = ?');
        return $db->execute(array($this->task, $this->description, $this->timestart, $this->timeend,  $this->id));
    }
    
    public function delete(){
        global $USER, $LOG;
        checkCapabilities('task:delete', $USER->role_id);
        $this->load();
        $LOG->add($USER->id, 'task.class.php', dirname(__FILE__), 'Delete task: '.$this->task.', creator_id: '.$this->creator_id);
        $db             = DB::prepare('DELETE FROM task WHERE id = ?');
        $ret_task       = $db->execute(array($this->id));
        $db             = DB::prepare('DELETE FROM task_enrolments WHERE task_id = ?');
        $ret_enrolment  =  $db->execute(array($this->id));
        if (($ret_enrolment) == true AND ($ret_task == true)){
            return true;
        } else {
            return false;
        }
    } 
    
    public function load($dependency = 'id', $value = null){
        if (isset($value)){ $v = $value; } else { $v = $this->id; }
        $db = DB::prepare('SELECT * FROM task WHERE '.$dependency.' = ?');
        $db->execute(array($v));
        $result = $db->fetchObject();
        $user = new User();
        if ($result){
            $this->id            = $result->id;
            $this->task          = $result->task;
            $this->description   = $result->description;
            $this->creation_time = $result->creation_time;
            $this->creator_id    = $result->creator_id;
            $this->creator       = $user->resolveUserId($result->creator_id);
            $this->timestart     = $result->timestart;
            $this->timeend       = $result->timeend;
            $this->timerange     = date('d.m.Y G:i', strtotime($this->timestart)) .' - '. date('d.m.Y G:i', strtotime($result->timeend));
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
        $order_param = orderPaginator($paginator, array('task'         => 'ta',
                                                        'description'   => 'ta')); 
        $entrys = array();                      //Array of grades
        switch ($dependency) {
            case 'user':            $db = DB::prepare('SELECT ta.id
                                                        FROM task AS ta
                                                        WHERE ta.creator_id = ? '.$order_param );
                                    $db->execute(array($USER->id));
                break;

            case 'institution':      $db = DB::prepare('SELECT ta.id
                                                FROM task AS ta, task_enrolments AS te, context AS ct
                                                WHERE ct.context = ? 
                                                AND ct.context_id = te.context_id
                                                AND te.reference_id = ?
                                                AND te.task_id = ta.id ORDER BY ta.timeend' );
                                    $db->execute(array('institution', $id));
                break;
            case 'coursebook':      $db = DB::prepare('SELECT ta.id
                                                FROM task AS ta, task_enrolments AS te, context AS ct
                                                WHERE ct.context = ? 
                                                AND ct.context_id = te.context_id
                                                AND te.reference_id = ?
                                                AND te.task_id = ta.id '.$order_param );
                                    $db->execute(array('courseBook', $id));
                break;
            case 'upcoming':        $db = DB::prepare('SELECT ta.id FROM task AS ta, task_enrolments AS te, context AS ct, course_book AS cb, curriculum_enrolments AS ce, groups_enrolments AS ge
                                                        WHERE ct.context = ?
                                                        AND ct.context_id = te.context_id
                                                        AND te.reference_id = cb.cb_id
                                                        AND cb.course_id = ce.id
                                                        AND ge.group_id = ce.group_id
                                                        AND ge.user_id = ?
                                                        AND te.task_id = ta.id  AND ta.timeend >= NOW() ORDER BY ta.timeend ASC  '.$order_param );
                                    $db->execute(array('courseBook', $USER->id));
                break;
            default:
                break;
        }
        
        
        while($result = $db->fetchObject()) { 
                $this->id            = $result->id;
                $this->load();
                
                if ($dependency == 'upcoming'){
                    $db1 = DB::prepare('SELECT ua.* FROM user_accomplished AS ua, context AS co 
                                                   WHERE ua.context_id = co.context_id
                                                   AND co.context = ?
                                                   AND ua.user_id = ?
                                                   AND ua.reference_id = ?');
                    $db1->execute(array('task', $USER->id, $this->id));
                    $a = $db1->fetchObject();
                    if (isset($a)){
                        $this->accomplished = $a;
                    } else {
                        $this->accomplished = null;
                    }
                    
                }
                $entrys[]            = clone $this;        //it has to be clone, to get the object and not the reference
        } 
        
        return $entrys;
    }
    
    public function checkEnrolment($context_id, $reference_id, $status = '1'){
        $db = DB::prepare('SELECT count(id) FROM task_enrolments WHERE context_id = ? AND reference_id = ? AND task_id = ? AND status = ?');
        $db->execute(array($context_id, $reference_id, $this->id, $status));
        if ($db->fetchColumn() > 0){
            return true;
        } else {
            return false; 
        }    
    }
    
    public function enrol($context_id, $reference_id){
        global $USER;
        checkCapabilities('task:enrol', $USER->role_id);
        if ($this->checkEnrolment($context_id, $reference_id, 0)) {
            $db = DB::prepare('UPDATE task_enrolments SET status = 1, creator_id = ?, creation_time = NOW()
                                WHERE context_id = ? AND reference_id = ? AND task_id = ?'); //Status 1 == eingeschrieben
            return $db->execute(array($USER->id, $context_id, $reference_id, $this->id)); 
        } else {
            $db = DB::prepare('INSERT INTO task_enrolments (status,context_id,reference_id,task_id,creator_id) 
                                VALUES (1,?,?,?,?)');//Status 1 == eingeschrieben
            return $db->execute(array($context_id, $reference_id, $this->id, $USER->id));
        }
    }
    
    
    public function accomplish($dependency = null, $user_id = null,  $status = 2) {
        global $USER;
        switch ($dependency) {
            case 'user':    $db0    = DB::prepare('SELECT * FROM user_accomplished WHERE reference_id = ? AND user_id = ? AND context_id = 13');
                            $db0->execute(array($this->id, $user_id));
                            $result = $db0->fetchObject();
                            if ($result){ //if entry exists
                                switch ($result->status_id) {
                                    case 2 : $status = 0;
                                        break;
                                    case 0 : 
                                    default: $status = 2;
                                        break;
                                } // else $status = 2 by default
                                $db = DB::prepare('UPDATE user_accomplished SET status_id = ?, accomplished_time = CURRENT_TIMESTAMP WHERE reference_id = ? AND user_id = ? AND creator_id = ? AND context_id = 13');
                                return $db->execute(array($status, $this->id, $user_id, $USER->id));
                            } else {
                                $db = DB::prepare('INSERT INTO user_accomplished (status_id, reference_id, user_id, creator_id, context_id,accomplished_time) VALUES (?,?,?,?,?,CURRENT_TIMESTAMP)');
                                return $db->execute(array($status, $this->id, $user_id, $USER->id, 13)); //context_id 13 == task 
                            }
                break;
            
            default:        break;
        } 
    }
    
    /**
    * function used during the install process to set up creator id to new admin
    * @return boolean
    */
    public function dedicate(){ // only use during install
        $db = DB::prepare('UPDATE task SET  creator_id = ?');        
        return $db->execute(array($this->creator_id));
    }
}