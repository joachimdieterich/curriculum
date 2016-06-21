<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename f_block.php
* @copyright 2016 Joachim Dieterich
* @author Joachim Dieterich
* @date 2016.06.17 08:47
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
global $CFG, $USER;
$USER       = $_SESSION['USER'];
$func       = $_GET['func'];
$block      = new Block();
$types      = $block->types();
$name       = null;
$context_id = 11;
$region     = null;
$configdata = null;

$error      = null;
$object     = file_get_contents("php://input");
$data       = json_decode($object, true);
if (is_array($data)) {
    foreach ($data as $key => $value){
        $$key = $value;
    }
}
            
if (isset($func)){
    switch ($func) {
        case "new" : checkCapabilities('block:add', $USER->role_id);
                     $header            = 'Block hinzufügen';
                     if (null != filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT)){
                         $block_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
                     } else {
                         $block_id = $types[0]->id;
                     }    
                     $add              = true;   
            break;
        case "edit": checkCapabilities('block:update', $USER->role_id);
                     $header            = 'Block ändern';
                     $edit              = true;   
                     $block->id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
                     $block->load();
                     foreach ($block as $key => $value){
                         if (!is_object($value)){
                             $$key = $value;
                         }
                     }
            break;
        default: break;
    }
}

/* if validation failed, get formdata from session*/
if (isset($_SESSION['FORM'])){
    if (is_object($_SESSION['FORM'])) {
        foreach ($_SESSION['FORM'] as $key => $value){
            $$key = $value;
        }
    }
}

$content  ='<form id="form_block"  class="form-horizontal" role="form" method="post" action="../share/processors/fp_block.php"';

if (isset($currentUrlId)){ $content .= $currentUrlId; }
$content .= '"><input type="hidden" name="func" id="func" value="'.$func.'"/>';
if (isset($id)){
    $content .= '<input type="hidden" name="id" id="id" value="'.$id.'"/>';
}
$content .= Form::input_select('block_id', 'Blocktyp', $types, 'block', 'id', $block_id , $error, 'formloader(\'block\',\'new\', this.value);');

$content .= Form::input_text('name', 'Titel', $name, $error,'z.B. Links');
//get current type to render correct form elements
foreach($types as $typ) {
    if ($block_id == $typ->id) {
        $t = $typ;
        break;
    }
}
if ($t->block == 'moodle'){
    $content .= Form::input_text('moodle_block', 'Link zur Moodle Instanz', $configdata, $error,'http://example.de/moodle');
}
if ($t->block == 'html'){
    $content .= Form::input_textarea('html_block', 'HTML-Code', $configdata, $error);
}
$c            = new stdClass();
$c->id        = 11;
$c->context   = 'Dashboard';
$content .= Form::input_select('context_id', 'Bereich', array($c), 'context', 'id', $context_id , $error);
$r            = new stdClass();
$r->id        = null;
$r->region   = 'Übersicht';
$content .= Form::input_select('region', 'Position', array($r), 'region', 'id', $region , $error);
// sortierung neues input element generiern


$roles    = new Roles();
$content .= Form::input_select('role_id', 'Anzeigen für:', $roles->get(), 'role', 'id', $block_id , $error);


$content .= '</div></form>';
$f_content = '';
if (isset($edit)){
    $f_content .= '<button name="update" type="submit" class="btn btn-primary glyphicon glyphicon-saved pull-right" onclick="document.getElementById(\'form_block\').submit();"> '.$header.'</button>'; 
} 
if (isset($add)){
    $f_content .= '<button id="add" name="add" type="submit" class="btn btn-primary glyphicon glyphicon-ok pull-right" onclick="document.getElementById(\'form_block\').submit();"> '.$header.'</button> ';
}    
$html     = Form::modal(array('title'     => $header,
                              'content'   => $content, 
                              'f_content' => $f_content));

echo json_encode(array('html'=>$html));