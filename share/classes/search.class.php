<?php
/**
* This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename search.class.php
* @copyright 2016 joachimdieterich
* @author joachimdieterich
* @date 2016.0.14 11:05
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

class Search {
    public $id;
    public $search;
    
    public function view(){
        global $USER;
        $db = DB::prepare('SELECT eo.id FROM enablingObjectives AS eo
                           WHERE eo.curriculum_id = ? AND (eo.enabling_objective LIKE ? OR eo.description LIKE ?)');
        $db->execute(array($this->id, '%'.$this->search.'%','%'.$this->search.'%'));
        
        $r  = array();
        while($result = $db->fetchObject()) { 
                $e     = new EnablingObjective();
                $e->id = $result->id;
                $e->load(); 
                $r[]   = clone $e;
        } 
        
        $db1 = DB::prepare('SELECT id FROM terminalObjectives 
                           WHERE curriculum_id = ? AND (terminal_objective LIKE ? OR description LIKE ?)');
        $db1->execute(array($this->id, '%'.$this->search.'%','%'.$this->search.'%'));
        
        while($result = $db1->fetchObject()) { 
                $t     = new TerminalObjective();
                $t->id = $result->id;
                $t->load(); 
                $r[]   = clone $t;
        } 
        
        

        return $r;     
    }
}