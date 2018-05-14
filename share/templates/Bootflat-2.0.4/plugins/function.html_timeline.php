<?php
/*
 *     Smarty plugin
 * 
 * -------------------------------------------------------------
 * Smarty {html_timeline} plugin
 * File:        function.timeline.php
 * Type:        function
 * Name:        timeline
 * Description: Timeline renderer.
 *
 * -------------------------------------------------------------
 * @license GNU Public License (GPL)
 *
 * -------------------------------------------------------------
 * 
 */

function smarty_function_html_timeline($params, $template) {
    global $USER;
    require_once(dirname(__FILE__) . '/function.paginate_first.php');
    require_once(dirname(__FILE__) . '/function.paginate_order.php');
    require_once(dirname(__FILE__) . '/function.paginate_prev.php');
    require_once(dirname(__FILE__) . '/function.paginate_middle.php');
    require_once(dirname(__FILE__) . '/function.paginate_next.php');
    require_once(dirname(__FILE__) . '/function.paginate_last.php');
    foreach($params as $_key => $_val) {
        switch ($_key) {
            case 'id':          $id                 = $_val;
                break;
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
    /* Curriculum selector */
    $_html_result .= '<div class="btn-group pull-right">
                        <button type="button" class="btn btn-default fa fa-th dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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

/* Timeline */
$_html_result .= '<div class="row"><div class="col-md-12"><div class="timeline" ';
    if (isset($table_id['id'])){        
        $_html_result .= 'id="'.$table_id['id'].'"';
    } 
    $_html_result .='><dl>';
    
    /* generate list of all ids on the current page */
    //foreach ($values as $_val){ $list[] = $_val->id; }
    $p_date  = '';
    $i = 0;
    foreach ($values as $_val){     
        if ($p_date != date("d.m.Y", strtotime($_val->creation_time))){ //only print time label if last artefact timestamp neq this timestamp
            $_html_result .=   '<dt>
                                <input class="bg-gray " style="text-align: center;border-radius: 4px;width:100%;" id="cb_datepicker" data-toggle="tooltip" title="Start-Datum wählen" data-provide="datepicker" data-date-format="dd.mm.yyyy" onchange="location.href=\'index.php?action=courseBook&date=\'+this.value;" 
                                    value="'.date("d.m.Y", strtotime($_val->creation_time)).'"</></dt>';
            $p_date = date("d.m.Y", strtotime($_val->creation_time));
        }
        if ($i % 2 == 1){ $pos = 'pos-left'; } else { $pos = 'pos-right'; }
        $_html_result .= '<dd class="'.$pos.' clearfix">
                             <div class="circ"></div>
                             <div class="time">'.date("d M", strtotime($_val->creation_time)).'</div>
                <div class="events">
                  <div class="events-body">
                  <h4 class="events-heading"><a href="#">';
                  if (isset($_val->curriculum)){
                    $_html_result .=  $_val->curriculum;
                  }
        $_html_result .= '</a> '.$_val->creator.'
                  <span class="pull-right fa fa-trash-o" onclick="del(\'courseBook\','.$_val->id.');"></span>
                  <span class="pull-right fa fa-print" onclick="processor(\'print\',\'courseBook\','.$_val->id.');"></span>
                  <span class="pull-right fa fa-edit" onclick="formloader(\'courseBook\',\'edit\','.$_val->id.');"></span>
                  </h4>
                  <h5>'.$_val->topic.'</h5> '.$_val->description;
        $_html_result     .=    Render::todoList($_val->task, 'coursebook', $_val->id);
        if (checkCapabilities('absent:update', $USER->role_id, false)){
            $_html_result .=    Render::absentListe($_val->absent_list, 'coursebook', $_val->id);
        }
        $_html_result     .= ' </div> <!-- ./Eventsbody -->
                                </div>
                               </dd>';
        $i++;
    }       
    
    $_html_result .= '<dt><i class="fa fa-calendar-plus-o bg-gray" onclick="formloader(\'courseBook\',\'new\');"></i></dt>'
                  .  '</dl><!-- ./timeline -->'
            . '</div><!--col-->'
            . '</div><!--row-->';
    $_html_result .= '<div class="btn-group pull-right" role="group" aria-label="...">'
        .smarty_function_paginate_first(array('id' => $id), $template, true).' '
        .smarty_function_paginate_prev(array('id' => $id), $template, true).' '
        .smarty_function_paginate_middle(array('id' => $id, 'format' => 'page'), $template, true).' '
        .smarty_function_paginate_next(array('id' => $id), $template, true). ' '
        .smarty_function_paginate_last(array('id' => $id), $template, true). '
      </div>';
   
    $_html_result .= '<span class="pull-left">Eintrag '.SmartyPaginate::getCurrentItem($id).'-'.SmartyPaginate::getLastItem($id).' von '.SmartyPaginate::getTotal($id).' ';
        if (isset($url)){
        $_html_result .= '<input style="width:40px; text-align:right; margin-bottom:2px;" name="p_search" type="text" value="'.SmartyPaginate::getLimit($id).'"  onkeydown="if (event.keyCode == 13) {event.preventDefault(); window.location.href = \''.$url.'&paginator='.$id.'&paginator_limit=\'+this.value;}"> Einträge / Seite ';
    }
    return $_html_result;
}