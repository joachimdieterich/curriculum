<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename navigator.php
* @copyright 2018 Joachim Dieterich
* @author Joachim Dieterich
* @date 2018.02.07 10:39
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
$search             = false;
$navigator          = new Navigator_item();
$navigator->getNavigatorByInstitution($USER->institution_id);
$allowed_navigator  = $navigator->na_id;
$navigator->getFirstView($navigator->na_id);
$navigator_view     = $navigator->nv_id;                            //load navigator_id from db 
if (isset($_POST) ){
    if (isset($_POST['search'])){
        $search = filter_input(INPUT_POST, 'search', FILTER_SANITIZE_STRING);
        $TEMPLATE->assign('navigator_reset', true); 
    }   
}
if (isset($_GET)){
    if (isset($_GET['nv_id'])){
        $navigator_view = $_GET['nv_id'];
    }  elseif (checkCapabilities('navigator:add', $USER->role_id, false)) {
        $p_options = array(/*'delete' => array('onclick'    => "", 
                                            'capability' => checkCapabilities('navigator:delete', $USER->role_id, false),
                                            'icon'       => 'fa fa-trash',
                                            'tooltip'    => 'löschen',),
                           'edit'  => array('onclick'    => "formloader('subject','edit',__id__);",
                                            'capability' => checkCapabilities('navigator:update', $USER->role_id, false),
                                            'icon'       => 'fa fa-edit',
                                            'tooltip'    => 'bearbeiten')*/);
       $p_widget  = array('header'      => 'na_title'/*,
                          'subheader01' => 'description',
                          'subheader02' => 'institution'*/); //false ==> don't show icon on widget
       $p_config =   array('id'         => 'checkbox',
                           'na_title'     => 'Fach', 
                        /* 'institution'   => 'Institution', */
                         'p_search'      => array('na_title'),
                         'p_widget'      => $p_widget, 
                         'p_options'     => $p_options);
        $navigators = new Navigator();
        setPaginator('navigatorP', $navigators->get(), 'na_id', 'index.php?action=navigator', $p_config);  
        $TEMPLATE->assign('shownavigatorP',  true); //show navigator paginator ? 
    }
}

$navigator_bocks = $navigator->get($navigator_view);
if ($navigator_bocks[0]->na_id != $allowed_navigator){ die(); } //security check
$b_array     = $navigator->getBreadcrumbs($navigator_view);
$breadcrumbs = array();

foreach (array_reverse($b_array) as $value) {
    $breadcrumbs[$value->nv_title] = 'index.php?action=navigator&nv_id='.$value->nb_navigator_view_id;
}
$breadcrumbs[$navigator_bocks[0]->nv_title] = '';
$TEMPLATE->assign('breadcrumb',  $breadcrumbs);
$TEMPLATE->assign('search_navigator', $navigator->searchfield_content($navigator_view)); 
$TEMPLATE->assign('page_title', $navigator_bocks[0]->na_title); 
$TEMPLATE->assign('navigator', $navigator_bocks);   