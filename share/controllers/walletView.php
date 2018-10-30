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
$TEMPLATE->assign('page_title', 'Ansicht');  
$TEMPLATE->assign('sel_user_id', false); 
$wallet   = new Wallet(filter_input(INPUT_GET, 'wallet', FILTER_VALIDATE_INT));
if (isset($_GET['edit'])){ $TEMPLATE->assign('edit', true); } else { $TEMPLATE->assign('edit', false); }
if (isset($_GET['user_id']) AND $_GET['user_id'] != 'false'){
    $sel_user_id    = $_GET['user_id'];
    $TEMPLATE->assign('sel_user_id', $sel_user_id); 
    $wallet->get('user', $sel_user_id);
    $TEMPLATE->assign('show_wallet_text', 'meine Sammelmappe');
} else { 
    $wallet->get('user', $USER->id);
    $TEMPLATE->assign('show_wallet_text', 'Sammelmappe von Kursteilnehmer wÃ¤hlen...');
}
$TEMPLATE->assign('breadcrumb',  array('Sammelmappe' => 'index.php?action=wallet', 'Ansicht' => 'index.php?action=walletView&wallet='.$wallet->id));
/******************************************************************************
 * END POST / GET
 */

$course_user        = new User();
$course_user->id    = $USER->id;
//$TEMPLATE->assign('userlist', $course_user->getUsers('curriculum', 'walletPaginator', $wallet->curriculum_id));
if ($wallet->creator_id == $USER->id) {
    $users = $course_user->getUsers('wallet_shared', 'walletuserPaginator', $wallet->curriculum_id, null, $wallet->id);
} else{ // SchÃ¼leransicht
    $user->id           = $USER->id;
    $user->firstname    = "meine";
    $user->lastname     = "Sammelmappe";
    $users[]            = clone $user;

        $user->id           = $wallet->creator_id;
        $user->firstname    = "gestellte";
        $user->lastname     = "Sammelmappe";
        $users[]            = clone $user;
        $TEMPLATE->assign('show_wallet_text', 'Sammelmappe wechseln');
    if (!(isset($_GET['user_id']) AND $_GET['user_id'] != 'false') AND empty($wallet->content)) { // noch keine Daten vom SchÃ¼ler 
        $wallet->get('user', $wallet->creator_id);
        $TEMPLATE->assign('sel_user_id', $wallet->creator_id);
    }
}
$TEMPLATE->assign('userlist', $users);
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


if ($users) {
    $p_options = array('delete'    => array('onclick'    => "del('wallet_sharing',{$wallet->id},__id__);",
                                            'capability' => checkCapabilities('wallet:share', $USER->role_id, false),
                                            'icon'       => 'fa fa-trash',
                                            'tooltip'    => 'lÃ¶schen'),
                       'mailnew'   => array('onclick'    => 'formloader(\'mail\', \'new-to\', __id__);',
                                            'capability' => checkCapabilities('mail:postMail', $USER->role_id, false),
                                            'icon'       => 'fa fa-envelope',
                                            'tooltip'    => 'Nachricht schreiben'));
    $t_config  = array('table_id'  => array('id'         => 'contentsmalltable'),
                       'td'        => array('onclick'    => "location.href='index.php?action=walletView&wallet={$wallet->id}&user_id=__id__';"));
    $p_config  = array('id'        => 'no-checkrow',
                       'username'  => 'Benutzername',
                       'firstname' => 'Vorname',
                       'lastname'  => 'Nachname',
                       'permission'=> 'Freigabe',
                       'timerange' => 'Freigabezeitraum',
                      /* 'completed' => 'Fortschritt',
                       'role_name' => 'Rolle',*/
                       'p_search'  => array('username', 'firstname', 'lastname'),
                       'p_options' => $p_options,
                       't_config'  => $t_config);
    setPaginator('walletuserPaginator', $TEMPLATE, $users, 'results', 'index.php?action=walletView&wallet='.$wallet->id, $p_config); //set Paginator 
}
