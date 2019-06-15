<?php
/**
* Form class
* 
* @abstract This file is part of curriculum - http://www.joachimdieterich.de
* @package core
* @filename form.class.php
* @copyright 2016 Joachim Dieterich
* @author Joachim Dieterich
* @date 2016.04.04 18:05
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
class Form {
    /**
     * Button
     * @param array $params Possible params: id, label, type (default: submit), class (default: btn btn-default), onclick, icon
     * @return string HTML
     */
    public static function input_button ($params){
        $class      = 'btn btn-default';
        $type       = 'submit';
        $onclick    = '';
        $icon       = '';
        $label      = 'submit';
        foreach($params as $key => $val) { $$key = $val; }  
        return "<button id='btn_{$id}' type='{$type}' name='{$id}' class='{$class}' onclick=".$onclick.">
                    <span class='{$icon}'></span> {$label}
                </button>"; //onclick only works if concat without curly braces
                    
    }
    
    /**
     * Infotext
     * @param array $params Possible params: id, label, content, class_left (default: col-sm-3), class_right (default: col-sm-9)
     * @return string HTML
     */
    public static function info($params){
        $label          = '';
        $error          = null;
        $class_left     = 'col-sm-3';
        $class_right    = 'col-sm-9';
        
        foreach($params as $key => $val) { $$key = $val; } 
        return self::form_group($id, $content, $label, $error, $class_left, $class_right);
    }
    
     /**
      * input_text
      * Text input -> todo use $params statt einzelner parameter
      * @param string $id 
      * @param string $label
      * @param mixed $data
      * @param array $error
      * @param string $placeholder
      * @param string $class_left
      * @param string $class_right
      * @return string
      */
    public static function input_text($id, $label, $input, $error, $placeholder ='Text...', $type='text', $min=null, $max=null, $class_left='col-sm-3', $class_right='col-sm-9', $readonly = null, $onchange = null){
        $min_html       = '';
        $max_html       = '';
        $input_html     = '';
        $readonly_html  = '';
        $onchange_html  = '';
        switch($type){
            case 'string': $type = 'text'; break;
            case 'int':    $type = 'number'; break;
            default:       break;
        }
        if ($min) { $min_html .= "min='{$min}'"; } 
        if ($min) { $max_html .= "max='{$max}'"; } 
        if (isset($input)){ $input_html .=  "value='{$input}'"; } 
        if (isset($readonly)) { $readonly_html .=  " readonly "; }
        if (isset($onchange)) { $onchange_html .=  " onchange='{$onchange}'"; } 
        $form = "<input id='{$id}' name='{$id}' type='{$type}' {$min_html} {$max_html} class='form-control' placeholder='{$placeholder}' {$input_html} {$readonly_html} {$onchange_html}/>";
        
        return self::form_group($id, $form, $label, $error, $class_left, $class_right);
    }
    
    public static function input_textarea($id, $label, $input, $error, $placeholder ='Text...', $class_left='col-sm-3', $class_right='col-sm-9'){
        $form  = "<textarea id='{$id}' name='{$id}' class='ckeditor' rows='10' cols='80' style='visibility: hidden; display: none;'>";
        if (isset($input)) { 
            $form .=  $input;  
        } else {
            $form .=  $placeholder;
        }
        $form .= "</textarea>"; 
        return self::form_group($id, $form, $label, $error, $class_left, $class_right);
    }
    
    public static function input_checkbox($id, $label, $input, $error, $type='checkbox', $onclick='', $class_left='col-sm-3', $class_right='col-sm-9'){
        $form = '<input id="'.$id.'" name="'.$id.'" type="'.$type.'"';
        if ($input == true){
            $form .= 'checked="checked"';
        }    
        $form .= ' onclick="'.$onclick.'" />';
        return self::form_group($id, $form, $label, $error, $class_left, $class_right);
    }
    
    public static function input_switch($id, $label, $input, $error, $show_id = false, $class_left='col-sm-3', $class_right='col-sm-9', $onchange = ''){
        $form = '<div id="'.$id.'_form_group" class="form-group '.validate_msg($error, $id, true).'">
                  <label class="control-label '.$class_left.'" for="'.$id.'">'.$label;
        if ($show_id){
            $form .='<br><small>'.$id.'</small>';
        }
        $form .='</label><div class="'.$class_right.'" style="padding-left:85px;">'.validate_msg($error, $id).'
                <input type="checkbox" name="'.$id.'" id="'.$id.'" class="ios-toggle" ';
                if ($input == 1){
                    $form .= ' value="true" checked ';
                } else {
                    $form .= ' value="false" ';
                }
                $form .= ' onclick="switchValue(\''.$id.'\');" ';
                $form .= "onchange='{$onchange}'";
                $form .= '/>
                 <label for="'.$id.'" class="checkbox-label" data-off="nicht erlaubt" data-on="erlaubt"></label>'; 
        $form .= '</div></div>';  

        return $form;
    }
    /**
     * 
     * @param type $id
     * @param type $label
     * @param type $select_data
     * @param type $select_label field or comma seperated fields
     * @param type $select_value
     * @param type $input
     * @param type $error
     * @param type $onchange
     * @param type $placeholder
     * @param type $class_left
     * @param type $class_right
     * @return string
     */
    public static function input_select($id, $label, $select_data, $select_label, $select_value, $input, $error, $onchange= '', $placeholder ='---', $class_left='col-sm-3', $class_right='col-sm-9', $disabled = '', $css_group = ''){
        $limiter        = ' '; //todo: $params array 
        $select_entries = $placeholder; 
        $form = '<select id="'.$id.'" name="'.$id.'" class="select2 form-control" onchange="'.$onchange.'" '.$disabled.'>';
            if (count($select_data) > 0){
                  if ($placeholder != '---'){
                     $form .= '<option value="false">'.$placeholder.'</option>';
                  }
                 foreach ($select_data as $value) {
                     if (strpos($select_label, ',')){ // more than one field in select_label
                         foreach (explode(', ', $select_label) as $f) {
                             $fields[]  = $value->$f;
                         }
                         $select_entries = implode($limiter, $fields);
                         unset($fields);
                     } else {
                         $select_entries  = $value->$select_label;
                     }
                     $form .= '<option label="'.$select_entries.'" value="'.$value->$select_value.'"'; if ($input == $value->$select_value){ $form .= 'selected="selected"'; } $form .= '>'.$select_entries.'</option>';
                 }
            } else {
                $form .= '<option label="'.$placeholder.'" value="false">'.$placeholder.'</option>';
            }
        $form .= '</select> ';
        if ($disabled != ''){       
            $form .= '<input type="hidden" name="'.$id.'" value="'.$input.'" />'; //to get value on submit
        }
        
        return self::form_group($id, $form, $label, $error, $class_left, $class_right, $css_group);
    }
    public static function input_select_multiple($params){
        /*$id, $label, $select_data, $select_label, $select_value, $input*/
        $error          = null;
        $onchange       = '';
        $placeholder    = '---';
        $select_entries = $placeholder; 
        $class_left     = 'col-sm-3';
        $class_right    = 'col-sm-9';
        $height         = '135px';
        $limiter        = ' ';
        foreach($params as $key => $val) { $$key = $val; }
        
        $form = '<select multiple id="'.$id.'" name="'.$id.'[]" class="select2 form-control" style="height:'.$height.';" onchange="'.$onchange.'">';
                        if (count($select_data) > 0 AND gettype($select_data) != "boolean"){
                            foreach ($select_data as $value) {
                                if (strpos($select_label, ',')){ // more than one field in select_label                   
                                    foreach (explode(', ', $select_label) as $f) {
                                        $fields[]  = $value->$f;
                                    }
                                    $select_entries = implode($limiter, $fields);
                                    unset($fields);
                                } else {
                                    $select_entries  = $value->$select_label;
                                }
                                $form .= '<option label="'.strip_tags($select_entries).'" value="'.$value->$select_value.'"'; 
                                    if (is_array($input)){
                                        if (in_array($value->$select_value, $input)){ $form .= 'selected="selected"'; } 
                                    }
                                $form .= '>'.strip_tags($select_entries).'</option>';
                            }
                        } else {
                           $form .= '<option label="'.$placeholder.'">'.$placeholder.'</option>';
                        }
        $form .= '</select><span class="pull-right" >Alles auswählen <input type="checkbox" id="'.$id.'_checkbox" onclick="selectAll(\''.$id.'\');" ></span>';
        
        return self::form_group($id, $form, $label, $error, $class_left, $class_right);
    }
    
    public static function input_color($params){
        $label          = 'Farbe';
        $rgb            = '#3cc95b';
        $class_left     ='col-sm-3'; 
        $class_right    ='col-sm-9';
        foreach($params as $key => $val) {
            $$key = $val;
        }
        $form = "<div id='colorpicker' class='input-group color-picker colorpicker-element' >
                    <input id='{$id}' name='{$id}' type='text' class='form-control' value='{$rgb}'>
                    <div class='input-group-addon'>
                      <i style='background-color: {$rgb};'></i>
                    </div>
                </div>";
        return self::form_group($id, $form, $label, $error, $class_left, $class_right);
    }
    
    public static function input_date($params){
        $type           = 'date';
        $class_left     ='col-sm-3'; 
        $class_right    ='col-sm-9';
        foreach($params as $key => $val) {
            $$key = $val;
        }
        switch ($type) {
            case 'date':    $icon = 'fa-calendar'; break;
            case 'time':    $icon = 'fa-clock-o';  break;
            default:        $icon = 'fa-calendar'; break;
        }
        
        $form = "<div class='input-group'>
                    <div class='input-group-addon'><i class='fa {$icon}'></i></div>
                <input id='{$id}' name='{$id}' type='text' class='form-control datepicker' data-provide='datepicker' value='{$time}'>
                </div>";
        return self::form_group($id, $form, $label, $error, $class_left, $class_right);
    }
    
    
    public static function input_dropdown($id, $label, $select_data, $select_label, $select_value, $input, $error, $onclick= '', $placeholder ='---'){
        $class          =  "notifications-menu";
        $count_semester = count($select_data);
        $form = '<li id="'.$id.'_dropdown" class="dropdown '.$class.'" data-toggle="tooltip" data-placement="bottom" title="Schule/Zeitraum wechseln">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="padding: 15px 8px 15px 8px;">
                      <i class="fa fa-history"></i>';
                      //<span class="label label-warning">'.$count_semester.'</span>
        $form .= '  </a>
                    <ul class="dropdown-menu">
                      <li class="header">Sie haben '.$count_semester.' Lernzeiträume</li>
                      <li>
                        <!-- inner menu: contains the actual data -->
                        <ul class="menu">';
        if (count($select_data) > 0){
            foreach ($select_data as $value) {
                if (strpos($select_label, ',')){ // more than one field in select_label
                list ($field1, $field2) = explode(', ', $select_label);
                    $label  = $value->$field1. ' '. $value->$field2;
                } else {
                    $label  = $value->$select_label;
                }
                $form .= '<li><a href="#" data-id="'.$value->$select_value.'" onclick="'.$onclick.'"><i class="text-aqua"></i> ';
                                if ($input == $value->$select_value){ 
                                    $form .= '<b>'.$label.'</b>'; 
                                } else {
                                    $form .= $label; 
                                }        
                $form .= '  </a></li>';
            }
       } else {
                $form .= '<li><a href="#"><i class="text-aqua"></i> '.$placeholder.'</a></li>';
       }
                          
        $form .= '      </ul>
                      </li>
                      <li class="footer"><!-- <a href="#">View all</a>--></li>
                    </ul>
                 </li>';
        return $form;          
    }
    
    public static function upload_form($id, $label, $input, $error, $onclick='', $class_left='col-sm-3', $class_right='col-sm-9'){
        $form = '<p id="'.$id.'_fName" class="hidden"></p>
                <p id="'.$id.'_fSize" class="hidden"></p>
                <p id="'.$id.'_fType" class="hidden"></p>
                <div id="'.$id.'_fProgress" class="progress">
                    <div id="'.$id.'_fProgress_bar" class="progress-bar progress-bar-primary progress-bar-striped" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                        <span class="sr-only" id="'.$id.'_fPercent"></span>
                    </div>
                </div>

                <button id="'.$id.'_fUpload" name="fUpload" type="button" value="" class="btn btn-primary pull-right hidden " onclick="uploadFile(\''.$id.'\');">
                    <span class="fa fa-cloud-upload" aria-hidden="true" ></span>'.$label.'
                </button>
                <button id="'.$id.'_fAbort" name="fAbort" type="button" value="" class="btn btn-primary pull-right hidden" onclick="uploadAbort(\''.$id.'\');">
                    <span class="fa fa-times" aria-hidden="true"></span> Abbrechen
                </button>
                <input id="'.$id.'_fSelector" name="file" type="file" class="btn btn-primary col-sm-12" onchange="fileChange(\''.$id.'\');">';
        return self::form_group($id, $form, $label, $error, $class_left, $class_right);
    }
    
    public static function error($params){
        global $USER;
        foreach($params as $key => $val) {
            $$key = $val;
        }
        $html = '<div class="error-page">
            <h2 class="headline text-danger"> 403</h2>
            <div class="error-content"><br>
                <h3><i class="fa fa-warning text-red"></i> Fehlende Berechtigung.</h3>
                <p>Als <strong>'.$USER->role_name.'</strong> verfügen Sie nicht über die erforderliche Berechtigung (<strong>'.$capability.'</strong>).<br>Die Seite <strong>'.$page_name.'</strong> kann nicht angezeigt werden.<br><br>
                    Hier gehts zurück auf die  <a href="index.php?action=dashboard">Startseite</a>.</p>
            </div><!-- /.error-content -->
        </div><!-- /.error-page -->';
        return $html;     
    }
    
    /**
     * parameter
     * h_content
     * title
     * content
     * f_content
     * @param type $params
     */
    public static function modal($params){
        $target         = 'popup';              // default target, will be overwritten if target in $params
        foreach($params as $key => $val) {
            $$key = $val;
        }

        $html = '<div id="'.$target.'" class="modal-dialog" style="overflow-y: initial !important;" >
                    <div id="modal" class="modal-content" ><!-- height is dynamic set by popupFunction() -->
                        <div class="modal-header" onmousedown="dragstart(this)">';
                        if (isset($h_content)){
                            $html .= $h_content;
                        } else {
                            $html .= '<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closePopup(\''.$target.'\');"><span aria-hidden="true">×</span></button>';
                        }
                        $html .= '<h4 class="modal-title">'.$title.'</h4>';
            if (isset($background)){$background = 'background:'.$background.';';} else{$background = '';}
            $html .=   '</div>
                        <div class="modal-body" style="overflow: auto !important; '.$background;
                        if (isset($c_color)){
                            $html .= 'background-color: '.$c_color;
                       }
            $html .=   '"><div class="form-horizontal">
                            '.$content.'
                        </div></div><!-- /.modal-body -->';
                        if (isset($f_content)){
            $html .=    '<div class="modal-footer">';
                            if ($f_content == 'close'){
                                $html .= '<button name="close" type="button" class="btn btn-primary pull-right" onclick="closePopup()">OK</button>';
                            } else {
                                $html .= $f_content;
                            }
            $html .=   '</div>';
                        }
            $html .=   '</div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->';
            if (isset($sub_modal_id)){
                $html .= '<div id="sub_popup" class="modal" onload="popupFunction(this.id);"><div class="modal-dialog"><div class="box"><div class="box-header"><h3 class="box-title">Loading...'.$sub_modal_id.'</h3></div><div class="box-body"></div><div class="overlay"><i class="fa fa-refresh fa-spin"></i></div></div></div></div> <!-- Sub Popup -->';
            }
        return $html;    
    }
    
    public static function info_box($params){
        global $CFG;
        foreach($params as $key => $val) {
            $$key = $val;
        }
       
        $html  = ' <div id="material_'.$id.'" class="info-box">';
        if (isset($subjects)){
            $html .= '<span name="subjects" style="display: none"><ul>';
            foreach ($subjects as $subject) {
                $html .= '<li class="subjectItem">'.$subject.'</li>';
            }
            $html .= '</ul></span>';
        }
        if (isset($preview)){
            $html .= '<span class="info-box-icon bg-aqua"><div id="modal-preview" style="position:relative;height:100%;width:100%;background: url(\''.$preview.'\') center ;background-size: cover; background-repeat: no-repeat;">';
            if (isset($license_icon)){
                $html .= '<img style="position:absolute;bottom:0px; right:0px;" src="'.$license_icon.'" height="25"/>';
            }
            $html .= '</div></span>'; //pull-left --> overrides align: center to top
        } else {
           $html .= RENDER::thumb(array('file_list' => $id, 'format' => 'thumb', 'width' => '90px', 'height' => '90px'));
        }
        /* Box content */
        $html .= '<div class="info-box-content"><div class="pull-right">';
        if (isset($url)){
            $html .= '<button class="btn btn-box-tool"><a href="'.$url.'" target="_blank"><i class="fa fa-download"></i></a></button>';
        }
        if (isset($delete)){
            $html .= '<button class="btn btn-box-tool" onclick="processor(\'delete\', \'file\', '.$id.', { \'reload\': \'false\', \'callback\': \'replaceElementByID\', \'element_Id\': \'material_'.$id.'\'});"><i class="fa fa-trash"></i></button>';
        }
        $html .= '</div>';
        $html .= '<span class="info-box-text">';
        if (isset($player)){
            $html .= $player.'<br>';
        }
        if (!isset($url)){
            $url = '';
        }
        $html .= '<a href="'.$url.'" target="_blank" ';
        if ($onclick != false){
            $html .= 'onclick="formloader(\'preview\',\'file\','.$id.');'.$onclick.';"';
        }
        $html .= '>'.$title.'</a></span><span>'.$description.'</span> ';
        $html .= $footer.' </div></div>'; // .info-box-content // .info-box
        return $html;   
    }
    
    private static function form_group($id, $content, $label = '', $e = null, $css_l = 'col-sm-3', $css_r = 'col-sm-9', $css_group = '') {
        if ($css_l == '' AND $css_r = ''){
            return $content; //nur das Input-Element wird ausgegeben.
        } else {
            $form =  "<div id='{$id}_form_group' class='form-group " . $css_group . " " . validate_msg($e, $id, true) . "'>";
            if ($css_l != ''){ // if left class is empty no label is set
                $form .= "<label class='control-label {$css_l}' for='{$id}'>{$label}</label>";
            }
            $form .= "<div class='{$css_r}'>".validate_msg($e, $id)."{$content}</div></div>";
            return $form; 
        }    
    }
    
}
