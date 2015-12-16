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
 * @license 
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by  
 * the Free Software Foundation; either version 3 of the License, or (at your option) any later version.                                   
 *                                                                       
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of        
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details:
 *                                                                       
 * http://www.gnu.org/copyleft/gpl.html      
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
            case 'all':     $db = DB::prepare('SELECT count(id) as max FROM user_accomplished WHERE status_id = 1 OR status_id = 2');
                            $db->execute();
                            $result = $db->fetchObject();
                            return $result->max;

                break;
            case 'today':   $db = DB::prepare('SELECT count(id) as max FROM user_accomplished WHERE DATE(accomplished_time) = CURDATE() AND (status_id = 1 OR status_id = 2)');
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
 
}