<?php
/**
 * Roles object can add, update, delete and get data from user_roles db
 * 
 * @abstract This file is part of curriculum - http://www.joachimdieterich.de
 * @package core
 * @filename roles.class.php
 * @copyright 2013 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2013.07.26 09:44
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
class Roles {
    /**
     * ID of Role
     * @var int
     */
    public $id = null;
    /**
     * role id
     * @var int 
     */
    public $role_id = null; 
    /**
     * Name of role
     * @var string
     */
    public $role = null; 
    /**
     * Description of role
     * @var string
     */
    public $description = null; 
    /**
     * Timestamp when role was created
     * @var timestamp
     */
    public $creation_time = null; 
    /**
     * ID of User who created this role
     * @var int
     */
    public $creator_id =null; 
    /**
     * array of role capabilities
     * @var array 
     */
    public $capabilities = array(); 
    
    /**
     * add role
     * @return mixed 
     */
    public function add(){
        $query = sprintf("SELECT MAX(role_id) FROM user_roles");
        $result = mysql_query($query);
        list($max) = mysql_fetch_row($result);
        $this->role_id = $max + 1; 
        $query = sprintf("INSERT INTO user_roles (role_id, role, description,creator_id) 
                                                    VALUES ('%s', '%s', '%s', '%s')",
                        mysql_real_escape_string($this->role_id),
                        mysql_real_escape_string($this->role),
                        mysql_real_escape_string($this->description),
                        mysql_real_escape_string($this->creator_id));
        $write_role = mysql_query($query);
        
        foreach($this->capabilities as $key=>$value) {
            foreach ($value as $v_key => $v_value) {
            $query = sprintf("INSERT INTO role_capabilities (role_id, capability, permission, creator_id) 
                                VALUES ('%s', '%s', %s, '%s')",
                            mysql_real_escape_string($this->role_id),
                            mysql_real_escape_string($v_key),
                            mysql_real_escape_string($v_value),
                            mysql_real_escape_string($this->creator_id));
            $write_role_capabilities = mysql_query($query);
            }
        }
        
        if ($write_role == true AND $write_role_capabilities == true){
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Update role
     * @return boolean 
     */
    public function update(){
         $query = sprintf("UPDATE user_roles SET role = '%s', description = '%s',creator_id = '%s'
                            WHERE role_id = '%s'",
                        mysql_real_escape_string($this->role),
                        mysql_real_escape_string($this->description),
                        mysql_real_escape_string($this->creator_id),
                        mysql_real_escape_string($this->role_id));
        $update_role = mysql_query($query);
        //object_to_array($this->capabilities);
        foreach($this->capabilities as $key=>$value) {
            foreach ($value as $v_key => $v_value) {
            $query = sprintf("SELECT role_id FROM role_capabilities WHERE role_id = '%s' AND capability = '%s'", 
                            mysql_real_escape_string($this->role_id),
                            mysql_real_escape_string($v_key));
            $result = mysql_query($query);
            list($role_id) = mysql_fetch_row($result);
            
                if (isset($role_id)){       
                    $query = sprintf("UPDATE role_capabilities SET permission= %s, creator_id = '%s'
                                        WHERE role_id = '%s' AND capability = '%s'",
                                    mysql_real_escape_string($v_value),
                                    mysql_real_escape_string($this->creator_id),
                                    mysql_real_escape_string($this->role_id),
                                    mysql_real_escape_string($v_key));
                    $update_role_capabilities = mysql_query($query);
                } else {
                    $query = sprintf("INSERT INTO role_capabilities (role_id, capability, permission, creator_id) 
                                VALUES ('%s', '%s', %s, '%s')",
                            mysql_real_escape_string($this->role_id),
                            mysql_real_escape_string($v_key),
                            mysql_real_escape_string($v_value),
                            mysql_real_escape_string($this->creator_id));
                    $update_role_capabilities = mysql_query($query);
                }
            }
        }
        
        if ($update_role == true AND $update_role_capabilities == true){
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Delete role
     * @return mixed 
     */
    public function delete(){
        $query = sprintf("DELETE FROM user_roles WHERE role_id='%s'",
                        mysql_real_escape_string($this->role_id));
        $delete_role =  mysql_query($query);
        $query = sprintf("DELETE FROM role_capabilities WHERE role_id='%s'",
                        mysql_real_escape_string($this->role_id));
        $delete_role_capabilities = mysql_query($query);
       if ($delete_role == true AND $delete_role_capabilities == true){
            return true;
        } else {
            return false;
        } 
    } 
    
    /**
     * Load Grade with id $this->id 
     */
    public function load(){
        $query = sprintf("SELECT * FROM user_roles WHERE role_id='%s'",
                        mysql_real_escape_string($this->role_id));
        $result = mysql_query($query);
        $row = mysql_fetch_assoc($result);
        $this->role_id      = $row["role_id"];
        $this->role         = $row["role"];
        $this->description  = $row["description"];
        $this->creation_time= $row["creation_time"];
        $this->creator_id   = $row["creator_id"];  
    }
    
    /**
     * get user roles from db
     * @return array of roles |boolean 
     */
    public function get(){
        $query = "SELECT * FROM user_roles";
        $result = mysql_query($query);
        while ($row = mysql_fetch_assoc($result)) {
            $this->role_id      = $row["role_id"];
            $this->role         = $row["role"];
            $this->description  = $row["description"];
            $roles[] = clone $this; 
        }
    
        if (isset($roles)){
            return $roles; 
        } else {return false;}
    }
    
    /**
    * function used during the install process to set up creator id to new admin
    * @return boolean
    */
    public function dedicate(){ // only use during install
        $query = sprintf("UPDATE user_roles SET creator_id = '%s'",
                                            mysql_real_escape_string($this->creator_id));
        $dedicate_roles =  mysql_query($query);
        $query = sprintf("UPDATE role_capabilities SET creator_id = '%s'",
                                            mysql_real_escape_string($this->creator_id));
        $dedicate_capabilities =  mysql_query($query);
        
        if ($dedicate_roles  == true AND $dedicate_capabilities == true){
            return true;
        } else {
            return false;
        }
    }
}
?>