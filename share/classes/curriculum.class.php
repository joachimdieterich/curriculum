<?php
/**
 * Group object can add, update, delete and get data from curriculum db
 * 
 * @example
 * // Add new curriculum <br>
 * $new_curriculum = new Curriculum(); <br>
 * 
 * @abstract This file is part of curriculum - http://www.joachimdieterich.de
 * @package core
 * @filename curriculum.class.php
 * @copyright 2013 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2013.06.08 15:53
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
class Curriculum {
    /**
     * ID of curriculum
     * @var int
     */
    public $id = null;
    /**
     * combined id | curriculumID_groupID
     * @var string 
     */
    public $id_grid = null;
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
     * id of grade
     * @var int 
     */
    public $grade_id = 8; //??? std vars setzten --> am besten über constructor
    /**
     * name of grade
     * @var string
     */
    public $grade = null;
    /**
     * id of subject
     * @var int
     */
    public $subject_id = 1; 
    /**
     * name of subject
     * @var type 
     */
    public $subject = null; 
    /**
     * id of schooltype
     * @var int
     */ 
    public $schooltype_id = 1; 
    /**
     * id of state
     * @var int
     */
    public $state_id = 11; 
    /**
     * id of country
     * @var int
     */
    public $country_id = 56; 
    /**
     * id of icon
     * @var int
     */
    public $icon_id = null; 
 
    /**
     * Timestamp when Grade was created
     * @var timestamp
     */
    public $creation_time = null; 
    /**
     * ID of User who created this Grade
     * @var int
     */
    public $creator_id =null; 
    
    public $language_code = null; 
    /**
     * array which holds terminal objectives of this curriculum
     * @var type 
     */
    public $terminal_objectives = null; 
    /**
     * add curriculum to db
     * @return mixed 
     */
    public function add(){
        $query = sprintf("SELECT COUNT(id) FROM curriculum WHERE UPPER(curriculum) = UPPER('%s')",
                                    mysql_real_escape_string($this->curriculum));
        $result = mysql_query($query);
        list($count) = mysql_fetch_row($result);
        if($count >= 1) { 
            return 'Diesen Lehrplan gibt es bereits.';
        } else {
            $query = sprintf("INSERT INTO curriculum (curriculum, description, grade_id, subject_id, schooltype_id, state_id, icon_id, country_id, creator_id) 
                                                  VALUES ('%s','%s','%s','%s','%s','%s','%s','%s','%s')",
                                            mysql_real_escape_string($this->curriculum),
                                            mysql_real_escape_string($this->description),
                                            mysql_real_escape_string($this->grade_id),
                                            mysql_real_escape_string($this->subject_id),
                                            mysql_real_escape_string($this->schooltype_id),
                                            mysql_real_escape_string($this->state_id),
                                            mysql_real_escape_string($this->icon_id),
                                            mysql_real_escape_string($this->country_id),
                                            mysql_real_escape_string($this->creator_id));
            return mysql_query($query);		
        }
    }
    
    /**
     * Update curriculum in db
     * @return boolean 
     */
    public function update(){
        $query = sprintf("UPDATE curriculum 
                SET curriculum = '%s', description = '%s', grade_id = '%s', subject_id = '%s', 
                schooltype_id = '%s', state_id = '%s', icon_id = '%s', country_id = '%s', creator_id = '%s'
                WHERE id = '%s'",
                mysql_real_escape_string($this->curriculum),
                mysql_real_escape_string($this->description),
                mysql_real_escape_string($this->grade_id),
                mysql_real_escape_string($this->subject_id),
                mysql_real_escape_string($this->schooltype_id),
                mysql_real_escape_string($this->state_id),
                mysql_real_escape_string($this->icon_id),
                mysql_real_escape_string($this->country_id),
                mysql_real_escape_string($this->creator_id), 
                mysql_real_escape_string($this->id));
        return mysql_query($query);
    }
    
    /**
     * Delete curriculum from db
     * @return mixed 
     */
    public function delete(){
        $query = sprintf("DELETE FROM curriculum WHERE id='%s'",
                        mysql_real_escape_string($this->id));
        return mysql_query($query);
    } 
    
    /**
     * load curriculum depending on id 
     * if load_terminal_objectives == true -> get Objectives
     * @param type $load_terminal_objectives 
     */
    public function load($load_terminal_objectives = false){
        $query = sprintf("SELECT cu.*, co.code, su.subject 
                            FROM curriculum AS cu, countries AS co, subjects AS su 
                            WHERE cu.country_id = co.id 
                            AND cu.subject_id = su.id
                            AND cu.id='%s'",
                        mysql_real_escape_string($this->id));
        $result = mysql_query($query);
        $row = mysql_fetch_assoc($result);
        $this->curriculum       = $row["curriculum"];
        $this->description      = $row["description"];
        $this->grade_id         = $row["grade_id"];
        $this->subject_id       = $row["subject_id"];
        $this->subject          = $row["subject"];
        $this->schooltype_id    = $row["schooltype_id"];
        $this->state_id         = $row["state_id"];
        $this->icon_id          = $row["icon_id"];
        $this->country_id       = $row["country_id"];
        $this->language_code    = $row["code"];
        $this->creation_time    = $row["creation_time"];
        $this->creator_id       = $row["creator_id"];
        if ($load_terminal_objectives){
            $terminal_objectives = new TerminalObjective();
            $this->terminal_objectives = $terminal_objectives->getObjectives('curriculum', $this->id, true);
        }
        
    }
    /**
     * get curriulum depending on dependency
     * @param string $dependency
     * @param int $id
     * @return array of curriculum objects  
     */
    public function getCurricula($dependency = null, $id = null){
        switch ($dependency) {
            case 'group':   $query = sprintf("SELECT cu.id, cu.curriculum, cu.description, fl.filename, su.subject, 
                                            gr.grade, sc.schooltype, st.state, co.de
                                            FROM curriculum AS cu, curriculum_enrolments AS ce, 
                                            files AS fl, subjects AS su, grade AS gr, schooltype AS sc,
                                            state AS st, countries AS co
                                            WHERE cu.id = ce.curriculum_id
                                            AND cu.icon_id = fl.id
                                            AND cu.grade_id = gr.id
                                            AND cu.subject_id = su.id
                                            AND cu.schooltype_id = sc.id
                                            AND cu.state_id = st.id
                                            AND cu.country_id = co.id
                                            AND ce.group_id = '%s'
                                            AND ce.status = '1'
                                            ORDER BY cu.curriculum ASC",
                                            mysql_real_escape_string($id));    
                            $result = mysql_query($query);
                            if ($result && mysql_num_rows($result)) {
                                while($row = mysql_fetch_assoc($result)) { 
                                        $curriculum[] = $row; 
                                }         
                            }
                break;
            case 'creator': $query = sprintf("SELECT cu.id, cu.curriculum, cu.description, gr.grade  
                                                FROM curriculum AS cu, grade AS gr
                                                WHERE cu.creator_id = '%s' AND gr.id = cu.grade_id",
                                                mysql_real_escape_string($id));
                            $result = mysql_query($query);
                            while($row = mysql_fetch_assoc($result)) {
                                        $curriculum[] = $row; 
                            }
                break; 
            case 'teacher': $query = sprintf("SELECT cu.id, cu.curriculum, cu.description, gp.groups, gp.id AS gpid, fl.filename
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
                                $counter = 0;
                                while($row = mysql_fetch_assoc($result)) { 
                                        $curriculum[$counter]["id"] = $row["id"];
                                        $curriculum[$counter]["id_clsid"] = $row["id"].'_'.$row["gpid"];
                                        $curriculum[$counter]["curriculum"] = $row["curriculum"];
                                        $curriculum[$counter]["description"] = $row["description"];
                                        $curriculum[$counter]["groups"] = $row["groups"];
                                        $curriculum[$counter]["filename"] = $row["filename"];
                                        $curriculum[$counter]["coursename"] = $row["groups"].' | '.$row["curriculum"].' | '.$row["description"]; 
                                $counter++;
                                }
                                }
                break; 
            case 'user':    $query = sprintf("SELECT cu.*, co.de, st.state, sc.schooltype, gr.grade, su.subject, fl.filename  
                                            FROM curriculum AS cu, countries AS co, state AS st, schooltype AS sc, grade AS gr, subjects AS su, files AS fl
                                            WHERE  cu.country_id = co.id AND cu.state_id = st.id 
                                            AND cu.schooltype_id = sc.id AND cu.grade_id = gr.id 
                                            AND cu.subject_id = su.id AND cu.creator_id = '%s'
                                            AND cu.icon_id = fl.id",
                                            mysql_real_escape_string($id));
                            $result = mysql_query($query);
                            while($row = mysql_fetch_assoc($result)) { 
                                $curriculum[] = $row; //result Data wird an setPaginator vergeben
                            } 
                break; 
            default:    
                break;
        }
        
        if (isset($curriculum)){
            return $curriculum;    
        } 
   }
   
   /**
    * Check if there are active enrolments - used by deleteCurriculum in request.php
    * @return boolean 
    */
   public function getCurriculumEnrolments(){
       $query = sprintf("SELECT id 
                            FROM curriculum_enrolments
                            WHERE curriculum_id = '%s' AND status = 1",
                    mysql_real_escape_string($this->id));
       $result = mysql_query($query);
       list($count) = mysql_fetch_row($result);
       if($count >= 1) { return true;} else {return false;}
   }
   
   /**
    * function used during the install process to set up creator id to new admin
    * @return boolean
    */
    public function dedicate(){ // only use during install
        $query = sprintf("UPDATE curriculum SET creator_id = '%s'",
                                            mysql_real_escape_string($this->creator_id));
        if (mysql_query($query)){
            $query = sprintf("UPDATE curriculum_enrolments SET creator_id = '%s'",
                                            mysql_real_escape_string($this->creator_id));
            return mysql_query($query);
        }
    }
}
?>