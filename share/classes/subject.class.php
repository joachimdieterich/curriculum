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
class Subject {
    /**
     * ID of Subject
     * @var int 
     */
    public $id = null; 
    /**
     * Subject title
     * @var string 
     */
    public $subject = null;
    /**
     * Subject shortcut
     * @var string
     */
    public $subject_short = null; 
    /**
     * Subject description
     * @var string
     */
    public $description = null; 
    /**
     * ID of institution to which subject belongs to
     * @var type 
     */
    public $institution_id = null; 
    /**
     * Timestamp when Subject was created
     * @var timestamp 
     */
    public $creation_timestamp = null; 
    /**
     * ID of User who created this subject
     * @var int
     */
    public $creator_id = null; 
    
    /**
     * Get all available subjects of current Institution
     * @return array of objects 
     */
    public function getSubjects(){
        $subjects = array();
        $query = sprintf("SELECT * FROM subjects WHERE institution_id IN ('%s')",
                        mysql_real_escape_string(implode(",", $this->institution_id)));
        $result = mysql_query($query);
        if ($result && mysql_num_rows($result)) {
            while($row = mysql_fetch_assoc($result)) { 
                    $this->id                   = $row['id'];
                    $this->subject              = $row['subject'];
                    $this->subject_short        = $row['subject_short'];
                    $this->description          = $row['description'];
                    $this->creation_timestamp   = $row['creation_time'];
                    $this->creator_id           = $row['creator_id'];
                    $this->institution_id       = $row['institution_id'];
                    $subjects[] = clone $this;
            } 
            
            return $subjects;
        } else {return $result;}
    }
    
    /**
     * Add Subject
     * @return boolean 
     */
    public function add(){
        $query = sprintf("SELECT COUNT(id) FROM subjects WHERE UPPER(subject) = UPPER('%s') AND institution_id = '%s'",
                        		 mysql_real_escape_string($this->subject),
                                         mysql_real_escape_string($this->institution_id));
        $result = mysql_query($query);
        list($count) = mysql_fetch_row($result);
        if($count >= 1) { 
            return 'Diesen Fächernamen gibt es bereits.';
        } else {
            $query = sprintf("INSERT INTO subjects (subject,subject_short,description,creator_id,institution_id) 
                                            VALUES ('%s','%s','%s','%s','%s')",
                                            mysql_real_escape_string($this->subject),
                                            mysql_real_escape_string($this->subject_short),
                                            mysql_real_escape_string($this->description),
                                            mysql_real_escape_string($this->creator_id),
                                            mysql_real_escape_string($this->institution_id));
            return  mysql_query($query);	
        }
    }
    
    /**
     * Update current subject
     * @return boolean 
     */
    public function update(){
        $query = sprintf("UPDATE subjects 
                            SET subject = '%s', subject_short = '%s', description = '%s',
                            creator_id = '%s'
                            WHERE id = '%s'",
                                            mysql_real_escape_string($this->subject),
                                            mysql_real_escape_string($this->subject_short),
                                            mysql_real_escape_string($this->description),
                                            mysql_real_escape_string($this->creator_id),
                                            mysql_real_escape_string($this->id));
        return mysql_query($query);
    }
    /**
     * Delete current subject
     * @return boolean 
     */
    public function delete(){
        $query = sprintf("SELECT id 
                            FROM curriculum
                            WHERE subject_id = '%s'",
                    mysql_real_escape_string($this->id));
        $result = mysql_query($query);
        if ($result && mysql_num_rows($result)){
            return false;
        } else { //nur löschen, wenn keine Einschreibungen existieren
            $query = sprintf("DELETE FROM subjects WHERE id='%s'",
                            mysql_real_escape_string($this->id));
            return mysql_query($query);
        }
    }

    /**
     * Load a subject 
     */
    public function load(){
        $query = sprintf("SELECT * FROM subjects WHERE id='%s'",
                        mysql_real_escape_string($this->id));
        $result = mysql_query($query);

        $row = mysql_fetch_assoc($result);
        $this->subject       = $row["subject"];
        $this->subject_short = $row["subject_short"];
        $this->description   = $row["description"];
    }
    
    /**
    * function used during the install process to set up creator id to new admin
    * @return boolean
    */
    public function dedicate(){ // only use during install
        $query = sprintf("UPDATE subjects SET institution_id = '%s', creator_id = '%s'",
                                            mysql_real_escape_string($this->institution_id),
                                            mysql_real_escape_string($this->creator_id));
        return mysql_query($query);
    }
}
?>