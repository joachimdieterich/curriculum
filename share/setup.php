<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename setup.php - Main setup file. 
* @copyright  2013 Joachim Dieterich  {@link http://www.joachimdieterich.de}
* @author Joachim Dieterich
* @date 2013.03.08 13:26
* @license:  
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
date_default_timezone_set('Europe/Berlin');                         // Zeitzone setzen

include_once('config.php');
include_once('libs/Smarty-3.0.6/libs/Smarty.class.php');
include_once('libs/Smarty-3.0.6/libs/SmartyPaginate.class.php');
include_once('include.php');                                             // Klassen laden
include_once('function.php'); 

global $CFG;
global $USER;                                                       // Nutzerdaten
global $PAGE;                                                       // Daten der aktuellen Seite
global $COURSE;                                                     // Daten des aktuelle Kurses
global $INSTITUTION;                                                // Daten der (letzten --> laut DB-Abfrage) Institution des aktuellen Nutzers --> Es können mehr als eine Institution gegeben sein... optimieren!
global $TEMPLATE;                                                   // Smarty TEMPLATE object
global $CONTEXT;                                                    // Array of contexts objects. You can use $CONTEXT[ID] or $CONTEXT[context] ($_SESSION['CONTEXT'][ID/context] in modals) to get the matching context
global $LICENSE;                                                    // Array of file_context objects. You cna use $LICENSE[ID] (or $_SESSION['LICENSE'] in modals) to get the matching file_context. used for license_icons

session_start();                                                    // Starte Sesseion

/* Load Plugins */
$config = new Config();
if (isset($CFG->db_configured)){
    if (!isset($_SESSION['CFG']->settings)){
        $config->load(); //load additional settings from db
    } else {
        $CFG->settings = $_SESSION['CFG']->settings;
    }
    
    /* load user template config */
    if (isset($_SESSION['USER']->id)){
        $c = $config->get('user', 'template', 'userFiles', $_SESSION['USER']->id);
        if ($c){
            $CFG->settings->template = $c[0]->value;
        }
    }
    /*if (isset($CFG->settings->auth)){
        $CFG->auth = get_plugin('auth',$CFG->settings->auth);
    }*/
}

$TEMPLATE = new Smarty();

/* Load Language Pack */
require '../share/language/'.$CFG->settings->language.'.php';
$TEMPLATE->assign('lang',   $lang);  

$TEMPLATE->assign('tb_param',       $CFG->tb_param);
$TEMPLATE->assign('global_timeout', $CFG->settings->timeout);
$TEMPLATE->assign('message_timeout',$CFG->settings->message_timeout);
$TEMPLATE->assign('post_max_size',  $CFG->post_max_size);
$TEMPLATE->assign('base_url',       $CFG->base_url);
$TEMPLATE->assign('request_url',    $CFG->request_url);
$TEMPLATE->assign('media_url',      $CFG->media_url);
$TEMPLATE->assign('lib_url',        $CFG->lib_url);
$TEMPLATE->assign('access_file',    $CFG->access_file);
$TEMPLATE->assign('access_file_id', $CFG->access_id_url);
$TEMPLATE->assign('avatar_path',    $CFG->avatar_path);
$TEMPLATE->assign('support_path',   $CFG->support_path);
$TEMPLATE->assign('subjects_path',  $CFG->subjects_path);
$TEMPLATE->assign('solutions_path', $CFG->solutions_path);

$TEMPLATE->assign('app_title',      $CFG->app_title);
$TEMPLATE->assign('app_version',    $CFG->version);
$TEMPLATE->assign('app_footer',     $CFG->app_footer);
$TEMPLATE->assign('cfg_guest_login',$CFG->settings->guest_login);
$TEMPLATE->assign('cfg_shibboleth', $CFG->settings->shibboleth);

if (isset($CFG->settings->show_subjectIcon)){
  $TEMPLATE->assign('cfg_show_subjectIcon', $CFG->settings->show_subjectIcon);
} else {
  $TEMPLATE->assign('cfg_show_subjectIcon', "ALWAYS"); // possible values: ALWAYS, NEVER, SELECT
}

if (isset($CFG->settings->login_wallpaper)){
  $TEMPLATE->assign('cfg_login_wallpaper',  $CFG->settings->login_wallpaper);
}else {
  $TEMPLATE->assign('cfg_login_wallpaper',  true);
}

if (!isset($CFG->settings->template)){ 
    //$CFG->settings           = new stdClass(); // now called in config.class.php do not call here, it will delete current settings
    $CFG->settings->template = 'AdminLTE-2.3.7'; // fallback for installation process
} 
if (isset($CFG->settings->guest_usr)){ 
  $TEMPLATE->assign('cfg_guest_usr',$CFG->settings->guest_usr);
} else {
  $TEMPLATE->assign('cfg_guest_usr', false);  
}

$TEMPLATE->template_dir           = dirname(__FILE__).'/templates/'.$CFG->settings->template.'/';
$TEMPLATE->compile_dir            = $TEMPLATE->template_dir.'compiled';
$TEMPLATE->cache_dir              = $TEMPLATE->template_dir.'cached';
$TEMPLATE->assign('template_path',  $TEMPLATE->template_dir);
$CFG->smarty_template_dir_url     = $CFG->base_url.'share/templates/'.$CFG->settings->template.'/';
$TEMPLATE->assign('template_url',   $CFG->smarty_template_dir_url );
$TEMPLATE->addPluginsDir(dirname(__FILE__).'/templates/'.$CFG->settings->template.'/plugins/');   //load smarty plugins for actual template
/*Template render classes*/
include($TEMPLATE->template_dir .'renderer/form.class.php');                  // Form 
include($TEMPLATE->template_dir .'renderer/render.class.php');    

/** Sortierung der Paginatoren */
/* Paginator reset*/
if (filter_input(INPUT_GET, 'order', FILTER_UNSAFE_RAW) && filter_input(INPUT_GET, 'sort', FILTER_UNSAFE_RAW) && filter_input(INPUT_GET, 'paginator', FILTER_UNSAFE_RAW)){
    SmartyPaginate::setSort(filter_input(INPUT_GET, 'order', FILTER_UNSAFE_RAW),filter_input(INPUT_GET, 'sort', FILTER_UNSAFE_RAW), filter_input(INPUT_GET, 'paginator', FILTER_UNSAFE_RAW));
}
/* Paginator limit */
if (filter_input(INPUT_GET, 'paginator_limit', FILTER_UNSAFE_RAW) && filter_input(INPUT_GET, 'paginator', FILTER_UNSAFE_RAW)){
    SmartyPaginate::setLimit(filter_input(INPUT_GET, 'paginator_limit', FILTER_UNSAFE_RAW), filter_input(INPUT_GET, 'paginator', FILTER_UNSAFE_RAW));
}

if (filter_input(INPUT_GET, 'p_reset', FILTER_UNSAFE_RAW) && filter_input(INPUT_GET, 'paginator', FILTER_UNSAFE_RAW)){
    resetPaginator(filter_input(INPUT_GET, 'paginator', FILTER_UNSAFE_RAW)); 
    if (isset($PAGE->url)){
        $TEMPLATE->assign('page_url', removeUrlParameter($PAGE->url, 'p_reset'));
    }
}
