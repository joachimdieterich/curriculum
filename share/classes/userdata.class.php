<?php
/** 
* Klasse zum erstellen einer imscc-Datei aus einem Lehrplan (curriculum)
* 
* @package core
* @filename backup.class.php
* @copyright 2013 joachimdieterich
* @author joachimdieterich
* @date 2013.05.27 21:36
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

class Userdata {
    /**
     * id of backup
     * @var int
     */
    private $id; 
    /**
     * path to backup file
     * @var string
     */
    private $path; 
    /**
     * path to temp directory
     * @var string 
     */
    public $temp_path;
    /**
     * filename of backup
     * @var string
     */
    private $file_name; 
    /**
     * id of curriculum
     * @var int
     */
    private $curriculum_id; 
    /**
     * name of curriculum
     * @var string
     */
    private $curriculum;
    /**
     * user id of creator
     * @var int
     */
    private $creator_id; 
    /**
     * username of creator
     * @var string
     */
    private $creator; 
    /**
     * timestamp of file creation
     * @var timestamp
     */
    private $creation_time; 
    /**
     * array of resource tags
     * @var array 
     */
    private $rTagItems;
    
    
   /**
    * add backup file informations to db
    * @param int id  curriculum id
    * @return string filename of backup file
    */
    
    function add($id=null){
        return $this->make($id);
    }
    
    function request($id=null){
        global $USER;
        $db = DB::prepare("INSERT INTO getUserData (user_id) VALUES (?)");
        $db->execute(array($USER->id));
    }
    
    function make($id = null){
        global $CFG, $USER;
        
        $file                   = new File();
        $this->curriculum_id    = $id;        
        $timestamp       = date("Y-m-d_H-i-s");                                // Generiere Zeitstempel
        
        $filename = $timestamp . "_" . $USER->id . ".xml";
        $filenameZip = $timestamp . "_" . $USER->id . ".zip";
        
        $file->filename         = $filename;
        $file->type             = '.xml';
        $file->title            = 'USER-Backup'; 
        $file->description      = 'Userdaten von ' . $USER->id;
        $file->author           = $USER->firstname.' '.$USER->lastname.' ('.$USER->username.')';
        $file->license          = 2;                                                                // --> alle Rechte vorbehalten //TODO noch ändern
        $file->path             = $USER->id.'/';
        $file->context_id       = 1;
        $file->file_context     = 4;
        $file->creator_id       = $USER->id;
        $file->curriculum_id    = $this->curriculum_id;
        $file->terminal_objective_id = NULL;
        $file->enabling_objective_id = NULL;
        $file->add(false);
        
        $this->temp_path        = $CFG->curriculumdata_root . "/user/" . $file->path;
                
        $this->databaseToXml($CFG->curriculumdata_root . "/user/" . $file->path . "/" . $filename);
 
        $zip = FALSE;
        if ($zip == true){
            $this->zipBackup($file->path, $file->filenameZip);
        }
        
        return $file->filename;
    }
    
    public function getUserData($paginator = '') {
        global $USER;
        $userData       = array();
        $db             = DB::prepare('SELECT gud.*, us.username FROM getuserdata AS gud, users AS us WHERE gud.user_id = us.id;');
        $db->execute();
        while($result = $db->fetchObject()) { 
            $this->id                = $result->id;
            $this->user_id           = $result->user_id;
            $this->username          = $result->username;
            $this->time              = $result->time; 
            $this->freigegeben       = $result->freigegeben;
            $userData[]              = clone $this; 
        }           
        return $userData;
    }
    
    
    private function tableToXml($db, $tablename, $rownames, $xml){
        while ($row = $db->fetchObject()) {        
            $xml->startElement('Tabellenname');
            $xml->writeAttribute('Tabellenname', $tablename);            
            foreach ($rownames as $name){
                $xml->startElement($name);
                $xml->writeAttribute($name, $row->$name);
                $xml->writeRaw($row->$name);
                $xml->endElement();
            }
            $xml->endElement();
        }


    }


    private function databaseToXml($filename){
        global $USER;
        $uid =  $USER->id;
        $db = DB::prepare("SELECT DISTINCT c.TABLE_NAME AS name
        FROM   INFORMATION_SCHEMA.COLUMNS AS c
        WHERE  c.TABLE_SCHEMA = 'curriculum'
        AND c.COLUMN_NAME = 'user_id';");
        $db->execute();
        $tablenames_user_id = array();
        $i = 0;

        while($result = $db->fetchObject()){
            $tablenames_user_id[$i] = $result->name;
            $i ++;
        }

        $db = DB::prepare("SELECT DISTINCT c.TABLE_NAME AS name
        FROM   INFORMATION_SCHEMA.COLUMNS AS c
        WHERE  c.TABLE_SCHEMA = 'curriculum'
        AND c.COLUMN_NAME = 'creator_id';");
        $db->execute();
        $tablenames_creator_id = array();
        $i = 0;

        while($result = $db->fetchObject()){
            $tablenames_creator_id[$i] = $result->name;
            $i ++;
        }

        $x = new XMLWriter();
        $x->openURI($filename);
        $x->startDocument();
        $x->setIndent(true);
        foreach ($tablenames_user_id as $name){
            $db = DB::prepare('SELECT c.COlUMN_NAME AS name FROM INFORMATION_SCHEMA.COLUMNS AS c WHERE c.TABLE_NAME = "'.$name.'" AND c.TABLE_SCHEMA = "curriculum";');
            $db->execute();
            $i = 0;
            $rownames = array();
            while ($result = $db->fetchObject()){
                $rownames[$i] = $result->name;
                $i ++;
            }
            $db = DB::prepare('SELECT * FROM curriculum.'.$name.' WHERE user_id = ?;');
            $db->execute(array($uid));
            $this->tableToXml($db, $name, $rownames, $x);
        }

        foreach ($tablenames_creator_id as $name){
            $db = DB::prepare('SELECT c.COlUMN_NAME AS name FROM INFORMATION_SCHEMA.COLUMNS AS c WHERE c.TABLE_NAME = "'.$name.'" AND c.TABLE_SCHEMA = "curriculum";');
            $db->execute();
            $i = 0;
            $rownames = array();
            while ($result = $db->fetchObject()){
                $rownames[$i] = $result->name;
                $i ++;
            }
            $db = DB::prepare('SELECT * FROM curriculum.'.$name.' WHERE creator_id = ?;');
            $db->execute(array($uid));
            $this->tableToXml($db, $name, $rownames, $x);
        }

        header('Content-type: text/xml');
        $x->flush();
    }
    

    /**
    * zip backup file, move .imscc file to backupfolder and delete temp files
    * 
    * @since 0.5
    * @param string $backup_folder         url of backup folder (where imscc file is finally saved)
    * @param string $filename              Name of backup file
    */
    private function zipBackup($backup_folder, $filename){
       global $CFG, $PAGE;
        if (!file_exists($CFG->backup_root.''.$backup_folder)) {
            mkdir($CFG->backup_root.''.$backup_folder, 0777, true);
        }
        $zip = new ZipArchive();                                                                       
        if ($zip->open($CFG->backup_root.''.$backup_folder.$filename, ZIPARCHIVE::CREATE) !== TRUE) {   // Öffne Archiv
            die ("Could not open archive");
        }

        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->temp_path));    // initialize an iterator // pass it the directory to be processed
        foreach ($iterator as $key=>$value) {                                                           // iterate over the directory // add each file found to the archive
            if (substr($key, -2) != '/.' AND substr($key, -3) != '/..'){                                // exclude . and ..
                $zip->addFile(realpath($key), str_replace($this->temp_path, '/', $key)) or die ("ERROR: Could not add file: $key"); //str_replace: $url abschneiden, da sonst der komplette Pfad als Ordnerstuktur in der zip erscheint
            }
        }
        if ($zip->close()){  
            $PAGE->message[] = array('message' => 'Backup <strong>"' . $filename . '"</strong> wurde erfolgreich erstellt.', 'icon' => 'fa fa-cloud-download text-success');// Schließen und speichern
        } else {
            $PAGE->message[] = array('message' => 'Backup fehlgeschlagen.', 'icon' => 'fa fa-cloud-download text-danger');// Schließen und speichern
        }
        
        // Clean up temp
        delete_folder($this->temp_path);                                                                
        /*$files      = new RecursiveIteratorIterator(                                                    
        new RecursiveDirectoryIterator($this->temp_path, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST);                                                        // CHILD_FIRST !, to delete subfolders first
        foreach ($files as $fileinfo) {
            $todo   = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
            $todo($fileinfo->getRealPath());
        }*/
        //rmdir($this->temp_path);                                                                        // Delete root folder
    } 
}