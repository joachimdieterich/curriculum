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
        $link       = true;
        $email      = false; 
        $token      = false;
        $html       = '';
        $style      = 'style="font-size:18px;"';
        $options = array('green' => array('status' => 1,
                                          'tooltip'=> 'data-toggle="tooltip" title="Selbsteinschätzung: Ich kann das selbstständig."'), 
                         'orange'=> array('status' => 2, 
                                          'tooltip'=> 'data-toggle="tooltip" title="Selbsteinschätzung: Ich kann das mit Hilfe."'), 
                         'red'   => array('status' => 0, 
                                          'tooltip'=> 'data-toggle="tooltip" title="Selbsteinschätzung: Ich kann das noch nicht."'), 
                         'white' => array('status' => 3, 
                                          'tooltip'=> 'data-toggle="tooltip" title="Selbsteinschätzung: Ich habe das noch nicht bearbeitet."'));   //available options
         
        $css_green  = 'margin-r-5 text-green pointer_hand';
        $css_orange = 'margin-r-5 text-orange pointer_hand';
        $css_red    = 'margin-r-5 text-red pointer_hand';
        $css_white  = 'margin-r-5 text-gray pointer_hand';
        
        if (!is_array($params)){ //hack to use json_arrays from smarty
            $params = json_decode($params);
        }
        foreach($params as $key => $val) { $$key = $val; }   
        
        $ena       = new EnablingObjective();
        $ena->id   = $id;
        $ena->getObjectives('enabling_objective_status', $student); // get status of objective
        
        //generate css class based on accomplished status
        if (strlen($ena->accomplished_status_id) == 1){ //fallback check for older versions
            $ena->accomplished_status_id = 'x'.$ena->accomplished_status_id;
        }
        $student_bit = substr($ena->accomplished_status_id, 0,1);
        switch (true) { //Student Part of status (first char)
            case $student_bit === '0':   $red      = '-circle';
                        $green = $orange = $white = '-circle-o';
                break;
            case $student_bit === '1':   $green    = '-circle';
                        $red = $orange = $white = '-circle-o';
                break;
            case $student_bit === '2':   $orange   = '-circle';
                        $green = $red = $white = '-circle-o';
                break;
            case $student_bit === '3':   $white    = '-circle';
                        $green = $red = $orange = '-circle-o';
                break;
            
            default:    $green = $red = $orange = $white ='-circle-o';
                break;
        }
        $teacher_bit = substr($ena->accomplished_status_id, 1,1);
        switch (true) { //Teacher Part of status (second char)
            case $teacher_bit === '0':   $red      = 'fa fa-check'.$red;
                        $green    = 'fa fa'.$green;
                        $orange   = 'fa fa'.$orange;
                        $white    = 'fa fa'.$white;
                break;
            case $teacher_bit === '1':   $green    = 'fa fa-check'.$green;
                        $red      = 'fa fa'.$red;
                        $orange   = 'fa fa'.$orange;
                        $white    = 'fa fa'.$white;
                break;
            case $teacher_bit === '2':   $orange   = 'fa fa-check'.$orange;
                        $red      =  'fa fa'.$red;
                        $green    =  'fa fa'.$green;
                        $white    =  'fa fa'.$white;
                break;
            case $teacher_bit === '3':   $white    = 'fa fa-check'.$white;
                        $red      =  'fa fa'.$red;
                        $green    =  'fa fa'.$green;
                        $orange   =  'fa fa'.$orange;                        
                break;

            default:    $red      =  'fa fa'.$red;
                        $green    =  'fa fa'.$green;
                        $orange   =  'fa fa'.$orange;
                        $white    =  'fa fa'.$white;
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
                foreach($options AS $key => $val){
                        $html   .= '<a class="pointer_hand" '.$val['tooltip'].'><i id="'.$id.'_'.$key.'"  '.$style.' class="'.$$key.' '.${'css_'.$key}.'"   onclick="processor(\'accomplish\', \'enabling_objective\', '.$id.',{\'status_id\':'.$val['status'].', \'reload\':\'false\', \'callback\':\'setElementById\'});"></i></a>';   
                    }
            } else { //teacher
                if (checkCapabilities('course:selfAssessment', $USER->role_id, false) OR $teacher == $student) { // show in view
                    foreach($options AS $key => $val){
                        $html   .= '<a class="pointer_hand"><i id="'.$id.'_'.$key.'"  '.$style.' class="'.$$key.' '.${'css_'.$key}.'"   onclick="processor(\'accomplish\', \'enabling_objective\', '.$id.',{\'status_id\':'.$val['status'].', \'reload\':\'false\', \'callback\':\'setElementById\'});"></i></a>';   
                    }
                    $html  .= '<a class="pointer_hand"><i id="'.$id.'_comment" '.$style.' class="fa fa-comments text-primary margin-r-5 pointer_hand" onclick="formloader(\'comment\',\'accomplished\','.$id.');"></i></a>';
                }
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
            default:        if ($file->orgin != 'internal'){
                                $reponame = 'repository_plugin_'.$file->orgin;
                                $repo     = new $reponame;
                                $content  = $repo->render($file);  
                            }
                            else if (checkCapabilities('plugin:useEmbeddableGoogleDocumentViewer', $USER->role_id, false) AND !is_array(getimagesize($CFG->curriculumdata_root.$file->full_path))){
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
                case NULL: return ''; //e.g. edusharing links do not render in Thumblist
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
                                                    $html .= '<a href="#" class="btn btn-default btn-xs pull-right" data-toggle="tooltip" title="löschen" onclick="processor(\'delete\', \'file\', '.$file->id.', { \'reload\': \'false\', \'callback\': \'replaceElementByID\', \'element_Id\': \'thumb_'.$file->id.'\'});"><i class="fa fa-trash"></i></a>';
                                                }
                                $html .= '</span></div></'.$tag.'>'; 


                    break;
                case 'xs':      $html .=   '<div id="thumb_'.$file->id.'" class="btn-group" style="padding-right:10px;">
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
                                              <li><a href="#" onclick="processor(\'delete\', \'file\', '.$file->id.', { \'reload\': \'false\', \'callback\': \'replaceElementByID\', \'element_Id\': \'thumb_'.$file->id.'\'});"><i class="fa fa-trash"></i>löschen</a></li>';
                                }
                                $html .=   '</ul></div>';
                    break;
                case 'thumb':   if ($icon == true){
                                    $html .=   '<div id="thumb_'.$file->id.'" style="position:relative; height: '.$height.';width:'.$width.'; float:left;"><i class="'.resolveFileType($file->type).' info-box-icon"></i>';
                                        if (isset($_SESSION['LICENSE'][$file->license]->file_id)){
                                            $html .= '<img style="position:absolute;bottom:0px; right:0px;" src="'.$CFG->access_id_url.$_SESSION['LICENSE'][$file->license]->file_id.'" height="25"/>';
                                        }
                                    $html .=   '</div>';
                                    
                                    
                                } else {
                                    $html .=   '<div id="thumb_'.$file->id.'" style="position:relative; height: '.$height.';width:'.$width.'; float:left; background: url(\''.$url.'\') center; background-size: cover; background-repeat: no-repeat;">';
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
        $html =   '<div id="helpcard'.$help->id.'" class="col-lg-3 col-md-6 col-xs-12">';
                    $html .= '<a href="#" onclick="formloader(\'preview\',\'help\','.$help->id.')">
                              <div class="info-box">';
                    $html .= RENDER::thumb(array('file_list' => $help->file_id, 'format'=> 'thumb', 'width' => '90px', 'height' => '90px'));  
                    if (checkCapabilities('help:update', $USER->role_id, false)){
                        $html .='<a><span class="pull-right" onclick="formloader(\'help\',\'edit\','.$help->id.');"><i class="fa fa-edit margin"></i></span></a>';
                    }
                    if (checkCapabilities('help:add', $USER->role_id, false)){
                        $html .='<a><span class="pull-right" onclick="processor(\'delete\', \'help\', '.$help->id.', { \'reload\': \'false\', \'callback\': \'replaceElementByID\', \'element_Id\': \'helpcard'.$help->id.'\'});"><i class="fa fa-trash top-buffer"></i></span></a>';
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
    /* not used yet
    public static function wallet_thumb($params){
        $width_class     = 'col-lg-3 col-md-4 col-sm-6 col-xs-12';
                
        foreach($params as $key => $val) {
            $$key = $val;
        }
        global $USER;
        $html =   '<div id="wallet_'.$wallet->id.'" class="'.$width_class.'">';
                    
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
                        $html .='<a class="pull-right"><span class="fa fa-trash padding-top-5 margin-r-5" onclick="processor(\'delete\', \'wallet\', '.$wallet->id.', { \'reload\': \'false\', \'callback\': \'replaceElementByID\', \'element_Id\': \'wallet_'.$wallet->id.'\'});"></span></a>';
                    }
                    
                    $html .= '  <span class="pull-left"><small>'.$wallet->timerange.'</small></span><div class="caption text-center"><br>
                                  <h5 class="events-heading text-ellipse"><a href="index.php?action=walletView&wallet='.$wallet->id.'">'.$wallet->title.'</a></h5>
                                  <p style="overflow: scroll; width: 100%; height: 100px;">'.$wallet->description.'</p>
                                </div>
                              </div><!-- /.events-->
                    </div>';
        return $html;                                    
    }*/
    
    public static function wallet_content($wallet_content, $edit){
       
       $html  =   '<div id="wallet_content_'.$wallet_content->id.'" class="sortable" style="width:100%;height:100%;"><div class="'.$wallet_content->width_class;
       if ($edit == true){
           $html  .=   ' sortable wallet-content"><span style="position: absolute; right:15px;" ></button><button type="button" onclick="formloader(\'wallet_content\',\'edit\','.$wallet_content->id.');"><i class="fa fa-edit"></i></button>'
                      . '<button type="button"  onclick="processor(\'delete\', \'wallet_content\', '.$wallet_content->id.', { \'reload\': \'false\', \'callback\': \'replaceElementByID\', \'element_Id\': \'wallet_content_'.$wallet_content->id.'\'});"><i class="fa fa-trash"></i></button>'
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
    /**
     * 
     * @global type $CFG
     * @global type $USER
     * @param array $params array(
     *                            'permission' => 1         // Add / Delete comments
     * 
     *                            )
     * @return string
     */
    public static function comments($params){
        global $CFG, $USER;
        foreach($params as $key => $val) {
             $$key = $val;
        }
        if (!isset($permission)){ $permission = 1; }
        /* Load comments based on id and context */
        if (isset($id) AND isset($context)){
            $cm                 = new Comment();
            $cm->reference_id   = $id;
            $cm->context        = $context; 
            $comments           = $cm->get('reference');
        }
        
        $html = '<ul class="media-list ">';
        if (!isset($sub_comment)){
            $html .= '<li class="media" id="comment_placeholder_'.$id.'"></li>';
        }
        
        foreach ($comments as $c) {
            $html .= RENDER::comment($c, $permission);
        }
        $html .=  '</ul>';
        
        /* add Comments */
        if (checkCapabilities('comment:add', $USER->role_id, false, true) ){  
            if (!isset($sub_comment)){
                $html.= 'Neuen Kommentar hinzufügen
                <textarea id="comment" name="comment" class="no_editor" style="width:100%;"></textarea>
                <button type="submit" class="btn btn-primary pull-right" onclick="processor(\'comment\',\'new\','.$id.', {\'context_id\':'.$_SESSION['CONTEXT'][$context]->context_id.', \'text\':document.getElementById(\'comment\').value, \'reload\':\'false\', \'callback\':\'replaceElementByID\', \'elementId\':\'comment_placeholder_'.$id.'\'});"><i class="fa fa-commenting-o margin-r-10"></i> Kommentar abschicken</button>';
            }
        } else {
            $html .= "Sie haben nicht die Berechtigung Kommentare zu schreiben.";
        }

        return $html;
    }
    
    public static function comment($cm, $permission, $show_add = true){
        global $CFG, $USER;
        $u      = new User();
        $u->load('id', $cm->creator_id, false);
        $size   = '48';
        $html   = '<li id="comment_'.$cm->id.'" class="media" >
                   <a class="pull-left" href="#" >
                     <div style="height:'.$size.'px;width:'.$size.'px;background: url('.$CFG->access_id_url.$u->avatar_id.') center right;background-size: cover; background-repeat: no-repeat;"></div>
                    </a>
                  <div class="media-body" >
                  <a style="cursor:pointer;" class="text-red margin-r-10 pull-right" onclick=\'processor("set","likes",'.$cm->id.', {"dependency":"dislikes", "reload":"false", "callback":"innerHTML"});\'><i class="fa fa-thumbs-o-down margin-r-5"></i><span id="dislikes_'.$cm->id.'"> '.$cm->dislikes.'</span></a>
                    <a style="cursor:pointer;" class="text-green margin-r-10 pull-right" onclick=\'processor("set","likes",'.$cm->id.', {"dependency":"likes", "reload":"false",  "callback":"innerHTML"});\'><i class="fa fa-thumbs-o-up margin-r-5"></i><span id="likes_'.$cm->id.'"> '.$cm->likes.' </span></a>

                      <h4 class="media-heading">'.$u->username.' <small class="text-black margin-r-10"> '.$cm->creation_time.'</small> 
                      </h4>
                          <p class="media-heading">'.$cm->text.'<br>';
                          if ($cm->creator_id == $USER->id){
                              if (($permission > 0) AND checkCapabilities('comment:delete', $USER->role_id, false, true)){
                                $html  .= '<a class="text-red pull-right" onclick="processor(\'delete\',\'comment\','.$cm->id.', {\'reload\':\'false\', \'callback\':\'replaceElementByID\', \'element_Id\':\'comment_'.$cm->id.'\'});"><i class="fa fa-trash "></i></a>';
                              }
                          } else if (!checkCapabilities('comment:delete', $USER->role_id, false, true)) {
                            $html .= '<a class="text-red" onclick=""><i class="fa fa-exclamation-triangle "></i> Kommentar melden</a>';
                          }
                          if (($show_add == true) AND checkCapabilities('comment:add', $USER->role_id, false, true)){
                            $html .= '<a id="answer_'.$cm->id.'" onclick="toggle([\'comment_text_'.$cm->id.'\', \'cmbtn_'.$cm->id.'\'], [\'answer_'.$cm->id.'\'])"><i class="fa fa-comments"></i></a></p>';
                            $html .='<textarea id="comment_text_'.$cm->id.'" name="comment"  class="hidden no_editor" style="width:100%;"></textarea>
                                    <button id="cmbtn_'.$cm->id.'" type="submit" class="btn btn-primary pull-right hidden" onclick="processor(\'comment\',\'new\','.$cm->reference_id.', {\'context_id\':'.$cm->context_id.', \'text\':document.getElementById(\'comment_text_'.$cm->id.'\').value, \'parent_id\':'.$cm->id.', \'reload\':\'false\', \'callback\':\'replaceElementByID\', \'elementId\':\'comment_placeholder_'.$cm->id.'\'});"><i class="fa fa-commenting-o margin-r-10"></i>Kommentar abschicken</button>';
                         }
                         $html .='<hr class="dashed">';
        /* sub comments */
        if (!empty($cm->comment)){
            $html .= RENDER::comments(array('comments' => $cm->comment, 'sub_comment' => true));
        }
        $html .= '<li class="media" id="comment_placeholder_'.$cm->id.'"></li></li>';  
        return $html;
    }
    
    /* add all possible options (ter and ena) to this objective function*/
    public static function objective($params){
       global $CFG, $USER, $PAGE;
       $format      = 'default';
       $type        = 'terminal_objective'; 
       $show_parents= false;
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
                if (checkCapabilities('file:loadMaterial', $USER->role_id, false) AND ($objective->files['local'] != 0 OR $objective->files['repository'] != 0 OR isset($objective->files['webservice']) )){
                    $html  .='<span class="fa fa-briefcase box-sm-icon text-primary margin-r-5 pull-left" style="cursor:pointer; padding-top:3px;" onclick="formloader(\'material\',\''.$type.'\','.$objective->id.')"></span>';
                } else {
                    $html  .='<span class="fa fa-briefcase box-sm-icon deactivate text-gray margin-r-5 pull-left" style="cursor:not-allowed;padding-top:3px;" data-toggle="tooltip" title="Keine Materialien verfügbar"></span>';
                }
                $html  .='</span></div>';
                 /*************** ./Footer ***************/
                $html  .= '</div>';
                break;

            default:
                $html  = '';
                /* Show parent objective? */
                if ($show_parents AND $type == 'enabling_objective'){ 
                    $o      = new TerminalObjective();
                    $o->id  = $objective->terminal_objective_id ;
                    $o->load();
                    $html  .= RENDER::objective(["type" =>"terminal_objective", "objective" => $o]);
                }
                $html  .=   '<div ';
                if ($type == 'enabling_objective'){ //id is important to get scroll-to function while creating
                    $html  .= 'id="ena_'.$objective->id.'"';
                } else {
                    $html  .= 'id="ter_'.$objective->id.'"';
                }
                $html  .=   'class="box box-objective ';
                $style = 'padding-top: 0 !important; background: '.$objective->color.'; border: 1px solid '.$border_color;
                if (($objective->files['references'] == false AND $reference_view == true) OR isset($highlight) AND !in_array($type.'_'.$objective->id, $highlight)){
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
                             $html  .= '<span class="fa fa-arrow-'.$icon_up.' '.$position.' box-sm-icon '.$icon_class.'" onclick=\'processor("orderObjective", "'.$type.'", "'.$objective->id.'", {"order":"up","reload":"true"});\'></span>';
                         }
                         $html  .= '<span class="fa fa-minus pull-right box-sm-icon '.$icon_class.' margin-r-5" onclick=\'processor("delete","'.$type.'","'.$objective->id.'", {"reload":"false", "callback":"replaceElementByID", "element_Id":"'.substr($type, 0, 3).'_'.$objective->id.'"});\'></span>
                                    <span class="fa fa-edit pull-right box-sm-icon '.$icon_class.'" onclick=\'formloader("'.$type.'", "edit", '.$objective->id.');\'></span>';
                         if ($orderdown){
                             $html  .= '<span class="fa fa-arrow-'.$icon_down.' pull-left box-sm-icon '.$icon_class.'" onclick=\'processor("orderObjective", "'.$type.'", "'.$objective->id.'", {"order":"down","reload":"true"});\'></span>';
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
                                 if (checkCapabilities('file:loadMaterial', $USER->role_id, false) AND ($objective->files['local'] != 0 OR $objective->files['repository'] != 0 OR isset($objective->files['webservice']) OR $objective->files['references'] != false )){
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
                                         /*if (checkCapabilities('reference:show', $USER->role_id, false)){
                                             $html  .= '<span class="fa fa-link text-primary box-sm-icon pull-left" data-toggle="tooltip" title="Lehr- /Rahmenplanbezüge" onclick=\'formloader("reference_view", "'.$type.'", "'.$objective->id.'");\'></span>';
                                         }*/
                                         if (checkCapabilities('reference:add', $USER->role_id, false)){
                                             $html  .= '<span class="box-sm-icon pull-right text-primary" data-toggle="tooltip" title="Lehr- /Rahmenplanbezug hinzufügen" onclick=\'formloader("reference", "new", "'.$objective->id.'", {"context":"enabling_objective"});\'><i class="fa fa-link text-primary box-sm-icon"><i class="fa fa-plus fa-xs"></i></i></span>';
                                         }
                                         if (checkCapabilities('course:selfAssessment', $USER->role_id, false) OR checkCapabilities('course:setAccomplishedStatus', $USER->role_id, false) OR
                                             checkCapabilities('objectives:setStatus', $USER->role_id, false) ) {
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
                                         /*if (checkCapabilities('reference:show', $USER->role_id, false)){
                                             $html  .= '<span class="fa fa-link '.$icon_class.' box-sm-icon pull-left" data-toggle="tooltip" title="Lehr- /Rahmenplanbezüge" onclick=\'formloader("reference_view", "'.$type.'", "'.$objective->id.'");\'></span>';
                                         }*/
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
        $content = '<div id="mail_'.$mail->id.'" class="box">
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
                    <button class="btn btn-default btn-sm" data-toggle="tooltip" title="löschen" onclick="processor(\'delete\',\'message\','.$mail->id.', {\'ref_id\': \''.$box.'\', \'reload\':\'false\', \'callback\':\'replaceElementByID\', \'element_Id\':\'mail_'.$mail->id.'\'});"><i class="fa fa-trash-o"></i> Löschen</button>
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
        $file    = new File();
        $files   = $file->getFiles($dependency, $id, 'filelist_'.$dependency);
        
        setPaginator('filelist_'.$dependency, $files, 'fi_val', $url); //set Paginator for filelist
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
            $content .= '<tr id="thumb_'.$f->id.'">';
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
                          <li><a href="#" onclick="processor(\'delete\', \'file\', '.$f->id.', { \'reload\': \'false\', \'callback\': \'replaceElementByID\', \'element_Id\': \'thumb_'.$f->id.'\'});"><i class="fa fa-trash"></i>löschen</a></li>';
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
                          <span class="time" onclick="processor(\'delete\', \'courseBook\', '.$cb->id.');"><i class="fa fa-trash-o"></i></span>
                          <span class="time" onclick="processor(\'print\',\'courseBook\','.$cb->id.');"><i class="fa fa-print"></i></span>
                          <span class="time" onclick="formloader(\'courseBook\',\'edit\','.$cb->id.');"><i class="fa fa-edit"></i></span>
                          <span class="time"><i class="fa fa-clock-o"></i> '.$cb->creation_time.'</span>
                          
                          <h3 class="timeline-header"><a href="#">';
                          if (isset($cb->curriculum)){
                          $r      .=     $cb->curriculum;
                          }  
            $r      .= '                </a> '.$cb->creator.'</h3>
                          <div class="timeline-body">
                              <h4>'.$cb->topic .'</h4> 
                             '.$cb->description.'
                          </div>
                          
                          <!--div class="timeline-footer"-->';
            $r      .=    '<div style="display:inline-table;padding-left:10px;">'.Render::objective_list(array("dependency" => "courseBook", "id" => $cb->id)).'</div>';
            $r      .=    Render::todoList($cb->task, 'courseBook', $cb->id);
            if (checkCapabilities('absent:update', $USER->role_id, false)){
                $r  .=    Render::absentListe($cb->absent_list, 'courseBook', $cb->id);
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
        $r       .= ' <li id="task_item_'.$tsk->id.'" class="tasklink" ';
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
                        if (checkCapabilities('task:update', $USER->role_id, false) AND $onclick == false){
                            $r   .= '<div class="tools">
                                        <i class="fa fa-edit" onclick="formloader(\'task\',\'edit\', \''.$tsk->id.'\');"></i>
                                        <i class="fa fa-trash-o" onclick="processor(\'delete\', \'task\', '.$tsk->id.', { \'reload\': \'false\', \'callback\': \'replaceElementByID\', \'element_Id\': \'task_item_'.$tsk->id.'\'});"></i>
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
        $r     .= '<li id="absent_entry_'.$ub->id.'" class="item" >
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
                        <i class="fa fa-trash-o" onclick="processor(\'delete\', \'absent\', '.$ub->id.', { \'reload\': \'false\', \'callback\': \'replaceElementByID\', \'element_Id\': \'absent_entry_'.$ub->id.'\'});"></i></span>
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
        $upcoming_events = $events->get('upcoming', $USER->id, '');
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
                                        /* TODO implement count function for shorter syntax */
                                        $comments = new Comment();
                                        $comments->context = 'content';
                                        $comments->reference_id = $value->id;
                                        $c = $comments->get('reference');
                                        $c_max = count($c);
                                        /**/
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
                                        $html  .=       RENDER::comments(["id" => $value->id, "context" => "content"]); 
                                        $html  .=     '</div>
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
                                if (isset($val->title)){ //prevent error_logs if empty entries are given
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
                                  '.$header_box_tools_left;
                                  if (isset($header_onclick)){
                                    $html  .='<h3 class="box-title" '.$header_onclick.'> '.$header_content.'</h3>'; 
                                  } else {
                                    $html  .='<h3 class="box-title"> '.$header_content.'</h3>';
                                  }
                                   $html  .='<div class="box-tools pull-right">
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
            $$key = $val;
        }
        $html = '';
        if ($nb_visible == 1 OR(checkCapabilities('navigator:add', $USER->role_id, false) == true )){  //is visible?
            switch ($nb_context_id) {
                /*curriculum*/
                case 2:     $cur                = new Curriculum();
                            $cur->id            = $nb_reference_id; 
                            $cur->load(false);
                            $enroled_groups     = $cur->getGroupsByUserAndCurriculum($USER->id);
                            $file_id            = $cur->icon_id;
                            $widget_onclick     = "location.href='index.php?action=view&curriculum_id={$nb_reference_id}&group={$enroled_groups[0]->group_id}';";
                            if (checkCapabilities('navigator:update', $USER->role_id, false)){
                            $opt[]              = '<a type="button" style="color:#FFF;" class="fa fa-edit" onclick="formloader(\'navigator_item\', \'edit\','.$nb_id.', {\'nb_navigator_view_id\':\''.$nb_navigator_view_id.'\'})";></a>';
                            $html               = RENDER::paginator_widget(array('widget_title' => $nb_title, 'widget_desc' => $nb_description, 'file_id' => $file_id, 'widget_onclick' => $widget_onclick, 'opt' => $opt, 'global_onclick' => true));
                            } else {
                               $html               = RENDER::paginator_widget(array('widget_title' => $nb_title, 'widget_desc' => $nb_description, 'file_id' => $file_id, 'widget_onclick' => $widget_onclick, 'global_onclick' => true));
                            }
                            
                    break;
                case 15:    $content            = new Content();
                            $content->load('id', $nb_reference_id);
                            if (checkCapabilities('navigator:update', $USER->role_id, false)){
                                $html          .= RENDER::box(array('header_box_tools_right' => '<button class="btn btn-box-tool" onclick="formloader(\'content\',\'edit\', \''.$nb_reference_id.'\')"><i class="fa fa-edit"></i></button><button class="btn btn-box-tool" onclick="processor(\'print\',\'content\', \''.$nb_reference_id.'\')"><i class="fa fa-print"></i></button><button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-expand"></i></button>', 'header_onclick' => 'data-widget="collapse"', 'header_content' => $content->title, 'content_id' => $nb_reference_id, 'body_content' => $content->content));
                            } else {
                                $html          .= RENDER::box(array('header_box_tools_right' => '<button class="btn btn-box-tool" onclick="processor(\'print\',\'content\', \''.$nb_reference_id.'\')"><i class="fa fa-print"></i></button><button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-expand"></i></button>', 'header_onclick' => 'data-widget="collapse"', 'header_onclick' => 'data-widget="collapse"', 'header_content' => $content->title, 'content_id' => $nb_reference_id, 'body_content' => $content->content));
                            }
                            
                    break; 
                case 16:    $c                  = new Curriculum();
                            $curricula          = $c->getCurricula('group', $nb_reference_id);
                            foreach ($curricula as $cur) {
                                $html          .= RENDER::paginator_widget(["widget_title" =>$cur->curriculum, 'widget_desc' => $nb_description, "ref_id" => $cur->id, "group_id" => $nb_reference_id, 'global_onclick' => true]);
                            }

                    break;

                case 29:    $f                  = new File();
                            $f->load($nb_reference_id);
                            $widget_onclick     = "location.href='../share/accessfile.php?id={$nb_reference_id}';";                        
                            $html               = RENDER::paginator_widget(array('widget_title' => $nb_title, 'widget_desc' => $nb_description, 'file_id' => $nb_file_id, 'widget_onclick' => $widget_onclick , 'global_onclick' => true));
                            //$html               = '<div class="'.$nb_width_class.'">'.RENDER::file($f).'</div>';
                    break;
                case 31:    if ($nb_target_context_id == $_SESSION['CONTEXT']['file']->context_id){
                                $f                  = new File();
                                $f->load($nb_target_id);
                                $widget_onclick     = "location.href='{$f->path}'";
                            } else {
                                $widget_onclick     = "location.href='index.php?action=navigator&nv_id={$nb_target_id}';";
                            }

                            $html               = RENDER::paginator_widget(array('widget_title' => $nb_title, 'widget_desc' => $nb_description, 'file_id' => $nb_file_id, 'widget_onclick' => $widget_onclick, 'global_onclick' => true));

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
        return $html = RENDER::box(array('header_box_tools_left' => $left_menu,'header_box_tools_right' => '<button class="btn btn-box-tool" onclick="processor(\'print\',\'book\', \''.$b->id.'\')"><i class="fa fa-print"></i></button><button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-expand"></i></button>', 'header_onclick' => 'data-widget="collapse"', 'header_content' => $b->title, 'content_id' => $b->id, 'body_content' => $book_content));
    }
    
    
    
    public static function paginator_widget($params){
        global $CFG;
        /*default params*/
        $class_width    = 'col-md-6';
        $widget_type    = 'curriculum';
        $bg_color       = 'bg-primary';
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
            $widget_onclick     = "location.href='index.php?action=view&curriculum_id={$ref_id}&group={$group_id}';";
        } 
        
        $html   =  '<div class="box box-objective bg-white '.$bg_color.'" style="height: 300px !important; padding: 0; background: url(\''.$icon_url.'\') center center;  background-size: cover;"  ';
        
        $html   .= '>'; 
        if (count($opt) > 0){
            $html   .= '<span class="col-xs-12 '.$bg_color.'" style="background-color: '.$bg_color.'; position:absolute; display:block; left:0;right:0;top:0px;" >';
                       foreach ($opt as $k =>$o) {
                               $html .= '<span style="margin-right:12px;padding:5px;text-shadow: 1px 1px #FF0000;" class="fa">'.$o.'</span>';    
                       }
            $html .= '</span>';
        }        
        $html   .= '<span class="no-padding pointer_hand" style="position:absolute; bottom:0px; height: 275px;width:100%;"';
        if (isset($global_onclick)){
            $html   .= ' onclick="'.$widget_onclick.'"';
        }
        $html   .= '></span><span class="bg-white no-padding pointer_hand" style="background-color: #fff; position:absolute; bottom:0px; height: 120px;width:100%;text-align: center;" >'; 
                $html   .= '<div class="caption text-center">';
                if (isset($widget_timerange)){
                    $html   .= '<small>'.$widget_timerange.'</small>';
                }
                        $html   .= '  <h5 class="events-heading text-center"><a onclick="'.$widget_onclick.'">'.$widget_title.'</a></h5>
                                  <p style="height: 60px;" class="margin text-muted">'.$widget_desc.'</p>
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
                                        return $v->{$r[1]}; 
                                    }, $href);
                            $html   .= '<li id="w_row_'.$value->id.'"><a href="'.$href_regex.'">'.$l;
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
        $curriculum_id = '';
        //$count_ref = count($references);
        $count_ref = count(array_unique(array_column($references, 'curriculum_id'))); // count amount of different curricula
        foreach ($references as $ref) {
            if ($ref->curriculum_id != $curriculum_id){
                if ($curriculum_id != ''){
                    $content .= '</span>';
                }
                $curriculum_id = $ref->curriculum_id;
                if ($count_ref > 1){
                    $content .= '<span class="col-xs-12 bg-light-aqua" data-toggle="collapse" data-target="#curriculum_'.$curriculum_id.'"><h4 class="text-black" >'.$ref->curriculum_object->curriculum.' <small>'.$ref->schooltype.'</small><button class="btn btn-box-tool pull-right" style="padding-top:0;" type="button" data-toggle="collapse" data-target="#curriculum_'.$curriculum_id.'" aria-expanded="true" data-toggle="tooltip" title="Fach einklappen bzw. ausklappen"><i class="fa fa-expand"></i></button></h4></span><hr style="clear:both;">';
                    $content .= '<span id ="curriculum_'.$curriculum_id.'" class="collapse out">';
                } else {
                    $content .= '<span class="col-xs-12 bg-light-aqua" data-toggle="collapse" data-target="#curriculum_'.$curriculum_id.'"><h4 class="text-black" >'.$ref->curriculum_object->curriculum.' <small>'.$ref->schooltype.'</small><button class="btn btn-box-tool pull-right" style="padding-top:0;" type="button" data-toggle="collapse" data-target="#curriculum_'.$curriculum_id.'" aria-expanded="true" data-toggle="tooltip" title="Fach einklappen bzw. ausklappen"><i class="fa fa-compress"></i></button></h4></span><hr style="clear:both;">';
                    $content .= '<span id ="curriculum_'.$curriculum_id.'" class="collapse in">';
                }
            }
            $content .= RENDER::render_reference_entry($ref, $_SESSION['CONTEXT']['terminal_objective']->context_id);
        }
        $content .= '</span>'; //close last subject span
    } 
    
    if (count($references) == 0) {
        $content .= 'Keine überfachliche Bezüge vorhanden.';
    }

    if ($get['ajax'] == 'true'){  
        echo $content;
    } else {
        return $content;
    }
}



public static function render_reference_entry($ref, $context_id){
    global $USER;
    $c  = '<div id="reference_entry_'.$ref->id.'" class="row">
            <div class="col-xs-12 col-sm-3 pull-left"><dt>Thema/Kompetenzbereich</dt>'.Render::objective(array('format' => 'reference', 'objective' => $ref->terminal_object, 'color')).'</div>';
            if ($ref->context_id == $_SESSION['CONTEXT']['enabling_objective']->context_id) {
              $c .= '<div class="col-xs-12 col-sm-3"><dt>Kompetenz</dt>'.Render::objective(array('format' => 'reference', 'type' => 'enabling_objective', 'objective' => $ref->enabling_object, 'border_color' => $ref->terminal_object->color)).'</div>';
            }
            $c .= '
           <div class="col-xs-12 col-sm-6 pull-right">';
            if (checkCapabilities('reference:add',    $USER->role_id, false, true)){
                $c .= '<a onclick="processor(\'delete\', \'reference\', '.$ref->id.', { \'reload\': \'false\', \'callback\': \'replaceElementByID\', \'element_Id\': \'reference_entry_'.$ref->id.'\'});" class="btn btn-default btn-xs pull-right" data-toggle="tooltip" title="" data-original-title="Referenz löschen" style="margin-right:5px;"><i class="fa fa-trash"></i></a>';
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
    if ($quotes != false){
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
                    $cur_id             = $ref->reference_object->id;
                    $qus                = new Quote();
                    $qus->reference_id  = $ref->reference_id;
                    $quote_subscriptions= $qus->getQuoteSubscriptions();
                    $curriculum = $ref->reference_object->curriculum;
                    if ($quote_subscriptions){
                        $i = 0;
                        foreach ($quote_subscriptions as $qus_entry) {
                            if ($i == 0){
                                $curriculum = $qus_entry->curriculum;
                            } else {
                                $curriculum .= ', '.$qus_entry->curriculum;
                            }
                            $i++;
                        }
                    } 
                    $content .= '<span class="col-xs-12 bg-light-aqua" data-toggle="collapse" data-target="#cur_'.$cur_id.'"><h4 class="text-black">'.$curriculum.'<button class="btn btn-box-tool pull-right" style="padding-top:0;" type="button" data-toggle="collapse" data-target="#cur_'.$cur_id.'" aria-expanded="true" data-toggle="tooltip" title="Fach einklappen bzw. ausklappen"><i class="fa fa-expand"></i></button></h4></span><hr style="clear:both;">';
                    $content .= '<span id ="cur_'.$cur_id.'" class="collapse out">';
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
}

public static function quote_reference($quotes){
    global $USER;
    $content     = '';  
    if (isset($quotes) AND $quotes != false){
        $content_id = '';
        $quote_max  = count($quotes); //amount of quotes
        for ($i = 0; $i < $quote_max; $i++){
            if ($quotes[$i]->quote_link != $content_id){ //if new content render Title
                if ($content_id != ''){
                    $content .= '</span>';  
                }
                $content .= '<span class="col-xs-12 bg-light-aqua" data-toggle="collapse" data-target="#ct_'.$content_id.'"><h4 class="text-black">'.$quotes[$i]->reference_title.'<button class="btn btn-box-tool pull-right" style="padding-top:0;" type="button" data-toggle="collapse" data-target="#ct_'.$content_id.'" aria-expanded="true" data-toggle="tooltip" title="Fach einklappen bzw. ausklappen"><i class="fa fa-expand"></i></button></h4></span><hr style="clear:both;">';
                $content .= '<span id ="ct_'.$content_id.'" class="collapse out">';
                }
                $content_id = $quotes[$i]->quote_link;
                if (checkCapabilities('reference:update', $USER->role_id, false)){ //just for debugging
                    $quote_id = '(ID: '.$quotes[$i]->id.') ';
                } else {
                   $quote_id = ''; 
                }
                $content .= '<div class="col-xs-12 col-sm-6"><h4>Fundstelle im Text:</h4><br><blockquote>'.$quote_id.$quotes[$i]->quote.'<small>, <cite="'.$quotes[$i]->reference_title.'" class="pointer_hand"><a onclick="formloader(\'content\', \'show\','.$quotes[$i]->quote_link.');">'.$quotes[$i]->reference_title.'</a></cite></small></blockquote></div>'; 
                $c        = new Curriculum();    
                $c->id    = $quotes[$i]->terminal_object->curriculum_id;
                $c->load();
                $content .= '<div class="col-xs-12 col-sm-6 pull-right"><h4>'.$c->curriculum.'</h4></div>';
                $content .= '<div class="col-xs-12 col-sm-6 pull-right">';
                $content .= Render::objective(array('format' => 'reference', 'objective' => $quotes[$i]->terminal_object, 'color'));
                if ($quotes[$i]->context_id == $_SESSION['CONTEXT']['enabling_objective']->context_id) {
                  $content .= Render::objective(array('format' => 'reference', 'type' => 'enabling_objective', 'objective' => $quotes[$i]->enabling_object, 'border_color' => $quotes[$i]->terminal_object->color));
                }
                $quote_id = $quotes[$i]->id;   
                while (isset($quotes[$i+1])) { //check and render next objective until new quote is given
                    if ($quotes[$i+1]->id == $quote_id){
                        if ($quotes[$i+1]->id == $quote_id AND $quotes[$i+1]->terminal_objective_id == $quotes[$i]->terminal_objective_id){
                            if ($quotes[$i+1]->context_id == $_SESSION['CONTEXT']['enabling_objective']->context_id) {
                              $content .= Render::objective(array('format' => 'reference', 'type' => 'enabling_objective', 'objective' => $quotes[$i+1]->enabling_object, 'border_color' => $quotes[$i+1]->terminal_object->color));
                            }
                        } else {
                            $content .= Render::objective(array('format' => 'reference', 'objective' => $quotes[$i+1]->terminal_object, 'color'));
                            if ($quotes[$i+1]->context_id == $_SESSION['CONTEXT']['enabling_objective']->context_id) {
                                $content .= Render::objective(array('format' => 'reference', 'type' => 'enabling_objective', 'objective' => $quotes[$i+1]->enabling_object, 'border_color' => $quotes[$i]->terminal_object->color));
                            }
                        }
                            $i++;    
                    } else {
                        break;
                    }
                }
                $content .='</div><hr style="clear:both;">';
        }
        $content .= '</span>'; //close last content span
        
        return '<div class="col-xs-12 top-buffer" >'.RENDER::box(array('header_box_tools_right' => '<button class="btn btn-box-tool"><button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-expand"></i></button>', 'header_onclick' => 'data-widget="collapse"', 'header_content' => 'Textbezüge im Lehrplan', 'content_id' => null, 'body_content' => $content)).'</div>';
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
    
    
    public static function search($dependency = 'view', $id, $get ){
        $content = '';
        switch ($dependency) {
            case 'view':$s          = new Search();
                        $s->id      = $id; //cur_id
                        $s->search  = $get['search'];
                        $content    = Render::render_search_results('content', array('search_results' => $s->content(), 'get' => $get));  
                break;

            default:
                break;
        }
        
        
        if ($get['ajax'] == 'true'){  
            echo $content;
        } else {
            return $content;
        }
    }
    
    public static function render_search_results($dependency = 'content', $params ){
        $content     = ''; 
        //RENDER::sortByProp($params['search_results'], 'title', 'asc');
        /*Maybe realize further filter options with ofilter()*/
        $c_id = '';
        $empty = true;
        if (count($params['search_results']) < 1){
            return '';
        }
        foreach ($params['search_results'] as $s_result) {
            if ($s_result['id'] != $c_id){
                if ($c_id != ''){
                    $content .= '</span>';  
                }
                $c_id = $s_result['id'];
                
                if (!empty($s_result['matches'])){
                    $content .= '<span class="col-xs-12 bg-light-aqua" data-toggle="collapse" data-target="#s_result_'.$c_id.'"><h4 class="text-black">'.$s_result['title'].'<button class="btn btn-box-tool pull-right" style="padding-top:0;" type="button" data-toggle="collapse" data-target="#s_result_'.$c_id.'" aria-expanded="true" data-toggle="tooltip" title="Text einklappen bzw. ausklappen"><i class="fa fa-expand"></i></button></h4></span><hr style="clear:both;">';
                    $content .= '<span id ="s_result_'.$c_id.'" class="collapse out">';
                }
            }
            foreach ($s_result['matches'] AS $m_result){
                $content .= '<blockquote>'.str_ireplace ( $params['get']['search'] , '<span class="bg-yellow color-palette">'.$params['get']['search'].'</span>' , $m_result ).'<small>'.$s_result['title'].', <cite="'.$s_result['title'].'" class="pointer_hand"><a onclick="formloader(\'content\', \'show\','.$s_result['id'].');">'.$s_result['title'].'</a></cite></small></blockquote>'; 
                $empty = false;
            }   
        }
        $content .= '</span>'; //close last subject span
         
        if ($empty == true){
            return '';
        } else {
            return '<div class="col-xs-12 top-buffer" >'.RENDER::box(array('header_box_tools_right' => '<button class="btn btn-box-tool"><button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-expand"></i></button>', 'header_onclick' => 'data-widget="collapse"', 'header_content' => 'Suchergebnisse', 'content_id' => null, 'body_content' => $content)).'</div>';
        }
    }
    
    public static function print_reference($params){
        $content    = '';
        $pagebreak  = false; 
        foreach($params as $key => $val) {
            $$key   = $val;
        }
        if ($pagebreak){
            $content  .= '<pagebreak>';
            $pagebreak = false;
        }
        
        $c                  = new Curriculum();
        $c->id              = $id;
        $c->load(false);
        $content .= '<h3>'. $c->curriculum.'</h3>'; 
        
        $content .= '<h4>Bezüge zu den Kompetenzen</h4>'; 
        $ter_obj  = new TerminalObjective();         //load terminal objectives
        $ter      = $ter_obj->getObjectives('certificate', $id);
        $temp_ter_id = 0;
        foreach ($ter as $ter_value) {
            $ena_obj                = new EnablingObjective();         //load enabling objectives
            $ena_obj->curriculum_id = $id;
            $ena                    = $ena_obj->getObjectives('terminal_objective', $ter_value->id);
            $reference   = new Reference(); // todo: create function for both terminal and enabling objective (removes double code)
           
            $references  = $reference->get('reference_id', $_SESSION['CONTEXT']['terminal_objective']->context_id, $ter_value->id);
            if (count($references) > 0){
                $content    .= SELF::print_reference_objectives(array('references' => $references, 'ter_value' => $ter_value));
                $print_ter = false; 
            } else {
                $print_ter = true;
            }
            if(count($references) > 0 OR $temp_ter_id == 0){ $temp_ter_id = $ter_value->id; }
            if (is_array($ena)){
                foreach ($ena as $ena_value) {
                    $ena_obj->id        = $ena_value->id;
                    $ena_obj->load();  
                    $reference = new Reference();
                    $references = $reference->get('reference_id', $_SESSION['CONTEXT']['enabling_objective']->context_id, $ena_obj->id);
                    
                    //if ($temp_ter_id != $ter_value->id OR $temp_ter_id == 0){ $print_ter = true; } else { $print_ter = false; }
                    $content    .= SELF::print_reference_objectives(array('references' => $references, 'dependency' => 'enabling_objective', 'ter_value' => $ter_value, 'ena_value' => $ena_value, 'print_ter' => $print_ter));
                }
            }
           
            
         }

         if (count($ena) > 0){
             $pagebreak = true;   
         }
         
        /* Print Text References*/
        $linked_curricula   = $c->loadConfig(); 
        foreach ($linked_curricula as $l_cur_id) {

            $ter_ids = $c->getFieldArray($l_cur_id, 'terminal_objectives');
            $ena_ids = $c->getFieldArray($l_cur_id, 'enabling_objectives');
            $ct_ids  = $c->getFieldArray($id, 'curriculum_content');
            $quote   = new Quote(); 
            $cur_ct_refs = $quote->get('curriculum_content', $ct_ids, $ter_ids, $ena_ids);
            if (count($cur_ct_refs) > 0){
                $content .= '<h4>Bezüge zu Lehrplantexten</h4>'; 
                $content .= RENDER::print_reference_quote(array('references' => $cur_ct_refs, 'curriculum_id' => $id ));
            }
        }
         
         return $content;
    }
    /**
     * Generate References 
     * @param array $params
     * @return string html
     */
    public static function print_reference_objectives($params){
        $content     = ''; 
        $dependency  = 'terminal_objective';
        $print_ter   = true;
        $temp_cur_id = 0;
        $temp_ter_id = 0;
        $temp_ena_id = 0;
        foreach($params as $key => $val) {
            $$key   = $val;
        }
        
        if (count($references) > 0){
            switch ($dependency) {
                case 'terminal_objective':
                         $content    .= '<div style="padding:2px;border:1px solid '.$ter_value->color.';background:'.$ter_value->color.'; color:'.getContrastColor($ter_value->color).';"><small>'.strip_tags($ter_value->terminal_objective).'</small></div>';
                    break;
                case 'enabling_objective':
                    if ($print_ter){
                        $content    .= '<div style="padding:2px;border:1px solid '.$ter_value->color.';background:'.$ter_value->color.'; color:'.getContrastColor($ter_value->color).';"><small>'.strip_tags($ter_value->terminal_objective, '<br>').'</small></div>';
                    }
                        $content    .= '<div style="padding:2px;border:1px solid '.$ter_value->color.';background:#FFF; color:#000;"><small>'.strip_tags($ena_value->enabling_objective, '<br>').'</small></div>';
                    break;

                default:
                    break;
            }

            if (!empty($references)){
                $content    .='<table  frame="box" style="width:100%; overflow: wrap; vertical-align: top; autosize:1;">';   
            }
            $i   = 0;
            $j   = 0;
             
            $max = count($references);
            foreach ($references as $ref) { 
                $j_max = 1;
                $k_max = 1;
                $css = 'border-bottom:0.5pt solid black;'; 
                if ($temp_cur_id == 0 OR $temp_cur_id != $ref->curriculum_object->id){
                    $content    .= '<tr><td colspan="3" style="height:10px;"></td> </tr><tr><td colspan="3" style="width:100%;margin-top:10px;background:#DDD; "><small>Überfachlicher Bezug zu <i> '.strip_tags($ref->curriculum_object->curriculum).'</i>' .'</small></td></tr>';
                    if ($i == 0) {
                        $content    .='<tr><thead><th style="width:30%; '.$css.'"><small>Kompetenz/Inhalt</small></th><th style="width:30%; '.$css.'"><small>Teilkompetenz/Konkretisierung</small></th><th style="width:40%; '.$css.'"><small>Anregung zur Unterrichtsgestaltung</small></th></thead></tr>';  
                    }
                    $temp_cur_id = $ref->curriculum_object->id;
                }
                if ($i == $max - 1 OR $references[$i+1]->curriculum_object->id != $temp_cur_id) { 
                    $css = 'padding-bottom: 10px;'; 
                } 
               
                $content    .='<tr>';
                    if (isset($references[$i+1]->terminal_object->terminal_objective)) {
                        if ($references[$i+1]->terminal_object->id == $ref->terminal_object->id){
                           $css = '' ;
                        } else {
                           $css = 'border-bottom:0.5pt solid black;';   
                        }    
                    } 

                    $content    .='<td style="width:30%; '.$css.'">';   
                    if ($temp_ter_id == 0 OR $temp_ter_id != $ref->terminal_object->id){
                        RENDER::reference('terminal_objective', $ref->terminal_object->id, array('schooltype_id' => 'false', 'subject_id' => 'false', 'curriculum_id' => 'false', 'grade_id' => 'false', 'ajax' => 'false')) .'</div>';
                        $content .= '<small>'.strip_tags($ref->terminal_object->terminal_objective).'</small>';
                        $temp_ter_id = $ref->terminal_object->id;   
                    }
                    $content .= '</td>';

                    if ($i == $max - 1 OR $references[$i+1]->curriculum_object->id != $temp_cur_id) { 
                        $css = 'padding-bottom: 10px;'; 
                    } else {
                        $css = 'border-bottom:0.5pt solid black;';   
                    }
   
                    if (isset($references[$i+1]->enabling_object->enabling_objective)) {
                        if ($references[$i+1]->enabling_object->id == $ref->enabling_object->id){
                           $css = '' ;
                        } else {
                           $css = 'border-bottom:0.5pt solid black;';   
                        }    
                    } 
                    $content    .='<td style="width:30%; '.$css.'">';  
                    if ($temp_ena_id == 0 OR $temp_ena_id != $ref->enabling_object->id){
                        if ($ref->context_id == $_SESSION['CONTEXT']['enabling_objective']->context_id){
                            $content .= '<small>'.strip_tags($ref->enabling_object->enabling_objective, '<br>').'</small>';
                            $temp_ena_id = $ref->enabling_object->id;
                        }
                    }
                    $content    .='</td>';
                    
                    if (isset($references[$i+1]->content_object->content)) {
                        if ($references[$i+1]->content_object->content == $ref->content_object->content){
                           $css = '' ;
                        } else {
                           $css = 'border-bottom:0.5pt solid black;';   
                        }    
                    }
                    /* Print reference content */
                    $content    .='<td style="width:40%; '.$css.'">'; 
                    if (isset($ref->content_object->content)){
                        if ($ref->content_object->content != ''){
                            if (isset($references[$i-1]->content_object->content)){
                                if ($references[$i-1]->content_object->content != $ref->content_object->content){
                                    $content .= '<small>'.strip_tags($ref->content_object->content, '<br>').'</small>' ;
                                }
                            } else {
                                $content .= '<small>'.strip_tags($ref->content_object->content, '<br>').'</small>' ; 
                            }
                        }
                    }
                    $content .= '</td>';
                    
                $content    .='</tr>';
                $i++;
            }
             if (!empty($references)){
                $content    .='</table><br><br>';
            }
        
        }
        
        return $content; 
    }
    public static function print_reference_quote($params){
        $content     = '';    
        $temp_ref_id = 0;
        $temp_cur_id = 0;
        $temp_ter_id = 0;
        $temp_ena_id = 0;
        $content_id  = '';
       
        foreach($params as $key => $val) {
            $$key   = $val;
        }
        
        if (count($references) > 0 ){
            if (!empty($references)){
                $content    .='<table  frame="box" style="width:100%; overflow: wrap; vertical-align: top; autosize:1;">';   
            }
            
            $c        = new Curriculum();    
            $c->id    = $curriculum_id;
            $c->load();
            $content .= '<tr><td colspan="3" style="height:10px;"></td> </tr><tr><td colspan="3" style="width:100%;margin-top:10px;background:#DDD; "><small>'.$c->curriculum.'</small></td></tr>';
            
            $i = 0;
            $max = count($references);
            foreach ($references as $ref) { 
                $css = 'border-bottom:0.5pt solid black;'; 
                if ($ref->quote_link != $content_id){ //if new content render Title
                    $content .= '<tr><td colspan="3" style="height:10px;"></td> </tr><tr><td colspan="3" style="width:100%;margin-top:10px;background:#DDD; "><small>'.$ref->reference_title.'</small></td></tr>';
                    if ($i == 0) {
                        $content    .='<tr><thead><th style="width:30%; '.$css.'"><small>Kompetenz/Inhalt</small></th><th style="width:30%; '.$css.'"><small>Teilkompetenz/Konkretisierung</small></th><th style="width:40%; '.$css.'"><small>Fundstelle im Text</small></th></thead></tr>';  
                    }
                    
                }
                $content_id = $ref->quote_link;
                
                $content    .='<tr><td style="width:30%; '.$css.'">';   
                if ($temp_ter_id == 0 OR $temp_ter_id != $ref->terminal_object->id){     
                    if ($ref->context_id == $_SESSION['CONTEXT']['terminal_objective']->context_id) {
                        $content .= '<small>'.strip_tags($ref->terminal_object->terminal_objective).'</small>';
                        $temp_ter_id = $ref->terminal_object->id;   
                    }    
                }
                $content    .='</td><td style="width:30%; '.$css.'">';  
                if ($temp_ena_id == 0 OR $temp_ena_id != $ref->enabling_object->id){
                    if ($ref->context_id == $_SESSION['CONTEXT']['enabling_objective']->context_id){
                        $content .= '<small>'.strip_tags($ref->enabling_object->enabling_objective, '<br>').'</small>';
                        $temp_ena_id = $ref->enabling_object->id;
                    }
                }
                if (isset($references[$i+1]->content_object->content)){
                    if ($ref->content_object->content == $references[$i+1]->content_object->content){
                        $css = '';
                    }
                }
                
                if (isset($references[$i+1]->quote)) {
                        if ($references[$i+1]->quote == $ref->quote){
                           $css = '' ;
                        } else {
                           $css = 'border-bottom:0.5pt solid black;';   
                        }    
                    }
                
                $content    .='</td><td style="width:40%; '.$css.'">'; 
                if ($ref->quote != ''){
                    if (isset($references[$i-1]->quote)){
                        if ($references[$i-1]->quote != $ref->quote){
                            $content .= '<small>'.strip_tags($ref->quote, '<br>').'</small>' ;
                        }
                    } else {
                        $content .= '<small>'.strip_tags($ref->quote, '<br>').'</small>' ; 
                    }
                }
                $content .= '</td>';
                $content    .='</tr>';
                $i++;                
            }
             if (!empty($references)){
                $content    .='</table><br><br>';
            }
        
        }
        
        return $content; 
    }
    
    /**
     * 
     * @param string $func
     * @param int $id
     * @param array $get
     * @return string
     */
    public static function external_media($func, $id, $get){
        $content     = '';
        $subject   = new Subject();
        if ($get['m_boxes_json'] != []){
            $m_boxes_data  = json_decode(urldecode($get['m_boxes_json']), true);
            $subject_id = '';

            foreach ($m_boxes_data as $m_box) {
                if (isset($m_box['title'])) {
                    if (in_array($get['subject'], $m_box['subjects']) or $get['subject'] == 'false') {
                        $content .= Form::info_box($m_box);
                    }
                }
            }
        } else {
            $content .= 'Keine Medien für diese Kompetenz, dieses Fach vorhanden vorhanden.';
        }
        if ($get['ajax'] == 'true'){ 
            echo $content;
        } else {
            return $content;
        }
    }
    
    public static function plugin_config( $plugin_class ){
        $plugin = new $plugin_class();
        if (method_exists($plugin,'plugin_config')){
            return $plugin->plugin_config();
        }
        //return $plugin::PLUGINNAME;
    }

    
    public static function objective_list($params){
        global $USER;
        foreach($params AS $key=>$value){
            $$key = $value;
        }
        
        switch ($dependency){
            case 'courseBook':
                $os = new ObjectiveSubscription();
                $to_ids = ObjectiveSubscription::getSubscriptionIds(10, $id, 27);
                if (count($to_ids)>0){
                    $os->id = $to_ids[0];
                }else{
                    $os->id = 0;
                }
                
                $code = '';
                if ($os->load()){
                    #terminal laden
                    $terminal = new TerminalObjective();
                    $terminal->id = $os->reference_id;
                    $terminal->load();
                    $code .= '<div style="display:inline-table;">' . RENDER::objective(["type" =>"terminal_objective", "objective" => $terminal , "user_id" => $USER->id]) .'</div>';
                    
                    #enable laden
                    $enable = new EnablingObjective();
                    $enable_ids = ObjectiveSubscription::getSubscriptionIds(10, $id, 12);
                    foreach ($enable_ids AS $eid){
                        $enable->id = $eid;
                        $enable->load();
                        $code .= '<div style="display:inline-table; padding:10px">' . RENDER::objective(["type" =>"enabling_objective", "objective" => $enable , "border_color" => $terminal->color, "user_id" => $USER->id]) .'</div>';
                        
                    }
                }
                
                return $code;
                break;
            default:
                return "";
                break;
        }
        
    }

    /**
     * array($terminalObjectives, $user, $sel_curriculum, $selected_user_id, $enabledObjectives)
     * */
    public static function curriculum($params){
        global $CFG; 
        foreach($params as $key => $val) {
            $$key   = $val;
        }
        $html = '<div id="curriculum_content" class="row ">';
        if (isset($terminalObjectives)){  
            $html .= '<div class="col-xs-12">
                <div class="box box-default">
                    <div class="box-header ">';
                        if (count($selected_user_id) > 1){
                            $user  = new User(); 
                            $user->load('id', $selected_user_id[0], false);
                            if (isset($user->avatar)){
                                 $html .= Render::split_button($cur_content);
                                 $html .='<img src="'.$CFG->access_file.$user->avatar.'" style="height:40px;"class="user-image pull-left margin-r-5" alt="User Image">';
                            }
                            $html .= Render::badge_preview(["reference_id" => $sel_curriculum, "user_id" => $selected_user_id]);
                        }
                        $html .=' <p class="pull-right">Farb-Legende:
                            <button class="btn btn-success btn-flat" style="cursor:default">selbständig erreicht</button>
                            <button class="btn btn-warning btn-flat" style="cursor:default">mit Hilfe erreicht</button>
                            <button class="btn btn-danger btn-flat" style="cursor:default">nicht erreicht</button>
                            <button class="btn btn-default disabled btn-flat" style="cursor:default">nicht bearbeitet</button>
                            </p>
                        </div>';
                    
          $html .= '<div class="box-body" style="min-height:400px;">';

                    if ( isset($terminalObjectives)){
                        foreach ($terminalObjectives as $terid => $ter) {
                        $html .='<div class="row" ><div class="col-xs-12">';
                                // Thema
                                $html .= RENDER::objective(["type" =>"terminal_objective", "objective" => $ter , "user_id" => $selected_user_id,"group_id" => $sel_group_id]);
                                // Ziele
                                foreach ($enabledObjectives as $enaid => $ena) {
                                    if ($ena->terminal_objective_id == $ter->id){
                                        $html .= RENDER::objective(["type" =>"enabling_objective", "objective" => $ena , "user_id" => $selected_user_id, "group_id" => $sel_group_id, "border_color" => $ter->color]);
                                    }
                                }
                                 $html .='</div></div>';
                        }
                    } else {
                        if (isset($selected_user_id)){
                            $html .= '<p>Es wurden noch keine Kompetenzen eingegeben.</p>
                                      <p>Dies können sie unter Lehrpläne machen.</p>';
                        } else {
                            if (isset($sel_curriculum)) { //Wenn noch keine Lehrpläne angelegt wurden
                            $html .= '<p>Bitte wählen sie einen Benutzer aus.</p>';
                            }            
                        }
                    } 
                    $html .= '</div>
                </div>
            </div>';
        }
    $html .= '</div>';   
    return $html;
    }
    
}
