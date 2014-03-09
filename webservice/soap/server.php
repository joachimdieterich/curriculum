<?php 
/**
 *  This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package webservice
 * @filename server.php - curriculum webservice server class
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

$configfile = dirname(__FILE__).'/../../share/config.php'; //damit zugriff auf db funktioniert

global $CFG;

include($configfile);
include_once''.dirname(__FILE__).'/../../share/function.php'; //damit funktionen verfügbar sind.
include_once''.dirname(__FILE__).'/../../share/include.php'; //damit funktionen verfügbar sind.
include_once 'server.class.php';    //Server class file

$server = new SoapServer(NULL, 
                        array('uri' => $_SERVER['SERVER_NAME'].$CFG->BASE_URL."webservice/soap/server.php"));                //{uri} müsst ihr ersetzen mit den pfad 
/*
 * Funktionen zum Server hinzufügen
 */
$server->addFunction('soapConnect');            
$server->addFunction('soapLogin');           
$server->addFunction('get_Curriculum');       
$server->addFunction('get_Objectives');       
$server->addFunction('set_accomplishedObjectives');       
//$server->addFunction('getToken');        
$server->handle();                     //Hier wird die Abfrage abgearbeitet 


// Funktionen des Webservices
/**
 * connect with soap
 * @param string $username
 * @param string $password
 * @return object
 */
function soapConnect($username, $password) { 
        if (isset($username) && isset($password)) {   
            $request = new Server();
            return $request->setToken($username, md5($password));
        } else {
            return new SoapFault('Client', 'soapConnect benötigt weitere Parameter', 'server.php', ''); 
        }
} 

/**
 * login with soapclient
 * @param int $user_external_id
 * @param string $username
 * @param string $firstname
 * @param string $lastname
 * @param string $email
 * @param string $password
 * @param string $ws_username
 * @return object
 */
function soapLogin($user_external_id, $username, $firstname, $lastname, $email, $password, $ws_username) { 
        if (isset($username) && isset($password)) {   
            $request = new Server();
            return $request->setToken($username, $password, $user_external_id, $firstname, $lastname, $email, $ws_username);
        } else {
            return new SoapFault('Client', 'soapLogin benötigt weitere Parameter', 'server.php', ''); 
        }
} 

/**
 * get curriculum 
 * @param string $username
 * @return object
 */
function get_Curriculum($username/*,option=my_cur, all_cur, activ_cur, ... evtl. mit switch query auswählen*/) { 
        if (isset($username)) {
            $request = new Server();
            return $request->get_Curriculum($username);
            } else {
                return new SoapFault('Client', 'get_Curriculum benötigt weitere Parameter', 'server.php', '');
            }
} 

/**
 * get objectives
 * @param int $curriculum_id
 * @return object
 */
function get_Objectives($curriculum_id){
    if (isset($curriculum_id)) {   
        $request = new Server();
        return $request->get_Objectives($curriculum_id);
    } else {
        return new SoapFault('Client', 'get_Objectives benötigt $curriculum_id', 'server.php', ''); 
    }        
}

/**
 * set accomplished objectives 
 * @param int $curriculum_user_id
 * @param int $enabling_objective_id
 * @param int $teacher_id
 * @param int $status_id
 * @return object
 */
function set_accomplishedObjectives($curriculum_user_id, $enabling_objective_id, $teacher_id, $status_id){
    if (isset($curriculum_user_id) && isset($enabling_objective_id) && isset($teacher_id) && isset($status_id)) {   
        $request = new Server();
        return $request->set_accomplishedObjectives($curriculum_user_id, $enabling_objective_id, $teacher_id, $status_id);
    } else {
        return new SoapFault('Client', 'set_accomplishedObjectives benötigt weitere Parameter', 'server.php', ''); 
    }        
}

?>