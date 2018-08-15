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
    
    public function load(){
        $db = DB::prepare('SELECT * FROM config WHERE context_id = (SELECT context_id FROM context WHERE context = ?) AND reference_id = 0'); //load std values
        $db->execute(array('config'));
        $config = new stdClass();
        while($result = $db->fetchObject()) { 
            if ($result->type == "bool") {
                if (strtolower($result->value) == "false") {
                  $result->value = "";
                }
            }
            settype($result->value, $result->type); //store var with proper type ! for PHP < 4.2.0 change type "bool" to "boolean" and "int" to "integer"
            //error_log($result->name.': '.json_encode(gettype($result->value)).' -> '.$result->value);
            $config->{$result->name} = $result->value;
        }
        return $config;
    }
    
    public function load_plugin_config($plugin){
        $db = DB::prepare('SELECT * FROM config_plugins WHERE plugin = ?'); //load std values
        $db->execute(array($plugin));
        $config = new stdClass();
        while($result = $db->fetchObject()) { 
            $config->{$result->name} = $result->value;
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
    
    public function get($dependency = 'global', $name = null, $context = null, $reference_id = null){
        if ($name         == null){ $name         = $this->name; }
        if ($context      == null){ $context      = $this->context; }
        if ($reference_id == null){ $reference_id = $this->reference_id; }
        switch ($dependency) {
            case 'global':  $db = DB::prepare('SELECT * FROM config WHERE name = ? AND context_id = (SELECT context_id FROM context WHERE context = ?) AND reference_id = 0'); //load std values
                            $db->execute(array($name, 'config'));
                break;
            case 'user':
            case 'institution':
                            $db = DB::prepare('SELECT * FROM config WHERE name = ? AND context_id = (SELECT context_id FROM context WHERE context = ?) AND reference_id = ?'); //load std values
                            $db->execute(array($name, $context, $reference_id));
                break;

            default:
                break;
        }
        $result = $db->fetchObject();
        if ($result){
            foreach ($result as $key => $value) {
                $this->{$key} = $value;
            }
            return $this;
        } else { return false;}
        
    }
    
}