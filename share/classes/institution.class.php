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
    public $country_code        = null;
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
     * function load Institution 
     */
    public function load() {
        $query = sprintf("SELECT * FROM institution WHERE id = '%s'",
                         mysql_real_escape_string($this->id));
        
        $result = mysql_query($query);
        
        if ($result && mysql_num_rows($result)) {
          $this->confirmed          = mysql_result($result, 0, 'confirmed'); 
          $this->institution        = mysql_result($result, 0, 'institution'); 
          $this->description        = mysql_result($result, 0, 'description'); 
          $this->schooltype_id      = mysql_result($result, 0, 'schooltype_id'); 
          $this->country_id         = mysql_result($result, 0, 'country_id'); 
          $this->state_id           = mysql_result($result, 0, 'state_id'); 
          $this->creation_time      = mysql_result($result, 0, 'creation_time'); 
          $this->creator_id         = mysql_result($result, 0, 'creator_id'); 
        } else {
            return $result;
        }
    }
    
    /**
     *  addInstitution  
     */
    public function add() {
        $query = sprintf("SELECT COUNT(id) FROM institution WHERE institution = '%s'",
                                    mysql_real_escape_string($this->institution));
        $result = mysql_query($query);
        list($count) = mysql_fetch_row($result);
        if($count >= 1) { 
            return false;
        } else {
            $query = sprintf("INSERT INTO institution (institution, description, schooltype_id, country_id, state_id, creator_id, confirmed) 
                                VALUES ('%s','%s','%s','%s','%s','%s','%s')",
                                            mysql_real_escape_string($this->institution),
                                            mysql_real_escape_string($this->description),
                                            mysql_real_escape_string($this->schooltype_id),
                                            mysql_real_escape_string($this->country_id),
                                            mysql_real_escape_string($this->state_id),
                                            mysql_real_escape_string($this->creator_id),
                                            mysql_real_escape_string($this->confirmed));
            if (mysql_query($query)){
                return mysql_insert_id(); //gibt die ID zurück
            } else return false; 
            
        }
    }
    
    /**
     * delete Institution 
     * @return mixed 
     */
    public function deleteInstitution(){
        $query = sprintf("DELETE FROM institution WHERE id='%s'",
                          mysql_real_escape_string($this->id));
        return mysql_query($query);
    }
    
    /**
     * update Institution  
     */
    public function update(){
        $query = sprintf("UPDATE institution SET institution = '%s', description= '%s', schooltype_id= '%s', country_id= '%s', state_id= '%s', creator_id= '%s', confirmed = '%s' 
                                WHERE id = '%s'",
                                            mysql_real_escape_string($this->institution),
                                            mysql_real_escape_string($this->description),
                                            mysql_real_escape_string($this->schooltype_id),
                                            mysql_real_escape_string($this->country_id),
                                            mysql_real_escape_string($this->state_id),
                                            mysql_real_escape_string($this->creator_id),
                                            mysql_real_escape_string($this->confirmed),
                                            mysql_real_escape_string($this->id));
        return mysql_query($query);
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
    
    public function loadConfig($dependency = null, $id = null){
    global $INSTITUTION; 
    //Get institution data
    switch ($dependency) {
        case 'user':    $query = sprintf("SELECT ins.id, ins.institution, ins.description, sch.schooltype AS schooltype_id, sta.state AS state_id, 
                             ins.country_id, ins.creation_time, usr.username AS creator_id 
                        FROM institution AS ins, schooltype AS sch, state AS sta, users AS usr
                        WHERE sch.id = ins.schooltype_id
                        AND sta.id = ins.state_id
                        AND usr.id = ins.creator_id
                        AND ins.id = ANY (SELECT institution_id FROM institution_enrolments WHERE user_id = '%s')",
                        mysql_real_escape_string($id));
            break;

        default:
            break;
    }

    $result = mysql_query($query);
    //setup $INSTITUTION   
        while($row = mysql_fetch_assoc($result)) { 
            $INSTITUTION->id                = mysql_result($result, 0, "id");
            $INSTITUTION->institution       = mysql_result($result, 0, "institution");
            $INSTITUTION->description       = mysql_result($result, 0, "description");
            $INSTITUTION->schooltype        = mysql_result($result, 0, "schooltype_id");
            $INSTITUTION->country_id        = mysql_result($result, 0, "country_id");
            $INSTITUTION->state             = mysql_result($result, 0, "state_id");
            $INSTITUTION->creator_id        = mysql_result($result, 0, "creator_id");

            //get config data from db config_institution
            $query = sprintf("SELECT * FROM config_institution
                                WHERE institution_id = '%s'",
                                mysql_real_escape_string($INSTITUTION->id));
            $result = mysql_query($query);
            $INSTITUTION->institution_filepath              = mysql_result($result, 0, "institution_filepath");
            $INSTITUTION->institution_paginator_limit       = mysql_result($result, 0, "institution_paginator_limit");
            $INSTITUTION->institution_standard_role         = mysql_result($result, 0, "institution_standard_role");
            $INSTITUTION->institution_standard_country      = mysql_result($result, 0, "institution_standard_country");
            $INSTITUTION->institution_standard_state        = mysql_result($result, 0, "institution_standard_state");
            $INSTITUTION->institution_csv_size              = mysql_result($result, 0, "institution_csv_size");
            $INSTITUTION->institution_avatar_size           = mysql_result($result, 0, "institution_avatar_size");
            $INSTITUTION->institution_material_size         = mysql_result($result, 0, "institution_material_size");
            $INSTITUTION->institution_acc_days              = mysql_result($result, 0, "institution_acc_days");
            $INSTITUTION->institution_language              = mysql_result($result, 0, "institution_language");  
            $INSTITUTION->institution_timeout               = mysql_result($result, 0, "institution_timeout");  
                
        }
    }

    public function getNewInsitutions(){
        $query          = sprintf("SELECT COUNT(id) AS value FROM institution WHERE confirmed = 4");
        $result         = mysql_query($query); 
        $amount_of_new_instituions = mysql_fetch_array($result);
        if ($amount_of_new_instituions[0]){
            return $amount_of_new_instituions[0];
        } else {
            return false; 
        }
    }
    
    public function getInstitutionByUserName($username){
        $query = sprintf("SELECT ins.id
                            FROM institution AS ins
                            WHERE ins.id = ANY (SELECT institution_id FROM institution_enrolments WHERE user_id = 
                                            (SELECT id FROM users WHERE username = '%s'))",
                            mysql_real_escape_string($username));
        $result = mysql_query($query);

        while($row = mysql_fetch_assoc($result)) { 
                $this->id = $row['id']; 
        } 
    }
    
    /**
    * get institution by user id
    * @param int $userID
    * @return array , default = null 
    */
    public function getInstitutionsByUserID($user_id){
        $query = sprintf("SELECT ins.id, ins.institution, ins.description, sch.schooltype AS schooltype_id, sta.state AS state_id, ins.country_id, co.de AS country, ins.creation_time, usr.username AS creator_id 
                            FROM institution AS ins, schooltype AS sch, state AS sta, countries AS co, users AS usr
                            WHERE sch.id = ins.schooltype_id
                            AND sta.id = ins.state_id
                            AND co.id = ins.country_id
                            AND usr.id = ins.creator_id
                            AND ins.id = ANY (SELECT institution_id FROM institution_enrolments WHERE user_id = '%s')",
                            mysql_real_escape_string($user_id));
        $result = mysql_query($query);

        while($row = mysql_fetch_assoc($result)) { 
                $dataInstitution[] = $row; 
        } 
        if (isset($dataInstitution)){
            $value = $dataInstitution;
        } else {
            $value = NULL;
        } 
        return $value;
    }

} /* end of class Institution */
?>