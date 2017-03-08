<?php
/**
* This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename walletsharing.class.php
* @copyright 2017 joachimdieterich
* @author joachimdieterich
* @date 2017.01.24 13:28
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

class WalletSharing {
    public $id;
    public $wallet_id;
    public $reference_id;
    public $context_id;
    public $context;
    public $permission;
    
    public $timestart;
    public $timeend;
    public $timerange;
    
    public $creation_time;
    public $creator_id;
    
    
    public function __construct($id = null) {
        if ($id != null){ 
            $this->id = $id; 
            $this->load();
        }
    }
    
    public function load($id = null){
        //global $USER;
        if ($id == null){ $id = $this->id; }
        $db     = DB::prepare('SELECT ws.* FROM wallet_sharing AS ws WHERE ws.id = ?');
        $db->execute(array($id));
        $result = $db->fetchObject();
        if ($result){
            foreach ($result as $key => $value) {
                $this->$key  = $value; 
            }
            $this->timerange = date('d.m.Y G:i', strtotime($this->timestart)) .' - '. date('d.m.Y G:i', strtotime($result->timeend));
            return true;                                                        
        } else { 
            return false; 
        }
        
    }
    
    public function add(){
        global $USER;
        checkCapabilities('wallet:share', $USER->role_id);
        list ($this->timestart, $this->timeend) = explode(' - ',$this->timerange); // copy timestart and timeend from timerage
        $this->timestart = date('Y-m-d G:i:s', strtotime($this->timestart));
        $this->timeend   = date('Y-m-d G:i:s', strtotime($this->timeend));
        $db = DB::prepare('INSERT INTO wallet_sharing (wallet_id,reference_id,context_id, permission, timestart, timeend, creator_id) VALUES (?,?,?,?,?,?,?)');
        if ($db->execute(array($this->wallet_id, $this->reference_id, $this->context_id, $this->permission, $this->timestart, $this->timeend, $USER->id))){
            return DB::lastInsertId();
        } else {
            return false;
        }
    }
    
    public function delete($id = null){
        if ($id == null){ $id = $this->id; }
        global $USER;
        checkCapabilities('wallet:share', $USER->role_id);
        $db = DB::prepare('DELETE FROM wallet_sharing WHERE id = ?');
        return $db->execute(array($id));
    }
    
    public function get($dependency, $id = false, $context = null){
        global $USER;
        switch ($dependency) {
            /*case 'id':      $db = DB::prepare('SELECT wa.id FROM wallet AS wa WHERE wa.id = ?');
                            $db->execute(array($id));
                break;
            case 'user':    $db = DB::prepare('SELECT wa.id FROM wallet AS wa WHERE wa.id = ? AND wa.creator_id = ?');
                            $db->execute(array($this->id, $USER->id));
                            $content = new WalletContent();
                            $content->wallet_id = $this->id;
                            $this->content = $content->get('user', $id);
                break;
            */
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