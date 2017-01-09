<?php
/**
* This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename wallet.class.php
* @copyright 2016 joachimdieterich
* @author joachimdieterich
* @date 2016.12.28 05:27
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

class Wallet {
    public $id;
    public $title;
    public $description;
    public $file_id;
    /*event*/
    public $timestart;
    public $timeend;
    public $timerange;
    
    public $course_id;
    public $user_list_id;
    public $subject_id;
    public $content;
    public $objectives;
    
    public function __construct($id = null) {
        if ($id != null){ 
            $this->id = $id; 
            $this->load();
        }
    }
    
    public function load($id = null){
        global $USER;
        if ($id == null){ $id = $this->id; }
        $db     = DB::prepare('SELECT wa.* FROM wallet AS wa WHERE wa.id = ?');
        $db->execute(array($id));
        $result = $db->fetchObject();
        if ($result){
            foreach ($result as $key => $value) {
                $this->$key  = $value; 
            }
            $this->timerange = date('d.m.Y G:i', strtotime($this->timestart)) .' - '. date('d.m.Y G:i', strtotime($result->timeend));
            $this->objectives = $this->getObjectives();
            return true;                                                        
        } else { 
            return false; 
        }
        
    }
    
    public function add(){
        global $USER;
        checkCapabilities('wallet:add', $USER->role_id);
        list ($this->timestart, $this->timeend) = explode(' - ',$this->timerange); // copy timestart and timeend from timerage
        $this->timestart = date('Y-m-d G:i:s', strtotime($this->timestart));
        $this->timeend   = date('Y-m-d G:i:s', strtotime($this->timeend));
        $db = DB::prepare('INSERT INTO wallet (title,description,file_id, course_id, user_list_id, subject_id, timestart, timeend) VALUES (?,?,?,?,?,?,?,?)');
        if ($db->execute(array($this->title, $this->description, $this->file_id, $this->course_id, $this->user_list_id, $this->subject_id,$this->timestart, $this->timeend))){
            $this->id = DB::lastInsertId();  
            /* Remove all old objectives from wallet bevore adding new to prevent ambigious results*/
            $db_del = DB::prepare('DELETE FROM wallet_objectives  
                                                   WHERE wallet_id = ? 
                                                   AND context_id = (SELECT context_id FROM context WHERE context = ?)');
            $db_del->execute(array($this->id, 'enabling_objective'));
            foreach ($this->objectives as $obj) {
                $this->addObjectives($obj);
            }
            return true;
        }
        
    }
    
    public function addObjectives($objective_id){
        global $USER;
        $c  = new Context();
        $c->resolve('context', 'enabling_objective');
        $db_obj = DB::prepare('INSERT INTO wallet_objectives(wallet_id, context_id, reference_id,creator_id) VALUES (?,?,?,?)');
        return $db_obj->execute(array($this->id, $c->context_id, $objective_id, $USER->id));
        
    }
    public function getObjectives(){
        $db = DB::prepare('SELECT wo.reference_id FROM wallet_objectives AS wo, context AS co 
                                               WHERE wo.wallet_id = ? 
                                               AND wo.context_id = co.context_id 
                                               AND co.context = ?');
        $db->execute(array($this->id, 'enabling_objective'));
        $objectives = array();
        while($result = $db->fetchObject()) { 
            $objectives[]  = $result->reference_id; 
        }
        
        return $objectives;
    }
    public function removeObjective($objective){
        $db = DB::prepare('SELECT COUNT(wo.id) FROM wallet_objectives AS wo, context AS co 
                                               WHERE wo.reference_id = ? 
                                               AND wo.wallet_id = ? 
                                               AND wo.context_id = co.context_id 
                                               AND co.context = ?');
        $db->execute(array($objective->id, $this->id, 'enabling_objective'));
        $c  = new Context();
        $c->resolve('context', 'enabling_objective');
        if($db->fetchColumn() >= 1) {
            $db = DB::prepare('DELETE FROM wallet_objectives WHERE reference_id = ? AND wallet_id = ? AND context_id = ?');
            return $db->execute(array($objective->id, $this->id, $c->context_id));
        } 
    }
    
    public function update(){
        global $USER;
        checkCapabilities('wallet:update', $USER->role_id);
        list ($this->timestart, $this->timeend) = explode(' - ',$this->timerange); // copy timestart and timeend from timerage
        $this->timestart = date('Y-m-d G:i:s', strtotime($this->timestart));
        $this->timeend   = date('Y-m-d G:i:s', strtotime($this->timeend));
        $db = DB::prepare('UPDATE wallet SET title = ?,description = ?,file_id = ?, course_id = ?, user_list_id = ?, subject_id = ?, timestart = ?, timeend = ? WHERE id = ?');
        if ($db->execute(array($this->title, $this->description, $this->file_id, $this->course_id, $this->user_list_id, $this->subject_id, $this->timestart, $this->timeend, $this->id))){
            /* Remove all old objectives from wallet bevore adding new to prevent ambigious results*/
            $db_del = DB::prepare('DELETE FROM wallet_objectives  
                                                   WHERE wallet_id = ? 
                                                   AND context_id = (SELECT context_id FROM context WHERE context = ?)');
            $db_del->execute(array($this->id, 'enabling_objective'));
            foreach ($this->objectives as $obj) {
                $this->addObjectives($obj);
            }
            return true;
        }
    }
    
    public function delete(){
        global $USER;
        checkCapabilities('wallet:delete', $USER->role_id);
        $db = DB::prepare('DELETE FROM wallet WHERE id = ?');
        return $db->execute(array($this->id));
    }
    
    public function get($dependency, $id = false){
        switch ($dependency) {
            case 'id':      $db = DB::prepare('SELECT wa.id FROM wallet AS wa WHERE wa.id = ?');
                                $db->execute(array($id));
                break;
            case 'user':    $db = DB::prepare('SELECT wa.id FROM wallet AS wa WHERE wa.id = ?');
                            $db->execute(array($this->id));
                            $content = new WalletContent();
                            $content->wallet_id = $this->id;
                            $this->content = $content->get('user', $id);
                break;
            case 'search':  if ($id){
                                $db = DB::prepare('SELECT wa.id FROM wallet AS wa 
                                                                WHERE wa.title LIKE ? OR wa.description LIKE ? 
                                                                ORDER BY wa.title');
                                $db->execute(array('%'.$id.'%', '%'.$id.'%'));
                            } else {
                                $db = DB::prepare('SELECT wa.id FROM wallet AS wa ORDER BY wa.title');
                                $db->execute();
                            }
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