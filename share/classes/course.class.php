<?php
/**
 * 
 * @abstract This file is part of curriculum - http://www.joachimdieterich.de
 * @package core
 * @filename course.class.php
 * @copyright 2013 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2013.06.11 10:16
 * @license 
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by  
 * the Free Software Foundation; either version 3 of the License, or (at your option) any later version.                                   
 *                                                                       
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of        
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details:
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
    public function getCourse($dependency = null, $id = null, $array = false){
        $course = array();
        switch ($dependency) {
            case 'admin':   $db = DB::prepare('SELECT cu.id, cu.curriculum, cu.description, gp.groups, gp.semester_id, gp.id AS gpid, fl.filename
                                            FROM curriculum AS cu, curriculum_enrolments AS ce, groups AS gp, files AS fl
                                            WHERE cu.id = ce.curriculum_id
                                            AND cu.icon_id = fl.id
                                            AND gp.id = ce.group_id
                                            AND ce.group_id = ANY (SELECT id FROM groups 
                                                                   WHERE institution_id = ANY (SELECT institution_id FROM institution_enrolments 
                                                                                                WHERE user_id = ? and status = 1))
                                            ORDER BY gp.groups, cu.curriculum ASC');
                            $db->execute(array($id));          
                break; 
            case 'teacher': $db = DB::prepare('SELECT cu.id, cu.curriculum, cu.description, gp.groups, gp.semester_id, gp.id AS gpid, fl.filename
                                            FROM curriculum AS cu, curriculum_enrolments AS ce, groups AS gp, files AS fl
                                            WHERE cu.id = ce.curriculum_id
                                            AND gp.id = ce.group_id AND cu.icon_id = fl.id 
                                            AND ce.group_id = ANY(SELECT group_id
                                                    FROM groups_enrolments
                                                    WHERE user_id =  ? OR creator_id = ? AND status = 1)
                                                    ORDER BY gp.groups, cu.curriculum ASC');        //Abfrage überarbeiten liefert fehlerhafte Ergenisse
                            $db->execute(array($id, $id)); 
                break;
            case 'course':  $db = DB::prepare('SELECT cu.*, co.de, st.state, sc.schooltype, gr.grade, su.subject  
                                            FROM curriculum AS cu, countries AS co, state AS st, schooltype AS sc, grade AS gr, subjects AS su
                                            WHERE cu.country_id = co.id AND cu.state_id = st.id 
                                            AND cu.schooltype_id = sc.id AND cu.grade_id = gr.id AND cu.subject_id = su.id AND cu.id = ?');
                            $db->execute(array($id));
                break;  
            default:        break;
        }
        
        if ($array == true){ //Array output for quickform
            $c = array(); 
            while($result = $db->fetchObject()) {
                $c[$result->id] = $result->curriculum.' | '.$result->description;
            }
            return $c;
        } else {
            while($result = $db->fetchObject()) {
                $this->curriculum_id     = $result->id;
                $this->curriculum        = $result->curriculum;
                $this->description       = $result->description;
                if ($dependency == 'course'){  
                    $this->state         = $result->state; 
                    $this->country       = $result->de;
                    $this->grade         = $result->grade;
                    $this->subject       = $result->subject;
                    $this->schooltype    = $result->schooltype;
                } else {
                    $this->id            = $result->id.'_'.$result->gpid;
                    $this->semester_id   = $result->semester_id;
                    $this->course        = $result->groups.' | '.$result->curriculum.' | '.$result->description; 
                    $this->group         = $result->groups;
                    $this->icon          = $result->filename;
                }
                $course[]                = clone $this;        //it has to be clone, to get the object and not the reference
            }
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
        $teachers = array();
        $db = DB::prepare('SELECT ge.user_id  FROM role_capabilities AS rc, institution_enrolments AS ie, groups AS gp, groups_enrolments AS ge
                        WHERE rc.capability = ?
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
        $db->execute(array('course:setAccomplishedStatus', $user_id, $curriculum_id));    
        while($result = $db->fetchObject()) {
            $teachers[] = $result->user_id;
        }
        return $teachers;
   }    
}