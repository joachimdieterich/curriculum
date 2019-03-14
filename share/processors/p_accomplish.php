<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename p_accomplish.php
* @copyright 2016 Joachim Dieterich
* @author Joachim Dieterich
* @date 2016.07.15 20:15
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
global $USER;
$USER   = $_SESSION['USER'];
$func   = filter_input(INPUT_GET, 'func', FILTER_SANITIZE_STRING);

switch ($func) {
    case "task":    $t      = new Task();        
                    $t->id  = filter_input(INPUT_GET, 'val', FILTER_SANITIZE_STRING); // kein INT --> System ID -1
                    $t->accomplish('user', $USER->id); 
        break;
    case "enabling_objective":  $acc               = new Accomplish();
                                $acc->context_id   = $_SESSION['CONTEXT'][$func]->context_id;
                                $acc->reference_id = filter_input(INPUT_GET, 'val', FILTER_SANITIZE_STRING); // kein INT
                                $acc->creator_id   = $USER->id;
                                $acc->status_id    = filter_input(INPUT_GET, 'status_id', FILTER_SANITIZE_STRING); 
                                switch ($_SESSION['PAGE']->action) {
                                    case 'view':        $selected_user_id = array($USER->id); //Selbsteinschätzung (auch für Lehrer!)
                                        break;
                                    case 'objectives':  $selected_user_id = SmartyPaginate::_getSelection('userPaginator'); //Fremdeinschätzung.
                                        break;

                                    default:
                                        break;
                                }
                                foreach ($selected_user_id as $user) {
                                    $acc->user_id  = $user;
                                    if ($acc->user_id == $USER->id){
                                        $teacher   = false;  // Selbsteinschätzung
                                    } else {
                                        $teacher   = true;   // Fremdeinschätzung
                                    }
                                    $status = $acc->set($teacher);
                                }
                                echo json_encode(array('element_id'=> filter_input(INPUT_GET, 'element_id', FILTER_SANITIZE_STRING), 'status'=>$status));
                                
                                
        break;
                            
    default: break;
}