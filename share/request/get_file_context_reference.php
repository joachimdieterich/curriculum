<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename get_file_context_referecne.php
* @copyright 2018 Joachim Dieterich
* @author Joachim Dieterich
* @date 2018.10.08 12:10
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
$base_url  = dirname(__FILE__).'/../';
include($base_url.'setup.php');  //Läd Klassen, DB Zugriff und Funktionen
include_once(dirname(__FILE__).'/../login-check.php');  //check login status and reset idletimer
global $USER;
$USER      = $_SESSION['USER'];

switch (filter_input(INPUT_GET, 'dependency_id', FILTER_VALIDATE_INT)) { //format is used to get case
    case 1:     //todo
        break;  
    case 2:     //todo
        break;
    case 3:     //todo
        break;  
    case 4:     //todo
        break;
    default:
        break;
}



$html      = '';

foreach ($obj as $value) {
    $html  .=  '<option label="'.strip_tags($value->$objective).'" value="'.$value->id.'"'; 
    if (filter_input(INPUT_GET, 'select_id', FILTER_VALIDATE_INT) == $value->id) { 
        $html  .= ' selected="selected"';    
    } 
    $html  .= '><span>'.strip_tags($value->$objective).'<span></option>';
}
echo json_encode(array('html'=>$html));