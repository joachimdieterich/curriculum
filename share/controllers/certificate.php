<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename certificate.php
* @copyright 2013 Joachim Dieterich
* @author Joachim Dieterich
* @date 2014.07.30 22:43
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
$TEMPLATE->assign('breadcrumb',  array('Zertifikate' => 'index.php?action=certificate'));
$TEMPLATE->assign('page_title', 'Zertifikat einrichten');   

$certificates = new Certificate();
$certificates->institution_id = $USER->institutions;

$p_options = array('delete' => array('onclick'    => "del('certificate',__id__);", 
                                     'capability' => checkCapabilities('certificate:delete', $USER->role_id, false),
                                     'icon'       => 'fa fa-trash',
                                     'tooltip'    => 'löschen'),
                    'edit'  => array('onclick'    => "formloader('certificate','edit',__id__);",
                                     'capability' => checkCapabilities('certificate:update', $USER->role_id, false),
                                     'icon'       => 'fa fa-edit',
                                     'tooltip'    => 'bearbeiten'));
$p_widget  = array('header'     => 'certificate',
                   'subheader01'=> 'description',
                   'subheader02'=> 'institution',
                   'expand'     => 'template',
                   'description'=> false); //false ==> don't show icon on widget
$p_config =  array('id'           => 'checkbox',
                    'certificate' => 'Titel des Zertifikat-Vorlage', 
                  'description'   => 'Beschreibung', 
                  'institution'   => 'Institution', 
                  'creation_time' => 'Erstellungs-Datum',
                  'p_search'      => array('certificate','description','institution','template'),
                  'p_widget'      => $p_widget, 
                  'p_options'     => $p_options);
setPaginator('certificateP', $certificates->getCertificates('certificateP'), 'ct_val', 'index.php?action=certificate', $p_config); //set Paginator