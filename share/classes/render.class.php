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
    
     public static function accCheckboxes($id, $student, $teacher, $link = true){
        global $USER;
        if ($USER->id != $student OR $student == $teacher){ // 2. Bedingung bewirkt, dass als Lehrer eigene Einreichungen bewerten kann --> für Demonstration der Plattform wichtig
            $ena       = new EnablingObjective();
            $ena->id   = $id;
            $ena->getObjectives('enabling_objective_status', $student); // get status of objective
            
            $red       = 'fa fa-circle-o';
            $green     = 'fa fa-circle-o';
            $orange    = 'fa fa-circle-o';
            $white     = 'fa fa-circle-o';
            //error_log($ena->accomplished_status_id);
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
            
            if (!checkCapabilities('objectives:setStatus', $USER->role_id, false)){
               $status = $ena->accomplished_status_id;
                if (strlen($status) > 1){
                    $teacher_status = substr($status, 1,1);
                } else {
                    $teacher_status = 'x';
                }
                $teacher = $student;
                $html   = '<a class="pointer_hand"><i id="'.$id.'_green" style="font-size:18px;" class="'.$green.' margin-r-5 text-green pointer_hand" onclick="setAccomplishedObjectivesBySolution('.$teacher.', \''.$student.'\', '.$id.', \'1'.$teacher_status.'\')"></i></a>'
                    . '<a class="pointer_hand"><i id="'.$id.'_orange" style="font-size:18px;" class="'.$orange.' margin-r-5 text-orange pointer_hand" onclick="setAccomplishedObjectivesBySolution('.$teacher.', \''.$student.'\', '.$id.', \'2'.$teacher_status.'\')"></i></a>'
                    . '<a class="pointer_hand"><i id="'.$id.'_white" style="font-size:18px;" class="'.$white.' margin-r-5 text-white pointer_hand" onclick="setAccomplishedObjectivesBySolution('.$teacher.', \''.$student.'\', '.$id.', \'3'.$teacher_status.'\')"></i></a>'
                    . '<a class="pointer_hand"><i id="'.$id.'_red" style="font-size:18px;" class="'.$red.' margin-r-5 text-red pointer_hand" onclick="setAccomplishedObjectivesBySolution('.$teacher.', \''.$student.'\', '.$id.', \'0'.$teacher_status.'\')"></i></a>';
            } else {
                $status = $ena->accomplished_status_id;
                if (strlen($status) > 1){
                    $student_status = substr($status, 0,1);
                } else {
                    $student_status = 'x';
                }
                
                $html   = '<a class="pointer_hand"><i id="'.$id.'_green" style="font-size:18px;" class="'.$green.' margin-r-5 text-green pointer_hand" onclick="setAccomplishedObjectivesBySolution('.$teacher.', \''.$student.'\', '.$id.', \''.$student_status.'1\')"></i></a>'
                    . '<a class="pointer_hand"><i id="'.$id.'_orange" style="font-size:18px;" class="'.$orange.' margin-r-5 text-orange pointer_hand" onclick="setAccomplishedObjectivesBySolution('.$teacher.', \''.$student.'\', '.$id.', \''.$student_status.'2\')"></i></a>'
                    . '<a class="pointer_hand"><i id="'.$id.'_white" style="font-size:18px;" class="'.$white.' margin-r-5 text-white pointer_hand" onclick="setAccomplishedObjectivesBySolution('.$teacher.', \''.$student.'\', '.$id.', \''.$student_status.'3\')"></i></a>'
                    . '<a class="pointer_hand"><i id="'.$id.'_red" style="font-size:18px;" class="'.$red.' margin-r-5 text-red pointer_hand" onclick="setAccomplishedObjectivesBySolution('.$teacher.', \''.$student.'\', '.$id.', \''.$student_status.'0\')"></i></a>';
            
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
                            $script      = '<script>PDFObject.embed("'.$file->getFileUrl().'", "#pdf_'.$file->id.'");</script>';
                break;
            case '.rtf':    //include_once $CFG->lib_root.'rtf-html-php-master/rtf-html-php.php';
                            $reader      = new RtfReader();
                            $reader->Parse(file_get_contents($CFG->curriculumdata_root.$file->context_path.$file->path.$file->filename));
                            $formatter   = new RtfHtml();
                            $content     = utf8_encode('<div padding>'.$formatter->Format($reader->root).'</div>');
                            $padding     = 'padding:10px;';     
                break;
            case '.txt':
                            $content     = '<p style="width:100%;">'.nl2br(htmlspecialchars(file_get_contents($CFG->curriculumdata_root.$file->context_path.$file->path.$file->filename))).'</p>';
                            $padding     = 'padding:10px;';
                break;
            case '.url':    $content     ='<iframe src="'.$file->filename.'" style="width:100%; height: 600px;"></iframe>';
                break;
            default:        if (checkCapabilities('plugin:useEmbeddableGoogleDocumentViewer', $USER->role_id, false) AND !is_array(getimagesize($CFG->curriculumdata_root.$file->full_path))){
                                $content = '<iframe src="http://docs.google.com/gview?url='.$CFG->access_token_url .$file->addFileToken($file->id).'" style="width:100%; height:500px;" frameborder="0"></iframe>';
                            } else {
                                $content = RENDER::thumb(array($file->id), null, 'div');//$file->renderFile();
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
                                $html .= '<div class="mailbox-attachment-info" style="padding:5px 5px 5px 5px;">
                                                <a href="#" class="mailbox-attachment-name" style="word-wrap: break-word;"><small>'.truncate($file->filename, $truncate).'</small></a>
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
                                                if (checkCapabilities('file:delete', $USER->role_id, false)){
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
                                    $html .=   '<i class="'.resolveFileType($file->type).' info-box-icon"></i>';
                                } else {
                                    $html .=   '<div class="info-box-icon" style="background: url(\''.$url.'\'); background-size: cover; background-repeat: no-repeat;"></div>';
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
        $html =   '<div class="col-md-3 col-sm-6 col-xs-12">';
                    if (checkCapabilities('help:update', $USER->role_id, false)){
                        $html .='<a><span class="pull-right" onclick="formloader(\'help\',\'edit\','.$help->id.');"><i class="fa fa-edit margin"></i></span></a>';
                    }
                    if (checkCapabilities('help:add', $USER->role_id, false)){
                        $html .='<a><span class="pull-right" onclick="del(\'help\','.$help->id.');"><i class="fa fa-trash top-buffer"></i></span></a>';
                    }
                    $html .= '<a href="#" onclick="formloader(\'preview\',\'help\','.$help->id.')">
                              <div class="info-box">';
                    $html .= RENDER::thumb($help->file_id, null, null, $format='thumb');      
                            //<span class="info-box-icon bg-aqua" style="background: url(\'../share/accessfile.php?id='.$help->file_id.');background-size: cover; background-repeat: no-repeat;"></span>
                    $html .= '<div class="info-box-content">
                              <span class="info-box-text text-black">'.$help->category.'</span>
                              <span class="info-box-number text-black">'.$help->title.'</span>
                              <span class="info-box-more text-primary">'.$help->description.'</span>
                            </div><!-- /.info-box-content -->
                          </div><!-- /.info-box -->
                        </a>
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
        echo '<div class="box box-primary">
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
                  echo '<i class="fa fa-paperclip"></i><strong> Anhang: </strong>';
                  }
                    foreach ($thumbs as $t) {
                        $file     = new File();
                        $file->id = $t;
                        if ($file->load()){
                            echo $file->filename;
                            echo Render::file($file);
                        } else {
                            echo 'Datei wurde gelöscht.';
                        }
                    }   
          echo '  </ul>
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
        $content = '<div class="box-body scroll_list" style="overflow:auto;"><form name="'.$url.'" action="'.$url.'" method="post" enctype="multipart/form-data" >';
                    
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
        $r       = '<ul class="todo-list ui-sortable">';
                 foreach ($task as $tsk) {
        $r       .= ' <li>
                      <!--span class="handle ui-sortable-handle">
                        <i class="fa fa-ellipsis-v"></i>
                        <i class="fa fa-ellipsis-v"></i>
                      </span>
                      <input type="checkbox" value="" name=""-->
                      <span class="text">'.$tsk->task.'</span>
                      <small class="label label-primary pull-right"><i class="fa fa-clock-o"></i>'.$tsk->timeend.'</small>
                      <br><span class="text small">'.$tsk->description.'</span>';
                        if (checkCapabilities('task:update', $USER->role_id, false)){
                            $r   .= '<div class="tools">
                                        <i class="fa fa-edit" onclick="formloader(\'task\',\'edit\', '.$tsk->id.')"></i>
                                        <i class="fa fa-trash-o" onclick="del(\'task\', '.$tsk->id.')"></i>
                                    </div>';
                        }
        $r       .= '</li>';
                 }
                 
        if (checkCapabilities('task:add', $USER->role_id, false)){            
            $r   .= '<li><a class="btn btn-primary btn-xs" onclick="formloader(\'task\',\''.$context.'\', '.$reference_id.')"><i class="fa fa-plus"></i> Aufgabe hinzufügen</a></li> </ul>';
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
        $width  = 'col-md-4';
        $status = '';
        foreach($params['blockdata'] as $key => $val) {
            $$key = $val;
        }
        if ($USER->role_id === $role_id OR $role_id == $CFG->standard_role){
        $html  = '<div class="'.$width.'">
                    <div class="box box-primary '.$status.'">
                        <div class="box-header with-border">
                              <h3 class="box-title">'.$name.'</h3>
                              <div class="box-tools pull-right">';
                                if (checkCapabilities('block:add', $USER->role_id, false)){
                                    $html  .= '<button class="btn btn-box-tool" data-widget="edit" onclick="formloader(\'block\',\'edit\','.$id.');"><i class="fa fa-edit"></i></button>';
                                }
                                $html  .= '<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
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
        $width  = 'col-md-4';
        $status = '';
        foreach($params['blockdata'] as $key => $val) {
            $$key = $val;
        }
        if ($USER->role_id === $role_id OR $role_id == $CFG->standard_role){
            $html  = '<div class="'.$width.'">
                        <div class="box box-primary '.$status.'">
                            <div class="box-header with-border">
                                  <h3 class="box-title">'.$name.'</h3>
                                  <div class="box-tools pull-right">';
                                    if (checkCapabilities('block:add', $USER->role_id, false)){
                                        $html  .= '<button class="btn btn-box-tool" data-widget="edit" onclick="formloader(\'block\',\'edit\','.$id.');"><i class="fa fa-edit"></i></button>';
                                    }
                                    $html  .= '<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
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
    /*Simple table - example see userImport.tpl*/
    public static function table($params){
        $width_class     = 'col-md-6';
        $style           = '';
        $table_class     = 'table table-bordered';
                
        foreach($params as $key => $val) {
            $$key = $val;
        }
        
        $html = '<div class="'.$width_class.'" style="'.$style.'">
                    <table class="'.$table_class.'">
                        <tbody>
                            <tr>';
                            foreach($header as $v) {
                                $html .= '<th>'.$v.'</th>';
                            }   
                            $html .='</tr>';
                            foreach($data as $d) {
                                $html .='<tr>';
                                foreach($header as $k => $v) {
                                    $html .= '<td>'.$d->$k.'</td>';
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
        $widget_type    = 'user';
        $bg_color       = 'primary';
        $widget_title   = 'Titel';
        $widget_desc    = 'desc';
        $bg_badge       = 'bg-green';
        $href           = '#';
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
                   </div><!-- /.widget-'.$widget_type.' -->
               </div><!-- /.col -->';
        return $html;
    }
}