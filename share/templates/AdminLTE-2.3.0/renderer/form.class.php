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
    
    public static function info($id, $label, $content, $class_left='col-sm-3', $class_right='col-sm-9'){
        $form = '<div class="form-group">
                  <label class="control-label '.$class_left.'" for="'.$id.'">'.$label.'</label>
                  <div class="'.$class_right.'">'.$content.'</div>
                </div>';  

        return $form;
    }
    
     /**
      * Test input -> todo use $params statt einzelner parameter
      * @param string $id 
      * @param string $label
      * @param mixed $data
      * @param array $error
      * @param string $placeholder
      * @param string $class_left
      * @param string $class_right
      * @return string
      */
    public static function input_text($id, $label, $input, $error, $placeholder ='Text...', $type='text', $min=null, $max=null, $class_left='col-sm-3', $class_right='col-sm-9', $readonly = null){
        $form = '<div class="form-group '.validate_msg($error, $id, true).'">
                  <label class="control-label '.$class_left.'" for="'.$id.'">'.$label.'</label>
                  <div class="'.$class_right.'">'.validate_msg($error, $id).'<input id="'.$id.'" name="'.$id.'" type="'.$type.'" ';
                  if ($min) {$form .= 'min="'.$min.'" ';}
                  if ($min) {$form .= 'max="'.$max.'" ';}
        $form .= 'class="form-control" placeholder="'.$placeholder.'" ';
        if (isset($input)) { 
            $form .=  'value="'.$input.'"';  
        } 
        if (isset($readonly)) { 
            $form .=  ' readonly ';  
        } 
        $form .= ' /> </div></div>';  

        return $form;
    }
    
    public static function input_textarea($id, $label, $input, $error, $placeholder ='Text...', $class_left='col-sm-3', $class_right='col-sm-9'){
        $form  = '<div class="form-group '.validate_msg($error, $id, true).'"><label class="control-label '.$class_left.'" for="'.$id.'">'.$label.'</label>
                  <div class="'.$class_right.'">';
        $form .= '<textarea id="'.$id.'" name="'.$id.'" class="ckeditor" rows="10" cols="80" style="visibility: hidden; display: none;">';
        if (isset($input)) { 
            $form .=  $input;  
        } else {
            $form .=  $placeholder;
        }
        $form .= '</textarea>';
        $form .= '</div></div>'; 

        return $form;
    }
    
    public static function input_checkbox($id, $label, $input, $error, $type='checkbox', $onclick='', $class_left='col-sm-3', $class_right='col-sm-9'){
        $form = '<div class="form-group '.validate_msg($error, $id, true).'">
                  <div class="col-sm-offset-3 '.$class_right.'">
                  <div class="checkbox"><label>
                  <input id="'.$id.'" name="'.$id.'" type="'.$type.'"';
        if ($input == true){
            $form .= 'checked="checked';
        }    
        $form .= ' onclick="'.$onclick.'" ';
        $form .= ' /> '.$label.' </label></div></div></div>';  

        return $form;
    }
    
    public static function input_switch($id, $label, $input, $error, $class_left='col-sm-3', $class_right='col-sm-9'){
        $form = '<div class="form-group '.validate_msg($error, $id, true).'">
                  <label class="control-label '.$class_left.'" for="'.$id.'">'.$label.'<br><small>'.$input->capability.'</small></label>
                  <div class="'.$class_right.'" style="padding-left:85px;">'.validate_msg($error, $id).'
                <input type="checkbox" name="'.$input->capability.'" id="'.$input->capability.'" class="ios-toggle" ';
                if ($input->permission == 1){
                    $form .= ' value="true" checked ';
                } else {
                    $form .= ' value="false" ';
                }
                $form .= ' onclick="switchValue(\''.$input->capability.'\');"/>
                 <label for="'.$input->capability.'" class="checkbox-label" data-off="nicht erlaubt" data-on="erlaubt"></label>'; 
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
    public static function input_select($id, $label, $select_data, $select_label, $select_value, $input, $error, $onchange= '', $placeholder ='---', $class_left='col-sm-3', $class_right='col-sm-9'){
        $form = '<div class="form-group '.validate_msg($error, $id, true).'">
                  <label class="control-label '.$class_left.'" for="'.$id.'">'.$label.'</label>
                  <div class="'.$class_right.'">
                      <select id="'.$id.'" name="'.$id.'" class="form-control" onchange="'.$onchange.'">';
                       if (count($select_data) > 0){
                             if ($placeholder != '---'){
                                $form .= '<option>'.$placeholder.'</option>';
                             }
                            foreach ($select_data as $value) {
                                if (strpos($select_label, ',')){ // more than one field in select_label
                                    foreach (explode(', ', $select_label) as $f) {
                                        $fields[]  = $value->$f;
                                    }
                                    $label = implode(" | ", $fields);
                                    unset($fields);
                                } else {
                                    $label  = $value->$select_label;
                                }
                                $form .= '<option label="'.$label.'" value="'.$value->$select_value.'"'; if ($input == $value->$select_value){ $form .= 'selected="selected"'; } $form .= '>'.$label.'</option>';
                            }
                       } else {
                           $form .= '<option label="'.$placeholder.'">'.$placeholder.'</option>';
                       }
        $form .= '</select> ';
        $form .= '</div></div>';
        
        return $form;
    }
    public static function input_select_multiple($params){
        /*$id, $label, $select_data, $select_label, $select_value, $input*/
        $error          = null;
        $onchange       = '';
        $placeholder    = '---';
        $class_left     = 'col-sm-3';
        $class_right    = 'col-sm-9';
        $height         = '135px';
        $limiter        = ' ';
        foreach($params as $key => $val) { $$key = $val; }
        
        $form = '<div class="form-group '.validate_msg($error, $id, true).'">
                    <label class="control-label '.$class_left.'" for="'.$id.'">'.$label.'</label>
                    <div class="'.$class_right.'">
                        <select multiple id="'.$id.'[]" name="'.$id.'[]" class="form-control" style="height:'.$height.';" onchange="'.$onchange.'">';
                        if (count($select_data) > 0){
                            foreach ($select_data as $value) {
                                if (strpos($select_label, ',')){ // more than one field in select_label                   
                                    foreach (explode(', ', $select_label) as $f) {
                                        $fields[]  = $value->$f;
                                    }
                                    $label = implode($limiter, $fields);
                                    unset($fields);
                                } else {
                                    $label  = $value->$select_label;
                                }
                                $form .= '<option label="'.$label.'" value="'.$value->$select_value.'"'; if ($input == $value->$select_value){ $form .= 'selected="selected"'; } $form .= '>'.$label.'</option>';
                            }
                        } else {
                           $form .= '<option label="'.$placeholder.'">'.$placeholder.'</option>';
                        }
        $form .= '</select> ';
        $form .= '</div></div>';
        
        return $form;
    }
    
    public static function input_color($params){
        $label          = 'Farbe';
        $rgb            = '#3cc95b';
        $class_left     ='col-sm-3'; 
        $class_right    ='col-sm-9';
        foreach($params as $key => $val) {
            $$key = $val;
        }
        $form = '<div class="form-group">
                    <label class="control-label '.$class_left.'" for="'.$id.'">'.$label.'</label>
                    <div class="'.$class_right.'">'.validate_msg($error, $id).'    
                        <div id="colorpicker" class="input-group color-picker colorpicker-element" >
                          <input id="'.$id.'" name="'.$id.'" type="text" class="form-control" value="'.$rgb.'">
                          <div class="input-group-addon">
                            <i style="background-color: '.$rgb.';"></i>
                          </div>
                        </div><!-- /.input group -->
                    </div><!-- /.div class_right -->
                  </div>';
        return $form;
    }
    
    public static function input_date($params){
        $type           = 'date';
        $class_left     ='col-sm-3'; 
        $class_right    ='col-sm-9';
        foreach($params as $key => $val) {
            $$key = $val;
        }
        $form = ' <div class="form-group">
                    <label class="control-label '.$class_left.'" for="'.$id.'">'.$label.'</label>
                    <div class="'.$class_right.'">'.validate_msg($error, $id).'    
                        <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa ';
        switch ($type) {
            case 'date':    $form .= 'fa-calendar';
                break;
            case 'time':    $form .= 'fa-clock-o';
                break;
            default:        $form .= 'fa-calendar';
                break;
        }             
        $form .=            '"></i>
                        </div>
                        <input id="'.$id.'" name="'.$id.'" type="text" class="form-control datepicker" data-provide="datepicker" value="'.$time.'">
                        </div>  
                    </div><!-- /.div class_right -->
                  </div>';
        return $form;
    }
    
    
    public static function input_dropdown($id, $label, $select_data, $select_label, $select_value, $input, $error, $onclick= '', $placeholder ='---'){
        $class          =  "notifications-menu";
        $count_semester = count($select_data);
        $form = '<li class="dropdown '.$class.'" >
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
                $form .= '<li>
                            <a href="#" data-id="'.$value->$select_value.'" onclick="'.$onclick.'">
                              <i class="text-aqua"></i> ';
                                if ($input == $value->$select_value){ 
                                    $form .= '<b>'.$label.'</b>'; 
                                } else {
                                    $form .= $label; 
                                }
                                
                $form .= '  </a>
                          </li>';
                          //<!--option label="'.$label.'" value="'.$value->$select_value.'"'; if ($input == $value->$select_value){ $form .= 'selected="selected"'; } $form .= '>'.$label.'</option>';
            }
       } else {
                $form .= '<li>
                            <a href="#">
                              <i class="text-aqua"></i> '.$placeholder.'
                            </a>
                          </li>';
       }
                          
        $form .= '      </ul>
                      </li>
                      <li class="footer"><!-- <a href="#">View all</a>--></li>
                    </ul>
                 </li>';
        return $form;
                
    }
    
    public static function upload_form($id, $label, $input, $error, $onclick='', $class_left='col-sm-3', $class_right='col-sm-9'){
        $form = '<div id="'.$id.'" class="form-group '.validate_msg($error, $id, true).'">
                    <label class="control-label '.$class_left.'" for="'.$id.'">'.$label.'</label>
                    <div class="'.$class_right.'">
                        <p id="'.$id.'_fName" class="hidden"></p>
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
                        <input id="'.$id.'_fSelector" name="file" type="file" class="btn btn-primary col-sm-12" onchange="fileChange(\''.$id.'\');">
                    </div>
                </div>  
            ';
        
        return $form; 
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
                    <div class="modal-content" ><!-- height is dynamic set by popupFunction() -->
                        <div class="modal-header">';
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
        foreach($params as $key => $val) {
            $$key = $val;
        }
        $html  = ' <div id="material_'.$id.'" class="info-box">';
        if (isset($preview)){
            $html .= '<span class="info-box-icon bg-aqua"><div id="modal-preview" style="height:100%;width:100%;background: url(\''.$preview.'\') center ;background-size: cover; background-repeat: no-repeat;"></div></span>'; //pull-left --> overrides align: center to top
        } else {
           $html .= RENDER::thumb($id, null, null, 'thumb');
        }
        /* Box content */
        $html .= '<div class="info-box-content">';
        if (isset($delete)){
        $html .= '<div class="pull-right">
                    <button class="btn btn-box-tool" onclick="removeMaterial('.$id.')"><i class="fa fa-trash"></i></button>
                  </div>';
        }
        $html .= '<span class="info-box-text">';
        if (isset($player)){
            $html .= $player.'<br>';
        }
        if (!isset($url)){
            $url = '';
        }
        $html .= '<a href="'.$url.'" target="_blank" onclick="formloader(\'preview\',\'file\','.$id.');'.$onclick.';">'.$title.'</a></span>
                  <span>'.$description;
        
        $html .='</span> ';
        $html .= $footer;
        $html .= ' </div>'; // .info-box-content
        $html .= ' </div>'; // .info-box
        return $html;   
    }
    
}