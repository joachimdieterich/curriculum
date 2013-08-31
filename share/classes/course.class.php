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
    public $id = null;
    /**
     * name of course
     * @var string
     */
    public $course = null; 
    /**
     * id of curriculum
     * @var int
     */
    public $curriculum_id = null; 
    /**
     * Name of curriculum
     * @var string
     */
    public $curriculum = null; 
    /**
     * Description of curriculum
     * @var string
     */
    public $description = null; 
    /**
     * name of schooltype
     * @var string
     */
    public $schooltype = null; 
    /**
     * name of state
     * @var string
     */
    public $state = null; 
    /**
     * name of country
     * @var type 
     */
    public $country = null; 
    /**
     * name of group
     * @var string 
     */
    public $group = null; 
    /**
     * name of grade
     * @var string
     */
    public $grade = null; 
    /**
     * name of subject
     * @var string
     */
    public $subject = null; 
    /**
     * filename of icon
     * @var string
     */
    public $icon = null; 
 
 
    public function getCourse($dependency = null, $id = null){
        switch ($dependency) {
            case 'admin': $query = sprintf("SELECT cu.id, cu.curriculum, cu.description, gp.groups, gp.id AS gpid, fl.filename
                                            FROM curriculum AS cu, curriculum_enrolments AS ce, groups AS gp, files AS fl
                                            WHERE cu.id = ce.curriculum_id
                                            AND cu.icon_id = fl.id
                                            AND gp.id = ce.group_id
                                            AND ce.group_id = ANY (SELECT id FROM groups 
                                                                WHERE institution_id = ANY (SELECT institution_id FROM institution_enrolments 
                                                                                                WHERE user_id = '%s'))
                                            ORDER BY gp.groups, cu.curriculum ASC",
                                            mysql_real_escape_string($id)); 
                                $result = mysql_query($query);
                                if ($result && mysql_num_rows($result)){
                                    while($row = mysql_fetch_assoc($result)) { 
                                        $this->id            = $row["id"].'_'.$row["gpid"];
                                        $this->course        = $row["groups"].' | '.$row["curriculum"].' | '.$row["description"]; 
                                        $this->curriculum_id = $row["id"];
                                        $this->curriculum    = $row["curriculum"];
                                        $this->description   = $row["description"];
                                        $this->group         = $row["groups"];
                                        $this->icon          = $row["filename"];
                                        $course[] = clone $this;        //it has to be clone, to get the object and not the reference
                                    }
                                }
                            
                break; 
            case 'teacher': $query = sprintf("SELECT cu.id, cu.curriculum, cu.description, gp.groups
                                            FROM curriculum AS cu, curriculum_enrolments AS ce, groups AS gp
                                            WHERE cu.id = ce.curriculum_id
                                            AND gp.id = ce.group_id
                                            AND ce.group_id = ANY(SELECT group_id
                                                    FROM groups_enrolments
                                                    WHERE user_id =  '%s'
                                                    OR creator_id =  '%s')
                                                    ORDER BY gp.groups, cu.curriculum ASC",
                                            mysql_real_escape_string($id), 
                                            mysql_real_escape_string($id)); 
                                $result = mysql_query($query);
                                if ($result && mysql_num_rows($result)){
                                    while($row = mysql_fetch_assoc($result)) { 
                                        $this->id            = $row["id"].'_'.$row["gpid"];
                                        $this->course        = $row["groups"].' | '.$row["curriculum"].' | '.$row["description"]; 
                                        $this->curriculum_id = $row["id"];
                                        $this->curriculum    = $row["curriculum"];
                                        $this->description   = $row["description"];
                                        $this->group         = $row["groups"];
                                        $this->icon          = $row["filename"];
                                        $course[] = clone $this;        //it has to be clone, to get the object and not the reference
                                    }
                                }
                break; 
            case 'course': $query = sprintf("SELECT cu.*, co.de, st.state, sc.schooltype, gr.grade, su.subject  
                                            FROM curriculum AS cu, countries AS co, state AS st, schooltype AS sc, grade AS gr, subjects AS su
                                            WHERE cu.country_id = co.id AND cu.state_id = st.id 
                                            AND cu.schooltype_id = sc.id AND cu.grade_id = gr.id AND cu.subject_id = su.id AND cu.id = '%s'",            
                                        mysql_real_escape_string($id));
                                    $result = mysql_query($query);
                                    if ($result && mysql_num_rows($result)){
                                        while($row = mysql_fetch_assoc($result)) { 
                                            $this->curriculum_id = $row["id"];
                                            $this->curriculum    = $row["curriculum"];
                                            $this->description   = $row["description"];
                                            $this->state         = $row["state"]; 
                                            $this->country       = $row["de"];
                                            $this->grade         = $row["grade"];
                                            $this->subject       = $row["subject"];
                                            $this->schooltype       = $row["schooltype"];
                                            $course[] = clone $this;        //it has to be clone, to get the object and not the reference
                                        }
                                    }

            default:    
                break;
        }
        
        if (isset($course)){
            return $course;    
        } 
   }
   
   public function getTeacher($user_id, $curriculum_id){
       $query = sprintf("SELECT DISTINCT usr.id
                        FROM users AS usr, groups_enrolments AS gre
                        WHERE (usr.role_id = 1 OR usr.role_id = 2 OR usr.role_id = 3 OR usr.role_id = 4 )
                        AND gre.group_id = ANY (SELECT gre.group_id
                                FROM groups_enrolments as gre, curriculum_enrolments as cue
                                WHERE gre.user_id =  '%s' AND cue.curriculum_id = '%s')",
                        mysql_real_escape_string($user_id), 
                        mysql_real_escape_string($curriculum_id)); 
            $result = mysql_query($query);
            if ($result && mysql_num_rows($result)){
                while($row = mysql_fetch_assoc($result)) {
                    $teachers[] = $row["id"];
                }
            }
       if (isset($teachers)){
           return $teachers; 
       } else {return false;}
   }   
   
}
?>