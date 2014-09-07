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
 * This program is free software; you can redistribute it and/or modify 
 * it under the terms of the GNU General Public License as published by  
 * the Free Software Foundation; either version 3 of the License, or     
 * (at your option) any later version.                                   
 *                                                                       
 * This program is distributed in the hope that it will be useful,       
 * but WITHOUT ANY WARRANTY; without even the implied warranty of        
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the         
 * GNU General Public License for more details:                          
 *                                                                       
 * http://www.gnu.org/copyleft/gpl.html      
 */

class Semester {
    /**
     * ID of semester
     * @var int
     */
    public $id = null; 
    /**
     * Name of semester
     * @var string
     */
    public $semester = null; 
    /**
     * Description of description
     * @var string
     */
    public $description = null; 
    /**
     * ID of current institution
     * @var int
     */
    public $institution_id = null; 
    /**
     * Timestamp of semester begin
     * @var timestamp
     */
    public $begin = null; 
    /**
     * Timestamp of semester end
     * @var timestamp
     */
    public $end = null; 
    /** 
     * Timestamp of creation
     * @var timestamp
     */
    public $creation_time = null; 
    /**
     * ID of creator
     * @var int
     */
    public $creator_id = null; 
    /**
     * Username of creator
     * @var string 
     */
    public $creator_username = null; 
    
    /**
     * Get Semesterlist of current institution
     * @return \Semester 
     */
    public function getSemesters(){
        $semesters = array();
        $db = DB::prepare('SELECT se.id, se.semester, se.description, se.begin, se.end, 
                                se.creation_time, se.creator_id, us.username, ins.institution
                        FROM semester AS se, users AS us, institution AS ins
                        WHERE se.institution_id IN (?) AND se.creator_id = us.id AND se.institution_id = ins.id');
        $db->execute(array(implode(",", $this->institution_id)));
        while($result = $db->fetchObject()) { 
                $this->id                  = $result->id;
                $this->semester            = $result->semester;
                $this->description         = $result->description;
                $this->begin               = $result->begin;
                $this->end                 = $result->end;
                $this->creation_time       = $result->creation_time;
                $this->creator_id          = $result->creator_id;
                $this->creator_username    = $result->username;
                $semesters[] = clone $this;
        } 
        if (isset($semesters)) {    
            return $semesters;
        } else {
            return NULL;
        }        
    }
    
    public function getMySemesters($user_id){
        $semesters = array();
        $db = DB::prepare('SELECT se.id, se.semester, se.description, se.begin, se.end, 
                                se.creation_time, se.creator_id, us.username
                        FROM semester AS se, users AS us, groups AS gr, groups_enrolments AS ge
                        WHERE gr.semester_id = se.id AND gr.id = ge.group_id AND us.id = ge.user_id AND ge.user_id = ?');
        $db->execute(array($user_id));
        while($result = $db->fetchObject()) { 
                $this->id                  = $result->id;
                $this->semester            = $result->semester;
                $this->description         = $result->description;
                $this->begin               = $result->begin;
                $this->end                 = $result->end;
                $this->creation_time       = $result->creation_time;
                $this->creator_id          = $result->creator_id;
                $this->creator_username    = $result->username;
                $semesters[] = clone $this;
        } 
        if (isset($semesters)) {    
            return $semesters;
        } else {
            return NULL;
        } 
    }
    
    /**
     * Add a new semester to db
     * @return mixed 
     */
    public function add(){
        global $USER;
        if (checkCapabilities('semester:add', $USER->role_id)){
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
    }
    
    /**
     * Update semester 
     * @return boolean 
     */
    public function update(){
        global $USER;
        if (checkCapabilities('semester:update', $USER->role_id)){
            $db = DB::prepare('UPDATE semester SET semester = ?, description = ?, begin = ?, end = ? WHERE id = ?');
            return $db->execute(array($this->semester, $this->description, $this->begin, $this->end, $this->id));
        }
    }
    
    /**
     * Delete current semester
     * @return boolean 
     */
    public function delete($creator_id = null){
        /*if ($creator_id != null) { // if function is called by request-php --> required by checkCapabilities()
            $user = new USER();

            $user->load('id', $creator_id);
            $role_id = $user->role_id;
        } else {
            $role_id = $USER->role-id;
        } */
        global $USER;
        if (checkCapabilities('semester:delete', $USER->role_id)){
            $db = DB::prepare('SELECT id FROM groups WHERE semester_id = ?');
            $db->execute(array($this->id));           
            $result = $db->fetchObject();
            if ($result){
                return false; 
            } else {
                $db = DB::prepare('DELETE FROM semester WHERE id = ?');
                return $db->execute(array($this->id));
            }
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
?>