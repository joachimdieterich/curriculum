<?php
if (0 > version_compare(PHP_VERSION, '5')) {
    die('This file was generated for PHP 5');
}

/** 
 * This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package core
 * @filename user.class.php
 * @copyright 2013 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2013.05.03 21:21
 * @license
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
 * 
 */
  
class User {
    /**
     * user id
     * @var int 
     */
    public $id = null; 
    /**
     * username
     * @var string 
     */
    public $username = null; 
    /**
     * password md5
     * @var string
     */
    public $password = null;
    /**
     * role id
     * @var int 
     */
    public $role_id = 0;    //Standard Role !important
    /**
     * role name
     * @var string 
     */
    public $role_name = null; 
    /**
     * timestamp of last login
     * @var timestamp
     */
    public $last_login = null; 
    /**
     * email adress
     * @var string
     */
    public $email = null; 
    /**
     * status
     * @var int 
     */
    public $confirmed = null; 
    /**
     * firstname
     * @var string 
     */
    public $firstname = null; 
    /**
     * lastname
     * @var string 
     */
    public $lastname = null; 
    /**
     * postalcode
     * @var string 
     */
    public $postalcode = null; 
    /**
     * city
     * @var string 
     */
    public $city = null; 
    /**
     * state
     * @var string
     */
    public $state = null; 
    /**
     * id of state
     * @var int
     */
    public $state_id = null; 
    /**
     * country
     * @var string 
     */
    public $country = null; 
    /**
     * id of country
     * @var type 
     */
    public $country_id = null; 
    /**
     * filename of avatar
     * @var string
     */
    public $avatar = 'noprofile.jpg'; 
    /**
     * user language
     * @var string
     */
    public $language = null; 
    /**
     * timestamp of creation
     * @var timestamp
     */
    public $creation_time = null; 
    /**
     * timestamp of creator user
     * @var int
     */
    public $creator_id = null; 
    /**
     * Array of institutions
     * @var array 
     */
    public $institutions = array();
    /**
     * array of enrolments
     * @var array
     */
    
    public $enrolments = array();
    /**
     * role capabilities 
     * @var array [capability][read/write]
     */
    public $capabilities = array();
    
    /**
     * User class constructor
     * @param mixed $user_value 
     */
    public function __construct($user_value = null) {
        if ($user_value != null){
            $this->load('id', $user_value); //load user by id
        }
    }
    
    /**
     * Load User
     * @param string $key
     * @param string $user_value 
     */
    public function load($key, $user_value) {
        if ($key == 'username'){
            $query = sprintf("SELECT * FROM users WHERE UPPER(%s) = UPPER('%s')",
                                            mysql_real_escape_string($key),
                                            mysql_real_escape_string($user_value));
            $result = mysql_query($query);
        } else {
            $query = sprintf("SELECT * FROM users WHERE %s = '%s'",
                                            mysql_real_escape_string($key),
                                            mysql_real_escape_string($user_value));
            $result = mysql_query($query); 
        }
        
        $this->id                = mysql_result($result, 0, "id");
        $this->username          = mysql_result($result, 0, "username");
        $this->password          = mysql_result($result, 0, "password");
        $this->firstname         = mysql_result($result, 0, "firstname"); 
        $this->lastname          = mysql_result($result, 0, "lastname"); 
        $this->email             = mysql_result($result, 0, "email"); 
        $this->postalcode        = mysql_result($result, 0, "postalcode"); 
        $this->city              = mysql_result($result, 0, "city"); 
        $this->state_id          = mysql_result($result, 0, "state_id"); 
        
        $query = sprintf("SELECT state FROM state WHERE id = '%s'",
                                            mysql_real_escape_string($this->state_id));
        $state_result = mysql_query($query);
        if ($state_result && mysql_num_rows($state_result)){
        $this->state             = mysql_result($state_result, 0, "state");
        }
        $this->country_id        = mysql_result($result, 0, "country_id");
        $query = sprintf("SELECT de FROM countries WHERE id = '%s'",
                                            mysql_real_escape_string($this->country_id));
        $country_result = mysql_query($query);
        if ($country_result && mysql_num_rows($country_result)){
        $this->country           = mysql_result($country_result, 0, "de");
        }
        $this->confirmed         = mysql_result($result, 0, "confirmed"); 
        $this->last_login        = mysql_result($result, 0, "last_login"); 
        $this->role_id           = mysql_result($result, 0, "role_id"); 
        $this->avatar            = mysql_result($result, 0, "avatar");
        $this->creation_time     = mysql_result($result, 0, "creation_time");
        $this->creator_id        = mysql_result($result, 0, "creator_id");
        $role = new Roles(); 
        $role->role_id = $this->role_id;
        $role->load(); 
        $this->role_name         = $role->role;
        $this->enrolments        = $this->get_user_enrolments();
        
        $query = sprintf("SELECT * FROM config_user WHERE user_id = '%s'",
                                            mysql_real_escape_string($this->id));
        $result = mysql_query($query);
        $this->language          = mysql_result($result, 0, "user_language");
        $this->acc_days          = mysql_result($result, 0, "user_acc_days");
        $this->paginator_limit   = mysql_result($result, 0, "user_paginator_limit");
        
        /**
         * ! users can be enroled in more than one institution 
         */
        $query = sprintf("SELECT id, institution
                      FROM institution
                      WHERE id = ANY (SELECT institution_id FROM institution_enrolments WHERE user_id = '%s')",
                            mysql_real_escape_string($this->id));
        $result = mysql_query($query);
        if ($result && mysql_num_rows($result)){
            $this->institutions['id'][] = mysql_result($result, 0, "id");
            $this->institutions['institution'][] = mysql_result($result, 0, "institution");
        } else {
            $this->institutions[] = NULL;
        }    
        
        /**
         * get capabilities 
         */
        $capabilitiy = new Capability();
        $this->capabilities = $capabilitiy->getCapabilities($this->role_id);
    }
    
    /**
    * add User
    * @return mixed 
    */
    public function add(){ 
        global $USER; 
        if (checkCapabilities('user:addUser', $USER->role_id)){
            $query = sprintf("SELECT COUNT(id) FROM users WHERE UPPER(username) = UPPER('%s')",
                                                mysql_real_escape_string($this->username));
            $result = mysql_query($query);
            list($count) = mysql_fetch_row($result);
            if($count >= 1) { 
                    return false;
            } else {
                $query = sprintf("INSERT INTO users (username,firstname,lastname,email,postalcode,city,state_id,country_id,avatar,password,role_id,confirmed,creator_id) 
                                                VALUES('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')",
                                                    mysql_real_escape_string($this->username),
                                                    mysql_real_escape_string($this->firstname),
                                                    mysql_real_escape_string($this->lastname),
                                                    mysql_real_escape_string($this->email),
                                                    mysql_real_escape_string($this->postalcode),
                                                    mysql_real_escape_string($this->city),
                                                    mysql_real_escape_string($this->state_id),
                                                    mysql_real_escape_string($this->country_id),
                                                    mysql_real_escape_string($this->avatar),
                                                    mysql_real_escape_string(md5($this->password)),
                                                    mysql_real_escape_string($this->role_id),
                                                    mysql_real_escape_string($this->confirmed), 
                                                    mysql_real_escape_string($this->creator_id)); 
                if (mysql_query($query)){
                        $query = sprintf("SELECT id from users WHERE UPPER(username) = UPPER('%s')",
                                            mysql_real_escape_string($this->username));
                        $result = mysql_query($query);
                        $this->id = mysql_result($result, 0, "id"); 

                        $user_config = new Config();    //generate Config
                        $user_config->add('user', $this->id);

                        return $this->id;          
                } else { 
                    return false; 
                }
            }
        }
    }
    
    /**
     * update User
     * @return boolean
     */
    public function update() {
        global $USER; 
        if(checkCapabilities('user:updateUser', $USER->role_id) OR $_POST['userID'] == $USER->id){ //2. Bedingung für Änderung des eigenen Profils 
            $query = sprintf("UPDATE users 
                    SET username = '%s', firstname = '%s', lastname = '%s', email = '%s', postalcode = '%s', city = '%s', state_id = '%s', country_id = '%s', avatar = '%s' 
                    WHERE id = '%s'",
                                    mysql_real_escape_string($this->username),
                                    mysql_real_escape_string($this->firstname),
                                    mysql_real_escape_string($this->lastname),
                                    mysql_real_escape_string($this->email),
                                    mysql_real_escape_string($this->postalcode),
                                    mysql_real_escape_string($this->city),
                                    mysql_real_escape_string($this->state_id),
                                    mysql_real_escape_string($this->country_id),
                                    mysql_real_escape_string($this->avatar),
                                    mysql_real_escape_string($this->id));
            return mysql_query($query);
        }
    }
    
    public function updateRole(){
        global $USER; 
        if(checkCapabilities('user:updateRole', $USER->role_id)){
            $query = sprintf("UPDATE users SET role_id = '%s' WHERE id='%s'",
                                    mysql_real_escape_string($this->role_id), 
                                    mysql_real_escape_string($this->id));
            if (mysql_query($query)){
                $role = new Roles(); 
                $role->role_id = $this->role_id;
                $role->load(); 
                $this->role_name         = $role->role;
                return true;
            } else {return false;}
        }
    }
    /**
     * delete User
     * @return boolean 
     */
    public function delete(){
        global $USER; 
        if(checkCapabilities('user:delete', $USER->role_id)){
            $query = sprintf("DELETE FROM users WHERE id='%s'",
                                    mysql_real_escape_string($this->id));
            if (mysql_query($query)) {
                $user_config = new Config(); 
                $user_config->delete('user', $this->id);
                $query = sprintf("DELETE FROM institution_enrolments WHERE user_id = '%s'", 
                                mysql_real_escape_string($this->id));
                mysql_query($query);
                return true; 
            } else {return false;} 
        }   
    }
    /**
     * change password
     * @param string $password
     * @return boolean
     */
    public function changePassword($password) {
        global $USER; 
        if(checkCapabilities('user:changePassword', $USER->role_id)){
            $query = sprintf("UPDATE users SET password = '%s', confirmed = 1 WHERE UPPER(username) = UPPER('%s')",
                                                mysql_real_escape_string($password),
                                                mysql_real_escape_string($this->username));
            return mysql_query($query);
        }
    }
  
    /**
     * Get password
     * @param string $table
     * @param string $format
     * @return string 
     */
    public function getPassword($table='users', $format='md5') {
        global $USER; 
        if(checkCapabilities('user:getPassword', $USER->role_id)){
            $query = sprintf("SELECT password FROM %s WHERE UPPER(username) = UPPER('%s')",
                                                mysql_real_escape_string($table),
                                                mysql_real_escape_string($this->username));
            $result = mysql_query($query);

            if ($format == 'md5'){
                return  md5(mysql_result($result, 0, "password"));
            } else {
                return  mysql_result($result, 0, "password");
            }  
        }
    }
    
    /**
     * get group members (of all groups in which current user is enroled)
     * @return array 
     */
    public function getGroupMembers($dependency = null, $id = null) {
        global $USER; 
        if(checkCapabilities('user:getGroupMembers', $USER->role_id)){
            switch ($dependency) {
                case 'group':   $query = sprintf("SELECT user_id FROM groups_enrolments WHERE group_id = '%s'",
                                                        mysql_real_escape_string($id));
                                $result =  mysql_query($query);  
                                while($row = mysql_fetch_assoc($result)) { 
                                    $group_members[] =  $row["user_id"];
                                }
                                return $group_members;

                    break;

                default:        $query = sprintf("SELECT DISTINCT usr.id, usr.firstname, usr.lastname, usr.username 
                                        FROM users AS usr, groups_enrolments AS cle 
                                        WHERE cle.group_id IN (SELECT DISTINCT group_id FROM groups_enrolments WHERE user_id = '%s')
                                        AND usr.id = cle.user_id", 
                                        mysql_real_escape_string($this->id)); //??? WHERE Mailempfang erlaubt!!!
                                $result = mysql_query($query);

                                while($row = mysql_fetch_assoc($result)) { 
                                        $class_members["id"][]     = $row["id"];  //??? besser als object realisieren
                                        $class_members["user"][]   = $row['firstname'].' '.$row['lastname'].' ('.$row['username'].')'; 
                                } 
                                if (isset($class_members)){
                                    return $class_members;
                                } else return false; 
                    break;
            }
        }
    }  
    
    /**
     * List of new Users since last login of current user
     * @return array of object
     */
    public function newUsers($id){
        global $USER; 
        if(checkCapabilities('user:listNewUsers', $USER->role_id)){
            $query = sprintf("SELECT usr.*, rol.role 
                        FROM users AS usr, user_roles AS rol
                        WHERE usr.role_id = rol.role_id AND usr.creation_time > (SELECT last_login FROM users WHERE id = '%s')",
                                mysql_real_escape_string($id));
            $result = mysql_query($query);
            if ($result && mysql_num_rows($result)){
                while($row = mysql_fetch_assoc($result)) { 
                        $this->id                = $row['id'];
                        $this->username          = $row['username'];
                        $this->password          = $row['password'];
                        $this->firstname         = $row['firstname']; 
                        $this->lastname          = $row['lastname']; 
                        $this->email             = $row['email']; 
                        $this->postalcode        = $row['postalcode'];
                        $this->city              = $row['city']; 
                        $this->state_id          = $row['state_id'];
                        $this->country_id        = $row['country_id'];
                        $this->confirmed         = $row['confirmed']; 
                        $this->last_login        = $row['last_login']; 
                        $this->role_id           = $row['role_id']; 
                        $this->avatar            = $row['avatar'];
                        $this->creation_time     = $row['creation_time'];
                        $this->creator_id        = $row['creator_id'];
                        $role = new Roles(); 
                        $role->role_id           = $this->role_id;
                        $role->load(); 
                        $this->role_name         = $role->role;
                        $users[] = clone $this; 
                }
                return $users;          
            } else {
                return false;   
            }   //keine neuen Benutzer
        }
    }
    
    /**
     * enrole user to institution
     * @param int $institution_id
     * @return boolean 
     */
    public function enroleToInstitution($institution_id){
        global $USER; 
        if(checkCapabilities('user:enroleToInstitution', $USER->role_id)){
            $query = sprintf("INSERT INTO institution_enrolments (institution_id,user_id,creator_id) 
                                        VALUES('%s','%s','%s')",
                                        mysql_real_escape_string($institution_id), 
                                        mysql_real_escape_string($this->id),
                                        mysql_real_escape_string($this->creator_id));
            return mysql_query($query);
        }
    }
    
    /**
     * enrole user to group
     * @param int $group_id
     * @param int $creator_id
     * @return boolean 
     */
    public function enroleToGroup($group_id, $creator_id){
        global $USER; 
        if(checkCapabilities('user:enroleToGroup', $USER->role_id)){
            $query = sprintf("SELECT count(id) FROM groups_enrolments WHERE group_id = '%s' AND user_id = '%s'",
                                                mysql_real_escape_string($group_id), 
                                                mysql_real_escape_string($this->id));
            $result = mysql_query($query);
            list($count) = mysql_fetch_row($result);
            if($count > 0) { 
            $query = sprintf("UPDATE groups_enrolments SET status = 1 WHERE group_id = '%s' AND user_id = '%s'",
                                                mysql_real_escape_string($group_id), 
                                                mysql_real_escape_string($this->id)); //Status 1 == eingeschrieben
            return mysql_query($query);
            } else {            
                $query = sprintf("INSERT INTO groups_enrolments 
                                                (status,group_id,user_id,creator_id) 
                                                VALUES (1,'%s','%s','%s')",
                                                mysql_real_escape_string($group_id), 
                                                mysql_real_escape_string($this->id),
                                                mysql_real_escape_string($creator_id)); //Status 1 == eingeschrieben
            return mysql_query($query);
            }
        }
    }
    
    /**
     * expel user from group
     * @param int $group_id
     * @return boolean 
     */
    public function expelFromGroup($group_id){
        global $USER; 
        if(checkCapabilities('user:expelFromGroup', $USER->role_id)){
            $query = sprintf("SELECT COUNT(id) FROM groups_enrolments WHERE group_id = '%s' AND user_id = '%s'",
                                                mysql_real_escape_string($group_id), 
                                                mysql_real_escape_string($this->id));
            $result = mysql_query($query);
            list($count) = mysql_fetch_row($result);
            if($count >= 1) {
            $query = sprintf("UPDATE groups_enrolments SET status = 0, expel_time = NOW()
                                    WHERE user_id ='%s'", 
                                    mysql_real_escape_string($this->id)); //Status 0 Benutzer wurde ausgeschrieben
            return mysql_query($query);
            }
        }
    }
    
    /** 
     * import csv user list
     * @global object $CFG
     * @param int $institution_id
     * @param file $import_file
     * @param string $delimiter
     * @return boolean 
     */
    public function import($institution_id, $import_file, $delimiter = ';'){
        global $CFG, $USER;
        if(checkCapabilities('user:import', $USER->role_id)){
            $row = 1;   //row counter
            ini_set("auto_detect_line_endings", true);
            if (($handle = fopen($import_file, "r")) !== FALSE) {
                while (($data = fgetcsv($handle, 1000, $delimiter)) !== FALSE) {
                        $num = count($data);        
                    if ($row == 1) {	// Hier werden die Felder verknüpft.
                        for ($c=0; $c < $num; $c++) {
                            if ($data[$c] == "username")    {$username_position       = $c;}
                            if ($data[$c] == "password")    {$password_position       = $c;}
                            if ($data[$c] == "role_id")     {$role_id_position        = $c;}
                            if ($data[$c] == "email")       {$email_position          = $c;}
                            if ($data[$c] == "confirmed")   {$confirmed_position      = $c;}
                            if ($data[$c] == "firstname")   {$firstname_position      = $c;}
                            if ($data[$c] == "lastname")    {$lastname_position       = $c;}
                            if ($data[$c] == "postalcode")  {$postalcode_position     = $c;}
                            if ($data[$c] == "city")        {$city_position           = $c;}
                            if ($data[$c] == "state_id")       {$state_position          = $c;}
                            if ($data[$c] == "country_id")     {$country_position        = $c;}
                            if ($data[$c] == "avatar")      {$avatar_position         = $c;}
                        }    
                    }
                    $row++; //Tielzeile überspringen
                    if ($row > 2) {	
                        $this->role_id = 0; //reset role id to avoid wrong permissions
                        if (!isset($username_position))       {$this->username   = '';}                  else {$this->username   = $data[$username_position];}
                        if (!isset($firstname_position))      {$this->firstname  = '';}                  else {$this->firstname  = $data[$firstname_position];}
                        if (!isset($lastname_position))       {$this->lastname   = '';}                  else {$this->lastname   = $data[$lastname_position];}
                        if (!isset($email_position))          {$this->email      = '';}                  else {$this->email      = $data[$email_position];}
                        if (!isset($postalcode_position))     {$this->postalcode = '';}                  else {$this->postalcode = $data[$postalcode_position];}
                        if (!isset($city_position))           {$this->city       = '';}                  else {$this->city       = $data[$city_position];}
                        if (!isset($state_position))          {$this->state_id      = $CFG->standard_state;}                  else {$this->state_id      = $data[$state_position];}
                        if (!isset($country_position))        {$this->country_id    = $CFG->standard_country;}                  else {$this->country_id    = $data[$country_position];}
                        if (!isset($avatar_position))         {$this->avatar     = 'noprofile.jpg';}     else {$this->avatar     = $data[$avatar_position];}
                        if (!isset($password_position))       {$this->password   = 'Reis1834';}          else {$this->password   = $data[$password_position];} //??? standard password global regeln
                        if (!isset($role_id_position))        {$this->role_id    = $this->role_id;}      else {$this->role_id    = $data[$role_id_position];}
                        if (!isset($confirmed_position))      {$this->confirmed  = '3';}                 else {$this->confirmed  = $data[$confirmed_position];}

                        $validated_data = $this->validate();
                        if ($validated_data === true) {
                            $this->add();
                            $this->enroleToInstitution($institution_id);
                        } else {
                            $error[] = array('username' => $this->username, 
                                            'error'    => $validated_data); 
                        }
                    }
                }
            }
            fclose($handle);
            if (isset($error)){ //if there are any error messages
                return $error;
            } else {
                return true;    
            }
        } //capability
    }
    
    /**
     * get user list depending on $dependency
     * @param string $dependency
     * @param int $id
     * @return array of object 
     */
    public function userList($dependency = 'institution', $id = null){
        global $CFG, $USER;
        if(checkCapabilities('user:userList', $USER->role_id)){
            switch ($dependency) {
                case 'institution': if ($this->role_id == 3 OR $this->role_id == 2){ // 3 = Rolle Lehrer, 2 = Tutor //Bedingung Lehrer müssen in die Klasse eingeschrieben sein, oder sie erstellt haben    
                                    $query = sprintf("SELECT us.id
                                                    FROM users AS us
                                                    WHERE us.id = ANY (SELECT user_id FROM institution_enrolments 
                                                                    WHERE institution_id = ANY (SELECT institution_id FROM institution_enrolments 
                                                                                                WHERE user_id = '%s'))      
                                                    AND us.creator_id = '%s'
                                                    ORDER by us.lastname",
                                                    mysql_real_escape_string($this->id),
                                                    mysql_real_escape_string($this->id)); 
                                    } else if ($this->role_id == 4 OR $this->role_id == 1){ //4 = Institutions-Admin, 1= sidewide Admin
                                            $query = sprintf("SELECT us.id
                                                    FROM users AS us
                                                    WHERE us.id = ANY (SELECT user_id FROM institution_enrolments 
                                                                    WHERE institution_id = ANY (SELECT institution_id FROM institution_enrolments 
                                                                    WHERE user_id = '%s'))                                         
                                                    ORDER by us.lastname",
                                                    mysql_real_escape_string($this->id)); //Bisher werden nur Benutzer der Institution angezeigt, an der man angemeldet ist. ???Side-Admin muss aber alle sehen können 
                                    }

                                    break;
                case 'group':       $query = sprintf("SELECT us.id
                                                    FROM users AS us, groups_enrolments AS gre
                                                    WHERE gre.user_id = us.id 
                                                    AND gre.status = 1
                                                    AND gre.group_id = '%s'",
                                                    mysql_real_escape_string($id));
                                    break;
                case 'confirm':     if ($this->role_id = 1){
                                        $query = "SELECT us.id 
                                        FROM users AS us
                                        WHERE us.confirmed = 4";
                                    } else {
                                        $query = sprintf("SELECT us.id 
                                        FROM users AS us, institution_enrolments AS ine
                                        WHERE us.confirmed = 4
                                        AND ine.user_id = us.id
                                        AND ine.institution_id IN ('%s')",
                                        mysql_real_escape_string(implode(',',$this->institutions["id"])));
                                    }
                break; 
                default:
                    break;
            }


            $result = mysql_query($query);
            while($row = mysql_fetch_assoc($result)) { 
                    $this->load('id', $row['id']);
                    $users[] = clone $this;
            } 
            if (isset($users)){
                return $users;
            }
        }
    }
    
    /**
     * Reset Password
     * @return boolean 
     */
    public function resetPassword() {
        global $USER;
        if(checkCapabilities('user:resetPassword', $USER->role_id)){
            $query = sprintf("UPDATE users SET password = '%s', confirmed = '%s' WHERE id='%s'",
                                                    mysql_real_escape_string(md5($this->password)),
                                                    mysql_real_escape_string($this->confirmed),
                                                    mysql_real_escape_string($this->id));
            return mysql_query($query);       
        }
    }
    /**
     * Returns all Curricula in which the user is enroled. 
     * If User isn't enroled in any curriculum, return is false
     * @return array of object | boolean 
     */
    public function getCurricula() {
        $query = sprintf("SELECT cu.id, cu.curriculum, cu.description, fl.filename, su.subject, 
                                        gr.grade, sc.schooltype, st.state, co.de
                                            FROM curriculum AS cu, groups_enrolments AS ce, curriculum_enrolments AS cure,
                                            files AS fl, subjects AS su, grade AS gr, schooltype AS sc,
                                            state AS st, countries AS co
                                            WHERE cu.icon_id = fl.id
                                            AND cu.id = cure.curriculum_id
                                            AND cure.group_id = ce.group_id
                                            AND cu.grade_id = gr.grade
                                            AND cu.subject_id = su.id
                                            AND cu.schooltype_id = sc.id
                                            AND cu.state_id = st.id
                                            AND cu.country_id = co.id
                                            AND ce.user_id = '%s'
                                            AND ce.status = 1
                                            ORDER BY cu.curriculum ASC",
                                            mysql_real_escape_string($this->id));    
                $result = mysql_query($query);
                if ($result && mysql_num_rows($result)) {
                    while($row = mysql_fetch_assoc($result)) { 
                            $curricula[] = $row; 
                    }         
                }
        if (isset($curricula)) {
            return $curricula;      
        } else {
            return false; 
        }
    }
    
    /**
     * get groups of current user
     * @return array of object | boolean 
     */
    public function getGroups(){
       $query = sprintf("SELECT gp.*, gr.grade, yr.semester, ins.institution, us.username AS creator
                            FROM groups AS gp, groups_enrolments AS cle, grade AS gr, semester AS yr, institution AS ins, users AS us
                            WHERE cle.user_id = '%s'
                            AND cle.group_id = gp.id
                            AND gr.id = gp.grade_id 
                            AND yr.id = gp.semester_id 
                            AND ins.id = gp.institution_id 
                            AND us.id = gp.creator_id
                            AND cle.status = 1",
                            mysql_real_escape_string($this->id));

                $result = mysql_query($query);
                if ($result && mysql_num_rows($result)) {
                    while($row = mysql_fetch_assoc($result)) { 
                            $groups[] = $row;
                    } 
                }
       if (isset($groups)) {
            return $groups;      
        } else {
            return false; 
        }         
    }
    
    /**
     * Validate User
     * @return boolean 
     */
    public function validate($check_password = false){
        $gump = new Gump(); /* Validation */
        
        if ($check_password){ // if true -> validate password 
            $gump->validation_rules(array(
            'username'          => 'required|alpha_numeric|max_len,100|min_len,3',
            'firstname'         => 'required|alpha_numeric|max_len,100',
            'lastname'          => 'required|alpha_numeric|max_len,100',
            'email'             => 'required|valid_email',
            'password'          => 'required|max_len,100|min_len,6'
            ));
        } else {            // don't validate password
            $gump->validation_rules(array(
            'username'          => 'required|alpha_numeric|max_len,100|min_len,3',
            'firstname'         => 'required|alpha_numeric|max_len,100',
            'lastname'          => 'required|alpha_numeric|max_len,100',
            'email'             => 'required|valid_email'
            ));
        }
        
        /**
         * generate array for validation process
         */
        $user = array(
                'username'          => $this->username,
                'firstname'         => $this->firstname,
                'lastname'          => $this->lastname,
                'email'             => $this->email,
                'password'          => $this->password
                );
        
        $validated_data = $gump->run($user); 
        
        if($validated_data === false) {/* validation failed */
            return $gump->get_readable_errors();     
        } else {/* validation successful */
            return true;    
        }
    }
    
    /**
     * get users depending on course
     * @param string $dependency
     * @param int $id
     * @return array of object|boolean 
     */
    public function getUsers($dependency = null, $id = null){
        global $USER;
        if(checkCapabilities('user:getUsers', $USER->role_id)){
            switch ($dependency) {
                case 'course': $query = sprintf("SELECT DISTINCT us.*          
                                                    FROM users AS us
                                                    INNER JOIN groups_enrolments AS gr ON us.id = gr.user_id 
                                                    AND gr.group_id = ANY (SELECT group_id FROM curriculum_enrolments WHERE curriculum_id = '%s' AND status = 1 )
                                                    AND gr.group_id = ANY (SELECT id FROM groups 
                                                                            WHERE institution_id = ANY (SELECT institution_id FROM institution_enrolments 
                                                                                                            WHERE user_id = '%s'))                                                       
                                                    ORDER by us.lastname",
                                                    mysql_real_escape_string($id),                
                                                    mysql_real_escape_string($this->id),
                                                    mysql_real_escape_string($this->id));

                                    $result = mysql_query($query);
                                    if ($result && mysql_num_rows($result)) {
                                        while($row = mysql_fetch_assoc($result)) { 
                                            $this->id           = $row['id'];
                                            $this->load('id', $this->id);
                                            $users[] = clone $this; 
                                        }
                                    }
                    break;

                default:
                    break;
            }

                if (isset($users)) {
                    return $users; 
                } else {return false;}
        }
   }
   
   /**
    * get new users depending on institution
    * @param string $dependency
    * @param int $id
    * @return boolean 
    */
   public function getNewUsers($dependency = null, $id = null){
       global $USER;
       if(checkCapabilities('user:getNewUsers', $USER->role_id)){
            switch ($dependency) {
                case null:   $query   = sprintf("SELECT COUNT(id) FROM users WHERE confirmed = 4");
                                $result  = mysql_query($query);
                                $amount_of_new_user = mysql_fetch_array($result);
                                if ($amount_of_new_user[0]){
                                    return $amount_of_new_user[0];
                                } else {
                                    return false; 
                                }
                    break;
                case 'institution': $query = sprintf("SELECT COUNT(usr.id) FROM users AS usr, institution_enrolments AS ine
                                        WHERE usr.confirmed = 4
                                        AND usr.id = ine.user_id
                                        AND ine.institution_id IN ('%s')",
                                        mysql_real_escape_string(implode(',',$this->institutions["id"])));
                                $result  = mysql_query($query);
                                $amount_of_new_user = mysql_fetch_array($result);
                                if ($amount_of_new_user[0]){
                                    return $amount_of_new_user[0];
                                } else {
                                    return false; 
                                }
                    break;
                default:
                    break;
            }
       }  
   }
   
   /**
    * check login data
    * @return boolean 
    */
   public function checkLoginData(){
        $query   = sprintf("SELECT COUNT(id) FROM users WHERE UPPER(username) = UPPER('%s') AND password='%s'",
                                    mysql_real_escape_string($this->username),
                                    mysql_real_escape_string($this->password));
        $result  = mysql_query($query);
        $user_exists = mysql_fetch_array($result);
        if ($user_exists[0]){

            return true;
        } else {
            return false; 
        }
   }
   
   /**
    * set last login
    * @return boolean
    */
   public function setLastLogin(){
       $query   = sprintf("UPDATE users SET last_login = NOW() WHERE UPPER(username) = UPPER('%s') AND password = '%s'",
                                    mysql_real_escape_string($this->username),
                                    mysql_real_escape_string($this->password));
       return mysql_query($query);
   }
   
   /**
    * get users confirmed status
    * @return int
    */
   public function getConfirmed(){
       $query   = sprintf("SELECT confirmed FROM users WHERE UPPER(username) = UPPER('%s') AND password='%s'",
                                    mysql_real_escape_string($this->username),
                                    mysql_real_escape_string($this->password));
       $result  = mysql_query($query);
       $this->confirmed = mysql_result($result, 0, "confirmed");
       return $this->confirmed;
   }
   
   /**
    * confirm user
    * @param int $user_id
    * @return boolean 
    */
   public function confirmUser($user_id){
       global $USER;
       if(checkCapabilities('user:confirmUser', $USER->role_id)){
            $query = sprintf("UPDATE users SET confirmed = 1 WHERE id = '%s'",
                                        mysql_real_escape_string($user_id)); //confirmed 1 == freigeben
            return mysql_query($query);
       }
       
   }
   
    /**
     * get user enrolments
     * @return string | array
     */
   public function get_user_enrolments() {
        $query = sprintf("SELECT cu.curriculum, cu.id, cu.grade_id, gp.id AS group_id, gp.groups, fl.filename 
                            FROM curriculum AS cu, curriculum_enrolments AS ce, groups AS gp, files AS fl
                            WHERE cu.id = ce.curriculum_id AND ce.status = 1 AND gp.id = ce.group_id AND cu.icon_id = fl.id
                            AND ce.group_id = ANY (SELECT group_id FROM groups_enrolments 
                                WHERE user_id = (SELECT id FROM users WHERE username = '%s'))
                                ORDER BY gp.groups, cu.curriculum ASC",
                            mysql_real_escape_string($this->username));

        $result = mysql_query($query);

        while($row = mysql_fetch_assoc($result)) { 
                $data[] = $row; 
        } 
        if (!isset($data)){
            $data = '';
        }
        return $data;
    }
    
    /**
    * function used during the install process to set up creator id to new admin
    * @return boolean
    */
    public function dedicate(){ // only use during install
        global $USER;
        if(checkCapabilities('user:dedicate', $USER->role_id)){
            $query = sprintf("UPDATE users SET creator_id = '%s'",
                                                mysql_real_escape_string($this->creator_id));
            return mysql_query($query);
        }
    }
}
?>