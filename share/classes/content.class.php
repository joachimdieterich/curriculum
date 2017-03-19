<?php
/**
* Content Class
* 
* @abstract This file is part of curriculum - http://www.joachimdieterich.de
* @package core
* @filename content.class.php
* @copyright 2016 Joachim Dieterich
* @author Joachim Dieterich
* @date 2016.11.17 12:49
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
class Content {

    public $id;
    public $title; 
    public $content; 
    public $timecreated; 
    public $timemodified; 
    public $creator_id; 
    public $creator; 
    // vars context_subscrition: 
    public $context_id;
    public $file_context;
    public $reference_id;
    public $sub_timecreated;
    public $sub_timemodified;
    public $status;
    public $sub_creator_id;
    public $sub_creator;
   
    public function add($subscribe = true){
        global $USER;
        $db = DB::prepare('INSERT INTO content (title,content,timecreated,creator_id) VALUES (?,?,NOW(),?)');
        if($db->execute(array($this->title, $this->content, $USER->id))){
            $this->id = DB::lastInsertId(); 
            if ($subscribe) { $this->addSubscription(); }
            return $this->id;
        } else {
            return false;
        }
    }
    
    public function update(){
        global $USER;
        $db = DB::prepare('UPDATE content SET title = ?, content = ? WHERE id = ?');
        return $db->execute(array($this->title, $this->content, $this->id));
    }
    
    public function delete(){
        global $USER, $LOG;
        checkCapabilities('content:delete', $USER->role_id);
        $this->load();
        $LOG->add($USER->id, 'content.class.php', dirname(__FILE__), 'Delete content: '.$this->title.', creator_id: '.$this->creator_id);
        $db = DB::prepare('DELETE FROM content WHERE id = ?');
        return $db->execute(array($this->id));
    } 
    
    public function load($dependency = 'id', $value = null){
        if (isset($value)){ $v = $value; } else { $v = $this->id; }
        $db = DB::prepare('SELECT ct.* FROM content AS ct WHERE ct.'.$dependency.' = ?');
        $db->execute(array($v));
        $result     = $db->fetchObject();
        $user       = new User();
        
        if ($result){
            $this->id            = $result->id;
            $this->title         = $result->title;
            $this->content       = $result->content;
            $this->timecreated   = $result->timecreated;
            $this->timemodified  = $result->timemodified;
            $this->creator_id    = $result->creator_id;
            $this->creator       = $user->resolveUserId($result->creator_id);
            return true;                                                        
        } else { 
            return false; 
        }
    }
       
    public function get($dependency = 'curriculum', $id = null){
        $entrys = array();                      //Array of content
        
        switch ($dependency) {
            case 'curriculum':  $db = DB::prepare('SELECT ct.id FROM content AS ct, content_subscriptions AS cts, context AS co
                                                        WHERE  co.context = "curriculum" 
                                                        AND co.context_id = cts.context_id
                                                        AND cts.reference_id = ?
                                                        AND cts.content_id = ct.id');
                                $db->execute(array($id));
                break;
            
            case 'terms':       $db = DB::prepare('SELECT ct.id FROM content AS ct, content_subscriptions AS cts, context AS co
                                                        WHERE  co.context = "terms"
                                                        AND co.context_id = cts.context_id
                                                        AND cts.content_id = ct.id');
                                $db->execute();
                break;
            case 'signature':   $db = DB::prepare('SELECT ct.id FROM content AS ct, content_subscriptions AS cts, context AS co
                                                        WHERE  co.context = "'.$dependency.'"
                                                        AND co.context_id = cts.context_id
                                                        AND cts.reference_id = ?
                                                        AND cts.content_id = ct.id');
                                $db->execute(array($id));
                break;
            
            default:
                break;
        }
        
        while($result = $db->fetchObject()) { 
            $this->id            = $result->id;
            $this->load();
            $entrys[]            = clone $this;        //it has to be clone, to get the object and not the reference
        } 
        return $entrys;
    }
    
    public function addSubscription(){
        global $USER;
        checkCapabilities('content:add', $USER->role_id);
        $db = DB::prepare('INSERT INTO content_subscriptions (content_id,context_id,file_context,reference_id,timecreated,status,creator_id) VALUES (?,?,?,?,NOW(),?,?)');
        return $db->execute(array($this->id, $this->context_id, $this->file_context, $this->reference_id,1,$USER->id));
    }
    
    /**
    * function used during the install process to set up creator id to new admin
    * @return boolean
    */
    public function dedicate(){ // only use during install
        $db = DB::prepare('UPDATE content SET  creator_id = ?');        
        $db->execute(array($this->creator_id));
        $db1 = DB::prepare('UPDATE content_subscriptions SET  creator_id = ?');        
        return $db1->execute(array($this->creator_id));
    }
}