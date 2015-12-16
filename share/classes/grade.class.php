<?php
/**
 * Grade object can add, update, delete and get data from grade db
 * 
 * @abstract This file is part of curriculum - http://www.joachimdieterich.de
 * @package core
 * @filename grade.class.php
 * @copyright 2013 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2013.03.10 10:58
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
class Grade {
    /**
     * ID of Grade
     * @var int
     */
    public $id;
    /**
     * Name of Grade
     * @var string
     */
    public $grade; 
    /**
     * Description of Grade
     * @var string
     */
    public $description; 
    /**
     * Timestamp when Grade was created
     * @var timestamp
     */
    public $creation_time; 
    /**
     * ID of User who created this Grade
     * @var int
     */
    public $creator_id; 
    /**
     * ID of institution to which Grade belongs to
     * @var int
     */
    public $institution_id; 
    public $institution; 
   
    
    /**
     * add grade
     * @return mixed 
     */
    public function add(){
        global $USER;
        checkCapabilities('grade:add', $USER->role_id);
        $db = DB::prepare('INSERT INTO grade (grade,description,creator_id,institution_id) VALUES (?,?,?,?)');
        return $db->execute(array($this->grade, $this->description, $this->creator_id, $this->institution_id));
    }
    
    /**
     * Update grade
     * @return boolean 
     */
    public function update(){
        global $USER;
        checkCapabilities('grade:update', $USER->role_id);
        $db = DB::prepare('UPDATE grade SET grade = ?, description = ?, creator_id = ? WHERE id = ?');
        return $db->execute(array($this->grade, $this->description, $this->creator_id, $this->id));
    }
    
    /**
     * delete grade
     * @global object $USER
     * @param int $creator_id
     * @return boolean 
     */
    public function delete(){
        global $USER;
        checkCapabilities('grade:delete', $USER->role_id);
        $db = DB::prepare('SELECT id FROM curriculum WHERE grade_id = ?');
        $db->execute(array($this->id));
        if ($db->fetchObject()){ //endroled !
            return false;
        } else {
            $db = DB::prepare('DELETE FROM grade WHERE id = ?');
            return $db->execute(array($this->id));
        } 
    } 
    
    /**
     * Load Grade with id $this->id 
     */
    public function load(){
        $db = DB::prepare('SELECT * FROM grade WHERE id = ?');
        $db->execute(array($this->id));
        
        $result = $db->fetchObject();
        $this->grade       = $result->grade;
        $this->description = $result->description;
        $this->institution_id = $result->institution_id;
    }
    
    /**
     * Get all availible Grades of current institution
     * @return array of Grade objects 
     */
    public function getGrades($paginator = ''){
        global $USER;
        $order_param = orderPaginator($paginator); 
        $grades = array();                      //Array of grades
        $db = DB::prepare('SELECT gr.*, ins.institution 
                           FROM grade AS gr, institution AS ins 
                           WHERE gr.institution_id = ANY (SELECT institution_id FROM institution_enrolments WHERE institution_id = ins.id AND user_id = ?) 
                           AND gr.institution_id = ins.id '.$order_param );
        $db->execute(array($USER->id));
        
        while($result = $db->fetchObject()) { 
                $this->id               = $result->id;
                $this->grade            = $result->grade;
                $this->description      = $result->description;
                $this->institution      = $result->institution;
                $this->creation_times   = $result->creation_time;
                $this->creator_id       = $result->creator_id;
                $this->institution_id   = $result->institution_id;
                $grades[]               = clone $this;        //it has to be clone, to get the object and not the reference
        } 
        return $grades;
    }
    
    /**
    * function used during the install process to set up creator id to new admin
    * @return boolean
    */
    public function dedicate(){ // only use during install
        $db = DB::prepare('UPDATE grade SET institution_id = ?, creator_id = ?');        
        return $db->execute(array($this->institution_id, $this->creator_id));
    }
}