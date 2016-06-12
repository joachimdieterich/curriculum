<?php
/*
 *     Smarty plugin
 * 
 * -------------------------------------------------------------
 * Smarty {html_paginator} plugin
 * File:        function.paginator.php
 * Type:        function
 * Name:        paginator
 * Description: Paginator renderer.
 *
 * -------------------------------------------------------------
 * @license GNU Public License (GPL)
 *
 * -------------------------------------------------------------
 * 
 */

function smarty_function_html_block($params, $template) {
    
    foreach($params as $_key => $_val) {
        switch ($_key) {
            case 'block':      $block                = $_val;
                break;
            case 'configdata':  $configdata          = $_val;
                break;
            case 'visible':     $visible             = $_val;
                break;
          
            default: break;
        } 
    } 
    
    switch ($block) {
        case 'moodle_login':    $_html = RENDER::moodle_login(array('link' => $configdata));
            break;
        case 'html':            $_html = $configdata;
        
        default:
            break;
    }
    
    if ($visible != 0){
        return $_html;
    }
}