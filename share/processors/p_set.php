<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename p_set.php
* @copyright 2017 Joachim Dieterich
* @author Joachim Dieterich
* @date 2017.02.01 09:23
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
include_once(dirname(__FILE__).'/../setup.php');  // Klassen, DB Zugriff und Funktionen
include_once(dirname(__FILE__).'/../login-check.php');  //check login status and reset idletimer
global $USER,$CFG, $PAGE;
$USER   = $_SESSION['USER'];
$func   = filter_input(INPUT_GET, 'func',           FILTER_SANITIZE_STRING);
$object = file_get_contents("php://input");

switch ($func) { //$func == db table name
    case "likes":           $id             = filter_input(INPUT_GET, 'val', FILTER_VALIDATE_INT);
                            $c              = new Comment($id);   
                            $dependency     = filter_input(INPUT_GET, 'dependency', FILTER_SANITIZE_STRING);
                            $c->$dependency = intval($c->$dependency)+1;
                            $c->set($dependency, $id);
                            echo json_encode(array('id'=> $dependency.'_'.$id, 'html'=>$c->$dependency));
        break;
    case "parentalAuthority": $u              = new User();   
                              $u->unsetChildren(filter_input(INPUT_GET, 'val', FILTER_VALIDATE_INT), filter_input(INPUT_GET, 'child_id', FILTER_VALIDATE_INT));
        break;
    case "ajaxsubmit":      
                            switch ($_POST['table']) {
                                case 'config_plugins':  $c = new Config();
                                                        error_log($c->set_config_plugin($_POST['params']['plugin'], $_POST['params']['name'], $_POST['value']));

                                    break;

                                default:
                                    break;
                            }
        break; 
  
    default: break;
}
