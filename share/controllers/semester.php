<?php
/**
*  This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename semester.php
* @copyright 2013 Joachim Dieterich
* @author Joachim Dieterich
* @date 2013.03.08 13:26
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
global $USER, $TEMPLATE;

$TEMPLATE->assign('page_title', 'Lernzeiträume');
$TEMPLATE->assign('breadcrumb',  array('Lernzeiträume' => 'index.php?action=semester'));
$semesters                  = new Semester();
$semesters->institution_id  = $USER->institutions; 

$p_options = array('delete' => array('onclick'    => "processor('delete', 'semester', __id__, { 'reload': 'false', 'callback': 'replaceElementByID', 'element_Id': 'row__id__'});", 
                                     'capability' => checkCapabilities('semester:delete', $USER->role_id, false),
                                     'icon'       => 'fa fa-trash',
                                     'tooltip'    => 'löschen'),
                    'edit'  => array('onclick'    => "formloader('semester','edit',__id__);", 
                                     'capability' => checkCapabilities('semester:update', $USER->role_id, false),
                                     'icon'       => 'fa fa-edit',
                                     'tooltip'    => 'bearbeiten'));
$p_widget  = array('header'       => 'semester',
                   'subheader01'  => 'description',
                   'subheader02'  => 'institution'); //false ==> don't show icon on widget
$p_config = array('id'            => 'checkbox',
                  'semester'      => 'Lernzeitraum', 
                  'description'   => 'Beschreibung',
                  'institution'   => 'Institution',
                  'begin'         => 'Lernzeitraum-Beginn',
                  'end'           => 'Lernzeitraum-Ende',
                  'creation_time' => 'Erstellungsdatum',
                  'username'      => 'Erstellt von',
                  'p_search'      => array('semester','description','institution'),
                  'p_widget'      => $p_widget, 
                  'p_options'     => $p_options);
setPaginator('semesterP', $semesters->getSemesters('all',null,'semesterP'), 'se_val', 'index.php?action=semester', $p_config); 