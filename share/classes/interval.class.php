<?php
/**
* This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename interval.class.php
* @copyright 2016 joachimdieterich
* @author joachimdieterich
* @date 2016.04.18 11:05
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

class Interval {
    /**
     * ID of interval
     * @var int
     */
    public $id; 
    /**
     * interval in days
     * @var int
     */
    public $repeat_interval; 
    /**
     * Description of interval
     * @var string
     */
    public $description; 
    
    public function __construct($id = '') {
        if ($id != ''){
            $this->id = $id;
            $this->load();
        }
    }
    
    /**
     * Load semester 
     */
    public function load(){
        $db = DB::prepare('SELECT * FROM repeat_interval WHERE id = ?');
        $db->execute(array($this->id));
        $result                  = $db->fetchObject();
        $this->id                = $result->id;
        $this->repeat_interval   = $result->repeat_interval;
        $this->description       = $result->description;
    }
    
    /**
     * 
     * @global object $USER
     * @return \Interval
     */
    public function getIntervals(){
        global $USER;
       
        $intervals = array();
        $db        = DB::prepare('SELECT * FROM repeat_interval');
        $db->execute();
        while($result = $db->fetchObject()) { 
                $this->id                  = $result->id;
                $this->repeat_interval     = $result->repeat_interval;
                $this->description         = $result->description;
                $intervals[]               = clone $this;
        } 
        return $intervals;     
    }
      
}