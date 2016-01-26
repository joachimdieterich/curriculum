<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package core
 * @filename bulletinBoard.php
 * @copyright 2016 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2016.01.04 09:51
 * @license: 
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by  
 * the Free Software Foundation; either version 3 of the License, or (at your option) any later version.
 *                                                                       
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of 
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details:                          
 *                                                                       
 * http://www.gnu.org/copyleft/gpl.html      
 */
$base_url       = dirname(__FILE__).'/../';
include($base_url.'setup.php');  //Läd Klassen, DB Zugriff und Funktionen


global $USER, $CFG, $PAGE;
$USER           = $_SESSION['USER'];

$form           = new HTML_QuickForm2('bulletinBoard', 'post', 'bulletinBoard.php');   // Instantiate the HTML_QuickForm2 object
$fieldset       = $form->addElement('fieldset');
$institution_id = $fieldset->addElement('select',   'institution_id', null, array('options' => $USER->get_instiution_enrolments(true), 'label' => 'Institution / Schule'));
$title          = $fieldset->addElement('text',     'title', array('size' => 40, 'maxlength' => 255, 'id' => 'title'))->setLabel('Überschrift');
$text           = $fieldset->addElement('textarea', 'text', array('style' => 'width: 300px;', 'cols' => 50, 'rows' => 7)) ->setLabel('Text');
                  $fieldset->addElement('submit',   null, array('value' => 'Pinnwand speichern'));

$title->addRule('required', 'Bitte Titel eingeben');
$text->addRule('required',  'Bitte Text eingeben');
$institution        = new Institution();
$institution->id    = $USER->institution_id;
$bb                 = $institution->getBulletinBoard();
if ($bb) {
    $form->addDataSource(new HTML_QuickForm2_DataSource_Array(array(
        'institution_id' => $bb->id,
        'title'          => $bb->title,
        'text'           => $bb->text
        )));
} else {    
    $form->addDataSource(new HTML_QuickForm2_DataSource_Array(array(
        'institution_id' => $USER->institution_id
        )));
}

if ($form->validate()) {
    $bulletinBoard = new Institution();
    $bulletinBoard->id = $institution_id->getValue();
    $bulletinBoard->setBulletinBoard($title->getValue(), $text->getValue());
    header("Location: ../../public/index.php?action=dashboard"); exit;
}

echo '<div class="messageboxClose" onclick="closePopup();"></div><div class="contentheader">Pinnwand bearbeiten</div>
<div id="popupcontent">';
echo $form;
echo '</div>';