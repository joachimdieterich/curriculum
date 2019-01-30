<?php
/**
* Group object can add, update, delete and get data from curriculum db
* 
* @abstract This file is part of curriculum - http://www.joachimdieterich.de
* @package core
* @filename curriculum.class.php
* @copyright 2013 Joachim Dieterich
* @author Joachim Dieterich
* @date 2013.06.08 15:53
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
class Curriculum {
    /**
     * ID of curriculum
     * @var int
     */
    public $id;
    /**
     * combined id | curriculumID_groupID
     * @var string 
     */
    public $id_grid;
    /**
     * Name of curriculum
     * @var string
     */
    public $curriculum; 
    /**
     * Description of curriculum
     * @var string
     */
    public $description; 
    /**
     * id of grade
     * @var int 
     */
    public $grade_id = 8; //todo: std vars setzten 
    /**
     * name of grade
     * @var string
     */
    public $grade;
    /**
     * id of subject
     * @var int
     */
    public $subject_id = 1; //todo: std vars setzten 
    /**
     * name of subject
     * @var type 
     */
    public $subject; 
    /**
     * id of schooltype
     * @var int
     */ 
    public $schooltype_id = 1; //todo: std vars setzten 
    /**
     * id of state
     * @var int
     */
    public $state_id = 11; //todo: std vars setzten 
    /**
     * id of country
     * @var int
     */
    public $country_id = 56; //todo: std vars setzten 
    /**
     * id of icon
     * @var int
     */
    public $icon_id; 
 
    /**
     * Timestamp when Grade was created
     * @var timestamp
     */
    public $creation_time; 
    /**
     * ID of User who created this Grade
     * @var int
     */
    public $creator_id =null; 
    /**
     * language code
     * @var string
     */
    public $language_code; 
    public $color; 
    /**
     * array which holds terminal objectives of this curriculum
     * @var type 
     */
    public $terminal_objectives;
    /**
     * contains click counter value (table statistics)
     * @var int 
     */
    public $clicks;
    /**
     * add curriculum to db
     * @return mixed 
     */    
    public $publisher;
    /**
     * publisher of curriculum
     * @var string
     */
    public $publishingCompany;
    /**
     * publisher of Curriculum
     * @var string
     */
    public $place;
    /**
     * place of publication
     * @var string
     */
    public $date;
    /**
     * date of publication
     * @var string
     */
    
    public function add(){
        global $USER;
        checkCapabilities('curriculum:add', $USER->role_id);
        $db = DB::prepare('INSERT INTO curriculum (curriculum, description, grade_id, subject_id, schooltype_id, state_id, icon_id, country_id, color, creator_id, publisher, publishingCompany, place, date) 
                                            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
        $db->execute(array($this->curriculum, $this->description, $this->grade_id, $this->subject_id, $this->schooltype_id, $this->state_id, $this->icon_id, $this->country_id, $this->color, $USER->id, $this->publisher, $this->publishingCompany, $this->place, $this->date));
        return DB::lastInsertId();
    }
    
    /**
     * Update curriculum in db
     * @return boolean 
     */
    public function update(){
        global $USER;
        checkCapabilities('curriculum:update', $USER->role_id); 
        $db = DB::prepare('UPDATE curriculum SET curriculum = ?, description = ?, grade_id = ?, subject_id = ?, schooltype_id = ?, state_id = ?, icon_id = ?, country_id = ?, color = ?, publisher = ?, publishingCompany = ?, place = ?, date = ? 
                                                WHERE id = ?');
        return $db->execute(array($this->curriculum, $this->description, $this->grade_id, $this->subject_id, $this->schooltype_id, $this->state_id, $this->icon_id, $this->country_id, $this->color, $this->publisher, $this->publishingCompany, $this->place, $this->date, $this->id));
    }
    
    public function setOwner($new_owner){
        global $USER;
        checkCapabilities('curriculum:update', $USER->role_id); 
        $db = DB::prepare('UPDATE curriculum SET creator_id = ? WHERE id = ?');
        return $db->execute(array($new_owner, $this->id));
    }
    
    /**
     * Delete curriculum from db
     * @return mixed 
     */
    public function delete(){
        global $USER, $PAGE, $LOG;
        checkCapabilities('curriculum:delete', $USER->role_id);
        $this->load();
        $LOG->add($USER->id, 'curriculum.class.php', dirname(__FILE__), 'Delete curriculum: '.$this->curriculum.', creator_id: '.$this->creator_id);
        //check if groups are enroled in this curriculum 
        $enrdb = DB::prepare('SELECT id FROM curriculum_enrolments WHERE curriculum_id=? AND status = 1');
        $enrdb->execute(array($this->id));
        if ($enrdb->fetchObject()){
            $PAGE->message[] = array('message' => 'Lehrplan kann nicht gelöscht werden. Es sind Gruppen eingeschrieben', 'icon' => 'fa fa-th text-success');// Schließen und speichern
            return false;
        } else { // delete curriculum
            $db = DB::prepare('DELETE FROM curriculum WHERE id=?');
            if ($db->execute(array($this->id))){
                $terdb = DB::prepare('DELETE FROM terminalObjectives WHERE curriculum_id = ?');
                if ($terdb->execute(array($this->id))){
                    $enadb = DB::prepare('DELETE FROM enablingObjectives WHERE curriculum_id = ?');
                    if ($enadb->execute(array($this->id))){
                        $f = new File();
                        $files = $f->getFiles('curriculum', $this->id);
                        foreach ($files as $file) {
                            $f->id = $file->id; 
                            if (!$f->isUsed()){ //überprüft ob Datei verwendet wird
                                $f->delete();
                            }
                        }
                        return true;
                    } 
                }
            }
        } 
    } 
    
    /**
     * load curriculum depending on id 
     * if load_terminal_objectives == true -> get Objectives
     * @param type $load_terminal_objectives 
     */
    public function load($load_terminal_objectives = false){
        $db = DB::prepare('SELECT cu.*, co.code, su.subject 
                            FROM curriculum AS cu, countries AS co, subjects AS su 
                            WHERE cu.country_id = co.id 
                            AND cu.subject_id = su.id
                            AND cu.id=?');
        $db->execute(array($this->id));
        $result = $db->fetchObject();
        $this->curriculum       = $result->curriculum;
        $this->description      = $result->description;
        $this->grade_id         = $result->grade_id;
        $this->subject_id       = $result->subject_id;
        $this->subject          = $result->subject;
        $this->schooltype_id    = $result->schooltype_id;
        $this->state_id         = $result->state_id;
        $this->icon_id          = $result->icon_id;
        $this->country_id       = $result->country_id;
        $this->language_code    = $result->code;
        $this->color            = $result->color;
        $this->creation_time    = $result->creation_time;
        $this->creator_id       = $result->creator_id;
        $this->publisher        = $result->publisher;
        $this->publishingCompany= $result->publishingCompany;
        $this->place            = $result->place;
        $this->date             = $result->date;
        
        if ($load_terminal_objectives){
            $terminal_objectives = new TerminalObjective();
            $this->terminal_objectives = $terminal_objectives->getObjectives('curriculum', $this->id, true);
        }
        
    }
    /**
     * get curriulum depending on dependency
     * @param string $dependency
     * @param int $id
     * @return array of curriculum objects  
     */
    public function getCurricula($dependency = null, $id = null, $paginator = ''){
        global $USER;
        
        $order_param = orderPaginator($paginator, array('id' => 'cu',
                                                        'curriculum' => 'cu',
                                                        'description' => 'cu',
                                                        'de'         => 'co',
                                                        'state'      => 'st',
                                                        'schooltype' => 'sc',
                                                        'grade'      => 'gr',
                                                        'subject'    => 'su'));  
        /*if ($order_param == ''){
            $order_param = 'ORDER BY cu.curriculum';
        }*/
        $curriculum = array();
        switch ($dependency) {
            case 'group':   $db = DB::prepare('SELECT SQL_CALC_FOUND_ROWS cu.id, cu.curriculum, cu.description, fl.filename, su.subject, 
                                            gr.grade, sc.schooltype, st.state, co.de
                                            FROM curriculum AS cu, curriculum_enrolments AS ce, 
                                            files AS fl, subjects AS su, grade AS gr, schooltype AS sc,
                                            state AS st, countries AS co
                                            WHERE cu.id = ce.curriculum_id
                                            AND cu.icon_id = fl.id AND cu.grade_id = gr.id AND cu.subject_id = su.id
                                            AND cu.schooltype_id = sc.id AND cu.state_id = st.id AND cu.country_id = co.id
                                            AND ce.group_id = ? AND ce.status = 1
                                            '.$order_param);
                            $db->execute(array($id));
                            while($result = $db->fetchObject()) { 
                                    $curriculum[] = $result; 
                            } 
                            if ($paginator != ''){ 
                                set_item_total($paginator); //set item total based on FOUND ROWS()
                            }
                break;
            case 'creator': $db = DB::prepare('SELECT SQL_CALC_FOUND_ROWS cu.id, cu.curriculum, cu.description, gr.grade  
                                                FROM curriculum AS cu, grade AS gr
                                                WHERE cu.creator_id = ? AND gr.id = cu.grade_id '.$order_param);
                            $db->execute(array($id));
                            while($result = $db->fetchObject()) {
                                        $curriculum[] = $result; 
                            }
                            if ($paginator != ''){ 
                                set_item_total($paginator); //set item total based on FOUND ROWS()
                            }
                break; 
            case 'user':    if (checkCapabilities('curriculum:showAll', $USER->role_id, false)){
                                $db = DB::prepare('SELECT DISTINCT SQL_CALC_FOUND_ROWS cu.*, co.de, st.state, sc.schooltype, gr.grade, su.subject, fl.filename  
                                    FROM curriculum AS cu, countries AS co, state AS st, schooltype AS sc, grade AS gr, subjects AS su, files AS fl
                                                WHERE cu.country_id = co.id AND cu.state_id = st.id 
                                                AND cu.schooltype_id = sc.id AND cu.grade_id = gr.id 
                                                AND cu.subject_id = su.id AND cu.icon_id = fl.id '.$order_param);
                                $db->execute(array());
                            } else {
                                $db = DB::prepare('SELECT DISTINCT SQL_CALC_FOUND_ROWS cu.*, co.de, st.state, sc.schooltype, gr.grade, su.subject, fl.filename  
                                    FROM curriculum AS cu, countries AS co, state AS st, schooltype AS sc, grade AS gr, subjects AS su, files AS fl
                                                WHERE  (cu.country_id = co.id AND cu.state_id = st.id 
                                                AND cu.schooltype_id = sc.id AND cu.grade_id = gr.id 
                                                AND cu.subject_id = su.id AND cu.icon_id = fl.id 
                                                AND cu.creator_id = ? ) OR (
                                                cu.country_id = co.id AND cu.state_id = st.id 
                                                AND cu.schooltype_id = sc.id AND cu.grade_id = gr.id 
                                                AND cu.subject_id = su.id AND cu.icon_id = fl.id 
                                                AND cu.id IN (SELECT cure.curriculum_id 
                                                FROM curriculum_enrolments AS cure WHERE cure.status = 1 AND cure.group_id IN (SELECT ge.group_id FROM groups_enrolments AS ge WHERE ge.status = 1 AND ge.user_id = ?))) '.$order_param);
                                $db->execute(array($id, $id));
                            }
                            if ($paginator != ''){ 
                                set_item_total($paginator); //set item total based on FOUND ROWS()
                            }
                            while($result = $db->fetchObject()) {
                                //get statistics
                                $db2 = DB::prepare('SELECT clicks FROM statistics WHERE context_id = ? AND reference_id = ?');
                                $db2->execute(array($_SESSION['CONTEXT']['curriculum']->context_id, $result->id));
                                $stat_result  = $db2->fetchObject();
                                if (isset($stat_result->clicks)){
                                    $result->clicks = $stat_result->clicks;
                                } else {
                                    $result->clicks = 0;
                                }
                                
                                //get statistics
                                $curriculum[] = $result;  //result Data wird an setPaginator vergeben
                            } 
                break; 
            default:  break;
        }
        
        return $curriculum;
   }
   
   public function loadImportFormData($file){
        global $CFG, $USER;
        
        $import_folder = basename($file, ".curriculum");
        $zip = new ZipArchive;
        if ($zip->open($CFG->backup_root.''.$file) === TRUE) {
            $zip->extractTo($CFG->backup_root.$import_folder.'/');
            $zip->close();
        } 
        
        $xml = new DOMDocument( "1.0", "UTF-8" );
        $xml->load($CFG->backup_root.$import_folder.'/'.$import_folder.'.xml');
        foreach($xml->getElementsByTagName('curriculum') as $curriculum) {
                 $old_cur_id              = $curriculum->getAttribute('id');
                 $this->curriculum        = $curriculum->getAttribute('curriculum');
                 $this->description       = $curriculum->getAttribute('description');
                 $g = new Grade();
                 if ($g->load('grade', $curriculum->getAttribute('grade'))){ // else use preset $this->grade_id
                     $this->grade_id      = $g->id;
                 } 
                 $s = new Subject();
                 if ($s->load('subject', $curriculum->getAttribute('subject'))){
                      $this->subject_id   = $s->id;
                 }
                 $sch = new Schooltype();
                 if ($sch->load('schooltype', $curriculum->getAttribute('schooltype'))){
                     $this->schooltype_id = $sch->id; 
                 }

                 $this->state_id          = $curriculum->getAttribute('state_id');
                 $this->country_id        = $curriculum->getAttribute('country_id');
                 $this->icon_id           = $curriculum->getAttribute('icon_id');
                 $this->creator_id        = $USER->id;
       }
       delete_folder($CFG->backup_root.$import_folder);                        // Löscht temporäre Dateien
   }
   
   public function import($file, $preset = true){ 
        global $CFG, $USER;                                                         //--> s.    public function loadImportFormData($file) // code doppelt
        $import_folder  = basename($file, ".curriculum");
        $zip            = new ZipArchive;
        if ($zip->open($file) === TRUE) {
            $zip->extractTo($CFG->backup_root.$import_folder.'/');
            $zip->close();
        } 
        
        $xml = new DOMDocument( "1.0", "UTF-8" );
        $xml->load($CFG->backup_root.$import_folder.'/'.$import_folder.'.xml');
        if (isset($CFG->repository)){   // prüfen, ob Repository Plugin vorhanden ist.
            $ext_reference = get_plugin('repository', $CFG->settings->repository);
        }
        foreach($xml->getElementsByTagName('curriculum') as $curriculum) {
                $this->subject_id = 1;  //fallback todo: fallback options for all fields -> validator
                $old_cur_id              = $curriculum->getAttribute('id');
            if (!$preset) { // Werte aus backup nutzen -> sonst Werte des Formulars nutzen
                 $this->curriculum        = $curriculum->getAttribute('curriculum');
                 $this->description       = htmlspecialchars_decode($curriculum->getAttribute('description'), ENT_QUOTES);
                 $g = new Grade();
                 if ($g->load('grade', $curriculum->getAttribute('grade'))){ // else use preset $this->grade_id
                     $this->grade_id      = $g->id;
                 } 
                 $s = new Subject();
                 if ($s->load('subject', $curriculum->getAttribute('subject'))){
                     $this->subject_id   = $s->id;
                 } 
                 $sch = new Schooltype();
                 if ($sch->load('schooltype', $curriculum->getAttribute('schooltype'))){
                     $this->schooltype_id = $sch->id; 
                 }

                 $this->state_id          = $curriculum->getAttribute('state_id');
                 $this->country_id        = $curriculum->getAttribute('country_id');
                 //if file(icon_id) -> subject_image
                 $this->icon_id           = $curriculum->getAttribute('icon_id');
                 $this->creator_id        = $USER->id;
            }
            $c_id = $this->add();
            
            /* import content */
            $cur_content_nodes = getImmediateChildrenByTagName($curriculum, 'content');
            foreach ($cur_content_nodes as $ct) {
                $this->importContent($ct, 'curriculum', $c_id);
            }
            /* end import content */
            
            /* import glossar */
            $gl_content_nodes = getImmediateChildrenByTagName($curriculum, 'glossar');
            foreach ($gl_content_nodes as $gl) {
                $this->importContent($gl, 'glossar', $c_id);
            }
            /* end import glossar */
            
            /* import curriculum files */
            $f_content_nodes = getImmediateChildrenByTagName($curriculum, 'file');
            foreach($f_content_nodes as $cur_fil) {
                $this->importFile($cur_fil, $import_folder, $old_cur_id.'/', $c_id); //call import function
            }
            /* end import curriculum files */
            
        }                                                                                    //<-- s.    public function loadImportFormData($file) 
        foreach($xml->getElementsByTagName('terminal_objective') as $ter) {
            $t = new TerminalObjective();
            $old_ter_id            = $ter->getAttribute('id');
            $t->curriculum_id      = $c_id;
            $t->terminal_objective = htmlspecialchars_decode($ter->getAttribute('terminal_objective'), ENT_QUOTES);
            $t->description        = htmlspecialchars_decode($ter->getAttribute('description'), ENT_QUOTES);
            $t->order_id           = $ter->getAttribute('order_id');
            $t->repeat_interval    = $ter->getAttribute('repeat_interval');
            $t->color              = $ter->getAttribute('color');
            $t->type_id               = $ter->getAttribute('type_id');
            $t->creator_id         = $USER->id;
            $t_id                  = $t->add();                                      // add terminal objective
            $t_ref                 = $ter->getAttribute('ext_reference');
            if ($t_ref != '' AND isset($ext_reference)){
                $ext_reference->setReference(0, $t_id, $t_ref);                      // add ext. reference for this terminal objective
            }
            /* ter files */
            $ter_file_nodes = getImmediateChildrenByTagName($ter, 'file');
            foreach($ter_file_nodes as $ter_fil) {
                $this->importFile($ter_fil, $import_folder, $old_cur_id.'/'.$old_ter_id.'/', $c_id, $t_id); //call import function
            } 
            
            /*ter references*/
            $ter_refernce_nodes = getImmediateChildrenByTagName($ter, 'reference');
            foreach($ter_refernce_nodes as $ter_ref) {
                $this->importReference($ter_ref, 'terminal_objective', $t_id); //call import function
            }

            /* enabling objectives*/   
            foreach($ter->getElementsByTagName('enabling_objective') as $ena) {
                $e = new EnablingObjective();
                $old_ena_id                  = $ena->getAttribute('id');
                $e->curriculum_id            = $c_id;
                $e->terminal_objective_id    = $t_id;
                $e->enabling_objective       = htmlspecialchars_decode($ena->getAttribute('enabling_objective'), ENT_QUOTES);
                $e->description              = htmlspecialchars_decode($ena->getAttribute('description'), ENT_QUOTES);
                $e->order_id                 = $ena->getAttribute('order_id');
                $e->repeat_interval          = $ena->getAttribute('repeat_interval');
                $e->creator_id               = $USER->id;
                $e_id                        = $e->add();
                $e_ref                       = $ena->getAttribute('ext_reference');   
                if ($e_ref != '' AND isset($ext_reference)){
                    $ext_reference->setReference(1, $e_id, $e_ref);                      // add ext. reference for this enabling objective
                }
                
                /* ena files*/
                $ena_file_nodes = getImmediateChildrenByTagName($ena, 'file');
                foreach($ena_file_nodes as $ena_fil) {
                    $this->importFile($ena_fil, $import_folder, $old_cur_id.'/'.$old_ter_id.'/'.$old_ena_id.'/', $c_id, $t_id, $e_id); //call import function
                } 
                
                /*ena references*/
                $ena_refernce_nodes = getImmediateChildrenByTagName($ena, 'reference');
                foreach($ena_refernce_nodes as $ena_ref) {
                    $this->importReference($ena_ref, 'enabling_objective', $e_id); //call import function
                }
            }     
        }
        delete_folder($CFG->backup_root.$import_folder);                        // Löscht temporäre Dateien
        unlink($file);
    }
    private function importReference($ref_node, $context, $parent_id){
        $reference = new Reference();
        $reference->unique_id       = $ref_node->getAttribute('unique_id');
        $reference->reference_id    = $parent_id;
        $gr        = new Grade();
        $gr->load('grade', $ref_node->getAttribute('grade'));
        $reference->grade_id        = $gr->id;
        $reference->context_id      = $_SESSION['CONTEXT'][$context]->id;
        $reference->import();
        $content_nodes = getImmediateChildrenByTagName($ref_node, 'content');
        foreach ($content_nodes as $c) {
            $this->importContent($c, 'reference', $parent_id);
        }
    }
   
   private function importFile($file_node, $import_folder, $old_path, $cur_id = null, $ter_id = null, $ena_id = null) {
        global $USER, $CFG;
        $f = new File();
        $f->title                   = $file_node->getAttribute('title');
        $f->filename                = $file_node->getAttribute('filename');
        $f->description             = $file_node->getAttribute('description');
        $f->author                  = $file_node->getAttribute('author');
        $f->license                 = $file_node->getAttribute('license');
        $f->type                    = $file_node->getAttribute('type');
        
        if ($f->type == '.url' OR $f->type == 'external'){
            $f->path                = $f->filename;             //path == filename on urls and external links
        } else {
            $path                   = $cur_id.'/';
            if ($ter_id != null){   
                $path               = $path.$ter_id.'/';
            }
            if ($ena_id != null){   
                $path               = $path.$ena_id.'/';
            }
            $f->path                = $path; //$cur_id.'/'.$ter_id.'/'.$ena_id.'/';//$fil->getAttribute('path');
        }
        
        $f->context_id              = $file_node->getAttribute('context_id');
        $f->file_context            = $file_node->getAttribute('file_context');
        $f->creator_id              = $USER->id;
        $f->curriculum_id           = $cur_id;
        $f->terminal_objective_id   = $ter_id;
        $f->enabling_objective_id   = $ena_id;
        $f->add();
        if ($f->type != '.url' AND $f->type != 'external'){
            silent_mkdir($CFG->curriculum_root.$f->path);
            copy($CFG->backup_root.$import_folder.'/'.$old_path.$f->filename, $CFG->curriculum_root.$f->path.$f->filename);
        } 
   } 
   
    private function importContent($content_node, $context, $cur_id = null/*, $ter_id = null, $ena_id = null*/){
        global $USER;
        $content = new Content();
        $content->title         = getImmediateChildrenByTagName($content_node, 'title')[0]->nodeValue;
        $content->content       = htmlspecialchars_decode(getImmediateChildrenByTagName($content_node, 'text')[0]->nodeValue, ENT_QUOTES);
        $content->file_context  = 1;
        $content->creator_id    = $USER->id;
        $content->context_id    = $_SESSION['CONTEXT'][$context]->id;
        $content->reference_id  = $cur_id;
        $content->add();
    }
    
   public function checkEnrolment($status = '1'){
        $db = DB::prepare('SELECT ce.*, gp.groups, ins.institution FROM curriculum_enrolments AS ce, 
							groups AS gp,
							institution AS ins 
						WHERE ce.curriculum_id = ? 
						AND ce.status = ? 
						AND ce.group_id = gp.id
						AND gp.institution_id = ins.id ORDER BY ins.institution'); //ORDER BY institution for statistic chart
        $db->execute(array($this->id, $status));
        
        while($result = $db->fetchObject()) { 
            $ce[] = $result; 
        } 
        if (isset($ce)){
            return $ce; 
        } else {
            return false;
        }
    } 
    
    /* Used by reference view: Get group based on curriculum*/
    public function getGroupsByUserAndCurriculum($user_id, $curriculum_id = null, $status = 1) {
        if ($curriculum_id == null){
            $curriculum_id = $this->id;
        }
        $db = DB::prepare('SELECT ce.group_id FROM curriculum_enrolments AS ce, groups_enrolments AS ge
						WHERE ce.curriculum_id = ?
						AND ce.status = ?
						AND ce.group_id = ge.group_id AND ge.user_id = ?'); //ORDER BY institution for statistic chart
        $db->execute(array($curriculum_id, $status, $user_id));
        
        while($result = $db->fetchObject()) { 
            $groups[] = $result; 
        } 
        if (isset($groups)){
            return $groups; 
        } else {
            return false;
        }
    }
   
   /**
    * function used during the install process to set up creator id to new admin
    * @return boolean
    */
    public function dedicate(){ // only use during install
        $db = DB::prepare('UPDATE curriculum SET creator_id = ?');
        if ($db->execute(array($this->creator_id))){
            DB::prepare('UPDATE curriculum_enrolments SET creator_id = ?');
            return $db->execute(array($this->creator_id));
        }
    }
    
    public function getFieldArray($id, $dependency = 'curriculum_content', $field ='id'){
        $ids = array();
        switch ($dependency) {
            case 'curriculum_content':  $db     = DB::prepare('SELECT ct.'.$field.' FROM content AS ct, content_subscriptions AS cts WHERE  cts.context_id = ?
                                                        AND cts.reference_id = ?
                                                        AND cts.content_id = ct.id');
                                        $db->execute(array($_SESSION['CONTEXT']['curriculum']->context_id, $id));
                                        while($r = $db->fetchObject()) { 
                                          $ids[] = $r->$field;
                                        }

                break;
            case 'terminal_objectives': $db     = DB::prepare('SELECT '.$field.' FROM terminalObjectives WHERE curriculum_id = ?');
                                        $db->execute(array($id));
                                        while($r = $db->fetchObject()) { 
                                          $ids[] = $r->$field;
                                        }
                break;
            case 'enabling_objectives': $db     = DB::prepare('SELECT '.$field.' FROM enablingObjectives WHERE curriculum_id = ?');
                                        $db->execute(array($id));
                                        while($r = $db->fetchObject()) { 
                                          $ids[] = $r->$field;
                                        }
                break;

            default:
                break;
        }
        
        if (isset($ids)){
            return $ids;
        } else {
            return false; 
        }
    }
    
    public function loadConfig($dependency = 'reference'){
        switch ($dependency) {
            case 'reference':   $db = DB::prepare('SELECT reference_id FROM config_curriculum WHERE curriculum_id = ? AND context_id = 26');
                                $db->execute(array($this->id));
                                $config_curriculum = array();
                                while($result = $db->fetchObject()) { 
                                        $config_curriculum[] = $result->reference_id; 
                                }
                break;

            default:    $db = DB::prepare('SELECT * FROM config_curriculum WHERE curriculum_id = ?');
                        $db->execute(array($this->id));
                        $config_curriculum = array();
                        while($result = $db->fetchObject()) { 
                                $config_curriculum[] = $result; 
                        }
                break;
        }
        if (isset($config_curriculum)){
            return $config_curriculum;
        } else {
            return false;
        }
    }
    
}