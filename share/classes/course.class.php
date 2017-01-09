<?php
/**
* 
* @abstract This file is part of curriculum - http://www.joachimdieterich.de
* @package core
* @filename course.class.php
* @copyright 2013 Joachim Dieterich
* @author Joachim Dieterich
* @date 2013.06.11 10:16
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
class Course {
    /**
     * combined id | curriculumID_groupID
     * @var string
     */
    public $id;
    /**
     * sollte die id ablösen todo
     */
    public $course_id;
    /**
     * name of course
     * @var string
     */
    public $course; 
    /**
     * semester id
     * @var int
     */
    public $semester_id; 
    /**
     * id of curriculum
     * @var int
     */
    public $curriculum_id; 
    /**
     * Name of curriculum
     * @var string
     */
    public $curriculum; 
    /**
     * Description of curriculum
     * @var string
     */
    public $description; 
    /**
     * name of schooltype
     * @var string
     */
    public $schooltype; 
    /**
     * name of state
     * @var string
     */
    public $state; 
    /**
     * name of country
     * @var type 
     */
    public $country; 
    /**
     * name of group
     * @var string 
     */
    public $group; 
    public $group_id; 
    /**
     * name of grade
     * @var string
     */
    public $grade; 
    /**
     * name of subject
     * @var string
     */
    public $subject; 
    /**
     * filename of icon
     * @var string
     */
    public $icon; 
    public $icon_id; 

 
    /**
     * get courses depending on dependency
     * @param string $dependency
     * @param int $id
     * @return array of course objects
     */
    public function getCourse($dependency = null, $id = null){
        global $USER;
        $course = array();
        switch ($dependency) {
            case 'admin':   $db = DB::prepare('SELECT cu.id, cu.curriculum, cu.description, cu.icon_id, gp.groups, gp.semester_id, gp.id AS gpid, ce.id AS course_id 
                                            FROM curriculum AS cu, curriculum_enrolments AS ce, groups AS gp
                                            WHERE cu.id = ce.curriculum_id
                                            AND gp.id = ce.group_id
                                            AND ce.status = 1
                                            AND ce.group_id = ANY (SELECT id FROM groups 
                                                                   WHERE institution_id = ANY (SELECT institution_id FROM institution_enrolments 
                                                                                                WHERE user_id = ? and status = 1))
                                            ORDER BY gp.groups, cu.curriculum ASC');
                            $db->execute(array($id));          
                break; 
            case 'admin_semester':   $db = DB::prepare('SELECT cu.id, cu.curriculum, cu.description, cu.icon_id, gp.groups, gp.semester_id, gp.id AS gpid, ce.id AS course_id
                                            FROM curriculum AS cu, curriculum_enrolments AS ce, groups AS gp
                                            WHERE cu.id = ce.curriculum_id
                                            AND gp.id = ce.group_id
                                            AND ce.status = 1
                                            AND gp.semester_id = ?
                                            AND ce.group_id = ANY (SELECT id FROM groups 
                                                                   WHERE institution_id = ANY (SELECT institution_id FROM institution_enrolments 
                                                                                                WHERE user_id = ? and status = 1))
                                            ORDER BY gp.groups, cu.curriculum ASC');
                            $db->execute(array($id,$USER->id));          
                break; 
            case 'teacher': $db = DB::prepare('SELECT cu.id, cu.curriculum, cu.description, cu.icon_id, gp.groups, gp.semester_id, gp.id AS gpid, ce.id AS course_id
                                            FROM curriculum AS cu, curriculum_enrolments AS ce, groups AS gp
                                            WHERE cu.id = ce.curriculum_id
                                            AND gp.id = ce.group_id
                                            AND ce.status = 1
                                            AND ce.group_id = ANY(SELECT group_id
                                                    FROM groups_enrolments
                                                    WHERE user_id =  ? OR creator_id = ? AND status = 1)
                                                    ORDER BY gp.groups, cu.curriculum ASC');        //Abfrage überarbeiten liefert fehlerhafte Ergenisse
                            $db->execute(array($id, $id)); 
                break;
            case 'teacher_semester': $db = DB::prepare('SELECT cu.id, cu.curriculum, cu.description, cu.icon_id, gp.groups, gp.semester_id, gp.id AS gpid, ce.id AS course_id
                                            FROM curriculum AS cu, curriculum_enrolments AS ce, groups AS gp
                                            WHERE cu.id = ce.curriculum_id
                                            AND gp.id = ce.group_id 
                                            AND ce.status = 1
                                            AND gp.semester_id = ?
                                            AND ce.group_id = ANY(SELECT group_id
                                                    FROM groups_enrolments
                                                    WHERE user_id =  ? OR creator_id = ? AND status = 1)
                                                    ORDER BY gp.groups, cu.curriculum ASC');        //Abfrage überarbeiten liefert fehlerhafte Ergenisse
                            $db->execute(array($id, $USER->id, $USER->id)); 
                break;
            case 'course':  $db = DB::prepare('SELECT cu.*, co.de, st.state, sc.schooltype, gr.grade, su.subject  
                                            FROM curriculum AS cu, countries AS co, state AS st, schooltype AS sc, grade AS gr, subjects AS su
                                            WHERE cu.country_id = co.id AND cu.state_id = st.id 
                                            AND cu.schooltype_id = sc.id AND cu.grade_id = gr.id AND cu.subject_id = su.id AND cu.id = ?');
                            $db->execute(array($id));
                break;  
            default:        break;
        }
        
        while($result = $db->fetchObject()) {
            $this->curriculum_id     = $result->id;
            $this->curriculum        = $result->curriculum;
            $this->description       = $result->description;
            $this->icon_id           = $result->icon_id;
            if ($dependency == 'course'){  
                $this->state         = $result->state; 
                $this->country       = $result->de;
                $this->grade         = $result->grade;
                $this->subject       = $result->subject;
                $this->schooltype    = $result->schooltype;
            } else {
                $this->course_id     = $result->course_id;
                $this->id            = $result->id.'_'.$result->gpid;
                $this->semester_id   = $result->semester_id;
                $this->course        = $result->groups.' | '.$result->curriculum; //.' | '.$result->description; 
                $this->group         = $result->groups;
                //$this->icon          = $result->filename;
            }
            $course[]                = clone $this;        //it has to be clone, to get the object and not the reference
        }
        return $course;
        
   }
   
   /**
    * get teacher depending on user id and curriculum id
    * @param int $user_id
    * @param int $curriculum_id
    * @return array of users | boolean 
    */
   public function getTeacher($user_id, $curriculum_id){
        $teachers = array();
        $db = DB::prepare('SELECT ge.user_id  FROM role_capabilities AS rc, institution_enrolments AS ie, groups AS gp, groups_enrolments AS ge
                        WHERE rc.capability = \'course:setAccomplishedStatus\'
                        AND rc.permission = 1
                        AND ie.status = 1
                        AND ie.role_id = rc.role_id
                        AND ge.status = 1
                        AND ge.user_id = ie.user_id
                        AND gp.institution_id = ie.institution_id
                        AND gp.id = ge.group_id
                        AND ge.group_id = ANY (SELECT ge.group_id
                                    FROM groups_enrolments as ge, curriculum_enrolments as ce
                                    WHERE ge.user_id = ? AND ce.curriculum_id = ? AND ce.status = 1 AND 		
                                    ce.group_id = ge.group_id)'); 
        $db->execute(array($user_id, $curriculum_id));    
        while($result = $db->fetchObject()) {
            $teachers[] = $result->user_id;
        }
        return $teachers;
   }   
   
   public function getCourseId($curriculum_id, $group_id){
       $db = DB::prepare('SELECT * FROM curriculum_enrolments AS ce
                        WHERE ce.curriculum_id = ? AND ce.group_id = ?');
       $db->execute(array($curriculum_id, $group_id));
       $result = $db->fetchObject();
        if ($result){
            $this->id            = $result->id;
            $this->status        = $result->status;
            $this->curriculum_id = $result->curriculum_id;
            $this->group_id      = $result->group_id;
            $this->creation_time = $result->creation_time;
            $this->expel_time    = $result->expel_time;
            $this->creator_id    = $result->creator_id;
            return $this;
        }
   }
   
   public function getCourseById($id){
       $db = DB::prepare('SELECT * FROM curriculum_enrolments AS ce
                        WHERE ce.id = ?');
       $db->execute(array($id));
       $result = $db->fetchObject();
        if ($result){
            $this->id            = $result->id;
            $this->status        = $result->status;
            $this->curriculum_id = $result->curriculum_id;
            $this->group_id      = $result->group_id;
            $this->creation_time = $result->creation_time;
            $this->expel_time    = $result->expel_time;
            $this->creator_id    = $result->creator_id;
            return $this;
        }
   }
   
   public function members($dependency = 'id', $id){
        $user = new User();
        switch ($dependency) {
            case 'id':          $db = DB::prepare('SELECT ge.user_id FROM groups_enrolments AS ge, curriculum_enrolments AS ce
                                            WHERE ce.id = ? AND ce.group_id = ge.group_id'); 
                                $db->execute(array($id));  
                                while($result = $db->fetchObject()) {
                                    $user->load('id', $result->user_id, false);
                                    $users[] = clone $user;
                                }
                break;
            case 'group_id':  $db = DB::prepare('SELECT ge.user_id FROM groups_enrolments AS ge, curriculum_enrolments AS ce
                                            WHERE ce.group_id = ge.group_id 
                                            AND ce.group_id = ?
                                            AND ce.curriculum_id = ?'); 
                                $db->execute(array($id, $this->curriculum_id));  
                                while($result = $db->fetchObject()) {
                                    $users[]['id'] = $result->user_id;
                                }
                break;

            default:
                break;
        }
          
        
       return $users;
   }
   
   public function getGroupID($cur_id, $teacher, $student){
       $db = DB::prepare('SELECT DISTINCT ce.group_id from curriculum_enrolments AS ce, groups_enrolments AS ge 
                            WHERE ce.curriculum_id = ? AND ge.user_id = ? AND ge.status = 1
                            AND ge.group_id = (Select group_id FROM groups_enrolments 
                                                                WHERE user_id = ? and ge.group_id = group_id AND status = 1)');
        $db->execute(array($cur_id, $teacher, $student));  
        $result = $db->fetchObject();
        if ($result){
            return $result->group_id;
        } 
   }
   
}