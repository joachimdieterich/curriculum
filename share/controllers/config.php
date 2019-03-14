<?php
/**
*  This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename schooltype.php
* @copyright 2019 Joachim Dieterich
* @author Joachim Dieterich
* @date 2019.01.25 07:51
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
global $USER, $TEMPLATE;
$TEMPLATE->assign('page_title', 'Einstellungen');
$TEMPLATE->assign('breadcrumb',  array('Einstellungen' => 'index.php?action=config'));

$TEMPLATE->assign('error', null);

$config = new Config();
$TEMPLATE->assign('cf_user', $config->get('user', null, $_SESSION['CONTEXT']['userFiles']->context_id, $USER->id));
$TEMPLATE->assign('cf_system', $config->get('global', null, $_SESSION['CONTEXT']['config']->context_id, 0));

$cf_global_plugins = array();
foreach ($CFG->settings as $type_key => $val) {
    if (is_object($val)){
        foreach ($val as $plugin_key => $plugin) {
            $plug                   = new stdClass();
            $plug->name             = $plugin_key;
            $plug->type             = $type_key;
            $plug->config           = $config->load_plugin_config($type_key.'/'.$plugin_key);
            $cf_global_plugins[]    = clone $plug;
            $TEMPLATE->assign('f_'.$plugin_key,  NULL);
        }
    }
    
}    
//error_log(json_encode($cf_global_plugins));
if (!empty($plug->config)){
    $TEMPLATE->assign('cf_global_plugins', $cf_global_plugins);
    
}
//Set current Tag
if(isset($_SESSION['PAGE']->config['tab'])){
    $TEMPLATE->assign($_SESSION['PAGE']->config['tab'], true);
} else {
    $TEMPLATE->assign('f_user', true);
}

