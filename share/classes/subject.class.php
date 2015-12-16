<?php
/**
 * This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package core
 * @filename subject.class.php
 * @copyright 2013 joachimdieterich
 * @author joachimdieterich
 * @date 2013.05.11 20:50
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
class Subject {
    /**
     * ID of Subject
     * @var int 
     */
    public $id; 
    /**
     * Subject title
     * @var string 
     */
    public $subject;
    /**
     * Subject shortcut
     * @var string
     */
    public $subject_short; 
    /**
     * Subject description
     * @var string
     */
    public $description; 
    /**
     * ID of institution to which subject belongs to
     * @var type 
     */
    public $institution_id; 
    /**
     * Timestamp when Subject was created
     * @var timestamp 
     */
    public $creation_timestamp; 
    /**
     * ID of User who created this subject
     * @var int
     */
    public $creator_id; 
    
    /**
     * Get all available subjects of current Institution
     * @return array of objects 
     */
    public function getSubjects($paginator =''){
        global $USER;
        $order_param    = orderPaginator($paginator); 
        $subjects       = array();
        $db             = DB::prepare('SELECT sub.*, ins.institution 
                                       FROM subjects AS sub, institution AS ins 
                                       WHERE sub.institution_id  = ANY (SELECT institution_id FROM institution_enrolments WHERE institution_id = ins.id AND user_id = ?) 
                                       AND sub.institution_id= ins.id '.$order_param);
        $db->execute(array($USER->id));
        while($result = $db->fetchObject()) { 
                $this->id                   = $result->id;
                $this->subject              = $result->subject;
                $this->subject_short        = $result->subject_short;
                $this->description          = $result->description;
                $this->creation_timestamp   = $result->creation_time;
                $this->creator_id           = $result->creator_id;
                $this->institution_id       = $result->institution_id;
                $this->institution          = $result->institution;
                $subjects[] = clone $this;
        } 
         if (isset($subjects)) {    
            return $subjects;
        } else {return $result;}
    }
    
    /**
     * Add Subject
     * @return boolean 
     */
    public function add(){
        global $USER;
        checkCapabilities('subject:add', $USER->role_id);
        $db = DB::prepare('SELECT COUNT(id) FROM subjects WHERE UPPER(subject) = UPPER(?) AND institution_id = ?');
        $db->execute(array($this->subject, $this->institution_id));
        if($db->fetchColumn() >= 1) { 
            return 'Diesen Fächernamen gibt es bereits.';
        } else {
            $db = DB::prepare('INSERT INTO subjects (subject,subject_short,description,creator_id,institution_id) 
                                            VALUES (?,?,?,?,?)');
            return $db->execute(array($this->subject, $this->subject_short, $this->description, $this->creator_id, $this->institution_id));
        }
    }
    
    /**
     * Update current subject
     * @return boolean 
     */
    public function update(){
        global $USER;
        checkCapabilities('subject:update', $USER->role_id);
        $db = DB::prepare('UPDATE subjects  SET subject = ?, subject_short = ?, description = ?, creator_id = ? WHERE id = ?');
        return $db->execute(array($this->subject, $this->subject_short, $this->description, $this->creator_id, $this->id));
    }
    /**
     * Delete current subject
     * @return boolean 
     */
    public function delete(){
        global $USER;
        checkCapabilities('subject:delete', $USER->role_id);
        $db = DB::prepare('SELECT id FROM curriculum WHERE subject_id = ?');
        $db->execute(array($this->id));
        if ($db->fetchObject()){
            return false;
        } else { //delete only, if no enrolments exists
            $db = DB::prepare('DELETE FROM subjects WHERE id = ?');
            return $db->execute(array($this->id));
        }
    }

    /**
     * Load a subject 
     */
    public function load(){
        $db = DB::prepare('SELECT * FROM subjects WHERE id = ?');
        $db->execute(array($this->id));
        $result = $db->fetchObject();
        $this->subject        = $result->subject;
        $this->subject_short  = $result->subject_short;
        $this->description    = $result->description;
        $this->institution_id = $result->institution_id;
    }
    
    /**
    * function used during the install process to set up creator id to new admin
    * @return boolean
    */
    public function dedicate(){ // only use during install
        $db = DB::prepare('UPDATE subjects SET institution_id = ?, creator_id = ?');
        return $db->execute(array($this->institution_id, $this->creator_id));
    }
}