<?php
/**
* Group object can add, update, delete and get data from groups db
* 
* @abstract This file is part of curriculum - http://www.joachimdieterich.de
* @package core
* @filename group.class.php
* @copyright 2013 Joachim Dieterich
* @author Joachim Dieterich
* @date 2013.06.06 22:11
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
class Group {
    /**
     * ID of Group
     * @var int
     */
    public $id;
    /**
     * Name of Group
     * @var string
     */
    public $group; 
    /**
     * Description of Grade
     * @var string
     */
    public $description; 
    /**
     * id of grade
     * @var int 
     */
    public $grade_id; 
    /**
     * name of grade
     * @var string
     */
    public $grade; 
    /**
     * id of semester
     * @var int
     */
    public $semester_id; 
    /**
     * name of semester
     * @var string
     */
    public $semester; 
    /**
     * ID of institution to which Grade belongs to
     * @var int
     */
    public $institution_id; 
    /**
     * name of institution
     * @var string
     */
    public $institution; 
    /**
     * Timestamp when Grade was created
     * @var timestamp
     */
    public $creation_time; 
    /**
     * ID of User who created this Grade
     * @var int
     */
    public $creator_id; 
    /**
     * name of creator
     * @var string
     */
    public $creator;
   
    /**
     * add group to db
     * @param boolean $condition
     * @return boolean 
     */
    public function add($condition = false){
        global $USER;
        checkCapabilities('groups:add', $USER->role_id);
        switch ($condition) {
            case 'semester':    $db = DB::prepare('SELECT COUNT(id) FROM groups WHERE groups = ? AND semester_id = ?');
                                $db->execute(array($this->group, $this->semester_id));
                break;
            default:            $db = DB::prepare('SELECT COUNT(id) FROM groups WHERE groups = ?');
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
    
    /**
     * Update group
     * @return boolean 
     */
    public function update(){
        global $USER;
        checkCapabilities('groups:update', $USER->role_id);
        $db = DB::prepare('UPDATE groups SET groups = ?, description = ?, grade_id = ?, semester_id = ?, institution_id = ?,creator_id = ? WHERE id = ?');
        return $db->execute(array($this->group, $this->description, $this->grade_id, $this->semester_id, $this->institution_id, $this->creator_id, $this->id));
    }
    
    /**
     *
     * @global object $USER
     * @param int $creator_id
     * @return boolean 
     */
    public function delete(){
        global $USER;
        checkCapabilities('groups:delete', $USER->role_id);
        $db = DB::prepare('SELECT id FROM curriculum_enrolments WHERE group_id = ? AND status = 1');
        $db->execute(array($this->id));
        if ($db->fetchObject()){
            return false;
        } else {
            $db = DB::prepare('DELETE FROM groups WHERE id = ?');
            return $db->execute(array($this->id));
        }
    } 
    
    /**
     * Load group with id $this->id 
     */
    public function load(){
        $db     = DB::prepare('SELECT gr.*, se.semester FROM groups AS gr, semester AS se WHERE gr.id = ? AND gr.semester_id = se.id');
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
        checkCapabilities('groups:expel', $USER->role_id);
        if ($this->checkEnrolment($curriculum_id)) {
            $db = DB::prepare('UPDATE curriculum_enrolments SET status = 0, creator_id = ?, creation_time = NOW()
                                WHERE group_id = ? AND curriculum_id = ?'); //Status 0 == not enroled
            return $db->execute(array($user_id, $this->id, $curriculum_id));
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
        checkCapabilities('groups:enrol', $USER->role_id);
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
    
    /**
     * assume group members to new group
     * @global object $USER 
     */
    public function changeSemester(){
        global $USER;
        checkCapabilities('groups:changeSemester', $USER->role_id);
        $users          = new User(); 
        error_log('groupid '.$this->id);
        $group_members  = $users->getGroupMembers('group', $this->id);                                              // Load group members
        $db             = DB::prepare('SELECT id FROM groups WHERE UPPER(groups) = UPPER(?) AND semester_id = ?');  // Load new group id
        $db->execute(array($this->group, $this->semester_id));
        $result         = $db->fetchObject(); 
        $this->id       = $result->id;
        $this->load();
        if  (count($group_members) > 0){                                    // Mitglieder in der Gruppe?
            foreach($group_members as $value) {                             // Mitglieder in die neue Lerngruppe einschreiben
                $users->id = $value;
                $users->enroleToGroup($this->id, $USER->id);  
            } 
        }
    }
 
    /**
     * Get all availible groups of current institution
     * @global object $USER
     * @param type $dependency
     * @param type $id
     * @return array of groups objects 
     */
    public function getGroups($dependency = null, $id = null, $paginator = ''){
        global $USER;
        $order_param = orderPaginator($paginator, array('groups'        => 'gp',
                                                        'description'   => 'gp', 
                                                        'grade'         => 'gr',
                                                        'semester'      => 'se', 
                                                        'institution'   => 'ins'));        
        $groups      = array();
        switch ($dependency) {
            case 'course':  $db = DB::prepare('SELECT gp.*, gr.grade, se.semester FROM groups AS gp, semester AS se, grade AS gr
                                            WHERE gp.semester_id = se.id AND gp.grade_id = gr.id AND gp.id = ? 
                                            '.$order_param);
                            $db->execute(array($id));
                break;

            case 'group':   $db = DB::prepare('SELECT gp.*, gr.grade, se.semester, ins.institution, us.username 
                                FROM groups AS gp, grade AS gr, semester AS se, institution AS ins, users AS us
                                WHERE gp.institution_id = ANY (SELECT institution_id FROM institution_enrolments WHERE user_id = ? and status = 1)
                                AND gr.id = gp.grade_id 
                                AND se.id = gp.semester_id 
                                AND ins.id = gp.institution_id 
                                AND us.id = gp.creator_id 
                                '.$order_param);
                            $db->execute(array($id));                            
                 break;
            case 'user':    $db = DB::prepare('SELECT gp.*, gr.grade, se.semester, ins.institution AS institution_id, usr.username AS creator_id
                                                FROM groups AS gp, semester AS se, institution AS ins, users AS usr, grade AS gr
                                                WHERE se.id = gp.semester_id
                                                AND gp.grade_id = gr.id
                                                AND ins.id = gp.institution_id
                                                AND usr.id = gp.creator_id
                                                AND gp.id = ANY (SELECT group_id FROM groups_enrolments WHERE user_id = ? AND status = 1) 
                                                '.$order_param);
                            $db->execute(array($id));
                 break; 
            case 'institution':   $db = DB::prepare('SELECT gp.*, gr.grade, se.semester FROM groups AS gp, grade AS gr, semester AS se
                                                     WHERE se.id = gp.semester_id
                                                     AND gp.grade_id = gr.id AND gp.institution_id = ? 
                                '.$order_param);
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
                    $this->institution      = $result->institution;
                    $this->creator          = $result->username;
                }
                $groups[] = clone $this;        //it has to be clone, to get the object and not the reference
        } 
        return $groups;
    }
    
    /**
    * function used during the install process to set up creator id to new admin
    * @return boolean
    */
    public function dedicate(){ // only use during install
        $db     = DB::prepare('UPDATE groups SET creator_id = ?');
        if ($db->execute(array($this->creator_id))){
            $db = DB::prepare('UPDATE groups_enrolments SET creator_id = ?');
            return $db->execute(array($this->creator_id));
        }
    }
}