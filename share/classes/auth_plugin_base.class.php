<?php
/**
* Base class for authentication plugins
* 
* @abstract This file is part of curriculum - http://www.joachimdieterich.de
* @package plugin
* @filename auth_plugin_Base.class.php
* @copyright 2016 Joachim Dieterich
* @author Joachim Dieterich
* @date 2017.04.24 17:08
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
class auth_plugin_base {
    /**
     * Config Data from config_plugin table
     * @var object 
     */
    var $config;

    /**
     * Authentication plugin type - the same as db field.
     * @var string
     */
    var $authtype;
    /*
     * The fields we can lock and update from/to external authentication backends
     * @var array
     */
    var $userfields = array(
        'username',
        'firstname',
        'lastname',
        'email',
        'poastalcode',
        'city',
        'country'
    );
    
    function add_user($user){
        /* load Superuser to get work done ---> todo use ldap system users*/
        global $USER;
        $USER = new User();
        $USER->load('username', 'admin');             // Benutzer aus DB laden
        $USER->password         = '';                 // Passwort aus Session löschen
        $_SESSION['USER']       =& $USER;
        /* end */
        
        return $user->add($user->institutions); //enrol to all given institutions / role (from LDAP)
    }
    
    
}