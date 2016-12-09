<?php
/*
 *     Smarty plugin
 * 
 * -------------------------------------------------------------
 * Smarty {html_paginator} plugin
 * File:        function.widget_paginator.php
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

function smarty_function_widget_paginator($params, $template) {
    global $CFG;
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
    if (SmartyPaginate::getView($id) == 'table'){
        require_once(dirname(__FILE__) . '/function.html_paginator.php');
        return smarty_function_html_paginator($params, $template);
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
    if (isset($config['p_widget'])){
        $keys['p_widget']     = $config['p_widget']; 
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
    SmartyPaginate::setSearchField($config['p_search'], $id);
    if (!isset($values) AND !SmartyPaginate::_getSearch($id)){ return 'Keine Datensätze vorhanden.'; } 
    $html  = '<div class="row"><div class="'.$width.' top-buffer" style="padding:0;">';
    if (null !== SmartyPaginate::_getSearch($id)){
        $html .= '<div class="col-sm-3 btn-group pull-left">
                    <button type="button" class="btn btn-default fa fa-times-circle-o" onclick="processor(\'config\',\'paginator_reset\',\''.$id.'\');"> Suche: <i>'.SmartyPaginate::_getSearch($id).'</i></button>
                  </div>';
    } else {
        $html .= '<div class="col-sm-3 col-xs-8 btn-group pull-left" ><div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Suche..." onkeydown="if (event.keyCode == 13) {event.preventDefault(); processor(\'config\',\'paginator_search\',\''.$id.'\',{ \'order\':\'\',\'search\':this.value});}">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat" ><i class="fa fa-search"></i>
                </button>
              </span>
        </div></div>';
    }
    
    /* Column controller */
    $html .= '<div class="btn-group pull-right" style="padding-right:15px;">';
    
    $html .= '<button type="button" class="btn btn-default fa fa-table" onclick="processor(\'config\',\'paginator_view\',\''.$id.'\',{\'view\':\'table\'});"></button>';
    
    $html .= ' <button type="button" class="btn btn-default fa fa-th dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
                                $html .= '><span class="col-xs-10" style="font-weight:400;">'.$config[$_key].'</span></label></li>';
                                break;
                        }
                    } 
                }     
    $html .= '</ul>
              </div><br><br>'; 

    /* Table */
    if (isset($values)){
        foreach ($values as $_val){ $list[] = $_val->id; }
        foreach ($values as $_val){
            $opt = '';
            foreach ($keys as $k_key => $k_val){
                if ($k_key == 'id'){
                     $_id = $_val->$k_key; // aktuelle id
                } else if ($k_key == 'p_options'){ // column optionen
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
                                $opt[$o_key] = '<a name="'.$o_key.'" type="button" style="color:#FFF;" class="'.$p_options_icon.'" '; if ($p_options_tooltip != ''){ $opt[$o_key] .= "data-toggle='tooltip' title='".$p_options_tooltip."'";} if ($p_options_type != ''){  $opt[$o_key] .= $p_options_type.'="'.str_replace('__id__', $_id, $o_val[$p_options_type]).'"></a>'; };
                            }
                        }
                    }     
                } else if ($k_key == 'p_widget'){ //get widget options
                    foreach ($config['p_widget'] as $w_key => $w_val){
                        if (strpos($w_val, ',')){ // more than one field in select_label
                            foreach (explode(', ', $w_val) as $f) {
                                $fields[]  = $_val->$f;
                            }
                            $$w_key = implode(" | ", $fields);
                            unset($fields);
                        } else {
                            if (isset($_val->$w_val)){ 
                                $$w_key = $_val->$w_val; 
                            } else {
                                $$w_key = $w_val;
                            }
                        }
                    }
                }
            }
            if (!isset($_val->color)){
                $_val->color = '#3c8dbc99'; //todo global color
            }
            $html .= '<div class="col-lg-4 col-md-6 col-sm-12 margin-bottom">
                      <div class="box box-widget widget-user collapsed-box bottom-buffer-20">
                        <!-- Add the bg color to the header using any of the bg-* classes -->
                        <span class="col-xs-12 no-margin" ';
                            if (isset($config['p_widget']['bg_image'])){ 
                                $html .= 'style="background: url('.$CFG->access_id_url.$file_id.') center center;  background-size: cover;"';
                            }
                        $html .= ' ">';
                        $html .= '    <div class="widget-user-header col-xs-12" style="background: '.$_val->color.';"  >
                                <span class="pull-right no-margin text-white-shadow">'.$opt['delete'].'</span>
                                <h3 class="widget-user-username text-white-shadow" >'.$header.'</h3> 
                                <h5 class="widget-user-desc text-white-shadow" >'.$subheader01 .'</h5>
                                <h5 class="widget-user-desc text-white-shadow "  >'.$subheader02.'</h5>
                                <span class="col-xs-12" style="background-color: '.substr($_val->color, 0,7).'; position:absolute; display:block; left:0;right:0;bottom:0;">';
                                if (isset($expand)){ 
                                    $html .= '<a type="button" style="padding:5px;" class="pull-right fa fa-chevron-circle-down text-white-shadow" data-widget="collapse"></a>';
                                }
                                foreach ($opt as $k =>$o) {
                                    if ($k != 'delete'){ // skip delete button 
                                        $html .= '<span style="margin-right:15px;padding:5px;text-shadow: 1px 1px #FF0000;" class="fa">'.$o.'</span>';    
                                    }
                                }
                        $html .= '</span>
                            </div>
                        </span>';
                        if (isset($config['p_widget']['circle_image'])){ 
                            $html .= '<div style="position: absolute;top: 75px;right: 15px;" >';
                            $html .= '  <img class="img-circle img-bordered-sm" style="height:40px;width:40px;" src="'.$CFG->access_id_url.$file_id.'" alt="User Avatar">';
                            $html .= '</div>';
                        }
                if (isset($expand)){     
                    $html .= '<div class="box-footer no-padding" >
                          <ul class="nav nav-stacked">
                            <li style="padding: 10px 15px; overflow:hidden;">'.$expand.'</li>
                          </ul>
                        </div>';
                }
              $html .= '</div><!-- /.widget-user -->
                    </div>
                    <!-- /.col -->';
        }
        
        $html .= '</div>'; //table end
        $html .= '<div class="col-sm-12">';
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
        $html .= ' <input type="checkbox" id="'.$id.'_all" value="all" ';
        if (isset($all['onclick'])){        
            $html .= 'onclick="'.$all['onclick'].'"';
        } else {
            $html .= 'onclick="checkrow(\'all\', \''.$id.'\');"';
        }
        $html .= '> Alle <span id="span_unselect" class="hidden"><input type="checkbox" id="p_unselect" value="p_unselect" onclick="checkrow(\'none\', \''.$id.'\');"> Auswahl aufheben </span>';
        $html .= ' | <span id="count_selection">'.count($selected_id).'</span> Datensätze markiert</span><br>';
    } 
    $html .= '</div>';
    return $html;
}