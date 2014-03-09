<?php

/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename config.php - Main configuration file. 
* @copyright  2013 Joachim Dieterich  {@link http://www.joachimdieterich.de}
* @author Joachim Dieterich
* @date 2013.03.08 13:26
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
 
global $CFG, $DB;  
$CFG = new stdClass();

/**
 * Applicationn !IMPORTANT! Do not change manually
 */
$CFG->app_title='curriculum 0.9';
$CFG->app_footer                = 'curriculum 0.9 BETA - 2012-2013 www.joachimdieterich.de';

$CFG->debug                     = true;     

/**
 * DB Settings
 */
$CFG->db_host='127.0.0.1';
$CFG->db_user='root';
$CFG->db_password ='root';
$CFG->db_name='install';

$DB = new PDO('mysql:host='.$CFG->db_host.';dbname='.$CFG->db_name.';charset=utf8', $CFG->db_user, $CFG->db_password ); 
$DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$DB->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
/**
 * Paths - do not edit
 */
$CFG->BASE_URL='/curriculum/';                               // URL to index //Pfad von 
$CFG->document_root             = dirname(__FILE__).'/../public/';
$CFG->controllers_root          = dirname(__FILE__).'/controllers/'; 
$CFG->user_root                 = dirname(__FILE__).'/../curriculumdata/userdata/';
$CFG->curriculum_root           = dirname(__FILE__).'/../curriculumdata/curriculum/';
$CFG->avatar_root               = dirname(__FILE__).'/../curriculumdata/avatar/'; 
$CFG->subjects_root             = dirname(__FILE__).'/../curriculumdata/subjects/'; 
$CFG->solutions_root            = dirname(__FILE__).'/../curriculumdata/solutions/'; 
$CFG->backup_url                = dirname(__FILE__).'/../curriculumdata/backups/';//URL for backups 
$CFG->sql_backup_root           = dirname(__FILE__).'/../curriculumdata/backups/sql/';
$CFG->demo_root                 = dirname(__FILE__).'/../curriculumdata/support/demo/';//URL for backups 

$CFG->request_url               = implode('/', array_slice(explode('/', 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']), 0, -1)).'/';

$CFG->data_root                 = $CFG->request_url.'../curriculumdata/';
$CFG->webservice_root           = $CFG->request_url.'../webservice/';
$CFG->media_root                = $CFG->request_url.'assets/';
$CFG->support_url               = $CFG->data_root.'../curriculumdata/support/';
$CFG->subjects_url              = $CFG->data_root.'../curriculumdata/subjects/';
$CFG->avatar_url                = $CFG->data_root.'../curriculumdata/avatar/';
$CFG->data_url                  = $CFG->data_root.'../curriculumdata/';
$CFG->web_backup_url            = $CFG->data_root.'../curriculumdata/backups/';
$CFG->logfile                   = $CFG->document_root.'/../../logs/'.$CFG->app_title.($CFG->debug ? '_debug' : 'stable').'.log';

/**
 * Files 
 */
$CFG->avatar_default_file       = 'noprofile.jpg';

/**
 * Standard Values 
 */
$CFG->acc_days                  = 7; 
$CFG->language                  = 'de';
$CFG->paginator_limit           = 30; 
$CFG->standard_role             = 0; 
$CFG->standard_country          = 56; 
$CFG->standard_state            = 11;
$CFG->csv_size                  = 1048576;
$CFG->avatar_size               = 1048576;
$CFG->material_size             = 1048576;
$CFG->timeout                   = 10;

//neu 
$CFG->message_timeout           = 4000; //in millisec.
/**
 * Paginators - do not edit
 */
$CFG->paginator_name            = '';                                           //alle Paginatorlisten die gerade auf der Seite sind
$CFG->paginator_id              = '';                                           //alle markierungen auf Paginatorlisten

/**
 * Get php_info('post_max_size')
 */
$CFG->post_max_size             = ini_get('post_max_size');

// Smarty template engine
$CFG->smarty_template_dir           = dirname(__FILE__).'/templates/';
$CFG->smarty_template_compile_dir   = $CFG->smarty_template_dir.'compiled';
$CFG->smarty_template_cache_dir     = $CFG->smarty_template_dir.'cached'; 


/**
 * Not used yet
 */
//Paths
$CFG->session_save_path         = session_save_path();
$CFG->SESSION_URL               = 'localhost';                                  // URL for session cookie on client
// SSSSHHHHH
$CFG->secret = '$$$SomeSecretsSauce$$$';
$CFG->e64secret = base64_encode($CFG->secret);

/**
 *    Writes a custom message to the log file for debugging purposes.
 *    The message is prepended with a current timestamp and file identifier.
 *
 *    @param string $info_message The message to write to the log file.    
 *
 */
function log_entry($info_message) { 
    global $CFG;
    
    if (!$info_message || trim($info_message) == '') {
        return;
    }
    error_log('PHP '.$CFG->app_title.' Message: ('.$_SERVER['PHP_SELF'].') '.str_replace("\n", "", $info_message));
}
?>