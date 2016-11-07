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
if (isset($_GET['chart'])){
    $chart  = $_GET['chart'];
}
$map        = new Statistic();
$TEMPLATE->assign('chart', $chart);  
$TEMPLATE->assign('map', $map->map($chart));  
