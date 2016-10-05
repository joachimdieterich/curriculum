<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename f_preview.php
* @copyright 2016 Joachim Dieterich
* @author Joachim Dieterich
* @date 2016.09.30 15:55
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
$base_url   = dirname(__FILE__).'/../';
include($base_url.'setup.php');  //Läd Klassen, DB Zugriff und Funktionen
include(dirname(__FILE__).'/../login-check.php');  //check login status and reset idletimer
global $USER;
global $CFG;
$USER       = $_SESSION['USER'];
$func       = $_GET['func'];
$padding    = '';
$options    = '';
$script     = null;
$file       = new File();

switch ($func) {
    case 'help':    $help       = new Help();
                    $help->load(filter_input(INPUT_GET, 'id',   FILTER_SANITIZE_STRING));
                    $file->load($help->file_id);
                    $title      = $help->title;
                    $category   = $help->category;
        break;

    case 'file':    $file->load(filter_input(INPUT_GET, 'id',   FILTER_SANITIZE_STRING));
                    $title      = $file->title;
                    $context    = new Context();
                    $context->resolve('context_id', $file->context_id);                
                    $category   = $context->context;
                    $padding    = 'padding:0px;';
                    if ($file->type != '.url'){
                        $options    = '<a href="'.$file->getFileUrl().'" class="btn btn-default btn-xs pull-right" style="margin-right:20px;"><i class="fa fa-cloud-download"></i></a>';
                    }
                    $z_index    = 3001;
                            
        break;
    default:   
        break;
}



switch ($file->type) {
    case '.mp4':
    case '.mov':    $content     = '<video width="100%" controls>
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
                    //$script_file = '<script_file>'.$CFG->base_url.'public/assets/scripts/PDFObject-master/pdfobject.js</script_file>';
                    $script      = '<script>PDFObject.embed("'.$CFG->access_file.$file->context_path.$file->path.$file->filename.'", "#pdf_'.$file_id.'");</script>';
                    break;
    
    case '.rtf':    include_once $CFG->lib_root.'rtf-html-php-master/rtf-html-php.php';
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
    default:        $content     = $file->renderFile();
        break;
}

        
$html       = Form::modal(array('title'   => $title.'<small>  |  '.$category.'</small>'.$options,
                                'content' => $content, 
                                'c_color' => '#ecf0f5',
                                'target'  => 'null', 
                                'background' => ';'.$padding));
                  
echo json_encode(array('html'=>$html, 'script' => $script, 'zindex' => $z_index));