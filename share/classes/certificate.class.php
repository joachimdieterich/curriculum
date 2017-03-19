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
* @license: 
*
* The MIT License (MIT)
* Copyright (c) 2012 Joachim Dieterich http://www.curriculumonline.de
* 
* Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), 
* to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, 
* and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
* 
* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
* 
* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF 
* MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, 
* DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR 
* THE USE OR OTHER DEALINGS IN THE SOFTWARE.
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
     * id of User who created this certificate-template
     * @var int
     */
    public $creator_id; 
    public $creator;
    /**
     * id of institution to which certificate-template belongs to
     * @var int
     */
    public $institution_id; 
    public $institution;
    /**
     * id of curriculum to wich certificate-template belongs to
     * @var int 
     */
    public $curriculum_id;
    public $curriculum;
   
    
    /**
     * add certificate
     * @return mixed 
     */
    public function add(){
        global $USER;
        checkCapabilities('certificate:add', $USER->role_id);
        $db = DB::prepare('INSERT INTO certificate (certificate,description,template,creator_id,institution_id,curriculum_id) VALUES (?,?,?,?,?,?)');
        return $db->execute(array($this->certificate, $this->description, $this->template, $USER->id, $this->institution_id, $this->curriculum_id));
    }
    
    /**
     * Update certificate
     * @return boolean 
     */
    public function update(){
        global $USER;
        checkCapabilities('certificate:update', $USER->role_id);  
        $db = DB::prepare('UPDATE certificate SET certificate = ?, description = ?, template = ?, institution_id = ?, curriculum_id = ? WHERE id = ?');
        return $db->execute(array($this->certificate, $this->description, $this->template, $this->institution_id, $this->curriculum_id, $this->id));
    }
    
    /**
     * delete certificate
     * @global object $USER
     * @param int $creator_id
     * @return boolean 
     */
    public function delete(){
        global $USER, $LOG;
        checkCapabilities('certificate:delete', $USER->role_id);
        $this->load();
        $LOG->add($USER->id, 'certificate.class.php', dirname(__FILE__), 'Delete certificate: '.$this->certificate.', curriculum_id: '.$this->curriculum_id.' institution_id: '.$this->institution_id);
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
        $this->curriculum_id     = $result->curriculum_id;
    }
    
    /**
     * Get all availible certificates of current institution
     * @return array of certificates objects 
     */
    public function getCertificates($paginator = ''){
        global $USER;
        $order_param    = orderPaginator($paginator,array('certificate' => 'ce',
                                                          'description' => 'ce',
                                                          'template'    => 'ce',
                                                          'creation_time' => 'ce',
                                                          'username'    => 'us',
                                                          'institution' => 'ins')); 
        $certificates   = array();                      //Array of certificates
        if (isset($this->curriculum_id)){
            $db             = DB::prepare('SELECT ce.*, us.username, ins.institution FROM certificate AS ce, users AS us, institution AS ins
                               WHERE ce.institution_id = ANY (SELECT institution_id FROM institution_enrolments WHERE us.id = user_id AND institution_id = ins.id AND user_id = ?) 
                               AND ins.id = ce.institution_id 
                               AND (ce.curriculum_id = ? OR ce.curriculum_id = 0) '.$order_param);
            $db->execute(array($USER->id, $this->curriculum_id));
        } else {
            $db             = DB::prepare('SELECT ce.*, us.username, ins.institution FROM certificate AS ce, users AS us, institution AS ins
                               WHERE ce.institution_id = ANY (SELECT institution_id FROM institution_enrolments WHERE us.id = user_id AND institution_id = ins.id AND user_id = ?) 
                               AND ins.id = ce.institution_id '.$order_param);
            $db->execute(array($USER->id));
        }
        
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
                /*$this->curriculum_id   = $result->curriculum_id;
                $this->curriculum      = $result->curriculum; */
                
                $certificates[] = clone $this;        //it has to be clone, to get the object and not the reference       
        } 
        
        return $certificates;
    }
    
    /**
    * function used during the install process to set up creator id to new admin
    * @return boolean
    */
    public function dedicate(){ // only use during install
        $db = DB::prepare('UPDATE certificate SET creator_id = ?');        
        return $db->execute(array($this->creator_id));
    }
}