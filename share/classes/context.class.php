<?php
/**
* Context class
* 
* @abstract This file is part of curriculum - http://www.joachimdieterich.de
* @package core
* @filename context.class.php
* @copyright 2016 Joachim Dieterich
* @author Joachim Dieterich
* @date 2016.04.08 12:53:00
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
class Context {
    /**
     * ID 
     * @var int
     */
    public $id;
    /**
     * context 
     * @var string
     */
    public $context; 
    public $context_id; 
    
    public $description; 
    public $path;
    
    
    public function get($depedency = 'file_context', $id = NULL){
        global $USER;
        $context = array();
        switch ($depedency) {
            case 'file_context':    if ($id != NULL){
                                        $db = DB::prepare('SELECT * FROM file_context WHERE id = ?');
                                        $db->execute(array($id));
                                    } else {
                                        $db = DB::prepare('SELECT * FROM file_context');
                                        $db->execute();   
                                    } 
                                    while($result = $db->fetchObject()) {
                                        if (checkCapabilities('file:uploadContext'.ucfirst($result->context), $USER->role_id, false) OR $result->context == 'user'){
                                            $this->id          = $result->id;
                                            $this->context     = $result->context;
                                            $this->description = $result->description;
                                            $context[$result->context]         = clone $this;
                                        }
                                    }
                break;
            case 'context':         $db = DB::prepare('SELECT * FROM context');
                                    $db->execute();   
                                    while($result = $db->fetchObject()) {
                                            $this->id            = $result->id;
                                            $this->context       = $result->context;
                                            $this->context_id    = $result->context_id;
                                            $this->path          = $result->path;
                                            $context[$result->context] = clone $this; 
                                            $context[$result->context_id]      = clone $this; // double saved to be able to resolve by context and id
                                    }             
                break;

            default:
                break;
        }
           
        return $context;
        
    }
    
   
    
    public function resolve($dependency = 'context', $id){
        $db = DB::prepare('SELECT * FROM context WHERE '.$dependency.' = ?');
        $db->execute(array($id));
        $result = $db->fetchObject();
        if ($result){
            $this->id            = $result->id;
            $this->context       = $result->context;
            $this->context_id    = $result->context_id;
            $this->path          = $result->path;
            return true;
        } else {
            return false;
        }
    }
    

}