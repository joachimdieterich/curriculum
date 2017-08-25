<?php
/**
 * Moodle Plugin for curriculum
 *
 * @abstract This file is part of curriculum - http://www.joachimdieterich.de
 * @package plugins
 * @filename plugin.php
 * @copyright 2017 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2017.06.05 21:44
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

class webservice_plugin_moodle extends webservice_plugin_base { 
    
    function __construct() {
        include_once 'curl.php';
        $config         = new Config();
        $this->config   = $config->load_plugin_config('webservice/moodle');
        //error_log(json_encode($this->config ));
    }
    
    // Rest call
    function call($functionname, $params){
        $serverurl      = $this->config->domainname . '/webservice/rest/server.php'. '?wstoken=' . $this->config->token . '&wsfunction='.$functionname;
        
        $curl           = new curl;        
        $rest_format    = ($this->config->rest_format == 'json')?'&moodlewsrestformat=' . $this->config->rest_format:'';    //if rest format == 'xml', then we do not add the param for backward compatibility with Moodle < 2.2        
        return $curl->post($serverurl . $rest_format, $params);   
    }
    
    function core_course_get_contents($courseid){
        $params         = array('courseid' => $courseid);
        return $this->call('core_course_get_contents',  $params);
    }
    
    
    function core_course_get_courses(/*$options*/){
        $params         = array();
        return $this->call('core_course_get_courses',  $params);
    }
    
    function core_user_get_users($field, $search){
        $params         = array('criteria' => array(array('key'=> $field, 'value' => $search)));
        return $this->call('core_user_get_users',  $params);
    }
   
    
    
    // db functions
    function link_module($context_id, $reference_id, $config_data){
        global $USER;
        $db = DB::prepare('SELECT count(id) FROM plugin_moodle WHERE context_id = ? AND reference_id = ? AND ws_function = ?');
        $db->execute(array($context_id, $reference_id, 'link_module'));
        if($db->fetchColumn() > 0) {
            $db = DB::prepare('UPDATE plugin_moodle SET config_data = ?, creator_id = ? WHERE context_id = ? AND reference_id = ? AND ws_function = ?');
            return $db->execute(array($config_data, $USER->id, $context_id, $reference_id, 'link_module'));
        } else { 
            $db = DB::prepare('INSERT INTO plugin_moodle (config_data,creator_id,context_id,reference_id,ws_function) VALUES (?,?,?,?,?)');
            return $db->execute(array($config_data, $USER->id, $context_id, $reference_id, 'link_module'));
        }
    }
    
    function link_module_results($context_id, $reference_id){
        $db = DB::prepare('SELECT * FROM plugin_moodle WHERE context_id = ? AND reference_id = ? AND ws_function = ?');
        $db->execute(array($context_id, $reference_id, 'link_module'));
        $result     = $db->fetchObject();
        $res_obj    = new stdClass();
        if ($result){
            $res_obj->id            = $result->id;
            $res_obj->context_id    = $result->context_id;
            $res_obj->reference_id  = $result->reference_id;
            $res_obj->ws_function   = $result->ws_function;
            $res_obj->config_data   = $result->config_data;
            $res_obj->creation_time = $result->creation_time;
            $res_obj->creator_id    = $result->creator_id;
        }
        return $res_obj;
    }
    
    function getFiles_link_module($dependency, $id, $files){
        $link_module = $this->link_module_results($_SESSION['CONTEXT'][$dependency]->id, $id);
        if (!isset($link_module->config_data)){
            return $files;
        }
        $config_data = json_decode($link_module->config_data);
        $course_cont = json_decode($this->core_course_get_contents($config_data->moodle_course_id)); 
        $tmp_file    = new File();
        $mdl_link    = array();
        foreach ($course_cont AS  $value) {
             foreach($value->modules AS $module){
                 if (isset($module->id) AND $module->modname == 'quiz' ){
                     if ($module->id == $config_data->moodle_module_id){
                         $tmp_file->license                       = '---';
                         $tmp_file->title                         = $module->name;
                         $tmp_file->type                          ='external';
                         $tmp_file->file_context                  = 6;
                         if (isset($module->description)){
                             $tmp_file->description               = $module->description;
                         } else {
                             $tmp_file->description = '';
                         }
                         $tmp_file->file_version['t']['filename'] = $module->modicon;
                         $tmp_file->filename                      = $module->url;
                         $tmp_file->path                          = $module->url;
                         $mdl_link[] = clone $tmp_file;
                     }
                 }
             }
         }
         if (is_array($mdl_link) AND isset($tmp_file->title)){ //todo define all cases
              $files = array_merge($files, $mdl_link);
         }
         return $files;
    }
    
     public function count($context_id, $id){
        $db         = DB::prepare('SELECT COUNT(*) AS MAX FROM plugin_moodle  WHERE reference_id = ? AND context_id = ?');
        $db->execute(array($id, $context_id));
        $res        = $db->fetchObject();
        if ($res->MAX >= 1){
            return ' '.$res->MAX.' Aufgabe(n)';
        } else {
            return '';
        }
    }
    
}