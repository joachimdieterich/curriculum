<?php
/**
 * This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package core
 * @filename db.class.php
 * @copyright 2013 joachimdieterich
 * @author Nathan Tsoi, joachimdieterich
 * @date 2013.03.08 13:26
 * @license: 
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by  
 * the Free Software Foundation; either version 3 of the License, or (at your option) any later version.                                   
 *                                                                       
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of        
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details:                          
 *                                                                       
 * http://www.gnu.org/copyleft/gpl.html 
 * 
 * Database Singleton - USE this class for all database access
 * 	DB::exec("DELETE FROM Blah");	
 *	foreach( DB::query("SELECT * FROM Blah") as $row){
 *	        print_r($row);
 *	}
 * 	
 * Or...
 *  $db = DB::prepare("SELECT 1 FROM `bookmark` WHERE `userid` = ? AND (`url` = ? OR `name` = ?)");
 *  $db->execute(array($data['userid'], $data['url'], $data['name']));
 *  if(!$db->fetchAll()) { //if no rows returned, then return false
 *       return false;
 *  }
 *
 * Taken from: http://www.php.net/manual/en/book.pdo.php#93178    
 */

class DB {
    private static $objInstance;
   
    /*
     * Class Constructor - Create a new database connection if one doesn't exist
     * Set to private so no-one can create a new instance via ' = new DB();'
     */
    private function __construct() {}
   
    /*
     * Like the constructor, we make __clone private so nobody can clone the instance
     */
    private function __clone() {}
   
    /*
     * Returns Db instance or create initial connection
     * @param
     * @return $objInstance;
     */
    public static function getInstance(  ) {
        global $CFG;   
        if(!self::$objInstance){
            self::$objInstance = new PDO('mysql:host='.$CFG->db_host.';dbname='.$CFG->db_name.';charset=utf8;', $CFG->db_user, $CFG->db_password ); // PDO DSN http://www.php.net/manual/en/ref.pdo-mysql.php
            self::$objInstance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING); //ERRMODE_SILENT, ERRMODE_WARNING (for debug), ERRMODE_EXCEPTION 
            self::$objInstance->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        }
       
        return self::$objInstance;
   
    } # end method
    
    /*
     * Passes on any static calls to this class onto the singleton PDO instance
     * @param $chrMethod, $arrArguments
     * @return $mix
     */
    final public static function __callStatic( $chrMethod, $arrArguments ) {
        $objInstance = self::getInstance();
        return call_user_func_array(array($objInstance, $chrMethod), $arrArguments);
    } # end method   
}