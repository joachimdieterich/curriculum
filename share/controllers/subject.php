<?php
/**
*  This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename subject.php
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
$TEMPLATE->assign('page_title', 'Fächer');
$TEMPLATE->assign('breadcrumb',  array('Fächer' => 'index.php?action=subject'));
$subject                    = new Subject();
$subject->institution_id    = $USER->institutions;
$p_options = array('delete' => array('onclick'    => "del('subject',__id__);", 
                                     'capability' => checkCapabilities('subject:delete', $USER->role_id, false),
                                     'icon'       => 'fa fa-minus',
                                     'tooltip'    => 'löschen',),
                    'edit'  => array('onclick'    => "formloader('subject','edit',__id__);",
                                     'capability' => checkCapabilities('subject:update', $USER->role_id, false),
                                     'icon'       => 'fa fa-edit',
                                     'tooltip'    => 'bearbeiten',));
$p_config =   array('id'         => 'checkbox',
                    'subject'       => 'Fach', 
                  'subject_short' => 'Kürzel',
                  'description'   => 'Beschreibung', 
                  'institution'   => 'Institution', 
                  'p_options'     => $p_options);
setPaginator('subjectP', $TEMPLATE, $subject->getSubjects('subjectP'), 'su_val', 'index.php?action=subject', $p_config); 