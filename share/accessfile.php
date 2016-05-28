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
* This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by  
* the Free Software Foundation; either version 3 of the License, or (at your option) any later version.                                   
*                                                                       
* This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of        
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details:                          
*                                                                       
* http://www.gnu.org/copyleft/gpl.html      
*/
ob_start();
include_once('setup.php');  //Läd Klassen, DB Zugriff und Funktionen
global $CFG, $USER;

//error_log($_SERVER['REMOTE_ADDR'].' acclib: '.session_id().' file:'.filter_input(INPUT_GET, 'file').' sesusr:');
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