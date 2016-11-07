<?php
/**
* Statistics
* 
* @abstract This file is part of curriculum - http://www.joachimdieterich.de
* @package curriculum
* @filename statistic.class.php
* @copyright 2015 Joachim Dieterich
* @author Joachim Dieterich
* @date 2015.12.11 19:57
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
class Statistic {
    
    /**
     * add cronjob log into db
     * @param string $cronjob
     * @param int $user_id
     * @param string $log
     * @return boolean 
     */
    public function getAccomplishedObjectives($dependency = 'all'){
        switch ($dependency) {
            case 'all':     $db = DB::prepare('SELECT count(id) as max FROM user_accomplished WHERE context_id = 12 AND (status_id = 1 OR status_id = 2)');
                            $db->execute();
                            $result = $db->fetchObject();
                            return $result->max;
                break;
            case 'today':   $db = DB::prepare('SELECT count(id) as max FROM user_accomplished WHERE DATE(accomplished_time) = CURDATE() AND context_id = 12 AND (status_id = 1 OR status_id = 2)');
                            $db->execute();
                            $result = $db->fetchObject();
                            return $result->max;
                break;

            default:
                break;
        }
        
    } 
    
    public function getUsersOnline($dependency = 'today') {
        switch ($dependency) {
            case 'today': $db = DB::prepare('SELECT count(id) as max FROM users WHERE DATE(last_login) = CURDATE()');
                            $db->execute();
                            $result = $db->fetchObject();
                            return $result->max;
                break;
            case 'all':
                break;

            default:
                break;
        }
    }
    
    public function map($modus = 'institutions'){
        global $CFG;
        switch ($modus){
            case 'institutions': $ins          = new Institution();
                                 $institutions = $ins->getInstitutions('all');
                                 $data = array();
                                 $node_l0 = new Node();
                                 $node_l1 = new Node();
                                 
                                 $size_l0 = 10000;
                                 $size_l1 = 20000;
                                 $size_l2 = 5000;
                                 //topLevel
                                 $node_l0->name        = $CFG->app_title;
                                 $node_l0->parentName  = 'A';
                                 $node_l0->size        = $size_l0;
                                 $node_l0->link        = ''; 
                                 
                                 foreach ($institutions as $value) {
                                     $node_l1->name       = $value->institution;
                                     $node_l1->parentName = 'A';//$value->id; // colerfuler than right value 'A'
                                     $node_l1->size       = 50000;
                                     $s1                  = $size_l1;
                                     $n1                  = $value->institution;
                                     $gr                  = new Group();
                                     $group = $gr->getGroups('institution', $value->id);
                                     $groups = array();
                                     foreach ($group as $value) {
                                        $node_l2             = new Node(); 
                                        $node_l2->name       = $value->group;
                                        $node_l2->parentName = $n1;
                                        $users      = new User();
                                        $u_list     = $users->getGroupMembers('group', $value->id);
                                        if ($u_list != false){
                                               $node_l2->size    = $size_l2+(200*count($u_list));
                                        } else {
                                            $node_l2->size       = $size_l2;
                                        }
                                        $s1                  = $s1 + 5000;
                                        $groups[]            = $node_l2;
                                     }
                                     $node_l1->children = $groups;
                                     $node_l1->size       = $s1;
                                     $size_l0             = $size_l0 + 1000;
                                     $node_l0->children[] = clone $node_l1;
                                 }
                                $node_l0->size = $size_l0;
                                
                                return json_encode($node_l0);   
                break;
            default:
                break;
        }
        
    }
 
}