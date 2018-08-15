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
    
    $_html_result = '<section class="content-header">';
        if (isset($p_title)){
            $_html_result .= '<h1>'.$p_title.'</h1>';
        }
        if (isset($pages)){
                global $CFG, $USER;
                $_html_result .= '<ol class="breadcrumb">';
                if ($USER->role_id == 8 AND $CFG->settings->guest_show_menu == 0) {
                    $_html_result .= '<li><a href="index.php?action=navigator"><i class="fa fa-dashboard"></i>Home</a></li>';
                } else {
                    $_html_result .= '<li><a href="index.php?action=dashboard"><i class="fa fa-dashboard"></i>Home</a></li>';
                }
                foreach ($pages as $key =>$value){
                    $_html_result .= '<li ';
                    if ($value == $p_title){
                        $_html_result .= 'class="active"';
                    }
                    $_html_result .= '><a href="'.$value.'">'.$key.'</a></li>';
                    
                }
                if (isset($help)){
                    $_html_result .= '<li><i class="fa fa-info" type="button" name="help" onclick="curriculumdocs(\''.$help.'\');" data-toggle="tooltip" data-placement="left" title="Curriculum-Wiki">></i></li>';
                }
                $_html_result .= '</ol>';
        }
    $_html_result .= '</section>';


    return $_html_result;
}
