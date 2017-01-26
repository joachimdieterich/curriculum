<?php
/*
 *     Smarty plugin
 * 
 * -------------------------------------------------------------
 * Smarty {content_header} plugin
 * File:        function.content_header.php
 * Type:        function
 * Name:        content_header
 * Description: content header renderer.
 *
 * -------------------------------------------------------------
 * @license GNU Public License (GPL)
 *
 * -------------------------------------------------------------
 * 
 */

function smarty_function_content_header($params) {
    
    foreach($params as $_key => $_val) {
        
        switch ($_key) {
            case 'pages':   $pages = $_val;
                break;
            case 'help':    $help  = $_val;
                break;
            case 'p_title': $p_title  = $_val;
                break;
            default:
                break;
        } 
    }                         
    
    $_html_result = '';
        
        if (isset($pages)){
                $_html_result .= '<ol class="breadcrumb breadcrumb-arrow pull-right">';
                $_html_result .= '<li><a href="index.php?action=dashboard">Home</a></li>';
                foreach ($pages as $key =>$value){
                    $_html_result .= '<li ';
                    if ($key == $p_title){
                        $_html_result .= 'class="active"';
                        $_html_result .= '><span>'.$key.'</span></li>';
                    } else {
                        $_html_result .= '><a href="'.$value.'">'.$key.'</a></li>';
                    }
                    
                }
                if (isset($help)){
                    $_html_result .= '<li style="width:20px;padding-left:5px;"><i class="fa fa-info" type="button" name="help" onclick="curriculumdocs(\''.$help.'\');"></i></li>';
                }
                $_html_result .= '</ol>';
        }
    


    return $_html_result;
}