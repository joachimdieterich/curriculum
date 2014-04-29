<?php
/**
 * file object can add, update, delete and get data from files db
 * 
 * 
 * @abstract This file is part of curriculum - http://www.joachimdieterich.de
 * @package core
 * @filename file.class.php
 * @copyright 2013 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2013.06.09 21:06
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
class File {
    /**
     * id of file
     * @var int
     */
    public $id = null;
    /**
     * title of file
     * @var string 
     */
    public $title = null;
    /**
     * filename
     * @var string
     */
    public $filename = null; 
    /**
     * Description of file
     * @var string
     */
    public $description = null; 
    /**
     * filetype
     * @var string 
     */
    public $type = null; 
    /**
     * filepath
     * @var string 
     */
    public $path = null; 
    /**
     * id of context
     * @var int 
     */
    public $context_id = null; 
    /**
     * context depending path
     * @var string 
     */
    public $context_path = null; 
    /**
     * timestamp when file was created
     * @var timestamp
     */
    public $creation_time = null; 
    /**
     * ID of User who created this file
     * @var int
     */
    public $creator_id = null; 
    /**
     * firstname
     * @var string 
     */
    public $firstname = null; 
    /**
     * lastname
     * @var string 
     */
    public $lastname = null; 
    /**
     * id of curriculum
     * @var int
     */
    public $curriculum_id = null; 
    /**
     * id of terminal objective
     * @var int
     */
    public $terminal_objective_id = null; 
    /**
     * id of enabling objective
     * @var int
     */
    public $enabling_objective_id = null; 
    
    /**
     * add file
     * @return mixed 
     */
    public function add(){
        global $USER;
        if (checkCapabilities('file:upload', $USER->role_id)){
            $db = DB::prepare('INSERT INTO files (title, filename, description, type, path, context_id, creator_id, cur_id, ter_id, ena_id) 
                                VALUES (?,?,?,?,?,?,?,?,?,?)');
            return $db->execute(array($this->title, $this->filename, $this->description, $this->type, $this->path, $this->context_id, $this->creator_id, $this->curriculum_id, $this->terminal_objective_id, $this->enabling_objective_id));
        }
    }
    
    /**
     * Update file
     * @return boolean 
     */
    public function update(){
        global $USER;
        if (checkCapabilities('file:update', $USER->role_id)){
            $db = DB::prepare('UPDATE files SET title = ?, filename = ?, description = ?, type = ?, path = ?, context_id = ?, creator_id = ?, cur_id = ?, ter_id = ?, ena_id = ? WHERE id = ?');
            return $db->execute(array($this->title, $this->filename, $this->description, $this->type, $this->path, $this->context_id, $this->creator_id, $this->curriculum_id, $this->terminal_objective_id, $this->enabling_objective_id, $this->id));
        }
    }
    
    /**
     * Delete file
     * @return mixed 
     */
    public function delete(){
        global $CFG, $USER;
        if (checkCapabilities('file:delete', $USER->role_id)){
            $this->load();
            $db = DB::prepare('DELETE FROM files WHERE id=?');
            if ($db->execute(array($this->id))){/* unlink file*/
                switch ($this->context_id) {
                    case 1: 
                            $return = (unlink($CFG->user_root.$this->creator_id.'/'.$this->filename)); //Datei vom Server löschen     
                        break;
                    case 2:
                            $return = (unlink($CFG->curriculum_root.$this->curriculum_id.'/'.$this->terminal_objective_id.'/'.$this->enabling_objective_id.'/'.$this->filename)); //Datei vom Server löschen     
                        break;
                    case 3: // evtl erst checken, ob Avatar verwendet wird.
                            /*$query = sprintf("SELECT fl.*, users.avatar
                                                    FROM files AS fl
                                                    LEFT JOIN users ON users.avatar = files.filename 
                                                    WHERE files.id = '%s'",
                                            mysql_real_escape_string($_GET['fileID']));
                                $result = mysql_query($query);
                                if ($result && mysql_num_rows($result)){
                                    if (mysql_num_rows($result) == 1){
                                        $dbuser = mysql_result($result, 0, "avatar"); 
                                        $dbfilename =mysql_result($result, 0, "filename");
                                        if ($dbuser == NULL && $dbfilename == NULL){
                                            $filEnrExists = false;
                                        }
                                    } else {
                                        $filEnrExists = true; 
                                    }
                                } else {$filEnrExists = false;} */
                            $return = (unlink($CFG->avatar_root.$this->filename)); //Datei vom Server löschen     
                        break;
                    case 4:
                            $return = (unlink($CFG->solutions_root.$this->curriculum_id.'/'.$this->terminal_objective_id.'/'.$this->enabling_objective_id.'/'.$this->filename)); //Datei vom Server löschen     
                        break;
                    case 5:
                            $return = (unlink($CFG->subjects_root.$this->filename)); //Datei vom Server löschen     
                        break;

                    default: $return = false; 
                        break;
                }
                return $return; 
            } else {
                return false;
            }
        }
    } 
    
    /**
     * Load file with id $this->id 
     */
    public function load(){
        $db = DB::prepare('SELECT * FROM files WHERE id=?');
        $db->execute(array($this->id));
        $result = $db->fetchObject();
        $this->id                    = $result->id;
        $this->title                 = $result->title;
        $this->filename              = $result->filename;
        $this->description           = $result->description;
        $this->path                  = $result->path;
        $this->filetype              = $result->type;
        $this->context_id            = $result->context_id;
        $this->curriculum_id         = $result->cur_id;
        $this->terminal_objective_id = $result->ter_id;
        $this->enabling_objective_id = $result->ena_id;
        $this->creation_time         = $result->creation_time;
        $this->creator_id            = $result->creator_id;   
    }
    
    /**
     * get Solutions depending on dependency
     * @param string $dependency
     * @param inst $course_id
     * @param string $user_ids 
     */
    public function getSolutions($dependency = null, $course_id = null, $user_ids = null){
        global $USER;
        if (checkCapabilities('file:getSolutions', $USER->role_id)){
            switch ($dependency) {
                case 'course':  if (is_array($user_ids)){
                                    $user_ids = implode(", ", $user_ids);
                                }
                                $db = DB::prepare('SELECT fl.*, us.firstname, us.lastname FROM files AS fl, users AS us
                                    WHERE fl.cur_id = ? AND fl.creator_id IN ('.$user_ids.')
                                    AND fl.creator_id = us.id AND fl.context_id = 4');
                                $db->execute(array($course_id));  
                                break;
                default:        break;
            }
            $files = array(); //Array of files
            while($result = $db->fetchObject()) { 
                    $this->id                    = $result->id;
                    $this->title                 = $result->title;
                    $this->filename              = $result->filename;
                    $this->description           = $result->description;
                    $this->path                  = $result->path;
                    $this->filetype              = $result->type;
                    $this->context_id            = $result->context_id;
                    $this->curriculum_id         = $result->cur_id;
                    $this->terminal_objective_id = $result->ter_id;
                    $this->enabling_objective_id = $result->ena_id;
                    $this->creation_time         = $result->creation_time;
                    $this->creator_id            = $result->creator_id;
                    $this->firstname             = $result->firstname;
                    $this->lastname              = $result->lastname;
                    $files[] = clone $this;        //it has to be clone, to get the object and not the reference
            } 
            if (isset($files)) {  
                return $files;
            } else {return false;}
        }
        
    }
    
    /**
     * get files depending on dependency
     * @param string $dependency
     * @param int $id
     * @return array of file objects|boolean 
     */
    public function getFiles($dependency = null, $id = null){
        switch ($dependency) {
            case 'context':     $db = DB::prepare('SELECT fl.*, ct.path AS context_path FROM files AS fl, context AS ct
                                                        WHERE fl.context_id = ? AND fl.context_id = ct.context_id');
                                $db->execute(array($id));
                break;
            case 'userfiles':   $db = DB::prepare('SELECT fl.*, ct.path AS context_path FROM files AS fl, context AS ct
                                                        WHERE fl.creator_id = ? AND fl.context_id = 1 AND fl.context_id = ct.context_id');
                                $db->execute(array($id));
                break;
            case 'curriculum':  $db = DB::prepare('SELECT fl.*, ct.path AS context_path FROM files AS fl, context AS ct
                                                        WHERE fl.cur_id = ? AND fl.context_id = 2 AND fl.context_id = ct.context_id');
                                $db->execute(array($id));
                break;
            case 'enabling_objective':$db = DB::prepare('SELECT fl.*, ct.path AS context_path FROM files AS fl, context AS ct
                                                        WHERE fl.ena_id = ? AND fl.context_id = 2 AND fl.context_id = ct.context_id');
                                $db->execute(array($id));
                break;
            case 'avatar':      $db = DB::prepare('SELECT fl.*, ct.path AS context_path FROM files AS fl, context AS ct
                                                        WHERE fl.creator_id = ? AND fl.context_id = 3 AND fl.context_id = ct.context_id');
                                $db->execute(array($id));
                break;
            case 'solution':    $db = DB::prepare('SELECT fl.*, ct.path AS context_path FROM files AS fl, context AS ct
                                                        WHERE fl.cur_id = ? AND fl.context_id = 4 AND fl.context_id = ct.context_id');
                                $db->execute(array($id));
                break;

            case 'user':        $db = DB::prepare('SELECT fl.*, ct.path AS context_path FROM files AS fl, context AS ct
                                                        WHERE fl.creator_id = ? AND fl.context_id = ct.context_id');
                                $db->execute(array($id));
                break;
            case 'enabling_objective': $db = DB::prepare('SELECT id FROM filesWHERE ena_id = ?');
                                $db->execute(array($id));
                break;
            default : break; 
            
        }                      
        $files = array(); //Array of files
        while($result = $db->fetchObject()) { 
                $this->id                    = $result->id;
                $this->title                 = $result->title;
                $this->filename              = $result->filename;
                $this->description           = $result->description;
                $this->path                  = $result->path;
                $this->filetype              = $result->type;
                $this->context_id            = $result->context_id;
                if (isset($result->context_path)){
                    $this->context_path      = $result->context_path;
                }
                $this->curriculum_id         = $result->cur_id;
                $this->terminal_objective_id = $result->ter_id;
                $this->enabling_objective_id = $result->ena_id;
                $this->creation_time         = $result->creation_time;
                $this->creator_id            = $result->creator_id;
                $files[] = clone $this;        //it has to be clone, to get the object and not the reference
        }
        if (isset($files)) {
            return $files;
        } else {return false;}
    }
    
 /**
 * get context path
 * @param string $context
 * @return string 
 */
public function getContextPath($context){ //get Context by context name
    $db = DB::prepare('SELECT path FROM context WHERE context = ?');
    $db->execute(array($context));
    $result = $db->fetchObject();
    if ($result) {
        return  $result->path;
    } else {return false;}
}

/**
 * get context id
 * @param string $context
 * @return string 
 */
public function getContextId($context){ //get Context by context name
    $db = DB::prepare('SELECT context_id FROM context WHERE context = ?');
    $db->execute(array($context));
    $result = $db->fetchObject();
    if ($result) {
        return  $result->context_id;
    } else {return false;}
}

/**
 * function used during the install process to set up creator id to new admin
 * @return boolean
 */
public function dedicate(){ // only use during install
    $db = DB::prepare('UPDATE files SET creator_id = ?');
    return $db->execute(array($this->creator_id));
}
}
?>