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
* This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by  
* the Free Software Foundation; either version 3 of the License, or (at your option) any later version.                                   
*                                                                       
* This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of        
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details:                          
*                                                                       
* http://www.gnu.org/copyleft/gpl.html      
*/
global $PAGE, $USER, $TEMPLATE;

$TEMPLATE->assign('page_title', 'Zertifikat einrichten');   

$form           = new HTML_QuickForm2('certificate', 'post', 'action=index.php?action=certificate');   // Instantiate the HTML_QuickForm2 object
$fieldset       = $form->addElement('fieldset');
$id             = $fieldset->addElement('hidden',   'id', array('id' => 'id'));
$mode           = $fieldset->addElement('hidden',   'mode');
$certificate    = $fieldset->addElement('text',     'certificate', array('size' => 40, 'maxlength' => 255, 'id' => 'certificate'))->setLabel('Name der Zertifikat-Vorlage');
$description    = $fieldset->addElement('text',     'description', array('size' => 40, 'maxlength' => 255))->setLabel('Beschreibung');
$institution_id = $fieldset->addElement('select',   'institution_id', null, array('options' => $USER->get_instiution_enrolments(true), 'label' => 'Institution / Schule'));
$c_template     = $fieldset->addElement('textarea', 'template', array('style' => 'width: 300px;', 'cols' => 50, 'rows' => 7)) ->setLabel('Zertifikat-Vorlage<br><br>Felder:<br>*&lt;!--Vorname--&gt;, *&lt;!--Nachname--&gt;</br> 
                                            *&lt;!--Start--&gt;, *&lt;!--Ende--&gt</br>
                                             &lt;!--Ort--&gt;, &lt;!--Datum--&gt;, &lt;!--Unterschrift--&gt;</br>
                                             &lt;!--Thema--&gt;, &lt;!--Ziel--&gt;</br>
                                             &lt;!--Ziel_mit_Hilfe_erreicht--&gt;,  &lt;!--Ziel_erreicht--&gt;, &lt;!--Ziel_offen--&gt;</br>
                                             &lt;ziel status="[1]" class="[objective_green row]" &gt;&lt;/ziel&gt;</br>
                                             &lt;!--Bereich{terminal_objective_id,...}--&gt;HTML&lt;!--/Bereich--&gt;');

if (isset($_GET['function'])) {
     switch ($_GET['function']) {
        case "new":     $TEMPLATE->assign('showForm', true); 
                        $form->addDataSource(new HTML_QuickForm2_DataSource_Array(array('mode' => 'add')));
                        $btnval = 'Vorlage hinzufügen';
            break;
        case "edit":    $TEMPLATE->assign('showForm', true); 
                        $edit_certificate       = new Certificate();
                        $edit_certificate->id   = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
                        $edit_certificate->load();
                        $form->addDataSource(new HTML_QuickForm2_DataSource_Array(array(
                        'id'             => $edit_certificate->id,
                        'certificate'    => $edit_certificate->certificate,
                        'description'    => $edit_certificate->description,
                        'institution_id' => $edit_certificate->institution_id,
                        'template'       => $edit_certificate->template,
                        'mode'           => 'update'
                        )));
                        $btnval = 'Vorlage aktualisieren';
            break;
         default: break;
     }
}

$certificate->addFilter('trim');
$certificate->addRule('required', 'Bitte Name der Zertifikat-Vorlage eingeben');
$description->addRule('required', 'Bitte Beschreibung eingeben');
$c_template->addRule( 'required', 'Zertifikat-Vorlage darf nicht leer sein');
if ($form->validate()) {
    $new_certificate = new Certificate();
    if (null != $id->getValue()){
        $new_certificate->id         = $id->getValue();  
    }
    $new_certificate->certificate    = $certificate->getValue();
    $new_certificate->description    = $description->getValue();
    $new_certificate->template       = $c_template->getValue();
    $new_certificate->creator_id     = $USER->id;
    $new_certificate->institution_id = $institution_id->getValue();
    if ($mode->getValue() == 'add')    { $new_certificate->add(); }
    if ($mode->getValue() == 'update') { $new_certificate->update(); }    
} else if (null != $mode->getValue()){ // Form weiter anzeigen, wenn Validierung fehlschlägt
    $TEMPLATE->assign('showForm', true);
    switch ($mode->getValue()) {
        case 'add':     $btnval = 'Vorlage hinzufügen';     break;
        case 'update':  $btnval = 'Vorlage aktualisieren';  break;
        default: break;
    } 
}

$submit_btn = $fieldset->addElement('submit', isset($btnval) ? $btnval : "", array('value' => isset($btnval) ? $btnval : "")); // butten value funktioniert nicht über addDataSource

$TEMPLATE->assign('certificate_form', $form);     // assign the form

$certificates = new Certificate();
$certificates->institution_id = $USER->institutions;

$p_options = array('delete' => array('onclick' => "del('certificate',__id__, $USER->id);", 
                                     'capability' => checkCapabilities('certificate:delete', $USER->role_id, false)),
                    'edit'  => array('href'    => 'index.php?action=certificate&function=edit&id=__id__'),
                                     'capability' => checkCapabilities('certificate:update', $USER->role_id, false));
$p_config =  array('certificate' => 'Titel des Zertifikat-Vorlage', 
                  'description'   => 'Beschreibung', 
                  'institution'   => 'Institution', 
                  'creation_time' => 'Erstellungs-Datum',
                  'username'      => 'Erstellt von',
                  'p_options'     => $p_options);
setPaginator('certificateP', $TEMPLATE, $certificates->getCertificates('certificateP'), 'ct_val', 'index.php?action=certificate', $p_config); //set Paginator