<?php

/**
 * This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package core
 * @filename backup.class.php
 * @copyright 2013 joachimdieterich
 * @author joachimdieterich
 * @date 2013.05.27 21:36
 * @license: 
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
class Backup {
    public $id              = null; 
    public $file_path       = null; 
    public $file_name       = null; 
    public $curriculum_id   = null; 
    public $curriculum      = null; 
    public $creator_id      = null; 
    public $creator         = null; 
    
    public $creation_time   = null; 
    
    
   /**
    * add backup file informations to db
    * 
    * @since 0.5
    * 
    * @param string $url                         url of backup folder (where imscc files are finally saved)
    * @param string $filename                    Name of backup file
    * @param string $cur_id                      id of curriculum
    * @param string $userID                      id of current user
    */
    function add($url, $filename, $cur_id, $userID){
        $query = sprintf("INSERT INTO files_backup (file_path,file_name,curriculum_id,creator_id) 
                            VALUES ('%s','%s','%s','%s')",
                        mysql_real_escape_string($url),
                        mysql_real_escape_string($filename),
                        mysql_real_escape_string($cur_id),
                        mysql_real_escape_string($userID));
       return mysql_query($query);  
    }
    
    public function load($dependency = null){
        global $USER; 
        switch ($dependency) {
            case 'admin':   $query = sprintf("SELECT fb.*, cu.curriculum, us.username
                                                FROM files_backup AS fb, curriculum AS cu, users AS us
                                                WHERE cu.id = fb.curriculum_id
                                                AND us.id = fb.creator_id ORDER BY fb.id DESC");
                break;
            case 'teacher': $query = sprintf("SELECT DISTINCT fb.*, cu.curriculum, us.username
                                                FROM files_backup AS fb, curriculum_enrolments AS ce, curriculum AS cu, users AS us
                                                WHERE fb.curriculum_id = ce.curriculum_id 
                                                AND cu.id = fb.curriculum_id
                                                AND us.id = fb.creator_id
                                                AND ce.group_id = ANY(SELECT gr.group_id
                                                                    FROM groups_enrolments AS gr, institution_enrolments AS ine
                                                                    WHERE ine.user_id = gr.user_id
                                                                    AND ine.institution_id IN ('%s')
                                                                    AND gr.user_id =  '%s'
                                                                    OR gr.creator_id =  '%s') ORDER BY fb.id DESC", 
                                            mysql_real_escape_string(implode(',', $USER->institutions["id"])),
                                            mysql_real_escape_string($USER->id),
                                            mysql_real_escape_string($USER->id));
                break;

            default:
                break;
        }
        $result = mysql_query($query);
        $backup = array();
        if ($result && mysql_num_rows($result)) {
            while($row = mysql_fetch_assoc($result)) { ; 
                    $this->id               = $row['id'];
                    $this->file_path        = $row['file_path'];
                    $this->file_name        = $row['file_name'];
                    $this->curriculum_id    = $row['curriculum_id'];
                    $this->curriculum       = $row['curriculum'];
                    $this->creation_time    = $row['creation_time'];
                    $this->creator_id       = $row['creator_id'];
                    $this->creator          = $row['username'];
                   
                    $backup[] = clone $this;        //it has to be clone, to get the object and not the reference
            } 
            
            return $backup;
        } else {
            return false;
        }  
        
    }
}

?>
