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
    /**
     * array which holds terminal objectives of this curriculum
     * @var type 
     */
    public $terminal_objectives; 
    /**
     * add curriculum to db
     * @return mixed 
     */
    public function add(){
        global $USER;
        checkCapabilities('curriculum:add', $USER->role_id);
        $db = DB::prepare('INSERT INTO curriculum (curriculum, description, grade_id, subject_id, schooltype_id, state_id, icon_id, country_id, creator_id) 
                                            VALUES (?,?,?,?,?,?,?,?,?)');
        $db->execute(array($this->curriculum, $this->description, $this->grade_id, $this->subject_id, $this->schooltype_id, $this->state_id, $this->icon_id, $this->country_id, $USER->id));
        return DB::lastInsertId();
    }
    
    /**
     * Update curriculum in db
     * @return boolean 
     */
    public function update(){
        global $USER;
        checkCapabilities('curriculum:update', $USER->role_id); 
        $db = DB::prepare('UPDATE curriculum SET curriculum = ?, description = ?, grade_id = ?, subject_id = ?, schooltype_id = ?, state_id = ?, icon_id = ?, country_id = ?
                                                WHERE id = ?');
        return $db->execute(array($this->curriculum, $this->description, $this->grade_id, $this->subject_id, $this->schooltype_id, $this->state_id, $this->icon_id, $this->country_id, $this->id));
    }
    
    /**
     * Delete curriculum from db
     * @return mixed 
     */
    public function delete(){
        global $USER, $PAGE;
        checkCapabilities('curriculum:delete', $USER->role_id);
        //check if groups are enroled in this curriculum 
        $enrdb = DB::prepare('SELECT id FROM curriculum_enrolments WHERE curriculum_id=?');
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
        $this->creation_time    = $result->creation_time;
        $this->creator_id       = $result->creator_id;
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
        $order_param = orderPaginator($paginator, array('curriculum' => 'cu',
                                                        'description' => 'cu',
                                                        'de'         => 'co',
                                                        'state'      => 'st',
                                                        'schooltype' => 'sc',
                                                        'grade'      => 'gr',
                                                        'subject'    => 'su'));  
        
        $curriculum = array();
        switch ($dependency) {
            case 'group':   $db = DB::prepare('SELECT cu.id, cu.curriculum, cu.description, fl.filename, su.subject, 
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
                break;
            case 'creator': $db = DB::prepare('SELECT cu.id, cu.curriculum, cu.description, gr.grade  
                                                FROM curriculum AS cu, grade AS gr
                                                WHERE cu.creator_id = ? AND gr.id = cu.grade_id '.$order_param);
                            $db->execute(array($id));
                            while($result = $db->fetchObject()) {
                                        $curriculum[] = $result; 
                            }
                break; 
            case 'user':    if (checkCapabilities('curriculum:showAll', $USER->role_id, false)){
                                $db = DB::prepare('SELECT DISTINCT cu.*, co.de, st.state, sc.schooltype, gr.grade, su.subject, fl.filename  
                                    FROM curriculum AS cu, countries AS co, state AS st, schooltype AS sc, grade AS gr, subjects AS su, files AS fl
                                                WHERE cu.country_id = co.id AND cu.state_id = st.id 
                                                AND cu.schooltype_id = sc.id AND cu.grade_id = gr.id 
                                                AND cu.subject_id = su.id AND cu.icon_id = fl.id '.$order_param);
                                $db->execute(array());
                            } else {
                                $db = DB::prepare('SELECT DISTINCT cu.*, co.de, st.state, sc.schooltype, gr.grade, su.subject, fl.filename  
                                    FROM curriculum AS cu, groups_enrolments AS ce, curriculum_enrolments AS cure, countries AS co, state AS st, schooltype AS sc, grade AS gr, subjects AS su, files AS fl
                                                WHERE  (cu.country_id = co.id AND cu.state_id = st.id 
                                                AND cu.schooltype_id = sc.id AND cu.grade_id = gr.id 
                                                AND cu.subject_id = su.id AND cu.icon_id = fl.id 
                                                AND cu.creator_id = ? ) OR (
                                                cu.country_id = co.id AND cu.state_id = st.id 
                                                AND cu.schooltype_id = sc.id AND cu.grade_id = gr.id 
                                                AND cu.subject_id = su.id AND cu.icon_id = fl.id 
                                                AND cu.id = cure.curriculum_id
                                                AND cure.group_id = ce.group_id
                                                AND ce.status = 1
                                                AND ce.user_id = ?) '.$order_param);
                                $db->execute(array($id, $id));
                            }
                            while($result = $db->fetchObject()) {
                                $curriculum[] = $result; //result Data wird an setPaginator vergeben
                            } 
                break; 
            default:  break;
        }

        return $curriculum;
   }
   
   public function getNiveau(){  
       $db = DB::prepare('SELECT cn.* FROM curriculum_niveaus AS cn
                                                WHERE cn.base_curriculum_id = (SELECT base_curriculum_id FROM curriculum_niveaus
                                                WHERE curriculum_id = ?) ORDER BY level');
                            $db->execute(array($this->id));
                            $niveaus = array();
                            while($result = $db->fetchObject()) {
                                        $niveaus[] = $result; 
                            }
                            return $niveaus;
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
        if (isset($CFG->repository)){ // prüfen, ob Repository Plugin vorhanden ist.
            $ext_reference = get_plugin('repository', $CFG->settings->repository);
        }
        foreach($xml->getElementsByTagName('curriculum') as $curriculum) {
                $old_cur_id              = $curriculum->getAttribute('id');
            if (!$preset) { // Werte aus backup nutzen -> sonst Werte des Formulars nutzen
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
                 //if file(icon_id) -> subject_image
                 $this->icon_id           = $curriculum->getAttribute('icon_id');
                 $this->creator_id        = $USER->id;
            }
            $c_id = $this->add();
       }                                                                                    //<-- s.    public function loadImportFormData($file) 
       foreach($xml->getElementsByTagName('terminal_objective') as $ter) {
           $t = new TerminalObjective();
           $old_ter_id            = $ter->getAttribute('id');
           $t->curriculum_id      = $c_id;
           $t->terminal_objective = $ter->getAttribute('terminal_objective');
           $t->description        = $ter->getAttribute('description');
           $t->order_id           = $ter->getAttribute('order_id');
           $t->repeat_interval    = $ter->getAttribute('repeat_interval');
           $t->color              = $ter->getAttribute('color');
           $t->creator_id         = $USER->id;
           $t_id                  = $t->add();                                      // add terminal objective
           $t_ref                 = $ter->getAttribute('ext_reference');
           if ($t_ref != '' AND isset($ext_reference)){
               $ext_reference->setReference(0, $t_id, $t_ref);                      // add ext. reference for this terminal objective
           }
           /* ter files */
           $ter_file_nodes = getImmediateChildrenByTagName($ter, 'file');
           foreach($ter_file_nodes as $ter_fil) {
                    $f = new File();
                    $f->title                   = $ter_fil->getAttribute('title');
                    $f->filename                = $ter_fil->getAttribute('filename');
                    $f->description             = $ter_fil->getAttribute('description');
                    $f->author                  = $ter_fil->getAttribute('author');
                    $f->license                 = $ter_fil->getAttribute('license');
                    $f->type                    = $ter_fil->getAttribute('type');
                    $f->path                    = $c_id.'/'.$t_id.'/';//$fil->getAttribute('path');
                    $f->context_id              = $ter_fil->getAttribute('context_id');
                    $f->file_context            = $ter_fil->getAttribute('file_context');
                    $f->creator_id              = $USER->id;
                    $f->curriculum_id           = $c_id;
                    $f->terminal_objective_id   = $t_id;
                    $f->enabling_objective_id   = NULL;
                    $f->add();
                    if ($f->type != '.url'){
                        silent_mkdir($CFG->curriculum_root.$f->path);
                        copy($CFG->backup_root.$import_folder.'/'.$old_cur_id.'/'.$old_ter_id.'/'.$f->filename, $CFG->curriculum_root.$f->path.$f->filename);
                    }
                }
            /* enabling objectives*/
                
           foreach($ter->getElementsByTagName('enabling_objective') as $ena) {
                $e = new EnablingObjective();
                $old_ena_id                  = $ena->getAttribute('id');
                $e->curriculum_id            = $c_id;
                $e->terminal_objective_id    = $t_id;
                $e->enabling_objective       = $ena->getAttribute('enabling_objective');
                $e->description              = $ena->getAttribute('description');
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
                    $f = new File();
                    $f->title                   = $ena_fil->getAttribute('title');
                    $f->filename                = $ena_fil->getAttribute('filename');
                    $f->description             = $ena_fil->getAttribute('description');
                    $f->author                  = $ena_fil->getAttribute('author');
                    $f->license                 = $ena_fil->getAttribute('license');
                    $f->type                    = $ena_fil->getAttribute('type');
                    $f->path                    = $c_id.'/'.$t_id.'/'.$e_id.'/';//$fil->getAttribute('path');
                    $f->context_id              = $ena_fil->getAttribute('context_id');
                    $f->file_context            = $ena_fil->getAttribute('file_context');
                    $f->creator_id              = $USER->id;
                    $f->curriculum_id           = $c_id;
                    $f->terminal_objective_id   = $t_id;
                    $f->enabling_objective_id   = $e_id;
                    $f->add();
                    if ($f->type != '.url'){
                        silent_mkdir($CFG->curriculum_root.$f->path);
                        copy($CFG->backup_root.$import_folder.'/'.$old_cur_id.'/'.$old_ter_id.'/'.$old_ena_id.'/'.$f->filename, $CFG->curriculum_root.$f->path.$f->filename);
                    }
                } 
            }     
        }
       
        delete_folder($CFG->backup_root.$import_folder);                        // Löscht temporäre Dateien
        unlink($file);
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
}