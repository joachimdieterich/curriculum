<?php
/**
* Quote Class
* 
* @abstract This file is part of curriculum - http://www.joachimdieterich.de
* @package core
* @filename quote.class.php
* @copyright 2018 Joachim Dieterich
* @author Joachim Dieterich
* @date 2018.06.04 21:58
* @license: 
*
* The MIT License (MIT)
* Copyright (c) 2018 Joachim Dieterich http://www.curriculumonline.de
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
class Quote {

    public $id;
    public $context_id; 
    public $reference_id; 
    public $reference_title;
    public $reference_object;   
    public $quote; 
    public $quote_link;
    public $creation_time; 
    public $creator_id; 
    public $creator; 
   
    public function load($id = null){
        if ($id == null){ $id = $this->id; }
        $db = DB::prepare('SELECT qu.* FROM quote AS qu WHERE qu.id = ?');
        $db->execute(array($id));
        $result     = $db->fetchObject();
        $user       = new User();
        
        if ($result){
            $this->id            = $result->id;
            $this->context_id    = $result->context_id;
            $this->reference_id  = $result->reference_id;
            $this->creation_time = $result->creation_time;
            $this->creator_id    = $result->creator_id;
            $this->creator       = $user->resolveUserId($result->creator_id);
            $this->quote         = $this->getQuote($_SESSION['CONTEXT'][$this->context_id]->context);
            return true;                                                        
        } else { 
            return false; 
        }
    }
    
    public function get($dependency, $reference_id){
       
        switch ($dependency) {
            /*case 2:
                    $db = DB::prepare('SELECT qu.* FROM quote AS qu, content_subscriptions AS cts 
                                        WHERE qu.reference_id = cts.content_id AND cts.context_id = ? AND cts.reference_id = ?');
                    $db->execute(array($dependency, $reference_id));
                break;*/


            default:    $db = DB::prepare('SELECT qu.* FROM quote AS qu, quote_subscriptions AS qus 
                                        WHERE qu.id = qus.quote_id AND qus.context_id = ? AND qus.reference_id = ?');
                        $db->execute(array($dependency, $reference_id));
                break;
        }
        $user       = new User();
        $entrys     = array();
        
        while($result = $db->fetchObject()) { 
            $this->id            = $result->id;
            $this->context_id    = $result->context_id;
            $this->reference_id  = $result->reference_id;
            if ($this->context_id == 15){ //content subscribed in a curriculum context // other contextes are not available yet
                $db1 = DB::prepare('SELECT cu.* FROM curriculum AS cu, content_subscriptions AS cts 
                                        WHERE cu.id = cts.reference_id AND cts.context_id = ? AND cts.content_id = ?');
                $db1->execute(array(2, $this->reference_id)); //2 --> curriculum
                $cur_result     = $db1->fetchObject();
                if ($cur_result){
                    $this->reference_object = $cur_result;
                }
            }
            
            $this->creation_time = $result->creation_time;
            $this->creator_id    = $result->creator_id;
            $this->creator       = $user->resolveUserId($result->creator_id);
            $this->quote         = $this->getQuote($_SESSION['CONTEXT'][$this->context_id]->context);
            $entrys[]            = clone $this;        //it has to be clone, to get the object and not the reference
        } 
        return $entrys;
        
    }
            
    public function getQuote($dependency = 'content'){
        
        switch ($dependency) {
            case 'content': $content = new Content();
                            $content->load('id', $this->reference_id);
                            $regex   = '#\<quote id="'.$this->id.'"\>(.+?)\<\/quote\>#s';
                            preg_match($regex, $content->content, $matches);
                            //$matches[0] == with quote tag
                            //$matches[1] == quote only
                            $this->reference_title  = $content->title;
                            $this->quote_link       = $content->id;
                            return  $matches[1];
                break;

            default:
                break;
        } 
    }
    
    
}