<?php
/**
* 
* @abstract This file is part of curriculum - http://www.joachimdieterich.de
* @package core
* @filename EnablingObjective.class.php
* @copyright 2013 Joachim Dieterich
* @author Joachim Dieterich
* @date 2013.06.11 21:00
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
class EnablingObjective {
    /**
     * ID of enabling objective
     * @var int
     */
    public $id;
    /**
     * enabling Objective
     * @var string 
     */
    public $enabling_objective;
    /**
     * Description of enabling objective
     * @var html    
     */
    public $description; 
    /**
     * id of curriculum
     * @var int 
     */
    public $curriculum_id;
    /**
     * curriculum name - used for accomplished objectives on dashboard
     * @var string 
     */
    public $curriculum; 
    /**
     * id of terminal objective
     * @var int
     */
    public $terminal_objective_id; 
    /**
     * name of terminal objective
     * @var string 
     */
    public $terminal_objective; 
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
     * repeat interval
     * @var int 
     */
    public $repeat_interval = -1; //important std -> don't repeat
    /**
     * Position of enabling_objective  within terminal_objective
     * @var type 
     */
    public $order_id; 
    /**
     * id of current accomplish status
     * @var int
     */
    public $accomplished_status_id; 
    /**
     * timestamp of last accomplish status change
     * @var timestamp
     */
    public $accomplished_time; 
    /**
     * id of teacher who set last accomplished status 
     * @var type 
     */
    public $accomplished_teacher_id; 
    /**
     * name of teacher who set accomplished status
     * @var string
     */
    public $accomplished_teacher; 
    /**
     * number of enroled users
     * @var int
     */
    public $enroled_users;
    /**
     * number of users who accomplished objective
     * @var int
     */
    public $accomplished_users; 
    /**
     * percent value - number of  users who accomplished objective
     * @var int 
     */
    public $accomplished_percent; 
    /**
     * array of files of current enabling objective
     * @var array of file object
     */
    public $files; 
    public $quiz;
            
            
    /**
     * add objective
     * @return mixed 
     */
    public function add(){
        global $USER;
        checkCapabilities('objectives:addEnablingObjective', $USER->role_id);
        $db = DB::prepare('SELECT MAX(order_id)AS max FROM enablingObjectives WHERE terminal_objective_id = ?');
        $db->execute(array($this->terminal_objective_id));
        $result = $db->fetchObject();
        $this->order_id = $result->max+1;
        $db = DB::prepare('INSERT INTO enablingObjectives 
                    (enabling_objective,description,terminal_objective_id,curriculum_id,repeat_interval,order_id,creator_id) 
                    VALUES (?,?,?,?,?,?,?)');        
        $db->execute(array($this->enabling_objective, $this->description, $this->terminal_objective_id, $this->curriculum_id, $this->repeat_interval, $this->order_id, $USER->id));
        return DB::lastInsertId(); //returns id 
    }
    
    /**
     * Update objective
     * @return boolean 
     */
    public function update(){
        global $USER;
        checkCapabilities('objectives:updateEnablingObjectives', $USER->role_id);
        $db = DB::prepare('UPDATE enablingObjectives SET enabling_objective = ?, description = ?, repeat_interval = ? WHERE id = ?');
        return $db->execute(array($this->enabling_objective, $this->description, $this->repeat_interval, $this->id));
    }
    
    /**
     * delete enabling objective
     * @return boolean 
     */
    public function delete(){
        global $USER;
        checkCapabilities('objectives:deleteEnablingObjectives', $USER->role_id);
        $db = DB::prepare('DELETE FROM enablingObjectives WHERE id = ?');
        return $db->execute(array($this->id));
    } 
    
    /**
     * Load enabling objectives from db 
     */
    public function load(){
        $db = DB::prepare('SELECT * FROM enablingObjectives WHERE id = ?');
        $db->execute(array($this->id));
        while($result = $db->fetchObject()) { 
            $this->id                    = $result->id;
            $this->enabling_objective    = $result->enabling_objective;
            $this->description           = $result->description;
            $this->curriculum_id         = $result->curriculum_id;
            $this->terminal_objective_id = $result->terminal_objective_id;
            $this->order_id              = $result->order_id;
            $this->repeat_interval       = $result->repeat_interval;
            $this->creation_time         = $result->creation_time;
            $this->creator_id            = $result->creator_id;
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
        global $USER, $CFG; 
        switch ($dependency) {
                case 'user':  $db = DB::prepare('SELECT en.*, ua.status_id, ua.accomplished_time, ua.creator_id AS teacher_id
                                            FROM enablingObjectives AS en 
                                            LEFT JOIN user_accomplished AS ua ON en.id = ua.enabling_objectives_id AND ua.user_id = (SELECT id FROM users WHERE id = ?)
                                            WHERE en.curriculum_id = ?
                                            ORDER by en.terminal_objective_id, en.order_id');
                                $db->execute(array($id, $this->curriculum_id));

                                while($result = $db->fetchObject()) { 
                                    $this->id                      = $result->id;
                                    $this->enabling_objective      = $result->enabling_objective;
                                    $this->description             = $result->description;
                                    $this->curriculum_id           = $result->curriculum_id;
                                    $this->terminal_objective_id   = $result->terminal_objective_id;
                                    $this->order_id                = $result->order_id;
                                    $this->repeat_interval_id      = $result->repeat_interval;
                                    $this->creation_time           = $result->creation_time;
                                    $this->creator_id              = $result->creator_id;   
                                    $this->accomplished_status_id  = $result->status_id;   
                                    $this->accomplished_time       = $result->accomplished_time;   
                                    $this->accomplished_teacher_id = $result->teacher_id;   
                                    $objectives[]                  = clone $this; 
                                } 
                break;
                
            case 'curriculum':  $db = DB::prepare('SELECT en.* FROM enablingObjectives AS en 
                                        WHERE en.curriculum_id = ? ORDER by en.terminal_objective_id, en.order_id');
                                $db->execute(array($this->curriculum_id));
                            
                                while($result = $db->fetchObject()) { 
                                    $this->id                      = $result->id;
                                    $this->enabling_objective      = $result->enabling_objective;
                                    $this->description             = $result->description;
                                    $this->curriculum_id           = $result->curriculum_id;
                                    $this->terminal_objective_id   = $result->terminal_objective_id;
                                    $this->order_id                = $result->order_id;
                                    $this->repeat_interval_id      = $result->repeat_interval;
                                    $this->creation_time           = $result->creation_time;
                                    $this->creator_id              = $result->creator_id;     
                                    /* Check if Material or external Reference is set */
                                    $db_03 = DB::prepare('SELECT COUNT(*) AS MAX FROM files AS fi WHERE ena_id = ? AND context_id = 2');
                                    $db_03->execute(array($result->id));
                                    $res_03 = $db_03->fetchObject();
                                    if (isset($CFG->repository)){ // prüfen, ob Repository Plugin vorhanden ist.
                                        $ext = $CFG->repository->count(1,$result->id);
                                    } 
                                    $this->files                = $res_03->MAX.$ext; //nummer of materials
                                    $objectives[]               = clone $this; 
                                }  
                break;   
            
             case 'terminal_objective': $files = new File(); 
                                $db = DB::prepare('SELECT en.* FROM enablingObjectives AS en 
                                    WHERE en.terminal_objective_id = ?
                                    ORDER by en.terminal_objective_id, en.order_id');
                                $db->execute(array($id));
                                while($result = $db->fetchObject()) { 
                                    $this->id                      = $result->id;
                                    $this->enabling_objective      = $result->enabling_objective;
                                    $this->description             = $result->description;
                                    $this->curriculum_id           = $result->curriculum_id;
                                    $this->terminal_objective_id   = $result->terminal_objective_id;
                                    $this->order_id                = $result->order_id;
                                    $this->repeat_interval_id      = $result->repeat_interval;
                                    $this->creation_time           = $result->creation_time;
                                    $this->creator_id              = $result->creator_id;     
                                    $this->files                   = $files->getFiles('enabling_objective', $this->id, 'default', false); // 3. Parameter false da nicht benötigt --> viel bessere performance
                                    $objectives[]                  = clone $this; 
                                }   
                break;    
             case 'enabling_objective_status': $db = DB::prepare('SELECT ua.status_id, ua.accomplished_time, ua.creator_id AS teacher_id
                                                                    FROM user_accomplished AS ua WHERE ua.user_id = ?
                                                                    AND  ua.enabling_objectives_id = ?');
                                        $db->execute(array($id, $this->id));
                                        $result = $db->fetchObject()
                                                ;
                                        if (isset($result->status_id)){ // wenn Ziel bereits in user_accomplished für den User angelegt wurde
                                            $this->accomplished_status_id  = $result->status_id;   
                                            $this->accomplished_time       = $result->accomplished_time;   
                                            $this->accomplished_teacher_id = $result->teacher_id;   
                                            //used by renderAccCheckboxes
                                        } else { //Zielstatus wurde noch nie gesetzt
                                            $this->accomplished_status_id  = 3;
                                        }
                break;    
            
            case 'course':      $db = DB::prepare('SELECT en.*, te.terminal_objective, cu.curriculum, ua.status_id, ua.accomplished_time, ua.creator_id AS teacher_id
                                                        FROM enablingObjectives AS en 
                                                        INNER JOIN terminalObjectives AS te ON en.terminal_objective_id = te.id
                                                        INNER JOIN curriculum AS cu ON en.curriculum_id = cu.id 
                                                        LEFT JOIN user_accomplished AS ua ON en.id = ua.enabling_objectives_id AND ua.user_id = ?
                                                        WHERE en.curriculum_id = ?
                                                        ORDER by en.terminal_objective_id, en.order_id');
                                $db->execute(array($USER->id, $id));
                                while($result = $db->fetchObject()) { //Prozentberechnung - Wie viele Teilnehmer (in %) waren erfolgreich
                                    $db_01 = DB::prepare('SELECT COUNT(ge.user_id) AS cntEnroled
                                                        FROM groups_enrolments AS ge, groups AS gp, role_capabilities AS rc, institution_enrolments AS ie
                                                        WHERE ge.status = 1
                                                        AND ge.group_id = ?
                                                        AND ge.group_id = gp.id
                                                        AND gp.institution_id = ie.institution_id
                                                        AND ie.user_id = ge.user_id
                                                        AND ie.role_id = rc.role_id AND rc.capability = ? AND rc.permission = 0');
                                    $db_01->execute(array($group, 'course:setAccomplishedStatus'));
                                    $cntEnroled = $db_01->fetchObject();
                                    //Anzahl der Teilnehmer, die das Ziel erfolgreich abgeschlossen haben. 
                                    $db_02 = DB::prepare('SELECT COUNT(ua.user_id) AS anzAccomplished
                                                        FROM role_capabilities AS rc,institution_enrolments AS ie, groups AS gp, groups_enrolments AS gr, user_accomplished AS ua
                                                        WHERE gr.user_id = ua.user_id 
                                                        AND ua.enabling_objectives_id = ?
                                                        AND gr.group_id = ?
                                                        AND gr.group_id = gp.id
                                                        AND gr.status = 1
                                                        AND ua.status_id = 1
                                                        AND gp.institution_id = ie.institution_id
                                                        AND ie.role_id = rc.role_id
                                                        AND ie.status = 1
                                                        AND ie.user_id = ua.user_id
                                                        AND rc.capability = ? AND rc.permission = 0');
                                    $db_02->execute(array($result->id, $group, 'course:setAccomplishedStatus'));
                                    $anz = $db_02->fetchObject();
                                    $this->id                      = $result->id;
                                    $this->enabling_objective      = $result->enabling_objective;
                                    $this->description             = $result->description;
                                    $this->curriculum_id           = $result->curriculum_id;
                                    $this->terminal_objective_id   = $result->terminal_objective_id;
                                    $this->order_id                = $result->order_id;
                                    $this->repeat_interval_id      = $result->repeat_interval;
                                    $this->creation_time           = $result->creation_time;
                                    $this->creator_id              = $result->creator_id;   
                                    $this->accomplished_status_id  = $result->status_id;   
                                    $this->accomplished_time       = $result->accomplished_time;   
                                    $this->accomplished_teacher_id = $result->teacher_id;   
                                    $this->enroled_users           = $cntEnroled->cntEnroled;   
                                    $this->accomplished_users      = $anz->anzAccomplished;  
                                    
                                    if ($cntEnroled->cntEnroled == 0){
                                        $this->accomplished_percent= 0;
                                    } else {
                                        $this->accomplished_percent= round($anz->anzAccomplished/$cntEnroled->cntEnroled*100, 0);     
                                    }
                                    /* Check if Material or external Reference is set */
                                    $db_03 = DB::prepare('SELECT COUNT(*) AS MAX FROM files AS fi WHERE ena_id = ? AND context_id = 2');
                                    $db_03->execute(array($result->id));
                                    $res_03 = $db_03->fetchObject();
                                    if (isset($CFG->repository)){ // prüfen, ob Repository Plugin vorhanden ist.
                                        $ext = $CFG->repository->count(1,$result->id);
                                    } 
                                    $this->files                = $res_03->MAX.$ext; //nummer of materials
                                    
                                    /* Check if Quiz is available for this enabling objective*/
                                    $db_05       = DB::prepare('SELECT COUNT(*) AS MAX FROM quiz_questions WHERE objective_id = ? AND objective_type = 1');
                                    $db_05->execute(array($result->id));
                                    $res_05      = $db_05->fetchObject();
                                    $this->quiz  = $res_05->MAX;
                                    
                                    $objectives[]                  = clone $this;     
                                }
                                break;
            case 'group':       $db = DB::prepare('SELECT en.*, te.terminal_objective, cu.curriculum, ua.status_id, ua.accomplished_time, ua.creator_id AS teacher_id
                                                        FROM enablingObjectives AS en 
                                                        INNER JOIN terminalObjectives AS te ON en.terminal_objective_id = te.id
                                                        INNER JOIN curriculum AS cu ON en.curriculum_id = cu.id 
                                                        LEFT JOIN user_accomplished AS ua ON en.id = ua.enabling_objectives_id AND ua.user_id = ?
                                                        WHERE en.curriculum_id = ?
                                                        ORDER by en.terminal_objective_id, en.order_id');
                                $db->execute(array($USER->id, $id));
                                while($result = $db->fetchObject()) { //Prozentberechnung - Wie viele der ausgewählten Teilnehmer (in %) waren erfolgreich
                                    $cntEnroled = count($group);
                                    //Anzahl der Teilnehmer, die das Ziel erfolgreich abgeschlossen haben. Status 01
                                    $db_02 = DB::prepare('SELECT COUNT(ua.user_id) AS anzAccomplished
                                                        FROM role_capabilities AS rc, groups AS gp, groups_enrolments AS ge, institution_enrolments AS ie, user_accomplished AS ua
                                                        INNER JOIN users AS us ON ua.user_id = us.id
                                                        WHERE ua.enabling_objectives_id = ?
                                                        AND ua.user_id IN ('.implode(",", $group).')
                                                        AND ua.status_id = 1  
                                                        AND ie.user_id = ua.user_id
                                                        AND ie.institution_id = gp.institution_id
                                                        AND ge.user_id = ua.user_id
                                                        AND gp.id = ge.group_id
                                                        AND ie.role_id = rc.role_id AND rc.capability = ? AND rc.permission = 0'); // keine Lehrer zählen
                                    $db_02->execute(array($result->id, 'course:setAccomplishedStatus'));
                                    $res_01 = $db_02->fetchObject();
                                    $anz_status_01 = $res_01->anzAccomplished;
                                    
                                    //Anzahl der Teilnehmer, die das Ziel erfolgreich abgeschlossen haben. Status 02
                                    $db_03 = DB::prepare('SELECT COUNT(ua.user_id) AS anzAccomplished
                                                        FROM role_capabilities AS rc, groups AS gp, groups_enrolments AS ge, institution_enrolments AS ie, user_accomplished AS ua
                                                        INNER JOIN users AS us ON ua.user_id = us.id
                                                        WHERE ua.enabling_objectives_id = ?
                                                        AND ua.user_id IN ('.implode(",", $group).')
                                                        AND ua.status_id = 2        
                                                        AND ie.user_id = ua.user_id
                                                        AND ie.institution_id = gp.institution_id
                                                        AND ge.user_id = ua.user_id
                                                        AND gp.id = ge.group_id
                                                        AND ie.role_id = rc.role_id AND rc.capability = ? AND rc.permission = 0'); // keine Lehrer zählen
                                    $db_03->execute(array($result->id, 'course:setAccomplishedStatus'));
                                    $res_02 = $db_03->fetchObject();
                                    $anz_status_02 = $res_02->anzAccomplished;
                                    
                                    $db_03 = DB::prepare('SELECT COUNT(ua.user_id) AS anzAccomplished
                                                        FROM role_capabilities AS rc,  groups AS gp, groups_enrolments AS ge, institution_enrolments AS ie, user_accomplished AS ua
                                                        INNER JOIN users AS us ON ua.user_id = us.id
                                                        WHERE ua.enabling_objectives_id = ?
                                                        AND ua.user_id IN ('.implode(",", $group).')
                                                        AND ua.status_id = 0        
                                                        AND ie.user_id = ua.user_id
                                                        AND ie.institution_id = gp.institution_id
                                                        AND ge.user_id = ua.user_id
                                                        AND gp.id = ge.group_id
                                                        AND ie.role_id = rc.role_id  AND rc.capability = ? AND rc.permission = 0'); // keine Lehrer zählen
                                    $db_03->execute(array($result->id, 'course:setAccomplishedStatus'));
                                    $res_03 = $db_03->fetchObject();
                                    $anz_status_00 = $res_03->anzAccomplished;
                                    
                                    $this->id                      = $result->id;
                                    $this->enabling_objective      = $result->enabling_objective;
                                    $this->description             = $result->description;
                                    $this->curriculum_id           = $result->curriculum_id;
                                    $this->terminal_objective_id   = $result->terminal_objective_id;
                                    $this->order_id                = $result->order_id;
                                    $this->repeat_interval_id      = $result->repeat_interval;
                                    $this->creation_time           = $result->creation_time;
                                    $this->creator_id              = $result->creator_id;   
                                    $this->accomplished_time       = $result->accomplished_time;   
                                    $this->accomplished_teacher_id = $result->teacher_id;   
                                    $this->enroled_users           = $cntEnroled;   
                                    $this->accomplished_users      = $anz_status_01;  
                                    
                                    $this->accomplished_percent= round($anz_status_01/$cntEnroled*100, 0);
                                    
                                    // Status ID über Häufigkeit berechnen --> Optimierungsbedarf
                                    if (        $anz_status_01 > $anz_status_00 AND $anz_status_01 > $anz_status_02 AND $anz_status_01 > $cntEnroled/2){ $this->accomplished_status_id = 1;
                                    } else if ( $anz_status_02 > $anz_status_00 AND $anz_status_02 > $anz_status_01 AND $anz_status_02 > $cntEnroled/2){ $this->accomplished_status_id = 2;
                                    } else if ( $anz_status_00 > $anz_status_01 AND $anz_status_00 > $anz_status_02 AND $anz_status_00 > $cntEnroled/2){ $this->accomplished_status_id = 0;
                                    } else { $this->accomplished_status_id = null;
                                    }
                                
                                    $objectives[]                  = clone $this;     
                                }
                break;
            default:
                break;
        }
        if (isset($objectives)){
            return $objectives;
        } else { return false;}
        
    }  
    
    /**
     * change order of objectives 
     * @param string $direction 
     */
    public function order($direction = null){
        switch ($direction) {
            case 'down': if ($this->order_id == 1){
                            // order_id kann nicht kleiner sein
                            } else {
                                $db = DB::prepare('SELECT id FROM enablingObjectives 
                                                    WHERE terminal_objective_id = ? AND order_id = ?');
                                $db->execute(array($this->terminal_objective_id, ($this->order_id-1)));
                                $result = $db->fetchObject();
                                $db = DB::prepare('UPDATE enablingObjectives SET order_id = ? WHERE id = ?');
                                $db->execute(array($this->order_id, $result->id));

                                $db = DB::prepare('UPDATE enablingObjectives SET order_id = ? WHERE id = ?');
                                $db->execute(array(($this->order_id-1), $this->id));
                            }
                break;
            case 'up':      $db = DB::prepare('SELECT MAX(order_id) as max FROM enablingObjectives WHERE terminal_objective_id = ?');
                            $db->execute(array($this->terminal_objective_id));
                            $result = $db->fetchObject();
                            if ($this->order_id == $result->max){
                            // order_id darf nicht größer als maximale order_id sein
                            } else {
                                $db = DB::prepare('SELECT id FROM enablingObjectives WHERE terminal_objective_id = ? AND order_id = ?');
                                $db->execute(array($this->terminal_objective_id, ($this->order_id+1)));
                                $result = $db->fetchObject();
                                $replace_id = $result->id;

                                $db = DB::prepare('UPDATE enablingObjectives SET order_id = ? WHERE id = ?');
                                $db->execute(array($this->order_id, $replace_id));

                                $db = DB::prepare('UPDATE enablingObjectives SET order_id = ? WHERE id = ?');
                                $db->execute(array(($this->order_id+1), $this->id));
                            }
                break;

            default:
                break;
        }     
    }
    
    
    /**
     * get last enabling objectives depending on users accomplished days
     * @global int $USER
     * @return mixed 
     */
    public function getLastEnablingObjectives(){
        global $USER;
        $db = DB::prepare('SELECT ena.*, SUBSTRING(cur.curriculum, 1, 20) AS curriculum, usa.status_id as status_id, 
                            usa.accomplished_time as accomplished_time, usa.creator_id as teacher_id, us.firstname, us.lastname
                        FROM enablingObjectives AS ena, user_accomplished AS usa, curriculum AS cur, users AS us
                        WHERE ena.id = usa.enabling_objectives_id
                        AND us.id = usa.creator_id
                        AND ena.curriculum_id = cur.id AND usa.user_id = ? AND usa.status_id = 1
                        AND usa.accomplished_time > DATE_SUB(now(), INTERVAL ? DAY)');
        $db->execute(array($USER->id, $USER->acc_days));
        while($result = $db->fetchObject()) { 
            $this->id                      = $result->id;
            $this->enabling_objective      = $result->enabling_objective;
            $this->description             = $result->description;
            $this->curriculum_id           = $result->curriculum_id;
            $this->curriculum              = $result->curriculum;
            $this->terminal_objective_id   = $result->terminal_objective_id;
            $this->order_id                = $result->order_id;
            $this->repeat_interval_id      = $result->repeat_interval;
            $this->creation_time           = $result->creation_time;
            $this->creator_id              = $result->creator_id;   
            $this->accomplished_status_id  = $result->status_id;   
            $this->accomplished_time       = $result->accomplished_time;   
            $this->accomplished_teacher_id = $result->teacher_id;   
            $this->accomplished_teacher    = $result->firstname.' '.$result->lastname;   
            $objectives[]                  = clone $this; 
        }
    if (isset($objectives)){
    } else {
        $objectives = NULL;
        }
    return $objectives;
    }
    
    
    
    /**
     * get data for user report
     * @global int $USER
     * @param int $id
     * @return object 
     */
    public function getReport($id = null){     
        $db = DB::prepare('SELECT * FROM user_accomplished WHERE user_id = ? AND status_id = 1 ORDER BY accomplished_time');
        if ($id == null) {
            global $USER;
            $db->execute(array($USER->id));
        } else {
            $db->execute(array($id));
        }
        while($result = $db->fetchObject()) { 
            $this->id                      = $result->enabling_objectives_id;
            $this->accomplished_status_id  = $result->status_id;   
            $this->accomplished_time       = $result->accomplished_time;    
            $objectives[]                  = clone $this; 
        }
    if (isset($objectives)){
    } else {
        $objectives = NULL;
        }
    return $objectives;
    }
    
    /**
     * get percentage of completion
     * @param int $cur
     * @param int $id
     * @return int 
     */
    public function getPercentageOfCompletion($cur = null, $id = null){
    $db = DB::prepare('SELECT COUNT(id) FROM enablingObjectives WHERE curriculum_id = ?');
    $db->execute(array($cur));
    $ena_count = $db->fetchColumn();
    
    $db = DB::prepare('SELECT COUNT(en.id) FROM enablingObjectives AS en, user_accomplished AS ua 
        WHERE en.curriculum_id = ? AND ua.user_id = ? AND ua.status_id = 1 AND ua.enabling_objectives_id = en.id');
    $db->execute(array($cur,$id));
    $ena_acc_count =  $db->fetchColumn();
    return round($ena_acc_count/$ena_count*100,2); 
    }
    
    /**
    * get repeat interval 
    * @param int $repeat_id
    * @return array 
    */
    public function getRepeatInterval($repeat_id) {
        $db = DB::prepare('SELECT repeat_interval FROM repeat_interval WHERE id = ?');
        $db->execute(array($repeat_id));
        while($result = $db->fetchObject()) { 
                $value = $result->repeat_interval;
        } 
        if (isset($value)) {    
            return $value;
        }
    }
    
    /**
     * get repeating objectives
     * @return array of EnablingObjective objects|boolean 
     */
    public function getRepeatingObjectives(){
        $db = DB::prepare('SELECT ua.*, ena.repeat_interval 
                        FROM user_accomplished AS ua, enablingObjectives AS ena
                        WHERE ua.status_id <> 2
                        AND ua.enabling_objectives_id = ena.id
                        AND ena.repeat_interval <> -1');
        $db->execute();

        while($result = $db->fetchObject()) { 
            $this->id                       = $result->enabling_objectives_id;
            $this->load();
            $this->repeat_interval          = $result->repeat_interval;
            $this->accomplished_users       = $result->user_id;
            $this->accomplished_status_id   = $result->status_id;
            $this->accomplished_time        = $result->accomplished_time;
            $this->accomplished_teacher_id  = $result->creator_id;
            $objectives[] = clone $this; 
        }
        if (isset($objectives)){
            return $objectives; 
        } else {return false;}  
    }
    
    /**
     * get accomplished users
     * @param int $group
     * @return array 
     */
    public function getAccomplishedUsers($group){
        $db = DB::prepare('SELECT ua.user_id
                              FROM user_accomplished AS ua
                              INNER JOIN groups_enrolments AS gr ON gr.user_id = ua.user_id 
                                    WHERE ua.enabling_objectives_id = ? AND gr.group_id = ?
                                    AND gr.status = 1 AND ua.status_id = 1');
                                    $db->execute(array($this->id, $group));
        while($result = $db->fetchObject()) {
            $users[] = $result->user_id; 
        }

        if (isset($users)){
            return $users;
        } else {return false;}
    }
    
    
    /**
     * set accomplished status of enabling objective in db
     * @global int $USER
     * @param string $dependency
     * @param int $user_id
     * @param int $creator_id
     * @param int $status
     * @return type 
     */
    public function setAccomplishedStatus($dependency = null, $user_id = null, $creator_id = null, $status = 2) {
        global $USER;
        switch ($dependency) {
            case 'cron':    $db = DB::prepare('UPDATE user_accomplished SET status_id = ? WHERE enabling_objectives_id = ?');
                            return $db->execute(array($status, $this->id));
                            break;
            case 'quiz':    $db = DB::prepare('SELECT COUNT(id) FROM user_accomplished WHERE enabling_objectives_id = ? AND user_id = ?');
                            $db->execute(array($this->id, $user_id));
                            if($db->fetchColumn() >= 1) {
                                $db = DB::prepare('UPDATE user_accomplished SET status_id = ?, creator_id = ? WHERE enabling_objectives_id = ? AND user_id = ?');
                                return $db->execute(array($status, $creator_id, $this->id, $user_id));
                            } else {
                                $db = DB::prepare('INSERT INTO user_accomplished(enabling_objectives_id,user_id,status_id,creator_id) VALUES (?,?,?,?)');
                                return $db->execute(array($this->id, $user_id, $status, $creator_id));
                            }
                            break;
            case 'teacher': checkCapabilities('objectives:setStatus', $USER->role_id);
                            $db = DB::prepare('SELECT COUNT(id) FROM user_accomplished WHERE enabling_objectives_id = ? AND user_id = ?');
                            $db->execute(array($this->id, $user_id));
                            if($db->fetchColumn() >= 1) { 
                                if ($status == 0){
                                    $issuing = new Issuing();  
                                    $email = new User();
                                    $issuing->email = $email->getValue('email', $user_id);
                                    /*if ($badge_slug != false){
                                        $res = $issuing->deleteInstance($CFG->badge_system, $badge_slug); 
                                    }*/
                                }
                                $db = DB::prepare('UPDATE user_accomplished SET status_id = ?, creator_id = ? WHERE enabling_objectives_id = ? AND user_id = ?');
                                return $db->execute(array($status, $creator_id, $this->id, $user_id));
                            } else {
                                $db = DB::prepare('INSERT INTO user_accomplished(enabling_objectives_id,user_id,status_id,creator_id) VALUES (?,?,?,?)');
                                return $db->execute(array($this->id, $user_id, $status, $creator_id));
                            }
                            
                            break;
            default:        break;
        } 
    }
    
    function calcTerminalPercentage($ter_id, $user_id){
        $db1    = DB::prepare('SELECT COUNT(id) FROM enablingObjectives WHERE terminal_objective_id IN (?)');
        $db1->execute(array($ter_id));
        $max    = $db1->fetchColumn(); 
        $db2    = DB::prepare('SELECT count(ua.id) 
                                FROM user_accomplished AS ua, enablingObjectives AS ena 
                                WHERE ua.enabling_objectives_id = ena.id
                                AND ena.terminal_objective_id IN (?)
                                AND ua.user_id = ? AND (ua.status_id = 1 OR ua.status_id = 2)');
        $db2->execute(array($ter_id, $user_id));
        $accomplished    = $db2->fetchColumn(); 
        if ($max > 0){
            return floatval($accomplished/$max);                               
        } else {
            return floatval(0);
        }
    }
    
    /**
    * function used during the install process to set up creator id to new admin
    * @return boolean
    */
    public function dedicate(){ // only use during install
        $db = DB::prepare('UPDATE enablingObjectives SET creator_id = ?');
        return $db->execute(array($this->creator_id));
    }
}