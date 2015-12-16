<?php
/**
 * Certificate Class - Zertifikate erstellen
 * 
 * @abstract This file is part of curriculum - http://www.joachimdieterich.de
 * @package core
 * @filename certificate.class.php
 * @copyright 2014 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2014.12.28 12:21
 * @license 
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by  
 * the Free Software Foundation; either version 3 of the License, or (at your option) any later version.                                   
 *                                                                       
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of        
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details:                          
 *                                                                       
 * http://www.gnu.org/copyleft/gpl.html      
 */
class Certificate {
    /**
     * ID of Grade
     * @var int
     */
    public $id;
    /**
     * Name of certificate-template
     * @var string
     */
    public $certificate; 
    /**
     * Description of certificate-template
     * @var string
     */
    public $description; 
    /**
     * HTML certificate-template 
     * @var html 
     */
    public $template;
    /**
     * Timestamp when certificate-template was created
     * @var timestamp
     */
    public $creation_time; 
    /**
     * ID of User who created this certificate-template
     * @var int
     */
    public $creator_id; 
    public $creator;
    /**
     * ID of institution to which certificate-template belongs to
     * @var int
     */
    public $institution_id; 
    public $institution;
   
    
    /**
     * add certificate
     * @return mixed 
     */
    public function add(){
        global $USER;
        checkCapabilities('certificate:add', $USER->role_id);
        $db = DB::prepare('INSERT INTO certificate (certificate,description,template,creator_id,institution_id) VALUES (?,?,?,?,?)');
        return $db->execute(array($this->certificate, $this->description, $this->template, $this->creator_id, $this->institution_id));
    }
    
    /**
     * Update certificate
     * @return boolean 
     */
    public function update(){
        global $USER;
        checkCapabilities('certificate:update', $USER->role_id);    
        $db = DB::prepare('UPDATE certificate SET certificate = ?, description = ?, template = ?, creator_id = ? WHERE id = ?');
        return $db->execute(array($this->certificate, $this->description, $this->template, $this->creator_id, $this->id));
    }
    
    /**
     * delete certificate
     * @global object $USER
     * @param int $creator_id
     * @return boolean 
     */
    public function delete(){
        global $USER;
        checkCapabilities('certificate:delete', $USER->role_id);
        $db = DB::prepare('DELETE FROM certificate WHERE id = ?');
        return $db->execute(array($this->id));
    } 
    
    /**
     * Load certificate with id $this->id 
     */
    public function load(){
        $db = DB::prepare('SELECT * FROM certificate WHERE id = ?');
        $db->execute(array($this->id));
        
        $result = $db->fetchObject();
        $this->certificate       = $result->certificate;
        $this->description       = $result->description;
        $this->template          = $result->template;
        $this->institution_id    = $result->institution_id;
    }
    
    /**
     * Get all availible certificates of current institution
     * @return array of certificates objects 
     */
    public function getCertificates($paginator = ''){
        global $USER;
        $order_param    = orderPaginator($paginator); 
        $certificates   = array();                      //Array of certificates
        $db             = DB::prepare('SELECT ce.*, us.username, ins.institution FROM certificate AS ce, users AS us, institution AS ins 
                           WHERE institution_id = ANY (SELECT institution_id FROM institution_enrolments WHERE us.id = user_id AND institution_id = ins.id AND user_id = ?) 
                           AND us.id = ce.creator_id AND ins.id = institution_id '.$order_param);
        $db->execute(array($USER->id));
        
        while($result = $db->fetchObject()) { 
                $this->id              = $result->id;
                $this->certificate     = $result->certificate;
                $this->description     = $result->description;
                $this->template        = $result->template;
                $this->creation_time   = $result->creation_time;
                $this->creator_id      = $result->creator_id;
                $this->creator         = $result->username;
                $this->institution_id  = $result->institution_id;
                $this->institution     = $result->institution;
                
                $certificates[] = clone $this;        //it has to be clone, to get the object and not the reference       
        } 
        
        return $certificates;
    }
    
    /**
    * function used during the install process to set up creator id to new admin
    * @return boolean
    */
    public function dedicate(){ // only use during install
        $db = DB::prepare('UPDATE certificate SET institution_id = ?, creator_id = ?');        
        return $db->execute(array($this->institution_id, $this->creator_id));
    }
}