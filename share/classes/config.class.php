<?php
/**
 * 
 * @abstract This file is part of curriculum - http://www.joachimdieterich.de
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
    public $creation_time                   = null; //evtl. überflüssig ???
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
                                
                                $query = sprintf("SELECT * FROM config_institution
                                                    WHERE institution_id IN ('%s')",
                                                    mysql_real_escape_string($this->institution_id));
                                $result = mysql_query($query);
                                $this->institution_id                    = mysql_result($result, 0, "institution_id");
                                $this->institution_filepath              = mysql_result($result, 0, "institution_filepath");
                                $this->institution_paginator_limit       = mysql_result($result, 0, "institution_paginator_limit");
                                $this->institution_standard_role         = mysql_result($result, 0, "institution_standard_role");
                                $this->institution_standard_country      = mysql_result($result, 0, "institution_standard_country");
                                $this->institution_standard_state        = mysql_result($result, 0, "institution_standard_state");
                                $this->institution_csv_size              = mysql_result($result, 0, "institution_csv_size");
                                $this->institution_avatar_size           = mysql_result($result, 0, "institution_avatar_size");
                                $this->institution_material_size         = mysql_result($result, 0, "institution_material_size");
                                $this->institution_acc_days              = mysql_result($result, 0, "institution_acc_days");
                                $this->institution_language              = mysql_result($result, 0, "institution_language");
                                $this->institution_timeout               = mysql_result($result, 0, "institution_timeout");
                                $query = sprintf("SELECT * FROM config_user
                                                    WHERE user_id = '%s'",
                                                    mysql_real_escape_string($USER->id));
                                $result = mysql_query($query);
                                $this->user_id                    = mysql_result($result, 0, "user_id");
                                $this->user_filepath              = mysql_result($result, 0, "user_filepath");
                                $this->user_paginator_limit       = mysql_result($result, 0, "user_paginator_limit");
                                $this->user_acc_days              = mysql_result($result, 0, "user_acc_days");
                                $this->user_language              = mysql_result($result, 0, "user_language"); 
                break;
            case 'user':        $this->user_id = $id;
                                $query = sprintf("SELECT * FROM config_user
                                                    WHERE user_id = '%s'",
                                                    mysql_real_escape_string($this->user_id));
                                $result = mysql_query($query);
                                $this->user_id                    = mysql_result($result, 0, "user_id");
                                $this->user_filepath              = mysql_result($result, 0, "user_filepath");
                                $this->user_paginator_limit       = mysql_result($result, 0, "user_paginator_limit");
                                $this->user_acc_days              = mysql_result($result, 0, "user_acc_days");
                                $this->user_language              = mysql_result($result, 0, "user_language");  
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
                                $query = sprintf("INSERT INTO config_institution(institution_id, institution_filepath, 
                                                            institution_paginator_limit,institution_standard_role, institution_standard_country,
                                                            institution_standard_state, institution_csv_size, institution_avatar_size, institution_material_size,
                                                            institution_acc_days, institution_language, institution_timeout) 
                                                       VALUES ('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')",
                                                        mysql_real_escape_string($this->institution_id),
                                                        mysql_real_escape_string($this->institution_filepath),
                                                        mysql_real_escape_string($this->institution_paginator_limit),
                                                        mysql_real_escape_string($this->institution_standard_role),
                                                        mysql_real_escape_string($this->institution_standard_country),
                                                        mysql_real_escape_string($this->institution_standard_state),
                                                        mysql_real_escape_string($this->institution_csv_size),
                                                        mysql_real_escape_string($this->institution_avatar_size),
                                                        mysql_real_escape_string($this->institution_material_size),
                                                        mysql_real_escape_string($this->institution_acc_days),
                                                        mysql_real_escape_string($this->institution_language),
                                                        mysql_real_escape_string($this->institution_timeout)
                                                        );
                                return mysql_query($query); 
                    break; 
                case 'user':    $this->user_id = $id; 
                                $query = sprintf("INSERT INTO config_user(user_id, 
                                                            user_paginator_limit,
                                                            user_acc_days,
                                                            user_language) 
                                                       VALUES ('%s','%s','%s','%s')",
                                                        mysql_real_escape_string($this->user_id),
                                                        mysql_real_escape_string($this->user_paginator_limit),
                                                        mysql_real_escape_string($this->user_acc_days),
                                                        mysql_real_escape_string($this->user_language)
                                                        );
                                return mysql_query($query);  
                    break; 
                default: break; 
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
                                $query = sprintf("DELETE FROM config_institution WHERE institution_id = '%s'",
                                                        mysql_real_escape_string($this->institution_id));
                                return mysql_query($query); 
                    break; 
                case 'user':    $this->user_id = $id; 
                                $query = sprintf("DELETE FROM config_user WHERE user_id = '%s'",
                                                        mysql_real_escape_string($this->user_id));
                                return mysql_query($query);  
                    break; 
                default: break; 
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
                                $query = sprintf("SELECT COUNT(institution_id) FROM config_institution 
                                                    WHERE institution_id = '%s'",
                                                    mysql_real_escape_string($this->institution_id));
                                $result = mysql_query($query);
                                list($count) = mysql_fetch_row($result);
                                if($count >= 1) { 
                                    $query = sprintf("UPDATE config_institution SET 
                                                            institution_paginator_limit = '%s', 
                                                            institution_standard_role = '%s',
                                                            institution_standard_country = '%s',
                                                            institution_standard_state = '%s',
                                                            institution_csv_size = '%s',
                                                            institution_avatar_size = '%s',
                                                            institution_material_size = '%s',
                                                            institution_acc_days = '%s',
                                                            institution_language = '%s',
                                                            institution_timeout = '%s',
                                                            update_time = NOW()
                                                            WHERE institution_id = '%s'",
                                                        mysql_real_escape_string($this->institution_paginator_limit),
                                                        mysql_real_escape_string($this->institution_standard_role),
                                                        mysql_real_escape_string($this->institution_standard_country),
                                                        mysql_real_escape_string($this->institution_standard_state),
                                                        mysql_real_escape_string($this->institution_csv_size),
                                                        mysql_real_escape_string($this->institution_avatar_size),
                                                        mysql_real_escape_string($this->institution_material_size),
                                                        mysql_real_escape_string($this->institution_acc_days),
                                                        mysql_real_escape_string($this->institution_language),
                                                        mysql_real_escape_string($this->institution_timeout),
                                                        mysql_real_escape_string($this->institution_id));
                                    return mysql_query($query);
                                } else { 
                                    $query = sprintf("INSERT INTO config_institution(
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
                                                       VALUES ('%s','%s','%s','%s', '%s', '%s', '%s', '%s', '%s', '%s', NOW())",
                                                        mysql_real_escape_string($this->institution_paginator_limit),
                                                        mysql_real_escape_string($this->institution_standard_role),
                                                        mysql_real_escape_string($this->institution_standard_country),
                                                        mysql_real_escape_string($this->institution_standard_state),
                                                        mysql_real_escape_string($this->institution_csv_size),
                                                        mysql_real_escape_string($this->institution_avatar_size),
                                                        mysql_real_escape_string($this->institution_material_size),
                                                        mysql_real_escape_string($this->institution_acc_days),
                                                        mysql_real_escape_string($this->institution_language),
                                                        mysql_real_escape_string($this->institution_timeout),
                                                        mysql_real_escape_string($this->institution_id));
                                    return mysql_query($query);      	
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
        $query = sprintf("SELECT COUNT(user_id) FROM config_user 
                            WHERE user_id = '%s'",
                            mysql_real_escape_string($this->user_id));
        $result = mysql_query($query);
        list($count) = mysql_fetch_row($result);
        if($count >= 1) { 
            $query = sprintf("UPDATE config_user 
                                    SET user_language = '%s', 
                                    user_filepath = '%s',
                                    user_paginator_limit = '%s',
                                    user_acc_days = '%s'
                                    WHERE user_id = '%s'",
                                mysql_real_escape_string($this->user_language),
                                mysql_real_escape_string($this->user_filepath),
                                mysql_real_escape_string($this->user_paginator_limit),
                                mysql_real_escape_string($this->user_acc_days),
                                mysql_real_escape_string($this->user_id));
            return mysql_query($query);
        } else { 
            $query = sprintf("INSERT INTO config_user(user_language, 
                                                    user_filepath, 
                                                    user_paginator_limit,
                                                    user_acc_days,
                                                    user_id) VALUES ('%s','%s','%s','%s','%s')",
                                mysql_real_escape_string($this->user_language),
                                mysql_real_escape_string($this->user_filepath),
                                mysql_real_escape_string($this->user_paginator_limit),
                                mysql_real_escape_string($this->user_acc_days),
                                mysql_real_escape_string($this->user_id));
            return mysql_query($query);      	
        }
    }
}
?>