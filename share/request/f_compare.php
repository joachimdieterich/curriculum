<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename f_compare.php
* @copyright 2015 Joachim Dieterich
* @author Joachim Dieterich
* @date 2015.05.31 19:51
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
$USER       = $_SESSION['USER'];
$func       = $_GET['func'];

switch ($func) {
    case 'group':   $ena      = new EnablingObjective();
                    $ena->id  = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
                    $ena->load();
                    $acc_0    = $ena->getAccomplishedUsers(filter_input(INPUT_GET, 'group_id', FILTER_VALIDATE_INT), 0);                 
                    $acc_1    = $ena->getAccomplishedUsers(filter_input(INPUT_GET, 'group_id', FILTER_VALIDATE_INT), 1);                 
                    $acc_2    = $ena->getAccomplishedUsers(filter_input(INPUT_GET, 'group_id', FILTER_VALIDATE_INT), 2);                 
                    $acc_3    = $ena->getAccomplishedUsers(filter_input(INPUT_GET, 'group_id', FILTER_VALIDATE_INT), 3);                         
        break;
    
    default:
        break;
}

$content    = '';
if (isset($ena->enabling_objective)){  
    $content .= '<div class="col-md-12 "><p>Übersicht zum Lernziel:<br><strong>'.$ena->enabling_objective.'</strong></p></div>'; 
    $order   = array('acc_0' => array('items'  => count($acc_0), 
                                'header' => 'Ziel nicht erreicht', 
                                'color'  => 'text-red', 
                                'class'  => 'danger',
                                'var'    => 'acc_0'),
                   'acc_1' => array('items'  => count($acc_1), 
                                'header' => 'Ziel erreicht', 
                                'color'  => 'text-green',
                                'class'  => 'success',
                                'var'    => 'acc_1'),
                   'acc_2' => array('items'  => count($acc_2), 
                                'header' => 'Ziel mit Hilfe erreicht', 
                                'color'  => 'text-orange',
                                'class'  => 'warning',
                                'var'    => 'acc_2'),
                   'acc_3' => array('items'  => count($acc_3), 
                                'header' => 'Ziel noch nicht bearbeitet', 
                                'color'  => false,
                                'class'  => 'default',
                                'var'    => 'acc_3'),
                    );
    rsort($order);
    
    //User-Solutions laden
    $user_id_list = array();
    foreach ($order as $key => $value) {
        if (is_array($$value['var'])){
            foreach($$value['var'] AS $v) {
                $user_id_list[] = $v->user_id;
            }
        }
    }
    
    $files     = new File(); 
    $content  .= Render::compare_list(array('order'     => $order,
                                            'solutions' => $files->getSolutions('objective', $user_id_list, $ena->id),
                                            'acc_0'     => $acc_0,
                                            'acc_1'     => $acc_1,
                                            'acc_2'     => $acc_2,
                                            'acc_3'     => $acc_3,
                                            )
                                     );
    
}
$html = Form::modal(array('target' => 'null',
                          'sub_modal_id' => 'preview',
                          'title'   => 'Lernstand der Gruppe',
                          'content' => $content));

echo json_encode(array('html'=>$html));