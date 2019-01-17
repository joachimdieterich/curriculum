<?php
/**
*  This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename schooltype.php
* @copyright 2019 Joachim Dieterich
* @author Joachim Dieterich
* @date 2019.01.07 14:42
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
$TEMPLATE->assign('page_title', 'Schul-/Institutionstyp');
$TEMPLATE->assign('breadcrumb',  array('Schul-/Institutionstyp' => 'index.php?action=schooltype'));
$schooltype                 = new Schooltype();
$p_options = array('delete' => array('onclick'    => "processor('delete', 'schooltype', __id__, { 'reload': 'false', 'callback': 'replaceElementByID', 'element_Id': 'row__id__'});", 
                                     'capability' => checkCapabilities('schooltype:delete', $USER->role_id, false),
                                     'icon'       => 'fa fa-trash',
                                     'tooltip'    => 'löschen',),
                    'edit'  => array('onclick'    => "formloader('schooltype','edit',__id__);",
                                     'capability' => checkCapabilities('schooltype:update', $USER->role_id, false),
                                     'icon'       => 'fa fa-edit',
                                     'tooltip'    => 'bearbeiten'));
$p_widget  = array('header'      => 'schooltype',
                   'subheader01' => 'description');
$p_config =   array('id'         => 'checkbox',
                    'schooltype' => 'Schul-/Institutionstyp', 
                  'description'   => 'Beschreibung', 
                  'state'         => 'Bundesland',
                  'country'       => 'Land',
                  'p_search'      => array('schooltype','description', 'state'),
                  'p_widget'      => $p_widget, 
                  'p_options'     => $p_options);
setPaginator('schooltypeP', $schooltype->getSchooltypes(false, 'schooltypeP'), 'id', 'index.php?action=schooltype', $p_config); 