<?php
/**
* 
* @abstract This file is part of curriculum - http://www.joachimdieterich.de
* @package core
* @filename file.class.php
* @copyright 2013 Joachim Dieterich
* @author Joachim Dieterich
* @date 2013.06.09 21:06
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
class File {
    /**
     * id of file
     * @var int
     */
    public $id;
    /**
     * title of file
     * @var string 
     */
    public $title;
    /**
     * filename
     * @var string
     */
    public $filename; 
    /**
     * Array of file versions
     * @var string 
     */

    public $file_version;
    /**
     * Description of file
     * @var string
     */
    public $description; 
    /**
     * filetype
     * @var string 
     */
    public $type; 
    /**
     * filepath
     * @var string 
     */
    public $path; 
    public $full_path;
    /**
     * id of context
     * @var int 
     */
    public $context_id; 
    /**
     * context depending path
     * @var string 
     */
    public $context_path; 
    public $file_context;
    /**
     * timestamp when file was created
     * @var timestamp
     */
    public $creation_time; 
    /**
     * ID of User who created this file
     * @var int
     */
    public $creator_id; 
    /**
     * firstname of creator
     * @var string 
     */
    public $firstname; 
    /**
     * lastname of creator
     * @var string 
     */
    public $lastname;
    /**
     * author of file 
     * @since 0.9
     * @var string
     */
    public $author; 
        /**
     * license
     * @since 0.9
     * @var string
     */
    public $license; 
    /**
     * id of curriculum
     * @var int
     */
    public $curriculum_id; 
    /**
     * id of terminal objective
     * @var int
     */
    public $terminal_objective_id; 
    /**
     * id of enabling objective
     * @var int
     */
    public $enabling_objective_id; 
    public $hits;
    /**
     * add file
     * @return mixed 
     */
    public function add(){
        global $USER, $LOG;
        if (checkCapabilities('file:upload', $USER->role_id, false) OR checkCapabilities('file:uploadAvatar', $USER->role_id, false));
        /* SET cur_id, ter_id and ena_id NULL if not int > 0*/
        if ($this->curriculum_id < 1)        { $this->curriculum_id         = NULL; }
        if ($this->terminal_objective_id < 1){ $this->terminal_objective_id = NULL; }
        if ($this->enabling_objective_id < 1){ $this->enabling_objective_id = NULL; }
        $db             = DB::prepare('INSERT INTO files (title, filename, description, author, license, type, path, context_id, file_context, creator_id, cur_id, ter_id, ena_id) 
                            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)');
        if($db->execute(array($this->title, $this->filename, $this->description, $this->author, $this->license, $this->type, $this->path, $this->context_id, $this->file_context, $USER->id, $this->curriculum_id, $this->terminal_objective_id, $this->enabling_objective_id))){
            $lastInsertId = DB::lastInsertId();                 //get last insert id bevor using db again!
            $LOG->add($USER->id, 'uploadframe.php', dirname(__FILE__), 'Context: '.$this->context_id.' Upload: '.$this->path.''.$this->filename);
            $_SESSION['PAGE']->message[] = array('message' => 'Datei erfolgreich hochgeladen', 'icon' => 'fa-file text-success');
            return $lastInsertId; 
        } else {
            return false; 
        } 
    }

    /**
     * Update file
     * @return boolean 
     */
    public function update(){
        global $USER;
        checkCapabilities('file:update', $USER->role_id);
        $db = DB::prepare('UPDATE files SET title = ?,  description = ?, license = ?, author = ?, file_context = ?, context_id = ? WHERE id = ?');
        return $db->execute(array($this->title,  $this->description, $this->license, $this->author, $this->file_context, $this->context_id, $this->id));
    }

    /**
     * Delete file
     * @return mixed 
     */
    public function delete(){
        global $CFG, $USER, $LOG;
        checkCapabilities('file:delete', $USER->role_id);
        $this->load();
        $db = DB::prepare('DELETE FROM files WHERE id=?');
        if ($db->execute(array($this->id))){/* unlink file*/
            $co = new Context();
            $co->resolve('id', $this->context_id);
            $path = $CFG->curriculumdata_root.$co->path;

            if ($path) {
                $LOG->add($USER->id, 'uploadframe.php', dirname(__FILE__), 'Context: '.$this->context_id.' Delete: '.$this->path.''.$this->filename);
                if ($this->type == ".url"){ // bei urls muss keine Datei gelöscht werden 
                    return true;
                } else {
                    return $this->deleteVersions($path); 
                }   
            }
        } else {
            return false;
        }
    } 

    public function deleteVersions($path){
        $extension_pos = strrpos($this->filename, '.'); // find position of the last dot, so where the extension starts
        $thumb_xt = substr($this->filename, 0, $extension_pos) . '_xt.png';
        $thumb_t  = substr($this->filename, 0, $extension_pos) . '_t.png';
        $thumb_qs = substr($this->filename, 0, $extension_pos) . '_qs.png';
        $thumb_xs = substr($this->filename, 0, $extension_pos) . '_xs.png';
        $thumb_s  = substr($this->filename, 0, $extension_pos) . '_s.png';
        $thumb_m  = substr($this->filename, 0, $extension_pos) . '_m.png';
        $thumb_l  = substr($this->filename, 0, $extension_pos) . '_l.png';

        if (file_exists($path.$thumb_xt))           { unlink($path.$thumb_xt); }
        if (file_exists($path.$thumb_t))            { unlink($path.$thumb_t); }
        if (file_exists($path.$thumb_qs))           { unlink($path.$thumb_qs); }
        if (file_exists($path.$thumb_xs))           { unlink($path.$thumb_xs); }
        if (file_exists($path.$thumb_s))            { unlink($path.$thumb_s); }
        if (file_exists($path.$thumb_m))            { unlink($path.$thumb_m); }
        if (file_exists($path.$thumb_l))            { unlink($path.$thumb_l); }
        if (file_exists($path.$this->filename))     { return (unlink($path.$this->filename)); }
    }

    /**
     * Load file with id $this->id 
     */
    public function load($id = null){
        if ($id == null) { $id = $this->id; }
        $db = DB::prepare('SELECT fl.*, ct.path AS context_path FROM files AS fl, context AS ct WHERE fl.context_id = ct.context_id AND fl.id = ?');
        $db->execute(array($id));
        $result = $db->fetchObject();
        if (isset($result->id)){
            $this->id                    = $result->id;
            $this->title                 = $result->title;
            $this->filename              = $result->filename;
            if (empty($this->title)){
               $this->title             =  $result->filename;
            }
            $this->description           = $result->description;
            $this->author                = $result->author;
            $this->license               = $result->license;
            $this->path                  = $result->path;
            $this->type                  = $result->type;
            $this->context_id            = $result->context_id;
            $this->file_context          = $result->file_context;
            if (isset($result->context_path)){
                $this->context_path      = $result->context_path;
            }
            $this->file_version          = $this->getFileVersions(); // muss unter context_path stehen!
            $this->full_path             = $this->context_path.$this->path.$this->filename;    
            $this->curriculum_id         = $result->cur_id;
            $this->terminal_objective_id = $result->ter_id;
            $this->enabling_objective_id = $result->ena_id;
            $this->creation_time         = $result->creation_time;
            $this->creator_id            = $result->creator_id;   
            if (isset($result->hits)){
                $this->hits              = $result->hits;
            }
            return true;
        } else {
            return false;
        }
    }
    
    public function addFileToken(){
        $fileToken = getToken();
        $db        = DB::prepare('INSERT INTO file_token (file_id, token) VALUES (?,?)');
        $db->execute(array($this->id, $fileToken));
         
        return $fileToken;
    }
    
    public function getFileUrl(){
        global $CFG;
        if ($this->type == '.url'){
            return false;
        }
        return $CFG->access_file_url.''.$this->context_path.''.$this->path.''.rawurlencode($this->filename);
    }
    
    public function getFileID($fileToken){
        $db     = DB::prepare('SELECT file_id FROM file_token WHERE token = ?');
        $db->execute(array($fileToken));
        $result = $db->fetchObject();
        if ($result){
            return $result->file_id;
        } else {
            return $result;
        }
    }
   
    public function deleteFileToken($token){
        $fileToken = getToken();
        $db = DB::prepare('DELETE FROM file_token WHERE token=?');
        $db->execute(array($token));
    }

    /**
     * returns existing file version
     * @return array of strings
     */
    public function getFileVersions(){
        global $CFG;
        $extension_pos = strrpos($this->filename, '.'); // find position of the last dot -> file extention
        $size = array("xt","t","qs","xs","s","m","l");
        foreach ($size as $value) {
           $thumb = substr($this->filename, 0, $extension_pos) . '_'.$value.'.png'; 
            if (file_exists($CFG->curriculumdata_root.$this->context_path.$this->path.$thumb)){ 
                $result[$value] = array("filename"  => $thumb, 
                                        "full_path" => $this->context_path.$this->path.$thumb, 
                                        "size"      => $this->getHumanFileSize($CFG->curriculumdata_root.$this->context_path.$this->path.$thumb)
                                       ); 
            }
        }
        
        if (isset($result)) {
            return $result; 
        } else {
            return false;
        }
    }
    
    /**
     * returns url of thumbnail (if exists) else false
     * @global object $CFG
     * @return string or boolean
     */
    public function getThumb(){
        global $CFG;
        $extension_pos = strrpos($this->filename, '.'); // find position of the last dot, so where the extension starts
        $thumb_t       = substr($this->filename, 0, $extension_pos) . '_t.png';
        if (file_exists($CFG->curriculumdata_root.$this->context_path.$this->path.$thumb_t)) {     
            return  $CFG->access_file_url.''.$this->context_path.''.$this->path.''.$thumb_t; 
        } else {
            return false;
        }
         
    }
    /**
     * 
     * @global object $CFG
     * @param string $path
     * @return string
     */
    public function getHumanFileSize($path = null){
        global $CFG;
        
        if (isset($path) AND $this->type != '.url'){
            if (file_exists($path)){
                return human_filesize(filesize($path));
            }
        } else {
            if (file_exists($CFG->curriculumdata_root.$this->full_path)){
                return human_filesize(filesize($CFG->curriculumdata_root.$this->full_path));
            }
        }
    }
    /**
     * get Solutions depending on dependency
     * @param string $dependency
     * @param inst $course_id
     * @param string $user_ids 
     */
    public function getSolutions($dependency = null, $user_ids = null, $reference_id = null){
        global $USER;
        checkCapabilities('file:getSolutions', $USER->role_id, false);
        switch ($dependency) {
            case 'course':      if (is_array($user_ids)){
                                    $user_ids = implode(", ", $user_ids);
                                }

                                $db = DB::prepare('SELECT fl.*, us.firstname, us.lastname FROM files AS fl, users AS us
                                    WHERE fl.cur_id = ? AND fl.creator_id IN ('.$user_ids.')
                                    AND fl.creator_id = us.id AND fl.context_id = 4 ORDER BY us.lastname');
                                $db->execute(array($reference_id));  
                break;
            case 'objective':   if (is_array($user_ids)){
                                   $user_ids = implode(", ", $user_ids);
                                }
                                $db = DB::prepare('SELECT fl.*, us.firstname, us.lastname FROM files AS fl, users AS us
                                    WHERE fl.ena_id = ? AND fl.creator_id IN ('.$user_ids.')
                                    AND fl.creator_id = us.id AND fl.context_id = 4 ORDER BY us.lastname');
                                $db->execute(array($reference_id));  
                    break;

            case 'artefacts':   if (is_array($user_ids)){
                                    $user_ids = implode(", ", $user_ids);
                                }
                                $db = DB::prepare('SELECT fl.*, us.firstname, us.lastname FROM files AS fl, users AS us
                                    WHERE fl.creator_id IN ('.$user_ids.')
                                    AND fl.creator_id = us.id');
                                $db->execute();  
                break;

            default:        break;
        }
        $files = array(); //Array of files
        while($result = $db->fetchObject()) { 
                $this->id                    = $result->id;
                $this->title                 = $result->title;
                $this->filename              = $result->filename;
                if (empty($this->title)){
                    $this->title             = $result->filename;
                 }
                $this->description           = $result->description;
                $this->author                = $result->author;
                $this->license               = $result->license;
                $this->path                  = $result->path;
                $this->type                  = $result->type;
                $this->context_id            = $result->context_id;
                $this->file_context          = $result->file_context;
                $this->curriculum_id         = $result->cur_id;
                $this->terminal_objective_id = $result->ter_id;
                $this->enabling_objective_id = $result->ena_id;
                $this->creation_time         = $result->creation_time;
                $this->creator_id            = $result->creator_id;
                $this->firstname             = $result->firstname;
                $this->lastname              = $result->lastname;
                if (isset($result->hits)){
                    $this->hits              = $result->hits;
                }
                $files[] = clone $this;        //it has to be clone, to get the object and not the reference
        } 
        if (isset($files)) {  
            return $files;
        } else {return false;}
    }

    /**
     * get files depending on dependency
     * @param string $dependency
     * @param int $id
     * @param string $paginaor
     * @param array $params
     * @return array of file objects|boolean 
     */
    public function getFiles($dependency = null, $id = null, $paginator = '', $params = array() ){
        global $USER, $CFG;
        $externalFiles = null;
        foreach($params as $key => $val) {
            $$key = $val;
        }
        $order_param = orderPaginator($paginator, array('id'            => 'fl',
                                                        'filename'      => 'fl',
                                                        'title'         => 'fl', 
                                                        'description'   => 'fl',
                                                        'creation_time' => 'fl',
                                                        'author'        => 'fl')); 
        switch ($dependency) {
            case 'context':             $db = DB::prepare('SELECT fl.*, ct.path AS context_path FROM files AS fl, context AS ct
                                                        WHERE fl.context_id = ? AND fl.context_id = ct.context_id '.$order_param);
                $db->execute(array($id));
                break;
            case 'userfiles':           $db = DB::prepare('SELECT fl.*, ct.path AS context_path FROM files AS fl, context AS ct
                                                        WHERE fl.creator_id = ? AND fl.context_id = ct.context_id '.$order_param);
                $db->execute(array($id));
                break;
            case 'curriculum':          $db = DB::prepare('SELECT fl.*, ct.path AS context_path FROM files AS fl, context AS ct
                                                        WHERE fl.cur_id = ? AND fl.context_id = 2 AND fl.context_id = ct.context_id '.$order_param);
                $db->execute(array($id));
                break;
            case 'terminal_objective':  $db = DB::prepare('SELECT DISTINCT fl.*, ct.path AS context_path FROM files AS fl, context AS ct
                                                            WHERE fl.ter_id = ? AND ISNULL(fl.ena_id) AND fl.context_id = 2 AND fl.context_id = ct.context_id
                                                            AND( fl.file_context = 1 /*Global Material*/
                                                            OR ( fl.file_context = 2 AND fl.creator_id = ANY (SELECT user_id from institution_enrolments WHERE institution_id = ? )) /*Institutional Material*/
                                                            OR ( fl.file_context = 3 AND fl.creator_id = ANY (SELECT user_id from groups_enrolments WHERE group_id = ANY (Select group_id from groups_enrolments WHERE user_id = ?))) /*Group Material*/
                                                            OR ( fl.file_context = 4 AND fl.creator_id = ?)) /*My Material*/
                                                            ORDER BY fl.file_context ASC');
                $db->execute(array($id, $USER->institution_id, $USER->id, $USER->id));
                break;
            case 'enabling_objective':  $db = DB::prepare('SELECT fl.*, ct.path AS context_path FROM files AS fl, context AS ct
                                                            WHERE fl.ena_id = ? AND fl.context_id = 2 AND fl.context_id = ct.context_id 
                                                              AND( fl.file_context = 1 /*Global Material*/
                                                              OR ( fl.file_context = 2 AND fl.creator_id = ANY (SELECT user_id from institution_enrolments WHERE institution_id = ? )) /*Institutional Material*/
                                                              OR ( fl.file_context = 3 AND fl.creator_id = ANY (SELECT user_id from groups_enrolments WHERE group_id = ANY (SELECT group_id FROM groups_enrolments WHERE user_id = ?))) /*Group Material*/
                                                              OR ( fl.file_context = 4 AND fl.creator_id = ?)) /*My Material*/
                                                         ORDER BY fl.file_context ASC');
                $db->execute(array($id, $USER->institution_id, $USER->id, $USER->id));
                break;                  
            
            case 'avatar':              $db = DB::prepare('SELECT fl.*, ct.path AS context_path FROM files AS fl, context AS ct
                                                        WHERE fl.creator_id = ? AND fl.context_id = 3 AND fl.context_id = ct.context_id '.$order_param);
                $db->execute(array($id));
                break;
            case 'id':            $db = DB::prepare('SELECT fl.*, ct.path AS context_path FROM files AS fl, context AS ct
                                                        WHERE fl.ena_id = ? AND fl.creator_id = ? AND fl.context_id = ct.context_id AND fl.file_context <> 4 '.$order_param); // file_context <> 4 --> don't show personal files
                $db->execute(array($id, $user_id)); //$user_id from $params
                break;
            case 'solution':            $db = DB::prepare('SELECT fl.*, ct.path AS context_path FROM files AS fl, context AS ct
                                                        WHERE fl.cur_id = ? AND fl.context_id = 4 AND fl.context_id = ct.context_id '.$order_param);
                $db->execute(array($id));
                break;
            case 'user':                $db = DB::prepare('SELECT fl.*, ct.path AS context_path FROM files AS fl, context AS ct
                                                        WHERE fl.creator_id = ? AND fl.context_id = ct.context_id '.$order_param);             
                $db->execute(array($id));
                break;
            case 'backup':              $db = DB::prepare('SELECT DISTINCT fl.*, ct.path AS context_path FROM files AS fl, context AS ct, curriculum_enrolments AS ce
                                                        WHERE fl.context_id = 8 AND fl.context_id = ct.context_id AND fl.cur_id = ce.curriculum_id
                                                        AND ce.group_id = ANY (SELECT gr.group_id FROM groups_enrolments AS gr WHERE gr.user_id =  ?) '.$order_param);  
                $db->execute(array($id));
                break;

            default : break; 
        }                      

        $files = array(); //Array of files
        while($result = $db->fetchObject()) { 
                $this->id                    = $result->id;
                $this->title                 = $result->title;
                $this->filename              = $result->filename;
                if (empty($this->title)){
                    $this->title             =  $result->filename;
                 }
                $this->description           = $result->description;
                $this->author                = $result->author;
                $this->license               = $result->license;
                $this->type                  = $result->type;
                if ($this->type != '.url'){
                    $this->path              = $result->path;
                } else {
                    $this->path              = $result->filename;
                }
                
                $this->context_id            = $result->context_id;
                if (isset($result->context_path)){
                    $this->context_path      = $result->context_path;
                }
                
                $this->full_path             = $this->context_path.$this->path.$this->filename;     //??? context path wird über die sql eigentlich schon ermittelt
                $this->file_context          = $result->file_context;
                $this->curriculum_id         = $result->cur_id;
                $this->terminal_objective_id = $result->ter_id;
                $this->enabling_objective_id = $result->ena_id;
                $this->creation_time         = $result->creation_time;
                $this->creator_id            = $result->creator_id;
                $this->file_version          = $this->getFileVersions();
                if (isset($result->hits)){
                    $this->hits              = $result->hits;
                }
                $files[]                     = clone $this;       
        }
           
        if (isset($CFG->repository) AND $externalFiles == true){
           $repo     = get_plugin('repository', $CFG->settings->repository);
           $allfiles = $repo->getFiles($dependency, $id, $files);
           return $allfiles; 
        } else {
            return $files;
        }
    }

     /**
     * get context path
     * @param string $context
     * @return string 
     */
    public function getContextPath($value){ //get Context by context name
        $db     = DB::prepare('SELECT path FROM context WHERE context = ?');   
        $db->execute(array($value));
        $result = $db->fetchObject();
        if ($result->path) {
            return  $result->path;
        } else {return false;}
    }

    /**
     * get context id
     * @param string $context
     * @return string 
     */
    public function getContextId($context){ //get Context by context name
        $db     = DB::prepare('SELECT context_id FROM context WHERE context = ?');
        $db->execute(array($context));
        $result = $db->fetchObject();
        if ($result) {
            return  $result->context_id;
        } else {return false;}
    }
    
    public function hit(){ // hit counter
        $db = DB::prepare('UPDATE files SET hits = hits + 1 WHERE id = ?');
        $db->execute(array($this->id));
    }
    /**
     * Überprüft in den folgenden Tabellen, ob die Datei verknüpft ist: 
     * - certificate    -> logo_id
     * - curriculum     -> icon_id
     * - institution    -> file_id
     * - users          -> avatar_id
     */
    public function isUsed(){
        $occurrence = array();
        $db0    = DB::prepare('SELECT id FROM certificate WHERE logo_id = ?');
        $db0->execute(array($this->id));
        $result0 = $db0->fetchObject();
        if ($result0){
            $occurrence['certificate'] = $result0->id;
        } 
        
        $db1     = DB::prepare('SELECT id FROM curriculum WHERE icon_id = ?');
        $db1->execute(array($this->id));
        $result1 = $db1->fetchObject();
        if ($result1){
            $occurrence['curriculum'] = $result1->id;
        } 
        
        $db2     = DB::prepare('SELECT id FROM institution WHERE file_id = ?');
        $db2->execute(array($this->id));
        $result2 = $db2->fetchObject();
        if ($result2){
            $occurrence['institution'] = $result2->id;
        } 
        
        $db3     = DB::prepare('SELECT id FROM users WHERE avatar_id = ?');
        $db3->execute(array($this->id));
        $result3 = $db2->fetchObject();
        if ($result3){
            $occurrence['users'] = $result3->id;
        } 
        
        return $occurrence;
    }
    /**
     * function used during the install process to set up creator id to new admin
     * @return boolean
     */
    public function dedicate(){ // only use during install
        $db = DB::prepare('UPDATE files SET creator_id = ?');
        return $db->execute(array($this->creator_id));
    }
}