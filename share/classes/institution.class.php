<?php
/** 
* This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core 
* @filename institution.class.php
* @copyright 2013 Joachim Dieterich
* @author Joachim Dieterich
* @date 2013.03.20 06:55
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

class Institution {
    /**
     * id of institution, default null
     * @var int 
     */
    public $id;
    /**
     * confirmed (1 = registered, 2 = n.a, 3 = registered, user has to change password, 4 = registered but not active), default null
     * @var int 
     */
    public $confirmed;
    /**
     * institution name, default null
     * @var string 
     */
    public $institution;
    /**
     * description of institution, default null 
     * @var string 
     */
    public $description;
    /**
     * id of schooltype, default null
     * @var int 
     */
    public $schooltype_id;
    /**
     * country code
     * @var string
     */
    public $country_code;
    /**
     * id of state
     * @var int
     */
    public $state_id; 
    /**
     * timestamp, default null 
     * @var timestamp
     */
    public $creation_time;
    /**
     * id of creator, default null
     * @var int 
     */
    public $creator_id;
    public $paginator_limit;
    public $std_role;
    public $csv_size;
    public $avatar_size;
    public $material_size;
    public $acc_days;
    public $timeout;
    public $semester_id;
    public $file_id;
    public $statistic;
       
    /**
     * load  institution from db depending on id
     */
    public function load() {
        $db = DB::prepare('SELECT * FROM institution WHERE id = ?');
        if ($db->execute(array($this->id))) {
          $result = $db->fetchObject();
          $this->confirmed          = $result->confirmed;
          $this->institution        = $result->institution; 
          $this->description        = $result->description; 
          $this->schooltype_id      = $result->schooltype_id; 
          $this->country_id         = $result->country_id; 
          $this->state_id           = $result->state_id; 
          $this->creation_time      = $result->creation_time; 
          $this->creator_id         = $result->creator_id; 
          $this->paginator_limit    = $result->paginator_limit; 
          $this->std_role           = $result->std_role; 
          $this->csv_size           = $result->csv_size; 
          $this->avatar_size        = $result->avatar_size; 
          $this->material_size      = $result->material_size; 
          $this->acc_days           = $result->acc_days; 
          $this->timeout            = $result->timeout; 
          $this->semester_id        = $result->semester_id; 
          $this->file_id            = $result->file_id; 
        } else {
            return false;
        }
    }
    
    public function getNewId(){
        $db             = DB::prepare('SELECT MAX(id) as max FROM institution');
        $db->execute();
        $result         = $db->fetchObject();
        return $result->max + 1;
    }
    /**
     *  add institution to db   
     */
    public function add() {
        global $USER;
        checkCapabilities('institution:add', $USER->role_id);
        $db = DB::prepare('SELECT COUNT(id) FROM institution WHERE institution = ?');
        $db->execute(array($this->institution));
        if($db->fetchColumn() >= 1) { 
            return false;
        } else {
            $db = DB::prepare('INSERT INTO institution (institution, description, schooltype_id, country_id, state_id, creator_id, confirmed, 
                               paginator_limit, std_role, csv_size, avatar_size, material_size, acc_days,
                               timeout, semester_id, file_id) 
                                VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
            if ($db->execute(array($this->institution, $this->description, $this->schooltype_id, $this->country_id, $this->state_id, $this->creator_id, $this->confirmed, $this->paginator_limit, $this->std_role, $this->csv_size, $this->avatar_size, $this->material_size, $this->acc_days, $this->timeout, $this->semester_id, $this->file_id))){    
                return DB::lastInsertId();
            } else {return false;} 
        }
    }
    
    
    /**
     * delete Institution from db
     * @return boolean 
     */
    public function delete(){
        global $USER;
        checkCapabilities('institution:delete', $USER->role_id);
        $db = DB::prepare('SELECT id FROM institution_enrolments WHERE institution_id = ? AND status = 1 AND user_id <> ?');
        $db->execute(array($this->id, $USER->id));
        if ($db->fetchObject()){
            return false;
        } else {
            $db = DB::prepare('SELECT file_id FROM institution WHERE id = ?');
            if ($db->execute(array($this->id))) {
                $result     = $db->fetchObject();
                $logo       = $result->file_id;
            }
            $db = DB::prepare('DELETE FROM institution WHERE id = ?');
            $ok = $db->execute(array($this->id));
            if ($ok){
                $file       = new File();
                $file->id   = $logo;
                return $file->delete();
            }
        }
        
    }
    
    /**
     * update institution in db
     * @return boolean 
     */
    public function update($install = false){
        global $USER;
        checkCapabilities('institution:update', $USER->role_id);
        if ($install){
            $db = DB::prepare('UPDATE institution SET institution = ?, description= ?, schooltype_id= ?, country_id= ?, state_id= ?, creator_id= ?, confirmed = ?, paginator_limit = ?, std_role = ?, csv_size = ?, avatar_size = ?, material_size = ?, acc_days = ?, timeout = ?,  file_id = ?');
            if ($db->execute(array($this->institution, $this->description, $this->schooltype_id, $this->country_id, $this->state_id, $this->creator_id, $this->confirmed, $this->paginator_limit, $this->std_role, $this->csv_size, $this->avatar_size, $this->material_size, $this->acc_days, $this->timeout, $this->semester_id, $this->file_id))){
                $db = DB::prepare('SELECT id FROM institution WHERE institution = ?');
                if ($db->execute(array($this->institution))) {
                    $result = $db->fetchObject();
                    $this->id          = $result->id; 
                    return $this->id;
                } else { return false; }
            }
        } else {
            $db = DB::prepare('UPDATE institution SET institution = ?, description= ?, schooltype_id= ?, country_id= ?, state_id= ?, creator_id= ?, confirmed = ?, paginator_limit = ?, std_role = ?, csv_size = ?, avatar_size = ?, material_size = ?, acc_days = ?, timeout = ?, semester_id = ? , file_id = ? 
                                    WHERE id = ?');
            return $db->execute(array($this->institution, $this->description, $this->schooltype_id, $this->country_id, $this->state_id, $this->creator_id, $this->confirmed, $this->paginator_limit, $this->std_role, $this->csv_size, $this->avatar_size, $this->material_size, $this->acc_days, $this->timeout, $this->semester_id, $this->file_id, $this->id));
        }
    }
    
    /**
     * class constructor
     * @param int $id default = null
     */
    public function __construct($id = null){
        if ($id != null){
           $this->id = $id; 
           $this->load();
        }
    }
    
    /**
     * load user config
     * @global object $INSTITUTION
     * @param string $dependency
     * @param int $id 
     */
    public function loadConfig($dependency = null, $id = null){
    global $INSTITUTION; 
    switch ($dependency) {
        case 'user':    $db = DB::prepare('SELECT ins.*, sch.schooltype AS schooltype_id, sta.state AS state_id, 
                             usr.username AS creator_id
                        FROM institution AS ins, schooltype AS sch, state AS sta, users AS usr
                        WHERE sch.id = ins.schooltype_id AND sta.id = ins.state_id AND usr.id = ins.creator_id
                        AND ins.id = ANY (SELECT institution_id FROM institution_enrolments WHERE user_id = ? and status = 1)');
                        $db->execute(array($id));
                        break;

        default:        break;
    }
    //setup $INSTITUTION   
        while($result = $db->fetchObject()) { 
            $INSTITUTION->id                = $result->id;
            $INSTITUTION->institution       = $result->institution;
            $INSTITUTION->description       = $result->description;
            $INSTITUTION->schooltype        = $result->schooltype_id;
            $INSTITUTION->country_id        = $result->country_id;
            $INSTITUTION->state_id          = $result->state_id;
            $INSTITUTION->creator_id        = $result->creator_id;
            $INSTITUTION->paginator_limit   = $result->paginator_limit;
            $INSTITUTION->std_role          = $result->std_role;
            $INSTITUTION->csv_size          = $result->csv_size;
            $INSTITUTION->avatar_size       = $result->avatar_size;
            $INSTITUTION->material_size     = $result->material_size;
            $INSTITUTION->acc_days          = $result->acc_days;
            $INSTITUTION->semester_id       = $result->semester_id;
            $INSTITUTION->timeout           = $result->timeout;                
        }
    }
    
    /** 
     * get institution of a given user
     * @param string $username 
     */
    public function getInstitutionByUserName($username){
        $db = DB::prepare('SELECT ins.id FROM institution AS ins
                            WHERE ins.id = ANY (SELECT institution_id FROM institution_enrolments WHERE user_id = 
                                            (SELECT id FROM users WHERE username = ?) AND status = 1)');
        $db->execute(array($username));
        while($result = $db->fetchObject()) { 
                $this->id = $result->id;
        } 
    }
    
    /**
    * get institution 
    * @param int $userID
    * @return array , default = null 
    */
    public function getInstitutions($dependency = 'user', $paginator = '', $id = null){
        $order_param = orderPaginator($paginator, array('institution'   => 'ins',
                                                        'description'   => 'ins',
                                                        'schooltype'    => 'sch',
                                                        'state'         => 'sta',
                                                        'de'            => 'co',));  
        switch ($dependency) {
            case 'user':$db = DB::prepare('SELECT ins.id, ins.institution, ins.description, ins.file_id, sch.schooltype AS schooltype_id, sta.state AS state_id, ins.country_id, co.de AS country, ins.creation_time, usr.username AS creator_id, ro.role
                            FROM institution AS ins, schooltype AS sch, state AS sta, countries AS co, users AS usr, institution_enrolments AS ie, roles AS ro
                            WHERE sch.id = ins.schooltype_id AND sta.id = ins.state_id AND co.id = ins.country_id AND usr.id = ins.creator_id AND ro.id = ie.role_id
                            AND ie.institution_id = ins.id AND ie.user_id = ? AND ie.status = 1 '.$order_param);
                        $db->execute(array($id));
                break;
            
            case 'all': $db = DB::prepare('SELECT ins.id, ins.institution, ins.description, sch.schooltype AS schooltype_id, sta.state AS state_id, ins.country_id, co.de AS country, ins.creation_time, usr.username AS creator_id 
                            FROM institution AS ins, schooltype AS sch, state AS sta, countries AS co, users AS usr
                            WHERE sch.id = ins.schooltype_id AND sta.id = ins.state_id AND co.id = ins.country_id AND usr.id = ins.creator_id '.$order_param);
                        $db->execute();
                break;

            default:
                break;
        }

        while($result = $db->fetchObject()) { 
                $dataInstitution[] = $result; 
        } 
        if (isset($dataInstitution)){
            $value = $dataInstitution;
        } else {
            $value = NULL;
        } 
        return $value;
    }
    
    public function getStatistic($id){
        $db =DB::prepare('SELECT ins.id, ins.institution, ins.description, ins.file_id
                            FROM institution AS ins, institution_enrolments AS ie
                            WHERE ie.institution_id = ins.id
                            AND ie.user_id = ? AND ie.status = 1 ');
                        $db->execute(array($id));
        
        while($result = $db->fetchObject()) { 
            $this->id          = $result->id;
            $this->institution = $result->institution;
            $this->description = $result->description;
            $this->file_id     = $result->file_id;
            $roles             = new Roles();
            foreach ($roles->get() as $r) {
                $db1     = DB::prepare('SELECT count(id) as max FROM institution_enrolments WHERE institution_id = ? AND role_id = ?');
                $db1->execute(array($this->id, $r->id));
                $r1      = $db1->fetchObject();
                $this->statistic[$r->id] = $r1->max;
            }
            
            $db2     = DB::prepare('SELECT count(ua.id) as max FROM user_accomplished AS ua, institution_enrolments AS ie 
                                WHERE ua.user_id = ie.user_id AND ua.status_id > 0 AND ie.institution_id = ?');
            $db2->execute(array($this->id));
            $r2 = $db2->fetchObject();
            $this->statistic['accomplished'] = $r2->max;
                
            $data[] = clone $this;
        }                
        return $data;
    }
    
    public function getTimeout($id){
        $db             = DB::prepare('SELECT timeout FROM institution WHERE id = ?');
        $db->execute(array($id));
        $result         = $db->fetchObject();
        return $result->timeout;
    }
    
    public function setBulletinBoard($title, $text){ //z. Zt. kann in jeder Institution nur ein Pinnwandeintrag erstellt werden. 
        global $USER;
        checkCapabilities('dashboard:editBulletinBoard', $USER->role_id);
        $db = DB::prepare('SELECT COUNT(id) FROM bulletinBoard WHERE institution_id = ?');
        $db->execute(array($this->id));
        if($db->fetchColumn() >= 1) { 
            $db = DB::prepare('UPDATE bulletinBoard SET title = ?, text = ?, creator_id = ?, creation_time = NOW() WHERE institution_id = ?');
            return $db->execute(array($title, $text, $USER->id, $this->id));
        } else {
            $db = DB::prepare('INSERT INTO bulletinBoard (title, text, creator_id, institution_id, creation_time)
                                            VALUES (?,?,?,?,NOW())');
            return $db->execute(array($title, $text, $USER->id, $this->id));	
        } 
    }
    
    public function getBulletinBoard(){
        $db = DB::prepare('SELECT * FROM bulletinBoard WHERE institution_id = ?');
        if ($db->execute(array($this->id))) {
          $result = $db->fetchObject();
          
          return $result;
        }
    }
    
    /**
    * function used during the install process to set up creator id to new admin
    * @return boolean
    */
    public function dedicate(){ // only use during install
        $db = DB::prepare('UPDATE institution SET creator_id = ?');
        if($db->execute(array($this->creator_id))){
            $db = DB::prepare('UPDATE institution_enrolments SET creator_id = ?');
            $db->execute(array($this->creator_id));
            return true; 
        } else { return false;}
    }
}