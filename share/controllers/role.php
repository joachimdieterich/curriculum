<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename role.php
* @copyright 2013 Joachim Dieterich
* @author Joachim Dieterich
* @date 2013.11.17 17:18
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
global $USER, $PAGE, $TEMPLATE;

$TEMPLATE->assign('page_title', 'Benutzerrollen');
$TEMPLATE->assign('breadcrumb',  array('Benutzerrollen' => 'index.php?action=role'));
$role = new Roles();

$p_options = array('delete' => array('onclick'    => "del('role',__id__, $USER->id);", 
                                     'capability' => checkCapabilities('role:delete', $USER->role_id, false),
                                     'icon'       => 'fa fa-minus',
                                     'tooltip'    => 'löschen'),
                    'edit'  => array('onclick'    => "formloader('role','edit',__id__);",
                                     'capability' => checkCapabilities('role:update', $USER->role_id, false),
                                     'icon'       => 'fa fa-edit',
                                     'tooltip'    => 'bearbeiten'));
$p_config =   array('id'          => 'checkbox',
                    'role'        => 'Rolle', 
                  'description'   => 'Beschreibung', 
                  'p_options'     => $p_options);
setPaginator('roleP', $TEMPLATE, $role->get('roleP'), 'ro_val', 'index.php?action=role', $p_config); //set Paginator