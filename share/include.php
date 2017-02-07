<?php
/** 
* This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename include.php
* @copyright 2013 Joachim Dieterich
* @author Joachim Dieterich
* @date 2013.07.25 22:37
* @license
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

include('classes/plugin.class.php');   // load plugin_base first to prevent errors in autoloader

/* Klassen laden */
/* Autoloader for classes */
spl_autoload_register(function ($className) {
    $base_dir = dirname(__FILE__). DIRECTORY_SEPARATOR;
    $extensions = array(".php", ".class.php");
    $paths = array('classes');
    foreach ($paths as $path) {
        $filename = $path . DIRECTORY_SEPARATOR . strtolower($className);
        foreach ($extensions as $ext) {
            if (is_readable($base_dir .$filename . $ext)) {
                include ($base_dir . $filename . $ext);
                break;
           }
       }
    } 
});
global $LOG;
$LOG = new Log(); 

require 'classes/video_stream.class.php';                                   //Videostream
require 'libs/htmlpurifier-4.7.0-standalone/HTMLPurifier.standalone.php';   //HTML Purifier
require 'libs/rtf-html-php-master/rtf-html-php.php';
require 'libs/PHPMailer-5.2.22/PHPMailerAutoload.php';