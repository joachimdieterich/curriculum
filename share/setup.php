<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename setup.php - Main setup file. 
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

require_once('config.php');
require_once('libs/Smarty-3.0.6/libs/Smarty.class.php');
require_once('libs/Smarty-3.0.6/libs/SmartyPaginate.class.php');
/**
 * globals
 * @global object $CFG
 * @name $CFG
 */
global $CFG;

/**
 * Holds the user table record for the current user. 
 *
 * $USER is stored in the session.
 *
 * Items found in the user record:
 *  $USER->id           
 *  $USER->username     
 *  $USER->firstname    
 *  $USER->lastname     
 *  $USER->last_login   
 *  $USER->role_id      
 *  $USER->role_name    
 *  $USER->avatar    
 *  $USER->language   
 * 
 * @global object $USER
 * @name $USER
 */
global $USER;

/**
 * A central store of information about the current page we are
 * generating in response to the user's request.
 *
 * Items found in the page record:
 *  $PAGE->id           
 *  $PAGE->title          
 *  $PAGE->url              full current url             
 *  $PAGE->php              current script file        
 *  $PAGE->controller       url of controller file
 *  $PAGE->action           php controller
 *  $PAGE->curriculum       current curriculum
 *  $PAGE->login            see password.php 
 * @global curriculum_page $PAGE
 * @name $PAGE
 */
global $PAGE, $CFG;

/**
 * Holds the institution table record for the current users institution 
 */
global $INSTITUTION;

/**
 * The current curriculum. An alias for $PAGE->curriculum.
 * @global object $CURRICULUM
 * @name $CURRICULUM
 */
global $CURRICULUM;

/**
 * @global object $LOG
 * @name $LOG
 */
global $LOG;

/*
 * Database setup
 */
$conn = mysql_connect($CFG->db_host,$CFG->db_user,$CFG->db_password);
if ($conn){ // if connected - else "install mode"
    mysql_select_db($CFG->db_name);
}

/*
 *  Configure Timezone
 */
date_default_timezone_set('Europe/Berlin');

/*
 *  Load classes 
 */
include_once('include.php');

/**
 * autoloader
 * @param string $className 
 */
function __autoload($className)
{
    include_once('classes/'.$className.'.class.php');
}

/*
 *  configure error reporting and PHP stuff
 */
ini_set('error_reporting', ($CFG->debug ? E_ALL : 0));
ini_set('display_errors', ($CFG->debug ? '1' : '1'));
ini_set('log_errors', ($CFG->debug ? '1' : '1'));
ini_set('error_log', $CFG->logfile);

/*
 *  use W3C-conforming URLS when parameters are appended
 */
ini_set('arg_separator.output', '&amp');

/*
 *  fall back to using URL for session ID when cookies disabled
 */
ini_set('session.use_trans_sid', '1');

/*
 *  Setup Session ... $$$ Uncomment for auth
 */
// session_set_cookie_params(0 , '/', $CFG->SESSION_URL);
session_start();

/*
 *  set up smarty template engine
 */
global $TEMPLATE;
$TEMPLATE = new Smarty();
$TEMPLATE->template_dir = $CFG->smarty_template_dir;
$TEMPLATE->compile_dir = $CFG->smarty_template_compile_dir;
$TEMPLATE->cache_dir = $CFG->smarty_template_cache_dir;

/**
 * TinyMCE
 */
$TEMPLATE->assign('tiny_mce', true);    //activate TinyMCE


/*
 *  assign server variables to base template
 */
$TEMPLATE->assign('BASE_URL', $CFG->BASE_URL);
$TEMPLATE->assign('request_url', $CFG->request_url);
$TEMPLATE->assign('media_url', $CFG->media_root);
$TEMPLATE->assign('avatar_url', $CFG->avatar_url);
$TEMPLATE->assign('support_url', $CFG->support_url);
$TEMPLATE->assign('subjects_url', $CFG->subjects_url);
$TEMPLATE->assign('template_url', $CFG->smarty_template_dir);
$TEMPLATE->assign('data_url', $CFG->data_url);

/*
 *  assign app variables to base template
 */
$TEMPLATE->assign('debug', $CFG->debug);
$TEMPLATE->assign('app_footer', $CFG->app_footer);
$TEMPLATE->assign('app_title', $CFG->app_title);


/*
 * 
 */
$TEMPLATE->assign('message_timeout', $CFG->message_timeout);
/*
 * PHP-INFO variables
 */
$TEMPLATE->assign('post_max_size', $CFG->post_max_size);
?>