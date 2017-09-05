<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename get_task.php
* @copyright 2017 Joachim Dieterich
* @author Joachim Dieterich
* @date 2017.08.20 18:17
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

$base_url = dirname(__FILE__).'/../';
include($base_url.'setup.php');  //Läd Klassen, DB Zugriff und Funktionen 
include(dirname(__FILE__).'/../login-check.php');  //check login status and reset idletimer
global $USER, $PAGE;
$USER      = $_SESSION['USER'];
$t         = new Task();
$t->load('id', filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT));
$_SESSION['PAGE']->show_reference_id = $t->id;
$content   =  '<div class="nav-tabs-custom"><div class="box-header">'
        . '<h3 class="box-title">'.$t->task.'</h3></div>'
        . '<div class="box-body"><span class="label label-primary pull-right">'.$t->timerange.'</span><br>'.$t->description.'<hr>';        


$f = new File();
$content .= RENDER::thumblist($f->getFiles('task', $t->id), '');

/* Button Fileupload */
$content .= '<div class="btn btn-default btn-file margin-r-5"><a href="'.$CFG->smarty_template_dir_url.'renderer/uploadframe.php?context=task&ref_id='.$t->id.$CFG->tb_param.'" class="nyroModal"><i class="fa fa-paperclip"></i> Anlage hizufügen</a></div>';
/* Notes / Content*/
$cnt      = new Content();
$content .='<div class="btn-group ">';
$content .= Render::split_button(array('label'=>'Notizen', 'btn_type'=> 'btn btn-default','entrys'=> $cnt->get('task', $t->id )));
if (checkCapabilities('content:add', $USER->role_id, false)){
    $content .='<button type="button" class="btn btn-default" onclick="formloader(\'content\', \'new\', null,{\'context_id\':\''.$_SESSION['CONTEXT']['task']->context_id.'\',\'reference_id\':\''.$t->id.'\'});">
                    <i class="fa fa-plus"></i>
                </button>';
}
$content .='</div>';

$content .= '<hr><small>Erstellt von <strong>'.$t->creator.'</strong><br>Erstellungsdatum: <strong>'.$t->creation_time.'</strong></small>';
$comments = new Comment();
$comments->context = 'task';
$comments->reference_id = $t->id;
$content .= '<hr><h4>Kommentare</h4>';
$content .=  RENDER::comments(["comments" => $comments->get('reference'), "permission" => 1]);
$content .= 'Neuen Kommentar hinzufügen
            <textarea id="comment" name="comment"  style="width:100%;"></textarea>
            <button type="submit" class="btn btn-primary pull-right" onclick="comment(\'new\','.$t->id.', 13, document.getElementById(\'comment\').value);">'
        . '<i class="fa fa-commenting-o margin-r-10"></i> Kommentar abschicken</button>';
$content .= '</div></div>';
echo $content;                 
