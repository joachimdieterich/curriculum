<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename debug.php
* @copyright 2018 Joachim Dieterich
* @author Joachim Dieterich
* @date 2018.02.25 14:59
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
$TEMPLATE->assign('breadcrumb',  array('Debug' => 'index.php?action=debug'));
$TEMPLATE->assign('page_title', 'Debug');  

$debug_content  = new Content();
$p_options = array('delete' => array('onclick'    => "del('content',__id__);", 
                                    'capability'  => checkCapabilities('content:delete', $USER->role_id, false),
                                    'icon'        => 'fa fa-trash',
                                    'tooltip'     => 'löschen'));
$p_widget  = array('header'       => 'title',
                   'subheader01'  => 'timecreated',
                   'bg_image'     => false); //false ==> don't show icon on widget
$t_config      = array('td'     => array('onclick'         => "formloader('content', 'show', '__id__');"));
$p_config =   array('id'          => 'checkbox',
                    'title'       => 'Titel', 
                    /*'description' => 'Beschreibung',*/
                    'timecreated'   => 'Erstellungsdatum',
                    't_config'  => $t_config,
                    'p_search'    => array('content','description'),
                    'p_widget'    => $p_widget, 
                    'p_options'   => $p_options);
setPaginator('debugP', $TEMPLATE, $debug_content->get('debug', 0), 'id', 'index.php?action=debug', $p_config); 