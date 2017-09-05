<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename get_modules.php
* @copyright 2017 Joachim Dieterich
* @author Joachim Dieterich
* @date 2017.06.97 20:54
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
$base_url  = dirname(__FILE__).'/../../../../';
include($base_url.'setup.php');  //Läd Klassen, DB Zugriff und Funktionen
include($base_url.'login-check.php');  //check login status and reset idletimer

global $CFG;
$web  = get_plugin('webservice', $CFG->settings->webservice);
$obj2 = json_decode($web->core_course_get_contents(filter_input(INPUT_GET, 'dependency_id', FILTER_VALIDATE_INT)));
//error_log(json_encode($obj2));
foreach ($obj2 AS  $value) {
    foreach($value->modules AS $module){
        if (isset($module->id)){
            $modules[] = $module;
        }
    }
}

$html      = '';
foreach ($modules as $value) {
    if($value->modname == 'quiz'){ //todo make quiz as var
        $html  .=  '<option label="'.$value->name.'" value="'.$value->id.'"'; 
        if (filter_input(INPUT_GET, 'select_id', FILTER_VALIDATE_INT) == $value->id) { 
            $html  .= ' selected="selected"';    
        } 
        $html  .= '>'.$value->name.'</option>';
    }
}

echo json_encode(array('html'=>$html));