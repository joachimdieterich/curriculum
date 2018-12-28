<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename curriculum.php
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
global $USER, $TEMPLATE, $PAGE, $INSTITUTION;
$curriculum = new Curriculum();
$TEMPLATE->assign('page_title', 'Lehrpläne verwalten'); 
$TEMPLATE->assign('breadcrumb',  array('Lehrpläne' => 'index.php?action=curriculum'));

if(isset($_GET['reset']) OR (isset($_POST['reset'])) OR (isset($_POST['new_curriculum']))){
    resetPaginator('curriculumP'); 
}

/*******************************************************************************
 * END POST / GET
 */
$p_options = array('delete' => array('onclick'      => "del('curriculum',__id__);", 
                                     'capability'   => checkCapabilities('curriculum:delete', $USER->role_id, false),
                                     'icon'         => 'fa fa-trash', 
                                     'tooltip'      => 'löschen'),
                   'owner' => array('onclick'      => "formloader('curriculum_owner','set',__id__);", 
                                     'capability'   => checkCapabilities('curriculum:update', $USER->role_id, false),
                                     'icon'         => 'fa fa-user', 
                                     'tooltip'      => 'Besitzer ändern'),
                   'edit'   => array('onclick'      => "formloader('curriculum','edit',__id__);",
                                     'capability'   => checkCapabilities('curriculum:update', $USER->role_id, false),
                                     'icon'         => 'fa fa-edit',
                                     'tooltip'      => 'bearbeiten'),
                   'preview'  => array('onclick'    => "formloader('preview_curriculum','full',__id__);", 
                                     'capability'   => true, 
                                     'icon'         => 'fa fa-list-alt',
                                     'tooltip'      => 'Überblick'),
                   'description'  => array('onclick'=> "formloader('description','curriculum',__id__);", 
                                     'capability'   => true,  //free for all
                                     'icon'         => 'fa fa-info',
                                     'tooltip'      => 'Beschreibung'));
$p_widget  = array('header'     => 'curriculum',
                   'subheader01'=> 'grade, schooltype',
                   'subheader02'=> 'state',
                   'file_id'    => 'icon_id',
                   'bg_image'   => 'file_id',
                   'expand'     => 'description',
                   'description'=> false); //false ==> don't show icon on widget
$t_config      = array('td'     => array('onclick'         => "location.href='index.php?action=view&function=addObjectives&curriculum_id=__id__'"));
$p_config  = array('id'         => 'checkbox',
                   'curriculum'  => 'Lehrplan', 
                   /*'description' => 'Beschreibung', */
                   'clicks'      => 'Aufrufe',
                   'subject'     => 'Fach',
                   'grade'       => 'Klassenstufe',
                   'schooltype'  => 'Schultyp',
                   'state'       => 'Bundesland/Region',
                   /*'de'        => 'Land',*/
                   'p_options'   => $p_options,
                   't_config'  => $t_config,
                   'p_search'    => array('curriculum','description','grade','schooltype','state','subject'),
                   'p_widget'    => $p_widget);
setPaginator('curriculumP', $curriculum->getCurricula('user', $USER->id, 'curriculumP'), 'cu_val', 'index.php?action=curriculum', $p_config);