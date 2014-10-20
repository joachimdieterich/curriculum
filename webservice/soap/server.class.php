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
        return getToken();//--> this function is now included in function.php
    }
    
    /**
     * check user exists
     * @param string $username
     * @param string $md5_password
     * @return int 
     */
    function userExists($username, $md5_password){
        $db = DB::prepare('SELECT COUNT(id) FROM users WHERE UPPER(username) = UPPER(?) AND password=?');
        $db->execute(array($username, $md5_password));
        return $db->fetchColumn();
    }
    
    /**
     * check if authentication dataset exists
     * @param string $username
     * @param string $md5_password
     * @return int 
     */
    function authenticateExists($username, $md5_password){
        $db = DB::prepare('SELECT COUNT(id) FROM authenticate WHERE UPPER(username) = UPPER(?) AND password=?');
        $db->execute(array($username, $md5_password));
        return $db->fetchColumn(); 
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
        $db = DB::prepare('UPDATE authenticate SET token = ?, status = ?, creation_time = NOW() WHERE UPPER(username) = UPPER(?) AND password=?');
        return $db->execute(array($token, $status, $username, $md5_password));
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
        $db = DB::prepare('INSERT INTO authenticate (username, password, token, creator_id, status, firstname, lastname, email, user_external_id, ws_username) 
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
       return $db->execute(array($username, $md5_password, $token, '-1', $status, $firstname, $lastname, $email, $user_external_id, $ws_username));
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
        } else {    // if User not exists Status == 0 --> New User (if allowed)
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
        $db = DB::prepare('SELECT cu.curriculum, cu.id, cu.grade_id, gp.id AS group_id, gp.groups, fl.filename 
                FROM curriculum AS cu, curriculum_enrolments AS ce, groups AS gp, files AS fl
                WHERE cu.id = ce.curriculum_id 
                AND ce.status = 1 AND gp.id = ce.group_id 
                AND gp.institution_id = ANY (SELECT institution_id FROM institution_enrolments WHERE user_id = 
                                            (SELECT id FROM users WHERE username = ?) AND status = 1)
                AND cu.icon_id = fl.id
                ORDER BY gp.groups, cu.curriculum ASC');
        $db->execute(array($username));
        while($result = $db->fetchObject()) {
            $curriculum_array[] = $result;
        }
        
        if (isset($curriculum_array)){
            return $curriculum_array;
        } else {return false;}
    }
    
    /**
     * get objectives
     * @param int $curriculum_id
     * @return string 
     */
    function get_Objectives($curriculum_id){
        $db = DB::prepare('SELECT a.id, a.enabling_objective, b.terminal_objective FROM enablingObjectives AS a, terminalObjectives AS b 
                        WHERE a.curriculum_id = ? AND a.terminal_objective_id = b.id ORDER BY b.id, a.id ASC');
        $db->execute(array($curriculum_id)); 
  
        while($result = $db->fetchObject()) {
                $get_Objectives_result[$result->id] = $result->terminal_objective.' | '.$result->enabling_objective;//substr($rec['enabling_objectieve'], 0, 70);
            }
        if (isset($get_Objectives_result)){
            return $get_Objectives_result;
        } else {return false;}
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
        $db = DB::prepare('SELECT COUNT(id) FROM user_accomplished WHERE enabling_objectives_id = ? AND user_id = ?');
        $db->execute(array($enabling_objective_id, $curriculum_user_id));
        if($db->fetchColumn() >= 1) { //nur eintragen wenn Ziel ausgewählt
                $db = DB::prepare('UPDATE user_accomplished SET status_id = ?, creator_id = ? WHERE enabling_objectives_id = ? AND user_id = ?');
                $result = $db->execute(array($status_id, $teacher_id, $enabling_objective_id, $curriculum_user_id));
        } else {
                $db = DB::prepare('INSERT INTO user_accomplished(enabling_objectives_id,user_id,status_id,creator_id) VALUES (?,?,?,?)');
                $result = $db->execute(array($status_id, $teacher_id, $enabling_objective_id, $curriculum_user_id));
        }
        return $result;
    }
}
?>