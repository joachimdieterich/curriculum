<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename institution.php
* @copyright 2014 Joachim Dieterich
* @author Joachim Dieterich
* @date 2014.10.13 08:26
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
global $CFG, $USER, $TEMPLATE, $INSTITUTION;

$TEMPLATE->assign('page_title',  'Institutionen');
$TEMPLATE->assign('breadcrumb',  array('Institutionen' => 'index.php?action=institution'));
$state     = new State;  

$p_options = array('delete' => array('onclick'    => "del('institution',__id__);", 
                                     'capability' => checkCapabilities('institution:delete', $USER->role_id, false),
                                     'icon'       => 'fa fa-trash',
                                     'tooltip'    => 'löschen'),
                    'edit'  => array('onclick'    => "formloader('institution', 'edit',__id__);",
                                     'capability' => checkCapabilities('institution:update', $USER->role_id, false),
                                     'icon'       => 'fa fa-edit',
                                     'tooltip'    => 'bearbeiten'),
                    'overview'  => array('onclick'   => "formloader('preview_institution','full',__id__);", 
                                     'capability'   => checkCapabilities('task:add', $USER->role_id, false),  //todo: use extra capability?
                                     'icon'         => 'fa fa-list-alt',
                                     'tooltip'      => 'Überblick')
                    );
$p_widget  = array('header'     => 'institution',
                   'subheader01'=> 'description',
                   'subheader02'=> 'state_id',
                   'file_id'    => 'file_id',
                   'bg_image'   => 'file_id'); //false ==> don't show icon on widget
$p_view    = array('id'           => 'checkbox', 
                  'institution'   => 'Institution', 
                  'description'   => 'Beschreibung', 
                  'street'        => 'Straße', 
                  'postalcode'    => 'PLZ', 
                  'city'          => 'city', 
                  'phone'         => 'Telefon', 
                  'email'         => 'Email', 
                  'schooltype_id' => 'Schultyp',
                  'state_id'      => 'Bundesland/Region',
                  'de'            => 'Land',
                  'creation_time' => 'Erstellungsdatum',
                  'username'      => 'Administrator',
                  'p_search'      => array('institution','description','schooltype','state','de'),
                  'p_widget'      => $p_widget, 
                  'p_options'     => $p_options);
$institution = new Institution();
setPaginator('institutionP', $institution->getInstitutions('all', 'institutionP'), 'in_val', 'index.php?action=institution', $p_view); //set Paginator   