<?php

/*
 * The MIT License
 *
 * Copyright 2018 joachimdieterich.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/**
 *  PIXABAY Plugin for curriculum
 * @abstract This file is part of curriculum - http://www.joachimdieterich.de
 * @package plugins
 * @filename plugin.php
 * @copyright 2018 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2018.08.30 15:13
 * @license 
 *
 * All rights reserved    
 */
/**
 * Über diese Klasse lassen sich Medien von OMEGA (omega.bildung-rp.de) einbinden.
 */
class repository_plugin_pixabay extends repository_plugin_base { 
    const PLUGINNAME = 'pixabay';
    public $total;
    public $totalHits;
    
    public $id;
    public $pageURL;
    public $type;
    public $tags;
    public $previewURL;
    public $previewWidth;
    public $previewHeight;
    public $webformatURL;
    public $webformatWidth;
    public $webformatHeight;
    public $largeImageURL;
    public $fullHDURL;
    public $imageURL;
    public $imageWidth;
    public $imageHeight;
    public $imageSize;
    public $views;
    public $downloads;
    public $favorites;
    public $likes;
    public $comments;
    public $user_id; 
    public $user;
    public $userImageURL;
            
    public function __construct() {
            
    }
    
    public function search($searchstring){
        $doc        = new DOMDocument();
        $query      = 'q='.$searchstring;
        $json       = file_get_contents($this->config('api').'?key='.$this->config('key').'&'.$query);
        $matches    = json_decode($json);
        
        if ($matches->totalHits == 0){
            $this->total        = $matches->total;
            $this->totalHits    = $matches->totalHits;
            return false; 
        }
        $files = array();
        foreach ($matches->hits AS $match){
            foreach ($match as $match_key => $match_value) {
                $this->$match_key = $match_value;
                //$html .= '<img src="'.$match->webformatURL.'" alt="pixabay_id_'.$match->id.'" height="42" width="42">';
            }
            $files[] = clone $this;
        }
        
        return $files;
    }
    
    
    
    private function config($name){
        $db = DB::prepare('SELECT value FROM config_plugins WHERE plugin = ? AND name = ?');
        $db->execute(array('repository/pixabay', $name));
        $result = $db->fetchObject();
        if ($result){
            return $result->value;
        }   
    }
    
}