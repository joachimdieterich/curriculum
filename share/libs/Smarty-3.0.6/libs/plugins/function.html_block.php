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
            case 'blockdata':  $blockfunction =   $_val->block.'_block';
                               $_html = RENDER::$blockfunction($params);
                break;
            default: break;
        } 
    } 
    
     return $_html;
}