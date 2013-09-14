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
        $query = sprintf("INSERT INTO files (title, filename, description, type, path, context_id, creator_id, cur_id, ter_id, ena_id) 
                            VALUES ('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')",
                                        mysql_real_escape_string($this->title),
                                        mysql_real_escape_string($this->filename),
                                        mysql_real_escape_string($this->description),
                                        mysql_real_escape_string($this->type),
                                        mysql_real_escape_string($this->path),
                                        mysql_real_escape_string($this->context_id),
                                        mysql_real_escape_string($this->creator_id),
                                        mysql_real_escape_string($this->curriculum_id),
                                        mysql_real_escape_string($this->terminal_objective_id),
                                        mysql_real_escape_string($this->enabling_objective_id));
        return mysql_query($query);		
    }
    
    /**
     * Update file
     * @return boolean 
     */
    public function update(){
        $query = sprintf("UPDATE files SET title = '%s', filename = '%s', description = '%s', type = '%s', path = '%s', 
                                           context_id = '%s', creator_id = '%s', cur_id = '%s', ter_id = '%s', ena_id = '%s'
                                WHERE id = '%s'",
                                        mysql_real_escape_string($this->title),
                                        mysql_real_escape_string($this->filename),
                                        mysql_real_escape_string($this->description),
                                        mysql_real_escape_string($this->type),
                                        mysql_real_escape_string($this->path),
                                        mysql_real_escape_string($this->context_id),
                                        mysql_real_escape_string($this->creator_id),
                                        mysql_real_escape_string($this->curriculum_id),
                                        mysql_real_escape_string($this->terminal_objective_id),
                                        mysql_real_escape_string($this->enabling_objective_id),
                                        mysql_real_escape_string($this->id));
        return mysql_query($query);
    }
    
    /**
     * Delete file
     * @return mixed 
     */
    public function delete(){
        global $CFG;
        $this->load();
        $query = sprintf("DELETE FROM files WHERE id='%s'",
                                        mysql_real_escape_string($this->id));
        if (mysql_query($query)){/* unlink file*/
            
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
    
    /**
     * Load file with id $this->id 
     */
    public function load(){
        $query = sprintf("SELECT * FROM files WHERE id='%s'",
                        mysql_real_escape_string($this->id));
        $result = mysql_query($query);
        $row = mysql_fetch_assoc($result);
        $this->id                    = $row['id'];
        $this->title                 = $row["title"];
        $this->filename              = $row["filename"];
        $this->description           = $row["description"];
        $this->path                  = $row["path"];
        $this->filetype              = $row["type"];
        $this->context_id            = $row["context_id"];
        $this->curriculum_id         = $row["cur_id"];
        $this->terminal_objective_id = $row["ter_id"];
        $this->enabling_objective_id = $row["ena_id"];
        $this->creation_time         = $row["creation_time"];
        $this->creator_id            = $row["creator_id"];   
    }
    
    /**
     * get Solutions depending on dependency
     * @param string $dependency
     * @param inst $course_id
     * @param string $user_ids 
     */
    public function getSolutions($dependency = null, $course_id = null, $user_ids = null){
        switch ($dependency) {
            case 'course':  if (!is_array($user_ids)){
                               $user_ids = array ($user_ids);
                            }
                            $query = sprintf("SELECT fl.*, us.firstname, us.lastname
                            FROM files AS fl, users AS us
                            WHERE fl.cur_id = '%s'
                            AND fl.creator_id IN (%s)
                            AND fl.creator_id = us.id
                            AND fl.context_id = '4'",
                            mysql_real_escape_string($course_id),
                            mysql_real_escape_string(implode(", ", $user_ids)));
                break;

            default:
                break;
        }
        $result = mysql_query($query);
        if ($result && mysql_num_rows($result)) {
            $files = array(); //Array of files
            while($row = mysql_fetch_assoc($result)) { 
                    $this->id                    = $row['id'];
                    $this->title                 = $row["title"];
                    $this->filename              = $row["filename"];
                    $this->description           = $row["description"];
                    $this->path                  = $row["path"];
                    $this->filetype              = $row["type"];
                    $this->context_id            = $row["context_id"];
                    $this->curriculum_id         = $row["cur_id"];
                    $this->terminal_objective_id = $row["ter_id"];
                    $this->enabling_objective_id = $row["ena_id"];
                    $this->creation_time         = $row["creation_time"];
                    $this->creator_id            = $row["creator_id"];
                    $this->firstname             = $row["firstname"];
                    $this->lastname              = $row["lastname"];
                    
                    $files[] = clone $this;        //it has to be clone, to get the object and not the reference
            } 
            
            return $files;
        } else {return false;}
        
    }
    /**
     * get files depending on dependency
     * @param string $dependency
     * @param int $id
     * @return array of file objects|boolean 
     */
    public function getFiles($dependency = null, $id = null){
        switch ($dependency) {
            case 'context':     $query = sprintf("SELECT fl.*, ct.path AS context_path 
                                                        FROM files AS fl, context AS ct
                                                        WHERE fl.context_id = '%s' AND fl.context_id = ct.context_id",
                                            mysql_real_escape_string($id));
                break;
            case 'userfiles':   $query = sprintf("SELECT fl.*, ct.path AS context_path 
                                                        FROM files AS fl, context AS ct
                                                        WHERE fl.creator_id = '%s' AND fl.context_id = 1 AND fl.context_id = ct.context_id",
                                            mysql_real_escape_string($id));
                break;
            case 'curriculum':  $query = sprintf("SELECT fl.*, ct.path AS context_path 
                                                        FROM files AS fl, context AS ct
                                                        WHERE fl.cur_id = '%s' AND fl.context_id = 2 AND fl.context_id = ct.context_id",
                                            mysql_real_escape_string($id));
                break;
            case 'enabling_objective': $query = sprintf("SELECT fl.*, ct.path AS context_path 
                                                        FROM files AS fl, context AS ct
                                                        WHERE fl.ena_id = '%s' AND fl.context_id = 2 AND fl.context_id = ct.context_id",
                                            mysql_real_escape_string($id));
                break;
            case 'avatar':      $query = sprintf("SELECT fl.*, ct.path AS context_path 
                                                        FROM files AS fl, context AS ct
                                                        WHERE fl.creator_id = '%s' AND fl.context_id = 3 AND fl.context_id = ct.context_id",
                                            mysql_real_escape_string($id));
                break;
            case 'solution':    $query = sprintf("SELECT fl.*, ct.path AS context_path 
                                                        FROM files AS fl, context AS ct
                                                        WHERE fl.cur_id = '%s' AND fl.context_id = 4 AND fl.context_id = ct.context_id",
                                            mysql_real_escape_string($id));
                break;

            case 'user':               $query = sprintf("SELECT fl.*, ct.path AS context_path 
                                                        FROM files AS fl, context AS ct
                                                        WHERE fl.creator_id = '%s'
                                                        AND fl.context_id = ct.context_id", 
                                        mysql_real_escape_string($id));
                break;
            case 'enabling_objective': $query = sprintf("SELECT id 
                                                        FROM files
                                                        WHERE ena_id = '%s'",
                                       mysql_real_escape_string($id));
                break;
            default : break; 
            
        }                     
        
        $result = mysql_query($query);
        if ($result && mysql_num_rows($result)) {
            $files = array(); //Array of files
            while($row = mysql_fetch_assoc($result)) { 
                    $this->id                    = $row['id'];
                    $this->title                 = $row['title'];
                    $this->filename              = $row["filename"];
                    $this->description           = $row["description"];
                    $this->path                  = $row["path"];
                    $this->filetype              = $row["type"];
                    $this->context_id            = $row["context_id"];
                    if (isset($row["context_path"])){
                        $this->context_path      = $row["context_path"];
                    }
                    $this->curriculum_id         = $row["cur_id"];
                    $this->terminal_objective_id = $row["ter_id"];
                    $this->enabling_objective_id = $row["ena_id"];
                    $this->creation_time         = $row["creation_time"];
                    $this->creator_id            = $row["creator_id"];
                    
                    $files[] = clone $this;        //it has to be clone, to get the object and not the reference
            } 
            return $files;
        } else {return false;}
    }
    
 /**
 * get context path
 * @param string $context
 * @return string 
 */
public function getContextPath($context){ //get Context by context name
$query = sprintf("SELECT path FROM context WHERE context = '%s'",
                    mysql_real_escape_string($context));
    $result = mysql_query($query);
    if ($result && mysql_num_rows($result)) {
        return  mysql_result($result, 0, "path");
    } else {return false;}
}

/**
 * get context id
 * @param string $context
 * @return string 
 */
public function getContextId($context){ //get Context by context name
$query = sprintf("SELECT context_id FROM context WHERE context = '%s'",
                    mysql_real_escape_string($context));
    $result = mysql_query($query);
    if ($result && mysql_num_rows($result)) {
        return  mysql_result($result, 0, "context_id");
    } else {return false;}
}

/**
 * function used during the install process to set up creator id to new admin
 * @return boolean
 */
public function dedicate(){ // only use during install
        $query = sprintf("UPDATE files SET creator_id = '%s'",
                                            mysql_real_escape_string($this->creator_id));
        return mysql_query($query);
    }

}
?>