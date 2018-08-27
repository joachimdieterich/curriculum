<?php
/** 
* This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename user.class.php
* @copyright 2013 Joachim Dieterich
* @author Joachim Dieterich
* @date 2013.05.03 21:21
* @license: 
*
* The MIT License (MIT)
* Copyright (c) 2012 Joachim Dieterich http://www.curriculumonline.de
* 
* Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), 
* to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, 
* and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
* 
* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
* 
* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF 
* MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, 
* DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR 
* THE USE OR OTHER DEALINGS IN THE SOFTWARE.
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
     * password md5
     * @var string
     */
    public $password;
    /**
     * role id
     * @var int 
     */
    public $role_id;
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
    public $semester;
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
     * current institution id (depends on current semester)
     * @var int 
     */
    public $institution_id;
    /**
     * current institution data (depends on current semester)
     * @var obj 
     */
    public $institution;
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
     * authentication method 
     * default = manuell
     * @var string
     */
    public $auth = 'manual';
    
    /**
     * percentage of curriculum completion
     * @var int 
     */
    public $completed; 
    public $online; 
    
    /**
     *
     * @var array of user_ids 
     */
    public $children_id;
    
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
    public function load($key, $user_value, $enrolments = true) {
        if ($key == 'username'){
            $db = DB::prepare('SELECT * FROM users WHERE UPPER('.$key.') = UPPER(?)');
            $db->execute(array($user_value));
            $result = $db->fetchObject();
        } else {
            $db = DB::prepare('SELECT * FROM users WHERE '.$key.' = ?');
            $db->execute(array($user_value));
            $result = $db->fetchObject();
        }
        if (!$result){                                                          // return if $result is empty
            return false;
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
        if ($state_result){
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
        //nur Thumb laden um Bandbreite zu sparen
        $filename = $this->resolve_file($this->avatar_id);
        $this->avatar            = $thumb_xs = substr($filename, 0, strrpos($filename, '.')) . '_xs.png'; 
        
        $this->creation_time     = $result->creation_time;
        $this->creator_id        = $result->creator_id;
          
        if ($enrolments){    // Die folgenden Daten nur laden wenn benötigt --> Geschwindigkeit
            $this->enrolments    = $this->get_curriculum_enrolments();
            $this->institutions  = $this->get_instiution_enrolments();
            if ($this->semester_id == NULL AND $this->enrolments != false){
               $this->semester_id    = $this->enrolments[0]->semester_id;
               $semester             = new Semester($this->semester_id);
               $this->semester       = $semester->semester;
            }
            if(isset($this->semester_id)) {
                $db1 = DB::prepare('SELECT ie.role_id, ie.institution_id FROM institution_enrolments AS ie, semester AS se 
                                    WHERE se.id = ? AND se.institution_id = ie.institution_id AND ie.user_id = ? AND ie.status = 1');
                $db1->execute(array($this->semester_id, $this->id)); 
            } else if (isset($this->institution_id)){
                $db1 = DB::prepare('SELECT ie.role_id, ie.institution_id FROM institution_enrolments AS ie WHERE ie.user_id = ? AND ie.status = 1 AND ie.institution_id = ?'); // hack: if User is enroled to more than one institution but not enroled in any group use first institution enrolment.
                $db1->execute(array($this->id, $this->institution_id));
            } else { // Benutzer noch in keiner Lerngruppe eingeschrieben bzw. nur in einer Institution eingeschrieben
                $db1 = DB::prepare('SELECT ie.role_id, ie.institution_id FROM institution_enrolments AS ie WHERE ie.user_id = ? AND ie.status = 1 LIMIT 1'); // hack: if User is enroled to more than one institution but not enroled in any group use first institution enrolment.
                $db1->execute(array($this->id)); 
            }
            $ie_result          = $db1->fetchObject();
            $this->role_id      = $ie_result->role_id;
            $role               = new Roles(); 
            $role->id           = $this->role_id;
            $role->load(); 
            $this->role_name    = $role->role; 
            // Aktuelle institution laden (Abhängig von aktuellem Semester)
            if (isset($this->semester_id)){
                $se_result               = new Semester($this->semester_id);
                $this->institution_id    = $se_result->institution_id;
            } else {
                $this->institution_id    = $ie_result->institution_id;
            }
            if (isset($this->institution_id)){
                $this->institution = new Institution($this->institution_id);
            }
        }
        if ($result->token == NULL){                    //Token has to be set to reset password: if NULL --> user never was logged in. 
            $this->set('token', getToken());
        } else {
            $this->token             = $result->token;   
        }
        if (isset($result->auth)){
            $this->auth              = $result->auth;
        }
    }
    public function set($dependency, $value, $id = NULL){
        if ($id == null){ $id = $this->id; }
        $db = DB::prepare('UPDATE users SET '.$dependency.' = ? WHERE id = ?');
        return $db->execute(array($value, $id));
    }
    
    public function getRole($id){
        $db = DB::prepare('SELECT ie.role_id, ie.institution_id FROM institution_enrolments AS ie WHERE ie.user_id = ? AND ie.status = 1  AND ie.institution_id = ?'); // hack: if User is enroled to more than one institution but not enroled in any group use first institution enrolment.
        $db->execute(array($this->id, $id)); 
        $result          = $db->fetchObject();
        $this->role_id   = $result->role_id;
    }
    
    /**
    * add User
    * @return mixed 
    */
    public function add($institution_id, $group_id = null){ 
        global $USER, $PAGE, $CFG; 
        checkCapabilities('user:addUser', $USER->role_id);
        $db = DB::prepare('SELECT COUNT(id) FROM users WHERE UPPER(username) = UPPER(?)');
        $db->execute(array($this->username));
        if($db->fetchColumn() >= 1) { 
            $PAGE->message[] = array('message' => 'Benutzer '.$this->firstname.' '.$this->lastname.'('.$this->username.') existiert bereits', 'icon' => 'fa fa-user text-warning');// Schließen und speichern
        } else {
            if (!isset($this->paginator_limit)){ $this->paginator_limit = $CFG->settings->paginator_limit; } //fallback
            if (!isset($this->acc_days))       { $this->acc_days        = $CFG->settings->acc_days; }        //fallback
            $db = DB::prepare('INSERT INTO users (username,firstname,lastname,email,postalcode,city,state_id,country_id,avatar_id,password,confirmed,creator_id,paginator_limit,acc_days,auth) 
                                            VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
            if($db->execute(array($this->username,$this->firstname,$this->lastname,$this->email,$this->postalcode,$this->city,$this->state_id,$this->country_id,intval($this->avatar_id),md5($this->password),$this->confirmed,$USER->id,$this->paginator_limit,$this->acc_days,$this->auth))){
                $this->id = DB::lastInsertId();  
                if (is_array($institution_id)){                                     
                    $this->enroleToInstitutions($institution_id);                    //enrol to multiple Institutions
                } else {
                    $this->enroleToInstitution($institution_id);                    // enrol to one Institution
                    if (is_int($group_id)){                                         // enrol to group if id is set
                        $db_01 = DB::prepare('SELECT COUNT(id) FROM groups WHERE id = ? AND institution_id = ?'); //check if group is enroled to given institution
                        $db_01->execute(array($group_id, $institution_id));
                        if($db_01->fetchColumn() >= 1) {
                            $this->enroleToGroup(array($group_id));
                        }
                    }
                }
                $PAGE->message[] = array('message' => 'Der Benutzer <strong>'.$this->username.'</strong> wurde erfolgreich angelegt.', 'icon' => 'fa fa-user text-success');// Schließen und speichern
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
    public function update($dependency = 'full', $key = '', $value = '') {
        global $USER; 
        switch ($dependency) {
            case 'full': if(checkCapabilities('user:updateUser', $USER->role_id) OR checkCapabilities('user:update', $USER->role_id)){ //2. Bedingung für Änderung des eigenen Profils  
                            $db = DB::prepare('UPDATE users 
                                    SET username = ?, firstname = ?, lastname = ?, email = ?, postalcode = ?, city = ?, state_id = ?, 
                                        country_id = ?, avatar_id = ?, acc_days  = ?, paginator_limit   = ?
                                    WHERE id = ?');
                            $r =  $db->execute(array($this->username,$this->firstname,$this->lastname,$this->email,$this->postalcode,$this->city,$this->state_id,$this->country_id,$this->avatar_id,$this->acc_days,$this->paginator_limit,$this->id));    
                        }
                break;
            case 'value':   $db = DB::prepare('UPDATE users SET '.$key.' = ? WHERE id = ?');
                            $r =  $db->execute(array($value, $USER->id));    
                break;

            default:
                break;
        }
        return $r;
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
        global $USER, $LOG;
        checkCapabilities('user:delete', $USER->role_id);
        $this->load('id', $this->id);
        $LOG->add($USER->id, 'user.class.php', dirname(__FILE__), 'Delete user: ('.$this->resolveUserId($this->id).'), creator_id: '.$this->creator_id);
        $role_check             = new Roles();
        $role_check->load('id', $this->role_id);
        $delete_user_order_id   = $role_check->order_id;
        $role_check->load('id', $USER->role_id);
        $user_order_id          = $role_check->order_id;
        //error_log ($delete_user_order_id.'< target  user >'.$user_order_id);
        if ($this->id != $USER->id){
            if ($delete_user_order_id >= $user_order_id){ //user can only delete other user who has a higher role_order_id 
            
                $db = DB::prepare('DELETE FROM users WHERE id = ?');
                if ($db->execute(array($this->id))) {
                    $db1 = DB::prepare('DELETE FROM user_accomplished WHERE user_id = ?'); 
                    $db1->execute(array($this->id));
                    $db1 = DB::prepare('DELETE FROM accept_terms WHERE user_id = ?'); 
                    $db1->execute(array($this->id));
                    $db1 = DB::prepare('DELETE FROM groups_enrolments WHERE user_id = ?'); 
                    $db1->execute(array($this->id));
                    $db1 = DB::prepare('DELETE FROM log WHERE user_id = ?'); //todo: maybe log should not be deleted
                    $db1->execute(array($this->id));
                    /* Hier sollte alles gelöscht werden, was der Benutzer angelegt hat, evtl. auch Daten archivieren*/
                    $db2 = DB::prepare('DELETE FROM institution_enrolments WHERE user_id = ?');
                    $_SESSION['PAGE']->message[] = array('message' => 'Benutzerkonten wurden erfolgreich gelöscht!', 'icon' => 'fa-user text-success');
                    return $db2->execute(array($this->id));
                } else {return false;}   
            } else {
                $_SESSION['PAGE']->message[] = array('message' => 'Sie können keine Nutzer mit einer übergeordneter Rollen löschen!', 'icon' => 'fa-user text-warning');
            }
        } else {
            $_SESSION['PAGE']->message[] = array('message' => 'Man kann sich nicht selbst löschen!', 'icon' => 'fa-user text-warning');
        }
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
     * get group members (of all groups in which current user is enroled)
     * @return array 
     */
    public function getGroupMembers($dependency = null, $id = null) {
        global $USER; 
        checkCapabilities('user:getGroupMembers', $USER->role_id);
        switch ($dependency) {
            case 'group':   $db = DB::prepare('SELECT DISTINCT user_id FROM groups_enrolments 
                                                WHERE group_id = ?
                                                AND status = 1');
                            $db->execute(array($id)); 
                            while($result = $db->fetchObject()) { 
                                $group_members[] =  $result->user_id;
                            }
                            if (isset($group_members)){
                                return $group_members;
                            } else { return false;}
                break;
            case 'my_groups': $db = DB::prepare('SELECT DISTINCT usr.id, usr.firstname, usr.lastname, usr.username
                                    FROM users AS usr, groups_enrolments AS ge, institution_enrolments AS ie, groups AS gr 
                                    WHERE ge.group_id IN (SELECT DISTINCT group_id FROM groups_enrolments WHERE user_id = ?)
                                    AND ie.user_id = ?
                                    AND usr.id = ge.user_id 
                                    AND gr.id = ge.group_id 
                                    AND gr.institution_id = ie.institution_id 
                                    AND ge.status = 1');//Zeigt nur User in deren Gruppe man eingeschrieben ist. 
                                $db->execute(array($this->id, $this->id)); 
                            
                            while($result = $db->fetchObject()) { 
                                    $class_members[]     = clone $result;
                            } 
                            if (isset($class_members)){
                                return $class_members;
                            } else { 
                                return false;
                            }
                break;
            default:        if (checkCapabilities('user:userListComplete', $USER->role_id,false)){
                                $db = DB::prepare('SELECT DISTINCT us.id, us.firstname, us.lastname, us.username FROM users AS us ORDER BY us.lastname');//Zeige alle User --> nur für globale Admin Rolle !. 
                                $db->execute(array()); 
                            } else {
                                $db = DB::prepare('SELECT DISTINCT usr.id, usr.firstname, usr.lastname, usr.username
                                    FROM users AS usr, groups_enrolments AS ge, institution_enrolments AS ie, groups AS gr 
                                    WHERE ge.group_id IN (SELECT DISTINCT group_id FROM groups_enrolments WHERE user_id = ?)
                                    AND ie.user_id = ?
                                    AND usr.id = ge.user_id 
                                    AND gr.id = ge.group_id 
                                    AND gr.institution_id = ie.institution_id 
                                    AND ge.status = 1');//Zeigt nur User in deren Gruppe man eingeschrieben ist. 
                                $db->execute(array($this->id, $this->id)); 
                            }
                            
                            while($result = $db->fetchObject()) { 
                                    $class_members[]     = clone $result;
                            } 
                            if (isset($class_members)){
                                return $class_members;
                            } else {
                                return false; 
                            }
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
            $role = new Roles();                           
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
     * enrol user to multiple institutions
     * @param array $institution_enrolments
     */
    public function enroleToInstitutions($institution_enrolments){
        foreach ($institution_enrolments AS $value) {
            $this->role_id = $value['role_id'];
            $this->enroleToInstitution($value['institution_id']);
        }
    }
    
    /**
     * enrole user to institution
     * @global object $USER
     * @param int $institution_id
     * @return boolean 
     */
    public function enroleToInstitution($institution_id){
        global $USER, $PAGE, $CFG; 
        checkCapabilities('user:enroleToInstitution', $USER->role_id);
        $institution        = new Institution();
        $institution->id    = $institution_id; 
        $institution->load(); //load to generate readable feedback notifications
        /* ! Security ! check if role_id is permitted for $USER*/
        $role = new Roles();
        if ($role->checkRoleOrder($this->role_id) === null) {
            $PAGE->message[] = array('message' => 'Rolle für '.$this->username.' wurde wegen fehlender Berechtigung auf die Standard-Rolle zurückgesetzt.', 'icon' => 'fa fa-group text-warning');// Schließen und speichern
            $this->role_id    = $CFG->settings->standard_role; 
        } 
        
        if($this->checkInstitutionEnrolment($institution_id) > 0) {
            $db = DB::prepare('UPDATE institution_enrolments SET status = 1, creator_id = ?, role_id = ? WHERE user_id = ? AND institution_id = ?');
            if ($db->execute(array($USER->id, $this->role_id, $this->id, $institution_id))){
                $_SESSION['PAGE']->message[] = array('message' => 'Benutzereinschreibung (<strong>'.$this->username.'</strong>) der Institution <strong>'.$institution->institution.'</strong> aktualisiert.', 'icon' => 'fa fa-user text-success');// Schließen und speichern
            } else {
                $_SESSION['PAGE']->message[] = array('message' => 'Benutzereinschreibung (<strong>'.$this->username.'</strong>) der Institution <strong>'.$institution->institution.'</strong> konnte nicht aktualisiert werden.', 'icon' => 'fa fa-user text-warning');// Schließen und speichern
            }
        } else {
            $db = DB::prepare('INSERT INTO institution_enrolments (institution_id,user_id,role_id,creator_id,status) VALUES(?,?,?,?,1)');
            if ($db->execute(array($institution_id, $this->id, $this->role_id, $USER->id))){
                $_SESSION['PAGE']->message[] = array('message' => '<strong>'.$this->username.'</strong> in die Institution <strong>'.$institution->institution.'</strong> eingeschrieben.', 'icon' => 'fa fa-user text-success');// Schließen und speichern
                return true; 
            } else {
                $_SESSION['PAGE']->message[] = array('message' => '<strong>'.$this->username.'</strong> konnte nicht in die Institution <strong>'.$institution->institution.'</strong> eingeschrieben werden.', 'icon' => 'fa fa-user text-warning');// Schließen und speichern
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
        $institution        = new Institution();
        $institution->id    = $institution_id; 
        $institution->load();
        $db                 = DB::prepare('UPDATE institution_enrolments SET status = 0, creator_id = ? WHERE user_id = ? AND institution_id = ?');
        if ($db->execute(array($USER->id, $this->id, $institution_id))){
            $_SESSION['PAGE']->message[] = array('message' => 'Nutzer <strong>'.$this->username.'</strong> erfolgreich aus der Institution <strong>'.$institution->institution.'</strong> ausgeschrieben.', 'icon' => 'fa-user text-success');
        }
    }
    
    /**
     * enrole user to group
     * @param int $group_id
     * @param int $creator_id
     * @return boolean 
     */
    public function enroleToGroup($group_id_array){
        global $USER; 
        checkCapabilities('user:enroleToGroup', $USER->role_id);
        $groups = new Group();
        
        foreach ($group_id_array as $group_id) {
            $groups->id             = $group_id; 
            $groups->load();
            $db                     = DB::prepare('SELECT count(id) FROM groups_enrolments WHERE group_id = ? AND user_id = ?');
            $db->execute(array($group_id, $this->id));
            if($db->fetchColumn() > 0) {
                $db                 = DB::prepare('UPDATE groups_enrolments SET status = 1 WHERE group_id = ? AND user_id = ?');//Status 1 == enroled
                if ($db->execute(array($group_id, $this->id))){
                    $_SESSION['PAGE']->message[]    = array('message' => 'Nutzereinschreibung (<strong>'.$this->username.'</strong>) in <strong>'.$groups->group.'</strong> aktualisiert.', 'icon' => 'fa-user text-success');
                }
            } else { 
                $db                 = DB::prepare('INSERT INTO groups_enrolments (status,group_id,user_id,creator_id) VALUES (1,?,?,?)');  //Status 1 == enroled
                if ($db->execute(array($group_id, $this->id, $USER->id))){
                    $_SESSION['PAGE']->message[]    = array('message' => '<strong>'.$this->username.'</strong> in <strong>'.$groups->group.'</strong> eingeschrieben.', 'icon' => 'fa-user text-success');
                }
            }   
        }
    }
    
    /**
     * expel user from group
     * @param int $group_id
     * @return boolean 
     */
    public function expelFromGroup($group_id_array){
        global $USER; 
        checkCapabilities('user:expelFromGroup', $USER->role_id);
        $groups = new Group();
        foreach ($group_id_array as $group_id) {
            $db     = DB::prepare('SELECT COUNT(id) FROM groups_enrolments WHERE group_id = ? AND user_id = ?');
            $db->execute(array($group_id, $this->id));
            if($db->fetchColumn() >= 1) {
                $db = DB::prepare('UPDATE groups_enrolments SET status = 0, expel_time = NOW() WHERE group_id = ? AND user_id =? '); // Status 0 expelled
            if ($db->execute(array($group_id, $this->id))){
                $groups->load('id', $group_id);
                $_SESSION['PAGE']->message[]    = array('message' => '<strong>'.$this->username.'</strong> erfolgreich aus <strong>'.$groups->group.'</strong> ausgeschrieben.', 'icon' => 'fa-user text-success');
            }
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
    //public function import($institution_id, $role_id, $group_id, $import_file, $delimiter = ';'){
    public function import($params){
        foreach($params as $key => $val) {
            $$key = $val;
        }
        global $CFG, $USER, $PAGE, $_SESSION;
        checkCapabilities('menu:readuserImport', $USER->role_id);
        $row = 1;   //row counter
        ini_set("auto_detect_line_endings", true);
        if (($handle = fopen($import_file, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, $delimiter)) !== FALSE) {
                    $num = count($data);        
                if ($row == 1) {	// Hier werden die Felder verknüpft.
                    for ($c=0; $c < $num; $c++) {
                        if ($data[$c] == "username")   { $username_position       = $c; }
                        if ($data[$c] == "password")   { $password_position       = $c; }
                        if ($data[$c] == "role_id")    { $role_id_position        = $c; }
                        if ($data[$c] == "email")      { $email_position          = $c; }
                        if ($data[$c] == "confirmed")  { $confirmed_position      = $c; }
                        if ($data[$c] == "firstname")  { $firstname_position      = $c; }
                        if ($data[$c] == "lastname")   { $lastname_position       = $c; }
                        if ($data[$c] == "postalcode") { $postalcode_position     = $c; }
                        if ($data[$c] == "city")       { $city_position           = $c; }
                        if ($data[$c] == "state_id")   { $state_position          = $c; }
                        if ($data[$c] == "country_id") { $country_position        = $c; }
                        if ($data[$c] == "avatar_id")  { $avatar_position         = $c; }
                        if ($data[$c] == "group_id")   { $group_id_position       = $c; }
                        if ($data[$c] == "group")      { $group_position          = $c; }
                        if ($data[$c] == "grade_id")   { $grade_id_position       = $c; }
                        if ($data[$c] == "semester_id"){ $semester_id_position    = $c; }          
                    }    
                }
                $row++; //Tielzeile überspringen
                if ($row > 2) {	
                    $this->role_id = $CFG->settings->standard_role; //reset role id to avoid wrong permissions 
                    if (!isset($username_position))       { $this->username   = ''; }                         else { $this->username   = $data[$username_position]; }
                    if (!isset($firstname_position))      { $this->firstname  = ''; }                         else { $this->firstname  = $data[$firstname_position]; }
                    if (!isset($lastname_position))       { $this->lastname   = ''; }                         else { $this->lastname   = $data[$lastname_position]; }
                    if (!isset($email_position))          { $this->email      = ''; }                         else { $this->email      = trim($data[$email_position]); } //trim da sonst bei Leerzeichen die Validierung fehlschlägt!
                    if (!isset($postalcode_position))     { $this->postalcode = ''; }                         else { $this->postalcode = $data[$postalcode_position]; }
                    if (!isset($city_position))           { $this->city       = ''; }                         else { $this->city       = $data[$city_position]; }
                    if (!isset($state_position))          { $this->state_id   = $CFG->settings->standard_state; }       else { $this->state_id   = $data[$state_position]; }
                    if (!isset($country_position))        { $this->country_id = $CFG->settings->standard_country; }     else { $this->country_id = $data[$country_position]; }
                    if (!isset($avatar_position))         { $this->avatar_id  = $CFG->settings->standard_avatar_id; }   else { $this->avatar_id  = $data[$avatar_position]; }
                    if (!isset($password_position))       { $this->password   = 'password'; }                 else { $this->password   = $data[$password_position]; } //todo: besser Fehlermeldung, wenn Passwort nicht gesetzt
                    if (!isset($role_id_position))        { 
                        if (isset($role_id)){ $this->role_id  = $role_id; } else { $this->role_id  = $CFG->settings->standard_role; }
                    } else { 
                        $this->role_id    = $data[$role_id_position]; // security check moved to institution enrolmenT                        
                    }
                    if (!isset($group_position)){       // if no group is set check if group_id is set -> if this is also not set, use $group_id
                        if (!isset($group_id_position))  { $current_group_id = $group_id; }                  else { $current_group_id = $data[$group_id_position]; }
                    } else {
                        //check if group exists in db
                        $gp = new Group();
                        if ($data[$group_position] != ''){
                            $gp->group = $data[$group_position];
                        } else {
                            $gp->group = 'New Group';
                        }
                        if (!isset($semester_id_position)) { 
                            $gp->semester_id = $_SESSION['USER']->semester_id;  //if semester_id is not set use current semester_id
                        } else { 
                            $gp->semester_id = $data[$semester_id_position]; 
                        } 
                        /* institution */
                        $gp->institution_id = $institution_id;
                        /* grade */
                        if (!isset($grade_id_position)) {
                            $gr = new Grade();
                            $gr->load('institution_id', $gr->institution_id);
                            if ($gr->id == false){ // no grades in this institution --> use global grades
                                $gr->load('institution_id', 0);  
                            }
                            $gp->grade_id = $gr->id; //get a grade_id of institution --> todo: user should chose grade if not set
                        } else { 
                            $gp->grade_id = $data[$grade_id_position]; 
                        }
                        
                        if ($gp->add()){        
                            $current_group_id = $gp->id; //get new group_id
                        } else {
                            $current_group_id = $gp->id; //get existing group_id
                        }
                    }
                    if (!isset($confirmed_position))      { $this->confirmed  = '3'; }                        else { $this->confirmed  = $data[$confirmed_position]; } 

                    $validated_data = $this->validate();
                    if ($validated_data === true) {
                        $this->add($institution_id, intval($current_group_id));
                    } else {
                        $error[] = array('username' => $this->username, 'error'    => $validated_data); 
                        foreach ($error as $value) {
                            $PAGE->message[] = array('message' => 'Benutzer: '.$value['username'].' Error: '.array2str($value['error']), 'icon' => 'fa fa-group text-warning');// Schließen und speichern
                        }
                    }
                }
            }
        }
        fclose($handle);
        if (!isset($error)){ //if there are any error messages
            $PAGE->message[] = array('message' => 'Benutzerliste erfolgreich importiert', 'icon' => 'fa fa-group text-success');// Schließen und speichern
        } 
    }
    
    /**
     * get user list depending on $dependency
     * @param string $dependency
     * @param int $id
     * @return array of object 
     */
    public function userList($dependency = 'institution', $paginator = '', $lost = false, $institution_id = 'false', $role_id = 'false', $group_id = 'false' ){
        global $USER;
        $order_param = orderPaginator($paginator, array('id'        => 'us',
                                                        'username'  => 'us',
                                                        'firstname' => 'us',
                                                        'lastname'  => 'us',
                                                        'email'     => 'us', 
                                                        'postalcode'=> 'us',
                                                        'city'      => 'us'));  
        
        //checkCapabilities('user:userList', $USER->role_id); // kann eigentlich weg.
        $users = array();                      //Array of grades
        switch ($dependency) {
            case 'institution': if(checkCapabilities('user:userListComplete', $USER->role_id,false)){ //Global Admin
                                    if ($lost){
                                        $db = DB::prepare('SELECT us.* FROM users AS us, institution_enrolments AS ie 
                                                WHERE us.id = us.id AND ie.user_id = us.id AND ie.status = 0 '.$order_param); //hack id = id to user search
                                        $db->execute(); 
                                    } else {
                                        $db = DB::prepare('SELECT us.* FROM users AS us WHERE us.id = us.id '.$order_param); //hack id = id to user search
                                        $db->execute(); 
                                    }
                                } else if (checkCapabilities('user:userListInstitution', $USER->role_id,false)) { //Manager
                                    $db = DB::prepare('SELECT us.* FROM users AS us WHERE us.id = ANY (SELECT user_id FROM institution_enrolments 
                                                    WHERE institution_id = ? AND status = 1 AND role_id <> 1) '.$order_param); // HACK to prevent edit of super user
                                    $db->execute(array($USER->institution_id)); 
                                } else if (checkCapabilities('user:userListGroup', $USER->role_id,false)) { //Kursersteller
                                    $db = DB::prepare('SELECT DISTINCT us.* FROM users AS us, groups_enrolments AS ge, institution_enrolments AS ie, roles AS ro 
                                                        WHERE ie.institution_id = ? AND ie.status = 1
                                                        AND ie.role_id <> 1 /* HACK to prevent edit of super user*/
                                                        AND ro.id = ie.role_id 
                                                        AND ro.order_id > (SELECT order_id FROM roles WHERE id = ?)
                                                        AND ie.user_id = us.id 
                                                        AND ge.user_id = ie.user_id
                                                        AND ge.status = 1
                                                        AND ge.group_id = ANY (SELECT group_id FROM groups_enrolments 
                                                                                               WHERE user_id = ? AND status =  1)  '.$order_param);
                                    $db->execute(array($USER->institution_id, $USER->role_id, $USER->id));  
                                }                       
            break;
            case 'filter_institution':
                                if (checkCapabilities('user:userListInstitution', $USER->role_id,false)) { //Schuladmin
                                    if ($role_id == 'false'){ 
                                        $role_filter = ' '; 
                                    } else { 
                                        $role_filter = 'AND ro.id = '.intval($role_id); 
                                    }
                                    if ($group_id  == 'false'){ 
                                        $group_filter = 'ANY (SELECT group_id FROM groups_enrolments WHERE user_id = '.intval($USER->id).' AND status =  1)'; 
                                    } else { 
                                        $group_filter = intval($group_id); 
                                    }
                            
                                    $db = DB::prepare('SELECT DISTINCT us.* FROM users AS us, groups_enrolments AS ge 
                                                        WHERE us.id = ANY (SELECT ie.user_id FROM institution_enrolments AS ie,roles AS ro
                                                            WHERE ie.institution_id = ? 
                                                            AND ie.status = 1 
                                                            AND ie.role_id <> 1
                                                            AND ro.id = ie.role_id '.$role_filter.'
                                                            AND ro.order_id > (SELECT order_id FROM roles WHERE id = ?))
                                                        AND ge.user_id = us.id 
                                                        AND ge.status = 1
                                                        AND ge.group_id = '.$group_filter.' '.$order_param); // HACK to prevent edit of super user
                                    $db->execute(array($institution_id, $USER->role_id)); 
                                } else if (checkCapabilities('user:userListGroup', $USER->role_id,false)) { //Teacher
                                    if ($role_id == 'false'){ 
                                        $role_filter = ' '; 
                                    } else { 
                                        $role_filter = 'AND ro.id = '.intval($role_id); 
                                    }
                                    if ($group_id  == 'false'){ 
                                        $group_filter = 'ANY (SELECT group_id FROM groups_enrolments WHERE user_id = '.intval($USER->id).' AND status =  1)'; 
                                    } else { 
                                        $group_filter = intval($group_id); 
                                    }
                                    $db = DB::prepare('SELECT DISTINCT us.*, ro.role AS role_name FROM users AS us, groups_enrolments AS ge, institution_enrolments AS ie, roles AS ro 
                                                        WHERE ie.institution_id = ? AND ie.status = 1
                                                        AND ie.role_id <> 1 /* HACK to prevent edit of super user*/
                                                        AND ro.id = ie.role_id '.$role_filter.'  
                                                        AND ro.order_id > (SELECT order_id FROM roles WHERE id = ?)
                                                        AND ie.user_id = us.id 
                                                        AND ge.user_id = ie.user_id
                                                        AND ge.status = 1
                                                        AND ge.group_id = '.$group_filter.' '.$order_param);
                                    $db->execute(array($institution_id, $USER->role_id));  
                                }
                break;
            case 'institution_overview':$db = DB::prepare('SELECT DISTINCT us.*, ro.role AS role_name FROM users AS us, groups_enrolments AS ge, institution_enrolments AS ie, roles AS ro 
                                                        WHERE ie.institution_id = ? AND ie.status = 1
                                                        AND ro.id = ie.role_id 
                                                        AND ie.user_id = us.id 
                                                        AND ge.user_id = ie.user_id
                                                        AND ge.status = 1');
                                    $db->execute(array($institution_id)); 
                
                break; 
            default:  break;
        }

        while($result = $db->fetchObject()) {
            $sortableUser = new stdClass();
            if (checkCapabilities('user:shortUserList', $USER->role_id, false)){
                $sortableUser->id           = $result->id;
                $sortableUser->firstname    = $result->firstname;
                $sortableUser->lastname     = $result->lastname;
                $sortableUser->username     = $result->username;
                $sortableUser->avatar_id    = $result->avatar_id;
            }
            else {
                $sortableUser->id           = $result->id;
                $sortableUser->username     = $result->username;
                $sortableUser->firstname    = $result->firstname;
                $sortableUser->lastname     = $result->lastname;
                $sortableUser->email        = $result->email;
                $sortableUser->postalcode   = $result->postalcode;
                $sortableUser->city         = $result->city;
                $sortableUser->avatar_id    = $result->avatar_id;
                if (isset($result->role_name)){
                   $sortableUser->role_name = $result->role_name;
                }
            }
            $users[] = clone $sortableUser;
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
        if ($db->execute(array(md5($this->password), $this->confirmed, $this->id))){
            $_SESSION['PAGE']->message[] = array('message' => 'Passwort des Nutzers '.$this->firstname.' '.$this->lastname.' ('.$this->username.') wurde zurückgesetzt.', 'icon' => 'fa-key text-success');
        } else {
            $_SESSION['PAGE']->message[] = array('message' => 'Password konnte nicht zurückgesetzt werden.', 'icon' => 'fa-key text-warning');
        }
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
    public function getUsers($dependency = null, $paginator = '', $id = null, $group = null, $wallet_id = null){
        global $USER, $CFG;
        
        $order_param = orderPaginator($paginator, array('id'  => 'us', 
                                                        'username'  => 'us', 
                                                        'firstname' => 'us', 
                                                        'lastname'  => 'us'
                                                    ));
        
        checkCapabilities('user:getUsers', $USER->role_id);
        switch ($dependency) {
            case 'curriculum':  $db = DB::prepare('SELECT us.*, ie.role_id FROM users AS us, groups_enrolments AS ge, curriculum_enrolments AS ce, institution_enrolments as ie, groups as gr
                                                WHERE us.id = ge.user_id 
                                                AND ce.curriculum_id = ?
                                                AND ce.status = 1
                                                AND ce.group_id = ge.group_id
                                                AND ie.user_id = us.id
                                                AND ie.status = 1                                                      
                                                AND ge.status = 1 
                                                AND gr.institution_id = ie.institution_id
                                                AND gr.id = ge.group_id '.$order_param);
                                $db->execute(array($id)); 
                                while($result = $db->fetchObject()) {  
                                    $this->id           = $result->id;
                                    $this->username     = $result->username;
                                    $this->firstname    = $result->firstname; 
                                    $this->lastname     = $result->lastname; 
                                    $this->role_id      = $result->role_id; 
                                    $users[]            = clone $this; 
                                }               
                break;
            case 'wallet_shared':  $db = DB::prepare('SELECT us.*, ie.role_id, ws.permission FROM users AS us, groups_enrolments AS ge, curriculum_enrolments AS ce, institution_enrolments as ie, groups as gr, wallet_sharing AS ws
                                                WHERE us.id = ge.user_id 
                                                AND ce.curriculum_id = ?
                                                AND ce.status = 1
                                                AND ce.group_id = ge.group_id
                                                AND ie.user_id = us.id
                                                AND us.id IN (SELECT ws.reference_id FROM wallet AS wa, wallet_sharing AS ws, context AS co
                                                            WHERE co.context = ? 
                                                            AND co.context_id = ws.context_id 
                                                            AND wa.id = ws.wallet_id
                                                            AND ws.wallet_id = ?)
                                                AND ie.status = 1                                                      
                                                AND ge.status = 1 
                                                AND gr.institution_id = ie.institution_id
                                                AND gr.id = ge.group_id
                                                AND ws.wallet_id = ?
                                                AND ws.reference_id = us.id '.$order_param);
                                $db->execute(array($id, 'userFiles', $wallet_id, $wallet_id)); 
                                while($result = $db->fetchObject()) {  
                                    $this->id           = $result->id;
                                    $this->username     = $result->username;
                                    $this->firstname    = $result->firstname; 
                                    $this->lastname     = $result->lastname; 
                                    $this->role_id      = $result->role_id; 
                                    switch ($result->permission) {
                                        case '0':       $this->permission = 'lesezugriff';
                                            break;
                                        case '1':       $this->permission = 'kommentierbar';
                                            break;
                                        case '2':       $this->permission = 'schreibzugriff';
                                            break;

                                        default:
                                            break;
                                    }
                                   
                                    $users[]            = clone $this; 
                                }               
                break;
            case 'course':  $db = DB::prepare('SELECT us.*, ie.role_id FROM users AS us, groups_enrolments AS ge, curriculum_enrolments AS ce, institution_enrolments as ie, groups as gr
                                                WHERE us.id = ge.user_id 
                                                AND ce.curriculum_id = ?
                                                AND ce.status = 1
                                                AND ce.group_id = ge.group_id
                                                AND ie.user_id = us.id
                                                AND ie.status = 1
                                                AND ge.group_id = ?                                                      
                                                AND ge.status = 1 
                                                AND ie.role_id = (SELECT id FROM roles where id = ?)
                                                AND gr.institution_id = ie.institution_id
                                                AND gr.id = ge.group_id
                                                '.$order_param);
                
                            $db->execute(array($id, $group, 0)); 
                            while($result = $db->fetchObject()) {  
                                    $this->id           = $result->id;
                                    $this->username     = $result->username;
                                    $this->firstname    = $result->firstname; 
                                    $this->lastname     = $result->lastname; 
                                    $this->role_id      = $result->role_id; 
                                    $role = new Roles(); 
                                    $role->id           = $this->role_id;
                                    $role->load(); 
                                    $this->role_name    = $role->role;
                                    // get online status 
                                    if (checkCapabilities('dashboard:globalAdmin', $USER->role_id, FALSE)){ //todo add capability for online status
                                        $timestamp = (time()-strtotime($result->last_action));
                                        $timeout   = ($CFG->settings->timeout)*60;
                                        if (intval($timestamp) <= intval($timeout)){
                                            $this->online       = 'online';
                                        } else {
                                            $this->online       = 'offline';
                                        }
                                    }
                                    if (!checkCapabilities('objectives:setStatus', $this->role_id, FALSE)){ //only add to list if not able to set status == teacher
                                        $ena = new EnablingObjective();
                                        $this->completed = $ena->getPercentageOfCompletion($id, $this->id);
                                    }
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
                                $this->load('id', $this->id, false);
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
   public function checkLoginData($resetPW = false){
        if ($resetPW){
            $db     = DB::prepare('SELECT COUNT(id) FROM users WHERE UPPER(username) = UPPER(?) AND token=?');
            $db->execute(array($this->username, $this->token));
            $count  = $db->fetchColumn();
            if ($count > 0){
                return true;
            } else {
                return false; 
            }
        } else {
            $db     = DB::prepare('SELECT COUNT(id) FROM users WHERE UPPER(username) = UPPER(?) AND password=?');
            $db->execute(array($this->username, $this->password));
            $count  = $db->fetchColumn();
            if ($count > 0){
                return true;
            } else {
                return false; 
            }
        }
   }
   
   public function checkInstitutionEnrolment($id){
       $db     = DB::prepare('SELECT COUNT(id) FROM institution_enrolments WHERE institution_id = ? AND user_id = ?');
        $db->execute(array($id, $this->id));
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
        Statistic::setStatistics(1, $this->id); //statistic count logins
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
        $db->execute(array($CFG->settings->timeout));
        return $db->fetchColumn();
    }
    
   public function userLogout(){
        global $CFG;
        $db = DB::prepare('SELECT last_action FROM users WHERE id = ?');
        $db->execute(array($this->id));
        $this->last_action = $db->fetchColumn();
        //set last_action
        $min= intval($CFG->settings->timeout);
        $db = DB::prepare("UPDATE users SET last_action = TIMESTAMPADD(MINUTE,-$min, ?) WHERE id = ?");
        $db->execute(array($this->last_action, $this->id));
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
        $db = DB::prepare('SELECT cu.curriculum, cu.id, cu.grade_id, cu.icon_id, cu.color, gp.id AS group_id, gp.semester_id, gp.groups 
                            FROM curriculum_enrolments AS ce, groups AS gp, institution_enrolments AS ie, groups_enrolments AS ge, curriculum AS cu 
                            WHERE cu.id = ce.curriculum_id 
                            AND ce.status = 1 
                            AND gp.id = ce.group_id 
                            AND ge.user_id = ?
                            AND ge.group_id = gp.id
                            AND ge.status = 1 
                            AND ie.status = 1 
                            AND ie.user_id = ge.user_id
                            AND gp.institution_id = ie.institution_id
                            ORDER BY gp.groups, cu.curriculum ASC');
        $db->execute(array($this->id));
        
        while($result = $db->fetchObject()) { 
            $c                      = new Curriculum();
            $c->id                  = $result->id;
            //$c->base_curriculum_id  = null; //if no niveau / level is set base_curriculum_id = null;
            $e                      = new EnablingObjective();
            $result->completed      = $e->getPercentageOfCompletion($result->id, $this->id);
            $data[]                 = $result;         
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
            return $CFG->settings->standard_avatar;
        }
    }
    
    function resolveUserId($id, $dependency = 'full'){
        $db = DB::prepare('SELECT firstname, lastname, username, avatar_id FROM users WHERE id =?');
        $db->execute(array($id));
        if ($result = $db->fetchObject()){
            switch ($dependency) {
                case 'full':     return $result->firstname.' '.$result->lastname.' ('.$result->username.')';
                    break;
                case 'name':     return $result->firstname.' '.$result->lastname;
                    break;
                case 'username': return $result->username;
                    break;
                case 'avatar':   return $result->avatar_id;
                    break;
                default:
                    break;
            }
        } else {
            return 'Gelöschter Nutzer';
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
    
    public function rejectTerms(){ // important for guest users
        $db = DB::prepare('Delete FROM accept_terms WHERE user_id = ?');//Status 1 == accepted
        return $db->execute(array($this->id));
    }
    
    public function exists($key, $value){
        $db = DB::prepare('SELECT count(id) FROM users WHERE '.$key.' = ?');
        $db->execute(array($value));
        if($db->fetchColumn() > 0) {
            return true;
        } else { 
            return false;
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
        $db = DB::prepare('SELECT DISTINCT curriculum_id FROM enablingObjectives WHERE id = ANY (SELECT DISTINCT reference_id FROM user_accomplished WHERE user_id = ?)');
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
    
    public function setChildren($parent, $child){
        global $USER;
        checkCapabilities('user:parentalAuthority', $USER->role_id);
        $db = DB::prepare('SELECT count(id) FROM parental_authority WHERE parent_id = ? AND child_id = ?');
        $db->execute(array($parent, $child));
        if($db->fetchColumn() > 0) {
            // entry already exists
             $_SESSION['PAGE']->message[] = array('message' => 'Das Kind wurde bereits dem Erziehungsberechtigten zugeordnet.', 'icon' => 'fa-child text-info');    
            return true;
        } else { 
            $db = DB::prepare('INSERT INTO parental_authority (parent_id,child_id) VALUES (?,?)');//Status 1 == accepted
             $_SESSION['PAGE']->message[] = array('message' => 'Das Kind wurde dem Erziehungsberechtigten zugeordnet.', 'icon' => 'fa-child text-success');    
            return $db->execute(array($parent, $child));
        }
    }
    public function unsetChildren($parent, $child){
        global $USER;
        checkCapabilities('user:parentalAuthority', $USER->role_id);
        $db = DB::prepare('DELETE FROM parental_authority WHERE parent_id = ? AND child_id = ?');
        if($db->execute(array($parent, $child))) {
            // entry already exists
             $_SESSION['PAGE']->message[] = array('message' => 'Die Zuorndung wurde aufgehoben.', 'icon' => 'fa-child text-success');    
            return true;
        } else { 
            return false; // do nothing
        }
    }
    
    
    public function getChildren(){
        global $USER;
        checkCapabilities('user:parentalAuthority', $USER->role_id);
        $db     = DB::prepare('SELECT child_id FROM parental_authority WHERE parent_id = ?');
        $db->execute(array($this->id));
        $users  = array();
        while($result = $db->fetchObject()) { 
            $this->id   = $result->child_id;
            $this->load('id', $this->id, true);
            $users[]    = clone $this; 
        }
        return $users;
    }
    /**
    * function used during the install process to set up creator id to new admin
    * @return boolean
    */
    public function dedicate(){ // only use during install
        global $USER;
        checkCapabilities('install:dedicate', $USER->role_id);
        $db = DB::prepare('UPDATE users SET creator_id = ?');
        return $db->execute(array($this->creator_id));
    }
   
}
