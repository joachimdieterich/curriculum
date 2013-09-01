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
        switch ($condition) {
            case 'semester': $query = sprintf("SELECT COUNT(id) FROM groups WHERE groups = '%s' AND semester_id = '%s'",
                                    mysql_real_escape_string($this->group),
                                    mysql_real_escape_string($this->semester_id));
                break;
            default:         $query = sprintf("SELECT COUNT(id) FROM groups WHERE groups = '%s'",
                                    mysql_real_escape_string($this->group));    
               break;
        }

        $result = mysql_query($query);
        list($count) = mysql_fetch_row($result);
        if($count >= 1) { 
            return false;
        } else {        
            $query = sprintf("INSERT INTO groups (groups,description,grade_id,semester_id,institution_id,creator_id) 
                                                  VALUES ('%s','%s','%s','%s','%s','%s')",
                                            mysql_real_escape_string($this->group),
                                            mysql_real_escape_string($this->description),
                                            mysql_real_escape_string($this->grade_id),
                                            mysql_real_escape_string($this->semester_id),
                                            mysql_real_escape_string($this->institution_id),
                                            mysql_real_escape_string($this->creator_id));
            return mysql_query($query);		
        }
    }
    
    /**
     * Update group
     * @return boolean 
     */
    public function update(){
        $query = sprintf("UPDATE groups 
                SET groups = '%s', description = '%s', grade_id = '%s', semester_id = '%s', institution_id = '%s',
                creator_id = '%s'
                WHERE id = '%s'",
                mysql_real_escape_string($this->group),
                mysql_real_escape_string($this->description),
                mysql_real_escape_string($this->grade_id),
                mysql_real_escape_string($this->semester_id),
                mysql_real_escape_string($this->institution_id),
                mysql_real_escape_string($this->creator_id),
                mysql_real_escape_string($this->id));
        return mysql_query($query);
    }
    
    /**
     * Delete group
     * @return mixed 
     */
    public function delete(){
        $query = sprintf("SELECT id 
                          FROM curriculum_enrolments
                          WHERE group_id = '%s' AND status_id = 1",
                          mysql_real_escape_string($this->id));
        $result = mysql_query($query);
        if ($result && mysql_num_rows($result)){
            return false;
        } else {
            $query = sprintf("DELETE FROM groups WHERE id='%s'",
                            mysql_real_escape_string($this->id));
            return mysql_query($query);
        } 
    } 
    
    /**
     * Load group with id $this->id 
     */
    public function load(){
        $query = sprintf("SELECT gr.*, se.semester 
                            FROM groups AS gr, semester AS se 
                            WHERE gr.id='%s'
                            AND gr.semester_id = se.id",
                        mysql_real_escape_string($this->id));
        $result = mysql_query($query);
        $row = mysql_fetch_assoc($result);
        $this->group            = $row["groups"];
        $this->description      = $row["description"];
        $this->grade_id         = $row["grade_id"];
        $this->semester_id      = $row["semester_id"];
        $this->semester         = $row["semester"];
        $this->institution_id   = $row["institution_id"];
        $this->creation_time    = $row["creation_time"];
        $this->creator_id       = $row["creator_id"];
        
    }
    
    /**
     * Checks if group is enrold in a curriculum
     * @param int $curriculum_id
     * @param int $status
     * @return int 
     */
    public function checkEnrolment($curriculum_id, $status = '1'){
        $query = sprintf("SELECT count(id) FROM curriculum_enrolments WHERE curriculum_id = '%s' AND group_id = '%s' AND status = '%s'",
                            mysql_real_escape_string($curriculum_id), 
                            mysql_real_escape_string($this->id),
                            mysql_real_escape_string($status));
        $result = mysql_query($query);
        list($value) = mysql_fetch_row($result);
        if ($value > 0){
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
        if ($this->checkEnrolment($curriculum_id)) {
            $query = sprintf("UPDATE curriculum_enrolments 
                                SET status = 0, creator_id = '%s', creation_time = NOW()
                                WHERE group_id = '%s' AND curriculum_id = '%s'",
                                mysql_real_escape_string($user_id),
                                mysql_real_escape_string($this->id), 
                                mysql_real_escape_string($curriculum_id)); //Status 1 == eingeschrieben
            return mysql_query($query);
        } 
    }
    
    /**
     * enrol user to curriculum
     * @param int $user_id
     * @param int $curriculum_id
     * @return boolean 
     */
    public function enrol($user_id, $curriculum_id){
        if ($this->checkEnrolment($curriculum_id, 0)) {
            $query = sprintf("UPDATE curriculum_enrolments 
                                SET status = 1, creator_id = '%s', creation_time = NOW()
                                WHERE group_id = '%s' AND curriculum_id = '%s'",
                                mysql_real_escape_string($user_id),
                                mysql_real_escape_string($this->id), 
                                mysql_real_escape_string($curriculum_id)); //Status 1 == eingeschrieben
            return mysql_query($query);
        } else {
            $query = sprintf("INSERT INTO curriculum_enrolments (status,group_id,curriculum_id,creator_id) 
                                VALUES (1,'%s','%s','%s')",
                                mysql_real_escape_string($this->id), 
                                mysql_real_escape_string($curriculum_id),
                                mysql_real_escape_string($user_id)); //Status 1 == eingeschrieben
            return mysql_query($query);
        }
    }
    
    /**
     * assume group members to new group
     * @global object $USER 
     */
    public function changeSemester(){
        global $USER;
        // Load group members
        $users = new User(); 
        $group_members = $users->getGroupMembers('group', $this->id); 
        // Load new group id
        $query = sprintf("SELECT id FROM groups WHERE UPPER(groups) = UPPER('%s') AND semester_id = '%s'",
                                mysql_real_escape_string($this->group),
                                mysql_real_escape_string($this->semester_id));
        $result = mysql_query($query);
        $row = mysql_fetch_assoc($result); 
        $this->id = $row["id"];
        $this->load();
        if  (count($group_members) > 0){ //if there are Group members
            foreach($group_members as $key=>$value) { //Die Benutzer in die neue Lerngruppe einschreiben
                $users->id = $value;
                $users->enroleToGroup($this->id, $USER->id);  
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
            case 'course':$query = sprintf("SELECT gp.*, gr.grade, se.semester 
                                            FROM groups AS gp, semester AS se, grade AS gr
                                            WHERE gp.semester_id = se.id 
                                            AND gp.grade_id = gr.id
                                            AND gp.id='%s'",
                                                mysql_real_escape_string($id));
                break;

            case 'group':    if ($USER->role_id == 3 OR $USER->role_id == 2){ // 3 = Rolle Lehrer, 2 = Tutor //Bedingung Lehrer müssen in die Klasse eingeschrieben sein, oder sie erstellt haben    
                            $query = sprintf("SELECT DISTINCT gp.*, gr.grade, yr.semester, ins.institution, us.username 
                                FROM groups AS gp, groups_enrolments AS gpe, grade AS gr, semester AS yr, institution AS ins, users AS us
                                WHERE gp.id = ANY (SELECT id FROM groups_enrolments 
                                                                WHERE user_id = '%s' 
                                                                OR creator_id = '%s')
                                AND gr.id = gp.grade_id 
                                AND yr.id = gp.semester_id 
                                AND ins.id = gp.institution_id 
                                AND us.id = gp.creator_id",
                                        mysql_real_escape_string($id),
                                        mysql_real_escape_string($id));
                        } else 
                            if ($USER->role_id == 4 OR $USER->role_id == 1){
                            $query = sprintf("SELECT gp.*, gr.grade, yr.semester, ins.institution, us.username 
                                FROM groups AS gp, grade AS gr, semester AS yr, institution AS ins, users AS us
                                WHERE gp.institution_id = ANY (SELECT institution_id FROM institution_enrolments WHERE user_id = '%s')
                                AND gr.id = gp.grade_id 
                                AND yr.id = gp.semester_id 
                                AND ins.id = gp.institution_id 
                                AND us.id = gp.creator_id",
                                mysql_real_escape_string($id));
                        }
                 break;
            case 'user': $query = sprintf("SELECT gp.*, gr.grade, sem.semester, ins.institution AS institution_id, usr.username AS creator_id
                                                FROM groups AS gp, semester AS sem, institution AS ins, users AS usr, grade AS gra
                                                WHERE sem.id = gp.semester_id
                                                AND gp.grade_id = gr.id
                                                AND ins.id = gp.institution_id
                                                AND usr.id = gp.creator_id
                                                AND gp.id = ANY (SELECT group_id FROM groups_enrolments WHERE user_id = '%s')",
                                mysql_real_escape_string($id));  
                 break; 
        default: break; 
        }
        
        $result = mysql_query($query);

        if ($result && mysql_num_rows($result)) {
            while($row = mysql_fetch_assoc($result)) { 
                    $this->id                   = $row['id'];
                    $this->group                = $row['groups'];
                    $this->description          = $row['description'];
                    $this->grade_id             = $row['grade_id'];
                    $this->grade                = $row['grade'];
                    $this->semester_id          = $row['semester_id'];
                    $this->semester             = $row['semester'];
                    $this->institution_id       = $row['institution_id'];
                    $this->creation_time       = $row['creation_time'];
                    $this->creator_id           = $row['creator_id'];
                    if ($dependency == 'group'){
                        $this->institution          = $row['institution'];
                        $this->creator              = $row['username'];
                    }
                    $groups[] = clone $this;        //it has to be clone, to get the object and not the reference
            } 
            return $groups;
        } else {return NULL;}  
    }
}
?>