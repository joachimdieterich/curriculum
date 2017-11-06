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
    function add($id, $schema = 'xml'){
        global $CFG, $USER;
        
        $file                   = new File();
        $this->curriculum_id    = $id;        
        checkCapabilities('backup:add', $USER->role_id);                                    // Benutzerrechte überprüfen
        $c                      = new Curriculum();
        $c->id                  = $this->curriculum_id;
        $c->load(true);                                                                             // Lade curriculum with objectives (für xml)
        $timestamp_folder       = date("Y-m-d_H-i-s").'_curriculum_nr_'.$this->curriculum_id;       // Generiere Verzeichnisname
        $this->temp_path        = $CFG->backup_root.'tmp/'.$timestamp_folder;                       // Speichere in /tmp/[timestamp]_curriculum_nr_[cur_id] Verzeichnis
        mkdir($this->temp_path, 0700);                                                              // Lese- und Schreibrechte nur für den Besitzer
        
        switch ($schema) {
            case 'xml':     $this->generateXML($c, $timestamp_folder);                          // generate xml Backup
                            $file->filename         = $timestamp_folder.'.curriculum';
                            $file->type             = '.curriculum';
                            $zip                    = true;
                break;
            case 'xml-rlp': $this->generate($c, $timestamp_folder);                             // schema http://bsbb.eu fachtype.xsd     
                            $file->filename         = $timestamp_folder.'.zip';
                            $file->type             = '.zip';
                            $zip                    = true;
                break;

            case 'imscc':   include (dirname(__FILE__).'../../libs/Backup/cc_constants.php');   // Konstanten laden
                            mkdir($this->temp_path, 0700);                                      // Lese- und Schreibrechte nur für den Besitzer
                            $this->manifest();                                                  // Manifest (Backup) erzeugen
                            $file->filename         = $timestamp_folder.'.imscc';
                            $file->type             = '.imscc';
                            $zip                    = true;
                break;

            default:        
                break;
        }

        $file->title            = 'Backup '.$c->curriculum; 
        $file->description      = 'Backup vom '.$timestamp_folder;
        $file->author           = $USER->firstname.' '.$USER->lastname.' ('.$USER->username.')';
        $file->license          = 2;                                                                // --> alle Rechte vorbehalten
        $file->path             = $c->id.'/';
        $file->context_id       = 8;
        $file->file_context     = 1;
        $file->creator_id       = $USER->id;
        $file->curriculum_id    = $this->curriculum_id;
        $file->terminal_objective_id = NULL;
        $file->enabling_objective_id = NULL;
        $file->add();
 
        if ($zip == true){
            $this->zipBackup($file->path, $file->filename);          //$path = curriculum_id/
        }
        
        return $file->filename;
    }
    
    public function generate($c, $filename){
        global $CFG; 

        $xml = new DOMDocument("1.0", "UTF-8"); 
        /* root */
        $root = $xml->createElement('fach');
        $root->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $root->setAttribute('xmlns', 'http://bsbb.eu');
        $root->setAttribute('xsi:schemaLocation', 'http://bsbb.eu fachtype.xsd');
            /* curriculum Attributes (c1)*/
            $c1     = $xml->createElement("c1");
            $c1_id  = $xml->createElement("id",       $c->id);    
            $c1->appendChild($c1_id);
            $c1_title = $xml->createElement("title",    $c->curriculum);
            $c1->appendChild($c1_title);
                /* (multiple subtexts) */
                $subtext = $xml->createElement("subtext");
                    $subtext_id     = $xml->createElement("id",       $c->id.'-1-1');
                    $subtext->appendChild($subtext_id);
                    $subtext_title  = $xml->createElement("title",    'Beschreibung');
                    $subtext->appendChild($subtext_title);
                    $subtext_content= $xml->createElement("content",  $c->description);
                    $subtext->appendChild($subtext_content);
                $c1->appendChild($subtext);
            $root->appendChild($c1);
            /* curriculum Attributes (c2)*/
            $c2 = $xml->createElement("c2");
                $c2_vorwort = $xml->createElement("vorwort");    
                    $c2_vorwort_id      = $xml->createElement("id", $c->id.'-2');
                    $c2_vorwort->appendChild($c2_vorwort_id);
                    $c2_vorwort_titel   = $xml->createElement("id", 'Regelungen für das Land Rheinland-Pfalz');
                    $c2_vorwort->appendChild($c2_vorwort_titel);
                    $c2_vorwort_content = $xml->createElement("content", 'Die Elemente Vorwort unter c2 werden in RLP aus meiner Sicht nicht gebraucht, in Berlin-Brandenburg werden hier die unterschiedlichen Regelungen zw. Berlin und Brandenburg hinterlegt.');
                    $c2_vorwort->appendChild($c2_vorwort_content);
                $c2->appendChild($c2_vorwort);
                
                /* area --> Terminal Objectives */
                foreach($c->terminal_objectives as $ter_value){ 
                    $c2_area = $xml->createElement("area");   
                        $c2_area_id     = $xml->createElement("id", $c->id.'-'.$ter_value->id);
                        $c2_area->appendChild($c2_area_id);
                        $c2_area_name   = $xml->createElement("name", $ter_value->terminal_objective);
                        $c2_area->appendChild($c2_area_name);
                        /* subarea --> enabling objectives */
                        $c2_subarea     = $xml->createElement("subarea");
                            $c2_subarea_id  = $xml->createElement("id", $c->id.'-'.$ter_value->id.'-1');
                            $c2_subarea->appendChild($c2_subarea_id);
                            $c2_subarea_name= $xml->createElement("name", $ter_value->terminal_objective);
                            $c2_subarea->appendChild($c2_subarea_name);
                            /* competence --> enabling objectives */
                            if (count($ter_value->enabling_objectives) >= 1 AND $ter_value->enabling_objectives != false){
                                foreach($ter_value->enabling_objectives as $ena_value){ 
                                    $c2_subarea_competence  = $xml->createElement("competence");
                                        $c2_subarea_competence_id   = $xml->createElement("id", $c->id.'-'.$ter_value->id.'-1'.$ena_value->id);
                                        $c2_subarea->appendChild($c2_subarea_competence_id);
                                        $c2_subarea_competence_name = $xml->createElement("name", $ena_value->enabling_objective);
                                        $c2_subarea->appendChild($c2_subarea_competence_name);
                                        $c2_subarea_competence_stufe= $xml->createElement("stufe");
                                            $c2_subarea_competence_stufe_id = $xml->createElement("id", $c->id.'-'.$ter_value->id.'-1'.$ena_value->id.'-EFGH');
                                            $c2_subarea_competence_stufe->appendChild($c2_subarea_competence_stufe_id);
                                            $c2_subarea_competence_stufe_level      = $xml->createElement("id", 'EFGH');
                                            $c2_subarea_competence_stufe->appendChild($c2_subarea_competence_stufe_level);
                                            $c2_subarea_competence_stufe_standard   = $xml->createElement("standard");
                                                $c2_subarea_competence_stufe_standard_id        = $xml->createElement("id", $c->id.'-'.$ter_value->id.'-1'.$ena_value->id.'-EFGH-1');
                                                $c2_subarea_competence_stufe_standard->appendChild($c2_subarea_competence_stufe_standard_id);
                                                $c2_subarea_competence_stufe_standard_content   = $xml->createElement("content", $ena_value->enabling_objective);
                                                $c2_subarea_competence_stufe_standard->appendChild($c2_subarea_competence_stufe_standard_content);
                                            $c2_subarea_competence_stufe->appendChild($c2_subarea_competence_stufe_standard);    
                                        $c2_subarea->appendChild($c2_subarea_competence_stufe);
                                    $c2_subarea->appendChild($c2_subarea_competence);
                                }
                            }
                        $c2_area->appendChild($c2_subarea);
                    $c2->appendChild($c2_area);
                }  
            $root->appendChild($c2);
            
            $c3 = $xml->createElement("c3");
                $c3_id      = $xml->createElement("id", $c->id.'-3');
                $c3->appendChild($c3_id);
                $c3_name    = $xml->createElement("title", 'Themen und Inhalte');
                $c3->appendChild($c3_id);
                $c3_name    = $xml->createElement("vortext", 'Hier muss überlegt werden, welche Stuktur wir in RLP hinterlegen wollen. In diesem Beispiel werden die dem Lehrplan zugeordneten Datensätze aus der content tabelle zugeordnet und . Es handlt sich dabei um die Texte des Lehrplans, die nicht im Raster hinterlegt sind.');
                $c3->appendChild($c3_name);
                $c3_themeninhalt = $xml->createElement("themeninhalt");
                    $c3_themeninhalt_id     = $xml->createElement("id", $c->id.'-3-1');
                    $c3_themeninhalt->appendChild($c3_themeninhalt_id);
                    $c3_themeninhalt_titel  = $xml->createElement("titel", 'Es sind mehrere Themeninhalte möglich.');
                    $c3_themeninhalt->appendChild($c3_themeninhalt_titel);
                    $c3_themeninhalt_content= $xml->createElement("content", 'In diesem Fall werden die content-Blöcke als untergeordnete Inhalt-Element ausgegeben.');
                    $c3_themeninhalt->appendChild($c3_themeninhalt_content);
                    /* content */
                    $content            = new Content();
                    $content_entries    = $content->get('curriculum', $c->id );
                    $i = 0;
                    foreach($content_entries as $con_value){ 
                        $i++;
                        $c3_themeninhalt_inhalt = $xml->createElement("inhalt");
                            $c3_themeninhalt_inhalt_id      = $xml->createElement("id", $c->id.'-3-1'.$i);
                            $c3_themeninhalt_inhalt->appendChild($c3_themeninhalt_inhalt_id);
                            $c3_themeninhalt_inhalt_title   = $xml->createElement("title", $con_value->title);
                            $c3_themeninhalt_inhalt->appendChild($c3_themeninhalt_inhalt_title);
                            $c3_themeninhalt_inhalt_content = $xml->createElement("content", $con_value->content);
                            $c3_themeninhalt_inhalt->appendChild($c3_themeninhalt_inhalt_content);   
                        $c3_themeninhalt->appendChild($c3_themeninhalt_inhalt);
                    }
                $c3->appendChild($c3_themeninhalt);
            $root->appendChild($c3);
        $xml->appendChild($root);
        
        $xml->preserveWhiteSpace = false; 
        $xml->formatOutput = true; 
        
        $file = $this->temp_path.'/'.$filename.'.xml'; // Backup / [cur_id] / 
        file_put_contents($file, $xml->saveXML());
    }
    
    public function generateXML($c, $filename){
        global $CFG; 

        $xml = new DOMDocument("1.0", "UTF-8"); 
        /* curriculum */
        $cur = $xml->createElement("curriculum");
        $cur->setAttribute("id",            $c->id);
        $cur->setAttribute("curriculum",    $c->curriculum);
        $gra = new Grade();
        $gra->load('id', $c->grade_id);
        $cur->setAttribute("grade",         $gra->grade);
        $cur->setAttribute("subject",       $c->subject);
        $sch = new Schooltype();
        $sch->id = $c->schooltype_id;
        $sch->load();
        $cur->setAttribute("schooltype",    $sch->schooltype);
        $cur->setAttribute("state_id",      $c->state_id);
        $cur->setAttribute("description",   $c->description);
        $cur->setAttribute("country_id",    $c->country_id);
        $cur->setAttribute("creation_time", $c->creation_time);
        $usr = new User($c->creator_id);
        $cur->setAttribute("creator",       $usr->firstname . ' ' . $usr->lastname );
        $cur->setAttribute("icon_id",       $c->icon_id);
        if (count($c->terminal_objectives) >= 1 ){
            if (isset($CFG->repository)){ // prüfen, ob Repository Plugin vorhanden ist.
                $ext_ref    = $CFG->repository;
            }
            $file       = new File();
            /* terminal objectives */
            foreach($c->terminal_objectives as $ter_value){ 
                $ter = $xml->createElement("terminal_objective");
                $ter->setAttribute("id",                 $ter_value->id);
                $ter->setAttribute("terminal_objective", $ter_value->terminal_objective);
                $ter->setAttribute("description",        $ter_value->description);
                $ter->setAttribute("order_id",           $ter_value->order_id);
                $ter->setAttribute("repeat_interval",    $ter_value->repeat_interval);
                $ter->setAttribute("color",              $ter_value->color);
                if (isset($ext_ref)){
                    $ter->setAttribute("ext_reference",  $ext_ref->getReference('terminal_objective', $ter_value->id));
                }
                
                $this->appendFile($xml, $ter, $ter_value->id, 'terminal_objective', $file); // terminal objective material
                $this->appendReference($xml, $ter, $ter_value->id, 'terminal_objective');   //append references
                
                /* enabling objectives */
                if (count($ter_value->enabling_objectives) >= 1 AND $ter_value->enabling_objectives != false){
                    foreach($ter_value->enabling_objectives as $ena_value){ 
                        $ena = $xml->createElement('enabling_objective');
                        $ena->setAttribute('id',                 $ena_value->id);
                        $ena->setAttribute('enabling_objective', $ena_value->enabling_objective);
                        $ena->setAttribute('description',        $ena_value->description);
                        $ena->setAttribute('order_id',           $ena_value->order_id);
                        $ena->setAttribute('repeat_interval',    $ena_value->repeat_interval);
                        if (isset($ext_ref)){
                            $ena->setAttribute('ext_reference',      $ext_ref->getReference('enabling_objective', $ena_value->id));
                        }
                        
                        $this->appendFile($xml, $ena, $ena_value->id, 'enabling_objective', $file); // enabling objective material
                        $this->appendReference($xml, $ena, $ena_value->id, 'enabling_objective');   //append references
                        $ter->appendChild( $ena );                              // append enabling_objective to terminal_objective
                    }
                }
                $cur->appendChild( $ter );                                      // append terminal_objective to curriculum
            }
        }
        
        $this->appendContent($xml, $cur, $c->id, 'curriculum', 'content');      //* export content *//
        $this->appendContent($xml, $cur, $c->id, 'glossar', 'glossar');         //* export glossar *//
        $this->appendFile(   $xml, $cur, $c->id, 'curriculum', $file);             //* export curriuculum files *//
        
        
        $xml->appendChild($cur);
        $xml->preserveWhiteSpace = false; 
        $xml->formatOutput = true; 
        
        $file = $this->temp_path.'/'.$filename.'.xml'; // Backup / [cur_id] / 
        file_put_contents($file, $xml->saveXML());
    }
    
    private function appendContent($xml, $parent_node, $ref_id, $context, $element_tag){
        $content                 = new Content();
        $content_entries         = $content->get($context, $ref_id );
        if (count($content_entries) >= 1){
            foreach($content_entries as $con_value){ 
                $content_tag         = $xml->createElement($element_tag);
                $content_tag_title   = $xml->createElement("title", $con_value->title);
                $content_tag->appendChild($content_tag_title);
                $content_tag_content = $xml->createElement("text", $con_value->content);
                $content_tag->appendChild($content_tag_content);   
                $parent_node->appendChild($content_tag);
            }
        }
    }
    
    private function appendFile($xml, $parent_node, $ref_id, $context, $file){
        global $CFG;
        if ($context == 'curriculum'){
            $cur_files  = $file->getFiles($context, $ref_id,'', array('cur' => true));
        } else {
            $cur_files  = $file->getFiles($context, $ref_id);
        }
        if (count($cur_files) >= 1){
            foreach($cur_files as $f_value) {
                $child  = $xml->createElement('file');
                $this->array_to_Attribute($child, $f_value);   
                $parent_node->appendChild( $child );        // Datei enabling objective zuordnen
                /* Datei in Backup Temp kopieren */
                if ($f_value->type != '.url' AND $f_value->type != 'external'){
                    silent_mkdir($this->temp_path.'/'.$f_value->path);
                    copy($CFG->curriculumdata_root.$f_value->full_path, $this->temp_path.'/'.$f_value->path.$f_value->filename);
                }
            }
        }
    }
    
    private function appendReference($xml, $parent_node, $ref_id, $context){
        $reference = new Reference();
        $references = $reference->get('reference_id', $_SESSION['CONTEXT'][$context]->context_id, $ref_id);
        if (count($references) >= 1){
            foreach ($references as $ref) {
                $child  = $xml->createElement('reference');
                $child->setAttribute('unique_id',     $ref->unique_id); 
                $child->setAttribute('reference_id',  $ref_id);
                
                $gr     = new Grade();
                $gr->load('id', $ref->grade_id);
                $child->setAttribute('grade',  $gr->grade); 
                
                $this->appendContent($xml, $child, $ref->id, 'reference', 'content');    /*load content */
                
                $parent_node->appendChild( $child );        // Datei enabling objective zuordnen
            }
        }
    }
    
    private function array_to_Attribute($node, $var){
        foreach($var as $key => $value){
            if ($key != 'file_version'){                                        //Dateiversionen sollen nicht ins Backup
                $node->setAttribute($key, $value);                     
            }
        }
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
        array_push($fileContent, implode("\n",$this->mMetadata(strtolower($c->language_code), $c->curriculum, strip_tags($c->description), $c->subject)));    //Add openingManifestTag
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
        $metadataTag[] = '<lomimscc:string language="'.$language.'">'.strip_tags($curriculum).'</lomimscc:string>';
        $metadataTag[] = '</lomimscc:title>';
        $metadataTag[] = '<lomimscc:language>'.$language.'</lomimscc:language>';
        $metadataTag[] = '<lomimscc:description>';
        $metadataTag[] = '<lomimscc:string language="'.$language.'">'.strip_tags($description).'</lomimscc:string>';
        $metadataTag[] = '</lomimscc:description>';
        $metadataTag[] = '<lomimscc:identifier>';
        $metadataTag[] = '<lomimscc:catalog>category</lomimscc:catalog>';
        $metadataTag[] = '<lomimscc:entry>'.strip_tags($subject).'</lomimscc:entry>';
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
           $manifestItems[] = '<title>'.strip_tags($c->terminal_objectives[$i]->terminal_objective).'</title>';
           // Add Topic Resources
           for ($l = 0; $l < count($c->terminal_objectives[$i]->files); $l++){
            $manifestItems[] = implode("\n", $this->addRessource($c->terminal_objectives[$i], $l));
           }
           for($j = 0;$j < count($c->terminal_objectives[$i]->enabling_objectives);$j++){
               if ($c->terminal_objectives[$i]->enabling_objectives[$j]){
                   $manifestItems[] = '<item identifier="I_'.$c->terminal_objectives[$i]->enabling_objectives[$j]->id.'">'; 
                   $manifestItems[] = '<title>'.strip_tags($c->terminal_objectives[$i]->enabling_objectives[$j]->enabling_objective).'</title>';
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
        $manifestItems[]    = '<title>'.strip_tags($obj->files[$k]->description).'</title>';         
        $manifestItems[]    = '</item>';

        //Add item ressource Tag 
        $this->rTagItems[] = '<resource identifier="I_'.$obj->id.''.$obj->files[$k]->id.'_'.$k.'_R" type="imswl_xmlv1p1">';
        $this->rTagItems[] = '<file href="i_'.$obj->id.''.$obj->files[$k]->id.'_'.$k.'_FURL/weblink.xml"/>';
        $this->rTagItems[] = '</resource>';
       
        $resourceFolderName = "/i_".$obj->id."".$obj->files[$k]->id.'_'.$k."_FURL"; // Create new Ressource Folder and File
        if ($obj->files[$k]->type == '.url'){ // URL
            $this->newUrlResource($resourceFolderName , strip_tags($obj->files[$k]->description), $obj->files[$k]->filename); 
        } else {// sonstige Dateien
            $this->newUrlResource($resourceFolderName , strip_tags($obj->files[$k]->description), $CFG->request_url.$CFG->access_file.$obj->files[$k]->full_path); // Dateien sollen in einer späteren Version in das Backup integriert werden
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