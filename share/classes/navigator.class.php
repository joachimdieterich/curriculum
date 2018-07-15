<?php
/**
* This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename navigator.class.php
* @copyright 2018 joachimdieterich
* @author joachimdieterich
* @date 2018.02.07 10:41
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

class Navigator {
    public $na_id;
    public $na_title;
    public $na_context_id;
    public $na_reference_id;
    public $na_creation_time;
    public $na_creator_id;
    public $na_file_id;
    
    public $nv_id;
    public $nv_title;
    public $nv_description;
    public $nv_navigator_id;
    public $nv_creator_id;
    public $nv_top_width_class;
    public $nv_content_width_class;
    public $nv_footer_width_class;
    
    public $nb_id;
    public $nb_context_id;
    public $nb_reference_id;
    public $nb_position;
    public $nb_width_class;
    public $nb_target_context_id;
    public $nb_target_id;
    public $nb_file_id;
    public $nb_visible;
    
    
    
    public function __construct() {
       
    }
    
    public function load($dependency = 'navigator_block', $id = null){
        //if ($id == null){ $id = $this->id; } //doesn't work 
        switch ($dependency) {
            case 'navigator_block':  $db     = DB::prepare('SELECT na.*, nv.*, nb.* FROM navigator AS na, navigator_view AS nv, navigator_block AS nb 
                                                                WHERE nb.nb_id = ? AND na.na_id = nv.nv_navigator_id AND nv.nv_id = nb.nb_navigator_view_id');
                                     $db->execute(array($id));
                                     $result = $db->fetchObject();
                                        if ($result){
                                            foreach ($result as $key => $value) {
                                                $this->$key = $value; 
                                            }
                                            return true;                                                        
                                        } else { 
                                            return false; 
                                        }


                break;

            default:
                break;
        }
    }
    
    public function get($navigator_view = false){
        
        $db = DB::prepare('SELECT nb_id FROM navigator_block WHERE nb_navigator_view_id = ? ORDER BY nb_title');
        $db->execute(array($navigator_view));
        
        $r  = array();
        while($result = $db->fetchObject()) { 
            $this->load('navigator_block', $result->nb_id); 
            $r[]  = clone $this;
        } 

        return $r;     
    }
    
    public function getBreadcrumbs($navigator_view_id){
        $b = array();
        do {
            $navigator_view_id = $this->getParentView($navigator_view_id);
            if ($navigator_view_id != false){
                $b[] = clone $this;
            }
        } while ($navigator_view_id != false);
        return $b;
    }
    
    public function getParentView($navigator_view_id){
        $db = DB::prepare('SELECT nb.nb_navigator_view_id, nv.nv_title FROM navigator_block AS nb, navigator_view AS nv 
                            WHERE nb.nb_target_id = ? AND nb.nb_target_context_id = ? AND nv.nv_id = nb.nb_navigator_view_id');
        $db->execute(array($navigator_view_id, $_SESSION['CONTEXT']['navigator_view']->context_id));
        $result = $db->fetchObject();
        //error_log(json_encode($result).' '.$navigator_view_id.' '.$_SESSION['CONTEXT']['navigator_view']->context_id);
        if ($result){
            foreach ($result as $key => $value) {
                $this->$key = $value; 
            }
            return $result->nb_navigator_view_id;                                                        
        } else { 
            return false; 
        }
    }
    
    public function searchfield_content($navigator_view_id){
        global $USER;
        $search   = array();
        $view_ids = $this->getChildren($navigator_view_id);
        foreach ($view_ids as $v_id) {
            $blocks = $this->get($v_id);
            foreach ($blocks as $block) {
                $s          = new stdClass();
                $s->id      = $block->nb_id;
                
                if ((checkCapabilities('navigator:add', $USER->role_id, false) == true AND $block->nb_visible == 0) OR  $block->nb_visible == 1){
                    switch ($block->nb_context_id) {
                        /* curriculum */
                        case 2:     $s->id      = $block->nb_id;
                                    $s->title   = $block->nb_title;
                                    $s->onclick = "index.php?action=view&curriculum_id={$block->nb_target_id}";
                                    $search[] = clone $s;
                            break;
                        /* content */
                        case 15:    $content            = new Content();
                                    $content->load('id', $block->nb_reference_id);
                                    $s->title   = $content->content;
                                    $s->onclick = "index.php?action=view&curriculum_id={$block->nb_target_id}";
                                    $search[] = clone $s;
                            break; 
                        /* curricula of group */
                        case 16:    $c                  = new Curriculum();
                                    $curricula          = $c->getCurricula('group', $block->nb_reference_id);
                                    foreach ($curricula as $cur) {
                                        $s->title   = $cur->curriculum;
                                        $s->onclick = "index.php?action=view&curriculum_id={$cur->id}";
                                        $search[] = clone $s;
                                    }

                            break;

                        case 29:    $f                  = new File();
                                    $f->load($block->nb_reference_id);
                                    $s->title   = $f->title;
                                    $s->onclick = "index.php?action=navigator&nv_id={$block->nb_target_id}";
                                    $search[] = clone $s;
                            break;

                        case 31:    /* Navigator View*/
                        case 33:    /* Book */
                                    $s->title   = $block->nb_title;
                                    $s->onclick = "index.php?action=navigator&nv_id={$block->nb_target_id}";
                                    $search[] = clone $s;
                            break;

                        default:
                            break;
                    }
                }
            }   
        }
        
        
        return $search;     
    }
    
    public function getChildren($navigator_view_id){
        $b = array();
        foreach ($this->getChildrenBlock($navigator_view_id) AS $block_id){
            $b[]  = $block_id;
            $b    = array_merge($b, $this->getChildren($block_id));

        }
        return $b;
    }
    
    public function getChildrenBlock($navigator_view_id){
        $db = DB::prepare('SELECT nb.* FROM navigator_block AS nb WHERE nb.nb_navigator_view_id = ? AND nb.nb_target_context_id = ?');
        $db->execute(array($navigator_view_id, $_SESSION['CONTEXT']['navigator_view']->context_id));
        
        $r  = array();
        while($result = $db->fetchObject()) { 
            $r[] =  $result->nb_target_id; 
        } 
        
        return $r;
        /*$result = $db->fetchObject();
        if ($result){
            foreach ($result as $key => $value) {
                $this->$key = $value; 
            }
            return $result->nb_target_id;                                                        
        } else { 
            return false; 
        }*/
        
    }
    
    public function getNavigatorByInstitution($institution_id) {
        $db = DB::prepare('SELECT na.* FROM navigator AS na WHERE na.na_context_id = ? AND na.na_reference_id = ?');
        $db->execute(array($_SESSION['CONTEXT']['institution']->context_id, $institution_id));
        
        $result = $db->fetchObject();
        if ($result){
            foreach ($result as $key => $value) {
                $this->$key = $value; 
            }
            return true;                                                        
        } else { 
            return false; 
        }
    }
    
    public function getFirstView($navigator_id){
        $db = DB::prepare('SELECT MIN(nv_id) AS nv_id FROM navigator_view WHERE nv_navigator_id = ?');
        $db->execute(array($navigator_id));
        
        $result = $db->fetchObject();
        if ($result){
            foreach ($result as $key => $value) {
                $this->$key = $value; 
            }
            return true;                                                        
        } else { 
            return false; 
        }
    }
    
}