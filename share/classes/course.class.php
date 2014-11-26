<?php
/**
 * Group object can add, update, delete and get data from curriculum db
 * 
 * @example
 * // Add new course<br>
 * $new_course = new Course(); <br>
 * 
 * @abstract This file is part of curriculum - http://www.joachimdieterich.de
 * @package core
 * @filename course.class.php
 * @copyright 2013 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2013.06.11 10:16
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
class Course {
    /**
     * combined id | curriculumID_groupID
     * @var string
     */
    public $id;
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
 
    /**
     * get courses depending on dependency
     * @param string $dependency
     * @param int $id
     * @return array of course objects
     */
    public function getCourse($dependency = null, $id = null){
        switch ($dependency) {
            case 'admin': $db = DB::prepare('SELECT cu.id, cu.curriculum, cu.description, gp.groups, gp.semester_id, gp.id AS gpid, fl.filename
                                            FROM curriculum AS cu, curriculum_enrolments AS ce, groups AS gp, files AS fl
                                            WHERE cu.id = ce.curriculum_id
                                            AND cu.icon_id = fl.id
                                            AND gp.id = ce.group_id
                                            AND ce.group_id = ANY (SELECT id FROM groups 
                                                                WHERE institution_id = ANY (SELECT institution_id FROM institution_enrolments 
                                                                                                WHERE user_id = ? and status = 1))
                                            ORDER BY gp.groups, cu.curriculum ASC');
                            $db->execute(array($id));
                            while($result = $db->fetchObject()) { 
                                $this->id            = $result->id.'_'.$result->gpid;
                                $this->semester_id   = $result->semester_id;
                                $this->course        = $result->groups.' | '.$result->curriculum.' | '.$result->description; 
                                $this->curriculum_id = $result->id;
                                $this->curriculum    = $result->curriculum;
                                $this->description   = $result->description;
                                $this->group         = $result->groups;
                                $this->icon          = $result->filename;
                                $course[] = clone $this;        //it has to be clone, to get the object and not the reference
                            }              
                            break; 
            case 'teacher': $db = DB::prepare('SELECT cu.id, cu.curriculum, cu.description, gp.groups, gp.semester_id, gp.id AS gpid, fl.filename
                                            FROM curriculum AS cu, curriculum_enrolments AS ce, groups AS gp, files AS fl
                                            WHERE cu.id = ce.curriculum_id
                                            AND gp.id = ce.group_id AND cu.icon_id = fl.id
                                            AND ce.group_id = ANY(SELECT group_id
                                                    FROM groups_enrolments
                                                    WHERE user_id =  ?
                                                    OR creator_id =  ?)
                                                    ORDER BY gp.groups, cu.curriculum ASC');
                            $db->execute(array($id, $id));
                            while($result = $db->fetchObject()) { 
                                $this->id            = $result->id.'_'.$result->gpid;
                                $this->semester_id   = $result->semester_id;
                                $this->course        = $result->groups.' | '.$result->curriculum.' | '.$result->description; 
                                $this->curriculum_id = $result->id;
                                $this->curriculum    = $result->curriculum;
                                $this->description   = $result->description;
                                $this->group         = $result->groups;
                                $this->icon          = $result->filename;
                                $course[] = clone $this;        
                            }
                            break; 
            case 'course':  $db = DB::prepare('SELECT cu.*, co.de, st.state, sc.schooltype, gr.grade, su.subject  
                                            FROM curriculum AS cu, countries AS co, state AS st, schooltype AS sc, grade AS gr, subjects AS su
                                            WHERE cu.country_id = co.id AND cu.state_id = st.id 
                                            AND cu.schooltype_id = sc.id AND cu.grade_id = gr.id AND cu.subject_id = su.id AND cu.id = ?');
                            $db->execute(array($id));
                            while($result = $db->fetchObject()) { 
                                $this->curriculum_id = $result->id;
                                $this->curriculum    = $result->curriculum;
                                $this->description   = $result->description;
                                $this->state         = $result->state; 
                                $this->country       = $result->de;
                                $this->grade         = $result->grade;
                                $this->subject       = $result->subject;
                                $this->schooltype    = $result->schooltype;
                                $course[] = clone $this;        
                            }
            default:        break;
        }
        
        if (isset($course)){
            return $course;    
        } 
   }
   
   /**
    * get teacher depending on user id and curriculum id
    * @param int $user_id
    * @param int $curriculum_id
    * @return array of users | boolean 
    */
   public function getTeacher($user_id, $curriculum_id){
        $db = DB::prepare('SELECT DISTINCT usr.id
                            FROM users AS usr, groups_enrolments AS gre
                            WHERE (usr.role_id = 1 OR usr.role_id = 2 OR usr.role_id = 3 OR usr.role_id = 4 )
                            AND gre.status = 1AND gre.user_id = usr.id AND gre.group_id = ANY (SELECT gre.group_id
                                    FROM groups_enrolments as gre, curriculum_enrolments as cue
                                    WHERE gre.user_id = ? AND cue.curriculum_id = ?)'); //WHERE Condition has to be changed for new roles
        $db->execute(array($user_id, $curriculum_id));    
        while($result = $db->fetchObject()) {
            $teachers[] = $result->id;
        }
       if (isset($teachers)){
           return $teachers; 
       } else {return false;}
   }    
}
?>