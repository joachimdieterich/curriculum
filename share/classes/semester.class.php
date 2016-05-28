<?php
/**
 * This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package core
 * @filename semester.class.php
 * @copyright 2013 joachimdieterich
 * @author joachimdieterich
 * @date 2013.05.14 11:05
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

class Semester {
    /**
     * ID of semester
     * @var int
     */
    public $id; 
    /**
     * Name of semester
     * @var string
     */
    public $semester; 
    /**
     * Description of description
     * @var string
     */
    public $description; 
    /**
     * ID of current institution
     * @var int
     */
    public $institution_id; 
    public $institution; 
    /**
     * Timestamp of semester begin
     * @var timestamp
     */
    public $begin; 
    /**
     * Timestamp of semester end
     * @var timestamp
     */
    public $end; 
    public $timerange;
    /** 
     * Timestamp of creation
     * @var timestamp
     */
    public $creation_time; 
    /**
     * ID of creator
     * @var int
     */
    public $creator_id; 
    /**
     * Username of creator
     * @var string 
     */
    public $creator_username; 
    
    
    public function __construct($id = '') {
        if ($id != ''){
            $this->id = $id;
            $this->load();
        }
    }
    
    /**
     * Get Semesterlist of current institution
     * @return \Semester 
     */
    public function getSemesters($paginator = ''){
        global $USER;
        $order_param = orderPaginator($paginator, array('semester'      => 'se',
                                                        'description'   => 'se', 
                                                        'institution'   => 'ins')); 
        $semesters = array();
        $db = DB::prepare('SELECT se.*, us.username, ins.institution
                           FROM semester AS se, users AS us, institution AS ins
                           WHERE se.institution_id = ANY (SELECT institution_id FROM institution_enrolments WHERE institution_id = ins.id AND user_id = ?) 
                           AND se.creator_id = us.id AND se.institution_id = ins.id '.$order_param);
        $db->execute(array($USER->id));
        while($result = $db->fetchObject()) { 
                $this->id                  = $result->id;
                $this->semester            = $result->semester;
                $this->description         = $result->description;
                $this->institution         = $result->institution;
                $this->begin               = $result->begin;
                $this->end                 = $result->end;
                $this->timerange           = date('d.m.Y G:i', strtotime($this->begin)) .' - '. date('d.m.Y G:i', strtotime($result->end));
                $this->creation_time       = $result->creation_time;
                $this->creator_id          = $result->creator_id;
                $this->creator_username    = $result->username;
                $semesters[] = clone $this;
        } 

        return $semesters;     
    }
    
   
    public function getMySemesters($user_id){
        $semesters = array();
        $db = DB::prepare('SELECT DISTINCT se.*, us.username, ins.institution
                           FROM semester AS se, users AS us, groups AS gr, groups_enrolments AS ge, institution_enrolments AS ie, institution AS ins
                           WHERE gr.semester_id = se.id AND gr.id = ge.group_id AND us.id = ge.user_id AND ie.user_id = ge.user_id 
                           AND gr.institution_id = ie.institution_id AND ie.status = 1 AND ge.status = 1
                           AND ins.id = ie.institution_id AND ge.user_id = ?');
        $db->execute(array($user_id));
        while($result = $db->fetchObject()) { 
                $this->id                  = $result->id;
                $this->semester            = $result->semester;
                $this->institution         = $result->institution;
                $this->description         = $result->description;
                $this->begin               = $result->begin;
                $this->end                 = $result->end;
                $this->timerange           = date('d.m.Y G:i', strtotime($this->begin)) .' - '. date('d.m.Y G:i', strtotime($result->end));
                $this->creation_time       = $result->creation_time;
                $this->creator_id          = $result->creator_id;
                $this->creator_username    = $result->username;
                $semesters[] = clone $this;
        } 
        return $semesters;
    }
    
    /**
     * Add a new semester to db
     * @return mixed 
     */
    public function add(){
        global $USER;
        checkCapabilities('semester:add', $USER->role_id);
        list ($this->begin, $this->end) = explode(' - ',$this->timerange); // copy timestart and timeend from timerage
        $this->begin = date('Y-m-d G:i:s', strtotime($this->begin));
        $this->end   = date('Y-m-d G:i:s', strtotime($this->end));
        $db = DB::prepare('SELECT COUNT(id) FROM semester WHERE UPPER(semester) = UPPER(?) AND institution_id = ?');
        $db->execute(array($this->semester, $this->institution_id));
        if($db->fetchColumn() >= 1) { 
            return 'Diesen Lernzeitraum gibt es bereits.';
        } else {
            $db = DB::prepare('INSERT INTO semester (semester,description,begin,end,creation_time,creator_id,institution_id)
                                            VALUES (?,?,?,?,NOW(),?,?)');
            return $db->execute(array($this->semester, $this->description, $this->begin, $this->end, $this->creator_id, $this->institution_id));	
        }   
    }
    
    /**
     * Update semester 
     * @return boolean 
     */
    public function update(){
        global $USER;
        checkCapabilities('semester:update', $USER->role_id);
        list ($this->begin, $this->end) = explode(' - ',$this->timerange); // copy timestart and timeend from timerage
        $this->begin = date('Y-m-d G:i:s', strtotime($this->begin));
        $this->end   = date('Y-m-d G:i:s', strtotime($this->end));
        $db = DB::prepare('UPDATE semester SET semester = ?, description = ?, begin = ?, end = ?, institution_id = ? WHERE id = ?');
        return $db->execute(array($this->semester, $this->description, $this->begin, $this->end, $this->institution_id, $this->id));
    }
    
    /**
     * Delete current semester
     * @return boolean 
     */
    public function delete(){
        global $USER;
        checkCapabilities('semester:delete', $USER->role_id);
        $db     = DB::prepare('SELECT id FROM groups WHERE semester_id = ?');
        $db->execute(array($this->id));
        if ($db->fetchObject()){
            return false; 
        } else {
            $db = DB::prepare('DELETE FROM semester WHERE id = ?');
            return $db->execute(array($this->id));
        }
    }
    
    /**
     * Load semester 
     */
    public function load(){
        $db = DB::prepare('SELECT * FROM semester WHERE id = ?');
        $db->execute(array($this->id));
        $result                  = $db->fetchObject();
        $this->id                = $result->id;
        $this->semester          = $result->semester;
        $this->description       = $result->description;
        $this->begin             = $result->begin;
        $this->end               = $result->end;
        $this->timerange         = date('d.m.Y G:i', strtotime($this->begin)) .' - '. date('d.m.Y G:i', strtotime($result->end));
        $this->institution_id    = $result->institution_id;
    }
    
    
    /**
    * function used during the install process to set up creator id to new admin
    * @return boolean
    */
    public function dedicate(){ // only use during install
        $db = DB::prepare('UPDATE semester SET institution_id = ?, creator_id = ?');
        return $db->execute(array($this->institution_id, $this->creator_id));
    }   
}