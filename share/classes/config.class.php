<?php
/**
* 
* @abstract This file is part of curriculum - http://www.joachimdieterich.de
* @package plugin
* @filename config.class.php
* @copyright 2016 Joachim Dieterich
* @author Joachim Dieterich
* @date 2016.06.12 21:01
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
class Config {
    public $id;
    public $name;
    public $value;
    public $context_id;
    public $reference_id;
    public $timestamp;
    
    public function __construct() {
        
    }
    /**
     * Load global config
     * @global $CFG
     */
    public function load(){
        global $CFG;
        $db = DB::prepare('SELECT * FROM config WHERE context_id = (SELECT context_id FROM context WHERE context = ?) AND reference_id = 0'); //load std values
        $db->execute(array('config'));
        $config = new stdClass();
        $CFG->settings = new stdClass();
        while($result = $db->fetchObject()) { 
            if ($result->type == "bool") {
                if (strtolower($result->value) == "false") {
                  $result->value = "";
                }
            }
            settype($result->value, $result->type); //store var with proper type ! for PHP < 4.2.0 change type "bool" to "boolean" and "int" to "integer"
            
            /**
             * check for plugin and load existing plugins
             */
            switch ($result->name) {
                case 'repository':
                case 'auth':
                case 'webservice':  if (!isset($CFG->settings->{$result->name})){
                                        $CFG->settings->{$result->name} = new stdClass();
                                    }
                                    $CFG->settings->{$result->name}->{$result->value} = get_plugin($result->name,$result->value); //store class in $CFG->settings
                    break;
                
                default:            $CFG->settings->{$result->name} = $result->value; //store value in $CFG->settings
                    break;
            }
        } 
    }
    
    public function load_plugin_config($plugin){
        $db = DB::prepare('SELECT * FROM config_plugins WHERE plugin = ?'); //load std values
        $db->execute(array($plugin));
        $config = new stdClass();
        while($result = $db->fetchObject()) { 
            //$config->{$result->name} = $result->value;
            $config->{$result->name} = $result;
        }
        return $config;
    }
    
    public function set(){
        $db = DB::prepare('SELECT id FROM config WHERE name = ? AND context_id = ? AND reference_id = ?');
        $db->execute(array($this->name, $this->context_id, $this->reference_id));
        $this->id = $db->fetchColumn();
        if($this->id > 0) { 
            $db = DB::prepare('UPDATE config SET name = ?, value = ?, context_id = ?, reference_id = ? WHERE id = ?');
            return $db->execute(array($this->name, $this->value, $this->context_id, $this->reference_id, $this->id));
        } else {
            $db = DB::prepare('INSERT INTO config (name,value,context_id,reference_id) VALUES (?,?,?,?)');
            return $db->execute(array($this->name, $this->value, $this->context_id, $this->reference_id));
        }
    }
    
    public function get($dependency = 'global', $name = null, $context_id = null, $reference_id = null){
        if ($context_id      == null){ $context_id      = $this->context_id; }
        if ($reference_id == null){ $reference_id = $this->reference_id; }
        switch ($dependency) {
            case 'global':  if ($name == null){
                                $db = DB::prepare('SELECT * FROM config WHERE context_id = ? AND reference_id = 0 ORDER BY name'); //load std values
                                $db->execute(array(19)); //19 == context_id for config
                            } else {
                                $db = DB::prepare('SELECT * FROM config WHERE name = ? AND context_id = ? AND reference_id = 0'); //load std values
                                $db->execute(array($name, 19)); //19 == context_id for config
                            }
                            
                break;
            case 'user':
            case 'institution': if ($name == null){
                                    $db = DB::prepare('SELECT * FROM config WHERE context_id = ? AND reference_id = ?'); //load std values
                                    $db->execute(array($context_id, $reference_id));
                                } else {                  
                                    $db = DB::prepare('SELECT * FROM config WHERE name = ? AND context_id = ? AND reference_id = ?'); //load std values
                                    $db->execute(array($name, $context_id, $reference_id));
                                }
                            
                break;

            default:
                break;
        }
        
        $entrys = array();
        while($result = $db->fetchObject()) { 
            foreach ($result AS $key => $value){
                $this->$key = $value;
            }
            $entrys[]            = clone $this;
        } 
        
        if (!empty($entrys)){ 
            return $entrys;
        } else {
            return false;
        }
        
    }
    
    public function set_config_plugin($plugin, $name, $value){
        $db = DB::prepare('SELECT id FROM config_plugins WHERE plugin = ? AND name = ?');
        $db->execute(array($plugin, $name));
        $id = $db->fetchColumn();
        if($id > 0) { 
            $db = DB::prepare('UPDATE config_plugins SET plugin = ?, name = ?, value = ? WHERE id = ?');
            return $db->execute(array($plugin, $name, $value, $id));
        } else {
            $db = DB::prepare('INSERT INTO config_plugins (plugin,name,value) VALUES (?,?,?)');
            return $db->execute(array($plugin, $name, $value));
        }
    }
}