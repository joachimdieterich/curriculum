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
        global $USER;
        if (checkCapabilities('role:add', $USER->role_id)){
            $db = DB::prepare('SELECT MAX(role_id) as max FROM user_roles');
            $db->execute();
            $result = $db->fetchObject();
            $this->role_id = $result->max + 1; 
            $db = DB::prepare('INSERT INTO user_roles (role_id, role, description,creator_id) VALUES (?,?,?,?)');
            $write_role = $db->execute(array($this->role_id, $this->role, $this->description, $this->creator_id));
            foreach($this->capabilities as $key=>$value) {
                foreach ($value as $v_key => $v_value) {
                    $db = DB::prepare('INSERT INTO role_capabilities (role_id, capability, permission, creator_id) VALUES (?, ?, ?, ?)');
                    $write_role_capabilities = $db->execute(array($this->role_id, $v_key, $v_value, $this->creator_id));
                }
            }
            if ($write_role == true AND $write_role_capabilities == true){
                return true;
            } else {
                return false;
            }
        }
    }
    
    /**
     * Update role
     * @return boolean 
     */
    public function update(){
        global $USER;
        if (checkCapabilities('role:update', $USER->role_id)){
            $db = DB::prepare('UPDATE user_roles SET role = ?, description = ?,creator_id = ? WHERE role_id = ?');
            $update_role = $db->execute(array($this->role, $this->description, $this->creator_id, $this->role_id));
            //object_to_array($this->capabilities);
            foreach($this->capabilities as $key=>$value) {
                foreach ($value as $v_key => $v_value) {
                $db = DB::prepare('SELECT role_id FROM role_capabilities WHERE role_id = ? AND capability = ?');
                $db->execute(array($this->role_id, $v_key));
                $result = $db->fetchObject();            
                    if (isset($result->role_id)){       
                        $db = DB::prepare('UPDATE role_capabilities SET permission= '.$v_value.', creator_id = ?
                                            WHERE role_id = ? AND capability = ?');
                        $update_role_capabilities = $db->execute(array($this->creator_id, $this->role_id, $v_key));
                    } else {
                        $db = DB::prepare('INSERT INTO role_capabilities (role_id, capability, permission, creator_id) 
                                            VALUES (?, ?, ?, ?)');
                        $update_role_capabilities = $db->execute(array($this->role_id, $v_key, $v_value, $this->creator_id));
                    }
                }
            }

            if ($update_role == true AND $update_role_capabilities == true){
                return true;
            } else {
                return false;
            }
        }
    }
    
    /**
     * Delete role
     * @return mixed 
     */
    public function delete($creator_id = null){
        global $USER;
        if (checkCapabilities('role:delete', $USER->role_id)){
           /* if ($creator_id != null) { // if function is called by request-php --> required by checkCapabilities()
                $user = new USER();

                $user->load('id', $creator_id);
                $role_id = $user->role_id;
            } else {
                $role_id = $USER->role-id;
            } */
            $db = DB::prepare('DELETE FROM user_roles WHERE role_id = ?');
            $delete_role =  $db->execute(array($this->role_id));
            $db = DB::prepare('DELETE FROM role_capabilities WHERE role_id= ?');
            $delete_role_capabilities = $db->execute(array($this->role_id));
        if ($delete_role == true AND $delete_role_capabilities == true){
                return true;
            } else {
                return false;
            } 
        }
    } 
    
    /**
     * Load Grade with id $this->id 
     */
    public function load(){
        $db = DB::prepare('SELECT * FROM user_roles WHERE role_id = ?');
        $db->execute(array($this->role_id)); 
        $result = $db->fetchObject();
        $this->role_id      = $result->role_id;
        $this->role         = $result->role;
        $this->description  = $result->description;
        $this->creation_time= $result->creation_time;
        $this->creator_id   = $result->creator_id;  
    }
    
    /**
     * get user roles from db
     * @return array of roles |boolean 
     */
    public function get(){
        $db = DB::prepare('SELECT * FROM user_roles');
        $db->execute();
        while ($result = $db->fetchObject()) {
            $this->role_id      = $result->role_id;
            $this->role         = $result->role;
            $this->description  = $result->description;
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
        $db = DB::prepare('UPDATE user_roles SET creator_id = ?');
        $dedicate_roles =  $db->execute(array($this->creator_id));
        $db = DB::prepare('UPDATE role_capabilities SET creator_id = ?');
        $dedicate_capabilities =  $db->execute(array($this->creator_id));
        
        if ($dedicate_roles  == true AND $dedicate_capabilities == true){
            $db = DB::prepare('DELETE FROM user_roles WHERE role_id = -1');
            $dedicate_roles =  $db->execute();
            $db = DB::prepare('DELETE FROM role_capabilities WHERE role_id = -1');
            $dedicate_roles =  $db->execute();
            return true;
        } else {
            return false;
        }
    }
}
?>