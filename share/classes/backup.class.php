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
    /**
     * id of backup
     * @var int
     */
    public $id              = null; 
    /**
     * path to backup file
     * @var string
     */
    public $file_path       = null; 
    /**
     * filename of backup
     * @var string
     */
    public $file_name       = null; 
    /**
     * id of curriculum
     * @var int
     */
    public $curriculum_id   = null; 
    /**
     * name of curriculum
     * @var string
     */
    public $curriculum      = null;
    /**
     * user id of creator
     * @var int
     */
    public $creator_id      = null; 
    /**
     * username of creator
     * @var string
     */
    public $creator         = null; 
    /**
     * timestamp of file creation
     * @var timestamp
     */
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
       global $USER;
       if (checkCapabilities('backup:addBackup', $USER->role_id)){ //check capability
            $db = DB::prepare('INSERT INTO files_backup (file_path,file_name,curriculum_id,creator_id) VALUES (?,?,?,?)');                
            return $db->execute(array($url, $filename, $cur_id, $userID)); 
       }
    }
    
    /**
     * load function 
     * @global int $USER
     * @param string $dependency
     * @return Array of backup objects|boolean 
     */
    public function load($dependency = null){
        global $USER; 
        if (checkCapabilities('backup:loadBackup', $USER->role_id)){ //check capability
            switch ($dependency) {
                case 'admin':   $db = DB::prepare('SELECT fb.*, cu.curriculum, us.username
                                                    FROM files_backup AS fb, curriculum AS cu, users AS us
                                                    WHERE cu.id = fb.curriculum_id
                                                    AND us.id = fb.creator_id ORDER BY fb.id DESC');
                                $db->execute();                    
                    break;
                case 'teacher': $db = DB::prepare('SELECT DISTINCT fb.*, cu.curriculum, us.username
                                                    FROM files_backup AS fb, curriculum_enrolments AS ce, curriculum AS cu, users AS us
                                                    WHERE fb.curriculum_id = ce.curriculum_id 
                                                    AND cu.id = fb.curriculum_id
                                                    AND us.id = fb.creator_id
                                                    AND ce.group_id = ANY(SELECT gr.group_id
                                                                        FROM groups_enrolments AS gr, institution_enrolments AS ine
                                                                        WHERE ine.user_id = gr.user_id
                                                                        AND ine.institution_id IN (?)
                                                                        AND gr.user_id =  ?
                                                                        OR gr.creator_id =  ?) ORDER BY fb.id DESC');
                                $db->execute(array(implode(',', $USER->institutions["id"]), $USER->id, $USER->id));                                            
                    break;

                default:
                    break;
            }

            $backup = array();   
            while($result = $db->fetchObject()) { ; 
                    $this->id               = $result->id;
                    $this->file_path        = $result->file_path;
                    $this->file_name        = $result->file_name;
                    $this->curriculum_id    = $result->curriculum_id;
                    $this->curriculum       = $result->curriculum;
                    $this->creation_time    = $result->creation_time;
                    $this->creator_id       = $result->creator_id;
                    $this->creator          = $result->username;
                    $backup[] = clone $this;        //it has to be clone, to get the object and not the reference
            } 
            if (isset($backup)) {    
                return $backup;
            } else {
                return false;
            } 
        }
    }
    
    public function delete($id = null){ //not used yet
        global $USER; 
        if (checkCapabilities('backup:deleteBackup', $USER->role_id)){ //check capability
        $db = DB::prepare('DELETE FROM files_backup WHERE id = ? '); 
            return $db->execute(array($id));
        }
    }
}
?>