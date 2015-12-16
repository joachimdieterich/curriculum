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

function smarty_function_html_paginator($params, $template) {
    require_once(SMARTY_PLUGINS_DIR . 'function.paginate_order.php');
    require_once(SMARTY_PLUGINS_DIR . 'function.paginate_prev.php');
    require_once(SMARTY_PLUGINS_DIR . 'function.paginate_middle.php');
    require_once(SMARTY_PLUGINS_DIR . 'function.paginate_next.php');
    
    foreach($params as $_key => $_val) {
        switch ($_key) {
            case 'id':      $id         = $_val;
                break;
            case 'values':  $values     = $_val;
                            $keys       = get_object_vars((object)$values[0]);
                break;
            case 'config': $config    = $_val;
                            if (isset($config['p_options'])){
                                $keys['p_options']     = $config['p_options'];                            
                                
                            }
                break; 

            default:
                break;
        } 
    } 

    if (!isset($values)){
        return '<div class="clearfix"></div><p>Keine Datensätze vorhanden.</p>';
    }
    
    $_html_result = '<div class="clearfix"></div><p class="floatright space-right">Datensätze '.SmartyPaginate::getCurrentItem($id).'-'.SmartyPaginate::getLastItem($id).' von '.SmartyPaginate::getTotal($id).' werden angezeigt.</p>';
    $_html_result .= '<table id="contenttable"><tr id="contenttablehead">';
    
    foreach ($keys as $_key => $_val){
        if ($_key == 'id'){
            $_html_result .= '<td></td>';
        } else if ($_key == 'p_options'){
            $_html_result .= '<td class="td_options">Optionen</td>';
        } else {
            if (array_key_exists($_key, $config)){
            $_html_result .= '<td>'.smarty_function_paginate_order(array('id' => $id, 'key' => $_key, 'text' => $config[$_key]), $template).'</td>';
            }
        }
    }          
            
    $_html_result .= '</tr>';
    
    foreach ($values as $_val){     
        foreach ($keys as $k_key => $k_val){
            if ($k_key == 'id'){
                $_html_result .= '<tr class="contenttablerow" id="row'.$_val->$k_key.'" onclick="checkrow('.$_val->$k_key.')">'
                            . '<td><input class="hidden" type="checkbox" id="'.$_val->$k_key.'" name="id[]" value='.$_val->$k_key.' /></td>';
                $_id = $_val->$k_key; // aktuelle id
            } else if ($k_key == 'p_options'){
                $_html_result .= '<td class="td_options">';
                foreach ($config['p_options'] as $o_key => $o_val){
                    if (array_key_exists('onclick', (array)$o_val)){
                        if ($o_val['capability'] == true){
                            $_html_result .= '<a name="'.$o_key.'" type="button" class="'.$o_key.'btn floatright" onclick="'.str_replace('__id__', $_id, $o_val['onclick']).'"></a>';
                        } else {
                            $_html_result .= '<a class="'.$o_key.'btn deactivatebtn floatright"></a>';
                        }
                    }
                    if (array_key_exists('href', (array)$o_val)){
                        $_html_result .= '<a class="'.$o_key.'btn floatright" href="'.str_replace('__id__', $_id, $o_val['href']).'"></a>';
                    }        
                }
                $_html_result .= '</td>'; 
            } else {
                if (array_key_exists($k_key, $config)){
                    $_html_result .= '<td>'.$_val->$k_key.'</td>';
                }
            }
        }

        $_html_result .= '</tr>';
    }       
                  
    $_html_result .= '</table><p class="floatright space-right">'.smarty_function_paginate_prev(array('id' => $id), $template).' '.smarty_function_paginate_middle(array('id' => $id), $template).' '.smarty_function_paginate_next(array('id' => $id), $template).'</p> ';
    $_html_result .= '<div class="clearfix"></div>';
    return $_html_result;
}