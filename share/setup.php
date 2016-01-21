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
* This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by  
* the Free Software Foundation; either version 3 of the License, or (at your option) any later version.                                   
*                                                                       
* This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of        
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details:                          
*                                                                       
* http://www.gnu.org/copyleft/gpl.html    
*/
date_default_timezone_set('Europe/Berlin');                         // Zeitzone setzen

include_once('config.php');
include('libs/Smarty-3.0.6/libs/Smarty.class.php');
include('libs/Smarty-3.0.6/libs/SmartyPaginate.class.php');
include('include.php');                                             // Klassen laden
include('function.php'); 

global $CFG;
global $USER;                                                       // Nutzerdaten
global $PAGE;                                                       // Daten der aktuellen Seite
global $INSTITUTION;                                                // Daten der (letzten --> laut DB-Abfrage) Institution des aktuellen Nutzers --> Es können mehr als eine Institution gegeben sein... optimieren!
global $TEMPLATE;                                                   // Smarty TEMPLATE object

session_start();                                                    // Starte Sesseion

$TEMPLATE = new Smarty();
$TEMPLATE->template_dir             = $CFG->smarty_template_dir; 
$TEMPLATE->compile_dir              = $CFG->smarty_template_compile_dir;
$TEMPLATE->cache_dir                = $CFG->smarty_template_cache_dir;
$TEMPLATE->assign('tiny_mce',       true);                          // Aktiviere TinyMCE
$TEMPLATE->assign('tb_param',       $CFG->tb_param);
$TEMPLATE->assign('global_timeout', $CFG->timeout);
$TEMPLATE->assign('message_timeout',$CFG->message_timeout);
$TEMPLATE->assign('post_max_size',  $CFG->post_max_size);

$TEMPLATE->assign('base_url',       $CFG->base_url);
$TEMPLATE->assign('request_url',    $CFG->request_url);
$TEMPLATE->assign('media_url',      $CFG->media_url);
$TEMPLATE->assign('lib_url',        $CFG->lib_url);
$TEMPLATE->assign('access_file',    $CFG->access_file);
$TEMPLATE->assign('avatar_path',    $CFG->avatar_path);
$TEMPLATE->assign('support_path',   $CFG->support_path);
$TEMPLATE->assign('subjects_path',  $CFG->subjects_path);
$TEMPLATE->assign('solutions_path', $CFG->solutions_path);
$TEMPLATE->assign('template_url',   $CFG->smarty_template_dir);

$TEMPLATE->assign('app_title',      $CFG->app_title);
$TEMPLATE->assign('app_footer',     $CFG->app_footer);