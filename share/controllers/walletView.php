<?php
/** 
* This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename walletView.php
* @copyright  2013 Joachim Dieterich  {@link http://www.joachimdieterich.de}
* @date 2016.12.28 08:03
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

global $CFG, $USER, $PAGE, $TEMPLATE, $INSTITUTION;
$TEMPLATE->assign('breadcrumb',  array('Sammelmappe' => 'index.php?action=wallet', 'Ansicht' => 'index.php?action=walletView'));
$TEMPLATE->assign('page_title', 'Ansicht');  
$TEMPLATE->assign('sel_user_id', false); 
$wallet   = new Wallet(filter_input(INPUT_GET, 'wallet', FILTER_VALIDATE_INT));
if (isset($_GET['edit'])){ $TEMPLATE->assign('edit', true); } else { $TEMPLATE->assign('edit', false); }
if (isset($_GET['user_id'])){ 
    $sel_user_id    = $_GET['user_id'];
    $TEMPLATE->assign('sel_user_id', $sel_user_id); 
    $wallet->get('user', $sel_user_id);
} else { 
    $wallet->get('user', $USER->id);
}
/******************************************************************************
 * END POST / GET
 */

$course_user        = new User();
$course_user->id    = $USER->id;

//$TEMPLATE->assign('userlist', $course_user->getUsers('curriculum', 'walletPaginator', $wallet->curriculum_id));
$TEMPLATE->assign('userlist', $course_user->getUsers('wallet_shared', 'walletPaginator', $wallet->curriculum_id, null, $wallet->id));
//error_log(json_encode($wallet->comments));
$TEMPLATE->assign('wallet', $wallet); 
$TEMPLATE->assign('course', $wallet); 

$ena = new EnablingObjective;
$obj = array();
foreach ($wallet->objectives as $id) {
    $ena->id = $id;
    $ena->load();
    $obj[]   = clone $ena;
}

$TEMPLATE->assign('objectives', $obj); 
$TEMPLATE->assign('page_bg_file_id', $wallet->file_id); 
