<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename f_wallet_sharing.php
* @copyright 2017 Joachim Dieterich
* @author Joachim Dieterich
* @date 2017.01.20 11:05
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
global $CFG, $USER,$TEMPLATE;
$USER           = $_SESSION['USER'];
$func           = $_GET['func'];
/*Variablen anlegen -> vermeidet unnötige if-Abfragen im Formular*/
$id             = null; 
$user_list      = null; 
$permission     = null; 
$timerange      = null; 
$error          = null;
$object         = file_get_contents("php://input");
$data           = json_decode($object, true);
if (is_array($data)) {
    foreach ($data as $key => $value){
        $$key = $value;
    }
}
            
if (isset($_GET['func'])){
    switch ($_GET['func']) {
        case "new":
        case "edit":    checkCapabilities('wallet:share',    $USER->role_id, false, true);
                        $header = 'Sammelmappe teilen';       
                        $wallet = new Wallet();
                        $wallet->load(filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT));
                        $id     = $wallet->id;
            break;

        default: break;
    }
}

/* if validation failed, get formdata from session*/
if (isset($_SESSION['FORM'])){
    if (is_object($_SESSION['FORM'])) {
        foreach ($_SESSION['FORM'] as $key => $value){
            $$key = $value;
        }
    }
}

$content ='<form id="form_wallet_sharing" class="form-horizontal" role="form" method="post" action="../share/processors/fp_wallet_sharing.php">'
        . '<input type="hidden" name="func" id="func" value="'.$func.'"/>';
if (isset($id)) {        // only set id input field if set! prevents error on validation form reload
     $content .= '<input id="id" name="id" type="text" class="invisible" value="'.$id.'">';
}
$content .= '<p><strong>'.$wallet->title.'</strong> teilen: <br></p>';
$content .= '<div class="nav-tabs-custom"> 
                <ul class="nav nav-tabs">
                    <li id="nav_tab_user" class="active"><a href="#tab_user" data-toggle="tab" aria-expanded="false" >Personen</a></li>
                    <li id="nav_tab_groups" class=""><a href="#tab_groups" data-toggle="tab" aria-expanded="true" >Gruppen</a></li>
                    <li id="nav_tab_institutions" class=""><a href="#tab_institutions" data-toggle="tab" aria-expanded="true" >Institutionen</a></li>
                </ul>
            </div>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_user">';
    $content .= Form::input_select_multiple(array('id' => 'user_list', 'label' => 'Schülerauswahl', 'select_data' => $USER->getGroupMembers('my_groups'), 'select_label' => 'firstname, lastname', 'select_value' => 'id', 'input' => $user_list, 'error' => $error));
    $permissions = array('lesezugriff' => '0',
                         'kommentierbar' => '1',
                         'schreibzugriff' => '2' );
    $permission_obj = new stdClass();
    foreach ($permissions as $key => $value) {
        $permission_obj->label       = $key;
        $permission_obj->position = $value;
        $o[] = clone $permission_obj;
    }
    $content .= Form::input_select('permission', 'Freigabe', $o, 'label', 'position', $permission , $error);
    $content .= Form::input_date(array('id'=>'timerange', 'label' => 'Freigabe-Zeitraum' , 'time' => $timerange, 'error' => $error, 'placeholder' => '', $type = 'date'));

    $course_user        = new User();
    $course_user->id    = $USER->id;
    $userlist = $course_user->getUsers('wallet_shared', 'walletPaginator', $wallet->curriculum_id, null, $wallet->id);
    $content   .= Render::box_widget(array('widget_title' => 'Geteilt',
                                       'class_width'  => 'col-sm-12',
                                       'bg_color'     => 'blue',
                                       'data'         => $userlist,
                                       'label'        => 'firstname, lastname', 
                                       'widget_desc'  => 'Freigaben an Personen',
                                       'bg_icon'      => 'fa fa-user',
                                       'bg_badge'     => 'bg-gray ',
                                       'badge'        => 'permission',
                                       /*'badge_title'  => 'freigabe ',*/));
    
    $content .='</div><!-- /.tab-pane -->
                <div class="tab-pane" id="tab_groups">not implemented yet
                </div><!-- /.tab-pane -->
                <div class="tab-pane" id="tab_institutions">not implemented yet
                </div><!-- /.tab-pane -->
            </div>';
$content .= '</form>';
$footer   = '<button type="submit" class="btn btn-primary pull-right" onclick="document.getElementById(\'form_wallet_sharing\').submit();"><i class="fa fa-floppy-o margin-r-5"></i>'.$header.'</button>'; 

$html     = Form::modal(array('title'     => $header,
                              'content'   => $content, 
                              'f_content' => $footer));

$script = '<script id=\'modal_script\'>
        $.getScript(\''.$CFG->smarty_template_dir_url.'plugins/daterangepicker/daterangepicker.js\', function (){
        $(\'.datepicker\').daterangepicker({timePicker: true, timePickerIncrement: 1, timePicker24Hour: true, locale: {format: \'DD.MM.YYYY HH:mm\'}});
        });
        </script>';
echo json_encode(array('html' => $html, 'script' => $script));