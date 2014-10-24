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
    public $id; 
    /**
     * username
     * @var string 
     */
    public $username; 
    /**
     * password md5
     * @var string
     */
    public $password;
    /**
     * role id
     * @var int 
     */
    public $role_id = 0;    //Standard Role !important
    /**
     * role name
     * @var string 
     */
    public $role_name; 
    /**
     * timestamp of last login
     * @var timestamp
     */
    public $last_login; 
    /**
     * last action 
     * @var timestamp
     */
    public $last_action; 
    /**
     * email adress
     * @var string
     */
    public $email; 
    /**
     * status
     * @var int 
     */
    public $confirmed; 
    /**
     * firstname
     * @var string 
     */
    public $firstname; 
    /**
     * lastname
     * @var string 
     */
    public $lastname; 
    /**
     * postalcode
     * @var string 
     */
    public $postalcode; 
    /**
     * city
     * @var string 
     */
    public $city; 
    /**
     * state
     * @var string
     */
    public $state; 
    /**
     * id of state
     * @var int
     */
    public $state_id; 
    /**
     * country
     * @var string 
     */
    public $country; 
    /**
     * id of country
     * @var type 
     */
    public $country_id; 
    /**
     * id of avatar
     * @var int
     */
    public $avatar_id; 
    /**
     * filename of avatar
     * @var string
     */
    public $avatar; 
    /**
     * user language
     * @var string
     */
    public $language; 
    /**
     * current user semester
     * @var int 
     */
    public $semester; 
    /**
     * timestamp of creation
     * @var timestamp
     */
    public $creation_time; 
    /**
     * id of creator user
     * @var int
     */
    public $creator_id; 
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
     * token for authentication
     * @var string 
     */
    public $token;
    /**
     * percentage of curriculum completion
     * @var int 
     */
    public $completed; 
    
    /**
     * User class constructor
     * @param mixed $user_value 
     */
    public function __construct($user_value = null) {
        if ($user_value != null){
            $this->load('id', $user_value, true); //load user by id
        }
    }
    
    /**
     * Load User
     * @param string $key
     * @param string $user_value 
     */
    public function load($key, $user_value, $get_auth = false) {
        
        if ($key == 'username'){
            $db = DB::prepare('SELECT * FROM users WHERE UPPER('.$key.') = UPPER(?)');
            $db->execute(array($user_value));
            $result = $db->fetchObject();
        } else {
            $db = DB::prepare('SELECT * FROM users WHERE '.$key.' = ?');
            $db->execute(array($user_value));
            $result = $db->fetchObject();
        }
        
        $this->id                = $result->id;
        $this->username          = $result->username;
        $this->password          = $result->password;
        $this->firstname         = $result->firstname; 
        $this->lastname          = $result->lastname; 
        $this->email             = $result->email; 
        $this->postalcode        = $result->postalcode; 
        $this->city              = $result->city; 
        $this->state_id          = $result->state_id; 

        $db = DB::prepare('SELECT state FROM state WHERE id = ?');
        $db->execute(array($this->state_id));
        $state_result = $db->fetchObject();
        if ($state_result->state){
        $this->state             = $state_result->state;
        }
        $this->country_id        = $result->country_id;
        $db = DB::prepare('SELECT de FROM countries WHERE id = ?');
        $db->execute(array($this->country_id));
        $country_result = $db->fetchObject();
        if ($country_result->de){
        $this->country           = $country_result->de;
        }
        $this->confirmed         = $result->confirmed; 
        $this->last_login        = $result->last_login; 
        $this->role_id           = $result->role_id; 
        $this->avatar_id         = $result->avatar_id;
        $db = DB::prepare('SELECT path, filename FROM files WHERE id = ?');
        $db->execute(array($result->avatar_id));
        $result_avatar = $db->fetchObject();
        if (is_object($result_avatar)){
            $this->avatar         = $result->id.'/'.$result_avatar->filename;
        } else {
            $this->avatar = 'noprofile.jpg';
        }
        $this->creation_time     = $result->creation_time;
        $this->creator_id        = $result->creator_id;
        $role = new Roles(); 
        $role->role_id           = $this->role_id;
        $role->load(); 
        $this->role_name         = $role->role;       
        $this->enrolments        = $this->get_user_enrolments();
        $db = DB::prepare('SELECT * FROM config_user WHERE user_id = ?');
        $db->execute(array($this->id));
        $result = $db->fetchObject();
        $this->language          = $result->user_language;
        $this->acc_days          = $result->user_acc_days;
        $this->paginator_limit   = $result->user_paginator_limit;
        if ($result->user_semester == NULL && $this->enrolments != false){                                //Update User config -> new field user_semester
            $upd_config = new Config();
            $upd_config->user_language = $this->language;
            $upd_config->user_semester = $this->enrolments[0]->semester_id;
            $upd_config->user_filepath = $result->user_filepath;
            $upd_config->user_filepath = $this->paginator_limit;
            $upd_config->user_filepath = $this->acc_days;
            $upd_config->user_id = $this->id;
            $upd_config->updateUser();
            $this->semester          = $result->user_semester;
        } else {
            $this->semester          = $result->user_semester;
        }
             
        /**
         * ! users can be enroled in more than one institution 
         */
        $db = DB::prepare('SELECT id, institution FROM institution WHERE id = ANY (SELECT institution_id FROM institution_enrolments WHERE user_id = ? AND status = 1)');
        $db->execute(array($this->id));
        $result = $db->fetchObject();
        
        if ($result){
            unset($this->institutions); //da sonst bei session_reload_user() die Institutionen mehrfach erscheinen
            $this->institutions['id'][] = $result->id;
            $this->institutions['institution'][] = $result->institution;
        } else {
            $this->institutions[] = NULL;
        }    
        
        /**
         * get capabilities 
         */
        $capabilitiy = new Capability();
        $this->capabilities = $capabilitiy->getCapabilities($this->role_id);
        
        /**
         * get token 
         */
        if ($get_auth){
            $authenticate = new Authenticate();
            $authenticate->username = $this->username;
            $authenticate->getUser('username');
            if (isset($authenticate->token)){
                $this->token  = $authenticate->token;
            } 
        }
       
    }
    
    /**
    * add User
    * @return mixed 
    */
    public function add(){ 
        global $USER; 
        if (checkCapabilities('user:addUser', $USER->role_id)){
            $db = DB::prepare('SELECT COUNT(id) FROM users WHERE UPPER(username) = UPPER(?)');
            $db->execute(array($this->username));
            if($db->fetchColumn() >= 1) { 
                    return false;
            } else {
                $db = DB::prepare('INSERT INTO users (username,firstname,lastname,email,postalcode,city,state_id,country_id,avatar_id,password,role_id,confirmed,creator_id) 
                                                VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?)');
                if($db->execute(array($this->username,$this->firstname,$this->lastname,$this->email,$this->postalcode,$this->city,$this->state_id,$this->country_id,$this->avatar_id,md5($this->password),$this->role_id,$this->confirmed,$this->creator_id))){
                    $db = DB::prepare('SELECT id from users WHERE UPPER(username) = UPPER(?)');
                    $db->execute(array($this->username));
                    $result = $db->fetchObject();
                    $this->id = $result->id;
                    
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
            $db = DB::prepare('UPDATE users 
                    SET username = ?, 
                        firstname = ?, 
                        lastname = ?, 
                        email = ?, 
                        postalcode = ?, 
                        city = ?, 
                        state_id = ?, 
                        country_id = ?,
                        avatar_id = ? 
                    WHERE id = ?');
            return $db->execute(array($this->username,$this->firstname,$this->lastname,$this->email,$this->postalcode,$this->city,$this->state_id,$this->country_id,$this->avatar_id,$this->id));    
        }
    }
    
    public function updateRole(){
        global $USER; 
        if(checkCapabilities('user:updateRole', $USER->role_id)){
            $db = DB::prepare('UPDATE users SET role_id = ? WHERE id= ?');
            if ($db->execute(array($this->role_id, $this->id))){
                $role = new Roles(); 
                $role->role_id      = $this->role_id;
                $role->load(); 
                $this->role_name    = $role->role;
                return true;
            } else {return false;}
        }
    }
    /**
     * delete User
     * @return boolean 
     */
    public function delete($creator_id = null){
        if ($creator_id != null) { // if function is called by request-php
            $user = new USER();

            $user->load('id', $creator_id);
            $role_id = $user->role_id;
        } else {
            $role_id = $USER->role-id;
        }
        
        if(checkCapabilities('user:delete', $role_id)){
            $db = DB::prepare('DELETE FROM users WHERE id = ?');
            if ($db->execute(array($this->id))) {
                $user_config = new Config(); 
                $user_config->delete('user', $this->id);
                $db = DB::prepare('DELETE FROM institution_enrolments WHERE user_id = ?');
                return $db->execute(array($this->id));
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
            $db = DB::prepare('UPDATE users SET password = ?, confirmed = 1 WHERE UPPER(username) = UPPER(?)');
            return $db->execute(array($password, $this->username));
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
            $db = DB::prepare('SELECT password FROM '.$table.' WHERE UPPER(username) = UPPER(?)');
            $db->execute(array($this->username));
            $result = $db->fetchObject();
            if ($format == 'md5'){
                return  md5($result->password);
            } else {
                return  $result->password;
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
                case 'group':   $db = DB::prepare('SELECT user_id FROM groups_enrolments WHERE group_id = ?');
                                $db->execute(array($id)); 
                                while($result = $db->fetchObject()) { 
                                    $group_members[] =  $result->user_id;
                                }
                                return $group_members;

                    break;

                default:        $db = DB::prepare('SELECT DISTINCT usr.id, usr.firstname, usr.lastname, usr.username 
                                        FROM users AS usr, groups_enrolments AS cle 
                                        WHERE cle.group_id IN (SELECT DISTINCT group_id FROM groups_enrolments WHERE user_id = ?)
                                        AND usr.id = cle.user_id');//todo: Bedingung einbauen damit nur Personen angezeigt werden die den  Mailempfang erlaubt haben!!!
                                $db->execute(array($this->id));
                                while($result = $db->fetchObject()) { 
                                        $class_members["id"][]     = $result->id;  //todo: besser als object realisieren
                                        $class_members["user"][]   = $result->firstname.' '.$result->lastname.' ('.$result->username.')'; 
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
            $db = DB::prepare('SELECT usr.*, rol.role 
                        FROM users AS usr, roles AS rol
                        WHERE usr.role_id = rol.role_id AND usr.creation_time > (SELECT last_login FROM users WHERE id = ?)');
            $db->execute(array($id));
                while($result = $db->fetchObject()) { 
                $this->id                = $result->id;
                $this->username          = $result->username;
                $this->password          = $result->password;
                $this->firstname         = $result->firstname; 
                $this->lastname          = $result->lastname; 
                $this->email             = $result->email; 
                $this->postalcode        = $result->postalcode;
                $this->city              = $result->city; 
                $this->state_id          = $result->state_id;
                $this->country_id        = $result->country_id;
                $this->confirmed         = $result->confirmed; 
                $this->last_login        = $result->last_login; 
                $this->role_id           = $result->role_id; 
                $this->avatar_id            = $result->avatar_id;
                $this->creation_time     = $result->creation_time;
                $this->creator_id        = $result->creator_id;
                $role = new Roles(); 
                $role->role_id           = $this->role_id;
                $role->load(); 
                $this->role_name         = $role->role;
                $users[] = clone $this; 
            }
            if (isset($users)){
                return $users;          
            } else {
                return false;   
            }   //keine neuen Benutzer
        }
    }
    
    /**
     * enrole user to institution
     * @global object $USER
     * @param int $institution_id
     * @return boolean 
     */
    public function enroleToInstitution($institution_id){
        global $USER; 
        if(checkCapabilities('user:enroleToInstitution', $USER->role_id)){
            $db = DB::prepare('SELECT count(id) FROM institution_enrolments WHERE institution_id = ? AND user_id = ?');
            $db->execute(array($institution_id, $this->id));
            if($db->fetchColumn() > 0) {
                $db = DB::prepare('UPDATE institution_enrolments SET status = 1, creator_id = ? WHERE user_id = ? AND institution_id = ?');
                 return $db->execute(array($USER->id, $this->id, $institution_id));
            } else {
                $db = DB::prepare('INSERT INTO institution_enrolments (institution_id,user_id,creator_id,status) VALUES(?,?,?,1)');
                return $db->execute(array($institution_id, $this->id, $USER->id));
            }
            
        } 
    }
    /**
     * expel user from institution
     * @global object $USER
     * @param int $institution_id
     * @return boolean 
     */
    public function expelFromInstitution($institution_id){
        global $USER; 
        if(checkCapabilities('user:expelFromInstitution', $USER->role_id)){
        $db = DB::prepare('UPDATE institution_enrolments SET status = 0, creator_id = ? WHERE user_id = ? AND institution_id = ?');
        return $db->execute(array($USER->id, $this->id, $institution_id));
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
            $db = DB::prepare('SELECT count(id) FROM groups_enrolments WHERE group_id = ? AND user_id = ?');
            $db->execute(array($group_id, $this->id));
            if($db->fetchColumn() > 0) {
                $db = DB::prepare('UPDATE groups_enrolments SET status = 1 WHERE group_id = ? AND user_id = ?');//Status 1 == enroled
                return $db->execute(array($group_id, $this->id));
            } else { 
                $db = DB::prepare('INSERT INTO groups_enrolments (status,group_id,user_id,creator_id) 
                                                VALUES (1,?,?,?)');//Status 1 == enroled
                return $db->execute(array($group_id, $this->id, $creator_id));
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
            $db = DB::prepare('SELECT COUNT(id) FROM groups_enrolments WHERE group_id = ? AND user_id = ?');
            $db->execute(array($group_id, $this->id));
            if($db->fetchColumn() >= 1) {
                $db = DB::prepare('UPDATE groups_enrolments SET status = 0, expel_time = NOW() WHERE user_id =?'); // Status 0 expelled
                return $db->execute(array($this->id));
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
        if(checkCapabilities('menu:readuserImport', $USER->role_id)){
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
                            if ($data[$c] == "avatar_id")      {$avatar_position         = $c;}
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
                        if (!isset($avatar_position))         {$this->avatar_id     = '';}               else {$this->avatar_id  = $data[$avatar_position];}
                        if (!isset($password_position))       {$this->password   = 'password';}          else {$this->password   = $data[$password_position];} //todo: besser Fehlermeldung, wenn Passwort nicht gesetzt
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
        }
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
                case 'institution': if(checkCapabilities('user:userListComplete', $USER->role_id,false)){
                                        $db = DB::prepare('SELECT us.id FROM users AS us WHERE us.id = ANY (SELECT user_id FROM institution_enrolments 
                                                        WHERE institution_id = ANY (SELECT institution_id FROM institution_enrolments 
                                                        WHERE user_id = ? )) ORDER by us.lastname');
                                    } else {
                                        $db = DB::prepare('SELECT us.id FROM users AS us WHERE us.id = ANY (SELECT user_id FROM institution_enrolments 
                                                        WHERE institution_id = ANY (SELECT institution_id FROM institution_enrolments 
                                                        WHERE user_id = ? ) AND status = 1) ORDER by us.lastname');
                                        
                                                        }
                                        $db->execute(array($this->id)); //Bisher werden nur Benutzer der Institution angezeigt, an der man angemeldet ist. todo: Side-Admin muss alle Benutzer sehen können 
                                        break;
                case 'group':       $db = DB::prepare('SELECT us.id FROM users AS us, groups_enrolments AS gre 
                                                        WHERE gre.user_id = us.id AND gre.status = 1 AND gre.group_id = ?');
                                    $db->execute(array($id)); 
                                    break;
                case 'confirm':     if (checkCapabilities('user:confirmUserSidewide', $USER->role_id, false)){
                                        $db = DB::prepare('SELECT us.id FROM users AS us WHERE us.confirmed = 4');
                                        $db->execute();
                                    } else if (checkCapabilities('user:confirmUser', $USER->role_id, false)){
                                        $db = DB::prepare('SELECT us.id FROM users AS us, institution_enrolments AS ine
                                        WHERE us.confirmed = 4 AND ine.user_id = us.id AND ine.status = 1 AND ine.institution_id IN (?)');
                                        $db->execute(array(implode(',',$this->institutions["id"])));
                                    }
                                    break; 
                default:            break;
            }
            
            while($result = $db->fetchObject()) { 
                    $this->load('id', $result->id);
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
            $db = DB::prepare('UPDATE users SET password = ?, confirmed = ? WHERE id=?');
            return $db->execute(array(md5($this->password), $this->confirmed, $this->id));       
        }
    }
    /**
     * Returns all Curricula in which the user is enroled. 
     * If User isn't enroled in any curriculum, return is false
     * @return array of object | boolean 
     */
    public function getCurricula() {
        $db = DB::prepare('SELECT cu.id, cu.curriculum, cu.description, fl.filename, su.subject, gr.grade, sc.schooltype, st.state, co.de
                            FROM curriculum AS cu, groups_enrolments AS ce, curriculum_enrolments AS cure, files AS fl, subjects AS su, grade AS gr, schooltype AS sc, state AS st, countries AS co
                            WHERE cu.icon_id = fl.id
                            AND cu.id = cure.curriculum_id
                            AND cure.group_id = ce.group_id
                            AND cu.grade_id = gr.grade
                            AND cu.subject_id = su.id
                            AND cu.schooltype_id = sc.id
                            AND cu.state_id = st.id
                            AND cu.country_id = co.id
                            AND ce.user_id = ?
                            AND ce.status = 1
                            ORDER BY cu.curriculum ASC');
        $db->execute(array($this->id));

        while($result = $db->fetchObject()) { 
                $curricula[] = $result; 
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
       $db = DB::prepare('SELECT gp.*, gr.grade, yr.semester, ins.institution, us.username AS creator
                            FROM groups AS gp, groups_enrolments AS cle, grade AS gr, semester AS yr, institution AS ins, users AS us
                            WHERE cle.user_id = ?
                            AND cle.group_id = gp.id
                            AND gr.id = gp.grade_id 
                            AND yr.id = gp.semester_id 
                            AND ins.id = gp.institution_id 
                            AND us.id = gp.creator_id
                            AND cle.status = 1');
       $db->execute(array($this->id)); 

       while($result = $db->fetchObject()) {  
                    $groups[] = $result;
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
                case 'course':  $db = DB::prepare('SELECT DISTINCT us.* FROM users AS us
                                                    INNER JOIN groups_enrolments AS gr ON us.id = gr.user_id 
                                                    AND gr.group_id = ANY (SELECT group_id FROM curriculum_enrolments WHERE curriculum_id = ? AND status = 1 )
                                                    AND gr.group_id = ANY (SELECT id FROM groups WHERE institution_id = ANY 
                                                    (SELECT institution_id FROM institution_enrolments WHERE user_id = ? AND status = 1))                                                       
                                                    ORDER by us.lastname');
                                $db->execute(array($id, $this->id)); 
  
                                while($result = $db->fetchObject()) {  
                                        $this->id           = $result->id;
                                        $this->load('id', $this->id);
                                        $ena = new EnablingObjective();
                                        $this->completed = $ena->getPercentageOfCompletion($id, $this->id);
                                        $users[] = clone $this; 
                                }
                                break;
                case 'institution':  $db = DB::prepare('SELECT DISTINCT us.* FROM users AS us
                                                    INNER JOIN groups_enrolments AS gr ON us.id = gr.user_id 
                                                    AND gr.group_id = ANY (SELECT id FROM groups WHERE institution_id = ANY 
                                                    (SELECT institution_id FROM institution_enrolments WHERE user_id = ? AND status = 1))                                                       
                                                    ORDER by us.lastname');
                                $db->execute(array($this->id)); 
  
                                while($result = $db->fetchObject()) {  
                                        $this->id           = $result->id;
                                        $this->load('id', $this->id);
                                        $users[] = clone $this; 
                                }
                                break;

                default:        break;
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
            case null:  $db = DB::prepare('SELECT COUNT(id) FROM users WHERE confirmed = 4');
                        $db->execute();
                        $count = $db->fetchColumn();
                        if ($count > 0){
                            return $count;
                        } else {
                            return false; 
                        }
                    break;
            case 'institution': 
                        $db = DB::prepare('SELECT COUNT(usr.id) FROM users AS usr, institution_enrolments AS ine
                                        WHERE usr.confirmed = 4 AND usr.id = ine.user_id AND ine.institution_id IN (?) AND status = 1');
                        $db->execute(array(implode(',',$this->institutions["id"])));
                        $count = $db->fetchColumn();
                        if ($count > 0){
                            return $count;
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
        $db = DB::prepare('SELECT COUNT(id) FROM users WHERE UPPER(username) = UPPER(?) AND password=?');
        $db->execute(array($this->username, $this->password));
        $count = $db->fetchColumn();
        if ($count > 0){
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
       $db = DB::prepare('UPDATE users SET last_login = NOW() WHERE UPPER(username) = UPPER(?) AND password = ?');
        $result = $db->execute(array($this->username, $this->password));
        if ($result){
            /* set user token --> used by request.php */
            $db = DB::prepare('SELECT COUNT(username) FROM authenticate WHERE username = ?');  
            $db->execute(array($this->username));
            $count = $db->fetchColumn();
            $this->token = getToken();
            if ($count > 0){
                $db = DB::prepare('UPDATE authenticate SET user_id = ?, token = ?, ip = ?, creation_time = ? WHERE username = ?');        
                $result = $db->execute(array($this->id, $this->token, getIp(), $this->last_login, $this->username));
            } else {
                $db = DB::prepare('INSERT INTO authenticate (username, user_id, token, ip, creation_time) VALUES (?,?,?,?,?)');        
                $result = $db->execute(array($this->username, $this->id, $this->token, getIp(), $this->last_login));
            }
        }
        return $result;
   }
   
   /**
    *
    * @param type $institution_id
    * @return int 
    */
   public function usersOnline (){
        //set last_action
        $db = DB::prepare('UPDATE users SET last_action = NOW() WHERE id = ?');        
        $db->execute(array($this->id));
        //get users online
        $db = DB::prepare('SELECT COUNT(id) FROM users WHERE TIMESTAMPDIFF(MINUTE,last_action,NOW()) < (SELECT institution_timeout FROM config_institution WHERE institution_id IN (?))');
        $db->execute(array(implode(',',$this->institutions["id"])));
        return $db->fetchColumn();
    }
    
   public function userLogout(){
        $db = DB::prepare('SELECT last_action FROM users WHERE id = ?');
        $db->execute(array($this->id));
        $this->last_action = $db->fetchColumn();
        //set last_action
        $db = DB::prepare('UPDATE users SET last_action = TIMESTAMPADD(MINUTE,-(SELECT institution_timeout FROM config_institution WHERE institution_id IN (?)), ?) WHERE id = ?');        
        $db->execute(array(implode(',',$this->institutions["id"]), $this->last_action, $this->id));
   }
           
   /**
    * get users confirmed status
    * @return int
    */
   public function getConfirmed(){
        $db = DB::prepare('SELECT confirmed FROM users WHERE UPPER(username) = UPPER(?) AND password=?');
        $db->execute(array($this->username, $this->password));
        
        $result  = $db->fetchObject();
        $this->confirmed = $result->confirmed;
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
            $db = DB::prepare('UPDATE users SET confirmed = 1 WHERE id = ?');//confirmed 1 == freigeben
            return $db->execute(array($user_id));
       }
   }
   
    /**
     * get user enrolments
     * @return string | array
     */
   public function get_user_enrolments() { 
        $db = DB::prepare('SELECT cu.curriculum, cu.id, cu.grade_id, gp.id AS group_id, gp.semester_id, gp.groups, fl.filename 
                            FROM curriculum AS cu, curriculum_enrolments AS ce, groups AS gp, files AS fl
                            WHERE cu.id = ce.curriculum_id AND ce.status = 1 AND gp.id = ce.group_id AND cu.icon_id = fl.id
                            AND ce.group_id = ANY (SELECT group_id FROM groups_enrolments 
                            WHERE user_id = (SELECT id FROM users WHERE username = ?))
                            ORDER BY gp.groups, cu.curriculum ASC');
        $db->execute(array($this->username));
        
        while($result = $db->fetchObject()) { 
                $data[] = $result;         
        } 
        if (isset($data)){
            return $data;
        } else {
            return false;
        }
    }
    
    public function exist(){
        $db = DB::prepare('SELECT COUNT(id) FROM users WHERE id = ?');  
        $db->execute(array($this->id));
        $count = $db->fetchColumn();
        if ($count > 0){
            return true; 
        } else {return false;}
    }
    
    /**
    * function used during the install process to set up creator id to new admin
    * @return boolean
    */
    public function dedicate(){ // only use during install
        global $USER;
        if(checkCapabilities('user:dedicate', $USER->role_id)){
            $db = DB::prepare('UPDATE users SET creator_id = ?');
            return $db->execute(array($this->creator_id));
        }
    }
   
}
?>