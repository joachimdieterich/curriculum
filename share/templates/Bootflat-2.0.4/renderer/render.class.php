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
   
    public static function accomplish($string, $student, $teacher){
        global $s, $t;
        $s = $student;
        $t = $teacher;
       
        return preg_replace_callback('/<accomplish id="(\d+)"><\/accomplish>/i', 
            function($r){ 
                global $s, $t; 
                return Render::accCheckboxes($r[1], $s, $t);
            }, $string);     
    }
    
     public static function accCheckboxes($id, $student, $teacher, $link = true, $email = false, $token = false){
        global $USER, $CFG;
        if ($USER->id != $student OR $student == $teacher){ // 2. Bedingung bewirkt, dass als Lehrer eigene Einreichungen bewerten kann --> für Demonstration der Plattform wichtig
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
            
            if ((!checkCapabilities('objectives:setStatus', $USER->role_id, false)) AND (!$email)){ //if student or email
               $status = $ena->accomplished_status_id;
                if (strlen($status) > 1){
                    $teacher_status = substr($status, 1,1);
                } else {
                    $teacher_status = 'x';
                }
                $teacher = $student;
                $html   = '<a class="pointer_hand"><i id="'.$id.'_green" style="font-size:18px;" class="'.$green.' margin-r-5 text-green pointer_hand" onclick="setAccomplishedObjectivesBySolution('.$teacher.', \''.$student.'\', '.$id.', \'1'.$teacher_status.'\')"></i></a>'
                    . '<a class="pointer_hand"><i id="'.$id.'_orange" style="font-size:18px;" class="'.$orange.' margin-r-5 text-orange pointer_hand" onclick="setAccomplishedObjectivesBySolution('.$teacher.', \''.$student.'\', '.$id.', \'2'.$teacher_status.'\')"></i></a>'
                    . '<a class="pointer_hand"><i id="'.$id.'_red" style="font-size:18px; " class="'.$red.' margin-r-5 text-red pointer_hand" onclick="setAccomplishedObjectivesBySolution('.$teacher.', \''.$student.'\', '.$id.', \'0'.$teacher_status.'\')"></i></a>'
                    . '<a class="pointer_hand"><i id="'.$id.'_white" style="font-size:18px; " class="'.$white.' margin-r-5 text-gray pointer_hand" onclick="setAccomplishedObjectivesBySolution('.$teacher.', \''.$student.'\', '.$id.', \'3'.$teacher_status.'\')"></i></a>';
            } else {
                $status = $ena->accomplished_status_id;
                if (strlen($status) > 1){
                    $student_status = substr($status, 0,1);
                } else {
                    $student_status = 'x';
                }
                if ($email AND $token){ //generate Links for Email
                    $html   = '<br><strong>Lösung bewerten:</strong><br><br>Nutzer hat das Ziel ...<br><br>';
                    $html  .= '<a href="'.$CFG->base_url.'public/index.php?action=extern&teacher='.$teacher.'&student='.$student.'&ena_id='.$id.'&status='.$student_status.'1'.'&token='.$token.'">... selbständig erreicht.</a>'
                        . '<br><a href="'.$CFG->base_url.'public/index.php?action=extern&teacher='.$teacher.'&student='.$student.'&ena_id='.$id.'&status='.$student_status.'2'.'&token='.$token.'">... mit Hilfe erreicht.</a>'
                        . '<br><a href="'.$CFG->base_url.'public/index.php?action=extern&teacher='.$teacher.'&student='.$student.'&ena_id='.$id.'&status='.$student_status.'3'.'&token='.$token.'">... nicht bearbeitet.</a>'
                        . '<br><a href="'.$CFG->base_url.'public/index.php?action=extern&teacher='.$teacher.'&student='.$student.'&ena_id='.$id.'&status='.$student_status.'0'.'&token='.$token.'">... nicht erreicht.</a>';
                } else {
                    $html   = '<a class="pointer_hand"><i id="'.$id.'_green" style="font-size:18px;" class="'.$green.' margin-r-5 text-green pointer_hand" onclick="setAccomplishedObjectivesBySolution('.$teacher.', \''.$student.'\', '.$id.', \''.$student_status.'1\')"></i></a>'
                        . '<a class="pointer_hand"><i id="'.$id.'_orange" style="font-size:18px; " class="'.$orange.' margin-r-5 text-orange pointer_hand" onclick="setAccomplishedObjectivesBySolution('.$teacher.', \''.$student.'\', '.$id.', \''.$student_status.'2\')"></i></a>'
                        . '<a class="pointer_hand"><i id="'.$id.'_red" style="font-size:18px;" class="'.$red.' margin-r-5 text-red pointer_hand" onclick="setAccomplishedObjectivesBySolution('.$teacher.', \''.$student.'\', '.$id.', \''.$student_status.'0\')"></i></a>'
                        . '<a class="pointer_hand"><i id="'.$id.'_white" style="font-size:18px;" class="'.$white.' margin-r-5 text-gray pointer_hand" onclick="setAccomplishedObjectivesBySolution('.$teacher.', \''.$student.'\', '.$id.', \''.$student_status.'3\')"></i></a>';
                }
            }
            
            if ($link){
                $group_id   = $course->getGroupID($ena->curriculum_id, $teacher, $student);
                $html  .= '<button class="btn btn-default btn-sm"><a href="index.php?action=objectives&course='.$ena->curriculum_id.'_'.$group_id.'&paginator=userPaginator&userPaginator_sel_id='.$student.'&certificate_template=-1&reset"><i class="fa fa-th"></i> Zum Lehrplan</a></button>'     ;
            }
            return $html;
        }
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
                                $content = RENDER::thumb(array($file->id), null, 'div');
                            }
                break;
        }
        
        return $content;
    }
    
    public static function thumb($file_list, $target = null, $tag = 'li', $format='normal'){
        global $USER;
        $height   = 187;
        $width    = 133;
        $truncate = 15;
        $file     = new File();
        $html     = '';
        $icon     = false;
        if (!is_array($file_list)){
            $file_list = array($file_list);
        }
        foreach ($file_list as $f) {
            $file->id = $f;
            $file->load();
            /* check if img*/ 
            switch ($file->type) {
                case '.pdf': 
                case '.bmp':    
                case '.gif':       
                case '.png':    
                case '.svg':    
                case '.jpeg':    
                case '.jpg':    if ($file->getThumb() == false){ $url = $file->getFileUrl(); } else { $url = $file->getThumb(); }
                    break;
                case '.url':    
                default:        $icon = true;
                    break;
            }
            
            switch ($format) {
                case 'normal': $html .= '<'.$tag.' id="thumb_'.$file->id.'" style="width:'.$width.'px !important; height:'.$height.'px !important;">';
                                if ($icon == true){
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
                                            <a href="#" class="mailbox-attachment-name " ><small>'.$file->filename.'</small></a>
                                            <span class="mailbox-attachment-size">';
                                            if ($target != null){
                                                $html .= '<a href="#" class="btn btn-default btn-xs" data-toggle="tooltip" title="Verwenden" onclick="setTarget('.$file->id.');"><i class="fa fa-check-circle"></i></a>';
                                            }
                                            if ($icon != true){
                                                $html .= '<a href="#" class="btn btn-default btn-xs" data-toggle="tooltip" title="Vorschau" onclick="formloader(\'preview\',\'file\','.$file->id.');"><i class="fa fa-eye"></i></a>';
                                            }
                                            if ($file->type != '.url'){
                                                $html .= '<a href="'.$file->getFileUrl().'" data-toggle="tooltip" title="herunterladen" class="btn btn-default btn-xs"><i class="fa fa-cloud-download"></i></a>';
                                            }
                                            if (checkCapabilities('file:update', $USER->role_id, false)){
                                                $html .= '<a href="#" class="btn btn-default btn-xs" data-toggle="tooltip" title="bearbeiten" onclick="formloader(\'file\',\'edit\','.$file->id.');"><i class="fa fa-edit"></i></a>';
                                            }
                                            if (checkCapabilities('file:delete', $USER->role_id, false)){
                                                $html .= '<a href="#" class="btn btn-default btn-xs pull-right" data-toggle="tooltip" title="löschen" onclick="del(\'file\','.$file->id.');"><i class="fa fa-trash"></i></a>';
                                            }
                                $html .= '</span></div></'.$tag.'>'; 


                    break;
                case 'xs':      $html .=   '<div class="btn-group" style="padding-right:10px;padding-bottom:2px;">
                                            <button type="button" class="btn btn-xs btn-default btn-flat" style="width:'.($width+17).'px !important; text-align:left;">';
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
                                    $html .=   '<i class="'.resolveFileType($file->type).' info-box-icon"></i>';
                                } else {
                                    $html .=   '<div style="height: 90px;
  width: 100%; background: url(\''.$url.'\') center; background-size: cover; background-repeat: no-repeat;"></div>';
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
        $html =   '<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">';
                    
                    $html .= '<div class="thumbnail">
                                <div class="row">
                                <div class="col-xs-12" >';
                    $html .= RENDER::thumb($help->file_id, null, null, $format='thumb');      
                            //<span class="info-box-icon bg-aqua" style="background: url(\'../share/accessfile.php?id='.$help->file_id.');background-size: cover; background-repeat: no-repeat;"></span>
                    $html .= '  </div></div>
                                ';
                    if (checkCapabilities('help:update', $USER->role_id, false)){
                        $html .='<a class="pull-right"><span class="fa fa-edit padding-top-5 margin-r-5" onclick="formloader(\'help\',\'edit\','.$help->id.');"></span></a>';
                    }
                    if (checkCapabilities('help:add', $USER->role_id, false)){
                        $html .='<a class="pull-right"><span class="fa fa-trash padding-top-5 margin-r-5" onclick="del(\'help\','.$help->id.');"></span></a>';
                    }
                    $html .= '  <div class="caption text-center">
                        <br>
                                  <h5 class="events-heading text-ellipse"><a href="#" onclick="formloader(\'preview\',\'help\','.$help->id.')">'.$help->title.'</a></h5>
                                  <p style="overflow: scroll; width: 100%; height: 80px;"><small>'.$help->category.'</small><br>'.$help->description.'</p>
                                </div>
                              </div><!-- /.events-->
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
                                if ($wallet->creator_id == $USER->id){ //only owner can edit and delete wallet(settings) 
                                $html .= '<span style="position: absolute; right:15px;" >';
                                    if (checkCapabilities('help:add', $USER->role_id, false)){
                                        $html .= '<button type="button"  onclick="formloader(\'wallet\',\'edit\','.$wallet->id.');"><i class="fa fa-edit"></i></button>';
                                    }
                                    if (checkCapabilities('help:add', $USER->role_id, false)){
                                        $html .= '<button type="button"  onclick="del(\'wallet\','.$wallet->id.');"><i class="fa fa-trash"></i></button>';
                                    }
                                    $html .= '</span>';
                                }
                    $html .= RENDER::thumb($wallet->file_id, null, null, $format='thumb');      
                    $html .= '  </div>
                                </div>';
                    switch ($wallet->permission) {
                        case '0': $icon = 'eye';            $tt = 'lesezugriff';    break;
                        case '1': $icon = 'commenting-o';   $tt = 'kommentierbar';  break;
                        case '2': $icon = 'pencil';         $tt = 'schreibzugriff'; break;
                        default: break;
                    }
                    $html .='<span class="fa fa-'.$icon.' padding-top-5 margin-r-5 pull-right"  data-toggle="tooltip" title="'.$tt.'"></span>';
                    $html .= '  <div class="caption text-center">
                                  <h5 class="events-heading text-ellipse"><a href="index.php?action=walletView&wallet='.$wallet->id.'">'.$wallet->title.'</a></h5>
                                  <small>'.$wallet->timerange.'</small>    
                                  <p style="overflow: scroll; width: 100%; height: 100px;">'.$wallet->description.'</p>
                                </div>
                              </div>
                    </div>';
        return $html;                                    
    }
    public static function wallet_content($wallet_content, $edit){
       $html  =   '<div class="'.$wallet_content->width_class;
       if ($edit == true){
           $html  .=   ' wallet-content"><span style="position: absolute; right:15px;" ><button type="button" onclick="formloader(\'wallet_content\',\'edit\','.$wallet_content->id.');"><i class="fa fa-edit"></i></button>'
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
                            $html  .=Render::file($f);
               break;
       }
       $html .=   '</span></div>';
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
       global $USER;
       $type        = 'terminal_objective'; 
       $edit        = false;
       //
       //$objective 
       foreach($params as $key => $val) {
            $$key = $val;
       }
       if (!isset($user_id)){$user_id = $USER->id;} // if user_id is set --> accCheckbox set this user 
       if (!isset($objective->color)){ 
           $objective->color = '#FFF'; 
           $text_class       = 'text-black';
       } else {
           $text_class       = 'text-white';
       }
       $html  =   '<div class="box box-objective ';
            if (isset($highlight)){
                if (in_array($type.'_'.$objective->id, $highlight)){
                    $html  .= 'highlight';
                }
            }
        $html  .= ' style="background: '.$objective->color.'"> 
                            <div class="boxheader" >';
            if ($edit){
                $html  .= '<span class="fa fa-minus pull-right box-sm-icon text-primary" onclick="del('.$type.', '.$objective->id.');"></span>
                                    <span class="fa fa-edit pull-right box-sm-icon text-primary" onclick="formloader('.$type.',\'edit\', '.$objective->id.');"></span>';
                if ($orderup){
                    $html  .= '<span class="fa fa-arrow-up pull-left box-sm-icon text-primary" onclick=\'processor("orderObjective", '.$type.', "'.$objective->id.'", {"order":"down"});\'></span>';
                }
                if ($orderdown){
                    $html  .= '<span class="fa fa-arrow-down pull-left box-sm-icon text-primary" onclick=\'processor("orderObjective", '.$type.', "'.$objective->id.'", {"order":"up"});\'></span>';
                }
            }
        $html  .='  </div>
                    <div id="'.$type.'_'.$objective->id.'" class="panel-body boxwrap" >
                        <div class="boxscroll" style="background: '.$objective->color.'">
                            <div class="boxcontent '.$text_class.'">'.$objective->$type.'</div>
                        </div>
                    </div>
                    <div class="boxfooter" style="background: '.$objective->color.'">';
                        if ($objective->description != ''){
                            $html  .='<span class="fa fa-info pull-right box-sm-icon text-primary" style="padding-top:2px; margin-right:3px;" data-toggle="tooltip" title="Beschreibung" onclick="formloader(\'description\', \''.$type.'\', '.$objective->id.');"></span>';
                        }
                        if ($edit){
                            if (checkCapabilities('file:upload', $USER->role_id, false)){
                                $html  .='<a href="../share/templates/Bootflat-2.0.4/renderer/uploadframe.php?context='.$type.'&ref_id='.$objective->id.'{$tb_param}" class="nyroModal"><span class="fa fa-plus pull-right box-sm-icon"></span></a>';
                            } 
                        }
                        $html  .='<span class="pull-left" style="margin-right:10px;">';
                        if (checkCapabilities('file:loadMaterial', $USER->role_id, false) AND $objective->files != null){
                            $html  .='<span class="fa fa-briefcase box-sm-icon text-primary" style="cursor:pointer;" data-toggle="tooltip" title="'.$objective->files.' Materialien verfügbar" onclick="formloader(\'material\',\''.$type.'\','.$objective->id.')"></span>';
                        } else {
                            $html  .='<span class="fa fa-briefcase box-sm-icon deactivate"></span>';
                        }
                        $html  .='</span>';
                        if (checkCapabilities('course:selfAssessment', $USER->role_id, false)){
                            $html  .='<span class="pull-left">'.Render::accCheckboxes($objective->id, $user_id, $user_id, false).'</span>';
                        }
        $html  .='    </div>
                  </div>';
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
        $content = '<div class="panel panel-primary">
                <div class="panel-header">
                  <div class="pull-right" >
                    <div class="btn-group padding-top-5" >
                      <button class="btn btn-default btn-sm" data-toggle="tooltip" title="Antworten" onclick="formloader(\'mail\',\'reply\','.$mail->id.')"><i class="fa fa-reply"></i> Antworten</button>
                      <button class="btn btn-default btn-sm" data-toggle="tooltip" title="Weiterleiten" onclick="formloader(\'mail\',\'forward\','.$mail->id.')"><i class="fa fa-share"></i> Weiterleiten</button>
                    </div><!-- /.btn-group -->
                    <div class="btn-group padding-top-5" >
                      <button class="btn btn-default btn-sm padding-top-5" data-toggle="tooltip" title="Drucken" onclick="processor(\'print\',\'mail\','.$mail->id.')"><i class="fa fa-print"></i> Drucken</button>
                          </div>
                    <div class="btn-group padding-top-5" >
                        <button class="btn btn-default btn-sm" data-toggle="tooltip" title="zurück"><i class="fa fa-chevron-left"></i></button>
                        <button class="btn btn-default btn-sm" data-toggle="tooltip" title="vor"><i class="fa fa-chevron-right"></i></button>
                    </div><!-- /.btn-group -->
                    <div class="btn-group padding-top-5 margin-r-5" >
                        <button class="btn btn-default btn-sm padding-top-5" data-toggle="tooltip" title="löschen" onclick="del(\'message\', '. $mail->id.', \''.$box.'\')"><i class="fa fa-trash-o"></i> Löschen</button>
                    </div>
                  </div>
                </div><!-- /.panel-header -->
                <div class="panel-body ">
                  <div class="user-block" style="padding-bottom:5px; border-bottom:1px solid #e6e9ed;">
                        <img class="img-circle img-bordered-sm" src="'.$CFG->access_file.$sender->avatar.'" alt="user image">
                            <a href="#" class="pull-right btn-box-tool" onclick="formloader(\'mail\',\'gethelp\','.$users->id.');"></a>
                        <span class="username">'.$sender->firstname.' '.$sender->lastname.' ('.$sender->username.')</span><small class="pull-right">'.$mail->creation_time.'</small>
                        <span class="description">'.$mail->subject.'</span>
                  </div><!-- /.mailbox-read-info -->
                  
                  <div class="mailbox-read-message">
                    <p>'.$mail->message.'</p>
                  </div><!-- /.mailbox-read-message -->
                </div><!-- /.panel-body -->
                <div class="panel-footer">';
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
          $content .= '
                </div><!-- /.panel-footer -->
              </div><!-- /. panel -->';
        return $content;
    }
    
    public static function popup($titel, $content, $url = false, $btn = 'OK') {
        $html= '<div class="modal-dialog">
                <div class="modal-content">
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
        $content = '<div class="panel-body scroll_list" style="overflow:auto;"><form name="'.$url.'" action="'.$url.'" method="post" enctype="multipart/form-data" >';
                    
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
            $content .= RENDER::thumb(array('id' => $f->id), $target); 
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
            $content .= RENDER::thumb(array('id' => $f->id),$target,'div','xs'); 
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
    
    public static function todoList($task, $context, $reference_id){
        global $USER;
        $r       = '<h5>Aufgaben';
        if (checkCapabilities('task:add', $USER->role_id, false)){            
            $r   .= '<a class="pull-right btn btn-primary btn-xs" onclick="formloader(\'task\',\''.$context.'\', '.$reference_id.')"><i class="fa fa-plus"></i> Aufgabe hinzufügen</a>';
        }
        $r   .= '</h5><div class="panel-group panel-group-lists collapse in" id="accordion_todo_'.$reference_id.'" style="overflow: scroll; width: 100%; max-height: 300px;">';
                 foreach ($task as $tsk) {
        $r       .= ' <div class="panel">
                        <div class="panel-heading">
                            <h4 class="panel-title bg-gray">
                                <a data-toggle="collapse" data-parent="#accordion_todo_'.$reference_id.'" href="#tab_todo_'.$tsk->id.'" class="collapsed">
                                     '.$tsk->task;
                                        if (checkCapabilities('task:update', $USER->role_id, false)){
                                            $r .= '<i class="pull-right fa fa-edit" onclick="formloader(\'task\',\'edit\', '.$tsk->id.')"></i>
                                                   <i class="pull-right fa fa-trash-o" onclick="del(\'task\', '.$tsk->id.')"></i>';
                                        }
                         $r   .='</a>';           
        $r       .=     '   </h4>
                        </div>
                        <div id="tab_todo_'.$tsk->id.'" class="panel-collapse collapse" style="height: 0px;">
                            <div class="panel-body" >'.$tsk->description.'</div>
                        </div>';
        $r       .= '</div>';
                 }
        $r       .= '</div>';
        return $r;
    }
    
    public static function absentListe($absent, $context, $reference_id){
        global $CFG,$USER;
        $r      = '<h5>Abwesend';
        if (checkCapabilities('absent:add', $USER->role_id, false)){
            $r .= '<a class="pull-right btn btn-primary btn-xs" onclick="formloader(\'absent\',\''.$context.'\', '.$reference_id.')"><i class="fa fa-plus"></i> Fehlende Personen erfassen</a>';
        }
        $r     .= '</h5><div class="panel-group panel-group-lists collapse in" id="accordion_abs_'.$reference_id.'" style="overflow: scroll; width: 100%; max-height: 300px;">';
                  foreach ($absent as $ub) {
        $r     .= '<div class="panel">
                        <div class="panel-heading">
                            <h4 class="panel-title bg-gray">
                                <a data-toggle="collapse" data-parent="#accordion_abs_'.$reference_id.'" href="#tab_abs_'.$ub->id.'" class="collapsed">';
                                if (checkCapabilities('absent:update', $USER->role_id, false)){
                                    $r .= '<span class="pull-right"><i class="fa fa-edit" onclick="formloader(\'absent\',\'edit\', '.$ub->id.')"></i>
                                                <i class="fa fa-trash-o" onclick="del(\'absent\', '.$ub->id.')"></i></span>';
                                }
                       $r     .= ' <img class="pull-left media-object img-rounded margin-r-5" style="max-height:20px;max-width:20px;" src="'.$CFG->access_id_url.$ub->user->avatar_id.'" /> 
                                    <span style="left:30px;">'.$ub->user->firstname.' '.$ub->user->lastname.' ('.$ub->user->username.')</span>'; 
                                    switch ($ub->status) {
                                        case 0: $r     .= '<span class="label label-danger pull-right margin-r-5"> unentschuldigt </span>';
                                            break;
                                        case 1: $r     .= '<span class="label label-success pull-right margin-r-5">'.$ub->done.'</span>';      
                                            break;

                                        default:
                                            break;
                                    }
                    $r     .= '</a>
                             </h4>
                         </div>
                        <div id="tab_abs_'.$ub->id.'" class="panel-collapse collapse" style="height: 0px;">
                            '.$ub->reason.'
                        </div>
                      </div>';
                  }
        $r       .= '</div>';
        return $r;
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
        $label  = 'Auswahl';
        
        foreach($params as $key => $val) {
            $$key = $val;
        }
        if (count($entrys) > 0){ // only show if entrys exists
            $html   =  '<div class="btn-group">
                            <button type="button" class="btn btn-default btn-flat dropdown-toggle" data-toggle="dropdown">'.$label.' </button>
                            <ul class="dropdown-menu" role="menu">';
                            foreach($entrys as $key => $val) {
                                $html .= '<li><a onclick="formloader(\'content\', \'show\','.$val->id.');">'.$val->title.'</span></a></li>';
                            }
            $html  .=      '</ul>
                        </div>';
            return $html;
        }
    }
    
    public static function box_widget($params){
        /*default params*/
        $class_width     = 'col-md-6';
        $bg_color       = 'primary';
        $widget_title   = 'Titel';
        $widget_desc    = 'desc';
        $bg_badge       = 'bg-primary';
        $href           = '#';
        // $data        == data array;
        // more params: $label, $badge, $bg_icon 
        
        foreach($params as $key => $val) {
            $$key = $val;
        }
        $html   =  '<div class="'.$class_width.'">
                    <div class="panel panel-default">
                      <div class="panel-heading">';
                      if (isset($bg_icon)){
                        $html   .= '<i class="pull-right '.$bg_icon.' text-'.$bg_color.'" style="font-size: 65px;"></i>';
                      }
                        $html   .= $widget_title.'<br><small>'.$widget_desc.'</small>
                      </div>
                      <div class="panel-body no-padding" style="overflow: scroll; width: 100%; max-height: 300px;">
                        <ul class="nav nav-stacked">';
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
                                $html   .= '<span class="pull-right badge '.$bg_badge.'">'.$value->$badge.'</span>';          
                            }
                            $html   .= '</a></li>';           
                        } 
        $html   .= '</ul>
                     </div>
                   </div>
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
                $html .= '<div class="col-md-6 "><div class="panel panel-'.$value['class'].'">
                            <div class="panel-heading">'.$value['header'];
                            if ($value['color']){
                                $html .= '<span class="pull-right badge bg-white '.$value['color'].'">Datum, Lehrer</span>';
                            }
                            $html .= '</div>
                            <div class="panel-body no-padding" style="overflow: scroll; width: 100%; max-height: 300px;">
                                <ul class="nav nav-stacked">';
                                if (isset($$value['var'])){
                                    foreach($$value['var'] AS $v) {
                                        $user     = new User();
                                        $html .= '<li><a href="#">'.$user->resolveUserId($v->user_id);

                                        foreach ( $solutions as $s ) { 
                                            if ( $v->user_id == $s->creator_id ) {
                                                $html .= '<span onClick=\'formloader("material", "id", '.$ena->id.', {"target":"sub_popup", "user_id": "'.$v->user_id.'"});\'>&nbsp;<i class="fa fa-paperclip"></i></span>';          
                                                break; // if one solution is found break to save time
                                            }
                                        }
                                        if ($value['color']){
                                            $html .= '<span class="pull-right badge bg-'.$value['color'].'" style="margin-top:-5px;" data-toggle="tooltip" title="" data-original-title="Nachricht schreiben" onclick="formloader(\'mail\', \'gethelp\', '.$v->creator_id.');">'.date('d.m.Y',strtotime($v->accomplished_time)).', <br>'.$user->resolveUserId($v->creator_id, 'name').'</span>';
                                        }
                                        $html .= '</a></li>';
                                    }   
                                }
                $html .= '</ul></div></div></div>'; 
            }
        }
        return $html;
    }
}