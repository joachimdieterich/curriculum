<?php
/*
 *     Smarty plugin
 * 
 * -------------------------------------------------------------
 * Smarty {html_paginator} plugin
 * File:        function.html_paginator.php
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
    
    
    require_once(dirname(__FILE__) . '/function.paginate_first.php');
    require_once(dirname(__FILE__) . '/function.paginate_order.php');
    require_once(dirname(__FILE__) . '/function.paginate_prev.php');
    require_once(dirname(__FILE__) . '/function.paginate_middle.php');
    require_once(dirname(__FILE__) . '/function.paginate_next.php');
    require_once(dirname(__FILE__) . '/function.paginate_last.php');
    foreach($params as $_key => $_val) {
        switch ($_key) {
            case 'id':          $id    = $_val;
                break;
            case 'title':       $p_title = $_val; 
                break;
            default: break;
        } 
    } 
    if (SmartyPaginate::getView($id) == 'widget'){
        require_once(dirname(__FILE__) . '/function.widget_paginator.php');
        return smarty_function_widget_paginator($params, $template);
    }
    SmartyPaginate::setTitle($p_title, $id);
    $url        = SmartyPaginate::getUrl($id);                    // get url
    $values     = SmartyPaginate::_getData($id);                  // get values
    $keys       = get_object_vars((object)$values[0]);
    $width      = SmartyPaginate::getWidth($id);                  // get width;
    $config     = SmartyPaginate::_getConfig($id);                // get config
    if (isset($config['p_options'])){
        $keys['p_options']     = $config['p_options'];  
        if (isset($config['t_config'])){                          // get t_config if is set
            foreach($config['t_config'] as $k => $v){
                $$k = $v;   
            }
        }
    }
    
    $selected_id = SmartyPaginate::_getSelection($id);           // get selected ids
    if (is_string($selected_id)){ 
        $selected_id = array();                                  // hack to prevent error_logs if selected_id is not set 
    } else {
        if (is_array($selected_id)){
            if(($del = array_search('none', $selected_id)) !== false) { // to get proper counting
                 unset($selected_id[$del]);  
            }
        }
    }
    if (!isset($config['p_search'])){$config['p_search'] = '';} //fallback
    SmartyPaginate::setSearchField($config['p_search'], $id);
    if (!isset($values) AND !SmartyPaginate::_getSearch($id)){ return 'Keine Datensätze vorhanden.'; } 
    $html  = '<div id="container_'.$id.'" class="row"><div class="'.$width.' top-buffer" >';
    if (null !== SmartyPaginate::_getSearch($id)){
        $html .= '<div class="btn-group pull-left">
                    <button type="button" class="btn btn-default fa fa-times-circle-o"  onclick="processor(\'config\',\'paginator_reset\',\''.$id.'\');"> Suche: <i>'.SmartyPaginate::_getSearch($id).'</i></button>
                  </div>';
    } else {
        $html .= '<div class="col-sm-34 col-xs-9 btn-group pull-left" ><div class="input-group">
          <input type="text" name="q" id="q" class="form-control" placeholder="Suche..." onkeydown="if (event.keyCode == 13) {event.preventDefault(); processor(\'config\',\'paginator_search\',\''.$id.'\',{\'order\':\'\',\'reload\':\'true\',\'search\':this.value});}">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat" onclick="processor(\'config\',\'paginator_search\',\''.$id.'\',{\'order\':\'\',\'reload\':\'true\',\'search\':document.getElementById(\'q\').value});"><i class="fa fa-search"></i>
                </button>
              </span>
        </div></div>';
    }
    
    /* Column controller */
    $html .= '<div class="btn-group pull-right">';
    if (isset($config['p_widget'])){
        $html .= '<button type="button" class="btn btn-default fa fa-th" onclick="processor(\'config\',\'paginator_view\',\''.$id.'\',{\'view\':\'widget\'});"></button>';
    }
                
     $html .= ' <button type="button" class="btn btn-default fa fa-bars dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
                            default: $html .= '  <li class="row" onclick="processor(\'config\',\'paginator_col\',\''.$id.'\',{\'column\':\''.$_key.'\'});"><label class="col-md-12 col-xs-offset-1"><input class="col-xs-1 " type="checkbox" id="cb_'.$id.'_col_'.$_key.'" ';
                                if (SmartyPaginate::getColumnVisibility($_key,$id)){
                                    $html .='checked';
                                }
                                if (!is_array($config[$_key])){
                                    $html .= '><span class="col-xs-10" style="font-weight:400;">'.$config[$_key].'</span></label></li>';
                                }
                                break;
                        }
                    } 
                }     
    $html .= '</ul>
              </div><br>'; 

    /* Table */
    if (isset($values)){
        $html .= '<div class="clearfix"><br><table class="table table-bordered table-striped dataTable" role="grid" ';
        if (isset($table_id['id'])){        
            $html .= 'id="'.$table_id['id'].'"';
        } 
        $html .='>';

        /* generate list of all ids on the current page */
        foreach ($values as $_val){ $list[] = $_val->id; }

        /* Table Header */
        $html .='<tr>';
        foreach ($keys as $_key => $_val){
            if ($_key == 'id'){
                $html .= '<td style="width:25px">';
                if ($config[$_key] == 'checkbox'){ // column id
                    $html .= '<input type="checkbox" id="'.$id.'_allonPage" ';
                    if (isset($page['onclick'])){
                        $html .= 'onclick="'.$page['onclick'].'"';
                    } else {
                        $html .= 'onclick="checkrow(\'page\', \''.$id.'\', \'true\');"';
                    }
                    $html .= '>';
                }
                $html .= '</td>';
            } else if ($_key == 'p_options'){
                $html .= '<td><i class="fa fa-print pull-right margin-r-5" style="padding-top:5px" onclick="processor(\'print\',\'paginator\',\''.$id.'\')"></i></td>';
            } else {
                if (array_key_exists($_key, $config) AND SmartyPaginate::getColumnVisibility($_key,$id)){
                    $html .= '<td name="'.$id.'_col_'.$_key.'">'.smarty_function_paginate_order(array('id' => $id, 'key' => $_key, 'text' => $config[$_key], 'search' => $config['p_search']), $template);
                    if (SmartyPaginate::_getOrder($id) == $_key) {
                        $html .= ' <i class="text-primary pull-right fa fa-sort-'.strtolower(SmartyPaginate::_getSort($id)).'"></i>';
                    }
                    
                    $html .=  '</td>';
                }
            }
        }                
        $html .= '</tr>';

         /* Table Content */
        foreach ($values as $_val){     
            foreach ($keys as $k_key => $k_val){
                if ($k_key == 'id'){ 
                    if ($config[$k_key] == 'checkbox'){ // column id
                        $html .= '<tr class="';
                        if (isset($selected_id) && in_array($_val->$k_key, $selected_id)) {
                            $html .='bg-aqua';
                        }
                        if (isset($_val->completed)){ if ($_val->completed == 100) { $html .= ' success text-black'; } } // green background if completed == 100
                        $html .= '" id="row'.$_val->$k_key.'" >';
                        $html .= '<td ';
                        if (isset($checkbox['onclick'])){
                            $html .= 'onclick="'.str_replace('__id__', $_val->$k_key, $checkbox['onclick']).'"';   
                        } else {
                            $html .= 'onclick="checkrow(\''.$_val->$k_key.'\', \''.$id.'\', \'false\');"';
                        }
                        $html .= ' ><input class="checkbox" type="checkbox" id="'.$id.'_'.$_val->$k_key.'" name="id[]" value="'.$_val->$k_key.'"';
                        if (isset($selected_id) && in_array($_val->$k_key, $selected_id)) {
                            $html .=   ' checked ';
                        }
                        $html .= ' /></td>';
                    } else {
                        if ($config[$k_key] == 'no-checkrow'){ // column id
                        $html .= '<tr id="row'.$_val->$k_key.'">'
                                . '<td ><input class="hidden" type="checkbox" id="'.$_val->$k_key.'" name="id[]" value='.$_val->$k_key.' /></td>';
                      } else {
                        $html .= '<tr id="row'.$_val->$k_key.'" onclick="checkrow(\''.$_val->$k_key.'\', \''.$id.'\',  \'true\')">'
                                . '<td ><input class="hidden" type="checkbox" id="'.$_val->$k_key.'" name="id[]" value='.$_val->$k_key.' /></td>';
                      }
                    }
                    $_id = $_val->$k_key; // aktuelle id

                } else if ($k_key == 'p_options'){ // column optionen
                    $html .= '<td>';
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
                                $html .= '<a name="'.$o_key.'" type="button" class="'.$p_options_icon.' pull-right" '; if ($p_options_tooltip != ''){ $html .= "data-toggle='tooltip' title='".$p_options_tooltip."'";} if ($p_options_type != ''){  $html .= $p_options_type.'="'.str_replace('__id__', $_id, $o_val[$p_options_type]).'"></a>'; };
                            }
                        }
                    }
                    $html .= '</td>'; 
                } else { // other columns
                    if (array_key_exists($k_key, $config) AND SmartyPaginate::getColumnVisibility($k_key,$id)){
                        $html .= '<td style=" word-break: break-all;" name="'.$id.'_col_'.$k_key.'" ';
                            if (isset($td['onclick'])){
                                $html .= 'onclick="'.str_replace('__id__', $_id, $td['onclick']).'"';
                            } else {
                                $html .= 'onclick="checkrow(\''.$_id.'\', \''.$id.'\', \'true\');"';
                            }
                        $html .= '>'.$_val->$k_key.'</td>';
                    }
                }
            }
            $html .= '</tr>';
        }       

        $html .= '</table></div>';
        $html .= '<div class="btn-group pull-right" role="group" aria-label="...">'
            .smarty_function_paginate_first(array('id' => $id), $template, true).' '
            .smarty_function_paginate_prev(array('id' => $id), $template, true).' '
            .smarty_function_paginate_middle(array('id' => $id, 'format' => 'page'), $template, true).' '
            .smarty_function_paginate_next(array('id' => $id), $template, true). ' '
            .smarty_function_paginate_last(array('id' => $id), $template, true). '
        </div>';

        $html .= '<span class="pull-left">Zeile '.SmartyPaginate::getCurrentItem($id).'-'.SmartyPaginate::getLastItem($id).' von '.SmartyPaginate::getTotal($id).' ';
            if (isset($url)){
            $html .= '<input style="width:40px; text-align:right; margin-bottom:2px;" name="p_search" type="text" value="'.SmartyPaginate::getLimit($id).'"  onkeydown="if (event.keyCode == 13) {event.preventDefault(); window.location.href = \''.$url.'&paginator='.$id.'&paginator_limit=\'+this.value;}"> Einträge / Seite ';
        }
        if ($config['id'] == 'checkbox'){ // column id
            $html .= ' <input type="checkbox" id="'.$id.'_all" value="all" ';
            if (isset($all['onclick'])){
                $html .= 'onclick="'.$all['onclick'].'"';
            } else {
                $html .= 'onclick="checkrow(\'all\', \''.$id.'\', \'true\');"';
            }
            $html .= '> Alle ';
            $html .= '<span id="span_unselect" class="hidden"><input type="checkbox" id="p_unselect" value="p_unselect" onclick="checkrow(\'none\', \''.$id.'\', \'true\');"> Auswahl aufheben </span>';

            $html .= ' | <span id="count_selection">'.count($selected_id).'</span> Datensätze markiert</span><br>';
        }
    } 
    $html .= '</div></div>';
    return $html;
}