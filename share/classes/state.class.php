<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename state.class.php 
* @copyright 2013 Joachim Dieterich
* @author Joachim Dieterich
* @date 2013.03.24 11:36
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
class State extends Country { 
    /**
     * states id
     * @var id 
     */
    public $id; 
    /**
     * state name
     * @var string
     */
    public $state; 
    /**
     * country code
     * @var string
     */
    public $country_code; 
       
    /**
     * get states depending on country code
     * @param string $dependency
     * @param int $id
     * @return array of objects|boolean 
     */
    public function getStates($dependency = null, $id = null) {
        switch ($dependency) {
            case null:      $db = DB::prepare('SELECT * FROM state WHERE country_code = ? ORDER BY state ASC');
                            $db->execute(array($this->code)); 
                break;
            
            case 'profile': $db = DB::prepare('SELECT sta.* FROM state AS sta, countries AS cou 
                                            WHERE sta.country_code = cou.code AND cou.id = ? ORDER BY sta.state ASC');
                            $db->execute(array($id));
                break;
            default: break;
        }
       
        while($result = $db->fetchObject()) { 
            $this->id            = $result->id;
            $this->state         = $result->state;
            $this->country_code  = $result->country_code;
            $states[]            = clone $this; 
        } 
        if (isset($states)){
            return $states;
        } else {
            $this->id            = '1';
            $this->state         = '---';
            $this->country_code  = $this->code;
            $states[]            = clone $this;
            return  $states; 
        }
    }
    
    /**
     * class constructor
     * @param int $country_id , default null
     */
    public function __construct($country_id = null){
        if ($country_id != null){
            parent::load($country_id);
        }
    }
}