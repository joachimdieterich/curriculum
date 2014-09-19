<?php
/**
 * Group object can add, update, delete and get data from groups db
 * 
 * @example
 * // Add new Group <br>
 * $new_group = new Group(); <br>
 * 
 * @abstract This file is part of curriculum - http://www.joachimdieterich.de
 * @package core
 * @filename group.class.php
 * @copyright 2013 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2013.06.06 22:11
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
class Group {
    /**
     * ID of Group
     * @var int
     */
    public $id = null;
    /**
     * Name of Group
     * @var string
     */
    public $group = null; 
    /**
     * Description of Grade
     * @var string
     */
    public $description = null; 
    /**
     * id of grade
     * @var int 
     */
    public $grade_id = null; 
    /**
     * name of grade
     * @var string
     */
    public $grade = null; 
    /**
     * id of semester
     * @var int
     */
    public $semester_id = null; 
    /**
     * name of semester
     * @var string
     */
    public $semester = null; 
    /**
     * ID of institution to which Grade belongs to
     * @var int
     */
    public $institution_id = null; 
    /**
     * name of institution
     * @var string
     */
    public $institution = null; 
    /**
     * Timestamp when Grade was created
     * @var timestamp
     */
    public $creation_time = null; 
    /**
     * ID of User who created this Grade
     * @var int
     */
    public $creator_id = null; 
    /**
     * name of creator
     * @var string
     */
    public $creator = null;
   
    
    /**
     * add group to db
     * @param boolean $condition
     * @return boolean 
     */
    public function add($condition = false){
        global $USER;
        if (checkCapabilities('groups:add', $USER->role_id)){
            switch ($condition) {
                case 'semester': $db = DB::prepare('SELECT COUNT(id) FROM groups WHERE groups = ? AND semester_id = ?');
                                $db->execute(array($this->group, $this->semester_id));
                    break;
                default:         $db = DB::prepare('SELECT COUNT(id) FROM groups WHERE groups = ?');
                                $db->execute(array($this->group));    
                break;
            }
            if($db->fetchColumn() >= 1) { 
                return false;
            } else {     
                $db = DB::prepare('INSERT INTO groups (groups,description,grade_id,semester_id,institution_id,creator_id) 
                                                    VALUES (?,?,?,?,?,?)');
                return $db->execute(array($this->group, $this->description, $this->grade_id, $this->semester_id, $this->institution_id, $this->creator_id));
            }
        }
    }
    
    /**
     * Update group
     * @return boolean 
     */
    public function update(){
        global $USER;
        if (checkCapabilities('groups:update', $USER->role_id)){
            $db = DB::prepare('UPDATE groups SET groups = ?, description = ?, grade_id = ?, semester_id = ?, institution_id = ?,creator_id = ? WHERE id = ?');
            return $db->execute(array($this->group, $this->description, $this->grade_id, $this->semester_id, $this->institution_id, $this->creator_id, $this->id));
        }
    }
    
    /**
     * Delete group
     * @return mixed 
     */
    public function delete($creator_id = null){
        /*if ($creator_id != null) { // if function is called by request-php --> required by checkCapabilities()
            $user = new USER();

            $user->load('id', $creator_id);
            $role_id = $user->role_id;
        } else {
            $role_id = $USER->role-id;
        } */
        global $USER;
        if (checkCapabilities('groups:delete', $USER->role_id)){
            $db = DB::prepare('SELECT id FROM curriculum_enrolments WHERE group_id = ? AND status = 1');
            $db->execute(array($this->id));
            $result = $db->fetchObject();
            if ($result){
                return false;
            } else {
                $db = DB::prepare('DELETE FROM groups WHERE id = ?');
                return $db->execute(array($this->id));
            } 
        }
    } 
    
    /**
     * Load group with id $this->id 
     */
    public function load(){
        $db = DB::prepare('SELECT gr.*, se.semester FROM groups AS gr, semester AS se WHERE gr.id = ? AND gr.semester_id = se.id');
        $db->execute(array($this->id));              
        $result = $db->fetchObject();
        $this->group            = $result->groups;
        $this->description      = $result->description;
        $this->grade_id         = $result->grade_id;
        $this->semester_id      = $result->semester_id;
        $this->semester         = $result->semester;
        $this->institution_id   = $result->institution_id;
        $this->creation_time    = $result->creation_time;
        $this->creator_id       = $result->creator_id;  
    }
    
    /**
     * Checks if group is enrold in a curriculum
     * @param int $curriculum_id
     * @param int $status
     * @return int 
     */
    public function checkEnrolment($curriculum_id, $status = '1'){
        $db = DB::prepare('SELECT count(id) FROM curriculum_enrolments WHERE curriculum_id = ? AND group_id = ? AND status = ?');
        $db->execute(array($curriculum_id, $this->id, $status));
        if ($db->fetchColumn() > 0){
            return true;
        } else {
            return false; 
        }    
    }
    
    /**
     * expel group from curriclum
     * @param int $user_id
     * @param int $curriculum_id
     * @return boolean 
     */
    public function expel($user_id, $curriculum_id){
        global $USER;
        if (checkCapabilities('groups:expel', $USER->role_id)){
            if ($this->checkEnrolment($curriculum_id)) {
                $db = DB::prepare('UPDATE curriculum_enrolments SET status = 0, creator_id = ?, creation_time = NOW()
                                    WHERE group_id = ? AND curriculum_id = ?'); //Status 0 == not enroled
                return $db->execute(array($user_id, $this->id, $curriculum_id));
            } 
        }
    }
    
    /**
     * enrol user to curriculum
     * @param int $user_id
     * @param int $curriculum_id
     * @return boolean 
     */
    public function enrol($user_id, $curriculum_id){
        global $USER;
        if (checkCapabilities('groups:enrol', $USER->role_id)){
            if ($this->checkEnrolment($curriculum_id, 0)) {
                $db = DB::prepare('UPDATE curriculum_enrolments SET status = 1, creator_id = ?, creation_time = NOW()
                                    WHERE group_id = ? AND curriculum_id = ?'); //Status 1 == eingeschrieben
                return $db->execute(array($user_id, $this->id, $curriculum_id)); 
            } else {
                $db = DB::prepare('INSERT INTO curriculum_enrolments (status,group_id,curriculum_id,creator_id) 
                                    VALUES (1,?,?,?)');//Status 1 == eingeschrieben
                return $db->execute(array($this->id, $curriculum_id, $user_id));
            }
        }
    }
    
    /**
     * assume group members to new group
     * @global object $USER 
     */
    public function changeSemester(){
        global $USER;
        if (checkCapabilities('groups:changeSemester', $USER->role_id)){
            global $USER;
            // Load group members
            $users = new User(); 
            $group_members = $users->getGroupMembers('group', $this->id); 
            // Load new group id
            $db = DB::prepare('SELECT id FROM groups WHERE UPPER(groups) = UPPER(?) AND semester_id = ?');
            $db->execute(array($this->group, $this->semester_id));
            $result = $db->fetchObject(); 
            $this->id = $result->id;
            $this->load();
            if  (count($group_members) > 0){ //if there are Group members
                foreach($group_members as $key=>$value) { //Die Benutzer in die neue Lerngruppe einschreiben
                    $users->id = $value;
                    $users->enroleToGroup($this->id, $USER->id);  
                } 
            }
        }
    }
    /**
     * Get all availible groups of current institution
     * @return array of groups objects 
     */
    public function getGroups($dependency = null, $id = null){
        global $USER;
        switch ($dependency) {
            case 'course':  $db = DB::prepare('SELECT gp.*, gr.grade, se.semester FROM groups AS gp, semester AS se, grade AS gr
                                            WHERE gp.semester_id = se.id AND gp.grade_id = gr.id AND gp.id = ?');
                            $db->execute(array($id));
                break;

            case 'group':   /*if ($USER->role_id == 3 OR $USER->role_id == 2){ // 3 = Rolle Lehrer, 2 = Tutor //Bedingung Lehrer müssen in die Klasse eingeschrieben sein, oder sie erstellt haben    
                            $db = DB::prepare('SELECT DISTINCT gp.*, gr.grade, yr.semester, ins.institution, us.username 
                                FROM groups AS gp, groups_enrolments AS gpe, grade AS gr, semester AS yr, institution AS ins, users AS us
                                WHERE gp.id = ANY (SELECT id FROM groups_enrolments 
                                                                WHERE user_id = ? 
                                                                OR creator_id = ?)
                                AND gr.id = gp.grade_id 
                                AND yr.id = gp.semester_id 
                                AND ins.id = gp.institution_id 
                                AND us.id = gp.creator_id');
                            $db->execute(array($id,$id));
                        } else if ($USER->role_id == 4 OR $USER->role_id == 1){*/
                            $db = DB::prepare('SELECT gp.*, gr.grade, yr.semester, ins.institution, us.username 
                                FROM groups AS gp, grade AS gr, semester AS yr, institution AS ins, users AS us
                                WHERE gp.institution_id = ANY (SELECT institution_id FROM institution_enrolments WHERE user_id = ?)
                                AND gr.id = gp.grade_id 
                                AND yr.id = gp.semester_id 
                                AND ins.id = gp.institution_id 
                                AND us.id = gp.creator_id');
                            $db->execute(array($id));
                        /*}*/
                 break;
            case 'user': $db = DB::prepare('SELECT gp.*, gr.grade, sem.semester, ins.institution AS institution_id, usr.username AS creator_id
                                                FROM groups AS gp, semester AS sem, institution AS ins, users AS usr, grade AS gr
                                                WHERE sem.id = gp.semester_id
                                                AND gp.grade_id = gr.id
                                                AND ins.id = gp.institution_id
                                                AND usr.id = gp.creator_id
                                                AND gp.id = ANY (SELECT group_id FROM groups_enrolments WHERE user_id = ?)');
                         $db->execute(array($id));
                 break; 
        default: break; 
        }
        
        while($result = $db->fetchObject()) { 
                $this->id                   = $result->id;
                $this->group                = $result->groups;
                $this->description          = $result->description;
                $this->grade_id             = $result->grade_id;
                $this->grade                = $result->grade;
                $this->semester_id          = $result->semester_id;
                $this->semester             = $result->semester;
                $this->institution_id       = $result->institution_id;
                $this->creation_time        = $result->creation_time;
                $this->creator_id           = $result->creator_id;
                if ($dependency == 'group'){
                    $this->institution          = $result->institution;
                    $this->creator              = $result->username;
                }
                $groups[] = clone $this;        //it has to be clone, to get the object and not the reference
        } 
        if (isset($groups)) {    
            return $groups;
        } else {return NULL;}  
    }
    
    /**
    * function used during the install process to set up creator id to new admin
    * @return boolean
    */
    public function dedicate(){ // only use during install
        $db = DB::prepare('UPDATE groups SET creator_id = ?');
        if ($db->execute(array($this->creator_id))){
            $db = DB::prepare('UPDATE groups_enrolments SET creator_id = ?');
            return $db->execute(array($this->creator_id));
        }
    }
}
?>