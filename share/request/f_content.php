<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename f_content.php
* @copyright 2016 Joachim Dieterich
* @author Joachim Dieterich
* @date 2016.11.17 12:48
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
include_once(dirname(__FILE__).'/../login-check.php');  //check login status and reset idletimer
global $CFG, $USER, $COURSE;
$USER        = $_SESSION['USER'];

$ct          = new Content();
/*Variablen anlegen -> vermeidet unnötige if-Abfragen im Formular*/
$id          = null;
$title       = null; 
$content     = null;
$context     = null;
$timecreated = null;
$creator_id  = null;
$label_title     = isset($_GET['label_title']) ? $_GET['label_title'] : 'Titel';
$label_content   = isset($_GET['label_content']) ? $_GET['label_content'] : 'Beschreibung';
$label_header    = isset($_GET['label_header']) ? $_GET['label_header'] : 'Inhalt hinzufügen';
$label_save      = isset($_GET['label_save']) ? $_GET['label_save'] : 'Inhalt speichern';
  
$footer      = '';
$options     = '';
$func        = $_GET['func'];
$error       =   null;
$object      = file_get_contents("php://input");
$data        = json_decode($object, true);
if (is_array($data)) {
    foreach ($data as $key => $value){
        $$key = $value;   
    }
}
            

if (isset($_GET['func'])){
    switch ($_GET['func']) {
        case "new":     checkCapabilities('content:add',    $USER->role_id, false, true);
                        if (null !== filter_input(INPUT_GET, 'context', FILTER_SANITIZE_STRING)){
                            $context_id     = $_SESSION['CONTEXT'][filter_input(INPUT_GET, 'context', FILTER_SANITIZE_STRING)]->context_id;
                        } else {
                            $context_id     = filter_input(INPUT_GET, 'context_id', FILTER_VALIDATE_INT);
                        }
                        $reference_id   = filter_input(INPUT_GET, 'reference_id', FILTER_VALIDATE_INT);
                        $add = true;              
            break;
        case "edit":    checkCapabilities('content:update', $USER->role_id, false, true);
                        $label_header   = 'Inhalt aktualisieren';
                        $edit           = true; 
                        $ct             = new Content();
                        $ct->load('id', filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT));
                        foreach ($ct AS $key => $value){
                            if (!is_object($value)){
                                $$key = $value;
                            }
                        }
            break;
        case "show":    $content        = new Content();
                        $content->load('id', filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT));
                        if (checkCapabilities('content:delete', $USER->role_id, false, true)){
                            if (isset($_SESSION['PAGE']->curriculum)){
                                $ref_id = ', '.$_SESSION['PAGE']->curriculum;
                            }
                            $options   .= '<a onclick="del(\'content\','.$content->id.''.$ref_id.');" class="btn btn-default btn-xs pull-right" style="margin-right:5px;"><i class="fa fa-trash"></i></a>';
                        }
                        $label_header   = $content->title;
                        if (checkCapabilities('content:update', $USER->role_id, false, true)){
                            $label_header   = $content->title.'<small> (ID:'.$content->id.')</small>' ; //show id 
                            $options   .= '<a onclick="formloader(\'content\', \'edit\','.$content->id.')" class="btn btn-default btn-xs pull-right" style="margin-right:5px;"><i class="fa fa-edit"></i></a>';
                        }
                        
                        /*$html           = '';
                        $ter     = new TerminalObjective();
                        $ter->id = 325;
                        $ter->load();
                        $obj .= '<div class="col-xs-1 col-sm-3 pull-right"><dt>Thema/Kompetenzbereich</dt>'.Render::objective(array('format' => 'reference', 'objective' => $ter, 'color')).'</div>';
                        $html          .= '<div class="col-xs-11 col-sm-9">'.str_replace('<quote', '<div class="col-xs-1 col-sm-3 pull-right box" style="position: relative;left:10px;height:10px;width:50px;">'.$obj.'</div><quote ', $content->content).'</div>';
                        *///$html          .= '<div class="col-xs-1 col-sm-3"></div>';
                        $html           = $content->content;
                        
            break;
        default: break;
    }
}

if ($_GET['func'] != "show"){
    /* if validation failed, get formdata from session*/
    if (isset($_SESSION['FORM'])){
        if (is_object($_SESSION['FORM'])) {
            foreach ($_SESSION['FORM'] as $key => $value){
                $$key = $value;
            }
        }
    }

    $html    ='<form id="form_content"  class="form-horizontal" role="form" method="post" action="../share/processors/fp_content.php"';
    if (isset($currentUrlId)){ $html .= $currentUrlId; }
    $html   .= '">
    <input id="func" name="func" type="hidden" value="'.$func.'"/>
    <input id="context_id" type="hidden" name="context_id" value="'.$context_id.'"/>
    <input id="reference_id" type="hidden" name="reference_id" value="'.$reference_id.'"/>
    <input id="id" name="id" type="text" class="invisible" ';
    if (isset($id)) { $html .= 'value="'.$id.'"';} $html .= '>';
    $html   .= Form::input_text('title', $label_title, $title, $error, 'Titel');
    $html   .= Form::input_textarea('content', $label_content, $content, $error, 'Beschreibung');
    $c       = new Context();
    $html   .=  Form::input_select('file_context', 'Freigabe-Level', $c->get(), 'description', 'id', $context , $error);
    $html   .= '</form>';
    $footer .= '<button type="submit" class="btn btn-primary pull-right" onclick="document.getElementById(\'form_content\').submit();"><i class="fa fa-floppy-o margin-r-5"></i>'.$label_save.'</button>';
}

$html_form   = Form::modal(array('target'    => 'null',
                                   'title'     => $label_header.$options,
                                   'content'   => $html, 
                                   'f_content' => $footer));

echo json_encode(array('html'=>$html_form));