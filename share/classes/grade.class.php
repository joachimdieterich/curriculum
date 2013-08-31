<?php
/**
 * Grade object can add, update, delete and get data from grade db
 * 
 * @example
 * // Add new Grade <br>
 * $new_grade = new Grade(); <br>
 * $new_grade->grade          = '01. Klasse'; <br>
 * $new_grade->description    = 'Erstes Schuljahr - Regelschule'; <br>
 * $new_grade->creator_id     = $USER->id; <br>
 * $new_grade->institution_id = $institution->id); <br>
 * $new_grade->add();
 * 
 * @abstract This file is part of curriculum - http://www.joachimdieterich.de
 * @package core
 * @filename grade.class.php
 * @copyright 2013 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2013.03.10 10:58
 * @license 
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
class Grade {
    /**
     * ID of Grade
     * @var int
     */
    public $id = null;
    /**
     * Name of Grade
     * @var string
     */
    public $grade = null; 
    /**
     * Description of Grade
     * @var string
     */
    public $description = null; 
    /**
     * Timestamp when Grade was created
     * @var timestamp
     */
    public $creation_time = null; 
    /**
     * ID of User who created this Grade
     * @var int
     */
    public $creator_id =null; 
    /**
     * ID of institution to which Grade belongs to
     * @var int
     */
    public $institution_id = null; 
   
    
    /**
     * add grade
     * @return mixed 
     */
    public function add(){
        $query = sprintf("SELECT COUNT(id) FROM grade WHERE grade = '%s'",
                                    mysql_real_escape_string($this->grade));
        $result = mysql_query($query);
        list($count) = mysql_fetch_row($result);
        if($count >= 1) { 
            return 'Diesen Klassennamen gibt es bereits.';
        } else {
            $query = sprintf("INSERT INTO grade (grade,description,creator_id,institution_id) VALUES ('%s','%s','%s','%s')",
                                            mysql_real_escape_string($this->grade),
                                            mysql_real_escape_string($this->description),
                                            mysql_real_escape_string($this->creator_id),
                                            mysql_real_escape_string($this->institution_id));
            return mysql_query($query);		
        }
    }
    
    /**
     * Update grade
     * @return boolean 
     */
    public function update(){
        $query = sprintf("UPDATE grade 
                SET grade = '%s', description = '%s',
                creator_id = '%s'
                WHERE id = '%s'",
                mysql_real_escape_string($this->grade),
                mysql_real_escape_string($this->description),
                mysql_real_escape_string($this->creator_id),
                mysql_real_escape_string($this->id));
        return mysql_query($query);
    }
    
    /**
     * Delete grade
     * @return mixed 
     */
    public function delete(){
        $query = sprintf("SELECT id 
                          FROM curriculum
                          WHERE grade_id = '%s'",
                          mysql_real_escape_string($this->id));
        $result = mysql_query($query);
        if ($result && mysql_num_rows($result)){
            return false;
        } else {
            $query = sprintf("DELETE FROM grade WHERE id='%s'",
                            mysql_real_escape_string($this->id));
            return mysql_query($query);
        } 
    } 
    
    /**
     * Load Grade with id $this->id 
     */
    public function load(){
        $query = sprintf("SELECT * FROM grade WHERE id='%s'",
                        mysql_real_escape_string($this->id));
        $result = mysql_query($query);
        $row = mysql_fetch_assoc($result);
        $this->grade       = $row["grade"];
        $this->description = $row["description"];
        
    }
    
    /**
     * Get all availible Grades of current institution
     * @return array of Grade objects 
     */
    public function getGrades(){
        $grades = array();                      //Array of grades
        $query = sprintf("SELECT * FROM grade WHERE institution_id IN ('%s')",
                        mysql_real_escape_string(implode(",", $this->institution_id)));
        $result = mysql_query($query);
        if ($result && mysql_num_rows($result)) {
            while($row = mysql_fetch_assoc($result)) { 
                    $this->id                   = $row['id'];
                    $this->grade                = $row['grade'];
                    $this->description          = $row['description'];
                    $this->creation_times       = $row['creation_time'];
                    $this->creator_id           = $row['creator_id'];
                    $this->institution_id       = $row['institution_id'];
                    
                    $grades[] = clone $this;        //it has to be clone, to get the object and not the reference
            } 
            
            return $grades;
        } else {return $result;}
    }
    
    public function dedicate(){ // only use during install
        $query = sprintf("UPDATE grade SET institution_id = '%s', creator_id = '%s'",
                                            mysql_real_escape_string($this->institution_id),
                                            mysql_real_escape_string($this->creator_id));
        return mysql_query($query);
    }
}
?>