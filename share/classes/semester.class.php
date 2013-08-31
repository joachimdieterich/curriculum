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
        $query = sprintf("SELECT se.id, se.semester, se.description, se.begin, se.end, 
                                se.creation_time, se.creator_id, us.username, ins.institution
                        FROM semester AS se, users AS us, institution AS ins
                        WHERE se.institution_id IN ('%s')
                        AND se.creator_id = us.id
                        AND se.institution_id = ins.id",
                            mysql_real_escape_string(implode(",", $this->institution_id)));
        $result = mysql_query($query);
        if ($result && mysql_num_rows($result)) {
            while($row = mysql_fetch_assoc($result)) { 
                    $this->id                  = $row['id'];
                    $this->semester            = $row['semester'];
                    $this->description         = $row['description'];
                    $this->begin               = $row['begin'];
                    $this->end                 = $row['end'];
                    $this->creation_time       = $row['creation_time'];
                    $this->creator_id          = $row['creator_id'];
                    $this->creator_username    = $row['username'];
                    $semesters[] = clone $this;
            } 
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
        $query = sprintf("SELECT COUNT(id) FROM semester WHERE UPPER(semester) = UPPER('%s') AND institution_id = '%s'",
                                    mysql_real_escape_string($this->semester),
                                    mysql_real_escape_string($this->institution_id));
        $result = mysql_query($query);
        list($count) = mysql_fetch_row($result);
        if($count >= 1) { 
                return 'Diesen Lernzeitraum gibt es bereits.';
        } else {
            $query = sprintf("INSERT INTO semester (semester,description,begin,end,creation_time,creator_id,institution_id)
                                            VALUES ('%s','%s','%s','%s',NOW(),'%s','%s')",
                                            mysql_real_escape_string($this->semester),
                                            mysql_real_escape_string($this->description),
                                            mysql_real_escape_string($this->begin),
                                            mysql_real_escape_string($this->end),
                                            mysql_real_escape_string($this->creator_id),
                                            mysql_real_escape_string($this->institution_id));
            return mysql_query($query);		
        }   
    }
    
    /**
     * Update semester 
     * @return boolean 
     */
    public function update(){
        $query = sprintf("UPDATE semester 
                        SET semester = '%s', description = '%s', begin = '%s', end = '%s'
                        WHERE id = '%s'",
                        mysql_real_escape_string($this->semester),
                        mysql_real_escape_string($this->description),
                        mysql_real_escape_string($this->begin),
                        mysql_real_escape_string($this->end),
                        mysql_real_escape_string($this->id));
        return mysql_query($query);   
    }
    
    /**
     * Delete current semester
     * @return boolean 
     */
    public function delete(){
        $query = sprintf("SELECT id 
                            FROM groups
                            WHERE semester_id = '%s'",
                    mysql_real_escape_string($this->id));
        $result = mysql_query($query);
        if ($result && mysql_num_rows($result)){
            return false; 
        } else {
            $query = sprintf("DELETE FROM semester WHERE id='%s'",
                        mysql_real_escape_string($this->id));
            return mysql_query($query);
        }
    }
    
    /**
     * Load semester 
     */
    public function load(){
        $query = sprintf("SELECT *
                        FROM semester 
                        WHERE id = '%s'",
                        mysql_real_escape_string($this->id));  
        $result                  = mysql_query($query); 
        $this->id                = mysql_result($result, 0, "id");
        $this->semester          = mysql_result($result, 0, "semester");
        $this->description       = mysql_result($result, 0, "description");
        $this->begin             = mysql_result($result, 0, "begin");
        $this->end               = mysql_result($result, 0, "end");
        $this->institution_id    = mysql_result($result, 0, "institution_id");
    }
    
    /**
    * function used during the install process to set up creator id to new admin
    * @return boolean
    */
    public function dedicate(){ // only use during install
        $query = sprintf("UPDATE semester SET institution_id = '%s', creator_id = '%s'",
                                            mysql_real_escape_string($this->institution_id),
                                            mysql_real_escape_string($this->creator_id));
        return mysql_query($query);
    }
    
}
?>