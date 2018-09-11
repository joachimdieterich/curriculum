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
        global $USER;
        switch ($dependency) {
            case 'all':     $db = DB::prepare('SELECT count(id) as max FROM user_accomplished WHERE context_id = 12 AND (status_id = "x1" OR status_id = "x2" 
                                                                                                                      OR status_id = "11" OR status_id = "12"
                                                                                                                      OR status_id = "21" OR status_id = "22"
                                                                                                                      OR status_id = "31" OR status_id = "32")');
                            $db->execute();
                            $result = $db->fetchObject();
                            return $result->max;
                break;
            case 'today':   $db = DB::prepare('SELECT count(id) as max FROM user_accomplished WHERE DATE(accomplished_time) = CURDATE() AND context_id = 12 AND (status_id = "x1" OR status_id = "x2" 
                                                                                                                      OR status_id = "11" OR status_id = "12"
                                                                                                                      OR status_id = "21" OR status_id = "22"
                                                                                                                      OR status_id = "31" OR status_id = "32")');
                            $db->execute();
                            $result = $db->fetchObject();
                            return $result->max;
                break;
            case 'user_all':   $db = DB::prepare('SELECT count(id) as max FROM user_accomplished WHERE context_id = 12 AND user_id = ? 
                                                                                                                   AND (status_id = "x1" OR status_id = "x2" 
                                                                                                                      OR status_id = "11" OR status_id = "12"
                                                                                                                      OR status_id = "21" OR status_id = "22"
                                                                                                                      OR status_id = "31" OR status_id = "32")');
                            $db->execute(array($USER->id));
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
   
    private function generateCSV($line_array){
        global $CFG;
        $file = fopen($CFG->curriculumdata_root.'temp/chart.csv','w');

        foreach ($line_array as $line) {
            fputcsv($file, $line);
        }
        fclose($file);
        return $CFG->access_file_url.'temp/chart.csv';
    }


    public function getAccomplishedPerDays($status = ''){
        switch ($status) {
            case '':     $db = DB::prepare('SELECT COUNT(accomplished_time) AS max, DATE(accomplished_time) AS date
                                            FROM user_accomplished 
                                            GROUP BY DATE(accomplished_time)');
                            $db->execute();
                break;
            default:        $db = DB::prepare('SELECT COUNT(accomplished_time) AS max, DATE(accomplished_time) AS date
                                            FROM user_accomplished WHERE status_id IN ('.$status.')
                                            GROUP BY DATE(accomplished_time)');
                            $db->execute();
                break;
        }
        
        $line_array[] = array('total','date');
        while($result = $db->fetchObject()) { 
            $line_array[] =  array($result->max, $result->date);
        }
        return $this->generateCSV($line_array);
    }
    
 
    
    public function getCreationStatistic($dependency = 'log'){
        $db = DB::prepare('SELECT COUNT(id) AS max, DATE(creation_time) AS date
                            FROM '.$dependency.'
                            GROUP BY DATE(creation_time)');
        $db->execute();
        $line_array[] = array('total','date');

        while($result = $db->fetchObject()) { 
            $line_array[] =  array($result->max, $result->date);
        }
        return $this->generateCSV($line_array);
    }
    
    public function acceptTerms($dependency = 'accept_terms'){
        $db = DB::prepare('SELECT COUNT(id) AS max, DATE(timestamp) AS date
                            FROM '.$dependency.'
                            GROUP BY DATE(timestamp)');
        $db->execute();
        $line_array[] = array('total','date');

        while($result = $db->fetchObject()) { 
            $line_array[] =  array($result->max, $result->date);
        }
        return $this->generateCSV($line_array);
    }
    
     public function lastlogin($dependency = 'users'){
        $db = DB::prepare('SELECT COUNT(id) AS max, DATE(last_login) AS date
                            FROM '.$dependency.'  WHERE last_login <> ""
                            GROUP BY DATE(last_login)');
        $db->execute();
        $line_array[] = array('total','date');

        while($result = $db->fetchObject()) { 
            $line_array[] =  array($result->max, $result->date);
        }
        return $this->generateCSV($line_array);
    }
    
    public function map($modus = 'institutions', $dependency = ''){
        global $CFG;
        $data = array();
        $node_l0 = new Node();
        $node_l1 = new Node();

        $size_l0 = 10000;
        $size_l1 = 20000;
        $size_l2 = 5000;
        
        switch ($modus){
            case 'institutions': $ins          = new Institution();
                                 $institutions = $ins->getInstitutions('all');
                                 
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
            case 'curriculum':  $node_l3 = new Node();
                                $size_l3 = 1000;
                                $cur = new Curriculum();
                                $curriculum = $cur->getCurricula('user');
                                //topLevel
                                $node_l0->name        = $CFG->app_title;
                                $node_l0->parentName  = 'A';
                                $node_l0->size        = $size_l0;
                                $node_l0->link        = ''; 
                               
                                foreach ($curriculum as $value) {
                                    $node_l1->name          = $value->curriculum;
                                    $node_l1->parentName    = 'A';//$value->id; // colerfuler than right value 'A'
                                    $node_l1->size          = 50000;
                                    $s1                     = $size_l1;
                                    $current_curriculum     = new Curriculum;
                                    $current_curriculum->id = $value->id;
                                    $ins                    = $current_curriculum->checkEnrolment();
                                    if ($ins){
                                        $n1             = $ins[0]->institution;
                                        $current_institution = '';
                                        $institutions   = array();
                                        for($i = 0; $i < count($ins); $i++) {
                                            if ($ins[$i]->institution != $current_institution){ 
                                                $node_l2             = new Node(); 
                                                $node_l2->name       = $ins[$i]->institution;
                                                $node_l2->parentName = $n1;
                                                for($j = $i; $j < count($ins); $j++) {                                                    
                                                    if ($ins[$j]->institution == $ins[$i]->institution){ 
                                                        $node_l3             = new Node(); 
                                                        $node_l3->name       = $ins[$j]->groups;
                                                        $node_l3->parentName = $node_l2->name;
                                                        $s1                  = $s1 + 200;
                                                        $node_l3->size       = $size_l2;
                                                        $groups[]            = $node_l3; 
                                                        $count_j = $j;
                                                    } else {
                                                        $node_l2->children   = $groups;
                                                        unset($groups);
                                                        $s2                  = $s1 + 500;
                                                        $node_l2->size       = $s2;
                                                        $institutions[]      = $node_l2;
                                                        break;
                                                    }
                                                }
                                                $i = $count_j; 
                                            } 
                                            $current_institution = $ins[$i]->institution;
                                            
                                        }
                                    }
                                    if (isset($institutions)){
                                        $node_l1->children   = $institutions;
                                    }
                                    $node_l1->size       = $s1;
                                    $size_l0             = $size_l0 + 1000;
                                    $node_l0->children[] = clone $node_l1;
                                }
                                $node_l0->size = $size_l0;
                                return json_encode($node_l0);
                break;
            case 'usage':        return $this->getCreationStatistic('log');
                break;
            case 'accomplished': return $this->getAccomplishedPerDays($dependency);
                 break;           
            case 'newUsers':     return $this->getCreationStatistic('users');
                 break;           
            case 'newCurricula': return $this->getCreationStatistic('curriculum');
                 break;           
            case 'newGroups':    return $this->getCreationStatistic('groups');
                 break;           
            case 'newMessages':  return $this->getCreationStatistic('message');
                 break;           
            case 'acceptTerms':  return $this->acceptTerms();
                 break;           
            case 'lastlLogin':   return $this->lastlogin();
                 break;           
            case 'curriculumClicks':   return $this->getClicks();
                 break;           
                
            default:
                break;
        }
        
    }
    
    public static function setStatistics($context_id,$reference_id,$increment = 1){
        $db = DB::prepare('SELECT clicks FROM statistics WHERE context_id = ? AND reference_id = ?');
        $db->execute(array($context_id, $reference_id));
        $result = $db->fetchObject();
        if ($result){
            //error_log('res'.($result->clicks + $increment).' context_id:'.$context_id.' reference_id:'.$reference_id);
            $db = DB::prepare('UPDATE statistics SET clicks = ? WHERE context_id = ? AND reference_id = ?');
            return $db->execute(array(($result->clicks + $increment), $context_id, $reference_id));
        } else {
            $db = DB::prepare('INSERT INTO statistics (context_id,reference_id,clicks) VALUES (?,?,?)');
            return $db->execute(array($context_id, $reference_id, $increment));
        }
    }
    
    public static function getClicks($dependency = 'curriculum'){
        global $CFG;
        $data = array();
        $node_l0 = new Node();
        $node_l1 = new Node();

        $size_l0 = 10;
        $size_l1 = 200;
      
        $cur = new Curriculum();
        $curriculum = $cur->getCurricula('user');
        //topLevel
        $node_l0->name        = $CFG->app_title;
        $node_l0->parentName  = 'A';
        $node_l0->size        = $size_l0;
        $node_l0->link        = ''; 

        foreach ($curriculum as $value) {  
            $node_l1->name          = $value->curriculum.' ('.$value->clicks.')';
            $node_l1->parentName    = 'A';//$value->id; // colerfuler than right value 'A'
            $node_l1->size          = (500*$value->clicks);
            $s1                     = $size_l1;
            $current_curriculum     = new Curriculum;
            $current_curriculum->id = $value->id;
            
            /*$ins                    = $current_curriculum->checkEnrolment();
            if ($ins){
                $n1                 = $ins[0]->institution;
                $current_institution = '';
                $institutions   = array();
                for($i = 0; $i < count($ins); $i++) {
                    
                    if ($ins[$i]->institution != $current_institution){ 
                        $node_l2             = new Node(); 
                        $node_l2->name       = $ins[$i]->institution;
                        $node_l2->parentName = $n1;
                        for($j = $i; $j < count($ins); $j++) {                                                    
                            if ($ins[$j]->institution == $ins[$i]->institution){ 
                                $node_l3             = new Node(); 
                                $node_l3->name       = $ins[$j]->groups;
                                $node_l3->parentName = $node_l2->name;
                                $s1                  = $s1 + 200;
                                $node_l3->size       = $size_l2;
                                $groups[]            = $node_l3; 
                                $count_j = $j;
                            } else {
                                $node_l2->children   = $groups;
                                unset($groups);
                                $s2                  = $s1 + 500;
                                $node_l2->size       = $s2;
                                $institutions[]      = $node_l2;
                                break;
                            }
                        }
                        $i = $count_j; 
                    } 
                    $current_institution = $ins[$i]->institution;

                }
            }*/
            /*if (isset($institutions)){
                $node_l1->children   = $institutions;
            }
            $node_l1->size       = $s1;*/
            $size_l0             = $size_l0 + 1000;
            $node_l0->children[] = clone $node_l1;
        }
        $node_l0->size = $size_l0;
        //error_log(json_encode($node_l0));
        return json_encode($node_l0);
    }
 
}