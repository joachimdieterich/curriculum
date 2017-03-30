<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename statistic.php
* @copyright 2016 Joachim Dieterich
* @author Joachim Dieterich
* @date 2016.11.01 17:00
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
global $PAGE, $USER, $TEMPLATE;
checkCapabilities('dashboard:globalAdmin', $USER->role_id);
$TEMPLATE->assign('breadcrumb',  array('Statistik' => 'index.php?action=statistic'));
$TEMPLATE->assign('page_title', 'Statistik');  

$chart      = 'institutions';
$dependency = '';
$TEMPLATE->assign('accomplished_status', '');
if (isset($_GET['chart'])){
    $chart  = $_GET['chart'];
}
$s     = array(
                array('id' => 0, 'status' => 'alle Einträge', 'db' => ''),
                array('id' => 1, 'status' => 'selbständig erreicht', 'db' => '"01","1","x1","11","21","31"'),
                array('id' => 2, 'status' => 'mit Hilfe erreicht', 'db' => '"02","2","x2","12","22","32"'),
                array('id' => 3, 'status' => 'nicht bearbeitet', 'db' => '"03","3","x3","13","23","33"'),
                array('id' => 4, 'status' => 'nicht erreicht', 'db' => '"04","4","x4","14","24","34"'),
              );



$status = new stdClass();
$s_array      = array();
foreach ($s as $value) {
    foreach ($value as $k => $v) {
        switch ($k) {
            case 'id':      $status->id       = $v;
                            if (isset($_GET['status'])){
                                if ($_GET['status'] == $v){
                                    $TEMPLATE->assign('accomplished_status', $v); 
                                    $dependency  = $value['db'];
                                }
                            }
                 break;
            case 'status':  $status->status   = $v;
                 break;
            case 'db':      $status->db       = $v;
                break;

            default:
                break;
        }
    }
    $s_array[] = clone $status;
}
$map        = new Statistic();
$TEMPLATE->assign('status', $s_array);  
$TEMPLATE->assign('chart', $chart);  
$TEMPLATE->assign('map', $map->map($chart,$dependency));  
