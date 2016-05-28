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
 * @license 
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by  
 * the Free Software Foundation; either version 3 of the License, or (at your option) any later version.                                   
 *                                                                       
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of        
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details:                          
 *                                                                       
 * http://www.gnu.org/copyleft/gpl.html      
 */
class Form {
    
    public static function info($id, $label, $content, $class_left='col-sm-4', $class_right='col-sm-7'){
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
    public static function input_text($id, $label, $input, $error, $placeholder ='Text...', $type='text', $min=null, $max=null, $class_left='col-sm-4', $class_right='col-sm-7', $readonly = null){
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
    
    public static function input_textarea($id, $label, $input, $error, $placeholder ='Text...', $class_left='col-sm-4', $class_right='col-sm-7'){
        $form  = '<div class="form-group '.validate_msg($error, $id, true).'"><label class="control-label '.$class_left.'" for="'.$id.'">'.$label.'</label>
                  <div class="'.$class_right.'">';
        $form .= '<textarea id="'.$id.'" name="'.$id.'" class="ckeditor" rows="10" cols="80" style="visibility: hidden; display: none;">';
        if (isset($input)) { 
            $form .=  $input;  
        } else {
            $form .=  $placeholder;
        }
        $form .= '</textarea>';
        //$form .= '</div>';
        /*/*$form = '<div class="form-group '.validate_msg($error, $id, true).'">
                  <label class="control-label '.$class_left.'" for="'.$id.'">'.$label.'</label>
                  <div class="'.$class_right.'">'.validate_msg($error, $id).'<input id="'.$id.'" name="'.$id.'" type="'.$type.'" ';
                  if ($min) {$form .= 'min="'.$min.'" ';}
                  if ($min) {$form .= 'max="'.$max.'" ';}
        $form .= 'class="form-control" placeholder="'.$placeholder.'" ';
         * 
         */
        /*if (isset($input)) { 
            $form .=  'value="'.$input.'"';  
        } */
         $form .= '</div></div>'; 

        return $form;
    }
    
    public static function input_checkbox($id, $label, $input, $error, $type='checkbox', $onclick='', $class_left='col-sm-4', $class_right='col-sm-7'){
        $form = '<div class="form-group '.validate_msg($error, $id, true).'">
                  <label class="control-label '.$class_left.'" for="'.$id.'"></label>
                  <div class="'.$class_right.'">'.validate_msg($error, $id).'<input id="'.$id.'" name="'.$id.'" type="'.$type.'" onclick="'.$onclick.'" class="'.$type.'" ';
        $form .= ' /> '.$label.'</div></div>';  

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
    public static function input_select($id, $label, $select_data, $select_label, $select_value, $input, $error, $onchange= '', $placeholder ='---', $class_left='col-sm-4', $class_right='col-sm-7'){
        $form = '<div class="form-group '.validate_msg($error, $id, true).'">
                  <label class="control-label '.$class_left.'" for="'.$id.'">'.$label.'</label>
                  <div class="'.$class_right.'">
                      <select id="'.$id.'" name="'.$id.'" class="form-control" onchange="'.$onchange.'">';
                       if (count($select_data) > 0){
                            foreach ($select_data as $value) {
                                if (strpos($select_label, ',')){ // more than one field in select_label
                                list ($field1, $field2) = explode(', ', $select_label);
                                    $label  = $value->$field1. ' '. $value->$field2;
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
    public static function input_select_multiple($id, $label, $select_data, $select_label, $select_value, $input, $error, $onchange= '', $placeholder ='---', $class_left='col-sm-4', $class_right='col-sm-7'){
        $form = '<div class="form-group '.validate_msg($error, $id, true).'">
                  <label class="control-label '.$class_left.'" for="'.$id.'">'.$label.'</label>
                  <div class="'.$class_right.'">
                      <select multiple id="'.$id.'" name="'.$id.'" class="form-control" onchange="'.$onchange.'">';
                       if (count($select_data) > 0){
                            foreach ($select_data as $value) {
                                if (strpos($select_label, ',')){ // more than one field in select_label
                                list ($field1, $field2) = explode(', ', $select_label);
                                    $label  = $value->$field1. ' '. $value->$field2;
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
        $label = 'Farbe';
        $rgb = '#3cc95b';
        $class_left='col-sm-4'; 
        $class_right='col-sm-7';
        foreach($params as $key => $val) {
            $$key = $val;
        }
        $form = '<div class="form-group">
                    <label class="control-label '.$class_left.'" for="'.$id.'">'.$label.'</label>
                    <div class="'.$class_right.'">'.validate_msg($error, $id).'    
                        <div id="colorpicker" class="input-group color-picker colorpicker-element">
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
        $type = 'date';
        $class_left='col-sm-4'; 
        $class_right='col-sm-7';
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
    
    public static function upload_form($id, $label, $input, $error, $onclick='', $class_left='col-sm-4', $class_right='col-sm-7'){
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
        foreach($params as $key => $val) {
            $$key = $val;
        }
        $html ='<div class="modal-dialog" >
                    <div class="modal-content" ><!-- height is dynamic set by popupFunction() -->
                        <div class="modal-header">';
                        if (isset($h_content)){
                            $html .= $h_content;
                        } else {
                            $html .= '<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closePopup();"><span aria-hidden="true">×</span></button>';
                        }
                        $html .= '<h4 class="modal-title">'.$title.'</h4>';
            if (isset($background)){$background = 'background:'.$background.';';} else{$background = '';}
            $html .=   '</div>
                        <div class="modal-body" style="overflow: auto !important; width:100%;'.$background.'"><div class="form-horizontal">
                            '.$content.'
                        </div><!-- /.modal-body -->';
                        if (isset($f_content)){
            $html .=    '<div class="modal-footer">';
                            if ($f_content == 'close'){
                                $html .= '<button name="close" type="button" class="btn btn-primary pull-right" onclick="closePopup()">OK</button>';
                            } else {
                                $html .= $f_content;
                            }
            $html .=   '</div>';
                        }
            $html .=   '</div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->';
    return $html;    
    }
    
    public static function box($params){
        foreach($params as $key => $val) {
            $$key = $val;
        }
        if (!isset($footer)){
            $footer =    '';
        } else {
            $footer = '<div class="box-footer" >'.$footer.'</div>';
        }
        $html =    '<div class="box box-primary" >
                        <div class="box-header with-border">
                            <h3 class="box-title">'.$header.'</h3>
                            <div class="box-tools pull-right">
                                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            </div><!-- /.box-tools -->
                        </div><!-- /.box-header -->
                        <div class="box-body" style="display: block;">'.$content.'
                        </div>'.$footer.'
                    </div>';
        return $html;     
    }
    
    public static function info_box($params){
        foreach($params as $key => $val) {
            $$key = $val;
        }
        $html  = ' <div id="material_'.$id.'" class="info-box">';
        if (isset($preview)){
            $html .= '<span class="info-box-icon bg-white"><img class="pull-left" src="'.$preview.'" ></img></span>'; //pull-left --> overrides align: center to top
        } else {
            $html .= '<span class="info-box-icon bg-aqua"><i class="'.$icon_class.'"></i></span>';
        }
        /* Box content */
        $html .= '<div class="info-box-content">';
        if (isset($delete)){
        $html .= '<div class="pull-right">
                    <button class="btn btn-box-tool" onclick="deleteFile('.$id.')"><i class="fa fa-trash"></i></button>
                  </div>';
        }
        $html .= '<span class="info-box-text">';
        if (isset($player)){
            $html .= $player.'<br>';
        }
        $html .= '<a href="'.$url.'" onclick="'.$onclick.'">'.$title.'</a></span>
                  <span>'.$description;
        
        $html .='</span> ';
        $html .= $footer;
        $html .= ' </div>'; // .info-box-content
        $html .= ' </div>'; // .info-box
        return $html;   
    }
    
}
