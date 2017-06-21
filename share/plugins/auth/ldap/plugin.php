<?php
/**
 * LDAP Plugin for curriculum
 *
 * @abstract This file is part of curriculum - http://www.joachimdieterich.de
 * @package plugins
 * @filename plugin.php
 * @copyright 2017 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2017.04.16 20:53
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
/**
 * Inspired by moodle Authentication Plugin: LDAP Authentication
 * @author Iñaki Arenaza
 * @author Martin Dougiamas
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 * 
 * AND
 *  
 * Inspired by moodle LDAP functions & data library
 * @author Iñaki Arenaza
 * @copyright  1999 onwards Martin Dougiamas  http://dougiamas.com
 * @copyright  2010 onwards Iñaki Arenaza
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * 
 */
class auth_plugin_ldap extends auth_plugin_base { 
    
    var $ldapconnection_counter;
    var $ldapconnection;
    
    function __construct() {
        $config         = new Config();
        $this->config   = $config->load_plugin_config('auth/ldap');
        $this->authtype = 'ldap';
        //error_log(json_encode($this->config ));
    }
    /**
     * Connect to the LDAP server, using the plugin settings.  
     * Inspired by 
     * @return resource A valid LDAP connection (or dies if it can't connect)
     */
    function ldap_connect() {
        if(!empty($this->ldapconnection)) {
            $this->ldapconnection_counter++;
            return $this->ldapconnection;
        }
        
        if($ldapconnection = $this->ldap_connect_curriculum()) {
            $this->ldapconnection_counter = 1;
            $this->ldapconnection         = $ldapconnection;
            return $ldapconnection;
        }
        //print_error('auth_ldap_noconnect_all', 'auth_ldap', '', $debuginfo);
    }
   
    
    function ldap_connect_curriculum() {
        if (empty($this->config->host_url) || empty($this->config->ldap_version) || empty($this->config->user_type)) {
            error_log('No LDAP Host URL, Version or User Type specified in your LDAP settings');
            return false;
        }

        $urls = explode(';', $this->config->host_url);
        foreach ($urls as $server) {
            $server = trim($server);
            if (empty($server)) {
                continue;
            }
            
            $connresult = ldap_connect($server); // ldap_connect returns ALWAYS true
            if (!empty($this->config->ldap_version)) {
                ldap_set_option($connresult, LDAP_OPT_PROTOCOL_VERSION, $this->config->ldap_version);
            }
            if ($this->config->user_type === 'ad') { 
                ldap_set_option($connresult, LDAP_OPT_REFERRALS, 0);
            }
            if (!empty($this->config->opt_deref)) { 
                ldap_set_option($connresult, LDAP_OPT_DEREF, $this->config->opt_deref);
            }
            if ($this->config->start_tls && (!ldap_start_tls($connresult))) {
                //error_log("Server: '$server', Connection: '$connresult', STARTTLS failed.");
                continue;
            }
            if (!empty($this->config->bind_dn)) {
                $bindresult = @ldap_bind($connresult, $this->config->bind_dn, $this->config->bind_pw);
            } else {
                $bindresult = @ldap_bind($connresult);  // Bind anonymously
            }

            if ($bindresult) {
                return $connresult;
            }
            //error_log("Server: '$server', Connection: '$connresult', Bind result: '$bindresult'");
        }

        // If any of servers were alive we have already returned connection.
        return false;
    }
    
    /**
     * Search defined contexts for username and returns the user dn like:
     * uid=username,cn=users,dc=host
     * @param string $username
     * @return mixed user dn (in LDAP encoding) or false
     */
    function ldap_find_userdn($username) {
        $ldap_contexts = explode(';', $this->config->contexts);
        if (!empty($this->config->create_context)) {
            array_push($ldap_contexts, $this->config->create_context);
        }
       
        if (empty($this->ldapconnection) || empty($username) || empty($ldap_contexts) || empty($this->config->objectclass) || empty($this->config->user_attribute)) {
            //error_log(json_encode($this->config));
            return false;
        }

        // Default return value
        $ldap_user_dn = false;

        // Get all contexts and look for first matching user
        foreach ($ldap_contexts as $context) {
            $context = trim($context);
            if (empty($context)) {
                continue;
            }
            
            if ($this->config->search_sub) {
                $ldap_result = @ldap_search($this->ldapconnection, $context,
                                            '(&'.$this->config->objectclass.'('.$this->config->user_attribute.'='.$username.'))',
                                            array($this->config->user_attribute));
            } else {
                $ldap_result = @ldap_list($this->ldapconnection, $context,
                                          '(&'.$this->config->objectclass.'('.$this->config->user_attribute.'='.$username.'))',
                                          array($this->config->user_attribute));
            }

            if (!$ldap_result) {
                continue; // Not found in this context.
            }

            $entry = ldap_first_entry($this->ldapconnection, $ldap_result);
            if ($entry) {
                $ldap_user_dn = ldap_get_dn($this->ldapconnection, $entry);
                break;
            }
        }

        return $ldap_user_dn;
    }
    
    /**
     * Reads user information from ldap and returns it in array()
     *
     * Function should return all information available. If you are saving
     * this information to curriculum user-table you should honor syncronization flags
     *
     * @param string $username username
     *
     * @return mixed array with no magic quotes or false on error
     */
    function get_userinfo($username) {
        
        $this->ldap_connect();
        if(!($user_dn   = $this->ldap_find_userdn($username))) {
            $this->ldap_close();
            return false;
        }
        
        $search_attribs = array();
        $attrmap        = $this->ldap_attributes();
        foreach ($attrmap as $key => $values) {
            if (!is_array($values)) {
                $values = array($values);
            }
            foreach ($values as $value) {
                if (!in_array($value, $search_attribs)) {
                    array_push($search_attribs, $value);
                }
            }
        }

        if (!$user_info_result = ldap_read($this->ldapconnection, $user_dn, '(objectClass=*)', $search_attribs)) {
            $this->ldap_close();
            return false; // error!
        }

        $user_entry = $this->ldap_get_entries_curriculum($user_info_result);
        if (empty($user_entry)) {
            $this->ldap_close();
            return false; // entry not found
        }

        $result = array();
        foreach ($attrmap as $key => $values) {
            if (!is_array($values)) {
                $values = array($values);
            }
            $ldapval = NULL;
            foreach ($values as $value) {
                $entry = array_change_key_case($user_entry[0], CASE_LOWER);
                if (($value == 'dn') || ($value == 'distinguishedname')) {
                    $result[$key] = $user_dn;
                    continue;
                }
                if (!array_key_exists($value, $entry)) {
                    continue; // wrong data mapping!
                }
                if (is_array($entry[$value])) {
                    $newval = mb_convert_encoding($entry[$value][0], $this->config->ldapencoding, 'utf-8');
                } else {
                    $newval = mb_convert_encoding($entry[$value], $this->config->ldapencoding, 'utf-8');
                }
                if (!empty($newval)) { // favour ldap entries that are set
                    $ldapval = $newval;
                }
            }
            if (!is_null($ldapval)) {
                $result[$key] = $ldapval;
            }
        }

        $this->ldap_close();
        return $result;
    }
    
    
     /**
     * Disconnects from a LDAP server
     *
     * @param force boolean Forces closing the real connection to the LDAP server, ignoring any
     *                      cached connections. This is needed when we've used paged results
     *                      and want to use normal results again.
     */
    function ldap_close($force=false) {
        $this->ldapconnection_counter--;
        if (($this->ldapconnection_counter == 0) || ($force)) {
            $this->ldapconnection_counter = 0;
            @ldap_close($this->ldapconnection);
            unset($this->ldapconnection);
        }
    }
    
    /**
     * Returns true if the username and password work and false if they are
     * wrong or don't exist.
     *
     * @param string $username The username (without system magic quotes)
     * @param string $password The password (without system magic quotes)
     *
     * @return bool Authentication success or failure.
     */
    function user_login($username, $password) {
        if ($ldap_user_array = $this->get_userinfo($username)){
            $new_user = new User();
            if ($new_user->exists('username', $ldap_user_array["username"])){
                error_log('User exists --> check for ldap_user...');
                $new_user->load('username', $ldap_user_array["username"]);
                if ($new_user->auth = 'ldap'){
                    error_log('ckecked... User is ldap_user!');
                    return $new_user->id;
                }
            } else {
                error_log('user does not exist.... creating user...');
                return $this->user_create($new_user, $ldap_user_array, $password);
            }
        } else {
            error_log('Invalid email address / password');
            return false;
        }
    }
    
    function user_create ($ldap_user, $ldap_user_array, $plain_password){
        global $CFG;
        $ldap_user->auth            = 'ldap';   //set auth-method
        $ldap_user->username        = $ldap_user_array["username"];
        $ldap_user->password        = $plain_password;
        if (isset($ldap_user_array["firstname"]))   { $ldap_user->firstname  = $ldap_user_array["firstname"]; }   else { $ldap_user->firstname  = ' '; }
        if (isset($ldap_user_array["lastname"]))    { $ldap_user->lastname   = $ldap_user_array["lastname"]; }    else { $ldap_user->lastname   = $ldap_user_array["username"]; }
        $ldap_user->email           = $ldap_user_array["email"];
        if (isset($ldap_user_array["poastalcode"])) { $ldap_user->postalcode = $ldap_user_array["poastalcode"]; }  else { $ldap_user->postalcode = ' '; } 
        if (isset($ldap_user_array["city"]))        { $ldap_user->city       = $ldap_user_array["city"]; }         else { $ldap_user->city = ' '; } 

        $ldap_user->country_id      = $CFG->settings->standard_country ; 
        $ldap_user->state_id        = $CFG->settings->standard_state; 

        $ldap_user->avatar_id       = $CFG->settings->standard_avatar_id;         
        $ldap_user->confirmed       = 1;

        $ldap_user->paginator_limit = $CFG->settings->paginator_limit;
        $ldap_user->acc_days        = $CFG->settings->acc_days;
        $this->get_user_institution_enrolments($ldap_user);
        
        return parent::add_user($ldap_user);  
    }
    
    
    function get_user_institution_enrolments($ldap_user){
        $this->ldap_connect();
        if (empty($this->ldapconnection) || empty($ldap_user->username) || empty($this->config->host_dn)) {
            error_log(json_encode($this->config));
            return false;
        }
        $member_result  = ldap_search($this->ldapconnection, $this->config->host_dn, '(&(member=uid='.$ldap_user->username.',cn=users,'.$this->config->host_dn.'))');
        $member_info    = ldap_get_entries($this->ldapconnection, $member_result);
        error_log(json_encode($member_info));
        $institution    = new Institution();
        $role           = new Roles();
        if ($member_info["count"] != 0 ){
            for ($i=0; $i<$member_info["count"]; $i++) {
                $mapping = ldap_explode_dn($member_info[$i]["dn"], 1);
                error_log($mapping[2].': '.$mapping[1]);
                $role->load('role', $mapping[1]);
                $institution->load('institution', $mapping[2]);
                $ldap_user->institutions[$institution->id] =  array('institution' => $institution->institution,
                                                                    'institution_id' => $institution->id,
                                                                    'role_id' => $role->id,
                                                                    'role' => $role->role);
            }
        }
        $this->ldap_close();
    }
    
    /**
     * Syncronizes institutions from external LDAP server to curriculum institution table
     */
    function sync_institutions($update = true){
        
    }
    
    function sync_update_ldap(){
        global $CFG;
        $this->ldap_connect();
        if (empty($this->ldapconnection)){
            return false;
        }

        /*Get institution entries in curriculum db*/
        $institutions        = new Institution();
        $institutions_array  = $institutions->getInstitutions('all');
        $roles               = new Roles();
        $roles_array         = $roles->get('',true); //get all roles
        //error_log(json_encode($institutions_array));
        // Institutionen hinzufügen
        foreach ($institutions_array as $i_value) {
            $i_info["objectclass"][0]  = "top";
            $i_info["objectclass"][1]  = "organizationalUnit";
            $i_info["ou"]              = $i_value->institution;

            ldap_add($this->ldapconnection, "ou=".$i_value->institution.",".$this->config->service_dn.",".$this->config->host_dn, $i_info);

            //add roles
            foreach ($roles_array as $r_value) {
                $r_info["objectclass"][0]  = "top";
                $r_info["objectclass"][1]  = "organizationalUnit";
                $r_info["ou"]              = $r_value->role;

                ldap_add($this->ldapconnection, "ou=".$r_value->role.",ou=".$i_value->institution.",".$this->config->service_dn.",".$this->config->host_dn, $r_info);
            }
            
            //add users
            
            
            
            //add groups
            
            
             
        }
        $this->ldap_close();
    }
    
    /**
     * Returns user attribute mappings (stored in config_plugins) between curriculum and LDAP
     *
     * @return array
     */
    function ldap_attributes () {
        $attributes = array();

        foreach ($this->userfields as $field) {
            if (!empty($this->config->{"field_map_$field"})) {
                $attributes[$field] = strtolower(trim($this->config->{"field_map_$field"}));
                if (preg_match('/,/', $attributes[$field])) {
                    $attributes[$field] = explode(',', $attributes[$field]); // split ?
                }
            }
        }
        $attributes['username'] = strtolower(trim($this->config->user_attribute));
        return $attributes;
    }
    
    /**
    * Returns values like ldap_get_entries but is binary compatible and
    * returns all attributes as array.
    *
    * @param mixed $searchresult A search result from ldap_search, ldap_list, etc.
    * @return array ldap-entries with lower-cased attributes as indexes
    */
   function ldap_get_entries_curriculum($searchresult) {
       if (empty($this->ldapconnection) || empty($searchresult)) {
           return array();
       }

       $i = 0;
       $result = array();
       $entry = ldap_first_entry($this->ldapconnection, $searchresult);
       if (!$entry) {
           return array();
       }
       do {
           $attributes = array_change_key_case(ldap_get_attributes($this->ldapconnection, $entry), CASE_LOWER);
           for ($j = 0; $j < $attributes['count']; $j++) {
               $values = ldap_get_values_len($this->ldapconnection, $entry, $attributes[$j]);
               if (is_array($values)) {
                   $result[$i][$attributes[$j]] = $values;
               } else {
                   $result[$i][$attributes[$j]] = array($values);
               }
           }
           $i++;
       } while ($entry = ldap_next_entry($this->ldapconnection, $entry));

       return ($result);
   }
   
    
}