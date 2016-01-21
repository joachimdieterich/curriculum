<?php

/**
 * based on https://github.com/krisolafson/badgekit/blob/master/includes/BadgekitConnection.inc
 * @file
 * BadgekitConnection class.
 */

/**
 * Badgekit Connection class.
 */
class BadgekitConnection {

  public $method;

  public $path;

  public $key;

  public $token;

  public $url;
  
  public $data;
  public $data_hash;

  public $response;
  
  public $header;

  /**
   * @param string $key
   */
  public function __construct($key = 'master') {
    $this->key = $key;
    $this->method = '';
    $this->path = '';
    $this->url = '';
    $this->data = null;
    $this->data_hash = null;
  }


  /**
   * Connect to the BridgeKit API and attempt to return data
   */
  public function connect() {
    $this->setToken();
    $this->response = json_decode($this->curl_get_contents($this->url));
  }
   
  /**
   * CURL 
   * @param type $url
   * @param type $header
   * @return type
   */
    public function curl_get_contents($url)
    {
      $ch = curl_init($url);
      
      curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
      curl_setopt($ch, CURLOPT_ENCODING ,"");
      if ($this->data != null){
          curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST"); 
          curl_setopt($ch, CURLOPT_POSTFIELDS, $this->data);
          curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: JWT token="' . $this->token . '"',
                            'Content-Type: application/json',
                            'Content-Length: ' . strlen($this->data)));  
      } else {
          curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: JWT token="' . $this->token . '"'));
      }
      
      curl_setopt($ch, CURLINFO_HEADER_OUT, true); // enable tracking
      $data = curl_exec($ch);
      curl_close($ch);
      
      return $data;
  }

  /**
   * Set method of API connection
   * @param string $method
   */
  public function setMethod($method) {
    $this->method = $method;
  }

  /**
   * Set path of API connection
   * @param string $path
   */
  public function setPath($path) {  
    global $CFG;
    $this->path = $path;
    $this->url = $CFG->badge_url . $path; 
    
  }


  /**
   * Set path of API connection
   */
  public function setToken() {
    global $CFG;  
    if ($this->data_hash != null){
        $token_data = array(
          "key"    => $this->key,
          "exp"    => strtotime('+1 minute', time()),
          "method" => $this->method,
          "path"   => $this->path,
          "body"   =>  array(
              "alg"   => 'sha256',
              "hash"  => $this->data_hash
           )
        ); 
    } else {
        $token_data = array(
          "key"    => $this->key,
          "exp"    => strtotime('+1 minute', time()),
          "method" => $this->method,
          "path"   => $this->path
        );
    }

    $secret = $CFG->badge_secret;
    $this->token = JWT::encode($token_data, $secret);
  }
  
  /**
   * prepare POST payload
   * @param  string
   */
  public function setPost($data){
       $this->data      = $data;
       $this->data_hash =  hash('sha256',  $data, false); 
  }

/* POST */
  public function createBadge($system, $body) {
    // Set up the JSON Web Token
    $this->setMethod('POST');
    $this->setPath('/systems/' . $system . '/badges');
    $this->data = $body;
    //echo 'Request'.$this->data;
    $this->data_hash =  hash('sha256', $body, false);
    $this->connect();
    return $this->response;
  } 
  
  /*public function createBadgeInstance($system, $body, $slug) {
    // Set up the JSON Web Token
    $this->setMethod('POST');
    $this->setPath('/systems/' . $system . '/badges/'. $slug . '/instances');
    $this->data = $body;
    $this->data_hash =  hash('sha256',  $body, false);
    $this->connect();
    return $this->response;
  }*/ 
  
  
  public function createSystem($body){
    // Set up the JSON Web Token
    $this->setMethod('POST');
    $this->setPath('/systems');
    $this->data = $body ;
    $this->data_hash = hash('sha256', $body);
    $this->connect();
    return $this->response;  
  }
/* GET */  
  /**
   * Gets all published badges
   *
   * @param string $system
   *  The system identifier
   *
   * @return array An array of badge objects
   */
  public function getBadges($system) {
    // Set up the JSON Web Token
    $this->setMethod('GET');
    $this->setPath('/systems/' . $system . '/badges');
    $this->connect();
    return $this->response->badges;
  }
  
  public function getBadge($system, $slug) {
    // Set up the JSON Web Token
    $this->setMethod('GET');
    $this->setPath('/systems/' . $system . '/badges/'. $slug);
    $this->connect();
    return $this->response->badge;
  }
  


  /**
   * Gets a specific system
   *
   * @param string $system
   *  The system identifier
   *
   * @return object A system object
   */
  public function getSystem($system) {
    $this->setMethod('GET');
    $this->setPath('/systems/' . $system);
    $this->connect();
    return $this->response->system;
  }


  /**
   * Gets all systems
   *
   * @return array An array of system objects
   */
  public function getSystems() {
    // Set up the JSON Web Token
    $this->setMethod('GET');
    $this->setPath('/systems');
    $this->connect();
    
    return $this->response->systems;
  }


  /**
   * Gets all issuers for a specified system
   *
   * @param string $system
   *  The system identifier
   *
   * @param string $issuer
   *  The issuer identifier
   *
   * @return An issuer object
   */
  public function getIssuer($system, $issuer) {
    $this->setMethod('GET');
    $this->setPath('/systems/' . $system . '/issuers/' . $issuer);
    $this->connect();
    return $this->response->issuer;
  }


  /**
   * Gets all instances (awarded awards) for a specified system and badge
   *
   * @param string $system
   *  The system identifier
   *
   * @param string $badge
   *  The badge
   *
   * @return array An array of instance objects
   */
  public function getInstancesByBadge($system, $badge) {
    $this->setMethod('GET');

    $this->setPath('/systems/' . $system . '/badges/' . $badge . '/instances');
    $this->connect();
    return $this->response->instances;
  }


  /**
   * Gets all instances (awarded awards) for a specified system and email address
   *
   * @param string $system
   *  The system identifier
   *
   * @param string $email
   *  The user's email address
   *
   * @return array An array of instance objects
   */
  public function getInstancesByUser($system, $email) {
    $this->setMethod('GET');
    $this->setPath('/systems/' . $system . '/instances/' . $email);
    $this->connect();
    return $this->response->instances;
  }


  /**
   * Gets all issuers for a specified system
   *
   * @param string $system
   *  The system identifier
   *
   * @return array An array of issuer objects
   */
  public function getIssuers($system) {
    $this->setMethod('GET');
    $this->setPath('/systems/' . $system . '/issuers');
    $this->connect();
    return $this->response->issuers;
  }


  /**
   * Gets all milestones for a specified system
   *
   * @param string $system
   *  The system identifier
   *
   * @return array An array of milestone objects
   */
  public function getMilestones($system) {
    $this->setMethod('GET');
    $this->setPath('/systems/' . $system . '/milestones');
    $this->connect();
    return $this->response->milestones;
  }


  /**
   * Gets all programs for a specified system and issuer
   *
   * @param string $system
   *  The system identifier
   *
   * @param string $issuer
   *  The issuer identifier
   *
   * @return array An array of program objects
   */
  public function getPrograms($system, $issuer) {
    $this->setMethod('GET');
    $this->setPath('/systems/' . $system . '/issuers/' . $issuer . '/programs');
    $this->connect();
    return $this->response->programs;
  }

}