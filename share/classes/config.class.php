<?php
/**
 * This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package core
 * @filename config.class.php
 * @copyright 2013 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2013.07.07 13:47
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


class Config {
    /**
     * id
     * @var int
     */
    public $id                              = null;
    /**
     * id of user
     * @var int
     */
    public $user_id                         = null; 
    /**
     * filepath of user (not used yet)
     * @var string
     */
    public $user_filepath                   = null; 
    /**
     * paginator limit of user
     * @var int
     */
    public $user_paginator_limit            = null; 
    /**
     * user accomplished days 
     * @var int
     */
    public $user_acc_days                   = null; 
    /**
     * user language
     * @var string
     */
    public $user_language                   = null; 
    /** 
     * id of institution
     * @var int
     */
    public $institution_id                  = null; 
    /**
     * filepath of institution (not used yet)
     * @var string
     */
    public $institution_filepath            = null; 
    /**
     * paginator limit of institution
     * @var int 
     */
    public $institution_paginator_limit     = null; 
    /**
     * standard role id of institution
     * @var int
     */
    public $institution_standard_role       = null; 
    /**
     * standard country id of institution
     * @var int
     */
    public $institution_standard_country    = null; 
    /**
     * standard state id of institution
     * @var int
     */
    public $institution_standard_state      = null; 
    /**
     * max csv size of institution
     * @var int
     */
    public $institution_csv_size            = null; 
    /**
     * max avatar size of institution
     * @var int 
     */
    public $institution_avatar_size         = null; 
    /**
     * max. material size of institution
     * @var int
     */
    public $institution_material_size       = null; 
    /**
     * accomplished days for institution
     * @var int 
     */
    public $institution_acc_days            = null; 
    /**
     * language setting of institution
     * @var string 
     */
    public $institution_language            = null; 
    /** 
     * timeout 
     * @var int 
     */
    public $institution_timeout             = null; 
    /**
     * timestamp of creation
     * @var timestamp
     */
    public $creation_time                   = null; //todo: evtl. überflüssig 
    /**
     * update timestamp
     * @var timestamp
     */
    public $update_time                     = null; 
    
 
    /**
     * constructor for config class
     * @global object $CFG
     * @global object $USER
     * @param string $dependency
     * @param int $id 
     */
    public function __construct($dependency = null, $id = null){
        global $CFG, $USER; 
        if ($id == null){
           $this->user_acc_days                 = $CFG->acc_days;  
           $this->user_language                 = $CFG->language;
           $this->user_paginator_limit          = $CFG->paginator_limit; 
    
           $this->institution_paginator_limit   = $CFG->paginator_limit; 
           $this->institution_standard_role     = $CFG->standard_role; 
           $this->institution_standard_country  = $CFG->standard_country; 
           $this->institution_standard_state    = $CFG->standard_state;
           $this->institution_csv_size          = $CFG->csv_size;
           $this->institution_avatar_size       = $CFG->avatar_size;
           $this->institution_material_size     = $CFG->material_size;
           $this->institution_acc_days          = $CFG->acc_days;
           $this->institution_language          = $CFG->language;
           $this->institution_timeout           = $CFG->timeout;   
        }
        switch ($dependency) {
            case 'institution': $this->institution_id  = implode(",", $id);
                                $db = DB::prepare('SELECT * FROM config_institution WHERE institution_id IN (?)'); 
                                $db->execute(array($this->institution_id));
                                $result = $db->fetchObject(); 
                                $this->institution_id                    = $result->institution_id;
                                $this->institution_filepath              = $result->institution_filepath;
                                $this->institution_paginator_limit       = $result->institution_paginator_limit;
                                $this->institution_standard_role         = $result->institution_standard_role;
                                $this->institution_standard_country      = $result->institution_standard_country;
                                $this->institution_standard_state        = $result->institution_standard_state;
                                $this->institution_csv_size              = $result->institution_csv_size;
                                $this->institution_avatar_size           = $result->institution_avatar_size;
                                $this->institution_material_size         = $result->institution_material_size;
                                $this->institution_acc_days              = $result->institution_acc_days;
                                $this->institution_language              = $result->institution_language;
                                $this->institution_timeout               = $result->institution_timeout;
                                
                                $db = DB::prepare('SELECT * FROM config_user WHERE user_id = ?'); 
                                $db->execute(array($USER->id));
                                $result = $db->fetchObject();
                                $this->user_id                    = $result->user_id;
                                $this->user_filepath              = $result->user_filepath;
                                $this->user_paginator_limit       = $result->user_paginator_limit;
                                $this->user_acc_days              = $result->user_acc_days;
                                $this->user_language              = $result->user_language; 
                break;
            case 'user':        $this->user_id = $id;
                                $db = DB::prepare('SELECT * FROM config_user WHERE user_id = ?'); 
                                $db->execute(array($this->user_id));
                                $result = $db->fetchObject();
                                $this->user_id                    = $result->user_id;
                                $this->user_filepath              = $result->user_filepath;
                                $this->user_paginator_limit       = $result->user_paginator_limit;
                                $this->user_acc_days              = $result->user_acc_days;
                                $this->user_language              = $result->user_language; 
                break;

            default:
                break;
        }
        
    }
    
    /**
     * add config to db
     * @param string $dependency
     * @param int $id
     * @return boolean
     */
    public function add($dependency = null, $id = null){
            switch ($dependency) {
                case 'institution': $this->institution_id = $id; 
                                    $db = DB::prepare('INSERT INTO config_institution(institution_id, institution_filepath, 
                                                            institution_paginator_limit,institution_standard_role, institution_standard_country,
                                                            institution_standard_state, institution_csv_size, institution_avatar_size, institution_material_size,
                                                            institution_acc_days, institution_language, institution_timeout) 
                                                    VALUES (?,?,?,?,?,?,?,?,?,?,?,?)');     
                                    return $db->execute(array($this->institution_id, $this->institution_filepath, $this->institution_paginator_limit,$this->institution_standard_role, $this->institution_standard_country, $this->institution_standard_state, $this->institution_csv_size, $this->institution_avatar_size, $this->institution_material_size, $this->institution_acc_days, $this->institution_language, $this->institution_timeout));
                                    break; 
                case 'user':        $this->user_id = $id; 
                                    $db = DB::prepare('INSERT INTO config_user(user_id, user_paginator_limit, user_acc_days, user_language) VALUES (?,?,?,?)');
                                    return $db->execute(array($this->user_id, $this->user_paginator_limit, $this->user_acc_days, $this->user_language));
                                    break; 
                default:            break; 
            }
    }
    /**
     * delete configuration from db
     * @param string $dependency
     * @param int $id
     * @return boolean 
     */
    public function delete($dependency = null, $id = null){
            switch ($dependency) {
                case 'institution': $this->institution_id = $id; 
                                    $db = DB::prepare('DELETE FROM config_institution WHERE institution_id = ?');
                                    $db->execute(array($this->institution_id));
                                    return $db->execute(array($this->institution_id));
                                    break; 
                case 'user':        $this->user_id = $id; 
                                    $db = DB::prepare('DELETE FROM config_user WHERE user_id = ?');                    
                                    return $db->execute(array($this->user_id)); 
                                    break; 
                default:            break; 
            }
    }
    /**
     * update configuration in db
     * @param string $dependency
     * @return boolean
     */
    public function update($dependency = null){    
        switch ($dependency) {
            case 'user':        return $this->updateUser(); 
                break;
                            
            case 'institution': $this->updateUser();
                                $db = DB::prepare('SELECT COUNT(institution_id) FROM config_institution WHERE institution_id =  ?'); 
                                $db->execute(array($this->institution_id));
                                if($db->fetchColumn() >= 1) { 
                                    $db = DB::prepare('UPDATE config_institution SET 
                                                            institution_paginator_limit = ?, 
                                                            institution_standard_role = ?,
                                                            institution_standard_country = ?,
                                                            institution_standard_state = ?,
                                                            institution_csv_size = ?,
                                                            institution_avatar_size = ?,
                                                            institution_material_size = ?,
                                                            institution_acc_days = ?,
                                                            institution_language = ?,
                                                            institution_timeout = ?,
                                                            update_time = NOW()
                                                            WHERE institution_id = ?'); 
                                return $db->execute(array($this->institution_paginator_limit, $this->institution_standard_role, $this->institution_standard_country, $this->institution_standard_state, $this->institution_csv_size, $this->institution_avatar_size, $this->institution_material_size, $this->institution_acc_days, $this->institution_language, $this->institution_timeout, $this->institution_id));
                                } else { 
                                    $db = DB::prepare('INSERT INTO config_institution(
                                                            institution_paginator_limit,
                                                            institution_standard_role,
                                                            institution_standard_country,
                                                            institution_standard_state,
                                                            institution_csv_size,
                                                            institution_avatar_size,
                                                            institution_material_size,
                                                            institution_acc_days,
                                                            institution_language,
                                                            institution_timeout,
                                                            update_time) 
                                                       VALUES (?,?,?,?, ?, ?, ?, ?, ?, ?, NOW())'); 
                                return $db->execute(array($this->institution_paginator_limit, $this->institution_standard_role, $this->institution_standard_country, $this->institution_standard_state, $this->institution_csv_size, $this->institution_avatar_size, $this->institution_material_size, $this->institution_acc_days, $this->institution_language, $this->institution_timeout, $this->institution_id));   	
                                }
                break;

            default:
                break;
        }     
    }
    
    /**
     * update user config
     * @return boolean
     */
    public function updateUser (){
        $db = DB::prepare('SELECT COUNT(user_id) FROM config_user WHERE user_id = ?');
        $db->execute(array($this->user_id));
        if($db->fetchColumn() >= 1) { 
            $db = DB::prepare('UPDATE config_user  SET user_language = ?, user_filepath = ?,
                                    user_paginator_limit = ?, user_acc_days = ? WHERE user_id = ?');                   
            return $db->execute(array($this->user_language, $this->user_filepath, $this->user_paginator_limit, $this->user_acc_days,$this->user_id)); 
        } else { 
            $db = DB::prepare('INSERT INTO config_user(user_language, user_filepath, user_paginator_limit, user_acc_days, user_id) VALUES (?,?,?,?,?)');                    
            return $db->execute(array($this->user_language, $this->user_filepath, $this->user_paginator_limit, $this->user_acc_days, $this->user_id));
        }
    }
}
?>