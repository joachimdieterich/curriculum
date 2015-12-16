<?php
/**
 * 
 * @abstract This file is part of curriculum - http://www.joachimdieterich.de
 * @package plugins
 * @filename omega.class.php
 * @copyright 2015 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2015.09.26 14:12
 * @license 
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by  
 * the Free Software Foundation; either version 3 of the License, or (at your option) any later version. 
 *                                                                       
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of        
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details:
 *                                                                       
 * http://www.gnu.org/copyleft/gpl.html      
 */
/**
 * Über diese Klasse lassen sich Medien von OMEGA (omega.bildung-rp.de) einbinden.
 */
class Omega {
    
    const OMEGA = 'https://omega.bildung-rp.de/api/api.php';
    const QUERY = '?query=searchContent&searchMode=searchContent&';//''
    
    public function setReference($dependency, $id, $reference){
        $type       = $this->resolveDependency($dependency);
        
        $db = DB::prepare('SELECT COUNT(id) FROM plugin_omega WHERE objective_id = ? AND type = ?');
        $db->execute(array($id, $type));
        if($db->fetchColumn() >= 1) { 
            if ($reference != ''){
                $db = DB::prepare('UPDATE plugin_omega SET reference = ? WHERE objective_id = ? AND type = ?');
                $db->execute(array($reference, $id, $type));   
            } else {
                $db = DB::prepare('DELETE FROM plugin_omega WHERE objective_id = ? AND type = ?');
                $db->execute(array($id, $type));   
            }
        } else {
            if ($reference != ''){
                $db = DB::prepare('INSERT INTO plugin_omega (objective_id, type, reference) VALUES (?,?,?)');
                $db->execute(array($id, $type, $reference));
            }
        }
    }
    
    public function getReference($dependency, $id){
           $db         = DB::prepare('SELECT reference FROM plugin_omega WHERE objective_id = ? AND type = ?');   
           $db->execute(array($id, $this->resolveDependency($dependency)));
           $result     = $db->fetchObject();
           if ($result) {
               return $result->reference;
           } else {
               return false;           
           }
    }

    public function getFiles ($dependency, $id, $files){
        $type       = $this->resolveDependency($dependency);
        $db         = DB::prepare('SELECT reference FROM plugin_omega WHERE objective_id = ? AND type = ?');   
        $db->execute(array($id, $type));
        $result     = $db->fetchObject();
        if (isset($result->reference)) { 
            $doc        = new DOMDocument();
            $doc->load(self::OMEGA.self::QUERY.$result->reference);
            $items      = $doc->getElementsByTagName('item');
            $tmp_file   = new File();
            foreach ($items as $item) {
                $elements = $item->getElementsByTagName('element');
                foreach ($elements as $element) {
                       switch ($element->getElementsByTagName('field')->item(0)->nodeValue) {
                            case 'general_identifier': 
                            case 'educational_resourcetype':
                            case 'technical_format':
                                break;
                            case 'rights_description':              $tmp_file->licence      = $element->getElementsByTagName('value')->item(0)->nodeValue;

                                break;
                            case 'general_title_de':            $tmp_file->title        = $element->getElementsByTagName('value')->item(0)->nodeValue;
                                                                $tmp_file->type         ='omega';
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
                $tmp_array[] = clone $tmp_file;
            }
            
            if (is_array($files) AND is_array($tmp_array)){ // beide Vorhanden
                return array_merge($files, $tmp_array) ;
            } else if (is_array($tmp_array)) { // nur Omega vorhanden
                return $tmp_array;
            }  else {
                return false;
            }
        } else {
            return $files;
        }
    }
    
    private function resolveDependency($dependency) {
        switch ($dependency) {
            case 'terminal_objective': return 0;
            case 'enabling_objective': return 1;
            default: break;
        }   
    }

}