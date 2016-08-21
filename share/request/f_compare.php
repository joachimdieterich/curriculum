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
include(dirname(__FILE__).'/../login-check.php');  //check login status and reset idletimer
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
    $content .= '<div class="col-md-12 "><p>Übersicht zum Lernziel:'.filter_input(INPUT_GET, 'group_id', FILTER_VALIDATE_INT).'<br><strong>'.$ena->enabling_objective.'</strong></p></div>'; 
    $order   = array('acc_0' => array('items'  => count($acc_0), 
                                'header' => 'Ziel nicht erreicht', 
                                'color'  => 'text-red', 
                                'class'  => 'box-danger',
                                'var'    => 'acc_0'),
                   'acc_1' => array('items'  => count($acc_1), 
                                'header' => 'Ziel erreicht', 
                                'color'  => 'text-green',
                                'class'  => 'box-success',
                                'var'    => 'acc_1'),
                   'acc_2' => array('items'  => count($acc_2), 
                                'header' => 'Ziel mit Hilfe erreicht', 
                                'color'  => 'text-orange',
                                'class'  => 'box-warning',
                                'var'    => 'acc_2'),
                   'acc_3' => array('items'  => count($acc_3), 
                                'header' => 'Ziel noch nicht bearbeitet', 
                                'color'  => false,
                                'class'  => 'box-default',
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
    $solutions = $files->getSolutions('objective', $user_id_list, $ena->id); 
    
    foreach ($order as $key => $value) {
        $content .= '<div class="col-md-6 "><div class="box '.$value['class'].' box-solid">
                        <div class="box-header with-border">
                        <div class="box-title">'.$value['header'].'</div>';
                        if ($value['color']){
                            $content .= '<span class="pull-right badge bg-white '.$value['color'].'">Datum, Lehrer</span>';
                        }
                        $content .= '</div>
                        <div class="box-footer no-padding">
                            <ul class="nav nav-stacked">';
                            if ($$value['var']){
                                foreach($$value['var'] AS $v) {
                                    $user     = new User();
                                    $content .= '<li><a href="#">'.$user->resolveUserId($v->user_id);
                                    
                                    foreach ( $solutions as $s ) { 
                                        if ( $v->user_id == $s->creator_id ) {
                                            $content .= '<span onClick=\'formloader("material", "id", '.$ena->id.', {"target":"sub_popup", "user_id": "'.$v->user_id.'"});\'>&nbsp;<i class="fa fa-paperclip"></i></span>';          
                                            break; // if one solution is found break to save time
                                        }
                                    }
                                    
                                    if ($value['color']){
                                        $content .= '<span class="pull-right badge bg-'.$value['color'].'" data-toggle="tooltip" title="" data-original-title="Nachricht schreiben" onclick="formloader(\'mail\', \'gethelp\', '.$v->creator_id.');">'.date('d.m.Y',strtotime($v->accomplished_time)).', '.$user->resolveUserId($v->creator_id, 'name').'</span>';
                                    }
                                    $content .= '</a></li>';
                                }   
                            }
        $content .= '</ul></div></div></div>'; 
    }
}
$html = Form::modal(array('sub_modal_id' => 'preview',
                          'title'   => 'Lernstand der Gruppe',
                          'content' => $content));

echo json_encode(array('html'=>$html));