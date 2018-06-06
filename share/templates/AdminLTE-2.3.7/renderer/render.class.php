<?php
/**
* Render class
* 
* @abstract This file is part of curriculum - http://www.joachimdieterich.de
* @package core
* @filename render.class.php
* @copyright 2015 Joachim Dieterich
* @author Joachim Dieterich
* @date 2015.11.28 18:05
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
class Render {
    static $sortKey;
   
    public static function accomplish($string, $student, $teacher){
        global $s, $t;
        $s = $student;
        $t = $teacher;
       
        return preg_replace_callback('/<accomplish id="(\d+)"><\/accomplish>/i', 
            function($r){ 
                global $s, $t; 
                return Render::accCheckboxes(array('id' => $r[1], 'student' => $s, 'teacher' => $t));
            }, $string);     
    }
    
    public static function accCheckboxes($params){
        //$id, $student, $teacher
        global $USER, $CFG;
        $link   = true;
        $email  = false; 
        $token  = false;
        
        if (!is_array($params)){ //hack to use json_arrays from smarty
            $params = json_decode($params);
        }
        foreach($params as $key => $val) { $$key = $val; }   
        
        $ena       = new EnablingObjective();
        $ena->id   = $id;
        $ena->getObjectives('enabling_objective_status', $student); // get status of objective

        $red       = 'fa fa-circle-o';
        $green     = 'fa fa-circle-o';
        $orange    = 'fa fa-circle-o';
        $white     = 'fa fa-circle-o';

        switch (true) {
            case $ena->accomplished_status_id === 'x0': $red    = 'fa fa-check-circle-o';
                       $bg     = 'bg-red';
                break;
            case $ena->accomplished_status_id === '0x': $red    = 'fa fa-circle';
                       $bg     = 'bg-red';
                break;
            case $ena->accomplished_status_id === '00': $red    = 'fa fa-check-circle';
                       $bg     = 'bg-red';
                break;
            case $ena->accomplished_status_id === '01': $red    = 'fa fa-circle';
                       $green  = 'fa fa-check-circle-o';
                       $bg     = 'bg-green';
                break;
            case $ena->accomplished_status_id === '02': $red    = 'fa fa-circle';
                       $orange  = 'fa fa-check-circle-o';
                       $bg     = 'bg-orange';
                break;
            case $ena->accomplished_status_id === '03': $red    = 'fa fa-circle';
                       $white  = 'fa fa-check-circle-o';
                       $bg     = 'bg-white';
                break;
            case $ena->accomplished_status_id === 'x1': $green  = 'fa fa-check-circle-o';
                break;
            case $ena->accomplished_status_id === '1x': $green  = 'fa fa-circle';
                break;
            case $ena->accomplished_status_id === '10': $red    = 'fa fa-check-circle-o';
                     $green  = 'fa fa-circle';
                break;
            case $ena->accomplished_status_id === '11': $green  = 'fa fa-check-circle';
                break;
            case $ena->accomplished_status_id === '12': $green  = 'fa fa-circle';
                     $orange = 'fa fa-check-circle-o';
                break;
            case $ena->accomplished_status_id === '13': $green  = 'fa fa-circle';
                     $white  = 'fa fa-check-circle-o';
                break;
            case $ena->accomplished_status_id === 'x2': $orange = 'fa fa-check-circle-o';
                break;
            case $ena->accomplished_status_id === '2x': $orange = 'fa fa-circle';
                break;
            case $ena->accomplished_status_id === '20': $orange = 'fa fa-circle';
                     $red    = 'fa fa-check-circle-o';
                break;
            case $ena->accomplished_status_id === '21': $orange = 'fa fa-circle';
                     $green  = 'fa fa-check-circle-o';
                break;
            case $ena->accomplished_status_id === '22': $orange = 'fa fa-check-circle';
                break;
            case $ena->accomplished_status_id === '23': $orange = 'fa fa-circle';
                                                        $white  = 'fa fa-check-circle-o';
                break;
            case $ena->accomplished_status_id === 'x3': $white  = 'fa fa-check-circle-o';
                break;
            case $ena->accomplished_status_id === '3x': $white  = 'fa fa-circle';
                break;
            case $ena->accomplished_status_id === '30': $white  = 'fa fa-circle';
                       $red    = 'fa fa-check-circle-o';
                break;
            case $ena->accomplished_status_id === '31': $white  = 'fa fa-circle';
                     $green  = 'fa fa-check-circle-o';
                break;
            case $ena->accomplished_status_id === '32': $white  = 'fa fa-circle';
                     $orange = 'fa fa-check-circle-o';
                break;
            case $ena->accomplished_status_id === '33': $white  = 'fa fa-check-circle';
                break;

            default:
                break;
        }

        $course     = new Course();
        $ena->load();

       
        if ($email AND $token){ 
            $u = new User(); //load teacherpermission
            $u->load('id',$teacher, false);
            //TODO: check if user has permission! --> check was made in fp_upload.php
            $status = $ena->accomplished_status_id;
            if (strlen($status) > 1){
                $student_status = substr($status, 0,1);
            } else {
                $student_status = 'x';
            }
            $html   = '<br><strong>Lösung bewerten:</strong><br><br>Nutzer hat das Ziel ...<br><br>';
            $html  .= '<a href="'.$CFG->base_url.'public/index.php?action=extern&teacher='.$teacher.'&student='.$student.'&ena_id='.$id.'&status='.$student_status.'1'.'&token='.$token.'">... selbständig erreicht.</a>'
                . '<br><a href="'.$CFG->base_url.'public/index.php?action=extern&teacher='.$teacher.'&student='.$student.'&ena_id='.$id.'&status='.$student_status.'2'.'&token='.$token.'">... mit Hilfe erreicht.</a>'
                . '<br><a href="'.$CFG->base_url.'public/index.php?action=extern&teacher='.$teacher.'&student='.$student.'&ena_id='.$id.'&status='.$student_status.'3'.'&token='.$token.'">... nicht bearbeitet.</a>'
                . '<br><a href="'.$CFG->base_url.'public/index.php?action=extern&teacher='.$teacher.'&student='.$student.'&ena_id='.$id.'&status='.$student_status.'0'.'&token='.$token.'">... nicht erreicht.</a>';
        } else if ($USER->id != $student OR $student == $teacher){ // 2. Bedingung bewirkt, dass als Lehrer eigene Einreichungen bewerten kann --> für Demonstration der Plattform wichtig
            if ((!checkCapabilities('objectives:setStatus', $USER->role_id, false))){ //if student
               $status = $ena->accomplished_status_id;
                if (strlen($status) > 1){
                    $teacher_status = substr($status, 1,1);
                } else {
                    $teacher_status = 'x';
                }
                $teacher = $student;
                $html   = '<a class="pointer_hand" data-toggle="tooltip" title="Selbsteinschätzung: Ich kann das selbstständig." ><i id="'.$id.'_green" style="font-size:18px;" class="'.$green.' margin-r-5 text-green pointer_hand" onclick="setAccomplishedObjectives('.$teacher.', \''.$student.'\', '.$id.', \'1'.$teacher_status.'\')"></i></a>'
                        . '<a class="pointer_hand" data-toggle="tooltip" title="Selbsteinschätzung: Ich kann das mit Hilfe." ><i id="'.$id.'_orange" style="font-size:18px;" class="'.$orange.' margin-r-5 text-orange pointer_hand" onclick="setAccomplishedObjectives('.$teacher.', \''.$student.'\', '.$id.', \'2'.$teacher_status.'\')"></i></a>'
                        . '<a class="pointer_hand" data-toggle="tooltip" title="Selbsteinschätzung: Ich kann das noch nicht." ><i id="'.$id.'_red" style="font-size:18px;" class="'.$red.' margin-r-5 text-red pointer_hand" onclick="setAccomplishedObjectives('.$teacher.', \''.$student.'\', '.$id.', \'0'.$teacher_status.'\')"></i></a>'
                        . '<a class="pointer_hand" data-toggle="tooltip" title="Selbsteinschätzung: Ich habe das noch nicht bearbeitet." ><i id="'.$id.'_white" style="font-size:18px;" class="'.$white.' margin-r-5 text-gray pointer_hand" onclick="setAccomplishedObjectives('.$teacher.', \''.$student.'\', '.$id.', \'3'.$teacher_status.'\')"></i></a>';
            } else {
                $status = $ena->accomplished_status_id;
                if (strlen($status) > 1){
                    $student_status = substr($status, 0,1);
                } else {
                    $student_status = 'x';
                }
                $html   = '<a class="pointer_hand"><i id="'.$id.'_green" style="font-size:18px;" class="'.$green.' margin-r-5 text-green pointer_hand" onclick="setAccomplishedObjectives('.$teacher.', \''.$student.'\', '.$id.', \''.$student_status.'1\')"></i></a>'
                    . '<a class="pointer_hand"><i id="'.$id.'_orange" style="font-size:18px;" class="'.$orange.' margin-r-5 text-orange pointer_hand" onclick="setAccomplishedObjectives('.$teacher.', \''.$student.'\', '.$id.', \''.$student_status.'2\')"></i></a>'
                    . '<a class="pointer_hand"><i id="'.$id.'_red" style="font-size:18px;" class="'.$red.' margin-r-5 text-red pointer_hand" onclick="setAccomplishedObjectives('.$teacher.', \''.$student.'\', '.$id.', \''.$student_status.'0\')"></i></a>'
                    . '<a class="pointer_hand"><i id="'.$id.'_white" style="font-size:18px;" class="'.$white.' margin-r-5 text-gray pointer_hand" onclick="setAccomplishedObjectives('.$teacher.', \''.$student.'\', '.$id.', \''.$student_status.'3\')"></i></a>';
                
            }
            
            if ($link){
                $group_id   = $course->getGroupID($ena->curriculum_id, $teacher, $student);
                $html  .= '<button class="btn btn-default btn-sm"><a href="index.php?action=objectives&course='.$ena->curriculum_id.'_'.$group_id.'&paginator=userPaginator&p_select='.$student.'&certificate_template=-1&reset"><i class="fa fa-th"></i> Zum Lehrplan</a></button>'     ;
            }
        }
        return $html;
    }
    
    public static function link($string, $context){
        global $c, $thumb_list;
        $c      = $context;
        preg_match_all('/<link id="(\d+)"><\/link>/i', $string, $hits, PREG_SET_ORDER); 
        $list   = array();
        foreach ($hits as $h){
           $list[] = $h[1];
        }
        return $list;
    }
    
    public static function file($file/*, $context = false*/){
        global $CFG, $USER;
        
        switch ($file->type) {
            case '.mp4':
            case '.mov':    $content    = '<video width="100%" controls>
                                           <source src="'.$CFG->access_file.$file->context_path.$file->path.$file->filename.'&video=true"  type="video/mp4"/>
                                           Your browser does not support the video element.</video>';
                break;
            case '.bmp':    
            case '.gif':       
            case '.png':    
            case '.svg':    
            case '.jpeg':    
            case '.jpg':    $content     = '<img src="'.$CFG->access_file.$file->context_path.$file->path.$file->filename.'" style="width:100%;"/>';
                break;
            case '.pdf':    $content     = '<div id="pdf_'.$file->id.'" style="width:100%; height: 600px;"></div>';
                break;
            case '.rtf':    $reader      = new RtfReader();
                            $reader->Parse(file_get_contents($CFG->curriculumdata_root.$file->context_path.$file->path.$file->filename));
                            $formatter   = new RtfHtml();
                            $content     = utf8_encode('<div padding>'.$formatter->Format($reader->root).'</div>');
                break;
            case '.txt':    $content     = '<p style="width:100%;">'.nl2br(htmlspecialchars(file_get_contents($CFG->curriculumdata_root.$file->context_path.$file->path.$file->filename))).'</p>';
                break;
            case '.url':    $content     ='<iframe src="'.$file->filename.'" style="width:100%; height: 600px;"></iframe>';
                break;
            default:        if (checkCapabilities('plugin:useEmbeddableGoogleDocumentViewer', $USER->role_id, false) AND !is_array(getimagesize($CFG->curriculumdata_root.$file->full_path))){
                                $content = '<iframe src="http://docs.google.com/gview?url='.$CFG->access_token_url .$file->addFileToken($file->id).'&embedded=true" style="width:100%; height:500px;" frameborder="0"></iframe>';
                            } else {
                                $content = RENDER::thumb(array('file_list' => array($file->id), 'tag' => 'div'));
                            }
                break;
        }
        
        return $content;
    }
    
    public static function thumb($params){/*$file_list, $target = null, $tag = 'li', $format='normal'*/
        global $USER,$CFG;
        $target     = null;
        $tag        = 'li';
        $format     = 'normal';
        $height     = 187;
        $width      = 133;
        $truncate   = 15;
        $file       = new File();
        $html       = '';
        $icon       = false;
        
        foreach($params as $key => $val) {
            $$key = $val;
        }
        if (!is_array($file_list)){
            $file_list = array($file_list);
        }
        foreach ($file_list as $f) {
            $file->id = $f;
            $file->load();
            /* check if img*/ 
            switch ($file->type) {
                case '.bmp':    
                case '.gif':       
                case '.png':    
                case '.svg':    
                case '.jpeg':    
                case '.jpg':    if ($file->getThumb() == false){ $url = $file->getFileUrl(); } else { $url = $file->getThumb(); }          
                    break;
                case '.pdf':    if ($file->getThumb() == false){ $icon = true; } else { $url = $file->getThumb(); }          
                    break;
                case '.url':    
                default:        $icon = true;
                    break;
            }
            
            switch ($format) {
                case 'normal': $html .= '<'.$tag.' id="thumb_'.$file->id.'" style="width:'.$width.'px !important; height:'.$height.'px !important;">';
                                if ($icon == true ){
                                    $html .= '<h6 class="pull-right" style="padding: 0 10px 0 5px; background-color:rgba(244, 244, 244, 0.8)" ><a href="#" data-toggle="tooltip" title="Dateigröße">'.$file->getHumanFileSize().'</a></h6>
                                              <h6 class="pull-left" style="padding: 0px 10px 0 5px; background-color:rgba(244, 244, 244, 0.8)"><a href="#" data-toggle="tooltip" title="Dateiaufrufe (Aus einem Lehrhplan)">'.$file->hits.'</a></h6>
                                              <span class="mailbox-attachment-icon" style="height:'.$width.'px"><i class="'.resolveFileType($file->type).'"></i></span>';
                                } else {
                                    $html .= '<span class="mailbox-attachment-icon has-img" style="height:'.$width.'px">
                                                    <div id="modal-preview" style="height:100%;width:100%;background: url(\''.$url.'\') ';
                                                    if ($file->type != '.pdf'){
                                                        $html .= 'center';
                                                    }
                                                     $html .= ';background-size: cover; background-repeat: no-repeat;">
                                                        <h6 class="pull-right" style="padding: 0 10px 0 5px;  background-color:rgba(244, 244, 244, 0.8)"><a href="#" data-toggle="tooltip" title="Dateigröße">'.$file->getHumanFileSize().'</a></h6>
                                                        <h6 class="pull-left" style="padding: 0px 10px 0 5px; background-color:rgba(244, 244, 244, 0.8)"><a href="#" data-toggle="tooltip" title="Dateiaufrufe (Aus einem Lehrhplan)">'.$file->hits.'</a></h6>
                                              </div></span>';
                                }
                                $html .= '<div class="mailbox-attachment-info text-ellipse" style="padding:5px 5px 5px 5px;">
                                                <a href="#" class="mailbox-attachment-name"><small>'.truncate($file->filename, $truncate).'</small></a>
                                                <span class="mailbox-attachment-size">';
                                                if ($target != null){
                                                    $html .= '  <a href="#" class="btn btn-default btn-xs" data-toggle="tooltip" title="Verwenden" onclick="setTarget('.$file->id.');"><i class="fa fa-check-circle"></i></a>';
                                                }
                                                if ($icon != true){
                                                    $html .=       '<a href="#" class="btn btn-default btn-xs" data-toggle="tooltip" title="Vorschau" onclick="formloader(\'preview\',\'file\','.$file->id.');"><i class="fa fa-eye"></i></a>';
                                                }
                                                if ($file->type != '.url'){
                                                    $html .= '<a href="'.$file->getFileUrl().'" data-toggle="tooltip" title="herunterladen" class="btn btn-default btn-xs"><i class="fa fa-cloud-download"></i></a>';
                                                }
                                                if (checkCapabilities('file:update', $USER->role_id, false)){
                                                    $html .= '<a href="#" class="btn btn-default btn-xs" data-toggle="tooltip" title="bearbeiten" onclick="formloader(\'file\',\'edit\','.$file->id.');"><i class="fa fa-edit"></i></a>';
                                                }
                                                if (checkCapabilities('file:delete', $USER->role_id, false) AND $file->creator_id == $USER->id){
                                                    $html .= '<a href="#" class="btn btn-default btn-xs pull-right" data-toggle="tooltip" title="löschen" onclick="del(\'file\','.$file->id.');"><i class="fa fa-trash"></i></a>';
                                                }
                                $html .= '</span></div></'.$tag.'>'; 


                    break;
                case 'xs':      $html .=   '<div class="btn-group" style="padding-right:10px;">
                                            <button type="button" class="btn btn-xs btn-default btn-flat" style="width:'.($width-25).'px !important; text-align:left;">';
                                            if ($icon == true){
                                                $html .=   '<span class="pull-left"><i class="'.resolveFileType($file->type).'" style="padding-right:5px; margin-right:5px;"></i></span>';
                                            } else {
                                                $html .=   '<div class="pull-left" id="modal-preview" style="height:15px;width:15px;background: url(\''.$url.'\'); background-size: cover; background-repeat: no-repeat; margin-top:2px; margin-right:5px;" ></div>';
                                            }
                                            $html .=   truncate($file->filename, $truncate);
                                            $html .=  '</button>
                                            <button type="button" class="btn btn-xs btn-flat dropdown-toggle" data-toggle="dropdown">';
                                $html .=   '  <span class="caret"></span>
                                              <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <ul class="dropdown-menu" role="menu">';
                                            if ($target != null){
                                                $html .=   '<li><a href="#" onclick="setTarget('.$file->id.');"><i class="fa fa-check-circle"></i>Verwenden</a></li>';
                                            } 
                                            if ($icon != true){
                                                $html .=   '<li><a href="#" onclick="formloader(\'preview\',\'file\','.$file->id.');"><i class="fa fa-eye"></i>Vorschau</a></li>';
                                            }
                                            if ($file->type != '.url'){
                                                $html .=   '<li><a href="'.$file->getFileUrl().'"><i class="fa fa-cloud-download"></i>herunterladen</a></li>';
                                            } 
                                if (checkCapabilities('file:update', $USER->role_id, false)){
                                     $html .=   '<li><a href="#" onclick="formloader(\'file\',\'edit\','.$file->id.');"><i class="fa fa-edit"></i>bearbeiten</a></li>';             
                                }
                                if (checkCapabilities('file:delete', $USER->role_id, false)){            
                                    $html .=   '  <li class="divider"></li>
                                              <li><a href="#" onclick="del(\'file\','.$file->id.');"><i class="fa fa-trash"></i>löschen</a></li>';
                                }
                                $html .=   '</ul></div>';
                    break;
                case 'thumb':   if ($icon == true){
                                    $html .=   '<div style="position:relative; height: '.$height.';width:'.$width.'; float:left;"><i class="'.resolveFileType($file->type).' info-box-icon"></i>';
                                        if (isset($_SESSION['LICENSE'][$file->license]->file_id)){
                                            $html .= '<img style="position:absolute;bottom:0px; right:0px;" src="'.$CFG->access_id_url.$_SESSION['LICENSE'][$file->license]->file_id.'" height="25"/>';
                                        }
                                    $html .=   '</div>';
                                    
                                    
                                } else {
                                    $html .=   '<div style="position:relative; height: '.$height.';width:'.$width.'; float:left; background: url(\''.$url.'\') center; background-size: cover; background-repeat: no-repeat;">';
                                        if (isset($_SESSION['LICENSE'][$file->license]->file_id)){
                                            $html .= '<img style="position:absolute;bottom:0px; right:0px;" src="'.$CFG->access_id_url.$_SESSION['LICENSE'][$file->license]->file_id.'" height="25"/>';
                                        }
                                    $html .=   '</div>';
                                    //$html .=   '<div class="info-box-icon" style="background: url(\''.$url.'\') center; background-size: cover; background-repeat: no-repeat;"></div>';
                                }
                    break;
                default:
                    break;
            }
                                
        }
        
        return $html;
    }
    
    public static function helpcard($help){
        global $USER;
        $html =   '<div class="col-lg-3 col-md-6 col-xs-12">';
                    $html .= '<a href="#" onclick="formloader(\'preview\',\'help\','.$help->id.')">
                              <div class="info-box">';
                    $html .= RENDER::thumb(array('file_list' => $help->file_id, 'format'=> 'thumb', 'width' => '90px', 'height' => '90px'));  
                    if (checkCapabilities('help:update', $USER->role_id, false)){
                        $html .='<a><span class="pull-right" onclick="formloader(\'help\',\'edit\','.$help->id.');"><i class="fa fa-edit margin"></i></span></a>';
                    }
                    if (checkCapabilities('help:add', $USER->role_id, false)){
                        $html .='<a><span class="pull-right" onclick="del(\'help\','.$help->id.');"><i class="fa fa-trash top-buffer"></i></span></a>';
                    }
                    $html .= '<p style="padding-left:100px;">
                              <span class="info-box-text text-black">'.$help->category.'</span>
                              <span class="info-box-number text-black text-ellipse">'.$help->title.'</span>
                              <span class="info-box-more text-primary text-ellipse">'.$help->description.'</span>
                            </p><!-- /.info-box-content -->
                            </a>
                          </div><!-- /.info-box -->
                        
                    </div>';
        return $html;                                    
    }
    
    public static function wallet_thumb($params){
        $width_class     = 'col-lg-3 col-md-4 col-sm-6 col-xs-12';
                
        foreach($params as $key => $val) {
            $$key = $val;
        }
        global $USER;
        $html =   '<div class="'.$width_class.'">';
                    
                    $html .= '<div class="thumbnail">
                                <div class="row">
                                <div class="col-xs-12" >';
                    $html .= RENDER::thumb(array('file_list' => $wallet->file_id, 'format' => 'thumb', 'height' => '90px', 'width' => '100%'));      
                    $html .= '  </div>
                                </div>';
                    if (checkCapabilities('help:update', $USER->role_id, false)){
                        $html .='<a class="pull-right"><span class="fa fa-edit padding-top-5 margin-r-5" onclick="formloader(\'wallet\',\'edit\','.$wallet->id.');"></span></a>';
                    }
                    if (checkCapabilities('help:add', $USER->role_id, false)){
                        $html .='<a class="pull-right"><span class="fa fa-trash padding-top-5 margin-r-5" onclick="del(\'wallet\','.$wallet->id.');"></span></a>';
                    }
                    
                    $html .= '  <span class="pull-left"><small>'.$wallet->timerange.'</small></span><div class="caption text-center"><br>
                                  <h5 class="events-heading text-ellipse"><a href="index.php?action=walletView&wallet='.$wallet->id.'">'.$wallet->title.'</a></h5>
                                  <p style="overflow: scroll; width: 100%; height: 100px;">'.$wallet->description.'</p>
                                </div>
                              </div><!-- /.events-->
                    </div>';
        return $html;                                    
    }
    public static function wallet_content($wallet_content, $edit){
       
       $html  =   '<div class="sortable" style="width:100%;height:100%;"><div class="'.$wallet_content->width_class;
       if ($edit == true){
           $html  .=   ' sortable wallet-content"><span style="position: absolute; right:15px;" ></button><button type="button" onclick="formloader(\'wallet_content\',\'edit\','.$wallet_content->id.');"><i class="fa fa-edit"></i></button>'
                      . '<button type="button"  onclick="del(\'wallet_content\','.$wallet_content->id.');"><i class="fa fa-trash"></i></button>'
                      . '<button type="button"  onclick=\'processor("orderWalletContent","left",'.$wallet_content->id.', {"order":"left"});\'><i class="fa fa-arrow-left"></i></button>'
                   . '<button type="button"  onclick=\'processor("orderWalletContent","right",'.$wallet_content->id.', {"order":"right"});\'><i class="fa fa-arrow-right"></i></button>'
                   . '<button type="button"  onclick=\'processor("orderWalletContent","up",'.$wallet_content->id.', {"order":"up"});\'><i class="fa fa-arrow-down"></i></button>'
                      . '<button type="button"  onclick=\'processor("orderWalletContent","down",'.$wallet_content->id.', {"order":"down"});\'><i class="fa fa-arrow-up"></i></button>'
                      . '</span>';
       } else {
            $html  .=  '"><span class="'.$wallet_content->position.'">';
       }
       switch ($wallet_content->context) {
           case 'content':  $c = new Content();
                            $c->load('id', $wallet_content->reference_id); 
                            $html  .= $c->content;

               break;

           default:         $f = new File();
                            $f->load($wallet_content->reference_id);
                            $html  .= Render::file($f);
               break;
       }
       $html .=   '</span></div></div>';
        return $html;  
    }
    
    public static function comments($params){
        global $CFG, $USER;
        foreach($params as $key => $val) {
             $$key = $val;
        }
        if (!isset($permission)){ $permission = 1; }
        $html = '<ul class="media-list ">';
        foreach ($comments as $cm) {
            $u      = new User();
            $u->load('id', $cm->creator_id, false);
            $size   = '48';
            $html  .= '<li class="media" >
                       <a class="pull-left" href="#" >
                         <div style="height:'.$size.'px;width:'.$size.'px;background: url('.$CFG->access_id_url.$u->avatar_id.') center right;background-size: cover; background-repeat: no-repeat;"></div>
                        </a>
                        <a style="cursor:pointer;" class="text-red margin-r-10 pull-right" onclick=\'processor("set","comments",'.$cm->id.', {"dependency":"dislikes", "input":"'.($cm->dislikes+1).'"});\'><i class="fa fa-thumbs-o-down margin-r-5"></i> '.$cm->dislikes.' </a>
                        <a style="cursor:pointer;" class="text-green margin-r-10 pull-right" onclick=\'processor("set","comments",'.$cm->id.', {"dependency":"likes", "input":"'.($cm->likes+1).'"});\'><i class="fa fa-thumbs-o-up margin-r-5"></i> '.$cm->likes.' </a>
                        
                      <div class="media-body" >
                          <h4 class="media-heading">'.$u->username.' <small class="text-black margin-r-10"> '.$cm->creation_time.'</small> 
                          </h4>
                              <p class="media-heading">'.$cm->text.'<br>';
                              if ($cm->creator_id == $USER->id){
                                  if ($permission > 0){
                                    $html  .= '<a class="text-red" onclick="del(\'comment\','.$cm->id.');"><i class="fa fa-trash "></i></a>';
                                  }
                              } else {
                                $html  .= '<a class="text-red" onclick=""><i class="fa fa-exclamation-triangle "></i> Kommentar melden</a>';
                              }
                              if ($permission > 0){
                                $html  .= ' | <a id="answer_'.$cm->id.'" onclick="toggle([\'comment_'.$cm->id.'\', \'cmbtn_'.$cm->id.'\'], [\'answer_'.$cm->id.'\'])">Antworten</a></p>';
                              }
                              $html .='<textarea id="comment_'.$cm->id.'" name="comment"  class="hidden" style="width:100%;"></textarea>
                                        <button id="cmbtn_'.$cm->id.'" type="submit" class="btn btn-primary pull-right hidden" onclick="comment(\'new\','.$cm->reference_id.', '.$cm->context_id.', document.getElementById(\'comment_'.$cm->id.'\').value, '.$cm->id.');"><i class="fa fa-commenting-o margin-r-10"></i>Kommentar abschicken</button>';
            /* sub comments */
            if (!empty($cm->comment)){
              $html .= RENDER::sub_comments(array('comment' => $cm->comment));
            }
            $html .= '</li><hr class="dashed">';
        }
        $html .=  '</ul>';

        return $html;
    }
    
    public static function sub_comments($params){
        global $CFG, $USER;
        foreach($params as $key => $val) {
             $$key = $val;
        }
        if (!isset($permission)){ $permission = 1; }
        $html = '';
        foreach ($comment as $cm){
            $u = new User();
            $u->load('id', $cm->creator_id, false);
            $size = '32';
            $html .=  '<div class="media ">
                        <a class="pull-left" href="#" >
                            <div style="height:'.$size.'px;width:'.$size.'px;background: url('.$CFG->access_id_url.$u->avatar_id.') center right;background-size: cover; background-repeat: no-repeat;"></div>
                        </a>                        
                        <div class="media-body" >
                            <h4 class="media-heading">'.$u->username.' <small class="text-black margin-r-10"> '.$cm->creation_time.'</small>
                                <a style="cursor:pointer;" class="text-green margin-r-10 " onclick=\'processor("set","comments",'.$cm->id.', {"dependency":"likes", "input":"'.($cm->likes+1).'"});\'><i class="fa fa-thumbs-o-up margin-r-5"></i> '.$cm->likes.' </a>
                                <a style="cursor:pointer;" class="text-red margin-r-10 " onclick=\'processor("set","comments",'.$cm->id.', {"dependency":"dislikes", "input":"'.($cm->dislikes+1).'"});\'><i class="fa fa-thumbs-o-down margin-r-5"></i> '.$cm->dislikes.' </a>
                            </h4>
                                <p class="media-heading">'.$cm->text.'<br>';
                                if ($cm->creator_id == $USER->id){
                                    if ($permission > 0){
                                        $html  .= '<a class="text-red" onclick="del(\'comment\','.$cm->id.');"><i class="fa fa-trash "></i></a>';
                                    }
                                  } else {
                                    $html  .= '<a class="text-red" onclick=""><i class="fa fa-exclamation-triangle "></i> Kommentar melden</a>';
                                  }
                                if ($permission > 0){  
                                    $html .= ' | <a id="answer_'.$cm->id.'" onclick="toggle([\'comment_'.$cm->id.'\', \'cmbtn_'.$cm->id.'\'], [\'answer_'.$cm->id.'\'])">Antworten</a></p>';
                                }
                                $html .='<textarea id="comment_'.$cm->id.'" name="comment"  class="hidden" style="width:100%;"></textarea>
                                        <button id="cmbtn_'.$cm->id.'" type="submit" class="btn btn-primary pull-right hidden" onclick="comment(\'new\','.$cm->reference_id.', '.$cm->context_id.', document.getElementById(\'comment_'.$cm->id.'\').value, '.$cm->id.');"><i class="fa fa-commenting-o margin-r-10"></i>Kommentar abschicken</button>';
                                if (!empty($cm->comment)){
                                    $html .= RENDER::sub_comments(array('comment' => $cm->comment));
                                }                         
            $html .=  ' </div></div>';

                
        }
        return $html;
        
    }
    
    /* add all possible options (ter and ena) to this objective function*/
    public static function objective($params){
       global $CFG, $USER, $PAGE;
       $format      = 'default';
       $type        = 'terminal_objective'; 
       $edit        = false;
       $sol_btn     = false;
       $orderup     = false;
       $orderdown   = false;
       $reference_view = false; // 'hide' objectives without references
       //$objective 
       foreach($params as $key => $val) {
            $$key = $val;
       }
       if (!isset($user_id)){$user_id = $USER->id;} // if user_id is set --> accCheckbox set this user 
       if (!isset($objective->color)){ 
            $objective->color = '#FFF'; 
            $text_class       = 'text-black';
            $icon_class       = 'text-primary';
       } else {
           if (getContrastColor($objective->color) == '#000000'){
               $text_class    = 'text-black';
               $icon_class    = 'text-black';
           }
           else {
               $text_class    = 'text-white';
               $icon_class    = 'text-white';
           }
           
       }
       if (!isset($border_color)){
            $border_color = $objective->color; 
        }
        
        
        //adding format to generate more objective layouts
        switch ($format) {
            case 'reference':
                $html  =   '<div ';
                if ($type == 'enabling_objective'){ //id is important to get scroll-to function while creating
                    $html  .= 'id="ena_'.$objective->id.'"';
                } else {
                    $html  .= 'id="ter_'.$objective->id.'"';
                }
                $html  .=   'class="box box-objective ';
                if (isset($highlight)){
                    if (in_array($type.'_'.$objective->id, $highlight)){
                        $html  .= 'highlight';
                    } 
                }
                $html  .= '" style="padding-top: 0 !important; background: '.$objective->color.'; border: 1px solid '.$border_color.'">';
                /*************** Header ***************/
                if ($type == 'enabling_objective'){
                    $html  .= '<div id="ena_header_'.$objective->id.'" class="boxheader bg-'.$objective->accomplished_status_id.'" >';
                } else {
                    $html  .= '<div class="boxheader">';
                }
                 $html  .='</div>';    
                 /*************** ./Header ***************/
                 /*************** Body ***************/    
                 $html  .='  <div id="'.$type.'_'.$objective->id.'" class="panel-body boxwrap" >
                                 <div class="boxscroll" ';
                                     if ($type == 'terminal_objective'){
                                         $html  .='style="background: '.$objective->color.'"';
                                     }
                                     $html  .='><div class="boxcontent '.$text_class.'">'.$objective->$type.'</div>
                                 </div>
                             </div>';
                 /*************** ./Body ***************/   
                /*************** Footer ***************/
                $html  .= '  <div class="boxfooter">';
                if ($objective->description != ''){
                    $html  .='<span class="fa fa-info pull-right box-sm-icon text-primary" style=" margin-right:3px;" onclick="formloader(\'description\', \''.$type.'\', '.$objective->id.');"></span>';
                }
                $html  .='<span class="pull-left margin-r-10">';
                if (checkCapabilities('file:loadMaterial', $USER->role_id, false) AND ($objective->files['local'] != '0' OR $objective->files['repository'] != '' OR isset($objective->files['webservice']) )){
                    $html  .='<span class="fa fa-briefcase box-sm-icon text-primary margin-r-5 pull-left" style="cursor:pointer; padding-top:3px;" onclick="formloader(\'material\',\''.$type.'\','.$objective->id.')"></span>';
                } else {
                    $html  .='<span class="fa fa-briefcase box-sm-icon deactivate text-gray margin-r-5 pull-left" style="cursor:not-allowed;padding-top:3px;" data-toggle="tooltip" title="Keine Materialien verfügbar"></span>';
                }
                $html  .='</span></div>';
                 /*************** ./Footer ***************/
                $html  .= '</div>';
                break;

            default:
                $html  =   '<div ';
                if ($type == 'enabling_objective'){ //id is important to get scroll-to function while creating
                    $html  .= 'id="ena_'.$objective->id.'"';
                } else {
                    $html  .= 'id="ter_'.$objective->id.'"';
                }
                $html  .=   'class="box box-objective ';
                if (isset($highlight)){
                    if (in_array($type.'_'.$objective->id, $highlight)){
                        $html  .= 'highlight';
                    } 
                }
                $style = 'padding-top: 0 !important; background: '.$objective->color.'; border: 1px solid '.$border_color;
                if ($objective->files['references'] == false AND $reference_view == true){
                   $style .= 'filter: alpha(opacity=40); opacity: 0.4; -moz-opacity: 0.4;';
                } 
                $html  .= '" style="'.$style.'">';
                /*************** Header ***************/
                if ($type == 'enabling_objective'){
                    $html  .= '<div id="ena_header_'.$objective->id.'" class="boxheader bg-'.$objective->accomplished_status_id.'" >';
                } else {
                    $html  .= '<div class="boxheader">';
                }
                     if (checkCapabilities('groups:showAccomplished', $USER->role_id, false)){
                         if (isset($objective->accomplished_users) AND isset($objective->enroled_users) AND isset($objective->accomplished_percent)){
                             $html  .= '<span class=" pull-left hidden-sm hidden-xs text-black" data-toggle="tooltip" title="Stand der Lerngruppe">'.$objective->accomplished_users.' von '.$objective->enroled_users.' ('.$objective->accomplished_percent.'%)</span><!--Ziel-->  ';
                         }
                     }

                     if ($edit){
                         if ($type == 'terminal_objective'){
                             $icon_up    = 'down'; 
                             $icon_down  = 'up';
                             $position   = 'pull-left';
                         } else {
                             $icon_up    = 'right'; 
                             $icon_down  = 'left';
                             $position   = 'pull-right';
                         }
                         if ($orderup){
                             $html  .= '<span class="fa fa-arrow-'.$icon_up.' '.$position.' box-sm-icon '.$icon_class.'" onclick=\'processor("orderObjective", "'.$type.'", "'.$objective->id.'", {"order":"up"});\'></span>';
                         }
                         $html  .= '<span class="fa fa-minus pull-right box-sm-icon '.$icon_class.' margin-r-5" onclick=\'del("'.$type.'", '.$objective->id.');\'></span>
                                    <span class="fa fa-edit pull-right box-sm-icon '.$icon_class.'" onclick=\'formloader("'.$type.'", "edit", '.$objective->id.');\'></span>';
                         if ($orderdown){
                             $html  .= '<span class="fa fa-arrow-'.$icon_down.' pull-left box-sm-icon '.$icon_class.'" onclick=\'processor("orderObjective", "'.$type.'", "'.$objective->id.'", {"order":"down"});\'></span>';
                         }
                     } else {
                        $c_menu_array               = array();
                        $content_menu_obj           = new stdClass();
                        if (checkCapabilities('file:solutionUpload', $USER->role_id, false) AND $type != 'terminal_objective' AND isset($solutions)){
                            foreach ($solutions AS $s){
                                if (($USER->id == $s->creator_id) AND ($s->enabling_objective_id == $objective->id) AND ($sol_btn != $objective->id)){
                                    $sol_btn = $objective->id;
                                    break;
                                }
                            }
                        }
                        if (isset($PAGE->action) AND $type == 'enabling_objective' AND checkCapabilities('file:solutionUpload', $USER->role_id, false) AND (checkCapabilities('file:upload', $USER->role_id, false) OR checkCapabilities('file:uploadURL', $USER->role_id, false))){
                            if($PAGE->action == 'view'){
                                $content_menu_obj->href = $CFG->smarty_template_dir_url.'renderer/uploadframe.php?context=solution&ref_id='.$objective->id.$CFG->tb_param;
                                $content_menu_obj->href_class = 'nyroModal';
                                if ($sol_btn == $objective->id){
                                    $content_menu_obj->title  = '<i class="fa fa-check-circle-o" ></i> Lösung (Datei) eingereicht';
                                } else {
                                    $content_menu_obj->title  = '<i class="fa fa-upload" ></i> Lösung (Datei) einreichen';
                                }  
                            }
                            $c_menu_array[]             = clone $content_menu_obj;
                            $content_menu_obj           = new stdClass();
                            $content_menu_obj->onclick  = 'formloader(\'content\', \'new\', null,{\'context_id\':\'4\', \'reference_id\':\''.$objective->id.'\'});';
                            $content_menu_obj->title    = '<i class="fa fa-pencil" ></i> Lösung online eingeben';
                            $c_menu_array[]             = clone $content_menu_obj;
                        }
                        if (checkCapabilities('course:setAccomplishedStatus', $USER->role_id, false) AND $type != 'terminal_objective' AND isset($group_id)){
                            $content_menu_obj->onclick  = 'formloader(\'compare\',\'group\', \''.$objective->id.'\', {\'group_id\':\''.$group_id.'\'});';
                            $content_menu_obj->title    = '<i class="fa fa-bar-chart-o"></i> Überblick über Gruppe';
                            $c_menu_array[]             = clone $content_menu_obj;
                            $content_menu_obj->onclick  = 'formloader(\'material\',\'solution\', \''.$objective->id.'\', {\'group_id\':\''.$group_id.'\', \'curriculum_id\': \''.$objective->curriculum_id.'\'});';
                            $content_menu_obj->title    = '<i class="fa fa-files-o"></i> Eingereichte Lösungen (Datei)';
                            $c_menu_array[]             = clone $content_menu_obj;
                            $content_menu_obj->onclick  = 'formloader(\'solution\',\'solution\', \''.$objective->id.'\', {\'group_id\':\''.$group_id.'\', \'curriculum_id\': \''.$objective->curriculum_id.'\'});';
                            $content_menu_obj->title    = '<i class="fa fa-files-o"></i> Eingereichte Lösungen (Texteingaben)';
                            $c_menu_array[]             = clone $content_menu_obj;
                        }
                        if (checkCapabilities('user:getHelp', $USER->role_id, false) AND $type != 'terminal_objective' AND isset($group_id)){
                            $content_menu_obj->onclick  = 'formloader(\'support\',\'random\', \''.$objective->id.'\', {\'group_id\':\''.$group_id.'\'});';
                            $content_menu_obj->title    = '<i class="fa fa-support" ></i> Gruppenmitglied um Hilfe bitten';
                            $c_menu_array[]             = clone $content_menu_obj;
                        }
                        $html  .= '<span class="pull-right box-sm-icon" style="padding-left:5px;">'.Render::split_button(array('type' => 'menu', 'btn_type' => 'btn btn-flat btn-default btn-xs','label' => '<i class="fa fa-caret-down"></i>', 'entrys' => $c_menu_array)).'</span>';
                        
                     }
                  if ($type == 'terminal_objective'){
                     $html  .=' <a class="collapse_btn" data-toggle="collapse" data-target="#collaps_ter_'.$objective->id.'" data-toggle="tooltip" title="Kompetenzen einklappen bzw. ausklappen"><i class="fa fa-compress box-sm-icon '.$text_class.'" style="padding-left:5px;"></i></a>';   
                  }
                  $html  .='  </div>';    
                 /*************** ./Header ***************/
                 /*************** Body ***************/    
                 $html  .='  <div id="'.$type.'_'.$objective->id.'" class="panel-body boxwrap" >
                                 <div class="boxscroll" ';
                                     if ($type == 'terminal_objective'){
                                         $html  .='style="background: '.$objective->color.'"';
                                     }
                                     $html  .='><div class="boxcontent '.$text_class.'">'.$objective->$type.'</div>
                                 </div>
                             </div>';
                 /*************** ./Body ***************/
                 /*************** Footer ***************/
                 $html  .= '  <div class="boxfooter">';
                                 if ($objective->description != ''){
                                     $html  .='<span class="fa fa-info pull-right box-sm-icon '.$icon_class.'" style=" margin-right:3px;" data-toggle="tooltip" title="Beschreibung" onclick="formloader(\'description\', \''.$type.'\', '.$objective->id.');"></span>';
                                 }
                                 $html  .='<span class="pull-left margin-r-10">';
                                 if (checkCapabilities('file:loadMaterial', $USER->role_id, false) AND ($objective->files['local'] != '0' OR $objective->files['repository'] != '' OR isset($objective->files['webservice']) OR $objective->files['references'] != false )){
                                     $html  .='<span class="fa fa-briefcase box-sm-icon '.$icon_class.' margin-r-5 pull-left" style="cursor:pointer; padding-top:3px;" onclick="formloader(\'material\',\''.$type.'\','.$objective->id.')"></span>';
                                 } else {
                                     $html  .='<span class="fa fa-briefcase box-sm-icon deactivate '.$icon_class.' margin-r-5 pull-left" style="cursor:not-allowed;padding-top:3px;" data-toggle="tooltip" title="Keine Materialien verfügbar"></span>';
                                 }
                                 if ((checkCapabilities('file:upload', $USER->role_id, false) OR checkCapabilities('file:uploadURL', $USER->role_id, false)) AND isset($PAGE->action)){
                                     if ($PAGE->action == 'view'){
                                         $html  .='<a href="'.$CFG->smarty_template_dir_url.'renderer/uploadframe.php?context='.$type.'&ref_id='.$objective->id.$CFG->tb_param.'" class="nyroModal pull-right margin-r-5"><span class="fa fa-plus '.$icon_class.' box-sm-icon" style="padding-top:3px;data-toggle="tooltip" title="Material hinzufügen"></span></a>';
                                     }
                                 } 
                                 $html  .='</span>';
                                 if ($edit){
                                     if ($type != 'terminal_objective'){

                                         $html  .= '<span class="fa fa-check-square-o pull-right box-sm-icon '.$icon_class.'" onclick=\'formloader("addQuiz", "enabling_objective", "'.$objective->id.'");\'></span>';
                                         if (checkCapabilities('webservice:linkModule', $USER->role_id, false) AND isset($objective->files['webservice']) AND $PAGE->action == 'view'){
                                             $html  .='<span class="fa fa-puzzle-piece ';
                                             if ($objective->files['webservice'] == ""){ 
                                                 $html .= 'deactivate text-gray ';
                                             } else {
                                                 $html  .=''.$icon_class.' ';
                                             }
                                             $html  .='pull-right" onclick=\'formloader("link_module","enabling_objective","'.$objective->id.'","","webservice/moodle");\'></span>';
                                         }   
                                     }
                                 } else {

                                     if ($type != 'terminal_objective'){
                                         if (checkCapabilities('reference:show', $USER->role_id, false)){
                                             $html  .= '<span class="fa fa-link text-primary box-sm-icon pull-left" data-toggle="tooltip" title="Lehr- /Rahmenplanbezüge" onclick=\'formloader("reference_view", "'.$type.'", "'.$objective->id.'");\'></span>';
                                         }
                                         if (checkCapabilities('reference:add', $USER->role_id, false)){
                                             $html  .= '<span class="box-sm-icon pull-right text-primary" data-toggle="tooltip" title="Lehr- /Rahmenplanbezug hinzufügen" onclick=\'formloader("reference", "new", "'.$objective->id.'", {"context":"enabling_objective"});\'><i class="fa fa-link text-primary box-sm-icon"><i class="fa fa-plus fa-xs"></i></i></span>';
                                         }
                                         if (checkCapabilities('course:selfAssessment', $USER->role_id, false)){
                                             if (is_array($user_id)){
                                                 $user_id = implode(',',$user_id);
                                             }
                                             $html  .='<span class="pull-left">'.Render::accCheckboxes(array('id' => $objective->id, 'student' => $user_id, 'teacher' => $USER->id, 'link' => false)).'</span>';
                                         }
                                         if (checkCapabilities('quiz:showQuiz', $USER->role_id, false) AND isset($PAGE->action)){
                                             if ($objective->quiz != '0' AND $PAGE->action == 'view'){
                                                 $html  .='<span class="fa fa-check-square-o pull-right box-sm-icon '.$icon_class.'" onclick=\'formloader("quiz","enabling_objective","'.$objective->id.'");\'></span>';
                                             }
                                         }
                                         if (checkCapabilities('webservice:linkModuleResults', $USER->role_id, false) AND isset($PAGE->action) AND isset($objective->files['webservice'])){
                                             if ($PAGE->action == 'view' AND $objective->files['webservice'] != ''){
                                                 $html  .='<span class="box-sm-icon '.$icon_class.'" onclick=\'processor("link_module_result","enabling_objective","'.$objective->id.'","","webservice/moodle");\'><i class="fa fa-external-link-square  fa-rotate-180"></i></span>';
                                             }
                                         }
                                     } else {
                                         if (checkCapabilities('reference:show', $USER->role_id, false)){
                                             $html  .= '<span class="fa fa-link '.$icon_class.' box-sm-icon pull-left" data-toggle="tooltip" title="Lehr- /Rahmenplanbezüge" onclick=\'formloader("reference_view", "'.$type.'", "'.$objective->id.'");\'></span>';
                                         }
                                         if (checkCapabilities('reference:add', $USER->role_id, false)){
                                             $html  .= '<span class="box-sm-icon pull-right '.$icon_class.'" data-toggle="tooltip" title="Lehr- /Rahmenplanbezug hinzufügen" onclick=\'formloader("reference", "new", "'.$objective->id.'", {"context":"terminal_objective"});\'><i class="fa fa-link '.$text_class.' box-sm-icon"><i class="fa fa-plus fa-xs"></i></i></span>';
                                         }    

                                     }
                                 }  

                 $html  .=' </div>';

                 /*************** ./Footer ***************/
                $html  .= '</div>'; 
           break;
        }
        
        return $html;
    }
    
    public static function mail($mail, $box = null){
        global $CFG;
        
        $sender         = new User();        
        $sender->id     = $mail->sender_id;
        if ($sender->exist()){                      //if User was deleted --> false + set first/lastname to "Gelöschter User
            $sender->load('id', $mail->sender_id, false);
        } 
        
        $receiver       = new User();
        $receiver->id   = $mail->receiver_id;
        if ($receiver->exist()){
            $receiver->load('id', $mail->receiver_id, false);
        } 
        $thumbs = Render::link($mail->message, 'message');
        $mail->message = Render::accomplish($mail->message, $sender->id, $receiver->id);
        $content = '<div class="box">
                <div class="box-header with-border">
                  <h3 class="box-title">Nachricht</h3>
                  <div class="box-tools pull-right" >
                    <div class="btn-group">
                      
                      <button class="btn btn-default btn-sm" data-toggle="tooltip" title="Antworten" onclick="formloader(\'mail\',\'reply\','.$mail->id.')"><i class="fa fa-reply"></i> Antworten</button>
                      <button class="btn btn-default btn-sm" data-toggle="tooltip" title="Weiterleiten" onclick="formloader(\'mail\',\'forward\','.$mail->id.')"><i class="fa fa-share"></i> Weiterleiten</button>
                    </div><!-- /.btn-group -->
                    <button class="btn btn-default btn-sm" data-toggle="tooltip" title="Drucken" onclick="processor(\'print\',\'mail\','.$mail->id.')"><i class="fa fa-print"></i> Drucken</button>
                    <div class="btn-group">
                        <button class="btn btn-default btn-sm" data-toggle="tooltip" title="zurück"><i class="fa fa-chevron-left"></i></button>
                        <button class="btn btn-default btn-sm" data-toggle="tooltip" title="vor"><i class="fa fa-chevron-right"></i></button>
                    </div><!-- /.btn-group -->
                    <button class="btn btn-default btn-sm" data-toggle="tooltip" title="löschen" onclick="del(\'message\', '. $mail->id.', \''.$box.'\')"><i class="fa fa-trash-o"></i> Löschen</button>
                  </div>
                </div><!-- /.box-header -->
                <div class="box-body no-padding">
                  <div class="mailbox-read-info">
                    <div class="pull-left image" style="margin-right:10px;">
                        <img src="'.$CFG->access_file.$sender->avatar.'" style="height:40px;" class="img-circle" alt="User Image">
                    </div>
                    <h3>'.$mail->subject.'</h3>
                    <h5>Von: '.$sender->firstname.' '.$sender->lastname.' ('.$sender->username.')<span class="mailbox-read-time pull-right">'.$mail->creation_time.'</span></h5>
                  </div><!-- /.mailbox-read-info -->
                  
                  <div class="mailbox-read-message">
                    <p>'.$mail->message.'</p>
                  </div><!-- /.mailbox-read-message -->
                </div><!-- /.box-body -->
                <div class="box-footer">
                  <ul class="mailbox-attachments clearfix">';
                  if (count($thumbs) > 0){
                  $content .= '<i class="fa fa-paperclip"></i><strong> Anhang: </strong>';
                  }
                    foreach ($thumbs as $t) {
                        $file     = new File();
                        $file->id = $t;
                        if ($file->load()){
                            $content .= $file->filename;
                            switch ($file->type) {
                                case '.pdf':    $content .= ' <a href="#" class="btn btn-default btn-xs" data-toggle="tooltip" title="Vorschau" onclick="formloader(\'preview\',\'file\','.$file->id.');"><i class="fa fa-eye"></i></a>';
                                    break;
                                default:      $content .= Render::file($file);
                                    break;
                            }
                        } else {
                            $content .= 'Datei wurde gelöscht.';
                        }
                    }   
          $content .= '  </ul>
                </div><!-- /.box-footer -->
                <!--div class="box-footer">
                  <div class="pull-right">
                    <button class="btn btn-default"><i class="fa fa-reply"></i> Reply</button>
                    <button class="btn btn-default"><i class="fa fa-share"></i> Forward</button>
                  </div>
                  <button class="btn btn-default"><i class="fa fa-trash-o"></i> Delete</button>
                  <button class="btn btn-default"><i class="fa fa-print"></i> Print</button>
                </div--><!-- /.box-footer -->
              </div><!-- /. box -->';
        return $content;
    }
    
    public static function popup($titel, $content, $url = false, $btn = 'OK') {
        $html= '<div class="modal-dialog">
                <div id="modal" class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" ';
                    if ($btn == 'OK') {
                        $html.= 'onclick="location.href='."'".$url."'".'"';
                    } else {
                        $html.= 'onclick="closePopup();"';
                    }
         $html.= '><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">'.$titel.'</h4>
                  </div>
                  <div class="modal-body scroll">
                    <p>'.$content.'</p>
                  </div>
                  <div class="modal-footer">
                    <!--<button type="button" class="btn btn-outline pull-left" data-dismiss="modal" onclick="closePopup();">Schließen</button>-->';
                    if ($btn == 'OK') {
                        if (!$url) { $url = $_SESSION['PAGE']->url; }
                        $html .= '<button type="button" class="btn btn-outline" onclick="location.href='."'".$url."'".'">OK</button>';
                    }
          $html .= '  </div>
                </div>
              </div>';
          return $html;
    } 
    
    public static function quiz($question, $attempt = false, $correction = false){
        $html= '';
        if (isset($question)){
            if (!$attempt){
                $html .= '<h3>Bitte wähle die richtige Antwort aus.</h3><br>'; 
            }
            
            $html .= '<form id="ajax_quiz_form" action="" name="addQuestion" method="post">';
            foreach ($question as $value) {
                $html .= '<h4>'.$value->question.'</h4>';
                switch ($value->type) {
                    case 0: foreach ($value->answers as $a) {
                                if ($attempt){
                                    $html .= '<div class="';
                                    if ($attempt[$value->id] == 1 AND $a->correct == 1){ $html .= ' callout callout-success ';}
                                    if ($attempt[$value->id] == 1 AND $a->correct == 0){ $html .= ' callout callout-danger ';}
                                    if ($attempt[$value->id] == 0 AND $a->correct == 1){ $html .= ' callout callout-success ';}
                                    $html .= '"><input style="width:40px;" type="radio" name="'.$value->id.'" ';
                                    if ($attempt[$value->id] == 1) { $html .= 'checked="checked"';}
                                    $html .= 'value="1">Wahr';
                                
                                    if ($attempt[$value->id] == 1 AND $a->correct == 1){
                                        $html .= '<div class="pull-right">Richtig</div>';
                                    }  else if ($attempt[$value->id] == 1 AND $a->correct == 0){
                                        $html .= '<div class="pull-right">Falsch</div>';
                                    }  
                                } else {
                                    $html .= '<div ><input style="width:40px;" type="radio" name="'.$value->id.'" value="1">Richtig';
                                }
                                $html .= '</div>';
                                if ($attempt){
                                    $html .= '<div class="space-left ';
                                    if ($attempt[$value->id] == 0 AND $a->correct == 0){ $html .= ' callout callout-success ';}
                                    if ($attempt[$value->id] == 0 AND $a->correct == 1){ $html .= ' callout callout-danger ';}
                                    if ($attempt[$value->id] == 1 AND $a->correct == 0){ $html .= ' callout callout-success ';}
                                    $html .= '"><input style="width:40px;" type="radio" name="'.$value->id.'" ';
                                    if ($attempt[$value->id] == 0) { $html .=  'checked="checked"';}
                                    $html .= 'value="0">Falsch';
                                    if ($attempt[$value->id] == 0 AND $a->correct == 0){
                                        $html .= '<div class="pull-right">Richtig</div>';
                                    }   else if ($attempt[$value->id] == 0 AND $a->correct == 1){
                                        $html .= '<div class="pull-right">Falsch</div>';
                                    }    
                                } else {
                                    $html .= '<div class=""><input style="width:40px;" type="radio" name="'.$value->id.'" value="0">Falsch';
                                }
                                $html .= '</div>';
                            }
                        break;
                    case 1: foreach ($value->answers as $a) {
                                if ($attempt){
                                    $html .= '<div class=" ';
                                    if ($attempt[$value->id] == $a->id AND $a->correct == 1){ $html .= ' callout callout-success '; }
                                    if ($attempt[$value->id] == $a->id AND $a->correct == 0){ $html .= ' callout callout-danger '; }
                                    if ($attempt[$value->id] != $a->id AND $a->correct == 1){ $html .= ' callout callout-success '; }
                                    $html .= '"><input style="width:40px;" type="radio" name="'.$value->id.'" value="'.$a->id.'" ';
                                    if ($attempt[$value->id] == $a->id) { $html .= 'checked="checked"';}
                                    $html .= '>'.$a->answer;
                                    if ($attempt[$value->id] == $a->id AND $a->correct == 1){
                                        $html .= '<div class="pull-right">Richtig</div>';
                                    }   else if ($attempt[$value->id] == $a->id AND $a->correct != 1){
                                        $html .= '<div class="pull-right">Falsch</div>';
                                    } 
                                } else {
                                    $html .= '<div class=""><input style="width:40px;" type="radio" name="'.$value->id.'" value="'.$a->id.'">'.$a->answer;
                                }
                                $html .='</div>';
                            }
                        break;
                    case 2: foreach ($value->answers as $a) {
                                if ($attempt){
                                    $html .= '<div class=" ';
                                    if ($attempt[$value->id] == $a->answer){ $html .= ' callout callout-success '; }
                                    if ($attempt[$value->id] != $a->answer ){ $html .= ' callout callout-danger '; }
                                    $html .= '"><input style="width:40px;" type="text" name="'.$value->id.'" value="'.$attempt[$value->id].'" >';
                                    if ($attempt[$value->id] == $a->answer ){
                                        $html .= '<div class="pull-right">Richtig</div>';
                                    }   else if ($attempt[$value->id] != $a->answer ){
                                        $html .= '<div class="pull-right">Richtige Antwort ist<strong> '.$a->answer.'</strong></div>';
                                    } 
                                } else {
                                    $html .= '<div class=""><input style="width:40px;" type="text" name="'.$value->id.'" onkeydown="if (event.keyCode == 13) {event.preventDefault();}">';
                                }
                                $html .='</div>';
                            }
                        break;
                    case 3: $html .= '<div id="div2" class="pull-right border-box" style="height:30px;width:90px;" ondrop="drop_answer(event, '.$value->id.')" ondragover="allowDrop(event)"></div>';
                            $html .= '<input style="width:40px;" type="hidden" name="'.$value->id.'" value="">';
                            foreach ($value->answers as $a) {
                                if ($attempt){
                                    $html .= '<div class=" ';
                                    if ($attempt[$value->id] == $a->answer){ $html .= ' callout callout-success '; }
                                    if ($attempt[$value->id] != $a->answer ){ $html .= ' callout callout-danger '; }
                                    $html .= '"><input style="width:40px;" type="text" name="'.$value->id.'" value="'.$attempt[$value->id].'" >';
                                    if ($attempt[$value->id] == $a->answer ){
                                        $html .= '<div class="pull-right">Richtig</div>';
                                    }   else if ($attempt[$value->id] != $a->answer ){
                                        $html .= '<div class="pull-right">Richtige Antwort ist<strong> '.$a->answer.'</strong></div>';
                                    } 
                                } else {
                                    $html .= '<div class="space-left">';
                                    $html .= '<div id="div_'.$a->id.'" class="border-box" style="height:30px;width:90px;" ondrop="drop(event)" ondragover="allowDrop(event)">
                                    <img src="'.$a->answer.'" draggable="true" ondragstart="drag(event, '.$value->id.')" id="'.$a->id.'" width="88" height="31">
                                  </div>';
                                }
                                $html .= '</div>';
                            }
                            
                        break;

                    default:
                        break;
                }
                $html .= '<br>';
            } 
            if (!$attempt){
                $html .= '<button class="btn btn-block btn-default" onclick="sendForm(\'ajax_quiz_form\', \'evaluateQuiz.php\');return false">Quiz beenden</button>';
            } else {
                $html .= '<button class="btn btn-block btn-default">Fenster schließen</button>';
            }
            $html .= '</form>';
        } 
        return $html;
    }
    /**
     * Render filelist
     * @param string $form
     * @param string $dependency
     * @param string $view
     * @param string $postfix
     * @param string $target
     * @param boolean $format
     * @param string $multiple
     * @param int $id
     * @return string
     */
    public static function filelist($url, $dependency, $view, $target, $id){
        global $TEMPLATE;
        $file    = new File();
        $files   = $file->getFiles($dependency, $id, 'filelist_'.$dependency);
        
        setPaginator('filelist_'.$dependency, $TEMPLATE, $files, 'fi_val', $url); //set Paginator for filelist
        $content = '<div class="box-body scroll_list" style="overflow:auto;" ><form name="'.$url.'" action="'.$url.'" method="post" enctype="multipart/form-data" >';
                    
        switch ($view) {
                    case 'thumbs': $content .= RENDER::thumblist($files, $target);
                        break;
                    case 'detail': $content .= RENDER::detaillist($files, $target);
                        break;
                    case 'list':   $content .= RENDER::flist($files, $target);
                        break;
                    default:
                        break;
        }
        $content .= '</form></div>';
       
        return $content;
    }
    /**
     * Render all files as thumbs
     * @param array $files
     * @return html
     */
    public static function thumblist($files, $target){
        $content = '<ul class="mailbox-attachments clearfix">'; //<!--onclick="processor(\'config\',\'paginator_order\',\'null\',{\'order\':\'filename\',\'sort\':\'DESC\',\'paginator\':\''.$postfix.'\'});" -->
        foreach ($files as $f) {
            $content .= RENDER::thumb(array('file_list' => array('id' => $f->id), 'target' => $target)); 
        }
        $content .= '</ul>';
        return $content;
    }
    
    public static function detaillist($files, $target ){
        global $USER;
        $content  = '<table class="table table-striped" style="width: 100%;word-break:break-all;"><tbody>';
        $content .= '<tr>
                        <th><i class="fa fa-bars"></i></th>
                        <th style="width:30%;">Dateiname</th>
                        <th >Titel</th>
                        <th style="width:140px;">Datum</th>
                        <th style="width:60px;">Größe</th>
                        <th style="width:50px;">Typ</th>
                    </tr>';
        foreach ($files as $f) {
            $content .= '<tr>';
            $content .= '<td><div class="btn-group"><button type="button" class="btn btn-xs btn-flat dropdown-toggle" data-toggle="dropdown">
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                              </button><ul class="dropdown-menu" role="menu">';
                            if ($target != null){
                                $content .= '<li><a href="#" onclick="setTarget('.$f->id.');"><i class="fa fa-check-circle"></i>Benutzen</a></li>';
                            }
                            $content .= '<li><a href="#" onclick="formloader(\'preview\',\'file\','.$f->id.');"><i class="fa fa-eye"></i>Vorschau</a></li>';
            if (checkCapabilities('file:update', $USER->role_id, false)){
                $content .=   '<li><a href="#" onclick="formloader(\'file\',\'edit\','.$f->id.');"><i class="fa fa-edit"></i>bearbeiten</a></li>';
            }
            if ($f->type != '.url'){
                $content .= '<li><a href="'.$f->getFileUrl().'"><i class="fa fa-cloud-download"></i>herunterladen</a></li>';
            }    
            if (checkCapabilities('file:delete', $USER->role_id, false)){
                $content .=   '  <li class="divider"></li>
                          <li><a href="#" onclick="del(\'file\','.$f->id.');"><i class="fa fa-trash"></i>löschen</a></li>';
            }
            $content .=  '</ul></div></td>';
            $content .= '<td>'.$f->filename.'</td>';
            $content .= '<td>'.$f->title.'</td>';
            //$content .= '<td>'.$f->description.'</td>';
            $content .= '<td>'.$f->creation_time.'</td>';
            if ($f->type == '.url'){
                $content .= '<td>-</td>';
            } else {
                $content .= '<td>'.$f->getHumanFileSize().'</td>';
            }
            $content .= '<td>'.$f->type.'</td>';
           
            $content .= '</tr>';
        }
        $content .= '</tbody></table>';
        return $content;
    }
    
    public static function flist($files, $target ){
        $content = '';
        foreach ($files as $f) {
            $content .= RENDER::thumb(array('file_list' => array('id' => $f->id), 'target' => $target,'tag' => 'div', 'format' => 'xs')); 
        }
        //$content .= '</div>';
        return $content;
    }
    
    public static function courseBook($coursebook){
        global $USER;
        $r       = '<div style="overflow:hidden;"><div id="coursebook" style="overflow:auto;">';
        $r      .= '<ul class="timeline" >';
        $p_date  = '';
        foreach ($coursebook as $cb) {
            if ($p_date != date("d.m.Y", strtotime($cb->creation_time))){ //only print time label if last artefact timestamp neq this timestamp
                $r      .= '<li class="time-label">
                                <span class="bg-red">
                                    '.date("d.m.Y", strtotime($cb->creation_time)).'
                                </span>
                            </li>';
                $p_date = date("d.m.Y", strtotime($cb->creation_time));
            }
            $r      .= '<li>
                        <i class="fa fa-check bg-green"></i>';
            //$r      .= element('coursebook', $cb);
            
            $r      .= '<div class="timeline-item " style="border-color: #f4f4f4;border-style: solid;border-width: 1px;">
                          <span class="time" onclick="del(\'courseBook\','.$cb->id.');"><i class="fa fa-trash-o"></i></span>
                          <span class="time" onclick="processor(\'print\',\'courseBook\','.$cb->id.');"><i class="fa fa-print"></i></span>
                          <span class="time" onclick="formloader(\'courseBook\',\'edit\','.$cb->id.');"><i class="fa fa-edit"></i></span>
                          <span class="time"><i class="fa fa-clock-o"></i> '.$cb->creation_time.'</span>
                          
                          <h3 class="timeline-header"><a href="#">';
                          if (isset($cb->curriculum)){
                          $r      .=     $cb->curriculum;
                          }  
            $r      .= '                </a> '.$cb->creator.'</h3>
                          <div class="timeline-body">
                              <h4>'.$cb->topic.'</h4> 
                             '.$cb->description.'
                          </div>
                          
                          <!--div class="timeline-footer"-->';
            $r      .=    Render::todoList($cb->task, 'coursebook', $cb->id);
            if (checkCapabilities('absent:update', $USER->role_id, false)){
                $r  .=    Render::absentListe($cb->absent_list, 'coursebook', $cb->id);
            }
            $r      .= ' </div></li>';
        }
        $r      .= '    <li>
                            <i class="fa fa-calendar-plus-o bg-gray" onclick="formloader(\'courseBook\',\'new\');"></i>
                        </li>
                    </ul><!-- timleline -->
                    </div></div>';
        return $r;
                
    }
    
    public static function todoList($task, $context, $reference_id, $add=true, $checkbox=false, $onclick=false, $show_description=true){
        global $USER;
        $usr     = new User();
        $r       = '<ul class="todo-list ui-sortable">';
                 foreach ($task as $tsk) {
        $r       .= ' <li class="tasklink" ';
        if ($onclick){ 
            $r   .= 'onclick="loadhtml(\'task\', '.$tsk->id.', \'task_left_col\', \'task_right_col\', \'col-xs-12 col-lg-6\', \'col-xs-12 col-lg-6\');">';
        }
            $r   .= '<!--span class="handle ui-sortable-handle">
                        <i class="fa fa-ellipsis-v"></i>
                        <i class="fa fa-ellipsis-v"></i>
                      </span-->';
                      if ($checkbox == true AND checkCapabilities('task:accomplish', $USER->role_id, false)){
                      $r       .= '<input type="checkbox" value="" name="" onchange="processor(\'accomplish\',\'task\', '.$tsk->id.');"';
                                if (isset($tsk->accomplished->status_id)){
                                    if ($tsk->accomplished->status_id == 2){
                                        $r  .= 'checked';
                                    }
                                }
                      $r       .= '>';
                      }
                      $r       .= '<span class="text">'.$tsk->task.'</span>
                      <img src="../share/accessfile.php?id='.$usr->resolveUserId($tsk->creator_id, 'avatar').'" class="img-responsive img-circle img-sm pull-right" data-toggle="tooltip" title="'.$tsk->creator.'" style="margin-top:-5px;" alt="User Image">    
                      <span class="label label-primary pull-right margin-r-5"><i class="fa fa-clock-o"></i> '.$tsk->timeend.'</span>';
                      
                      if ($show_description){
                            $r .=  '<br><span class="text small">'.$tsk->description.'</span>';
                      }
                        if (checkCapabilities('task:update', $USER->role_id, false)){
                            $r   .= '<div class="tools">
                                        <i class="fa fa-edit" onclick="formloader(\'task\',\'edit\', '.$tsk->id.')"></i>
                                        <i class="fa fa-trash-o" onclick="del(\'task\', '.$tsk->id.')"></i>
                                    </div>';
                        }
        $r       .= '</li>';
                 }
                 
        if (checkCapabilities('task:add', $USER->role_id, false) AND $add == true){            
            $r   .= '<li><a class="btn btn-primary btn-xs" onclick="formloader(\'task\',\''.$context.'\', '.$reference_id.')"><i class="fa fa-plus"></i> Aufgabe/Notiz hinzufügen</a></li> </ul>';
        }
        return $r;
    }
    
    public static function taskList($dependency, $id, $heading){
        $t      = new Task();
        $tasks  = $t->get($dependency, $id);
        $r = '';
        if (!empty($tasks)){
            $r .= "<h4>{$heading}</h4>";
            $r .= Render::todoList($tasks, $dependency, $id, true, true, true, false);
            $r .= '<hr>';
        } 
       
        return $r;
    }
    
    public static function absentListe($absent, $context, $reference_id){
        global $CFG;
        $r      = '<div style="padding:10px;"><span>Abwesend<span><ul class="products-list product-list-in-box ui-sortable-handle">';
                  foreach ($absent as $ub) {
        $r     .= '<li class="item" >
                      <div class="product-img">
                        <img src="'.$CFG->access_id_url.$ub->user->avatar_id.'" alt="Product Image">
                      </div>
                      <div class="product-info">
                        <a href="javascript::;" class="product-title">'.$ub->user->firstname.' '.$ub->user->lastname.' ('.$ub->user->username.')'; 
                        switch ($ub->status) {
                            case 0: $r     .= '<span class="label label-danger pull-right"> unentschuldigt </span>';
                                break;
                            case 1: $r     .= '<span class="label label-success pull-right">'.$ub->done.'</span>';      
                                break;

                            default:
                                break;
                        }
        $r     .= '     </a>
                        <span class="product-description">'.$ub->reason.'</span>
                        <span class="pull-right"><i class="fa fa-edit" onclick="formloader(\'absent\',\'edit\', '.$ub->id.')"></i>
                        <i class="fa fa-trash-o" onclick="del(\'absent\', '.$ub->id.')"></i></span>
                      </div>
                    </li><!-- /.item -->';
                  }
                    
        $r     .= '<li><a class="btn btn-primary btn-xs" onclick="formloader(\'absent\',\''.$context.'\', '.$reference_id.')"><i class="fa fa-plus"></i> Fehlende Personen erfassen</a></li> </ul></div>';
        return $r;
    }
    
    /* Todo: add more config options*/
    public static function moodle_block($params){ 
        global $USER, $CFG;
        $width  = '';//'col-md-4';
        $status = '';
        foreach($params['blockdata'] as $key => $val) {
            $$key = $val;
        }
        if ($USER->role_id === $role_id OR $role_id == $CFG->settings->standard_role){
        $html  = '<div id="block_instance_'.$id.'" class="'.$width.' sortable">
                    <div class="box '.$status.' bottom-buffer-20">
                        <div class="box-header with-border">
                              <h3 class="box-title">'.$name.'</h3>
                              <div class="box-tools pull-right">';
                                if (checkCapabilities('block:add', $USER->role_id, false)){
                                    $html  .= '<button class="btn btn-box-tool" data-widget="edit" onclick="formloader(\'block\',\'edit\','.$id.');"><i class="fa fa-edit"></i></button>';
                                }
                                $html  .= '<button class="btn btn-box-tool" data-widget="collapse" onclick="processor(\'config\',\'collapse\',\''.$id.'\');">';
                                        if ($status == ''){
                                            $html  .= '<i class="fa fa-compress"></i></button>';
                                        } else {
                                            $html  .= '<i class="fa fa-expand"></i></button>';
                                        }
                                    $html  .= '<button class="btn btn-box-tool" data-widget="remove" onclick="processor(\'config\',\'remove\', '.$id.');"><i class="fa fa-times"></i></button>
                              </div>
                        </div><!-- /.box-header -->
                        <div class="box-body text-center">
                            <form target="_blank" action="'.$configdata.'" method="post">
                               <div class="form-group has-feedback">
                                 <input type="text" name="username" class="form-control" placeholder="Benutzername">
                                 <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                               </div>
                               <div class="form-group has-feedback">
                                 <input type="password" name="password" class="form-control" placeholder="Passwort">
                                 <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                               </div>
                               <div class="row">
                                 <div class="col-xs-4">
                                   <button type="submit" class="btn btn-primary btn-block btn-flat">Anmelden</button>
                                 </div><!-- /.col -->
                               </div>
                             </form>
                        </div>
                    </div>
               </div>';
            if ($visible == 1){
                return $html; 
            }
        }
    }
    
    public static function html_block($params){ 
        global $USER,$CFG;
        $width  = '';//'col-md-4';
        $status = '';
        foreach($params['blockdata'] as $key => $val) {
            $$key = $val;
        }
        if ($USER->role_id === $role_id OR $role_id == $CFG->settings->standard_role){
            $html  = '<div id="block_instance_'.$id.'" class="'.$width.' sortable">
                        <div class="box '.$status.' bottom-buffer-20">
                            <div class="box-header with-border">
                                  <h3 class="box-title">'.$name.'</h3>
                                  <div class="box-tools pull-right">';
                                    if (checkCapabilities('block:add', $USER->role_id, false)){
                                        $html  .= '<button class="btn btn-box-tool" data-widget="edit" onclick="formloader(\'block\',\'edit\','.$id.');"><i class="fa fa-edit"></i></button>';
                                    }
                                    $html  .= '<button class="btn btn-box-tool" data-widget="collapse" onclick="processor(\'config\',\'collapse\', '.$id.');">';
                                        if ($status == ''){
                                            $html  .= '<i class="fa fa-compress"></i></button>';
                                        } else {
                                            $html  .= '<i class="fa fa-expand"></i></button>';
                                        }
                                    $html  .= '<button class="btn btn-box-tool" data-widget="remove" onclick="processor(\'config\',\'remove\', '.$id.');"><i class="fa fa-times"></i></button>
                                  </div>
                            </div><!-- /.box-header -->
                            <div class="box-body">
                                '.$configdata.'
                            </div>
                        </div>
                   </div>';

            if ($visible == 1){
                return $html; 
            }
        }
    }
    public static function event_block($params){ 
        global $USER,$CFG;
        $width  = '';//'col-md-4';
        $status = '';
        foreach($params['blockdata'] as $key => $val) {
            $$key = $val;
        }
        /*get upcoming events*/
        $events = new Event();
        $upcoming_events = $events->get('upcoming', $USER->id, '', 5);
        if ($USER->role_id === $role_id OR $role_id == $CFG->settings->standard_role){
            if (isset($upcoming_events)){
                $html ='';
                foreach ($upcoming_events AS $ue){
                $html  .= '<div id="block_instance_'.$id.'" class="alert alert-warning alert-dismissible sortable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h4 class="alert-heading"><i class="fa fa-calendar"></i> '.$ue->event.'</h4>
                        <p>'.$ue->timestart.' - '. $ue->timeend.'</p>
                        <p>'.$ue->description.'</p><br>    
                </div>';
                }
            }

            if ($visible == 1){
                return $html; 
            }
        }
    }
    public static function task_institution_block($params){
       /*TODO: Show last institution tasks for global admins*/
    }
    public static function task_block($params){ 
        global $USER,$CFG;
        $width  = '';//'col-md-4';
        $status = '';
        
        foreach($params['blockdata'] as $key => $val) {
            $$key = $val;
        }
        /*get upcoming events*/
        $task          = new Task();
        $upcoming_tasks = $task->get('upcoming', $USER->id);
        if ($USER->role_id === $role_id OR $role_id == $CFG->settings->standard_role){
            if (!empty($upcoming_tasks)){
                $html ='<div id="block_instance_'.$id.'" class="box box-widget widget-user bottom-buffer-20 sortable">
                  <div class="widget-user-header bg-green">
                    <i class="pull-right fa fa-tasks" style="font-size: 70px;"></i>
                    <h3 class="widget-user-username">'.$name.'</h3>
                    <h5 class="widget-user-desc"></h5>
                  </div>
                  <div class="box-footer no-padding">
                    <ul class="nav nav-stacked" style="overflow: scroll;  width: 100%; max-height: 200px;">';
                        foreach ($upcoming_tasks AS $tsk){
                            $html  .= '<li><a><strong>'.$tsk->task.'</strong>
                                       <input type="checkbox" class="pull-right" onchange="processor(\'accomplish\',\'task\', '.$tsk->id.');" ';
                                        if (isset($tsk->accomplished->status_id)){
                                            if ($tsk->accomplished->status_id == 2){
                                                $html  .= 'checked';
                                            }
                                        }
                                        $html  .= '><p>'.$tsk->timestart.' - '.$tsk->timeend.'</p>';
                                     
                                if (isset($tsk->accomplished->status_id)){
                                    if ($tsk->accomplished->status_id == 2){
                                        $html  .= '<p class="text-green">Erledigt am '.$tsk->accomplished->accomplished_time.'</p>';
                                    }
                                }
                                $html  .= '<div>'.$tsk->description.'</div>
                                </a>
                            </li>';
                        }
                    $html  .= '</ul>
                  </div>
                </div>';
            }

            if ($visible == 1 AND isset($html)){
                return $html; 
            }
        }
    }
   
    public static function statistic_block($params){ 
        global $USER,$CFG;
        $width  = '';//'col-md-4';
        $status = '';
        foreach($params['blockdata'] as $key => $val) {
            $$key = $val;
        }
        /*get upcoming events*/
        $stat_users_online   =  $USER->usersOnline($USER->institutions);  
        $statistics         = new Statistic();
        $stat_acc_all       = $statistics->getAccomplishedObjectives('all');  
        $stat_acc_today     = $statistics->getAccomplishedObjectives('today');  
        $stat_users_today   = $statistics->getUsersOnline('today');  
        if ($USER->role_id === $role_id OR $role_id == $CFG->settings->standard_role){
            if (isset($stat_users_online)){
                $html ='<div id="block_instance_'.$id.'" class="box bottom-buffer-20 sortable">
                <div class="box-header with-border">
                  <h3 class="box-title">'.$name.'</h3>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse" onclick="processor(\'config\',\'collapse\', '.$id.');">';
                                        if ($status == ''){
                                            $html  .= '<i class="fa fa-compress"></i></button>';
                                        } else {
                                            $html  .= '<i class="fa fa-expand"></i></button>';
                                        }
                                    $html  .= '<button class="btn btn-box-tool" data-widget="remove" onclick="processor(\'config\',\'remove\', '.$id.');"><i class="fa fa-times"></i></button>
                  </div>
                </div><!-- /.box-header -->
                <div class="box-footer no-padding">
                  <ul class="nav nav-pills nav-stacked" style="overflow: scroll; width: 100%; max-height: 400px;">
                    <li><a href="#"><strong>Erreichte Ziele</strong></a></li>
                    <li><a href="#">Gesamt <span class="pull-right text-green">'.$stat_acc_all.'</span></a></li>
                    <li><a href="#">davon Heute <span class="pull-right text-green">'.$stat_acc_today.'</span></a></li>
                  </ul>';
                  
                  if (checkCapabilities('page:showCronjob', $USER->role_id, false)){
                    $html .=  '<ul class="nav nav-pills nav-stacked">
                          <li><a href="#"><strong>Wiederholungen</strong></a></li>
                          <li><a href="#">Ziele die wiederholt werden müssen<span class="pull-right text-red">0 (deaktiviert)</span></a></li>
                      </ul>';
                  }
                  $html .=  '<ul class="nav nav-pills nav-stacked">
                    <li><a href="#"><strong>online</strong></a></li>
                    <li><a href="#">jetzt online <span class="pull-right"> '.$stat_users_online.'</span></a></li>
                    <li><a href="#">heute <span class="pull-right"> '.$stat_users_today.'</span></a></li>
                  </ul>
                  
                </div><!-- /.footer -->
            </div><!-- /.box --> ';
            }

            if ($visible == 1){
                return $html; 
            }
        }
    }
    
    
    public static function accomplishedObjectives_block($params){ 
        global $USER,$CFG, $BOX_BG;
        $width  = '';//'col-md-4';
        $status = '';
        foreach($params['blockdata'] as $key => $val) {
            $$key = $val;
        }
        $acc_obj            = new EnablingObjective();
        $enabledObjectives  = $acc_obj->getObjectiveStatusChanges(); /* Load last accomplished Objectives */
        $statistics         = new Statistic();
        $stat_user_all      = $statistics->getAccomplishedObjectives('user_all');
        if ($USER->role_id === $role_id OR $role_id == $CFG->settings->standard_role){
            $html  = '<div id="block_instance_'.$id.'" class="'.$width.' sortable">
                        <div class="box '.$status.' bottom-buffer-20">
                            <div class="box-header with-border">
                            <h3 class="box-title">'.$name.'</h3>
                            <div class="box-tools pull-right">
                              <button class="btn btn-box-tool" data-widget="collapse" onclick="processor(\'config\',\'collapse\', '.$id.');">';
                                        if ($status == ''){
                                            $html  .= '<i class="fa fa-compress"></i></button>';
                                        } else {
                                            $html  .= '<i class="fa fa-expand"></i></button>';
                                        }
                                    $html  .= '<button class="btn btn-box-tool" data-widget="remove" onclick="processor(\'config\',\'remove\', '.$id.');"><i class="fa fa-times"></i></button>
                            </div>
                          </div><!-- /.box-header -->
                      <div class="box-body" style="overflow: scroll; width: 100%; max-height: 300px;">';
                      if (isset($stat_user_all)){
                        if ($stat_user_all > 0){
                                $html  .= "Du hast schon <strong>$stat_user_all</strong> Ziele erreicht.<br><br>";
                        }
                      }    
                      if (isset($enabledObjectives)){
                      $html  .= 'In den vergangenen <strong>'.$USER->acc_days.'</strong> Tagen haben die folgende Ziele den Status geändert.';
                          foreach ($enabledObjectives AS $enaid => $ena){
                              $html  .= '<div class="callout bg-'.$ena->accomplished_status_id.'">
                                  <p><strong>'.$ena->curriculum.'</strong><span class="badge pull-right" data-toggle="tooltip" title="Lernstand gesetzt von ...">'.$ena->accomplished_teacher.'</span></p>
                                  '.strip_tags($ena->enabling_objective).'
                              </div>';
                          }
                      } else {
                          $html  .= '<p>In den letzten <strong>'.$USER->acc_days.'</strong> Tagen hast du keine Ziele abgeschlossen.</p>';
                      }
                      $html  .= '</div>
                        </div>
                   </div>';

            if ($visible == 1){
                return $html; 
            }
        }
    }
    
    public static function my_institution_block($params){ 
        global $USER,$CFG;
        $width  = '';//'col-md-4';
        $status = '';
        foreach($params['blockdata'] as $key => $val) {
            $$key = $val;
        }
        $institution        = new Institution();
        $myInstitutions     = $institution->getStatistic($USER->id); /* Institution / Schulen laden */
        if ($USER->role_id === $role_id OR $role_id == $CFG->settings->standard_role){
            $html  = '<div id="block_instance_'.$id.'"  class="box box-widget widget-user bottom-buffer-20 sortable">
                      <!-- Add the bg color to the header using any of the bg-* classes -->
                      <div class="widget-user-header bg-yellow">
                        <i class="pull-right fa fa-university" style="font-size: 70px;"></i>
                        <h4 class="widget-user-username">'.$name.'</h4>
                      </div>
                      <div class="box-footer no-padding">
                        <ul class="nav nav-stacked" style="overflow: scroll; width: 100%; max-height: 200px;">';
                            if ($USER->enrolments){
                                foreach ($myInstitutions AS $insid => $ins){
                                    $html  .= '<li><a>'.$ins->institution.'
                                        <small class="label pull-right bg-primary">
                                            <i class="fa fa-user" data-toggle="tooltip" title="Schüler">';
                                                if (isset($ins->statistic[$CFG->settings->standard_role])){
                                                    $html .= $ins->statistic[$CFG->settings->standard_role];
                                                } else {
                                                    $html .= '0';
                                                }
                                            $html  .= '</i>
                                        </small>
                                        <small class="label pull-right bg-primary margin-r-5">
                                            <i class="fa fa-check-circle-o" data-toggle="tooltip" title="Erreichte Ziele">';
                                                if (isset($ins->statistic['accomplished'])){
                                                    $html .= $ins->statistic['accomplished'];
                                                } else {
                                                    $html .= '0';
                                                }
                                            $html  .= '</i>
                                        </small>
                                        <small class="label pull-right bg-primary margin-r-5">
                                            <i class="fa fa-graduation-cap" data-toggle="tooltip" title="Lehrer">';
                                                if (isset($ins->statistic['7'])){
                                                    $html  .= $ins->statistic['7'];
                                                } else {
                                                    $html  .= '0';
                                                }
                                            $html  .= '</i>
                                        </small>
                                        <br><small>'.$ins->description.'</small>
                                    </a></li>';
                                }
                            }
                        $html  .= '</ul>
                      </div>
                    </div>';

            if ($visible == 1){
                return $html; 
            }
        }
    }
    
    public static function my_class_block($params){ 
        global $USER,$CFG;
        $width  = '';//'col-md-4';
        $status = '';
        foreach($params['blockdata'] as $key => $val) {
            $$key = $val;
        }
        $groups          = new Group(); 
        $myClasses       = $groups->getGroups('user', $USER->id);
        if ($USER->role_id === $role_id OR $role_id == $CFG->settings->standard_role){
            $html = ''; 
            if (isset($myClasses)){
                
                    $html .= '<div id="block_instance_'.$id.'" class="box box-widget widget-user bottom-buffer-20 sortable">
                                <div class="widget-user-header bg-yellow">
                                  <i class="pull-right fa fa-group" style="font-size: 70px;"></i>
                                  <h3 class="widget-user-username">'.$name.'</h3>
                                  <h5 class="widget-user-desc"></h5>
                                </div>
                                <div class="box-footer no-padding" id="groups_accordion">
                                  <ul class="nav nav-stacked" style="overflow: scroll; width: 100%; max-height: 200px;">';
                                    foreach ($myClasses AS $claid => $cla){
                                        $html .= '<li class="panel"><a data-toggle="collapse" data-parent="#groups_accordion" href="#collapse_'.$cla->id.'">'.$cla->group. '<br><small>'.$cla->institution_id.'</small> </a>';
                                                    if ($USER->enrolments){
                                                        $html .= '<ul id="collapse_'.$cla->id.'" class="panel-collapse collapse">';
                                                        foreach ($USER->enrolments AS $cur_menu){
                                                            if ($cur_menu->group_id == $cla->id){
                                                                $html .= '<li><a href="index.php?action=view&curriculum_id='.$cur_menu->id.'&group='.$cur_menu->group_id.'">'.$cur_menu->curriculum.' </a></li>';
                                                            }
                                                        }
                                                        $html .= '</ul>';
                                                    }
                                        $html .= '</li>';
                                    }    
                                  $html .= '</ul>
                                </div>
                              </div>';     
                  
            }

            if ($visible == 1){
                return $html; 
            }
        }
    }
    
    public static function blog_block($params){ 
        global $USER,$CFG;
        $width  = '';//'col-md-8';
        $height = '400px';
        $status = '';
        foreach($params['blockdata'] as $key => $val) {
            $$key = $val;
        }
        $blog = new Blog();
        $blog->load($id);
        $author = new User();
        if ($USER->role_id === $role_id OR $role_id == $CFG->settings->standard_role OR $USER->role_id == '1'){
            $html  = '<div id="block_instance_'.$id.'" class="'.$width.' sortable">
                        <div class="box '.$status.' bottom-buffer-20">
                            <div class="box-header with-border">
                                  <h3 class="box-title">'.$name.'</h3>
                                  <div class="box-tools pull-right"><input id="block_instance_'.$id.'_search" type="text" name="search"  placeholder="Blog durchsuchen" onchange="search(document.getElementById(\'block_instance_'.$id.'_search\').value, \'block_instance_'.$id.'_body\');">';
                                    if (checkCapabilities('block:add', $USER->role_id, false)){
                                        $html  .= '<button class="btn btn-box-tool" onclick=\'formloader("content", "new", null,{"context_id":"21", "reference_id":'.$id.'});\'><i class="fa fa-plus"></i></button>';
                                        $html  .= '<button class="btn btn-box-tool" data-widget="edit" onclick="formloader(\'block\',\'edit\','.$id.');"><i class="fa fa-edit"></i></button>';
                                    }
                                    $html  .= '<button class="btn btn-box-tool" data-widget="collapse" onclick="processor(\'config\',\'collapse\', '.$id.');">';
                                        if ($status == ''){
                                            $html  .= '<i class="fa fa-compress"></i></button>';
                                        } else {
                                            $html  .= '<i class="fa fa-expand"></i></button>';
                                        }
                                    $html  .= '<button class="btn btn-box-tool"  onclick="processor(\'config\',\'remove\', '.$id.');"><i class="fa fa-times"></i></button>
                                                </div>
                            </div><!-- /.box-header -->
                            <div id="block_instance_'.$id.'_body" class="box-body" style="overflow: scroll; width: 100%; max-height: '.$height.';">';
                                    foreach ($blog->content as $value) {
                                        $author->load('id', $value->creator_id);
                                        $comments = new Comment();
                                        $comments->context = 'content';
                                        $comments->reference_id = $value->id;
                                        $c = $comments->get('reference');
                                        $c_max = count($c);
                                        $html  .=  '<div class="post">
                                                        <div class="user-block">
                                                          <img class="img-circle img-bordered-sm" src="'.$CFG->access_id_url.$author->avatar_id.'" alt="user image">
                                                              <span class="username">
                                                                <a href="#">'.$value->creator.'</a>
                                                                <a href="#" class="pull-right btn-box-tool"></a>
                                                              </span>
                                                          <span class="description">'.$value->timecreated.'</span>
                                                        </div>
                                                        <!-- /.user-block -->
                                                        <b>'.$value->title.'</b>
                                                        '.$value->content.'
                                                        <ul class="list-inline">
                                                          <li><!-- a href="#" class="link-black text-sm"><i class="fa fa-share margin-r-5"></i> Share</a --></li>
                                                          <li><!-- a href="#" class="link-black text-sm"><i class="fa fa-thumbs-o-up margin-r-5"></i> Like</a --></li>
                                                          <li class="pull-right">
                                                            <a class="link-black text-sm" onclick="toggle([\'comments_'.$value->id.'\'])"><i class="fa fa-comments-o margin-r-5"></i> Kommentare
                                                              ('.$c_max.')</a></li>
                                                        </ul>
                                                        <div class="bottom-buffer-20 hidden" id="comments_'.$value->id.'"><b>Kommentare</b>';
                                        $html  .=       RENDER::comments(["comments" => $c, "permission" => '1']); //todo permission over rolecheck
                                        $html  .=     '<textarea id="comment" name="comment"  style="width:100%;"></textarea>
                                                       <p><button type="submit" class="btn btn-primary pull-right" onclick="comment(\'new\','.$value->id.', 15, document.getElementById(\'comment\').value);">
                                                           <i class="fa fa-commenting-o margin-r-10"></i>Kommentar abschicken</button></p><br>
                                                       </div>
                                                    </div>';
                                    }    
             $html  .= '     </div>
                        </div>
                   </div>';

            if ($visible == 1){
                return $html; 
            }
        }
    }
    
    
    
    
    /*Simple table - example see userImport.tpl*/
    public static function table($params){
        $width_class     = 'col-md-6';
        $style           = '';
        $table_class     = 'table table-bordered';
        $cell_style      = '';
                
        foreach($params as $key => $val) {
            $$key = $val;
        }
        
        $html = '<div class="'.$width_class.'" style="'.$style.'">
                    <table class="'.$table_class.'">
                        <tbody>
                            <tr>';
                            foreach($header as $v) {
                                $html .= '<th style='.$cell_style.'>'.$v.'</th>';
                            }   
                            $html .='</tr>';
                            foreach($data as $d) {
                                $html .='<tr>';
                                foreach($header as $k => $v) {
                                    $html .= '<td style='.$cell_style.'>'.$d->$k.'</td>';
                                } 
                                $html .='</tr>';
                            }
                    $html .='</tbody>
                    </table>
                </div>';
        return $html;
    }
    
    public static function split_button($params){
        $label      = 'Auswahl';
        $btn_type   = 'btn btn-default btn-flat';
        $type       = 'content';
        foreach($params as $key => $val) {
            $$key = $val;
        }
        if (count($entrys) > 0){ // only show if entrys exists
            $html   =  '<div class="btn-group">
                            <button type="button" class="'.$btn_type.' dropdown-toggle" data-toggle="dropdown">'.$label.' </button>
                            <ul class="dropdown-menu" role="menu">';
                            foreach($entrys as $key => $val) {
                                switch ($type) {
                                    case 'content':
                                        $html .= '<li><a onclick="formloader(\'content\', \'show\','.$val->id.');">'.$val->title.'</span></a></li>';
                                    break;
                                    case 'file':
                                        $html .= '<li><a onclick="formloader(\'preview\',\'file\','.$val->id.');">'.$val->title.'</span></a></li>';
                                    break;
                                    case 'menu':
                                        if (isset($val->href)){
                                            $html .= '<li><a href="'.$val->href.'" class="'.$val->href_class.'">'.$val->title.'</span></a></li>';
                                        } else {
                                            $html .= '<li><a onclick="'.$val->onclick.'">'.$val->title.'</span></a></li>';
                                        }
                                    break;
                                    
                                    default:
                                        break;
                                }
                                
                            }
            $html  .=      '</ul>
                        </div>';
            return $html;
        }
    }
    
    public static function box($params){
        $status                     = 'collapsed-box';
        $header_content             = 'Title';
        $header_box_tools_left      = '';
        $header_box_tools_right     = '';
        
        $body_content               = '';
        //$footer_content     = '';
        
        foreach($params as $key => $val) {
            $$key = $val;
        }
        $html   = '';
        $html  .= '<div class="box '.$status.' bottom-buffer-20">
                            <div class="box-header with-border">
                                  '.$header_box_tools_left.'
                                  <h3 class="box-title"> '.$header_content.'</h3>
                                  
                                  <div class="box-tools pull-right">
                                  '.$header_box_tools_right.'
                                  </div>
                            </div><!-- /.box-header -->
                            <div class="box-body" >
                                '.$body_content.'
                            </div>';
              if (isset($footer_content)){
              $html  .= '   <div class="box-footer">
                                '.$footer_content.'
                            </div>';
              }
        $html  .= '</div>';
        return $html;
    }
    
    public static function carousel($params){
        $carousel_id = 'carousel';
        $data_ride   = 'carousel';
        $slides     = array(); //[caption => '...', content => '...']
        foreach($params as $key => $val) {
            //error_log($key.' -> '. $val);
            $$key = $val;
        }
        $html = '<div id="'.$carousel_id.'" class="carousel slide" data-ride="'.$data_ride.'" >
                    <ol class="carousel-indicators">';
                    $active_class = 'active';
                    $i = 0; 
                    foreach ($slides as $s) {
                        $html .= '<li data-target="#'.$carousel_id.'" data-slide-to="'.$i.'" class="'.$active_class.'"></li>';
                        if ($active_class == 'active'){ $active_class = ''; }
                        $i++;
                    }
        $html .='   </ol>
                <div class="carousel-inner" >';
                    $active_class = 'active';
                    $i = 0; 
                    foreach ($slides as $s) {
                        $html .= '<div class="item '.$active_class.'" >
                                    <div style="overflow: scroll;  height: 400px;">'.$s->content.'</div>
                                    <div class="carousel-caption">'.$s->caption.'</div>
                                </div>';
                        if ($active_class == 'active'){ $active_class = ''; }
                        $i++;
                    }
        $html .='</div>
                <a class="left carousel-control" href="#'.$carousel_id.'" data-slide="prev">
                  <span class="fa fa-angle-left"></span>
                </a>
                <a class="right carousel-control" href="#'.$carousel_id.'" data-slide="next">
                  <span class="fa fa-angle-right"></span>
                </a>
              </div>';
        return $html;
    }


    public static function navigator_item($params){
        global $CFG, $USER;
        
        foreach($params as $key => $val) {
            //error_log($key.' -> '. $val);
            $$key = $val;
        }
        $html = '';
        if ($nb_visible == 1){  //is visible?
            switch ($nb_context_id) {
                /*curriculum*/
                case 2:     $cur                = new Curriculum();
                            $cur->id            = $nb_target; 
                            $cur->load(false);
                            $enroled_groups     = $cur->getGroupsByUserAndCurriculum($USER->id);
                            $file_id            = $cur->icon_id;
                            $widget_onclick     = "location.href='index.php?action=view&curriculum_id={$nb_target}&group={$enroled_groups[0]->group_id}';";
                            $html = RENDER::paginator_widget(array('widget_title' => $nb_title, 'file_id' => $file_id, 'widget_onclick' => $widget_onclick, 'global_onclick' => true));
                    break;
                case 15:    $content            = new Content();
                            $content->load('id', $nb_reference_id);
                            $html              .= RENDER::box(array('header_box_tools_right' => '<button class="btn btn-box-tool" onclick="processor(\'print\',\'content\', \''.$nb_reference_id.'\')"><i class="fa fa-print"></i></button><button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-expand"></i></button>', 'header_content' => $content->title, 'content_id' => $nb_reference_id, 'body_content' => $content->content));
                    break; 
                case 16:    $c                  = new Curriculum();
                            $curricula          = $c->getCurricula('group', $nb_reference_id);
                            foreach ($curricula as $cur) {
                                $html          .= RENDER::paginator_widget(["widget_title" =>$cur->curriculum, "ref_id" => $cur->id, "group_id" => $nb_reference_id, 'global_onclick' => true]);
                            }

                    break;

                case 29:    $f                  = new File();
                            $f->load($nb_reference_id);
                            $html               = '<div class="'.$nb_width_class.'">'.RENDER::file($f).'</div>';
                    break;
                case 31:    if ($nb_target_context_id == $_SESSION['CONTEXT']['file']->context_id){
                                $f                  = new File();
                                $f->load($nb_target);
                                $widget_onclick     = "location.href='{$f->path}'";
                            } else {
                                $widget_onclick     = "location.href='index.php?action=navigator&nv_id={$nb_target}';";
                            }

                            $html               = RENDER::paginator_widget(array('widget_title' => $nb_title, 'file_id' => $nb_file_id, 'widget_onclick' => $widget_onclick, 'global_onclick' => true));

                    break;

                case 33:    /* Book */
                            $html               = RENDER::book(array('book_id' => $nb_reference_id));
                            //$html               = RENDER::book(array('book_id' => $b->id, 'book_title' => $b->title, 'toc'=> $toc, 'book_content' => RENDER::carousel(array('data_ride' => '', 'slides' => $s))));
                            //$html = RENDER::carousel(array('data_ride' => '', 'slides' => $s));

                    break;

                default:
                    break;
            }
        } 
        return $html;     
    }
    
    public static function book($params){
        foreach($params as $key => $val) {
            $$key = $val;
        }
        $b                  = new Book();
        $book               = $b->get('book', $book_id);
        
        $s                  = array();
        $toc                = array(); //Table of Contents
        $i = 0;
        foreach($book AS $content){
            $slide             = new stdClass();
            $ct                = new Content();
            $ct->load('id', $content->content_id);
            $slide->caption    = $ct->title;
            $toc[$i]['id']     = $content->content_id;
            $toc[$i]['title']  = $ct->title;
            $i++;
            $slide->content    = $ct->content;
            $slides[]          = clone $slide;
            unset($slide);
        }
        
        $left_menu = '<i class="fa fa-bars dropdown-toggle" data-toggle="dropdown"></i><ul class="dropdown-menu" role="menu">';
        $i = 0;
        foreach($toc AS $t){
            $left_menu .= '<li><a data-slide-to="'.$i.'" data-target="#carousel">'.$t["title"].'</a></li>';
            $i++;
        }
                    
        $left_menu .='</ul>';
        $carousel_id = 'carousel';
        $data_ride   = ''; // if 'carousel' pages auto-slide

        $book_content = '<div id="'.$carousel_id.'" class="carousel slide" data-interval="false" data-ride="'.$data_ride.'" >
                    <ol class="carousel-indicators " style="bottom:5px;">';
                    $active_class = 'active';
                    $i = 0; 
                    foreach ($slides as $s) {
                        $book_content .= '<li data-target="#'.$carousel_id.'" data-slide-to="'.$i.'" ';
                        if ($active_class == 'active') { //todo --> change css class
                            $book_content .= 'style="background:#333;" ';
                        } else {
                            $book_content .= 'style="border: 1px solid #333;" ';
                        }
                        $book_content .= 'class=" '.$active_class.'"></li>';
                        if ($active_class == 'active'){ $active_class = ''; }
                        $i++;
                    }
        $book_content .='   </ol>
                <div class="carousel-inner" >';
                    $active_class = 'active';
                    $i = 0; 
                    foreach ($slides as $s) {
                        $book_content .= '<div class="item '.$active_class.'" >
                                    <div style="overflow: scroll;  height: 400px;" >'.$s->content.'</div>
                                    <div class="carousel-caption text-black" style="position:static;">'.$s->caption.'</div>
                                </div>';
                        if ($active_class == 'active'){ $active_class = ''; }
                        $i++;
                    }
        $book_content .='</div>
                <a class="left carousel-control" href="#'.$carousel_id.'" data-slide="prev">
                  <span class="fa fa-angle-left text-black" style="bottom: 22px; top: auto !important;"></span>
                </a>
                <a class="right carousel-control" href="#'.$carousel_id.'" data-slide="next">
                  <span class="fa fa-angle-right text-black" style="bottom: 22px; top: auto !important;"></span>
                </a>
              </div>';
        
        
        
        //$left_menu = '<i class="fa fa-bars dropdown-toggle" data-toggle="dropdown"></i>';
        return $html = RENDER::box(array('header_box_tools_left' => $left_menu,'header_box_tools_right' => '<button class="btn btn-box-tool" onclick="processor(\'print\',\'book\', \''.$b->id.'\')"><i class="fa fa-print"></i></button><button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-expand"></i></button>', 'header_content' => $b->title, 'content_id' => $b->id, 'body_content' => $book_content));
    }
    
    
    
    public static function paginator_widget($params){
        global $CFG;
        /*default params*/
        $class_width    = 'col-md-6';
        $widget_type    = 'curriculum';
        $bg_color       = 'bg-white';
        $widget_title   = 'Titel';
        $widget_desc    = '';
        $bg_badge       = 'bg-green';
        $href           = '#';
        //$onclick_badge  = '';
        $opt            = array(); 
        $widget_onclick = ''; 
        
        foreach($params as $key => $val) {
            $$key = $val;
        }
        if (isset($file_id)){
            $icon_url           = $CFG->access_id_url.$file_id;//.'&size=t';
        } else if (!isset($icon_url)){
            $cur                = new Curriculum();
            $cur->id            = $ref_id; 
            $cur->load(false);
            $icon_url           = $CFG->access_id_url.$cur->icon_id;//.'&size=t';
            //error_log('gr_id'.$group_id);
            $widget_onclick     = "location.href='index.php?action=view&curriculum_id={$ref_id}&group={$group_id}';";
        } 
        
        $html   =  '<div class="box box-objective bg-white '.$bg_color.'" style="height: 300px !important; padding: 0; background: url(\''.$icon_url.'\') center center;  background-size: cover;"  ';
        if (isset($global_onclick)){
            $html   .= ' onclick="'.$widget_onclick.'"';
        }
        $html   .= '>'; 
        $html   .= '                <span class="bg-white no-padding" style="background-color: #fff; position:absolute; bottom:0px; height: 120px;width:100%;text-align: center;">'
                . '<span class="col-xs-12" style="background-color: '.$bg_color.'; position:absolute; display:block; left:0;right:0;bottom:120px;" >';
        foreach ($opt as $k =>$o) {
                $html .= '<span style="margin-right:15px;padding:5px;text-shadow: 1px 1px #FF0000;" class="fa">'.$o.'</span>';    
        }
        $html .= '</span>';
                $html   .= '<div class="caption text-center">';
                if (isset($widget_timerange)){
                    $html   .= '<small>'.$widget_timerange.'</small>';
                }
                        $html   .= '  <h5 class="events-heading text-center"><a onclick="'.$widget_onclick.'">'.$widget_title.'</a></h5>
                                  <p style="width: 100%; height: 60px;">'.$widget_desc.'</p>
                                </div>';
                        
        $html   .=     '</span>
                     </div>';
        return $html;     
    }
    
    public static function box_widget($params){
        /*default params*/
        $class_width    = 'col-md-6';
        $widget_type    = 'user';
        $bg_color       = '';
        $widget_title   = 'Titel';
        $widget_desc    = 'desc';
        $bg_badge       = 'bg-green';
        $href           = '#';
        $onclick_badge  = '';
        
        // $data        == data array;
        // more params: $label, $badge, $bg_icon 
        
        foreach($params as $key => $val) {
            $$key = $val;
        }
        $html   =  '<div class="'.$class_width.'">
                    <!-- Widget: '.$widget_type.' widget style 1 -->
                    <div class="box box-widget widget-'.$widget_type.'">
                      <!-- Add the bg color to the header using any of the bg-* classes -->
                      <div class="widget-'.$widget_type.'-header bg-'.$bg_color.'">';
                      if (isset($bg_icon)){
                        $html   .= '<i class="pull-right '.$bg_icon.'" style="font-size: 90px;"></i>';
                      }
                        $html   .= '<h3 class="widget-'.$widget_type.'-username">'.$widget_title.'</h3>
                        <h5 class="widget-'.$widget_type.'-desc">'.$widget_desc.'</h5>
                        
                      </div>
                      <div class="box-footer no-padding">
                        <ul class="nav nav-stacked" style="max-height:250px;overflow:auto;">';
                        foreach($data AS $value){
                            if (strpos($label, ',')){ // more than one field in label
                            list ($field1, $field2) = explode(', ', $label);
                                $l  = $value->$field1. ' '. $value->$field2;
                            } else {
                                $l  = $value->$label;
                            }
                            global $v;
                            $v = $value;
                            $href_regex = preg_replace_callback('/__([^&]*)__/', 
                                    function($r){
                                        global $v;
                                        return $v->$r[1]; 
                                    }, $href);
                                
                            $html   .= '<li><a href="'.$href_regex.'">'.$l;
                            if (isset($badge)){
                                
                                $onclick = str_replace('__id__', $value->id, $onclick_badge);
                                $html   .= '<span class="pull-right badge '.$bg_badge.'" onclick="'.$onclick.'">';
                                if (isset($badge_title)){
                                    $html   .= $badge_title;
                                } else {
                                    $html   .= $value->$badge;
                                }
                                $html   .= '</span>';          
                            }
                            $html   .= '</a></li>';           
                        } 
        $html   .= '</ul>
                     </div>
                   </div><!-- /.widget-'.$widget_type.' -->
               </div><!-- /.col -->';
        return $html;
    }
    
    public static function compare_list($params){
        /* Params $order */
        $html = '';
        foreach($params as $key => $val) {
            $$key = $val;
        }
        foreach ($order as $key => $value) {
            if ($value['items'] > 1){ // only generate if there are items - todo check reason items == 1 and not 0
                $html .= '<div class="col-md-6 "><div class="box box-'.$value['class'].' box-solid">
                            <div class="box-header with-border">
                            <div class="box-title">'.$value['header'].'</div>';
                            if ($value['color']){
                                $html .= '<span class="pull-right badge bg-white '.$value['color'].'">Datum, Lehrer</span>';
                            }
                            $html .= '</div>
                            <div class="box-footer no-padding">
                                <ul class="nav nav-stacked">';
                                if (isset($$value['var'])){
                                    foreach($$value['var'] AS $v) {
                                        $user     = new User();
                                        $html .= '<li><a href="#">'.$user->resolveUserId($v->user_id);

                                        /*foreach ( $solutions as $s ) { 
                                            if ( $v->user_id == $s->creator_id ) {
                                                $html .= '<span onClick=\'formloader("material", "id", '.$ena_id.', {"target":"sub_popup", "user_id": "'.$v->user_id.'"});\'>&nbsp;<i class="fa fa-paperclip"></i></span>';          
                                                break; // if one solution is found break to save time
                                            }
                                        }*/

                                        if ($value['color']){
                                            $html .= '<span class="pull-right badge bg-'.$value['color'].'" data-toggle="tooltip" title="" data-original-title="Nachricht schreiben" onclick="formloader(\'mail\', \'gethelp\', '.$v->creator_id.');">'.date('d.m.Y',strtotime($v->accomplished_time)).', '.$user->resolveUserId($v->creator_id, 'name').'</span>';
                                        }
                                        $html .= '</a></li>';
                                    }   
                                }
                $html .= '</ul></div></div></div>'; 
            }
        }
        return $html;
    }
    
    public static function badge_preview($params){ 
        $s_2        = '';
        foreach($params as $key => $val) {
            $$key   = $val;
        }
        $c          = new Content();
        
        $content    = $c->get('badge_preview', $reference_id);
        
        if (count($user_id) == 1 AND isset($content[0]->content)){ 
            $s_2        = $content[0]->content;
            $enabling_objectives = new EnablingObjective();
            //Bereiche //evtl. besser über regex realisieren z.B. /<bereich value="[(\d+),]+">.+<\/bereich>/g
            $anz_bereiche           = substr_count($s_2, '<!--Bereich');
            $offset                 = 0;
            $user_id = $user_id[0];
             for ($i = 1; $i <= $anz_bereiche; $i++){    //todo doublicate in pdf.class.php --> make function to get bereichs-content
                $bereich_begin      = stripos($s_2, "<!--Bereich"     ); // besser über regex lösen
                $bereich_end        = stripos($s_2, "<!--/Bereich-->");
                $offset             = $bereich_end+15;   
                $bereich_content    = substr($s_2, $bereich_begin, $offset-$bereich_begin);
                $bereich_id_begin   = stripos($bereich_content, "{")+1; 
                $bereich_id_end     = stripos($bereich_content, "}"); 
                $bereich_id         = substr($bereich_content, $bereich_id_begin, $bereich_id_end-$bereich_id_begin); 
                if ($enabling_objectives->calcTerminalPercentage($bereich_id, $user_id) <= 0.6){// wenn nicht genügend Ziele erreicht wurden (hier 60 %) dann wird dieser Bereich ausgelassen
                    $s_2 = substr($s_2, 0, $bereich_begin) . substr($s_2, $offset); // Wenn Bedingung nicht erfüllt ist, wird Bereich ausgeschnitten  
                } else {
                    $s_2 = substr($s_2, 0, $bereich_begin) . substr($bereich_content, $bereich_id_end+4, -15) . substr($s_2, $offset);
                }
            } 
        return $s_2; 
        } else {
            
        }
    }
    
    public static function reference($func, $id, $get){
    $content     = '';
    $reference   = new Reference();
    $references  = $reference->get('reference_id', $_SESSION['CONTEXT'][$func]->context_id, $id);
    Reference::sortByProp($references, 'curriculum', 'asc');
    
    if ($get['schooltype_id'] != 'false'){
        $references  = ofilter($references, ['schooltype_id' => $get['schooltype_id']]);
    }
    if ($get['subject_id'] != 'false'){
         $references  = ofilter($references, ['subject_id' => $get['subject_id']]);
    }
    if ($get['curriculum_id'] != 'false'){
         $references  = ofilter($references, ['curriculum_id' => $get['curriculum_id']]);
    }
    if ($get['grade_id'] != 'false'){
         $references  = ofilter($references, ['grade_id' => $get['grade_id']]);
    }
    if (isset($references)){
        $subject_id = '';
        foreach ($references as $ref) {
            if ($ref->subject_id != $subject_id){
                if ($subject_id != ''){
                    $content .= '</span>';
                }
                $subject_id = $ref->subject_id;
                
                $content .= '<span class="col-xs-12 bg-light-aqua"><h4 class="text-black">'.$ref->curriculum_object->curriculum.' <small>'.$ref->schooltype.'</small><button class="btn btn-box-tool pull-right" style="padding-top:0;" type="button" data-toggle="collapse" data-target="#subject_'.$subject_id.'" aria-expanded="true" data-toggle="tooltip" title="Fach einklappen bzw. ausklappen"><i class="fa fa-expand"></i></button></h4></span><hr style="clear:both;">';
                $content .= '<span id ="subject_'.$subject_id.'" class="collapse in">';
            }
            $content .= RENDER::render_reference_entry($ref, $_SESSION['CONTEXT']['terminal_objective']->context_id);
        }
        $content .= '</span>'; //close last subject span
    } 
    
    if (count($references) == 0) {
        $content .= 'Keine Querverweise vorhanden.';
    }

    if ($get['ajax'] == 'true'){  
        echo $content;
    } else {
        return $content;
    }
}



public static function render_reference_entry($ref, $context_id){
    global $USER;
    $c  = '<div class="row">
            <div class="col-xs-12 col-sm-3 pull-left"><dt>Thema/Kompetenzbereich</dt>'.Render::objective(array('format' => 'reference', 'objective' => $ref->terminal_object, 'color')).'</div>';
            if ($ref->context_id == $_SESSION['CONTEXT']['enabling_objective']->context_id) {
              $c .= '<div class="col-xs-12 col-sm-3"><dt>Lernziel/Kompetenz</dt>'.Render::objective(array('format' => 'reference', 'type' => 'enabling_objective', 'objective' => $ref->enabling_object, 'border_color' => $ref->terminal_object->color)).'</div>';
            }
            $c .= '
           <div class="col-xs-12 col-sm-6 pull-right">';
            if (checkCapabilities('reference:add',    $USER->role_id, false, true)){
                $c .= '<a onclick="del(\'reference\', '.$ref->id.');" class="btn btn-default btn-xs pull-right" data-toggle="tooltip" title="" data-original-title="Referenz löschen" style="margin-right:5px;"><i class="fa fa-trash"></i></a>';
                //$c .= '<a onclick="formloader(\'reference\', \'edit\', '.$ref->id.', {\'context_id\': \''.$context_id.'\'});" class="btn btn-default btn-xs pull-right" data-toggle="tooltip" title="" data-original-title="Referenz editieren" style="margin-right:5px;"><i class="fa fa-edit"></i></a>';
            }
    
            $c .= '<br><dt>Anregungen zur Unterrichtsgestaltung <dd> ';
            if (isset($ref->content_object->content)){
                if ($ref->content_object->content != ''){
                    $c .= strip_tags($ref->content_object->content);
                }
            }
            $c .='</dd></dt>';
            if (checkCapabilities('reference:add',    $USER->role_id, false, true)){
                $c .= '<a onclick="formloader(\'content\', \'edit\','.$ref->content_object->id.');" class="btn btn-default btn-xs pull-right" style="margin-right:5px;"><i class="fa fa-edit"></i></a>';
            }
    $c .= '</div></div><hr style="clear:both;">';
    
    return $c;
}

public static function quote($quotes, $get){
    $content     = '';  
    RENDER::sortByProp($quotes, 'curriculum', 'asc');
    
    if ($get['schooltype_id'] != 'false'){
        $quotes  = ofilter($quotes, ['schooltype_id' => $get['schooltype_id']]);
    }
    if ($get['subject_id'] != 'false'){
         $quotes  = ofilter($quotes, ['subject_id' => $get['subject_id']]);
    }
    if ($get['curriculum_id'] != 'false'){
         $quotes  = ofilter($quotes, ['curriculum_id' => $get['curriculum_id']]);
    }
    if ($get['grade_id'] != 'false'){
         $quotes  = ofilter($quotes, ['grade_id' => $get['grade_id']]);
    }
    if (isset($quotes)){
        $cur_id = '';
        foreach ($quotes as $ref) {
            if ($ref->reference_object->id != $cur_id){
                if ($cur_id != ''){
                    $content .= '</span>';  
                }
                $cur_id = $ref->reference_object->id;
                $content .= '<span class="col-xs-12 bg-light-aqua"><h4 class="text-black">'.$ref->reference_object->curriculum.'<button class="btn btn-box-tool pull-right" style="padding-top:0;" type="button" data-toggle="collapse" data-target="#cur_'.$cur_id.'" aria-expanded="true" data-toggle="tooltip" title="Fach einklappen bzw. ausklappen"><i class="fa fa-expand"></i></button></h4></span><hr style="clear:both;">';
                $content .= '<span id ="cur_'.$cur_id.'" class="collapse in">';
            }
            $content .= '<blockquote>'.$ref->quote.'<small>'.$ref->reference_object->curriculum.', <cite="'.$ref->reference_title.'" class="pointer_hand"><a onclick="formloader(\'content\', \'show\','.$ref->quote_link.');">'.$ref->reference_title.'</a></cite></small></blockquote>'; 
        }
        $content .= '</span>'; //close last subject span
    } 
    
    if (count($quotes) == 0) {
        $content .= 'Keine Textbezüge vorhanden.';
    }

    if ($get['ajax'] == 'true'){  
        echo $content;
    } else {
        return $content;
    }
}

    public static function sorter_asc( $a, $b ){
        return strcasecmp( $a->{self::$sortKey}, $b->{self::$sortKey} );
    }
    
    public static function sorter_desc( $a, $b ){
        return strcasecmp( $b->{self::$sortKey}, $a->{self::$sortKey} );
    }

    public static function sortByProp( &$collection, $prop, $direction = 'asc' ){
        self::$sortKey = $prop;
        switch ($direction) {
            case 'desc': usort( $collection, array( __CLASS__, 'sorter_desc' ) );
                 break;

            default:    usort( $collection, array( __CLASS__, 'sorter_asc' ) );
                break;
        }
        
    }
    
    
}