<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename f_link_module.php
* @copyright 2017 Joachim Dieterich
* @author Joachim Dieterich
* @date 2017.06.07 08:11
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
$base_url  = '../../../../';
include($base_url.'setup.php');  //Läd Klassen, DB Zugriff und Funktionen
include($base_url.'login-check.php');  //check login status and reset idletimer
global $USER, $CFG;

$USER           = $_SESSION['USER'];
$func           = $_GET['func'];
$moodle_percent = '';
$error          = null;
if (isset($func)){
    switch ($func) {
        case 'terminal_objective':  $reference_id  =  filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
            break;          
        case 'enabling_objective':  $header = 'Moodle-Aktivität verknüpfen';
                                    //check if link exists
                                    $ws          = get_plugin('webservice', $CFG->settings->webservice);
                                    $link_module = $ws->link_module_results($_SESSION['CONTEXT'][$func]->id, filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT));
                                    
                                    $reference_id  =  filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
                                    //get courses
                                    $c_ids = json_decode($ws->core_course_get_courses());
                                    foreach ($c_ids AS  $value) { $courses[] = $value; }
                                    
                                    if (isset($link_module->config_data)){
                                        $config_data      = json_decode($link_module->config_data);
                                        $moodle_course_id = $config_data->moodle_course_id;
                                        $moodle_module_id = $config_data->moodle_module_id;
                                        $moodle_percent   = $config_data->moodle_percent;
                                        
                                        //get modules
                                        $obj2 = json_decode($ws->core_course_get_contents($moodle_course_id)); 

                                        foreach ($obj2 AS  $value) {
                                            foreach($value->modules AS $module){
                                                if (isset($module->id) AND $module->modname == 'quiz'){
                                                    $modules[] = $module;
                                                }
                                            }
                                        } 
                                    } else {
                                        $moodle_course_id = $courses[0]->id;
                                        $moodle_module_id = '';
                                        $moodle_percent   = 60;
                                        $obj2             = json_decode($ws->core_course_get_contents($courses[0]->id)); 
                                        //todo if no modules available 
                                        
                                        foreach ($obj2 AS  $value) {
                                            foreach($value->modules AS $module){
                                                if (isset($module->id) AND $module->modname == 'quiz'){
                                                    $modules[] = $module;
                                                }
                                            }
                                        } 
                                    }
                                    
                                    
                                               
            break;

        default:
            break;
    }
}
/* if validation failed, get formdata from session*/
if (isset($_SESSION['FORM'])){
    if (is_object($_SESSION['FORM'])) {
        foreach ($_SESSION['FORM'] as $key => $value){
            $$key = $value;
        }
    }
}

//* Load webservice form from plugin*/
$content   ='<form id="form_ws_link_module" class="form-horizontal" role="form" method="post" action="../share/plugins/webservice/moodle/processors/fp_link_module.php"';
if (isset($currentUrlId)){ $content .= $currentUrlId; }
$content  .= '"><input type="hidden" name="func" id="func" value="'.$func.'"/>';
if (isset($link_module_id)){
$content  .= '<input type="hidden" name="link_module_id" id="cert_id" value="'.$link_module_id.'"/> ';
}
if (isset($reference_id)){
$content  .= '<input type="hidden" name="reference_id" id="reference_id" value="'.$reference_id.'"/> ';
}
$content   .= Form::info(array('id' => '', 'content' => 'Bitte wählen Sie eine Aktivität aus, die mit dem Ziel / der Kompetenz verknüpft werden soll.'));
$content   .= Form::input_select('moodle_course_id', 'Moodle-Kurs', $courses, 'fullname', 'id', $moodle_course_id, $error, 'getValues(\'modules\', this.value, \'moodle_module_id\', \'\', \'\', \'webservice/moodle/request\');');
$content   .= Form::input_select('moodle_module_id', 'Moodle-Aktivität', $modules, 'name', 'id', $moodle_module_id, $error);
$content   .= Form::input_text('moodle_percent', 'Prozentwert bei dem Ziel freigeschaltet wird', $moodle_percent, $error, $placeholder ='60', $type='number', $min=0, $max=100);
$content  .= '</form>';
$footer    = '<button type="submit" class="btn btn-primary pull-right" onclick="document.getElementById(\'form_ws_link_module\').submit();"><i class="fa fa-floppy-o margin-r-5"></i>'.$header.'</button>'; 

$html      = Form::modal(array('title' => $header,
                          'content'   => $content, 
                          'f_content' => $footer));  

echo json_encode(array('html'=> $html));