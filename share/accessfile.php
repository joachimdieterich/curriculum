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
include_once 'config.php';          //Läd config.php
global $CFG;
/*$param = filter_input(INPUT_GET, 'file'); // token muss gemeinsam mit path übergeben werden, damit externe plugins funktionieren (Einbetten von Dateien in den Nachrichten)

list($token, $path) = explode('|', $param);
$path  = realpath($CFG->curriculumdata_root.$path);
error_log($token);error_log($path);
if ($token != $CFG->salt || !is_file($path)){ 
    die();
}  */

$path  = realpath($CFG->curriculumdata_root.filter_input(INPUT_GET, 'file'));
if (!is_file($path)){ 
    die();
}
if (filter_input(INPUT_GET, 'download') == true){
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.basename($path));
    header('Content-Length: ' . filesize($path));
    readfile($path);
    exit();
} else {
    header('Content-Type: ' . mime_content_type($path));
    header('Content-Disposition: attachment; filename='.basename($path));
    header('Content-Length: ' . filesize($path));
    readfile($path);
    exit();
}