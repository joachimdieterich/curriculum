<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* FormProcessor
* @package core
* @filename fp_search.php
* @copyright 2016 Joachim Dieterich
* @author Joachim Dieterich
* @date 2016.07.11 10:39
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
include(dirname(__FILE__).'/../setup.php');  // Klassen, DB Zugriff und Funktionen
include(dirname(__FILE__).'/../login-check.php');  //check login status and reset idletimer

if (!isset($_SESSION['PAGE']->target_url)){     //if target_url is not set -> use last PAGE url
    $_SESSION['PAGE']->target_url       = $_SESSION['PAGE']->url;
}
switch ($_POST['func']) {
    case 'view_highlight':  $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);  
                            $search = filter_input(INPUT_POST, 'search', FILTER_UNSAFE_RAW);  
                            if (isset($search)){
                                $s          = new Search();
                                $s->id      = $id;
                                $s->search  = $search;
                                $result     = $s->view();                 
                                $highlight  = array();
                                foreach($result AS $r){
                                    if (isset($r->enabling_objective)){
                                        $highlight[] = 'ena_'.$r->id;
                                    } else {
                                        $highlight[] = 'ter_'.$r->id;
                                    } 
                                }
                                $_SESSION['highlight'] = $highlight;
                            }
        break;
    case 'view':            $_SESSION['FORM']            = new stdClass();
                            $_SESSION['FORM']->form      = 'search';
                            foreach($_POST as $key => $value){
                                $_SESSION['FORM']->$key  = $value;
                            } 
        break;

    default:
        break;
}

header('Location:'.$_SESSION['PAGE']->target_url);