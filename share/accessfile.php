<?php
/**
*  This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename accessfile.php
* @copyright 2015 Joachim Dieterich
* @author Joachim Dieterich
* @date 2015.06.10 10:37
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
ob_start();
include_once('setup.php');  //Läd Klassen, DB Zugriff und Funktionen
global $CFG, $USER;

if (null != filter_input(INPUT_GET, 'token')){                  // Zugriff über token. Externe Services. 
    $f      = new File();
    $id     = $f->getFileID(filter_input(INPUT_GET, 'token'));
    if ($id == false){ die(); }
    $f->deleteFileToken(filter_input(INPUT_GET, 'token'));      // Token wird gelöscht und kann nicht mehr genutzt werden.
} else {
    //if ((!isset($_SESSION['USER'])) && (!isset($USER->id))){ echo 'Kein Zugriff!'; die(); }
}

if (null != filter_input(INPUT_GET, 'id')){
    $id   = filter_input(INPUT_GET, 'id');
} 

if (isset($id)){ 
    $f      = new File();
    $f->id  = $id;
    $f->load();
    if (filter_input(INPUT_GET, 'type')){
        $path   = realpath($CFG->curriculumdata_root.str_lreplace($f->type, '.'.filter_input(INPUT_GET, 'type'), $f->full_path)); // hack für xml Download über file_id
    } else {
        $path   = realpath($CFG->curriculumdata_root.$f->full_path);
    }
    error_log($path);
    // if file does not exist
    if (!file_exists($path)){
        $path = $CFG->curriculumdata_root.$CFG->standard_avatar;
    }
} else {
    $path   = realpath($CFG->curriculumdata_root.filter_input(INPUT_GET, 'file'));
}

if (!is_file($path)){ die(); }

if (filter_input(INPUT_GET, 'video') == true){
    $stream = new VideoStream($path);
    $stream->start();
    exit;
}

if (filter_input(INPUT_GET, 'download') == true){
    //header("Pragma: public"); //Useful when you come across this error: http://trac.edgewall.org/ticket/1020. IE 8 & less seems to like to cache things when they are on a SSL server. Putting 'Pragma:public' helps with: "Internet Explorer was not able to open this Internet site. The requested site is either unavailable or cannot be found. Please try again later"
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.basename($path));
    header('Content-Length: ' . filesize($path));
    ob_flush(); flush();// empty output buffer
    readfile($path);
    exit();
} else { 
    //header("Pragma: public"); //Useful when you come across this error: http://trac.edgewall.org/ticket/1020. IE 8 & less seems to like to cache things when they are on a SSL server. Putting 'Pragma:public' helps with: "Internet Explorer was not able to open this Internet site. The requested site is either unavailable or cannot be found. Please try again later"
    //header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    //header("Cache-Control: private", false); // required for certain browsers 
    header('Expires: 0');
   
    header("Content-Transfer-Encoding: binary"); 
    header('Content-Type: ' . mime_content_type($path));
    header('Content-Disposition: attachment; filename='.basename($path));
    header('Content-Length: ' . filesize($path));
    ob_flush(); flush(); // empty output buffer
    readfile($path);
    exit();
}