<?php
if (0 > version_compare(PHP_VERSION, '5')) {
    die('This file was generated for PHP 5');
}
/** 
 * This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package core 
 * @filename institution.class.php
 * @copyright 2013 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2013.03.20 06:55
 * @license
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

class Institution {
    /**
     * id of institution, default null
     * @var int 
     */
    public $id      = null;
    /**
     * confirmed (1 = registered, 2 = n.a, 3 = registered, user has to change password, 4 = registered but not active), default null
     * @var int 
     */
    public $confirmed           = null;
    /**
     * institution name, default null
     * @var string 
     */
    public $institution         = null;
    /**
     * description of institution, default null 
     * @var string 
     */
    public $description         = null;
    /**
     * id of schooltype, default null
     * @var int 
     */
    public $schooltype_id       = null;
    /**
     * country code
     * @var string
     */
    public $country_code        = null;
    /**
     * id of state
     * @var int
     */
    public $state_id            = null; 
    /**
     * timestamp, default null 
     * @var timestamp
     */
    public $creation_time   = null;
    /**
     * id of creator, default null
     * @var int 
     */
    public $creator_id          = null;
    
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
        } else {
            return false;
        }
    }
    
    /**
     *  add institution to db   
     */
    public function add() {
        $db = DB::prepare('SELECT COUNT(id) FROM institution WHERE institution = ?');
        $db->execute(array($this->institution));
        if($db->fetchColumn() >= 1) { 
            return false;
        } else {
            $db = DB::prepare('INSERT INTO institution (institution, description, schooltype_id, country_id, state_id, creator_id, confirmed) 
                                VALUES (?,?,?,?,?,?,?)');
            if ($db->execute(array($this->institution, $this->description, $this->schooltype_id, $this->country_id, $this->state_id, $this->creator_id, $this->confirmed))){    
                return DB::lastInsertId();
            } else return false; 
            
        }
    }
    
    /**
     * delete Institution from db
     * @return boolean 
     */
    public function deleteInstitution(){
        $db = DB::prepare('DELETE FROM institution WHERE id = ?');
        return $db->execute(array($this->id));
    }
    
    /**
     * update institution in db
     * @return boolean 
     */
    public function update($install = false){
        if ($install){
            $db = DB::prepare('UPDATE institution SET institution = ?, description= ?, schooltype_id= ?, country_id= ?, state_id= ?, creator_id= ?, confirmed = ?');
             if ($db->execute(array($this->institution, $this->description, $this->schooltype_id, $this->country_id, $this->state_id, $this->creator_id, $this->confirmed))){
                $db = DB::prepare('SELECT id FROM institution WHERE institution = ?');
                $db->execute(array($this->institution));
                $result = $db->fetchObject();
                if ($result) {
                    $this->id          = $result->id; 
                    return $this->id;
                } else { return false; }
             }
        } else {
            $db = DB::prepare('UPDATE institution SET institution = ?, description= ?, schooltype_id= ?, country_id= ?, state_id= ?, creator_id= ?, confirmed = ? 
                                    WHERE id = ?');
            return $db->execute(array($this->institution, $this->description, $this->schooltype_id, $this->country_id, $this->state_id, $this->creator_id, $this->confirmed, $this->id));
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
        case 'user':    $db = DB::prepare('SELECT ins.id, ins.institution, ins.description, sch.schooltype AS schooltype_id, sta.state AS state_id, 
                             ins.country_id, ins.creation_time, usr.username AS creator_id 
                        FROM institution AS ins, schooltype AS sch, state AS sta, users AS usr
                        WHERE sch.id = ins.schooltype_id AND sta.id = ins.state_id AND usr.id = ins.creator_id
                        AND ins.id = ANY (SELECT institution_id FROM institution_enrolments WHERE user_id = ?)');
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
            $INSTITUTION->state             = $result->state_id;
            $INSTITUTION->creator_id        = $result->creator_id;

            //get config data from db config_institution
            $db_1 = DB::prepare('SELECT * FROM config_institution
                                WHERE institution_id = ?');
            $db_1->execute(array($INSTITUTION->id));
            
            $result_1 =  $db_1->fetchObject();
            $INSTITUTION->institution_filepath              = $result_1->institution_filepath;
            $INSTITUTION->institution_paginator_limit       = $result_1->institution_paginator_limit;
            $INSTITUTION->institution_standard_role         = $result_1->institution_standard_role;
            $INSTITUTION->institution_standard_country      = $result_1->institution_standard_country;
            $INSTITUTION->institution_standard_state        = $result_1->institution_standard_state;
            $INSTITUTION->institution_csv_size              = $result_1->institution_csv_size;
            $INSTITUTION->institution_avatar_size           = $result_1->institution_avatar_size;
            $INSTITUTION->institution_material_size         = $result_1->institution_material_size;
            $INSTITUTION->institution_acc_days              = $result_1->institution_acc_days;
            $INSTITUTION->institution_language              = $result_1->institution_language;  
            $INSTITUTION->institution_timeout               = $result_1->institution_timeout;                
        }
    }
    
    /**
     * get amount of new institutions
     * @return boolean 
     */
    public function getNewInsitutions(){
        $db = DB::prepare('SELECT COUNT(id) AS value FROM institution WHERE confirmed = 4');
        $db->execute();
        $result = $db->fetchColumn();
        
        if ($result){
            return $result;
        } else {
            return false; 
        }
    }
    
    /** 
     * get institution of a given user
     * @param string $username 
     */
    public function getInstitutionByUserName($username){
        $db = DB::prepare('SELECT ins.id FROM institution AS ins
                            WHERE ins.id = ANY (SELECT institution_id FROM institution_enrolments WHERE user_id = 
                                            (SELECT id FROM users WHERE username = ?))');
        $db->execute(array($username));
        while($result = $db->fetchObject()) { 
                $this->id = $result->id;
        } 
    }
    
    /**
    * get institution by user id
    * @param int $userID
    * @return array , default = null 
    */
    public function getInstitutionsByUserID($user_id){
        $db = DB::prepare('SELECT ins.id, ins.institution, ins.description, sch.schooltype AS schooltype_id, sta.state AS state_id, ins.country_id, co.de AS country, ins.creation_time, usr.username AS creator_id 
                            FROM institution AS ins, schooltype AS sch, state AS sta, countries AS co, users AS usr
                            WHERE sch.id = ins.schooltype_id AND sta.id = ins.state_id AND co.id = ins.country_id AND usr.id = ins.creator_id
                            AND ins.id = ANY (SELECT institution_id FROM institution_enrolments WHERE user_id = ?)');
        $db->execute(array($user_id));

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
        } else {
        return false;}
    }
} 
?>