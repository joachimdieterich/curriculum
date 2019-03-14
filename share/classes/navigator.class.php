<?php
/**
* This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename navigator.class.php
* @copyright 2018 joachimdieterich
* @author joachimdieterich
* @date 2018.02.07 10:41
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

class Navigator {
    public $id;
    public $na_id;
    public $na_title;
    public $na_context_id;
    public $na_reference_id;
    public $na_creation_time;
    public $na_creator_id;
    public $na_file_id; 
    
    public function __construct() {
       
    }
      
    public function get(){
        $order_param = orderPaginator('navigatorP', 
                                        array('na_id'         => 'na',
                                              'na_title'      => 'na'), 
                                        'na_id');  //3.parameter cause    
        $navigators      = array();
        $db = DB::prepare('SELECT SQL_CALC_FOUND_ROWS na.* FROM navigator AS na
                                WHERE na.na_context_id = ? '.$order_param);
        $db->execute(array($_SESSION['CONTEXT']['institution']->context_id));
     
        while($result = $db->fetchObject()) { 
            $this->id        = $result->na_id;
            $this->na_id        = $result->na_id;
            $this->na_title     = $result->na_title;
            $this->na_reference_id = $result->na_reference_id;
            $navigators[]       = clone $this; 
        }
        
        if ($paginator != ''){ 
             set_item_total($paginator); //set item total based on FOUND ROWS()
        }
        
        return $navigators;
    }
      
    public function getNavigatorByInstitution($institution_id) {
        $db = DB::prepare('SELECT na.* FROM navigator AS na WHERE na.na_context_id = ? AND na.na_reference_id = ?');
        $db->execute(array($_SESSION['CONTEXT']['institution']->context_id, $institution_id));
        
        $result = $db->fetchObject();
        if ($result){
            foreach ($result as $key => $value) {
                $this->$key = $value; 
            }
            return true;                                                        
        } else { 
            return false; 
        }
    }
    
}