<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename grade.php
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
$TEMPLATE->assign('breadcrumb',  array('Klassenstufen' => 'index.php?action=grade'));
$TEMPLATE->assign('page_title', 'Klassenstufen');  
                                       
$grade                  = new Grade();
$grade->institution_id  = $USER->institutions;

$p_options = array('delete' => array('onclick'    => "del('grade',__id__);", 
                                     'capability' => checkCapabilities('grade:delete', $USER->role_id, false),
                                     'icon'       => 'fa fa-trash',
                                     'tooltip'    => 'löschen'),
                    'edit'  => array('onclick'    => "formloader('grade','edit',__id__);",
                                     'capability' => checkCapabilities('grade:update', $USER->role_id, false),
                                     'icon'       => 'fa fa-edit',
                                     'tooltip'    => 'bearbeiten'));
$p_widget  = array('header'       => 'grade',
                   'subheader01'  => 'description',
                   'subheader02'  => 'institution'); //false ==> don't show icon on widget
$p_config =   array('id'          => 'checkbox',
                    'grade'       => 'Klassenstufe', 
                    'description' => 'Beschreibung', 
                    'institution' => 'Institution', 
                    'p_search'    => array('grade','description','institution'),
                    'p_widget'    => $p_widget, 
                    'p_options'   => $p_options);
setPaginator('gradeP', $TEMPLATE, $grade->getGrades('all', null, 'gradeP'), 'gr_val', 'index.php?action=grade', $p_config); 