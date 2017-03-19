<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename f_settings.php
* @copyright 2017 Joachim Dieterich
* @author Joachim Dieterich
* @date 2017.01.30 08.24:05
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
$base_url   = dirname(__FILE__).'/../';
include($base_url.'setup.php');  //Läd Klassen, DB Zugriff und Funktionen
include(dirname(__FILE__).'/../login-check.php');  //check login status and reset idletimer
global $CFG, $USER,$TEMPLATE;
$USER           = $_SESSION['USER'];
$func           = $_GET['func'];
/*Variablen anlegen -> vermeidet unnötige if-Abfragen im Formular*/
$id             = null; 
$template       = null;
$error          = null;
$object         = file_get_contents("php://input");
$data           = json_decode($object, true);
if (is_array($data)) {
    foreach ($data as $key => $value){
        $$key = $value;
    }
}
            
if (isset($_GET['func'])){
    switch ($_GET['func']) {
        case "new":
        case "edit":    $header     = 'Einstellungen ändern';      
                        $selected_template   = $CFG->settings->template;
            break;

        default: break;
    }
}

/* if validation failed, get formdata from session */
if (isset($_SESSION['FORM'])){
    if (is_object($_SESSION['FORM'])) {
        foreach ($_SESSION['FORM'] as $key => $value){
            $$key = $value;
        }
    }
}

$content ='<form id="form_settings" class="form-horizontal" role="form" method="post" action="../share/processors/fp_settings.php"';
if (isset($currentUrlId)){ $content .= $currentUrlId; }
$content .= '"><input type="hidden" name="func" id="func" value="'.$func.'"/>';
if (isset($id)) {                                                               // only set id input field if set! prevents error on validation form reload
     $content .= '<input id="id" name="id" type="text" class="invisible" value="'.$id.'">';
}
$content .= '<div class="nav-tabs-custom"> 
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="false" ><i class="fa fa-user margin-r-10"></i> Persönliche Einstellungen</a></li>
                    <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="true" ><i class="fa fa-university margin-r-10"></i> Einstellungen (Institution)</a></li>';
if (checkCapabilities('user:userListComplete', $USER->role_id, false)){
$content .=         '<li class=""><a href="#tab_3" data-toggle="tab" aria-expanded="true" ><i class="fa fa-globe margin-r-10"></i> Einstellungen (global)</a></li>';
}
$content .=   ' </ul>
            </div>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_1">
                    <div class="nav-tabs-custom small"> 
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#sub_tab_1" data-toggle="tab" aria-expanded="false" ><i class="fa fa-dashboard margin-r-10"></i> Template</a></li> 
                            <li ><a href="#sub_tab_2" data-toggle="tab" aria-expanded="true" ><i class="fa fa- fa-envelope margin-r-10"></i> Signatur</a></li> 
                        </ul>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane active" id="sub_tab_1">';
                            $directories = glob($CFG->share_root . '/templates/*' , GLOB_ONLYDIR);
                            $templates_obj = new stdClass();
                            foreach ($directories as $key => $value) {
                                $templates_obj->label     = $key;
                                $templates_obj->template  = basename($value);
                                $t[] = clone $templates_obj;
                            }
                            $content .= Form::input_select('template', 'Template', $t, 'template', 'template', $selected_template,  $error);
                            $content .= Form::input_button(['id' => 'user_template_save','label'=>'Speichern',  'icon'=>'fa fa-save']);
                               
           $content .= '</div>
                        <div class="tab-pane" id="sub_tab_2">';
                            $signature = new Content();
                            $content .= Form::info(['id' => 'user_signature_info', 'content' => 'Hier können Sie ihre Signatur für Nachrichten ändern.']);
                            if (isset($signature->get('signature', $USER->id)[0]->content)){
                                $s = $signature->get('signature', $USER->id)[0]->content;
                            } else {
                                $s = '';
                            }
                            $content .= Form::input_textarea('user_signature', 'Signatur', $s, $error, '');
                            $content .= Form::input_button(['id' => 'user_signature_save','label'=>'Speichern',  'icon'=>'fa fa-save']);
         $content .=    '</div>
                    </div>';
                            
                             
                
    
    $content .='</div><!-- /.tab-pane -->
                <div class="tab-pane" id="tab_2"> under construction
                </div><!-- /.tab-pane -->';
    if (checkCapabilities('user:userListComplete', $USER->role_id, false)){
        $terms = new Content();
        $content .=  '<div class="tab-pane" id="tab_3">';
        $content .= Form::info(['id' => 'global_terms_info', 'content' => 'Hier können Sie die Nutzungsbedingungen / Datenschutzerklärung ändern. <br>Diese muss von allen Nutzern beim ersten Login bestätigt werden.']);
        $content .= Form::input_textarea('global_terms', 'Nutzungsbedingungen / Datenschutzerklärung', $terms->get('terms')[0]->content, $error, '');
        $content .= Form::input_button(['id' => 'global_terms_save','label'=>'Speichern',  'icon'=>'fa fa-save']);
        $content .=  '</div><!-- /.tab-pane -->';
    }
     $content .= '</div>';
$content .= '</form>';
$footer   = ''; 

$html     = Form::modal(array('title'     => $header,
                              'content'   => $content, 
                              'f_content' => $footer));
echo json_encode(array('html'   => $html));