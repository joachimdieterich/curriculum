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

$content  ='<div class="nav-tabs-custom"> 
              <ul class="nav nav-tabs">
                <li class="active"><a href="#block_new" data-toggle="tab" aria-expanded="false">Neuer Block</a></li>
                <li class=""><a href="#block_visible" data-toggle="tab" aria-expanded="true" >Ausgeblendete Blöcke</a></li>
              </ul>
              <div class="tab-content">
                <div class="tab-pane active" id="block_new">
                <form id="form_block"  class="form-horizontal" role="form" method="post" action="../share/processors/fp_block.php"';

                if (isset($currentUrlId)){ $content .= $currentUrlId; }
                $content .= '"><input type="hidden" name="func" id="func" value="'.$func.'"/>';
                if (isset($id)){
                    $content .= '<input type="hidden" name="id" id="id" value="'.$id.'"/>';
                }
                $content .= Form::input_select('block_id', 'Blocktyp', $types, 'block', 'id', $block_id , $error, 'formloader(\'block\',\'new\', this.value);');

                $content .= Form::input_text('name', 'Titel', $name, $error,'z.B. Links');
                /*get current type to render correct form elements*/
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
                $content     .= Form::input_select('context_id', 'Bereich', array($c), 'context', 'id', $context_id , $error);
                $r            = new stdClass();
                $r->id        = null;
                $r->region    = 'Übersicht';
                $content     .= Form::input_select('region', 'Position', array($r), 'region', 'id', $region , $error);
                // Sortierung neues input element generiern

                $roles        = new Roles();
                $content     .= Form::input_select('role_id', 'Anzeigen für:', $roles->get(), 'role', 'id', $block_id , $error);
                $content     .= '<button type="submit" class="btn btn-primary pull-right" onclick="document.getElementById(\'form_block\').submit();"><i class="fa fa-floppy-o margin-r-5"></i>'.$header.'</button><br><br>';
                $content     .= '</form>  
                 </div><!-- /.tab-pane -->
                 
                <div class="tab-pane " id="block_visible">';
                $blocks                 = new Block();
                $ct = new Context();
                $ct->resolve('context', 'dashboard');
                $blocks->context_id     = $ct->id;
                $blocks->institution_id = $USER->institution_id;
                $dashboar_blocks        = $blocks->get();
                $removed_blocks       = array();
                foreach ($dashboar_blocks as $key => $value) {
                    if ($value->visible == 0){
                        $removed_blocks[] = $value;
                    }                    
                }
                $content     .= '<input type="hidden" name="set_visible_status" id="set_visible_status" value="true"/>';
                $content     .= Form::input_select('block_id_visible', 'Block', $removed_blocks, 'name', 'id', '' , $error);
                $content     .= '<button class="btn btn-primary pull-right" onclick="processor(\'config\',\'remove\', $( \'select#block_id_visible option:checked\' ).val());"><i class="fa fa-floppy-o margin-r-5"></i>Block einblenden</button><br><br></div><!-- /.tab-pane -->
                                </div><!-- /.tab-content -->
                              </div><!-- /.nav-tab-custom -->';

$html         = Form::modal(array('title'     => $header,
                                  'content'   => $content, 
                                  'f_content' => ''));

echo json_encode(array('html'=>$html));