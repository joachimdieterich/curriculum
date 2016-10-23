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
include('libs/Smarty-3.0.6/libs/Smarty.class.php');
include('libs/Smarty-3.0.6/libs/SmartyPaginate.class.php');
include('include.php');                                             // Klassen laden
include('function.php'); 

global $CFG;
global $USER;                                                       // Nutzerdaten
global $PAGE;                                                       // Daten der aktuellen Seite
global $COURSE;                                                     // Daten des aktuelle Kurses
global $INSTITUTION;                                                // Daten der (letzten --> laut DB-Abfrage) Institution des aktuellen Nutzers --> Es können mehr als eine Institution gegeben sein... optimieren!
global $TEMPLATE;                                                   // Smarty TEMPLATE object

session_start();                                                    // Starte Sesseion

$TEMPLATE = new Smarty();
$TEMPLATE->template_dir           = $CFG->smarty_template_dir; 
$TEMPLATE->compile_dir            = $CFG->smarty_template_compile_dir;
$TEMPLATE->cache_dir              = $CFG->smarty_template_cache_dir;
$TEMPLATE->addPluginsDir($CFG->smarty_template_dir.'/plugins/');        //enable individual template smarty plugins
$TEMPLATE->assign('tb_param',       $CFG->tb_param);
$TEMPLATE->assign('global_timeout', $CFG->timeout);
$TEMPLATE->assign('message_timeout',$CFG->message_timeout);
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
$TEMPLATE->assign('template_url',   $CFG->smarty_template_dir);

$TEMPLATE->assign('app_title',      $CFG->app_title);
$TEMPLATE->assign('app_version',    $CFG->version);
$TEMPLATE->assign('app_footer',     $CFG->app_footer);

$TEMPLATE->assign('cfg_shibboleth', $CFG->shibboleth);

/* Load Plugins */
$config = new Config();
$CFG->settings = $config->load();
if (isset($CFG->settings->repository)){
    $CFG->repository = get_plugin('repository',$CFG->settings->repository);
}
/*if (isset($CFG->settings->auth)){
    $CFG->auth = get_plugin('auth',$CFG->settings->auth);
}*/


/** Sortierung der Paginatoren */
/* Paginator reset*/
if (filter_input(INPUT_GET, 'order', FILTER_UNSAFE_RAW) && filter_input(INPUT_GET, 'sort', FILTER_UNSAFE_RAW) && filter_input(INPUT_GET, 'paginator', FILTER_UNSAFE_RAW)){
    SmartyPaginate::setSort(filter_input(INPUT_GET, 'order', FILTER_UNSAFE_RAW),filter_input(INPUT_GET, 'sort', FILTER_UNSAFE_RAW), filter_input(INPUT_GET, 'paginator', FILTER_UNSAFE_RAW));
}
/* Paginator limit*/
if (filter_input(INPUT_GET, 'paginator_limit', FILTER_UNSAFE_RAW) && filter_input(INPUT_GET, 'paginator', FILTER_UNSAFE_RAW)){
    SmartyPaginate::setLimit(filter_input(INPUT_GET, 'paginator_limit', FILTER_UNSAFE_RAW), filter_input(INPUT_GET, 'paginator', FILTER_UNSAFE_RAW));
}
/* Paginator search*/
if (filter_input(INPUT_GET, 'order', FILTER_UNSAFE_RAW) && filter_input(INPUT_GET, 'paginator_search', FILTER_UNSAFE_RAW) && filter_input(INPUT_GET, 'paginator', FILTER_UNSAFE_RAW)){
    SmartyPaginate::setSearch(filter_input(INPUT_GET, 'order', FILTER_UNSAFE_RAW),filter_input(INPUT_GET, 'paginator_search', FILTER_UNSAFE_RAW), filter_input(INPUT_GET, 'paginator', FILTER_UNSAFE_RAW));
}
if (filter_input(INPUT_GET, 'paginator', FILTER_UNSAFE_RAW)){
    $selection = filter_input(INPUT_GET, 'paginator', FILTER_UNSAFE_RAW).'_sel_id';
    if (null != filter_input(INPUT_GET, $selection , FILTER_UNSAFE_RAW)){
        SmartyPaginate::setSelection(explode(",",filter_input(INPUT_GET, $selection , FILTER_UNSAFE_RAW)), filter_input(INPUT_GET, 'paginator', FILTER_UNSAFE_RAW));
    } else {
        SmartyPaginate::setSelection('',filter_input(INPUT_GET, 'paginator', FILTER_UNSAFE_RAW));
    }
}

if (filter_input(INPUT_GET, 'p_reset', FILTER_UNSAFE_RAW) && filter_input(INPUT_GET, 'paginator', FILTER_UNSAFE_RAW)){
    resetPaginator(filter_input(INPUT_GET, 'paginator', FILTER_UNSAFE_RAW));   
    $TEMPLATE->assign('page_url', removeUrlParameter($PAGE->url, 'p_reset'));
}