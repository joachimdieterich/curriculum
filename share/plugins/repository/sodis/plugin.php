<?php
/**
 *  SODIS Plugin for curriculum
 * @abstract This file is part of curriculum - http://www.joachimdieterich.de
 * @package plugins
 * @filename plugin.php
 * @copyright 2017 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2017.11.29 14:12
 * @license 
 *
 * All rights reserved    
 */
/**
 * Über diese Klasse lassen sich Medien von OMEGA (omega.bildung-rp.de) einbinden.
 */
class repository_plugin_sodis extends repository_plugin_base { 
    public $titles; //array to filter double entries
    
    
    public function getReference($dependency, $id){
           $db         = DB::prepare('SELECT reference FROM plugin_omega WHERE objective_id = ? AND type = ?');  // plugin_omega is needed 
           $db->execute(array($id, $this->resolveDependency($dependency)));
           $result     = $db->fetchObject();
           if ($result) {
               return $result->reference;
           } else {
               return false;           
           }
    }

    public function get($dependency, $id){
        $type       = $this->resolveDependency($dependency);
        $db         = DB::prepare('SELECT reference FROM plugin_omega WHERE objective_id = ? AND type = ?');   
        $db->execute(array($id, $type));
        $result     = $db->fetchObject();
        if (isset($result->reference)) { 
            $references = array();
            $references = explode(",", $result->reference);
            $this->titles     = array(); // titel_array, über das doppelte Treffer aussortiert werden -> 
            $sodis_array = array();
            foreach ($references as $reference) {
                if (substr(trim($reference), 0, 1) == '{'){
                    $sodis_array[] = $this->getItem($reference);
                } else {
                    // omega link --> not used here
                }
            } //end foreach loop
  
        } 
        if (isset($sodis_array)){
            return $sodis_array;
        }
    }
    
    public function getItem($reference){
        $doc        = new DOMDocument();
        $item      = json_decode($reference, false,2);         
        switch (key($item)) {
            case 'kmk_digital_competency_id':   $query = 'kmk_digital_competency_id='.$item->kmk_digital_competency_id;
                break;

            default:
                break;
        }
        $c  = new curl();
        return $c->get($this->config('api').$this->config('query').'/?'.$query);
        
    }
    
    private function resolveDependency($dependency) {
        switch ($dependency) {
            case 'terminal_objective': return 0;
            case 'enabling_objective': return 1;
            default: break;
        }   
    }
    
    public function config($name){
        $db = DB::prepare('SELECT value FROM config_plugins WHERE plugin = ? AND name = ?');
        $db->execute(array('repository/sodis', $name));
        $result = $db->fetchObject();
        if ($result){
            return $result->value;
        }   
    }
    
    

}