<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename f_wallet_content.php
* @copyright 2016 Joachim Dieterich
* @author Joachim Dieterich
* @date 2016.12.30 12:00
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
global $CFG, $USER,$TEMPLATE;
$USER           = $_SESSION['USER'];
$func           = $_GET['func'];
/*Variablen anlegen -> vermeidet unnötige if-Abfragen im Formular*/
$id             = null; 
$title          = null; 
$width_class    = null;
$position       = null;
$row_id         = null; 
$order_id       = null; 
$html           = null;
$file_id        = $CFG->settings->standard_avatar_id;
$error          = null;
$object         = file_get_contents("php://input");
$data           = json_decode($object, true);

if (is_array($data)) {
    extract($data);
}
checkCapabilities('wallet:workon',    $USER->role_id, false, true);            
if (isset($_GET['func'])){
    switch ($_GET['func']) {
        case "new_file":    $header     = 'Datei hinzufügen'; 
                            $type       =  'file';
                            $walletc    = new WalletContent();
                            $wc         = $walletc->get('wallet_id', filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT));
                            $wallet_id  = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
                            $row_id     = filter_input(INPUT_GET, 'row_id', FILTER_VALIDATE_INT);
                            $order_id   = filter_input(INPUT_GET, 'order_id', FILTER_VALIDATE_INT);             
            break;
        case "new_content": $header     = 'Text hinzufügen'; 
                            $type       = 'html';
                            $walletc    = new WalletContent();
                            $wc         = $walletc->get('wallet_id', filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT));
                            $wallet_id  = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
                            $row_id     = filter_input(INPUT_GET, 'row_id', FILTER_VALIDATE_INT);
                            $order_id   = filter_input(INPUT_GET, 'order_id', FILTER_VALIDATE_INT);
                            $context_id = $_SESSION['CONTEXT']['content']->context_id;      
            break;
        case "edit":        $header     = 'Medium bearbeiten';
                            $edit       = true; 
                            $walletc    = new WalletContent(filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT));
                            
                            extract($walletc);
                            switch ($context) {
                                case 'content':     $type = 'html';
                                                    $co    = new Content();
                                                    $co->load('id', $reference_id);
                                                    $html = $co->content;
                                    break;

                                default:            $type =  'file';
                                                    $file_id = $reference_id; 
                                    break;
                            }
                            
            break;
        default: break;
    }
}

/* if validation failed, get formdata from session*/
if (isset($_SESSION['FORM'])){
    if (is_object($_SESSION['FORM'])) {
        extract($_SESSION['FORM']);
    }
}

$content ='<form id="form_wallet_content" class="form-horizontal" role="form" method="post" action="../share/processors/fp_wallet_content.php">'
        . '<input type="hidden" name="func" id="func" value="'.$func.'"/>';
if (isset($id)) {                                                               // only set id input field if set! prevents error on validation form reload
     $content .= '<input id="id" name="id" type="text" class="invisible" value="'.$id.'">';
}
$content .= '<input id="type" name="type" type="text" class="invisible" value="'.$type.'">';
$content .= '<input id="row_id" name="row_id" type="text" class="invisible" value="'.$row_id.'">';
$content .= '<input id="order_id" name="order_id" type="text" class="invisible" value="'.$order_id.'">';
$content .= '<input id="wallet_id" name="wallet_id" type="text" class="invisible" value="'.$wallet_id.'">';
$content .= Form::input_text('title', 'Titel', $title, $error, 'Titel');
/* Width selector*/
$width_classes = array('Seitenbreite' => 'col-xs-12',
                       '3/4 Seite' => 'col-xs-9',
                       '2/3 Seite' => 'col-xs-8',
                       '1/2 Seite' => 'col-xs-6',
                       '1/3 Seite' => 'col-xs-4',
                       '1/4 Seite' => 'col-xs-3'
                      );
$width_obj = new stdClass();
foreach ($width_classes as $key => $value) {
    $width_obj->label       = $key;
    $width_obj->width_class = $value;
    $w[] = clone $width_obj;
}
$content .= Form::input_select('width_class', 'Blockgröße (Breite)', $w, 'label', 'width_class', $width_class , $error);

/* Reihe /orderID*/
$order_obj = new stdClass();
foreach ($w as $key => $value) {
    if ($key == 'row_id'){
        $width_obj->label       = $key;
        $width_obj->width_class = $value;
        $w[] = clone $width_obj;
    }
}

$position_classes = array('normal' => ' ',
                       'links' => 'pull-left',
                       'rechts' => 'pull-right'
                      );
$pos_obj = new stdClass();
foreach ($position_classes as $key => $value) {
    $pos_obj->label       = $key;
    $pos_obj->position = $value;
    $o[] = clone $pos_obj;
}
$content .= Form::input_select('position', 'Position', $o, 'label', 'position', $position , $error);

switch ($type) {
    case 'file':    $content .= '<input type="hidden" name="file_id" id="file_id" value="'.$file_id.'"/>';//id have to be set to add image
                    $content .= '<div class="col-xs-3"></div><div class="col-xs-9">'
                                . '<a href="'.$CFG->smarty_template_dir_url.'renderer/uploadframe.php?context=userFiles&target=file_id&ref_id='.$id.'&format=0&modal=true" class="nyroModal">';
                                if ($id != null) {
                                    $content .= '<img id="icon" style="height:100px; margin-left: -5px; padding-bottom:10px;" src="'.$CFG->access_id_url.$file_id.'" >';
                                } else {
                                    $content .= '<img id="icon" style="height:100px; margin-left: -5px; padding-bottom:10px;" src="'.$CFG->access_id_url.$file_id.'" >
                                                 <i class="fa fa-plus"></i> Bild hinzufügen';
                                }
                    $content .= '</a></div>';
        break;
    case 'html':    $content .= '<input type="hidden" name="context_id" id="context_id" value="'.$context_id.'"/>';
                    if (isset ($reference_id)){
                        $content .= '<input type="hidden" name="content_id" id="content_id" value="'.$reference_id.'"/>'; 
                    }
                    $content .= Form::input_textarea('html', 'Text', $html, $error);

    default:
        break;
}



$content .= '</form>';
$footer   = '<button type="submit" class="btn btn-primary pull-right" onclick="document.getElementById(\'form_wallet_content\').submit();"><i class="fa fa-check margin-r-5"></i>'.$header.'</button>'; 

$html     = Form::modal(array('title'     => $header,
                              'content'   => $content, 
                              'f_content' => $footer));

$script = '<script id=\'modal_script\'>
        $(function() {
            $(\'.nyroModal\').nyroModal({
            callbacks: {
                beforeShowBg: function(){
                    $(\'body\').css(\'overflow\', \'hidden\');
                       
                },
                afterHideBg: function(){
                    $(\'body\').css(\'overflow\', \'\');
                 
                },
                afterShowCont: function(nm) {
                    $(\'.scroll_list\').height($(\'.modal\').height()-150);
                }   
            }
        });
            $(\'#popup_generate\').nyroModal();
        });
        $(\'#file_id\').change(\'input\', function() {
            document.getElementById("icon").src = "'.$CFG->access_id_url.'"+document.getElementById("file_id").value;
        });
        </script>';
echo json_encode(array('html' => $html, 'script' => $script));