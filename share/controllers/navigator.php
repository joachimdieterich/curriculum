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
$navigator          = new Navigator();
$navigator->getNavigatorByInstitution($USER->institution_id);
$allowed_navigator  = $navigator->na_id;
$navigator->getFirstView($navigator->na_id);
$navigator_view     = $navigator->nv_id;                            //load navigator_id from 
if (isset($_POST) ){
    if (isset($_POST['search'])){
        $search = filter_input(INPUT_POST, 'search', FILTER_SANITIZE_STRING);
        $TEMPLATE->assign('navigator_reset', true); 
    }   
}
if (isset($_GET)){
    if (isset($_GET['nv_id'])){
        $navigator_view = $_GET['nv_id'];
    }
}
$content        = new Content();
$content->load('id', 1239);
$TEMPLATE->assign('top_text', $content); 


//$navigator_view = 1;
//error_log(json_encode($navigator->get($navigator_view)));
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