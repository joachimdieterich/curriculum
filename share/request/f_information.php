<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
*
* @package core
* @filename f_information.php
* @copyright 2018 Fabian Werner
* @author Fabian Werner
* @date 2018.08.16 16:35:05
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
include($base_url.'setup.php');  //LÃ¤d Klassen, DB Zugriff und Funktionen
//include(dirname(__FILE__).'/../login-check.php');  //don't check login -> terms should be available without login
global $CFG, $TEMPLATE;

$footer      = '';
$data        = [];
$information = new Content();
$func        = filter_input(INPUT_GET, 'func', FILTER_UNSAFE_RAW);

#if ($func == "undefined") {
#    $func = "information";
#}

$title    = $information->get('information', $func)[0]->title;
$content  = $information->get('information', $func)[0]->content;

$html     = Form::modal(array('title'     => $title,
                              'content'   => $content,
                              'f_content' => $footer));
echo json_encode(array('html'   => $html));