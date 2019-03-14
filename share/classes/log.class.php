<?php
/**
* Log class can add, update, delete and get data from log db
* 
* @abstract This file is part of curriculum - http://www.joachimdieterich.de
* @package core
* @filename log.class.php
* @copyright 2013 Joachim Dieterich
* @author Joachim Dieterich
* @date 2013.06.11 21:00
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
class Log {
    /**
     * ID 
     * @var int
     */
    public $id;
    /**
     * Timestamp 
     * @var timestamp
     */
    public $creation_time; 
    /**
     * ID of User 
     * @var int
     */
    public $user_id; 
    /**
     * name of User 
     * @var string
     */
    public $username; 
    /**
     * ip of user
     * @var string 
     */
    public $ip;
    /**
     * action
     * @var type 
     */
    public $action; 
    /**
     * current url
     * @var string
     */
    public $url;
    /**
     * Info
     * @var string
     */
    public $info; 

    /**
     * Add Log
     * @param int $user_id
     * @param string $action
     * @param string $url
     * @param string $info
     * @return boolean 
     */
    public function add($user_id, $action, $url, $info){
        $db = DB::prepare('INSERT INTO log (creation_time,user_id,ip,action,url,info) VALUES (NOW(),?,?,?,?,?)');
        return $db->execute(array($user_id, $_SERVER['REMOTE_ADDR'], $action, $url, $info));
    }
    
    public function getLogs($paginator = '') {
        global $USER;
        checkCapabilities('log:getLogs', $USER->role_id);
        $order_param    = orderPaginator($paginator, array('id'         => 'lg',
                                                        'username'      => 'us',
                                                        'creation_time' => 'lg', 
                                                        'ip'            => 'lg', 
                                                        'action'        => 'lg', 
                                                        'url'           => 'lg', 
                                                        'info'          => 'lg')); 
        $log            = array();
        $db             = DB::prepare('SELECT SQL_CALC_FOUND_ROWS lg.*, us.username FROM log AS lg, users AS us WHERE lg.user_id = us.id '.$order_param );
        $db->execute();
        while($result = $db->fetchObject()) { 
            $this->id                = $result->id;
            $this->creation_time     = $result->creation_time;
            $this->user_id           = $result->user_id;
            $this->username          = $result->username;
            $this->ip                = $result->ip; 
            $this->action            = $result->action;
            $this->url               = $result->url;   
            $this->info              = $result->info;   
            $log[]                   = clone $this; 
        }      
        
        if ($paginator != ''){ 
             set_item_total($paginator); //set item total based on FOUND ROWS()
        }
        
        return $log;
    }

}