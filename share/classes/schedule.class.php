<?php
/**
* Schedule
* 
* @abstract This file is part of curriculum - http://www.joachimdieterich.de
* @package core
* @filename schedule.class.php
* @copyright 2018 Joachim Dieterich
* @author Joachim Dieterich
* @date 2018.01.27 15:46
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
class Schedule {

    public $id;
    public $schedule; 
    public $description;
    public $context_id;
    public $reference_id;
    public $dow;
    public $date_start;
    public $date_end;
    public $time_start;
    public $time_end;
    public $creation_time; 
    public $creator_id; 
  
    public function __construct($id = null) {
        if ($id != null){
            $this->load('id', $id); 
        }
    }

    public function add(){
        global $USER;
        checkCapabilities('schedule:add', $USER->role_id);
        $db = DB::prepare('INSERT INTO schedule (schedule,description,context_id,reference_id,dow,date_start,date_end,time_start,time_end,creation_time,creator_id) VALUES (?,?,?,?,?,?,?,?,?,?,?)');
        return $db->execute(array($this->schedule, $this->description, $this->context_id, $this->reference_id, $this->dow, $this->date_start, $this->date_end, $this->time_start, $this->time_end,  $this->creation_time, $USER->id));
    }
    
    public function update(){
        global $USER;
        checkCapabilities('schedule:update', $USER->role_id);
        $db = DB::prepare('UPDATE schedule SET schedule = ?,description = ?,context_id = ?, reference_id = ? dow = ?,date_start = ?,date_end = ?,time_start = ?,time_end = ?,creation_time = ? WHERE id = ?');
        return $db->execute(array($this->schedule, $this->description, $this->context_id, $this->reference_id, $this->dow, $this->date_start, $this->date_end, $this->time_start, $this->time_end, $this->creation_time, $this->id));
    }
    
    public function delete(){
        global $USER;
        checkCapabilities('schedule:delete', $USER->role_id);
        $db = DB::prepare('DELETE FROM schedule WHERE id = ?');
        return $db->execute(array($this->id));
        
    } 
    
    public function load($dependency = 'id', $value = null){
        if (isset($value)){ $v = $value; } else { $v = $this->id; }
        $db = DB::prepare('SELECT * FROM schedule WHERE '.$dependency.' = ?');
        $db->execute(array($v));
        $result = $db->fetchObject();
        if ($result){
            $this->id                = $result->id;
            $this->schedule          = $result->schedule;
            $this->description       = $result->description;
            $this->context_id        = $result->context_id;
            $this->reference_id      = $result->reference_id;
            $this->dow               = $result->dow;
            $this->date_start        = $result->date_start;
            $this->date_end          = $result->date_end;
            $this->time_start        = $result->time_start;
            $this->time_end          = $result->time_end;
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
    public function get($dependency = 'course', $reference_ids = null, $paginator = '', $limit = 5){
        if ($reference_ids == null){
            $reference_ids = array($this->$reference_id);
        }
        $order_param = orderPaginator($paginator, array('schedule'      => 'sd',
                                                        'description'   => 'sd')); 
        
        $db = DB::prepare('SELECT SQL_CALC_FOUND_ROWS *
                                FROM schedule AS sd
                                WHERE sd.context_id = ? AND sd.reference_id IN ('.$reference_ids.') '.$order_param );
        $db->execute(array($_SESSION['CONTEXT'][$dependency]->id));
      
        $entrys = array();                      //Array of entrys
        while($result = $db->fetchObject()) { 
                $this->id                = $result->id;
                $this->schedule          = $result->schedule;
                $this->description       = $result->description;
                $this->context_id        = $result->context_id;
                $this->reference_id      = $result->reference_id;
                $this->dow               = $result->dow;
                $this->date_start        = $result->date_start;
                $this->date_end          = $result->date_end;
                $this->time_start        = $result->time_start;
                $this->time_end          = $result->time_end;
                $this->creation_time     = $result->creation_time;
                $this->creator_id        = $result->creator_id;
                $entrys[]                = clone $this;        //it has to be clone, to get the object and not the reference
        } 
        if ($paginator != ''){ 
             set_item_total($paginator); //set item total based on FOUND ROWS()
        } 
        return $entrys;
    }
    
    /**
    * function used during the install process to set up creator id to new admin
    * @return boolean
    */
    public function dedicate(){ // only use during install
        $db = DB::prepare('UPDATE schedule SET  creator_id = ?');        
        return $db->execute(array($this->creator_id));
    }
}