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
    require_once(SMARTY_PLUGINS_DIR . 'function.paginate_first.php');
    require_once(SMARTY_PLUGINS_DIR . 'function.paginate_order.php');
    require_once(SMARTY_PLUGINS_DIR . 'function.paginate_prev.php');
    require_once(SMARTY_PLUGINS_DIR . 'function.paginate_middle.php');
    require_once(SMARTY_PLUGINS_DIR . 'function.paginate_next.php');
    require_once(SMARTY_PLUGINS_DIR . 'function.paginate_last.php');
    foreach($params as $_key => $_val) {
        switch ($_key) {
            case 'id':          $id                 = $_val;
                break;
           /* case 'values':    $values             = $_val;
                                $keys               = get_object_vars((object)$values[0]); break;
            case 'url':         $url                = $_val; break;
            case 'config':      $config    = $_val;
                                if (isset($config['p_options'])){ $keys['p_options']     = $config['p_options']; } break; 
            default: break;*/
        } 
    } 
    $url        = SmartyPaginate::getUrl($id);                    // get url
    $values     = SmartyPaginate::_getData($id);                  // get values
    $keys       = get_object_vars((object)$values[0]);
    $width      = SmartyPaginate::getWidth($id);                    // get width;
    
    $config     = SmartyPaginate::_getConfig($id);               // get config
    if (isset($config['p_options'])){
        $keys['p_options']     = $config['p_options'];  
        if (isset($config['t_config'])){                        // get t_config if is set
            foreach($config['t_config'] as $k => $v){
                //error_log($k.' => '.$v);
                $$k = $v;   
            }
        }
    }
    
    $selected_id = SmartyPaginate::_getSelection($id);                  // get selected ids
   
    if (is_string($selected_id)){ 
        $selected_id = array();                                         // hack to prevent error_logs if selected_id is not set 
    } else {
        if (is_array($selected_id)){
            if(($del = array_search('none', $selected_id)) !== false) {     // to get proper counting
                 unset($selected_id[$del]);  
            }
        }
    }
    
    if (!isset($values) AND !strpos($url, 'paginator_search')){ return 'Keine Datensätze vorhanden.'; } 
   
    $_html_result  = '<div class="'.$width.'">';
    if (null !== SmartyPaginate::_getSearch($id)){
        $_html_result .= '<span class="pull-left"><a class="fa fa-close" href="'.removeUrlParameter($url, array ( 0 => 'paginator', 1 => 'p_reset')).'&paginator='.$id.'&p_reset=true"></a> Suche "'.SmartyPaginate::_getSearch($id).'"</span>';
    }
    /* Column controller */
    $_html_result .= '<div class="btn-group pull-right">
                        <button type="button" class="btn btn-default glyphicon glyphicon-th dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">';
                        foreach ($keys as $_key => $_val){
                            if (isset($config[$_key])){
                                switch ($config[$_key]) {
                                    case '':
                                    case 'checkbox':   
                                    case is_array($config[$_key]):   
                                        break;

                                    default: $_html_result .= '  <li class="row" onclick="toggle_column(\''.$id.'_col_'.$_key.'\');"><label class="col-md-12 col-xs-offset-1"><input class="col-xs-1 " type="checkbox" id="cb_'.$id.'_col_'.$_key.'" checked><span class="col-xs-10" style="font-weight:400;">'.$config[$_key].'</span></label></li>';
                                        break;
                                }
                            } 
                        }     
    $_html_result .= '</ul>
                      </div><br>'; 

/* Table */
$_html_result .= '<div class="row"><div class="clearfix"><br><table class="table table-bordered table-striped dataTable" role="grid" ';
    if (isset($table_id['id'])){        
        $_html_result .= 'id="'.$table_id['id'].'"';
    } 
    $_html_result .='>';
    
    /* generate list of all ids on the current page */
    foreach ($values as $_val){ $list[] = $_val->id; }
    
    /* Table Header */
    $_html_result .='<tr>';
    foreach ($keys as $_key => $_val){
        if ($_key == 'id'){
            $_html_result .= '<td style="width:25px">';
            
            $_html_result .= '<input type="checkbox" id="allonPage" ';
            if (SmartyPaginate::getLimit($id) > SmartyPaginate::getTotal($id)){
                $limit = SmartyPaginate::getTotal($id);
            } else { $limit = SmartyPaginate::getLimit($id); }
            if (count($selected_id) == $limit OR $selected_id == 'all'){ //??? wird 'all' verwendet?
                $_html_result .= ' value="allonPage" ';
                $_html_result .= ' onclick="window.location.assign(\''.removeUrlParameter($url, array ( 0 => 'paginator', 1 => $id.'_sel_id')).'&paginator='.$id.'&'.$id.'_sel_id=';  
                $_html_result .= '\');" checked';
            } else {
                $_html_result .= ' value="none" ';
                $_html_result .= ' onclick="window.location.assign(\''.removeUrlParameter($url, array ( 0 => 'paginator', 1 => $id.'_sel_id')).'&paginator='.$id.'&'.$id.'_sel_id=';    
                $_html_result .= implode(',', $list);  
                $_html_result .= '\');" ';
            } 
            $_html_result .= '></td>';
            
        } else if ($_key == 'p_options'){
            $_html_result .= '<td class="td_options">Optionen</td>';
        } else {
            if (array_key_exists($_key, $config)){
                $_html_result .= '<td name="'.$id.'_col_'.$_key.'">'.smarty_function_paginate_order(array('id' => $id, 'key' => $_key, 'text' => $config[$_key]), $template);
                if ($_key == 'username' OR $_key == 'firstname' OR $_key == 'lastname'  OR $_key == 'email' OR $_key == 'city' OR $_key == 'curriculum' OR $_key == 'description'){ // hack: muss dynamisch gemacht werden
                    $_html_result .=  '<input class="pull-right" id="'.$id.'_col_'.$_key.'_search" name="p_search" style="width:25px;" type="text" value="" onclick="toggle_input_size(\''.$id.'_col_'.$_key.'_search\');"  onblur="toggle_input_size(\''.$id.'_col_'.$_key.'_search\', false);" onkeydown="if (event.keyCode == 13) {event.preventDefault(); window.location.href = \''.removeUrlParameter($url, array ( 0 => 'paginator', 1 => 'paginator_search', 2 => 'order')).'&paginator='.$id.'&order='.$_key.'&paginator_search=\'+this.value;}">'; //event.preventDefault() importent to use paginator in <form>
                } 
                $_html_result .=  '</td>';
            }
        }
    }                
    $_html_result .= '</tr>';
    
    
     /* Table Content */
    foreach ($values as $_val){     
        foreach ($keys as $k_key => $k_val){
            if ($k_key == 'id'){ 
                if ($config[$k_key] == 'checkbox'){ // column id
                    $_html_result .= '<tr class="';
                    /*if (isset($selected_id) && in_array($_val->$k_key, $selected_id) OR $selected_id == 'all'){
                        $_html_result .= 'activecontenttablerow';    
                    } else {
                        $_html_result .= 'contenttablerow';    
                    }*/
                    if (isset($_val->completed)){ if ($_val->completed == 100) { $_html_result .= ' success '; } } // green background if completed == 100
                    $_html_result .= '" id="row'.$_val->$k_key.'" >';
                    $_html_result .= '<td ';
                    if (isset($checkbox['onclick'])){
                        $_html_result .= 'onclick="'.str_replace('__id__', $_val->$k_key, $checkbox['onclick']).'"';   
                    } else {
                        $_html_result .= 'onclick="checkrow(\''.$_val->$k_key.'\', \'id[]\', \''.$id.'\', \''.removeUrlParameter($url, array ( 0 => 'paginator', 1 => $id.'_sel_id')).'&paginator='.$id.'\');"';
                    }
                    $_html_result .= ' ><input class="checkbox" type="checkbox" id="'.$_val->$k_key.'" name="id[]" value="'.$_val->$k_key.'"';
                    if (isset($selected_id) && in_array($_val->$k_key, $selected_id)) {
                        $_html_result .=   ' checked ';
                    }
                    $_html_result .= ' /></td>';
                } else {
                    $_html_result .= '<tr class="contenttablerow" id="row'.$_val->$k_key.'" onclick="checkrow('.$_val->$k_key.')">'
                            . '<td ><input class="hidden" type="checkbox" id="'.$_val->$k_key.'" name="id[]" value='.$_val->$k_key.' /></td>';
                }
                $_id = $_val->$k_key; // aktuelle id
                //$list[] = $_id; //array of ids
                
            } else if ($k_key == 'p_options'){ // column optionen
                $_html_result .= '<td class="td_options">';
                foreach ($config['p_options'] as $o_key => $o_val){
                    $p_options_type         = '';
                    $p_options_icon         = '';
                    $p_options_tooltip      = '';
                    if (array_key_exists('onclick', (array)$o_val)){ $p_options_type    = 'onclick'; }
                    if (array_key_exists('href',    (array)$o_val)){ $p_options_type    = 'href'; }
                    if (array_key_exists('icon',    (array)$o_val)){ $p_options_icon    = $o_val['icon']; }
                    if (array_key_exists('tooltip', (array)$o_val)){ $p_options_tooltip = $o_val['tooltip']; }
                    if (array_key_exists('capability',    (array)$o_val)){
                        if ($o_val['capability'] == true){ 
                            $_html_result .= '<a name="'.$o_key.'" type="button" class="'.$p_options_icon.' pull-right" '; if ($p_options_tooltip != ''){ $_html_result .= "data-toggle='tooltip' title='".$p_options_tooltip."'";} if ($p_options_type != ''){  $_html_result .= $p_options_type.'="'.str_replace('__id__', $_id, $o_val[$p_options_type]).'"></a>'; };
                        }
                    }
                }
                $_html_result .= '</td>'; 
            } else { // other columns
                if (array_key_exists($k_key, $config)){
                    $_html_result .= '<td style=" word-break: break-all;" name="'.$id.'_col_'.$k_key.'" ';
                        if (isset($td['onclick'])){
                            $_html_result .= 'onclick="'.str_replace('__id__', $_id, $td['onclick']).'"';
                        } else {
                            $_html_result .= 'onclick="checkrow('.$_id.')"';
                        }
                    $_html_result .= '>'.$_val->$k_key.'</td>';
                }
            }
        }

        $_html_result .= '</tr>';
    }       
    
    $_html_result .= '</table></div></div>';
    $_html_result .= '<div class="btn-group pull-right" role="group" aria-label="...">'
        .smarty_function_paginate_first(array('id' => $id), $template, true).' '
        .smarty_function_paginate_prev(array('id' => $id), $template, true).' '
        .smarty_function_paginate_middle(array('id' => $id, 'format' => 'page'), $template, true).' '
        .smarty_function_paginate_next(array('id' => $id), $template, true). ' '
        .smarty_function_paginate_last(array('id' => $id), $template, true). '
      </div>';
    
    
    
    //$_html_result .= '<p class="pull-right space-right space-bottom" >'.smarty_function_paginate_prev(array('id' => $id), $template).' '.smarty_function_paginate_middle(array('id' => $id), $template).' '.smarty_function_paginate_next(array('id' => $id), $template).'</p> ';
    /**/
   
    $_html_result .= '<span class="pull-left">Zeile '.SmartyPaginate::getCurrentItem($id).'-'.SmartyPaginate::getLastItem($id).' von '.SmartyPaginate::getTotal($id).' ';
        if (isset($url)){
        $_html_result .= '<input style="width:40px; text-align:right; margin-bottom:2px;" name="p_search" type="text" value="'.SmartyPaginate::getLimit($id).'"  onkeydown="if (event.keyCode == 13) {event.preventDefault(); window.location.href = \''.$url.'&paginator='.$id.'&paginator_limit=\'+this.value;}"> Einträge / Seite ';
    }
    
    // all
    $_html_result .= ' <input class="inputsmall" type="checkbox" id="all" ';
    $_html_result .= ' value="all" ';
    $_html_result .= ' onclick="window.location.assign(\''.removeUrlParameter($url, array ( 0 => 'paginator', 1 => $id.'_sel_id')).'&paginator='.$id.'&'.$id.'_sel_id=';  
    if (count($selected_id) == SmartyPaginate::getTotal($id)){
        $_html_result .= '\');" checked';
    } else {
        $_html_result .= SmartyPaginate::_getSelectAll($id);
        $_html_result .= '\');" ';
    }
    $_html_result .= '> Alle ';
    /* Auswahl aufheben*/
    if (count($selected_id) > 1){
        $_html_result .= '<input class="inputsmall" type="checkbox" id="p_unselect" ';
        $_html_result .= ' onclick="window.location.assign(\''.removeUrlParameter($url, array ( 0 => 'paginator', 1 => $id.'_sel_id')).'&paginator='.$id.'\');"> Auswahl aufheben ';
        $_html_result .= ' | '.count($selected_id).' Datensätze markiert';
    }
    $_html_result .= '</span>';
    /* Hack: es soll user*/
    $_html_result .=  '<input class="invisible" type="checkbox" name="id[]" value="none" checked /></div>';
            //. '<!--/div-->';        // scheint zu viel zu sein
    return $_html_result;
}