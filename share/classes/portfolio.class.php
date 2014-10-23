<?php
/**
 * enabling objective class can add, update, delete and get data from curriculum db
 * 
 * @example
 * // Add new objective <br>
 * $new_objective = new Objective(); <br>
 * 
 * @abstract This file is part of curriculum - http://www.joachimdieterich.de
 * @package core
 * @filename objective.class.php
 * @copyright 2013 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2013.06.11 21:00
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
class Portfolio {
    /**
     * ID of enabling objective
     * @var int
     */
    public $id;
    /**
     * enabling Objective
     * @var string 
     */
    public $title;
    /**
     * Description of enabling objective
     * @var string
     */
    public $description; 
    /**
     * filename
     * @var string 
     */
    public $filename;
    /**
     * filetype
     * @var string 
     */
    public $type; 
    /**
     * filepath
     * @var string 
     */
    public $path; 
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
     * licence
     * @since 0.9
     * @var string
     */
    public $licence;
    /**
     * name of creator
     * @var string
     */
    public $creator; 
    /**
     * repeat interval
     * @var int 
     */
    public $repeat_interval;
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
    
    public $artefacts = array();
    public $artefact_type; 
    
   
    public function getArtefacts (){
        global $USER;
        $this->getLastEnablingObjectives($USER);
        $enabling = $this->artefacts;
        $this->getFiles($USER->id);
        $files = $this->artefacts;
        
        $result_merged = array_merge($enabling,$files);
        
        $result = PHPArrayObjectSorter($result_merged, 'creation_time', 'desc');
        
        if (isset($result)){
        } else {
            $result = NULL;
            }
        return $result;
    }
    
    /**
     * get last enabling objectives depending on users accomplished days
     * @global int $USER
     * @return mixed 
     */
    public function getLastEnablingObjectives($user){
        $db = DB::prepare('SELECT ena.*, cur.curriculum, usa.status_id as status_id, 
                            usa.accomplished_time as accomplished_time, usa.creator_id as teacher_id, us.firstname, us.lastname
                        FROM enablingObjectives AS ena, user_accomplished AS usa, curriculum AS cur, users AS us
                        WHERE ena.id = usa.enabling_objectives_id
                        AND us.id = usa.user_id
                        AND ena.curriculum_id = cur.id AND usa.user_id = ? AND usa.status_id = 1
                        ');
        $db->execute(array($user->id));
        while($result = $db->fetchObject()) { 
            $this->artefact_type           = 1; // 1 = enabling objective
            $this->id                      = $result->id;
            $this->title                   = $result->enabling_objective;
            $this->description             = $result->description;
            $this->curriculum              = $result->curriculum;
            $this->creation_time           = $result->creation_time;
            $this->creator_id              = $result->creator_id;   
            $this->accomplished_status_id  = $result->status_id;   
            $this->accomplished_time       = $result->accomplished_time;   
            $this->accomplished_teacher_id = $result->teacher_id;   
            $this->accomplished_teacher = $result->firstname.' '.$result->lastname;   
            $this->artefacts[]                  = clone $this; 
        }
    }
    
    
    
    public function getFiles($user_id){
        GLOBAL $CFG;
       $db = DB::prepare('SELECT fl.*, ct.path AS context_path, us.firstname, us.lastname FROM files AS fl, users AS us, context AS ct
                                    WHERE fl.creator_id IN ('.$user_id.')
                                    AND fl.creator_id = us.id
                                    AND fl.context_id = ct.context_id');
        $db->execute();  
        while($result = $db->fetchObject()) { 
            $this->artefact_type         = 2; // 1 = file
            $this->id                    = $result->id;
            $this->title                 = $result->title;
            $this->filename              = $result->filename;
            $this->description           = $result->description;
            $this->author                = $result->author;
            switch ($result->licence) {
                    case 1: $this->licence = 'Sonstige'; break;
                    case 2: $this->licence = 'Alle Rechte vorbehalten'; break;
                    case 3: $this->licence = 'Public Domain'; break;
                    case 4: $this->licence = 'CC'; break;
                    case 5: $this->licence = 'CC - keine Bearbeitung'; break;
                    case 6: $this->licence = 'CC - keine kommerzielle Nutzung - keine Bearbeitung'; break;
                    case 7: $this->licence = 'CC - keine kommerzielle Nutzung'; break;
                    case 8: $this->licence = 'CC - keine kommerzielle Nutzung - Weitergabe unter gleichen Bedingungen'; break;
                    case 9: $this->licence = 'CC - Weitergabe unter gleichen Bedingungen'; break;
                    default:
                        break;
                    
            }
            $this->path                  = $CFG->BASE_URL.'curriculumdata/'.$result->context_path.$result->path;
            $this->filetype              = $result->type;
            $this->curriculum_id         = $result->cur_id;
            $this->terminal_objective_id = $result->ter_id;
            $this->enabling_objective_id = $result->ena_id;
            $this->creation_time         = $result->creation_time;
            $this->creator_id            = $result->creator_id;
            $this->creator               = $result->firstname.' '.$result->lastname;
            
            $this->artefacts[] = clone $this;        //it has to be clone, to get the object and not the reference
        } 
        
    }
 
    
    
}
?>