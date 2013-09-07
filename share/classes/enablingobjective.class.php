<?php
/**
 * enabling objective class can add, update, delete and get data from curriculum db
 * 
 * @example
 * // Add new objective <br>
 * $new_objective = new Objective(); <br>
 * 
 * @abstract This file is part of curriculum - http://www.joachimdieterich.de
 * @package core
 * @filename objective.class.php
 * @copyright 2013 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2013.06.11 21:00
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
class EnablingObjective {
    /**
     * ID of enabling objective
     * @var int
     */
    public $id = null;
    /**
     * enabling Objective
     * @var string 
     */
    public $enabling_objective = null;
    /**
     * Description of enabling objective
     * @var string
     */
    public $description = null; 
    /**
     * id of curriculum
     * @var int 
     */
    public $curriculum_id = null;
    /**
     * curriculum name - used for accomplished objectives on dashboard
     * @var string 
     */
    public $curriculum = null; 
    /**
     * id of terminal objective
     * @var int
     */
    public $terminal_objective_id = null; 
    /**
     * name of terminal objective
     * @var string 
     */
    public $terminal_objective = null; 
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
     * repeat interval
     * @var int 
     */
    public $repeat_interval = null;
    /**
     * Position of enabling_objective  within terminal_objective
     * @var type 
     */
    public $order = null; 
    /**
     * id of current accomplish status
     * @var int
     */
    public $accomplished_status_id = null; 
    /**
     * timestamp of last accomplish status change
     * @var timestamp
     */
    public $accomplished_time = null; 
    /**
     * id of teacher who set last accomplished status 
     * @var type 
     */
    public $accomplished_teacher_id = null; 
    /**
     * number of enroled users
     * @var int
     */
    public $enroled_users = null;
    /**
     * number of users who accomplished objective
     * @var int
     */
    public $accomplished_users = null; 
    /**
     * percent value - number of  users who accomplished objective
     * @var int 
     */
    
    public $accomplished_percent = null; 
    /**
     * array of files of current enabling objective
     * @var array of file object
     */
    public $files = null; 
            
            
    /**
     * add objective
     * @return mixed 
     */
    public function add(){
        $query = sprintf("INSERT INTO enablingObjectives 
                    (enabling_objective,description,terminal_objective_id,curriculum_id,repeat_interval) 
                    VALUES ('%s','%s','%s','%s','%s')",
                    mysql_real_escape_string($this->enabling_objective),
                    mysql_real_escape_string($this->description),
                    mysql_real_escape_string($this->terminal_objective_id), 
                    mysql_real_escape_string($this->curriculum_id), 
                    mysql_real_escape_string($this->repeat_interval));
        return mysql_query($query);
    }
    
    /**
     * Update objective
     * @return boolean 
     */
    public function update(){
        $query = sprintf("UPDATE enablingObjectives 
                    SET enabling_objective = '%s', description = '%s', repeat_interval = '%s' 
                    WHERE id = '%s'",
                    mysql_real_escape_string($this->enabling_objective),
                    mysql_real_escape_string($this->description),
                    mysql_real_escape_string($this->repeat_interval),
                    mysql_real_escape_string($this->id));
         return mysql_query($query);
    }
    
    /**
     * delete enabling objective
     * @return boolean 
     */
    public function delete(){
        $query = sprintf("DELETE
                        FROM enablingObjectives 
                        WHERE id = '%s'",
                        mysql_real_escape_string($this->id));  
        return mysql_query($query);
    } 
    
    /**
     * Load enabling objectives from db 
     */
    public function load(){
        $query = sprintf("SELECT * 
                            FROM enablingObjectives
                            WHERE id = '%s'",
                            mysql_real_escape_string($this->id));
        $result = mysql_query($query);
        if ($result && mysql_num_rows($result)) {
            while($row = mysql_fetch_assoc($result)) { 
                $this->id                    = $row["id"];
                $this->enabling_objective    = $row["enabling_objective"];
                $this->description           = $row["description"];
                $this->curriculum_id         = $row["curriculum_id"];
                $this->terminal_objective_id = $row["terminal_objective_id"];
                $this->order                 = $row["order"];
                $this->repeat_interval       = $row["repeat_interval"];
                $this->creation_time         = $row["creation_time"];
                $this->creator_id            = $row["creator_id"];
            } 
        }    
    }
    /**
     * get objectives depending on dependency
     * @global int $USER
     * @param string $dependency
     * @param int $id
     * @param int $group
     * @return array of EnablingObjective objects|boolean 
     */
    public function getObjectives($dependency = null, $id = null, $group = null) {
        global $USER; 
        switch ($dependency) {
            case 'user':  $query = sprintf("SELECT en.*, ua.status_id, ua.accomplished_time, ua.creator_id AS teacher_id
                                        FROM enablingObjectives AS en 
                                        LEFT JOIN user_accomplished AS ua ON en.id = ua.enabling_objectives_id AND ua.user_id = (SELECT id FROM users WHERE id = '%s')
                                        WHERE en.curriculum_id = '%s'
                                        ORDER by en.id",
                                        mysql_real_escape_string($id),
                                        mysql_real_escape_string($this->curriculum_id));
                            $result = mysql_query($query);
                            if ($result && mysql_num_rows($result)) {
                                while($row = mysql_fetch_assoc($result)) { 
                                    $this->id                      = $row["id"];
                                    $this->enabling_objective      = $row["enabling_objective"];
                                    $this->description             = $row["description"];
                                    $this->curriculum_id           = $row["curriculum_id"];
                                    $this->terminal_objective_id   = $row["terminal_objective_id"];
                                    $this->order                   = $row["order"];
                                    $this->repeat_interval_id      = $row["repeat_interval"];
                                    $this->creation_time           = $row["creation_time"];
                                    $this->creator_id              = $row["creator_id"];   
                                    $this->accomplished_status_id  = $row["status_id"];   
                                    $this->accomplished_time       = $row["accomplished_time"];   
                                    $this->accomplished_teacher_id = $row["teacher_id"];   
                                    $objectives[]               = clone $this; 
                                } 
                            }  
                break;
            case 'curriculum':  $query = sprintf("SELECT en.*
                                        FROM enablingObjectives AS en 
                                        WHERE en.curriculum_id = '%s'
                                        ORDER by en.id",
                                        mysql_real_escape_string($this->curriculum_id));
                            $result = mysql_query($query);
                            if ($result && mysql_num_rows($result)) {
                                while($row = mysql_fetch_assoc($result)) { 
                                    $this->id                      = $row["id"];
                                    $this->enabling_objective      = $row["enabling_objective"];
                                    $this->description             = $row["description"];
                                    $this->curriculum_id           = $row["curriculum_id"];
                                    $this->terminal_objective_id   = $row["terminal_objective_id"];
                                    $this->order                   = $row["order"];
                                    $this->repeat_interval_id      = $row["repeat_interval"];
                                    $this->creation_time           = $row["creation_time"];
                                    $this->creator_id              = $row["creator_id"];     
                                    $objectives[]               = clone $this; 
                                } 
                            }  
                break;    
            
             case 'terminal_objective': $files = new File(); 
                                        $query = sprintf("SELECT en.*
                                        FROM enablingObjectives AS en 
                                        WHERE en.terminal_objective_id = '%s'
                                        ORDER by en.id",
                                        mysql_real_escape_string($id));
                            $result = mysql_query($query);
                            if ($result && mysql_num_rows($result)) {
                                while($row = mysql_fetch_assoc($result)) { 
                                    $this->id                      = $row["id"];
                                    $this->enabling_objective      = $row["enabling_objective"];
                                    $this->description             = $row["description"];
                                    $this->curriculum_id           = $row["curriculum_id"];
                                    $this->terminal_objective_id   = $row["terminal_objective_id"];
                                    $this->order                   = $row["order"];
                                    $this->repeat_interval_id      = $row["repeat_interval"];
                                    $this->creation_time           = $row["creation_time"];
                                    $this->creator_id              = $row["creator_id"];    
                                    $this->files                   = $files->getFiles('enabling_objective', $this->id);
                                    $objectives[]                  = clone $this; 
                                } 
                                
                            }  
                break;    
            
            case 'course': $query = sprintf("SELECT en.*, te.terminal_objective, cu.curriculum, ua.status_id, ua.accomplished_time, ua.creator_id AS teacher_id
                                                        FROM enablingObjectives AS en 
                                                        INNER JOIN terminalObjectives AS te ON en.terminal_objective_id = te.id
                                                        INNER JOIN curriculum AS cu ON en.curriculum_id = cu.id 
                                                        LEFT JOIN user_accomplished AS ua ON en.id = ua.enabling_objectives_id AND ua.user_id = '%s'
                                                        WHERE en.curriculum_id = '%s'
                                                        ORDER by en.id",
                                                        mysql_real_escape_string($USER->id),
                                                        mysql_real_escape_string($id));
                            $result = mysql_query($query);
                            
                            while($row = mysql_fetch_assoc($result)) { //Prozentberechnung - Wie viele Teilnehmer (in %) waren erfolgreich
                                    $query = sprintf("SELECT COUNT(user_id) AS cntEnroled
                                                        FROM groups_enrolments
                                                        WHERE status = 1
                                                        AND group_id = '%s'",
                                                        mysql_real_escape_string($group));
                                    $cntEnrResult = mysql_query($query);
                                    $cntEnroled = mysql_fetch_assoc($cntEnrResult);
                                    //Anzahl der Teilnehmer, die das Ziel erfolgreich abgeschlossen haben. 
                                    $query = sprintf("SELECT COUNT(ua.enabling_objectives_id) AS anzAccomplished
                                                        FROM user_accomplished AS ua
                                                        INNER JOIN groups_enrolments AS gr ON gr.user_id = ua.user_id 
                                                        WHERE ua.enabling_objectives_id = '%s'
                                                        AND gr.group_id = '%s'
                                                        AND gr.status = 1
                                                        AND ua.status_id = 1",
                                                        mysql_real_escape_string($row["id"]),
                                                        mysql_real_escape_string($group));
                                    $countresult = mysql_query($query);
                                    $anz = mysql_fetch_assoc($countresult);
       
                                    $this->id                      = $row["id"];
                                    $this->enabling_objective      = $row["enabling_objective"];
                                    $this->description             = $row["description"];
                                    $this->curriculum_id           = $row["curriculum_id"];
                                    $this->terminal_objective_id   = $row["terminal_objective_id"];
                                    $this->order                   = $row["order"];
                                    $this->repeat_interval_id      = $row["repeat_interval"];
                                    $this->creation_time           = $row["creation_time"];
                                    $this->creator_id              = $row["creator_id"];   
                                    $this->accomplished_status_id  = $row["status_id"];   
                                    $this->accomplished_time       = $row["accomplished_time"];   
                                    $this->accomplished_teacher_id = $row["teacher_id"];   
                                    $this->enroled_users           = $cntEnroled["cntEnroled"];   
                                    $this->accomplished_users      = $anz["anzAccomplished"];   
                                    $this->accomplished_percent    = round($anz["anzAccomplished"]/$cntEnroled["cntEnroled"]*100, 2);   
                                    
                                    $objectives[]               = clone $this;     
                                }
                         
                break;
            case 'terminal_objective': //checks if there are enabling objectives under a terminal objective
                                    $query = sprintf("SELECT id 
                                                        FROM enablingObjectives
                                                        WHERE terminal_objective_id = '%s'",
                                                mysql_real_escape_string($id));
                                    $result = mysql_query($query);
                                    if ($result && mysql_num_rows($result)){
                                        return true; 
                                    } else {return false;} 
                break;
            default:
                break;
        }
        if (isset($objectives)){
            return $objectives;
        } else { return false;}
        
    }  
    /**
     * get last enabling objectives depending on users accomplished days
     * @global int $USER
     * @return mixed 
     */
    public function getLastEnablingObjectives(){
        global $USER;
    $query = sprintf("SELECT ena.*, SUBSTRING(cur.curriculum, 1, 20) AS curriculum
                        FROM enablingObjectives AS ena, user_accomplished AS usa, curriculum AS cur
                        WHERE ena.id = usa.enablingObjectives_id
                        AND ena.curriculum_id = cur.id
                        AND usa.user_id = '%s'
                        AND usa.status_id = 1
                        AND usa.accomplished_timestamp > DATE_SUB(now(), INTERVAL '%s' DAY)",
            mysql_real_escape_string($USER->id),
            mysql_real_escape_string($USER->acc_days));
    $result = mysql_query($query);
    if ($result && mysql_num_rows($result)){
        while($row = mysql_fetch_assoc($result)) { 
            $this->id                      = $row["id"];
            $this->enabling_objective      = $row["enabling_objective"];
            $this->description             = $row["description"];
            $this->curriculum_id           = $row["curriculum_id"];
            $this->curriculum              = $row["curriculum"];
            $this->terminal_objective_id   = $row["terminal_objective_id"];
            $this->order                   = $row["order"];
            $this->repeat_interval_id      = $row["repeat_interval"];
            $this->creation_time           = $row["creation_time"];
            $this->creator_id              = $row["creator_id"];   
            $this->accomplished_status_id  = $row["status_id"];   
            $this->accomplished_time       = $row["accomplished_time"];   
            $this->accomplished_teacher_id = $row["teacher_id"];   
            $this->enroled_users           = $cntEnroled["cntEnroled"];   
            $this->accomplished_users      = $anz["anzAccomplished"];   
            $this->accomplished_percent    = round($anz["anzAccomplished"]/$cntEnroled["cntEnroled"]*100, 2);   
            $objectives[]               = clone $this; 
        }
    } else {
        $objectives = NULL;
        }
    return $objectives;
    }
    
    /**
    * get repeat interval 
    * @param int $repeat_id
    * @return array 
    */
    public function getRepeatInterval($repeat_id) {
        $query = sprintf("SELECT repeat_interval
                            FROM repeat_interval
                            WHERE id = '%s'",
                    mysql_real_escape_string($repeat_id));
        $result = mysql_query($query);
        if ($result && mysql_num_rows($result)) {
            while($row = mysql_fetch_assoc($result)) { 
                    $value = $row['repeat_interval'];
            } 
            return $value;
        }
    }
    
    /**
     * get repeating objectives
     * @return array of EnablingObjective objects|boolean 
     */
    public function getRepeatingObjectives(){
        $query = "SELECT ua.*, ena.repeat_interval 
                        FROM user_accomplished AS ua, enablingObjectives AS ena
                        WHERE ua.status_id <> 2
                        AND ua.enabling_objectives_id = ena.id
                        AND ena.repeat_interval <> '-1'";
        $result = mysql_query($query);
               

        if ($result && mysql_num_rows($result)){
            while($row = mysql_fetch_assoc($result)) { 
                $this->id               = $row['enabling_objectives_id']; //geht evtl??? vererbung
                $this->load();
                $this->repeat_interval          = $row['repeat_interval'];
                $this->accomplished_users       = $row['user_id'];
                $this->accomplished_status_id                = $row['status_id'];
                $this->accomplished_time        = $row['accomplished_time'];
                $this->accomplished_teacher_id  = $row['creator_id'];
                $objectives[] = clone $this; 
            }
        } 
        if (isset($objectives)){
            return $objectives; 
        } else {return false;}  
    }
    
    /**
     * set accomplished status of enabling objective in db
     * @param int $status
     * @return boolean 
     */
    public function setAccomplishedStatus($dependency = null, $user_id = null, $creator_id = null, $status = 2) {
        switch ($dependency) {
            case 'cron': $query = sprintf("UPDATE user_accomplished SET status_id = '%s' WHERE enabling_objectives_id = '%s'",
                                    mysql_real_escape_string($status),
                                    mysql_real_escape_string($this->id));
                         return mysql_query($query); 
                break;
            
            case 'teacher': $query = sprintf("SELECT COUNT(id) FROM user_accomplished WHERE enabling_objectives_id = '%s' AND user_id = '%s'",
                                                                mysql_real_escape_string($this->id),
                                                                mysql_real_escape_string($user));
                                $result = mysql_query($query);
                                list($count) = mysql_fetch_row($result);
                                if($count >= 1) { 
                                        //$error = 'Diesen Eintrag gibt es bereits.';
                                        $query = sprintf("UPDATE user_accomplished SET status_id = '%s', creator_id = '%s' WHERE enabling_objectives_id = '%s' AND user_id = '%s'",
                                                                        mysql_real_escape_string($status),
                                                                        mysql_real_escape_string($creator_id),
                                                                        mysql_real_escape_string($this->id),
                                                                        mysql_real_escape_string($user_id));
                                        return mysql_query($query);
                                } else {
                                        $query = sprintf("INSERT INTO user_accomplished(enabling_objectives_id,user_id,status_id,creator_id) VALUES ('%s','%s','%s','%s')",
                                                                        mysql_real_escape_string($this->id),
                                                                        mysql_real_escape_string($user_id),
                                                                        mysql_real_escape_string($status),
                                                                        mysql_real_escape_string($creator_id));
                                        return mysql_query($query);	
                                }
                break;
            
            default:
                break;
        } 
    }
    
    /**
    * function used during the install process to set up creator id to new admin
    * @return boolean
    */
    public function dedicate(){ // only use during install
        $query = sprintf("UPDATE enablingObjectives SET creator_id = '%s'",
                                            mysql_real_escape_string($this->creator_id));
        return mysql_query($query);
    }
}
?>