<?php
/**
 * schooltype object can add, update, delete and get data from schooltype db
 * 
 * 
 * @abstract This file is part of curriculum - http://www.joachimdieterich.de
 * @package core
 * @filename schooltype.class.php
 * @copyright 2013 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2013.06.09 20:45
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
class Schooltype {
    /**
     * id of Schooltype
     * @var int
     */
    public $id = null;
    /**
     * Name of schooltype
     * @var string
     */
    public $schooltype = null; 
    /**
     * Description of schooltype
     * @var string
     */
    public $description = null; 
    /**
     * id of country
     * @var int 
     */
    public $country_id = null; 
    /**
     * id of state
     * @var int 
     */
    public $state_id = null; 
    /**
     * timestamp when schooltype was created
     * @var timestamp
     */
    public $creation_time = null; 
    /**
     * ID of User who created this schooltype
     * @var int
     */
    public $creator_id =null; 

   
    
    /**
     * add schooltype
     * @return mixed 
     */
    public function add(){
        $query = sprintf("SELECT COUNT(id) FROM schooltype WHERE schooltype = '%s'",
                                    mysql_real_escape_string($this->schooltype));
        $result = mysql_query($query);
        list($count) = mysql_fetch_row($result);
        if($count >= 1) { 
            return false;
        } else {
            $query = sprintf("INSERT INTO schooltype (schooltype, description, country_id, state_id, creator_id) 
                                VALUES ('%s','%s','%s','%s','%s')",
                                            mysql_real_escape_string($this->schooltype),
                                            mysql_real_escape_string($this->description),
                                            mysql_real_escape_string($this->country_id),
                                            mysql_real_escape_string($this->state_id),
                                            mysql_real_escape_string($this->creator_id));
            if (mysql_query($query)){
                return mysql_insert_id(); //gibt die ID zurück
            } else return false; 
            
        }
    }
    
    /**
     * Update schooltype
     * @return boolean 
     */
    public function update(){
        $query = sprintf("UPDATE schooltype 
                SET schooltype = '%s', description = '%s', country_id = '%s', state_id = '%s',
                creator_id = '%s'
                WHERE id = '%s'",
                mysql_real_escape_string($this->schooltype),
                mysql_real_escape_string($this->description),
                mysql_real_escape_string($this->country_id),
                mysql_real_escape_string($this->state_id),
                mysql_real_escape_string($this->creator_id),
                mysql_real_escape_string($this->id));
        return mysql_query($query);
    }
    
    /**
     * Delete schooltype
     * @return mixed 
     */
    public function delete(){
        $query = sprintf("SELECT id 
                          FROM curriculum
                          WHERE schooltype_id = '%s'",
                          mysql_real_escape_string($this->id));
        $result = mysql_query($query);
        if ($result && mysql_num_rows($result)){
            return false;
        } else {
            $query = sprintf("DELETE FROM schooltype WHERE id='%s'",
                            mysql_real_escape_string($this->id));
            return mysql_query($query);
        } 
    } 
    
    /**
     * Load schooltype with id $this->id 
     */
    public function load(){
        $query = sprintf("SELECT * FROM schooltype WHERE id='%s'",
                        mysql_real_escape_string($this->id));
        $result = mysql_query($query);
        $row = mysql_fetch_assoc($result);
        $this->schooltype       = $row["schooltype"];
        $this->description      = $row["description"];
        $this->country_id       = $row["country_id"];
        $this->state_id         = $row["state_id"];
        $this->creation_time    = $row["creation_time"];
        $this->creator_id       = $row["creator_id"];
        
    }
    
    /**
     * Get all availible schooltypes 
     * @return array of schooltype objects 
     */
    public function getSchooltypes(){
        $schooltypes = array();                      //Array of schooltypes
        $query = "SELECT * FROM schooltype";
        $result = mysql_query($query);
        if ($result && mysql_num_rows($result)) {
            while($row = mysql_fetch_assoc($result)) { 
                    $this->id                   = $row['id'];
                    $this->schooltype       = $row["schooltype"];
                    $this->description      = $row["description"];
                    $this->country_id       = $row["country_id"];
                    $this->state_id         = $row["state_id"];
                    $this->creation_time    = $row["creation_time"];
                    $this->creator_id       = $row["creator_id"];
                    
                    $schooltypes[] = clone $this;        //it has to be clone, to get the object and not the reference
            } 
            
            return $schooltypes;
        } else {return $result;}
    }
    
    public function dedicate(){ // only use during install
        $query = sprintf("UPDATE schooltype SET creator_id = '%s'",
                                            mysql_real_escape_string($this->creator_id));
        return mysql_query($query);
    }
}
?>