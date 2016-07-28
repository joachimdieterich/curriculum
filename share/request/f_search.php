<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename f_search.php
* @copyright 2016 Joachim Dieterich
* @author Joachim Dieterich
* @date 2016.07.11 09:42
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
global $USER, $CFG;
$USER       = $_SESSION['USER'];
$error      = null; 
$search     = null; 
$id         = null; 
$func       = $_GET['func'];
$header     = 'Suchen';       
switch ($func) {
    case 'view':    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);  
        break;
    
    default:
        break;
}
/* if validation failed, get formdata from session*/
if (isset($_SESSION['FORM'])){
    if (is_object($_SESSION['FORM'])) {
        foreach ($_SESSION['FORM'] as $key => $value){
            $$key = $value;
        }
    }
}

$content = '<form id="form_search" method="post" action="../share/processors/fp_search.php">
 <div class="form-horizontal">
<input type="hidden" name="func" id="func" value="'.$func.'"/>
<input type="hidden" name="id" id="id" value="'.$id.'"/>'; 
$content .= Form::input_text('search', 'Suche', $search, $error, 'z. B. Medienkompetenz');
$content .= '</div></form>';

$f_content = '<button type="submit" class="btn btn-primary fa fa-search pull-right" onclick="document.getElementById(\'form_search\').submit();"> '.$header.'</button>';
/* get search result*/
if (isset($search)){
    $s          = new Search();
    $s->id      = $id;
    $s->search  = $search;
    $result     = $s->$func();   
    $content .= '<h4>Die Suche ergab folgende Treffer</h4>';
    switch ($func) {
        case 'view':    $highlight       = array();
                        foreach($result AS $r){
                            $content    .= '<strong>'.$r->enabling_objective.'</strong><hr>';
                            $highlight[] = 'ena_'.$r->id;
                        }
                        $_SESSION['highlight'] = $highlight;
                        $f_content = '<button class="btn btn-primary fa fa-search pull-right" onclick="closePopup();">Treffer im Lehrplan anzeigen</button>';
            break;

        default:    
            break;
    }
    $_SESSION['FORM'] = null;                                                   // reset Session Form object 
}
/* end search result */

$html     = Form::modal(array('title'     => $header,
                              'content'   => $content, 
                              'f_content' => $f_content));  

echo json_encode(array('html'=>$html));