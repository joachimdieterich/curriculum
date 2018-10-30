<?php
/**
 *  OMEGA Plugin for curriculum
 * @abstract This file is part of curriculum - http://www.joachimdieterich.de
 * @package plugins
 * @filename plugin.php
 * @copyright 2015 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2015.09.26 14:12
 * @license 
 *
 * All rights reserved    
 */
/**
 * Über diese Klasse lassen sich Medien von OMEGA (omega.bildung-rp.de) einbinden.
 */
class repository_plugin_omega extends repository_plugin_base { 
    const PLUGINNAME = 'omega';
    public $titles; //array to filter double entries
    
    public function setReference($dependency, $id, $reference = ''){
        $type       = $this->resolveDependency($dependency);
        
        $db = DB::prepare('SELECT COUNT(id) FROM plugin_repo WHERE objective_id = ? AND type = ? AND repo = ?');
        $db->execute(array($id, $type, 'omega'));
        if($db->fetchColumn() >= 1) { 
            if ($reference != ''){
                $db = DB::prepare('UPDATE plugin_repo SET reference = ? WHERE objective_id = ? AND type = ? AND repo = ?');
                $db->execute(array($reference, $id, $type, 'omega'));   
            } else {
                $db = DB::prepare('DELETE FROM plugin_repo WHERE objective_id = ? AND type = ? AND repo = ?');
                $db->execute(array($id, $type, 'omega'));   
            }
        } else {
            if ($reference != ''){
                $db = DB::prepare('INSERT INTO plugin_repo (objective_id, type, reference, repo) VALUES (?,?,?,?)');
                $db->execute(array($id, $type, $reference, 'omega'));
            }
        }
    }
    
    public function getReference($dependency, $id){
        $db         = DB::prepare('SELECT reference FROM plugin_repo WHERE objective_id = ? AND type = ? AND repo = ?');   
        $db->execute(array($id, $this->resolveDependency($dependency), 'omega'));
        $result     = $db->fetchObject();
        if ($result) {
            return $result->reference;
        } else {
            return false;           
        }
    }

    public function getFiles ($dependency, $id, $files){
        $type       = $this->resolveDependency($dependency);
        $db         = DB::prepare('SELECT reference FROM plugin_repo WHERE objective_id = ? AND type = ? AND repo = ?');   
        $db->execute(array($id, $type, 'omega'));
        $result     = $db->fetchObject();
        if (isset($result->reference)) { 
            $references     = array();
            $references     = explode(",", $result->reference);
            $this->titles   = array(); // titel_array, über das doppelte Treffer aussortiert werden -> 
            $omega_array    = array();
            foreach ($references as $reference) {
                if (substr(trim($reference), 0, 1) == '{'){
                    // ref e.g. {"sodis":["id": "509901"]} --> only used for sodis links
                } else {
                    $omega_array = array_merge($omega_array, $this->getItem($reference));
                }
            } //end foreach loop
            
            
            if (is_array($files) AND !empty($omega_array[0])){ // beide Vorhanden
                $files = array_merge($files, $omega_array);
            } else if (!empty($omega_array[0])) { // nur Omega vorhanden
                $files = $omega_array;
            }   
        } /*else { //if no external reference is set, use objective text to search omega
            $omega_array   = array();
            switch ($dependency) {
                case 'enabling_objective': $objective     = new EnablingObjective();
                    break;
                case 'terminal_objective': $objective     = new TerminalObjective();
                    break;
                default:
                    break;
            }
            $objective->id = $id;
            $objective->load();
            error_log(json_encode($objective));
            $omega_array = array_merge($omega_array, $this->getItem($objective->$dependency));
            if (is_array($files) AND !empty($omega_array[0])){ // beide Vorhanden
                $files = array_merge($files, $omega_array);
            } else if (!empty($omega_array[0])) { // nur Omega vorhanden
                $files = $omega_array;
            } 
        } */
        return $files;
    }
    
    public function getItem($reference){
        $doc        = new DOMDocument();
        $doc->load($this->config('api').$this->config('query').trim($reference));
        $items      = $doc->getElementsByTagName('item');
        $tmp_file   = new File();
        $tmp_array  = array();
        foreach ($items as $item) {
            $elements = $item->getElementsByTagName('element');
            foreach ($elements as $element) {
                   switch ($element->getElementsByTagName('field')->item(0)->nodeValue) {
                        case 'general_identifier': 
                        case 'educational_resourcetype':
                        case 'technical_format':
                            break;
                        case 'rights_description_text':     $tmp_file->license      = $element->getElementsByTagName('value')->item(0)->nodeValue;
                            break;
                        case 'general_title_de':            if (in_array($element->getElementsByTagName('value')->item(0)->nodeValue, $this->titles)) { 
                                                                $double_entry       = true;
                                                            } else {
                                                                $double_entry       = false;
                                                                $this->titles[]           = $element->getElementsByTagName('value')->item(0)->nodeValue;
                                                            }
                                                            $tmp_file->title        = $element->getElementsByTagName('value')->item(0)->nodeValue;
                                                            $tmp_file->type         ='external';
                                                            $tmp_file->file_context = 5;
                            break;
                        case 'general_description_de':      $tmp_file->description  = $element->getElementsByTagName('value')->item(0)->nodeValue;
                            break;
                        case 'technical_thumbnail':         $tmp_file->file_version['t']['filename'] = $element->getElementsByTagName('value')->item(0)->nodeValue;
                            break;
                        case 'technical_location':          $tmp_file->filename     = $element->getElementsByTagName('value')->item(0)->nodeValue;
                                                            $tmp_file->path         = $element->getElementsByTagName('value')->item(0)->nodeValue;
                            break;
                        default:
                            break;
                    }
            }
            if (isset($tmp_file->title) AND ($double_entry == false)){ // Hack: to prevent empty offset 0 and double entry
                $tmp_array[] = clone $tmp_file;
            }
        }
        return $tmp_array;
    }
    
    public function count($type, $id){
        switch ($_SESSION['CONTEXT'][$type]->context_id) {
            case 12: $type = 1;
                break;
            case 27: $type = 0;
                break;

            default:
                break;
        }
        $db         = DB::prepare('SELECT COUNT(*) AS MAX FROM plugin_repo AS po WHERE objective_id = ? AND type = ? AND repo = ?');
        $db->execute(array($id, $type, 'omega'));
        $res        = $db->fetchObject();
        if ($res->MAX >= 1){
            return $res->MAX;
        } else {
            return 0;
        }
    }
    
    private function resolveDependency($dependency) {
        switch ($dependency) {
            case 'terminal_objective': return 0;
            case 'enabling_objective': return 1;
            default: break;
        }   
    }
    
    private function config($name){
        $db = DB::prepare('SELECT value FROM config_plugins WHERE plugin = ? AND name = ?');
        $db->execute(array('repository/omega', $name));
        $result = $db->fetchObject();
        if ($result){
            return $result->value;
        }   
    }
   
}