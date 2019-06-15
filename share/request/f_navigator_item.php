<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename f_navigator_item.php
* @copyright 2018 Joachim Dieterich
* @author Joachim Dieterich
* @date 2018.05.30 13:57:00
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
global $USER, $CFG;
$USER                 = $_SESSION['USER'];
$nb_id                = '';
$nb_title             = '';
$nb_description       = '';
$nb_navigator_view_id = '';
$nb_context_id        = '';
$nb_reference_id      = '';
$nb_position          = 'content';
$nb_width_class       = 'col-xs-12';
$nb_target_context_id = '';
$nb_target_id         = '';
$nb_file_id           = '';
$nb_visible           = 1;

$error                = '';
$navigator            = new Navigator_item(); 
$func                 = $_GET['func'];

switch ($func) {
    case 'edit':    $navigator->load('navigator_block', filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT));
                    $header                       = 'Navigations-Element aktualisieren'; 
                    extract($navigator);
        break;
    case 'new':     $navigator->nb_id           = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT); // new case: id == ter_id
                    $header                       = 'Navigations-Element hinzufügen';
        break;
    
    default:
        break;
}

/* if validation failed, get formdata from session*/
if (isset($_SESSION['FORM'])){
    if (is_object($_SESSION['FORM'])) {
        extract($_SESSION['FORM']);
    }
}

$content = '<form id="form_navigator_item" method="post" action="../share/processors/fp_navigator_item.php">
<input type="hidden" name="nb_navigator_view_id" id="nb_navigator_view_id" value="'.$nb_navigator_view_id.'"/>
<input type="hidden" name="func" id="func" value="'.$func.'"/>'; 
if (isset($nb_id)) {     // only set id input field if set! prevents error on validation form reload
     $content .= '<input id="nb_id" name="nb_id" type="text" class="invisible" value="'.$nb_id.'">';
}
if (isset($nb_navigator_view_id)) {     // only set id input field if set! prevents error on validation form reload
     $content .= '<input id="nb_navigator_view_id" name="nb_navigator_view_id" type="text" class="invisible" value="'.$nb_navigator_view_id.'">';
}
/* Type selector*/
$t = generate_select_object( array('Lehrplan'                   => '2',
                                   'Text'                       => '15',
                                   'Navigationsblock'           => '31',
                                   'Lehrpläne einer Lerngruppe' => '16'));
//error_log('context'.$nb_context_id);
$content .= Form::input_select('nb_context_id', 'Navigations-Typ', $t, 'label', 'value', $nb_context_id , $error);

$content .= Form::input_text('nb_title', 'Titel', $nb_title, $error, 'Titel');
$content .= Form::input_textarea('nb_description', 'Beschreibung', $nb_description, $error, 'z.B. ...');
/* Width selector*/
$p = generate_select_object(array('Oben (Top)'   => 'top',
                                'Mitte(Content)' => 'content',
                                'Unten (Footer)' => 'footer'));
$content .= Form::input_select('nb_position', 'Position', $p, 'label', 'value', $nb_position , $error);
/* Width input*/
$content .= Form::input_text('nb_width_class', 'Blockgröße (Breite)', $nb_width_class, $error);

switch ($nb_context_id) {
    case '2':   /* curriculum */
                $curriculum = new Curriculum();
                $content .= Form::input_select('nb_reference_id', 'Lehrplan', $curriculum->getCurricula('user', $USER->id, 'curriculumP'), 'curriculum', 'id', $nb_reference_id , $error);
        break;

    default:
        break;
}
/* Visible selector*/
$v = generate_select_object(array('Veröffentlichen' => '1',
                                  'Verbergen'       => '0'));
$content .= Form::input_select('nb_visible', 'Sichtbarkeit', $v, 'label', 'value', $nb_visible , $error);


$content .= '</form>';
$footer   = '<button type="submit" class="btn btn-primary pull-right" onclick="document.getElementById(\'form_navigator_item\').submit();"><i class="fa fa-floppy-o margin-r-5"></i>'.$header.'</button>';

$html     = Form::modal(array('title'     => $header,
                              'content'   => $content, 
                              'f_content' => $footer));  
echo json_encode(array('html'=> $html));