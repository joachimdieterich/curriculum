<?php
/**
* This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename walletcontent.class.php
* @copyright 2016 joachimdieterich
* @author joachimdieterich
* @date 2016.12.28 09:27
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

class WalletContent {
    public $id;
    public $title;
    public $wallet_id;
    public $user_id;
    public $context_id;
    public $context;
    public $reference_id;
    public $width_class;
    public $position;
    public $order_id;
    public $creation_time;
    
    public function __construct($id = null) {
        if ($id != null){ 
            $this->id = $id; 
            $this->load();
        }
    }
    
    public function add(){
        global $USER;
        checkCapabilities('wallet:workon', $USER->role_id);
        $db = DB::prepare('INSERT INTO wallet_content (wallet_id,title,user_id,context_id, reference_id, width_class, position, order_id) VALUES (?,?,?,?,?,?,?,?)');
        return $db->execute(array($this->wallet_id, $this->title, $USER->id, $this->context_id, $this->reference_id, $this->width_class, $this->position, $this->order_id));
    }
    
    public function update(){
        global $USER;
        checkCapabilities('wallet:workon', $USER->role_id);
        $db = DB::prepare('UPDATE wallet_content SET wallet_id = ?, title = ?, user_id = ?,context_id = ?, reference_id = ?, width_class = ?, position = ?, order_id = ? WHERE id = ?');
        return $db->execute(array($this->wallet_id, $this->title, $USER->id, $this->context_id, $this->reference_id, $this->width_class, $this->position, $this->order_id, $this->id));
    }
    
    public function delete(){
        global $USER;
        checkCapabilities('wallet:workon', $USER->role_id);
        $db = DB::prepare('DELETE FROM wallet_content WHERE id = ?');
        return $db->execute(array($this->id));
    }
    
    public function load($id = null){
        global $USER;
        if ($id == null){ $id = $this->id; }
        $db     = DB::prepare('SELECT wc.* FROM wallet_content AS wc 
                                       WHERE wc.id = ? ORDER BY order_id');
        $db->execute(array($id));
        $result = $db->fetchObject();
        if ($result){
            foreach ($result as $key => $value) {
                $this->$key = $value; 
            }
            $ct     = new Context();
            $ct->resolve('context_id', $this->context_id);
            $this->context = $ct->context;
                    
            return true;                                                        
        } else { 
            return false; 
        }   
    }
    
    public function get($dependency, $id = false){
        switch ($dependency) {
            case 'wallet_id':   $db = DB::prepare('SELECT wc.id FROM wallet_content AS wc WHERE wc.wallet_id = ?');
                                $db->execute(array($id));
                break;
            case 'user':        $db = DB::prepare('SELECT wc.id FROM wallet_content AS wc WHERE wc.wallet_id = ? AND wc.user_id = ? ORDER BY wc.order_id');
                                $db->execute(array($this->wallet_id, $id));
                break;
            default:
                break;
        }
        
        $r  = array();
        while($result = $db->fetchObject()) { 
            $this->load($result->id); 
            $r[]  = clone $this;
        } 

        return $r;     
    }
}