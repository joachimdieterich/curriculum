<?php
/**
 *  This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package webservice
 * @filename server.class.php - curriculum webservice server class
 * @copyright 2013 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2013.04.03 10:36
 * @license: 
 *
 * This program is free software; you can redistribute it and/or modify 
 * it under the terms of the GNU General Public License as published by  
 * the Free Software Foundation; either version 3 of the License, or     
 * (at your option) any later version.                                   
 *                                                                       
 * This program is distributed in the hope that it will be useful,       
 * but WITHOUT ANY WARRANTY; without even the implied warranty of        
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the         
 * GNU General Public License for more details:                          
 *                                                                       
 * http://www.gnu.org/copyleft/gpl.html      
 */

/**
 * class Server 
 */
class Server {

    //generates Unique Token
    /**
     * get token
     * @return string 
     */
    function getToken() { 
        $s = strtoupper(md5(uniqid(rand(),true))); 
        $uniquetoken = 
            substr($s,0,8) . 
            substr($s,8,4) . 
            substr($s,12,4). 
            substr($s,16,4). 
            substr($s,20); 
        
        return $uniquetoken;
    }
    
    /**
     * check user exists
     * @param string $username
     * @param string $md5_password
     * @return int 
     */
    function userExists($username, $md5_password){
        $query = sprintf("SELECT COUNT(id) FROM users WHERE UPPER(username) = UPPER('%s') AND password='%s'",
                                                mysql_real_escape_string($username),
                                                mysql_real_escape_string($md5_password));
        $result = mysql_query($query);
        
        return mysql_result($result,0);   
    }
    
    /**
     * check if authentication dataset exists
     * @param string $username
     * @param string $md5_password
     * @return int 
     */
    function authenticateExists($username, $md5_password){
        $query = sprintf("SELECT COUNT(id) FROM authenticate WHERE UPPER(username) = UPPER('%s') AND password='%s'",
                                            mysql_real_escape_string($username),
                                            mysql_real_escape_string($md5_password));
        $result = mysql_query($query);
        return mysql_result($result,0);
    }
    
    /**
     * update authentication dataset
     * @param string $token
     * @param int $status
     * @param string $username
     * @param string $md5_password
     * @return boolean 
     */
    function authenticateUpdate($token, $status, $username, $md5_password){
        $query = sprintf("UPDATE authenticate SET token = '%s', status = '%s', creation_time = NOW() WHERE UPPER(username) = UPPER('%s') AND password='%s'",
                                            mysql_real_escape_string($token),
                                            mysql_real_escape_string($status),          // Status == 1 means user exists
                                            mysql_real_escape_string($username),
                                            mysql_real_escape_string($md5_password));
        return mysql_query($query);
    }
    
    /**
     * insert authentication dataset to db
     * @param string $token
     * @param int $status
     * @param string $username
     * @param string $md5_password
     * @param int $user_external_id
     * @param string $firstname
     * @param string $lastname
     * @param string $email
     * @param string $ws_username
     * @return boolean 
     */
    function authenticateInsert($token, $status, $username, $md5_password, $user_external_id = NULL, $firstname = NULL, $lastname = NULL, $email = NULL, $ws_username = NULL){
        $query = sprintf("INSERT INTO authenticate (username, password, token, creator_id, status, firstname, lastname, email, user_external_id, ws_username) 
                          VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')",
                                            mysql_real_escape_string($username),
                                            mysql_real_escape_string($md5_password),
                                            mysql_real_escape_string($token),
                                            mysql_real_escape_string('-1'),              //-1 means System
                                            mysql_real_escape_string($status),
                                            mysql_real_escape_string($firstname),
                                            mysql_real_escape_string($lastname),
                                            mysql_real_escape_string($email),
                                            mysql_real_escape_string($user_external_id),
                                            mysql_real_escape_string($ws_username));     // to get the right institution
        return mysql_query($query);
    }
    
    /**
     * setup token
     * @param string $username
     * @param string $md5_password
     * @param int $user_external_id
     * @param string $firstname
     * @param string $lastname
     * @param string $email
     * @param string $ws_username
     * @return boolean 
     */
    function setToken($username, $md5_password, $user_external_id = NULL, $firstname = NULL, $lastname = NULL, $email = NULL, $ws_username = NULL){
        $returnarray['token'] = $this->getToken();                              // get new token
        
        if($this->userExists($username, $md5_password) == 1) {                  // If User exists
            $returnarray['exists'] = true;
            if($this->authenticateExists($username, $md5_password) == 1) {
                $this->authenticateUpdate($returnarray['token'], '1', $username, $md5_password);
            } else {
                $this->authenticateInsert($returnarray['token'], '1', $username, $md5_password, $ws_username); 
            }
            return $returnarray;
        } else {                                                                // if User not exists Status == 0 --> New User (if allowed)
            $returnarray['exists'] = false;
            if($this->authenticateExists($username, $md5_password) == 1) {
                $this->authenticateUpdate($returnarray['token'], '0', $username, $md5_password);
            } else {
                $this->authenticateInsert($returnarray['token'], '0', $username, $md5_password, $user_external_id, $firstname, $lastname, $email, $ws_username); 
            }
            return $returnarray;
            //User does not exist
        }   
    }
    
    /**
     * get curriculum 
     * @param string  $username
     * @return array 
     */
    function get_Curriculum($username){
        $query = sprintf("SELECT cu.curriculum, cu.id, cu.grade_id, gp.id AS group_id, gp.groups, fl.filename 
                FROM curriculum AS cu, curriculum_enrolments AS ce, groups AS gp, files AS fl
                WHERE cu.id = ce.curriculum_id 
                AND ce.status = 1 
                AND gp.id = ce.group_id 
                AND gp.institution_id = ANY (SELECT institution_id FROM institution_enrolments WHERE user_id = 
                                            (SELECT id FROM users WHERE username = '%s'))
                AND cu.icon_id = fl.id
                ORDER BY gp.groups, cu.curriculum ASC",
                mysql_real_escape_string($username));

        $result = mysql_query($query);

        if ($result && mysql_num_rows($result)){
            while($row = mysql_fetch_assoc($result)) {
                    $curriculum_array[] = $row; 
            }
            return $curriculum_array;
        }
    }
    
    /**
     * get objectives
     * @param int $curriculum_id
     * @return string 
     */
    function get_Objectives($curriculum_id){
        $query = sprintf("SELECT a.id, a.enabling_objective, b.terminal_objective
                        FROM enablingObjectives AS a, terminalObjectives AS b 
                        WHERE a.curriculum_id = %s 
                        AND a.terminal_objective_id = b.id
                        ORDER BY b.id, a.id ASC",
                        mysql_real_escape_string($curriculum_id));
    $result = mysql_query($query);

    while ($rec = mysql_fetch_assoc($result)){
            $get_Objectives_result[$rec['id']] = $rec['terminal_objective'].' | '.$rec['enabling_objective'];//substr($rec['enabling_objectieve'], 0, 70);
    }

    return $get_Objectives_result;
    }
    
    /**
     * set accomplished objectibes
     * @param int $curriculum_user_id
     * @param int $enabling_objective_id
     * @param int $teacher_id
     * @param int $status_id
     * @return boolean 
     */
    function set_accomplishedObjectives($curriculum_user_id, $enabling_objective_id, $teacher_id, $status_id){
        $query = sprintf("SELECT COUNT(id) FROM user_accomplished WHERE enabling_objectives_id = '%s' AND user_id = '%s'",
                                            mysql_real_escape_string($enabling_objective_id),
                                            mysql_real_escape_string($curriculum_user_id));
        $result = mysql_query($query);
        list($count) = mysql_fetch_row($result);
        if($count >= 1) { //nur eintragen wenn Ziel ausgewählt
                //$error = 'Diesen Eintrag gibt es bereits.';
                $query = sprintf("UPDATE user_accomplished SET status_id = '%s', creator_id = '%s' WHERE enabling_objectives_id = '%s' AND user_id = '%s'",
                                                    mysql_real_escape_string($status_id),
                                                    mysql_real_escape_string($teacher_id),
                                                    mysql_real_escape_string($enabling_objective_id),
                                                    mysql_real_escape_string($curriculum_user_id));
                $result = mysql_query($query);
        } else {
                $query = sprintf("INSERT INTO user_accomplished(enabling_objectives_id,user_id,status_id,creator_id) VALUES ('%s','%s','%s','%s')",
                                                    mysql_real_escape_string($enabling_objective_id),
                                                    mysql_real_escape_string($curriculum_user_id),
                                                    mysql_real_escape_string($status_id),
                                                    mysql_real_escape_string($teacher_id));
                $result = mysql_query($query);	
        }
        return $result;
    }
}

?>
