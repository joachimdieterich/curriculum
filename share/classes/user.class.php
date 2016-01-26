<?php
/** 
 * This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package core
 * @filename user.class.php
 * @copyright 2013 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2013.05.03 21:21
 * @license
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by  
 * the Free Software Foundation; either version 3 of the License, or (at your option) any later version. 
 *                                                                       
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of        
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details:
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
     * current user semester_id
     * @var int 
     */
    public $semester_id; 
    /**
     * users paginator limit
     * @var int
     */
    public $paginator_limit; 
    /**
     * user accomplished days
     * @var int
     */
    public $acc_days;
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
     * current institution id (depends on current semester9
     * @var int 
     */
    public $institution_id;
    /**
     * array of enrolments
     * @var array
     */    
    public $enrolments = array();
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
    
    public function getValue($value, $id){
        $db = DB::prepare('SELECT '. $value .' FROM users WHERE id = ?');
        $db->execute(array($id));
        $result = $db->fetchObject();
        return $result->$value; //set email
    }
    /**
     * Load User
     * @param string $key
     * @param string $user_value 
     */
    public function load($key, $user_value, $get_auth = false, $view = '') {
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
        $this->acc_days          = $result->acc_days;
        $this->paginator_limit   = $result->paginator_limit;
        $this->semester_id       = $result->semester_id;            //--> legt auch die aktuelle Institution fest, da Semester an Institution gebunden 
        
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
        $this->avatar_id         = $result->avatar_id;
        // //nur Thumb laden um Bandbreite zu sparen
        $filename = $this->resolve_file($this->avatar_id);
        $this->avatar            = $thumb_xs = substr($filename, 0, strrpos($filename, '.')) . '_xs.png'; 
        
        $this->creation_time     = $result->creation_time;
        $this->creator_id        = $result->creator_id;
          
        if ($view != 'user'){    // Die folgenden Daten nicht laden, wenn in der Benutzerverwaltung --> Geschwindigkeit
            $this->enrolments    = $this->get_curriculum_enrolments();
            $this->institutions  = $this->get_instiution_enrolments();
        }
        if ($this->semester_id == NULL AND $this->enrolments != false){
           $this->semester_id = $this->enrolments[0]->semester_id;
        }
        //Aktuelle Rolle laden (abhängig von der Institution --> ermittelt über das aktuelle Semester
        $db = DB::prepare('SELECT COUNT(id) FROM institution_enrolments AS ie WHERE ie.user_id = ?');
        $db->execute(array($this->id));
        if($db->fetchColumn() > 1) {
            $db1 = DB::prepare('SELECT ie.role_id, ie.institution_id FROM institution_enrolments AS ie, semester AS se 
                                WHERE se.id = ? AND se.institution_id = ie.institution_id AND ie.user_id = ?');
            $db1->execute(array($this->semester_id, $this->id)); 
        } else { // Benutzer noch in keiner Lerngruppe eingeschrieben bzw. nur in einer Institution eingeschrieben
            $db1 = DB::prepare('SELECT ie.role_id, ie.institution_id FROM institution_enrolments AS ie WHERE ie.user_id = ?');
            $db1->execute(array($this->id)); 
        }
        $ie_result = $db1->fetchObject();
        $this->role_id = $ie_result->role_id;
        $role = new Roles(); 
        $role->id                = $this->role_id;
        $role->load(); 
        $this->role_name         = $role->role;  
        // Aktuelle institution laden (Abhängig von aktuellem Semester)
        if (isset($this->semester_id)){
            $se_result = new Semester($this->semester_id);
            $this->institution_id    = $se_result->institution_id;
        } else {
            $this->institution_id    = $ie_result->institution_id;
        }
        $this->token             = $result->token;   
    }
    
    /**
    * add User
    * @return mixed 
    */
    public function add($institution_id, $group_id = null){ 
        global $USER, $PAGE; 
        checkCapabilities('user:addUser', $USER->role_id);
        $db = DB::prepare('SELECT COUNT(id) FROM users WHERE UPPER(username) = UPPER(?)');
        $db->execute(array($this->username));
        if($db->fetchColumn() >= 1) { 
                $PAGE->message[] = 'Benutzer '.$this->firstname.' '.$this->lastname.'('.$this->username.') existiert bereits';
        } else {
            $db = DB::prepare('INSERT INTO users (username,firstname,lastname,email,postalcode,city,state_id,country_id,avatar_id,password,confirmed,creator_id,paginator_limit,acc_days) 
                                            VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
            if($db->execute(array($this->username,$this->firstname,$this->lastname,$this->email,$this->postalcode,$this->city,$this->state_id,$this->country_id,$this->avatar_id,md5($this->password),$this->confirmed,$this->creator_id,$this->paginator_limit,$this->acc_days))){
                $db = DB::prepare('SELECT id from users WHERE UPPER(username) = UPPER(?)');
                $db->execute(array($this->username));
                $result = $db->fetchObject();
                $this->id = $result->id;
                $this->enroleToInstitution($institution_id); // in Institution einschreiben
                if ($group_id != null){
                    $this->enroleToGroup($group_id, $this->creator_id);
                }
                $PAGE->message[]    = 'Der Benutzer <strong>'.$this->username.'</strong> wurde erfolgreich angelegt.';
                return $this->id; 
            } else {
                return false; 
            }
        }
    }
    
    /**
     * update User
     * @return boolean
     */
    public function update() {
        global $USER; 
        if(checkCapabilities('user:updateUser', $USER->role_id) OR checkCapabilities('user:update', $USER->role_id)){ //2. Bedingung für Änderung des eigenen Profils  
            $db = DB::prepare('UPDATE users 
                    SET username = ?, 
                        firstname = ?, 
                        lastname = ?, 
                        email = ?, 
                        postalcode = ?, 
                        city = ?, 
                        state_id = ?, 
                        country_id = ?,
                        avatar_id = ?, 
                        acc_days  = ?,
                        paginator_limit   = ?
                    WHERE id = ?');
            return $db->execute(array($this->username,$this->firstname,$this->lastname,$this->email,$this->postalcode,$this->city,$this->state_id,$this->country_id,$this->avatar_id,$this->acc_days,$this->paginator_limit,$this->id));    
        }
    }
    
    public function updateRole(){
        global $USER; 
        checkCapabilities('user:updateRole', $USER->role_id);
        $db = DB::prepare('UPDATE institution_enrolments SET role_id = ? WHERE user_id= ? AND institution_id = ?');
        if ($db->execute(array($this->role_id, $this->id, $this->institution_id))){
            $role = new Roles(); 
            $role->id         = $this->role_id;
            $role->load(); 
            $this->role_name  = $role->role;
            return true;
        } else {return false;}
    }
    /**
     * delete User
     * @return boolean 
     */
    public function delete(){
        global $USER;
        checkCapabilities('user:delete', $USER->role_id);
        $db = DB::prepare('DELETE FROM users WHERE id = ?');
        if ($db->execute(array($this->id))) {
            $db1 = DB::prepare('DELETE FROM user_accomplished WHERE user_id = ?'); 
            $db1->execute(array($this->id));
            /* Hier sollte alles gelöscht werden, was der Benutzer angelegt hat, evtl. auch Daten archivieren*/
            $db2 = DB::prepare('DELETE FROM institution_enrolments WHERE user_id = ?');
            return $db2->execute(array($this->id));
        } else {return false;}   
    }
    /**
     * change password
     * @param string $password
     * @return boolean
     */
    public function changePassword($password) {
        global $USER; 
        checkCapabilities('user:changePassword', $USER->role_id);
        $db = DB::prepare('UPDATE users SET password = ?, confirmed = 1 WHERE UPPER(username) = UPPER(?)');
        return $db->execute(array($password, $this->username));
    }
  
    /**
     * Get password
     * @param string $table
     * @param string $format
     * @return string 
     */
    public function getPassword($table='users', $format='md5') {
        global $USER; 
        checkCapabilities('user:getPassword', $USER->role_id);
        $db = DB::prepare('SELECT password FROM '.$table.' WHERE UPPER(username) = UPPER(?)');
        $db->execute(array($this->username));
        $result = $db->fetchObject();
        if ($format == 'md5'){
            return  md5($result->password);
        } else {
            return  $result->password;
        }      
    }
    
    /**
     * get group members (of all groups in which current user is enroled)
     * @return array 
     */
    public function getGroupMembers($dependency = null, $id = null) {
        global $USER; 
        checkCapabilities('user:getGroupMembers', $USER->role_id);
        switch ($dependency) {
            case 'group':   $db = DB::prepare('SELECT user_id FROM groups_enrolments WHERE group_id = ?');
                            $db->execute(array($id)); 
                            while($result = $db->fetchObject()) { 
                                $group_members[] =  $result->user_id;
                            }
                            return $group_members;
                break;

            default:        $db = DB::prepare('SELECT DISTINCT usr.id, usr.firstname, usr.lastname, usr.username 
                                    FROM users AS usr, groups_enrolments AS cle, institution_enrolments AS ie, groups as gr 
                                    WHERE cle.group_id IN (SELECT DISTINCT group_id FROM groups_enrolments WHERE user_id = ?)
                                    AND ie.user_id = ?
                                    AND usr.id = cle.user_id AND gr.id = cle.group_id and gr.institution_id = ie.institution_id');//Zeigt nur User in deren Gruppe man eingeschrieben ist. 
                            $db->execute(array($this->id, $this->id)); 
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
    
    /**
     * List of new Users since last login of current user
     * @return array of object
     */
    public function newUsers($id){
        global $USER; 
        checkCapabilities('user:listNewUsers', $USER->role_id);
        $db = DB::prepare('SELECT us.*, ro.role, ie.role_id
                    FROM users AS us, roles AS ro, institution_enrolments AS ie
                    WHERE ie.role_id = ro.id 
                    AND ie.user_id = us.id
                    AND us.creation_time > (SELECT last_login FROM users WHERE id = ?)');
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
            $this->avatar_id         = $result->avatar_id;
            if (is_int($result)){
                 $filename = $this->resolve_file($this->avatar_id);
                 $this->avatar            = $thumb_xs = substr($filename, 0, strrpos($filename, '.')) . '_xs.png'; 
            }
            $this->creation_time     = $result->creation_time;
            $this->creator_id        = $result->creator_id;
            $role = new Roles();                            //???bereits oben in der query ermittelt rol.role?
            $role->id                = $this->role_id;
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
    
    /**
     * enrole user to institution
     * @global object $USER
     * @param int $institution_id
     * @return boolean 
     */
    public function enroleToInstitution($institution_id){
        global $USER, $PAGE; 
        checkCapabilities('user:enroleToInstitution', $USER->role_id);
        $db = DB::prepare('SELECT count(id) FROM institution_enrolments WHERE institution_id = ? AND user_id = ?');
        $db->execute(array($institution_id, $this->id));
        if($db->fetchColumn() > 0) {
            $db = DB::prepare('UPDATE institution_enrolments SET status = 1, creator_id = ?, role_id = ? WHERE user_id = ? AND institution_id = ?');
             return $db->execute(array($USER->id, $this->role_id, $this->id, $institution_id));
        } else {
            $db = DB::prepare('INSERT INTO institution_enrolments (institution_id,user_id,role_id,creator_id,status) VALUES(?,?,?,?,1)');
            if ($db->execute(array($institution_id, $this->id, $this->role_id, $USER->id))){
                $PAGE->message[]    = '<strong>'.$this->username.'</strong> erfolgreich in die Institution eingeschrieben.';
                return true; 
            } else {
                $PAGE->message[]    = 'Benutzereinschreibung konnte nicht in die Institution eingeschrieben werden.';
                return false;
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
        checkCapabilities('user:expelFromInstitution', $USER->role_id);
        $db = DB::prepare('UPDATE institution_enrolments SET status = 0, creator_id = ? WHERE user_id = ? AND institution_id = ?');
        return $db->execute(array($USER->id, $this->id, $institution_id));
    }
    
    /**
     * enrole user to group
     * @param int $group_id
     * @param int $creator_id
     * @return boolean 
     */
    public function enroleToGroup($group_id, $creator_id){
        global $USER; 
        checkCapabilities('user:enroleToGroup', $USER->role_id);
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
    
    /**
     * expel user from group
     * @param int $group_id
     * @return boolean 
     */
    public function expelFromGroup($group_id){
        global $USER; 
        checkCapabilities('user:expelFromGroup', $USER->role_id);
        $db = DB::prepare('SELECT COUNT(id) FROM groups_enrolments WHERE group_id = ? AND user_id = ?');
        $db->execute(array($group_id, $this->id));
        if($db->fetchColumn() >= 1) {
            $db = DB::prepare('UPDATE groups_enrolments SET status = 0, expel_time = NOW() WHERE group_id = ? AND user_id =? '); // Status 0 expelled
            return $db->execute(array($group_id, $this->id));
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
        global $CFG, $USER, $PAGE;
        checkCapabilities('menu:readuserImport', $USER->role_id);
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
                        if ($data[$c] == "state_id")    {$state_position          = $c;}
                        if ($data[$c] == "couconntry_id")  {$country_position        = $c;}
                        if ($data[$c] == "avatar_id")   {$avatar_position         = $c;}
                    }    
                }
                $row++; //Tielzeile überspringen
                if ($row > 2) {	
                    $this->role_id = $CFG->standard_role; //reset role id to avoid wrong permissions 
                    if (!isset($username_position))       {$this->username   = '';}                  else {$this->username   = $data[$username_position];}
                    if (!isset($firstname_position))      {$this->firstname  = '';}                  else {$this->firstname  = $data[$firstname_position];}
                    if (!isset($lastname_position))       {$this->lastname   = '';}                  else {$this->lastname   = $data[$lastname_position];}
                    if (!isset($email_position))          {$this->email      = '';}                  else {$this->email      = trim($data[$email_position]);} //trim da sonst bei Leerzeichen die Validierung fehlschlägt!
                    if (!isset($postalcode_position))     {$this->postalcode = '';}                  else {$this->postalcode = $data[$postalcode_position];}
                    if (!isset($city_position))           {$this->city       = '';}                  else {$this->city       = $data[$city_position];}
                    if (!isset($state_position))          {$this->state_id   = $CFG->standard_state;}   else {$this->state_id      = $data[$state_position];}
                    if (!isset($country_position))        {$this->country_id = $CFG->standard_country;} else {$this->country_id    = $data[$country_position];}
                    if (!isset($avatar_position))         {$this->avatar_id  = '681';}               else {$this->avatar_id  = $data[$avatar_position];}
                    if (!isset($password_position))       {$this->password   = 'password';}          else {$this->password   = $data[$password_position];} //todo: besser Fehlermeldung, wenn Passwort nicht gesetzt
                    if (!isset($role_id_position))        {$this->role_id    = $this->role_id;}      else {$this->role_id    = $data[$role_id_position];}
                    if (!isset($confirmed_position))      {$this->confirmed  = '3';}                 else {$this->confirmed  = $data[$confirmed_position];}

                    $validated_data = $this->validate();
                    if ($validated_data === true) {
                        $this->add($institution_id);
                        //$this->enroleToInstitution($institution_id);
                    } else {
                        $error[] = array('username' => $this->username, 
                                        'error'    => $validated_data); 
                        foreach ($error as $value) {
                        $PAGE->message[] = 'Benutzer: '.$value['username'].' Error: '.array2str($value['error']);
                        }
                    }
                }
            }
        }
        fclose($handle);
        if (!isset($error)){ //if there are any error messages
            $PAGE->message[] = 'Benutzerliste erfolgreich importiert';
        } 
    }
    
    /**
     * get user list depending on $dependency
     * @param string $dependency
     * @param int $id
     * @return array of object 
     */
    public function userList($dependency = 'institution', $paginator = '', $id = null){
        global $USER;
        $order_param = orderPaginator($paginator, array('username'  => 'us',
                                                        'firstname' => 'us',
                                                        'lastname'  => 'us',
                                                        'email'     => 'us', 
                                                        'postalcode'=> 'us',
                                                        'city'      => 'us'));  
        
        checkCapabilities('user:userList', $USER->role_id); // kann eigentlich weg.
        $users = array();                      //Array of grades
        switch ($dependency) {
            case 'institution': if(checkCapabilities('user:userListComplete', $USER->role_id,false)){ //Global Admin
                                    $db = DB::prepare('SELECT us.* FROM users AS us WHERE us.id = us.id '.$order_param); //hack id = id to user search
                                    $db->execute(); 
                                } else if (checkCapabilities('user:userListInstitution', $USER->role_id,false)) { //Manager
                                    $db = DB::prepare('SELECT us.* FROM users AS us WHERE us.id = ANY (SELECT user_id FROM institution_enrolments 
                                                    WHERE institution_id = ? AND status = 1) '.$order_param);
                                    $db->execute(array($this->institution_id)); 
                                } else if (checkCapabilities('user:userListGroup', $USER->role_id,false)) { //Kursersteller
                                    $db = DB::prepare('SELECT us.* FROM users AS us, groups_enrolments AS ge, institution_enrolments AS ie 
                                                        WHERE ie.institution_id = ? AND ie.status = 1
                                                        AND ie.user_id = us.id 
                                                        AND ge.user_id = ie.user_id
                                                        AND ge.status = 1
                                                        AND ge.group_id = ANY (SELECT group_id FROM groups_enrolments 
                                                                                               WHERE user_id = ? AND status =  1) '.$order_param);
                                    $db->execute(array($this->institution_id, $this->id)); 
                                }                       
            break;
            default:  break;
        }

        while($result = $db->fetchObject()) { 
            $this->id           = $result->id; 
            $this->username     = $result->username;
            $this->firstname    = $result->firstname;
            $this->lastname     = $result->lastname;
            $this->email        = $result->email;
            $this->postalcode   = $result->postalcode;
            $this->city         = $result->city;
            //$this->load('id', $result->id, false, 'user');
            $users[] = clone $this;
        } 
            return $users;
    }
    
    /**
     * Reset Password
     * @return boolean 
     */
    public function resetPassword() {
        global $USER;
        checkCapabilities('user:resetPassword', $USER->role_id);
        $db = DB::prepare('UPDATE users SET password = ?, confirmed = ? WHERE id=?');
        return $db->execute(array(md5($this->password), $this->confirmed, $this->id));       
    }
    /**
     * Returns all Curricula in which the user is enroled. 
     * If User isn't enroled in any curriculum, return is false
     * @return array of object | boolean 
     */
    public function getCurricula($paginator = '') {
        $order_param = orderPaginator($paginator);
        $db = DB::prepare('SELECT DISTINCT cu.id, cu.curriculum, cu.description, fl.filename, su.subject, gr.grade, sc.schooltype, st.state, co.de
                            FROM curriculum AS cu, groups_enrolments AS ce, curriculum_enrolments AS cure, files AS fl, subjects AS su, grade AS gr, schooltype AS sc, state AS st, countries AS co
                            WHERE cu.icon_id = fl.id
                            AND cu.id = cure.curriculum_id
                            AND cure.group_id = ce.group_id
                            AND cu.grade_id = gr.id
                            AND cu.subject_id = su.id
                            AND cu.schooltype_id = sc.id
                            AND cu.state_id = st.id
                            AND cu.country_id = co.id
                            AND ce.user_id = ?
                            AND ce.status = 1
                            '.$order_param);
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
    public function getGroups($paginator = ''){
       $order_param = orderPaginator($paginator); 
       $db = DB::prepare('SELECT gp.*, gr.grade, yr.semester, ins.institution, us.username AS creator
                            FROM groups AS gp, groups_enrolments AS cle, grade AS gr, semester AS yr, institution AS ins, users AS us
                            WHERE cle.user_id = ?
                            AND cle.group_id = gp.id
                            AND gr.id = gp.grade_id 
                            AND yr.id = gp.semester_id 
                            AND ins.id = gp.institution_id 
                            AND us.id = gp.creator_id
                            AND cle.status = 1 '.$order_param);
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
        $gump   = new Gump();                           /* Validation */
        if (isset($_POST)){
            $_POST  = $gump->sanitize($_POST);                  //sanitize $_POST
        }
        
        if ($check_password){ // if true -> validate password 
            $gump->validation_rules(array(
                'username'          => 'required|max_len,100|min_len,3',
                'firstname'         => 'required|max_len,100',
                'lastname'          => 'required|max_len,100',
                'email'             => 'required|valid_email',
                'password'          => 'required|max_len,100|min_len,6'
            ));
        } else {            // don't validate password
            $gump->validation_rules(array(
                'username'          => 'required|max_len,100|min_len,3',
                'firstname'         => 'required|max_len,100',
                'lastname'          => 'required|max_len,100',
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
    public function getUsers($dependency = null, $paginator = '', $id = null, $group = null){
        global $USER;
        $order_param = orderPaginator($paginator);
        
        checkCapabilities('user:getUsers', $USER->role_id);
        switch ($dependency) {
            case 'course':  $db = DB::prepare('SELECT us.* FROM users AS us, groups_enrolments AS gr, curriculum_enrolments AS ce
                                                WHERE us.id = gr.user_id 
                                                AND ce.curriculum_id = ?
                                                AND ce.status = 1
                                                AND ce.group_id = gr.group_id
                                                AND gr.group_id = ?                                                       
                                                AND gr.status = 1 
                                                '.$order_param);
               
                            $db->execute(array($id, $group)); 
                            /*$db = DB::prepare('SELECT DISTINCT us.* FROM users AS us
                                                INNER JOIN groups_enrolments AS gr ON us.id = gr.user_id 
                                                AND gr.group_id = ANY (SELECT group_id FROM curriculum_enrolments WHERE curriculum_id = ? AND status = 1 )
                                                AND gr.group_id = ANY (SELECT id FROM groups WHERE institution_id = ANY 
                                                (SELECT institution_id FROM institution_enrolments WHERE user_id = ? AND status = 1))                                                       
                                                AND gr.status = 1 
                                                '.$order_param);
                            $db->execute(array($id, $this->id)); */

                            while($result = $db->fetchObject()) {  
                                    $this->id           = $result->id;
                                    $this->load('id', $this->id, false, 'user');
                                    $ena = new EnablingObjective();
                                    $this->completed = $ena->getPercentageOfCompletion($id, $this->id);
                                    $users[] = clone $this; 
                            }
                            break;
            case 'institution':  $db = DB::prepare('SELECT DISTINCT us.* FROM users AS us
                                                INNER JOIN groups_enrolments AS gr ON us.id = gr.user_id 
                                                AND gr.group_id = ANY (SELECT id FROM groups WHERE institution_id = ANY 
                                                (SELECT institution_id FROM institution_enrolments WHERE user_id = ? AND status = 1)) 
                                                '.$order_param);
                            $db->execute(array($this->id)); 

                            while($result = $db->fetchObject()) { 
                                    $this->id           = $result->id;
                                    $this->load('id', $this->id, false, 'user');
                                    $users[] = clone $this; 
                            }

                            break;

            default:        break;
        }

        if (isset($users)) {
            return $users; 
        } else {return false;}
   }
   
   /**
    * check login data
    * @return boolean 
    */
   public function checkLoginData(){
        $db     = DB::prepare('SELECT COUNT(id) FROM users WHERE UPPER(username) = UPPER(?) AND password=?');
        $db->execute(array($this->username, $this->password));
        $count  = $db->fetchColumn();
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
        $db = DB::prepare('UPDATE users SET last_login = NOW(), token = ? WHERE UPPER(username) = UPPER(?) AND password = ?');
        return $db->execute(array(getToken(), $this->username, $this->password));
   }
   
   
   public function setSemester($semester){
        $db = DB::prepare('UPDATE users SET semester_id = ? WHERE id = ?');
        return $db->execute(array($semester, $this->id));
   }
   /**
    *
    * @param type $institution_id
    * @return int 
    */
   public function usersOnline (){
       global $CFG;
        //set last_action
        $db = DB::prepare('UPDATE users SET last_action = NOW() WHERE id = ?');        
        $db->execute(array($this->id));
        //get users online
        $db = DB::prepare('SELECT COUNT(id) FROM users WHERE TIMESTAMPDIFF(MINUTE,last_action,NOW()) < ?');
        $db->execute(array($CFG->timeout));
        return $db->fetchColumn();
    }
    
   public function userLogout(){
        global $CFG;
        $db = DB::prepare('SELECT last_action FROM users WHERE id = ?');
        $db->execute(array($this->id));
        $this->last_action = $db->fetchColumn();
        //set last_action
        $db = DB::prepare('UPDATE users SET last_action = TIMESTAMPADD(MINUTE,-?, ?) WHERE id = ?');        
        $db->execute(array($CFG->timeout, $this->last_action, $this->id));
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
        checkCapabilities('user:confirmUser', $USER->role_id);
        $db = DB::prepare('UPDATE users SET confirmed = 1 WHERE id = ?');//confirmed 1 == freigeben
        return $db->execute(array($user_id));
   }
   
    /**
     * get user enrolments
     * @return string | array
     */
   public function get_curriculum_enrolments() { 
        $db = DB::prepare('SELECT cu.curriculum, cu.id, cu.grade_id, gp.id AS group_id, gp.semester_id, gp.groups, fl.filename 
                            FROM curriculum AS cu, curriculum_enrolments AS ce, groups AS gp, files AS fl
                            WHERE cu.id = ce.curriculum_id AND ce.status = 1 AND gp.id = ce.group_id AND cu.icon_id = fl.id
                            AND ce.group_id = ANY (SELECT group_id FROM groups_enrolments 
                                                    WHERE user_id = (SELECT id FROM users WHERE username = ?)
                                                    AND status = 1)
                            ORDER BY gp.groups, cu.curriculum ASC');
        $db->execute(array($this->username));
        
        while($result = $db->fetchObject()) { 
                $data[] = $result;         
        } 
        if (isset($data)){ return $data; } 
        else             { return false; }
    }
    /**
     * get user enrolments
     * @return string | array
     */
   public function get_instiution_enrolments($array = false) { 
        $db = DB::prepare('SELECT ins.institution, ins.id AS institution_id, ro.id AS role_id, ro.role
                            FROM institution AS ins, institution_enrolments AS ie, roles AS ro
                            WHERE ins.id = ie.institution_id AND ie.status = 1 AND ie.role_id = ro.id
                            AND ie.user_id = ?');
        $db->execute(array($this->id));
        
        while($result = $db->fetchObject()) { 
            if ($array == true){ //Array output for quickform
                $data[$result->institution_id] = $result->institution;
            } else {
                $data[] = $result;         
            }
        } 
        
        if (isset($data)){ return $data; } 
        else             { return false; }
    }
    
    public function exist(){
        if ($this->id == -1){
            $this->firstname = 'System-Nachricht';
            $this->lastname  = '';
            $this->username  = '';
        } else {
            $db     = DB::prepare('SELECT COUNT(id) FROM users WHERE id = ?');  
            $db->execute(array($this->id));
            $count  = $db->fetchColumn();
            if ($count > 0){
                return true; 
            } else {
                $this->firstname = 'Gelöschter';
                $this->lastname  = 'Nutzer';
                $this->username  = '';
                return false;
            }
        }
    }
    
    private function resolve_file($id){
        global $CFG;
        
        $file       = new File();
        $file->id   = $id;
        
        if (is_numeric($file->id)){                           // load-Funktion nur aufrufen wenn $id == numeric // is_int funktioniert nicht!i
            $file->load();
        }
        if (isset($file->filename)){
           return $file->full_path;
        } else {
            return $CFG->standard_avatar;
        }
    }
    
    public function checkTermsOfUse(){
        $db = DB::prepare('SELECT status FROM accept_terms WHERE user_id = ?');
        $db->execute(array($this->id));
        $status = $db->fetchColumn();
        if($status == 1) {
            return true;
        } else {
            return false;
        }
         
    }
    
    public function acceptTerms(){
        $db = DB::prepare('SELECT count(id) FROM accept_terms WHERE user_id = ?');
        $db->execute(array($this->id));
        if($db->fetchColumn() > 0) {
            $db = DB::prepare('UPDATE accept_terms SET status = 1 WHERE user_id = ?');//Status 1 == accepted
            return $db->execute(array($this->id));
        } else { 
            $db = DB::prepare('INSERT INTO accept_terms (status,user_id) VALUES (1,?)');//Status 1 == accepted
            return $db->execute(array($this->id));
        }
    }
    
    public function backup(){
        global $CFG;
        // create backup folder
        silent_mkdir($CFG->backup_root.'tmp/'.$this->username.'/');
        $this->userBackup() ;

        /* backup messages */
        $messages = new Mailbox();
        $xml_inbox   = $messages->backup($this->id, 'receiver_id');
        if ($xml_inbox != false) {
            file_put_contents($CFG->backup_root.'tmp/'.$this->username.'/messages_inbox.xml', $xml_inbox->saveXML());
        }
        $xml_outbox  = $messages->backup($this->id, 'sender_id');
        if ($xml_outbox != false) {
            file_put_contents($CFG->backup_root.'tmp/'.$this->username.'/messages_outbox.xml', $xml_outbox->saveXML());
        }
        /* backup accomplished objectives (curriculum)*/
        $this->curriculumBackup();
    }
    private function userBackup(){
        global $CFG;
        $xml = new DOMDocument("1.0", "UTF-8");
        $usr = $xml->createElement("user");
        foreach($this as $key => $value) {
            if ($key == 'institutions' OR $key == 'enrolments'){
                
            } else {
                $child = $xml->createElement($key, $value);
                $usr->appendChild($child);
            }
        }
        $xml->appendChild($usr);
        $xml->preserveWhiteSpace = false; 
        $xml->formatOutput = true;

        $file = $CFG->backup_root.'tmp/'.$this->username.'/user.xml'; // Backup / [username] / 
        file_put_contents($file, $xml->saveXML());
    }

    private function curriculumBackup(){
        global $CFG;
        $db = DB::prepare('SELECT DISTINCT curriculum_id FROM enablingObjectives WHERE id = ANY (SELECT DISTINCT enabling_objectives_id FROM user_accomplished WHERE user_id = ?)');
        $db->execute(array($this->id));
        while($result = $db->fetchObject()) {
            $cur[]  = $result->curriculum_id; 
        }
        foreach($cur as $value){
            $c      = new Curriculum();
            $c->id  = $value;
            $c->load(true);
            $c_backup = new Backup();
            $c_backup->temp_path = $CFG->backup_root.'tmp/'.$this->username.'/';
            $c_backup->generateXML($c, $value, 'xml');
        }
        
    }
    /**
    * function used during the install process to set up creator id to new admin
    * @return boolean
    */
    public function dedicate(){ // only use during install
        global $USER;
        checkCapabilities('user:dedicate', $USER->role_id);
        $db = DB::prepare('UPDATE users SET creator_id = ?');
        return $db->execute(array($this->creator_id));
    }
   
}