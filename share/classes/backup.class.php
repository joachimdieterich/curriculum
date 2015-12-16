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
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by  
 * the Free Software Foundation; either version 3 of the License, or (at your option) any later version.                                   
 *                                                                       
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even teh implied warranty of        
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details:                          
 *                                                                       
 * http://www.gnu.org/copyleft/gpl.html    
 * 
 * This file is part of curriculum - http://www.joachimdieterich.de  
 */

class Backup {
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
    private $temp_path;
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
    function add($id){
        global $CFG, $USER;
        $this->curriculum_id = $id;
        //*TEST SQL EXPORT*//
        $file = $CFG->backup_root.'tmp/curriculum.sql';
        $db = DB::prepare('SELECT curriculum, grade_id, subject_id, schooltype_id, state_id, description, country_id, creation_time, creator_id, icon_id INTO OUTFILE "'.$file.'" FROM curriculum WHERE id = ?');                      
        $result = $db->execute(array($id));
        
        $db = DB::prepare('LOAD DATA INFILE "'.$file.'" INTO TABLE curriculum (curriculum, grade_id, subject_id, schooltype_id, state_id, description, country_id, creation_time, creator_id, icon_id)');                      
        $result = $db->execute();
        
        
        $file = $CFG->backup_root.'tmp/thema.sql';
        $db = DB::prepare('SELECT * INTO OUTFILE "'.$file.'" FROM terminalObjectives WHERE curriculum_id = ?');                      
        $result = $db->execute(array($id));
        $file = $CFG->backup_root.'tmp/ziel.sql';
        $db = DB::prepare('SELECT * INTO OUTFILE "'.$file.'" FROM enablingObjectives WHERE  curriculum_id = ?');   
        $result = $db->execute(array($id));
        $file = $CFG->backup_root.'tmp/file.sql';
        $db = DB::prepare('SELECT * INTO OUTFILE "'.$file.'" FROM files WHERE  cur_id = ?');   
        $result = $db->execute(array($id));
        //*TEST SQL EXPORT*//
        checkCapabilities('backup:addBackup', $USER->role_id);                                    // Benutzerrechte überprüfen
        include (dirname(__FILE__).'../../libs/Backup/cc_constants.php');                           // Konstanten laden
        
        $timestamp_folder       = date("Y-m-d_H-i-s").'_curriculum_nr_'.$this->curriculum_id;       // Generiere Verzeichnisname
        $this->temp_path        = $CFG->backup_root.'tmp/'.$timestamp_folder;                       // Speichere in /tmp/[timestamp]_curriculum_nr_[cur_id] Verzeichnis
        mkdir($this->temp_path, 0700);                                                              // Lese- und Schreibrechte nur für den Besitzer
        $this->manifest();                                                                          // Manifest (Backup) erzeugen
        $c                      = new Curriculum();
        $c->id                  = $this->curriculum_id;
        $c->load();                                                                                 // Lade curriculum

        $file = new File();
        $file->filename         = $timestamp_folder.'.imscc';
        $file->title            = 'Backup '.$c->curriculum; 
        $file->description      = 'Backup vom '.$timestamp_folder;
        $file->author           = $USER->firstname.' '.$USER->lastname.' ('.$USER->username.')';
        $file->licence          = 2;                                                                // --> alle Rechte vorbehalten
        $file->type             = '.imscc';
        $file->path             = $c->id.'/';
        $file->context_id       = 8;
        $file->creator_id       = $USER->id;
        $file->curriculum_id    = $this->curriculum_id;
        $file->terminal_objective_id = '-1';
        $file->enabling_objective_id = '-1';
        $file->add();
        $this->zipBackup($file->path, $file->filename);                                            //$path = curriculum_id/

        return $file->filename;
    }
    
    /**
     * Creates imsmanifest.xml
     */
    private function manifest(){
        $fileHandle     = fopen($this->temp_path.'/imsmanifest.xml', 'w') or die("can't open file");
        
        $c              = new Curriculum();
        $c->id          = $this->curriculum_id;
        $c->load(true);

        $fileContent[]  = CC_HEADER;                                                 // header in fileContent-Variable schreiben
        array_push($fileContent, '<manifest '.CC_XMLNS.' '.CC_LOMIMSCC.' '.CC_XSI.' identifier="C_'.$this->curriculum_id.'" '.CC_SCHEMALOCATION.'>');               // add openingManifestTag
        array_push($fileContent, implode("\n",$this->mMetadata(strtolower($c->language_code), $c->curriculum, $c->description, $c->subject)));    //Add openingManifestTag
        array_push($fileContent, '<organizations><organization identifier="O_joachimdieterich.de" structure="rooted-hierarchy">');
        //Objectives
        array_push($fileContent, implode("\n",$this->manifestItems($c)));
        array_push($fileContent, '</organization></organizations>');
        //Ressorces
        array_push($fileContent, implode("\n",$this->manifestResources()));
        array_push($fileContent, '</manifest>'); 

        if (!fwrite($fileHandle, implode("\n", $fileContent))){
            echo "Can't write to imsmanifest.xml";
            exit;
        }
        fclose($fileHandle);
    }
    
    /**
    * Creates and returns metadataTag for imsmanifest.xml
    * 
    * @since 0.5
    * @param string language          language of curriculum
    * @param string $curriculum       name of curriculum
    * @param string $description      description of curriculum
    * @param string $subject          subject of curriculum
    * @return array
    */
    private function mMetadata($language, $curriculum, $description, $subject){
        $metadataTag[] = '<metadata>';
        $metadataTag[] = '<schema>'.CC_SCHEMA.'</schema>';
        $metadataTag[] = '<schemaversion>'.CC_Version.'</schemaversion>';
        $metadataTag[] = '<lomimscc:lom>';
        $metadataTag[] = '<lomimscc:general>';
        $metadataTag[] = '<lomimscc:title>';
        $metadataTag[] = '<lomimscc:string language="'.$language.'">'.$curriculum.'</lomimscc:string>';
        $metadataTag[] = '</lomimscc:title>';
        $metadataTag[] = '<lomimscc:language>'.$language.'</lomimscc:language>';
        $metadataTag[] = '<lomimscc:description>';
        $metadataTag[] = '<lomimscc:string language="'.$language.'">'.$description.'</lomimscc:string>';
        $metadataTag[] = '</lomimscc:description>';
        $metadataTag[] = '<lomimscc:identifier>';
        $metadataTag[] = '<lomimscc:catalog>category</lomimscc:catalog>';
        $metadataTag[] = '<lomimscc:entry>'.$subject.'</lomimscc:entry>';
        $metadataTag[] = '</lomimscc:identifier>';
        $metadataTag[] = '</lomimscc:general>';
        $metadataTag[] = '</lomimscc:lom>';
        $metadataTag[] = '</metadata>';

        return $metadataTag;           
    } 
    
    /**
    * Creates and returns manifest Items for imsmanifest.xml
    * @param string $url                   url to tmp folder
    * @param string $folder                tmp folder
    * @param string $cur                   curriculum
    * @return array 
    */
   private function manifestItems($c){
       $manifestItems[] = '<item identifier="root">';  

       //Thema 0 muss Nachrichtenforum in Moodle sein! Evtl Curriculumsbeschreibung abbilden
       $manifestItems[] = '<item identifier="I_Topic0">';
       $manifestItems[] = '<title>0</title>';
       $manifestItems[] = '<item identifier="I_Topic0_Content" identifierref="I_Topic0_Forum">';
       $manifestItems[] = '<title>Nachrichtenforum</title>'; 
       $manifestItems[] = '</item>';
       $manifestItems[] = '</item>';

       //Schleife ab Thema 1, da bei Moodle im Thema 0 das Nachrichtenforum liegt.
       for($i = 0;$i < count($c->terminal_objectives); $i++){
           $manifestItems[] = '<item identifier="I_'.$c->terminal_objectives[$i]->id.'">';
           $manifestItems[] = '<title>'.$c->terminal_objectives[$i]->terminal_objective.'</title>';
           // Add Topic Resources
           for ($l = 0; $l < count($c->terminal_objectives[$i]->files); $l++){
            $manifestItems[] = implode("\n", $this->addRessource($c->terminal_objectives[$i], $l));
           }
           for($j = 0;$j < count($c->terminal_objectives[$i]->enabling_objectives);$j++){
               if ($c->terminal_objectives[$i]->enabling_objectives[$j]){
                   $manifestItems[] = '<item identifier="I_'.$c->terminal_objectives[$i]->enabling_objectives[$j]->id.'">'; 
                   $manifestItems[] = '<title>'.$c->terminal_objectives[$i]->enabling_objectives[$j]->enabling_objective.'</title>';
                   $manifestItems[] = '</item>';
                   //If available - add ressources
                   for ($k = 0; $k < count($c->terminal_objectives[$i]->enabling_objectives[$j]->files); $k++){
                       $manifestItems[] = implode("\n",$this->addRessource($c->terminal_objectives[$i]->enabling_objectives[$j], $k));
                   }
               }  
           } // Ende Ziele (enabling_objectives)
           $manifestItems[] = '</item>';
       } // Ende Themenblöcke
       $manifestItems[] = '</item>';
       
       return $manifestItems;
   }
   
   /**
    * Generate items/ressorce tags and returns item tags. 
    * @global object $CFG Needed for paths
    * @param object $obj    current terminal_objective or enabling_objective object
    * @param int $k         id of current terminal_objective or enabling_objective object
    * @return array
    */ 
   private function addRessource($obj, $k){
       global $CFG;
       if ($obj->files[$k]){ // wenn Material vorhanden
        $manifestItems[]    = '<item identifier="I_'.$obj->id.''.$obj->files[$k]->id.'" identifierref="I_'.$obj->id.''.$obj->files[$k]->id.'_'.$k.'_R">'; 
        $manifestItems[]    = '<title>'.$obj->files[$k]->description.'</title>';         
        $manifestItems[]    = '</item>';

        //Add item ressource Tag 
        $this->rTagItems[] = '<resource identifier="I_'.$obj->id.''.$obj->files[$k]->id.'_'.$k.'_R" type="imswl_xmlv1p1">';
        $this->rTagItems[] = '<file href="i_'.$obj->id.''.$obj->files[$k]->id.'_'.$k.'_FURL/weblink.xml"/>';
        $this->rTagItems[] = '</resource>';
       
        $resourceFolderName = "/i_".$obj->id."".$obj->files[$k]->id.'_'.$k."_FURL"; // Create new Ressource Folder and File
        if ($obj->files[$k]->type == '.url'){ // URL
            $this->newUrlResource($resourceFolderName , $obj->files[$k]->description, $obj->files[$k]->filename); 
        } else {// sonstige Dateien
            $this->newUrlResource($resourceFolderName , $obj->files[$k]->description, $CFG->request_url.$CFG->access_file.$obj->files[$k]->full_path); // Dateien sollen in einer späteren Version in das Backup integriert werden
        }    
    }
    return $manifestItems;
   }
    
   /**
    * Creates and returns manifest Resources for imsmanifest.xml
    * @param string $url                          url to tmp folder
    * @param string $folder                       tmp folder
    */
    private function manifestResources(){
        $mRes[] = '<resources>';
        // Topic 0 Forum
        $itemIdent = 'I_Topic0_Forum';

        $mRes[] = '<resource identifier="'.$itemIdent.'" type="imsdt_xmlv1p1">';
        $mRes[] = '<file href="'.$itemIdent.'/discussion.xml"/>';
        $mRes[] = '</resource>';
        $this->newDiscussion($itemIdent);

        // add Resources Item    
        if (isset($this->rTagItems)){
            array_push($mRes, implode("\n",$this->rTagItems));                                     //Add Resources Items
        }
        $mRes[] = '</resources>';

        return $mRes;
    }
    
    /**
    * Add Forum Resources to ItemResource-Folder
    * @since 0.5
    *
    * @param string $url                          url to tmp folder
    * @param string $folder                       tmp folder
    */
    private function newDiscussion($folder){
        mkdir($this->temp_path.'/'.$folder, 0777);                                                                  // erzeuge ItemResouce-Folder                          
        $fileHandle    = fopen($this->temp_path.'/'.$folder.'/discussion.xml', 'w') or die("can't open file");      // erzeuge discussion.xml

        $fileContent[] = CC_HEADER;                                                                                 // Header in fileContent-Array schreiben
        array_push($fileContent, '<topic '.CC_XMLNS_DISCUSSION.' '.CC_XSI.' '.CC_SCHEMALOCATION_DISCUSSION.'>');    // Add discussionTopicOpeningTag()
        array_push($fileContent, CC_DISCUSSION_TITLE);                            
        array_push($fileContent, CC_DISCUSSION_DESCRIPTION);                            
        array_push($fileContent, '</topic>');                    

        if (!fwrite($fileHandle, implode("\n", $fileContent))){
            echo "Can't write to discussion.xml";
            exit;
        }
        fclose($fileHandle);
    } 
    
    /**
    * Add URL Resources 
    * @param string $folder                       tmp folder
    * @param string $title                        Title of Link
    * @param string $webLink                      URL
    */
    private function newUrlResource($folder, $title, $webLink){
        mkdir($this->temp_path.''.$folder, 0777);                                                               // erzeuge UrlResource-Folder                          
        $fileHandle    = fopen($this->temp_path.'/'.$folder.'/weblink.xml', 'w') or die("can't open file");     // erzeuge Weblink.xml

        $fileContent[] = CC_HEADER;                                                                             //Header in fileContent-Variable schreiben
        array_push($fileContent, '<webLink '.CC_XMLNS_WEBLINK.' '.CC_XSI.' '.CC_SCHEMALOCATION_WEBLINK.'>');                  
        array_push($fileContent, implode("\n",$this->urlResourceContent($title, $webLink)));          
        array_push($fileContent, '</webLink>');                    

        if (!fwrite($fileHandle, implode("\n", $fileContent))){
            echo "Can't write to discussion.xml";
            exit;
        }
        fclose($fileHandle);
    } 
     
    /**
    * Add URL (content) 
    * @param string $title                        Title of Link
    * @param string $webLink                      URL
    */
    private function urlResourceContent($title, $webLink){
        $urlResContent[] = '<title>'.$title.'</title>';
        $urlResContent[] = '<url href="'. str_replace('&', '&amp;amp;', $webLink).'" target="_self"/>'; //öffnet Weblink  ampersand muss '&amp;amp' sein!  siehe: http://stackoverflow.com/questions/1328538/how-do-i-escape-ampersands-in-xml

        return $urlResContent;
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
                $zip->addFile(realpath($key), str_replace($CFG->backup_root, '/', $key)) or die ("ERROR: Could not add file: $key"); //str_replace: $url abschneiden, da sonst der komplette Pfad als Ordnerstuktur in der zip erscheint
            }
        }
        if ($zip->close()){                                                                             // Schließen und speichern
            $PAGE->message[]        = 'Backup <strong>"' . $filename . '"</strong> wurde erfolgreich erstellt.';
        } else {
            $PAGE->message[]        = 'Backup fehlgeschlagen.';
        }
        $files      = new RecursiveIteratorIterator(                                                    // Lösche temporäre Dateien
        new RecursiveDirectoryIterator($this->temp_path, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST);                                                        // CHILD_FIRST wichtig, damit erst die unterordner/Dateien gelöscht werden
        foreach ($files as $fileinfo) {
            $todo   = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
            $todo($fileinfo->getRealPath());
        }
        rmdir($this->temp_path);                                                                        // Grundfolder löschen
    } 
}