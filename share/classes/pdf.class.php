<?php
/**
* This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename pdf.class.php
* @copyright 2013 joachimdieterich
* @author joachimdieterich
* @date 2014.07.30 10:06
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

class Pdf {
    public $font_size       =  10.0;
    public $font_name       = 'Helvetica';
    public $font_encoding   = 'utf8/unicode';
    public $page_format     = 'A4'; // A4-L for landscape 
    
    public $path;
    public $filename        = 'zertifikat.pdf';
    public $content        = ''; 
    
    public function generate(){
        global $USER, $CFG, $TEMPLATE;

        include_once(dirname(__FILE__).'/../libs/MPDF57/mpdf.php');
        $mpdf           = new mPDF($this->font_encoding, $this->page_format, $this->font_size, $this->font_name);
        /*$stylesheet     = file_get_contents($CFG->smarty_template_dir.'bootstrap/css/bootstrap.css');
        $stylesheet     .= file_get_contents($CFG->smarty_template_dir.'css/AdminLTE.min.css');
        $stylesheet     .= file_get_contents($CFG->smarty_template_dir.'skins/_all-skins.min.css');
        $stylesheet     .= file_get_contents($TEMPLATE->template_dir.'css/all-bs.min.css');
        $mpdf->WriteHTML($stylesheet,1);*/
        $mpdf->WriteHTML($this->content, 2);
        if (file_exists($CFG->curriculumdata_root.$this->path.$this->filename)){
            unlink($CFG->curriculumdata_root.$this->path.$this->filename); 
        }
        silent_mkdir($CFG->curriculumdata_root.$this->path); //add user folder if not exists
        $mpdf->Output($CFG->curriculumdata_root.$this->path.$this->filename, 'F');
        set_time_limit(30);
        header("Location: ".$CFG->access_file_url.$this->path.$this->filename);
    }
}