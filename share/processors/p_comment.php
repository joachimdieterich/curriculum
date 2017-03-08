<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename p_comment.php
* @copyright 2017 Joachim Dieterich
* @author Joachim Dieterich
* @date 2017.01.24 16:07
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
global $USER;

$USER       = $_SESSION['USER'];
$func       = $_GET['func'];

switch ($func) {
    case 'new':     $cm = new Comment();
                    $cm->text           = filter_input(INPUT_GET, 'text',        FILTER_SANITIZE_STRING);
                    $cm->reference_id   = filter_input(INPUT_GET, 'ref_id',         FILTER_VALIDATE_INT);
                    $cm->context_id     = filter_input(INPUT_GET, 'context_id',  FILTER_VALIDATE_INT);
                    if (isset($_GET['parent_id'])){
                        $cm->parent_id  = filter_input(INPUT_GET, 'parent_id',  FILTER_VALIDATE_INT);
                    } else {
                        $cm->parent_id  = null;
                    }
                    $cm->add();
    break;
    case 'update':  
        break;
    default:
        break;
}