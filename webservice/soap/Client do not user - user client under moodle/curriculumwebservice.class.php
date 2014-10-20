<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package webservice 
 * @filename curriculumwebservice.class.php - curriculum webservice class
 * @copyright 2013 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2013.04.01 10:36
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
 * class Curriculumwebservice 
 */
class Curriculumwebservice {
    /**
     * Client
     * @var object 
     */
    protected $client;
    /**
     * Path
     * @var string 
     */
    protected $path;
    /**
     * result
     * @var array 
     */
    protected $result;
    /**
     * webservice user
     * @var string 
     */
    private   $ws_username;
    /**
     * webservice password
     * @var string 
     */
    private   $ws_password;
    
    /**
     * class constructor
     * @param string $path
     * @param string $ws_username
     * @param string $ws_password 
     */
    function __construct($path, $ws_username, $ws_password) {
        $this->path         = $path;
        $this->ws_username  = $ws_username;
        $this->ws_password  = $ws_password;
        $this->client = new SoapClient($path.'webservice/soap/server.wsdl', array('exceptions' => 0));
        $this->client = new SoapClient($path.'webservice/soap/server.wsdl', array('cache_wsdl' => WSDL_CACHE_NONE) ); //umgeht die wsdl cache, damit neue Funktionen angezeigt werden
        ini_set("soap.wsdl_cache_enabled", 0);
        try {                                                                   // Errorhandling
            $this->result = $this->client->soapLogin($ws_username,$ws_password);
        } catch (SoapFault $e){
            echo $this->get_soap_fault($e);
        }
    }
    
    /**
     * soap login
     * @param string $username
     * @param string $password
     * @return boolean 
     */
    function soapLogin($username,$password){
        try {                                                                   // Errorhandling
            $this->result = $this->client->soapLogin($username,$password);     
                if ($this->result == true) {
                    header('Location:'.$this->path.'public/index.php?action=login&username='.$username.'&password='.md5($password));
                } else {
                    return false; //Benutzer nicht registriert
                }
        } catch (SoapFault $e){
            echo $this->get_soap_fault($e);
        }
    }
    
    /**
     * get curriculum
     * @param id $id
     * @return array 
     */
    function getCurriculum($id){
        try {                                                                   // Errorhandling
            $this->result = $this->client->getCurriculum($id);
            return $this->result;       
        } catch (SoapFault $e){
            echo $this->get_soap_fault($e);
        }
            
    }
    
    /**
     * get soap fault 
     * @param object $e
     * @return string 
     */
    function get_soap_fault($e){ 
        $soap_error = "Fehler-Code: ".$e->faultcode."<br> Beschreibung: ".$e->faultstring."<br> Sender: ".$e->faultactor; 
        return $soap_error;
    }
    
}
?>