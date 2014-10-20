<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package backup
 * @filename cc_export.php - curriculum Common Cartridge generator
 * @copyright 2013 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2013.03.08 13:26
 * @license: 
 * 
*
* This program is free software; you can redistribute it and/or modify 
* it under the terms of the GNU General Public License as published by  
* the Free Software Foundation; either version 3 of the License, or     
* (at your option) any later version.                                   
*                                                                       
* This program is distributed in the hope that it will be useful,       
* but WITHOUT ANY WARRANTY; without even the implied warranty of        
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the         
* GNU General Public License for more details:                          
*                                                                       
* http://www.gnu.org/copyleft/gpl.html      
*/

include 'cc_constants.php';                         //Bausteine für exportdatei

/**
  * Creates a new backup file and returns the .imscc-file with folder
  * 
  * @since 0.5
  *
  * @param string $backupURL    Path to Backup-URL
  * @param string $folder       Selcted curriculum ID
  * @param string $userID       Current user ID
  */
function newBackup($backupURL, $folder, $userID){
    $url = $backupURL.'tmp/';//session_save_path();                 //Save in /tmp(/php) verzeichnis
    $timestamp = date("Y-m-d_H-i-s");                               //to avoid duplicates
    $timestamp_folder = $timestamp.'_Curriculum_Nr_'.$folder;       //Foldername
    mkdir($url.''.$timestamp_folder, 0700);                         //Lese- und Schreibrechte nur für den Besitzer
    newManifest($url, $timestamp_folder, $folder);                  //create Manifest
    $zipfile = zipBackup($url, $backupURL, $timestamp_folder, $folder, $userID); //$folder = curriculum
    return $zipfile;                   
}

/**
  * Creates a new imsmanifest.xml for the selected curriculum based
  * 
  * @since 0.5
  *
  * @param string $url          Backup-URL (tmp/timestamp_Curriculum_Nr_{x})
  * @param string $folder       Timestamp-Folder
  * @param string $curriculum_id   Selcted curriculum ID
  */
function newManifest($url,$folder,$curriculum_id){
    $fileHandle = fopen($url.''.$folder.'/imsmanifest.xml', 'w') or die("can't open file");
    // Load curriculum
    $cur = new Curriculum();
    $cur->id = $curriculum_id;
    $cur->load(true);
    // fileContent
    unset($fileContent);
    $fileContent = xmlHeader();                                 //Header in fileContent-Variable schreiben
    array_push($fileContent, implode("\n",manifestOpeningTag($cur->id)));                                     //Add openingManifestTag
    array_push($fileContent, implode("\n",manifestMetadata(strtolower($cur->language_code), $cur->curriculum, $cur->description, $cur->subject)));    //Add openingManifestTag
    array_push($fileContent, implode("\n",manifestOrganizationsOpeningTag()));
    
    //Objectives
    array_push($fileContent, implode("\n",manifestItems($url, $folder, $cur, $resourceTagItems)));
    array_push($fileContent, implode("\n",manifestOrganizationsClosingTag()));
    //Ressorces
    array_push($fileContent, implode("\n",manifestResources($url,$folder, $resourceTagItems)));
    array_push($fileContent, implode("\n",manifestClosingTag())); 
    
    if (!fwrite($fileHandle, implode("\n", $fileContent))){
        echo "Can't write to imsmanifest.xml";
        exit;
    }
    fclose($fileHandle);
}

/**
  * Creates and returns xmlHeader for imsmanifest.xml
  * 
  * @since 0.5
  *
  */
function xmlHeader(){
    $header[] = CC_HEADER;
    return $header;
}

/**
  * Creates and returns manifestOpeningTag for imsmanifest.xml
  * 
  * @since 0.5
  * @param string $curID     Curriculum ID
  */
function manifestOpeningTag($curID){
    $manifestTag[] = '<manifest '.CC_XMLNS.' '.CC_LOMIMSCC.' '.CC_XSI.' identifier="C_'.$curID.'" '.CC_SCHEMALOCATION.'>';
    return $manifestTag;
}

/**
  * Creates and returns manifestClosingTag for imsmanifest.xml
  * 
  * @since 0.5
  */
function manifestClosingTag(){
    $manifestTag[] = '</manifest>';
    return $manifestTag;
}

/**
  * Creates and returns metadataTag for imsmanifest.xml
  * 
  * @since 0.5
  * @param string language          language of curriculum
  * @param string $curriculum       name of curriculum
  * @param string $description      description of curriculum
  * @param string $subject          subject of curriculum
  */
function manifestMetadata($language, $curriculum, $description, $subject){
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
  * Creates and returns organizationsTag (opening tag) for imsmanifest.xml
  * 
  * @since 0.5
  *
  */
function manifestOrganizationsOpeningTag(){
    $organizationsTag[] = '<organizations>';
    $organizationsTag[] = '<organization identifier="O_JoachimDieterich" structure="rooted-hierarchy">';
    return $organizationsTag;
    
}

/**
  * Creates and returns organizationsTag (closing tag) for imsmanifest.xml
  * 
  * @since 0.5
  *
  */
function manifestOrganizationsClosingTag(){
    $organizationsTag[] = '</organization>';
    $organizationsTag[] = '</organizations>';
    return $organizationsTag;
}


/**
 * Creates and returns manifest Items for imsmanifest.xml
 * @since 0.5
 * @param string $url                   url to tmp folder
 * @param string $folder                tmp folder
 * @param string $cur                   curriculum
 * @param string $resourceTagItems      pointer to resourceTagItems
 * @return string 
 */
function manifestItems($url, $folder, $cur, &$resourceTagItems){
    $resourceURL = $url.''.$folder;             //URL to resource
    
    $manifestItems[] = '<item identifier="root">';  
    
    //Thema 0 muss Nachrichtenforum in Moodle sein! Evtl Curriculumsbeschreibung abbilden
    $manifestItems[] = '<item identifier="I_Topic0">';
    $manifestItems[] = '<title>0</title>';
    $manifestItems[] = '<item identifier="I_Topic0_Content" identifierref="I_Topic0_Forum">';
    $manifestItems[] = '<title>Nachrichtenforum</title>'; 
    $manifestItems[] = '</item>';
    $manifestItems[] = '</item>';
    
    //Schleife ab Thema 1
    for($i = 0;$i < count($cur->terminal_objectives); $i++){
        $manifestItems[] = '<item identifier="I_'.$cur->terminal_objectives[$i]->id.'">';
        $manifestItems[] = '<title>'.$cur->terminal_objectives[$i]->terminal_objective.'</title>';
        for($j = 0;$j < count($cur->terminal_objectives[$i]->enabling_objectives);$j++){
            if ($cur->terminal_objectives[$i]->enabling_objectives[$j]){
                $manifestItems[] = '<item identifier="I_'.$cur->terminal_objectives[$i]->enabling_objectives[$j]->id.'">'; 
                $manifestItems[] = '<title>'.$cur->terminal_objectives[$i]->enabling_objectives[$j]->enabling_objective.'</title>';
                $manifestItems[] = '</item>';
                //If available - add ressources
                for ($k = 0;$k < count($cur->terminal_objectives[$i]->enabling_objectives[$j]->files);$k++){
                    if ($cur->terminal_objectives[$i]->enabling_objectives[$j]->files[$k]){
                        $manifestItems[] = '<item identifier="I_'.$cur->terminal_objectives[$i]->enabling_objectives[$j]->id.''.$cur->terminal_objectives[$i]->enabling_objectives[$j]->files[$k]->id.'" identifierref="I_'.$cur->terminal_objectives[$i]->enabling_objectives[$j]->id.''.$cur->terminal_objectives[$i]->enabling_objectives[$j]->files[$k]->id.'_R">'; //>Wenn Material rein soll    
                        $manifestItems[] = '<title>'.$cur->terminal_objectives[$i]->enabling_objectives[$j]->files[$k]->description.'</title>';         
                        $manifestItems[] = '</item>';

                        //Add item to ressource Tag ///todo: der Ressource-Type muss hier überprüft werden zzt. nur URL's
                        //Case URL
                        if ($cur->terminal_objectives[$i]->enabling_objectives[$j]->files[$k]->type = 'url'){
                            $resourceTagItems[] = '<resource identifier="I_'.$cur->terminal_objectives[$i]->enabling_objectives[$j]->id.''.$cur->terminal_objectives[$i]->enabling_objectives[$j]->files[$k]->id.'_R" type="imswl_xmlv1p1">';
                            $resourceTagItems[] = '<file href="i_'.$cur->terminal_objectives[$i]->enabling_objectives[$j]->id.''.$cur->terminal_objectives[$i]->enabling_objectives[$j]->files[$k]->id.'_FURL/weblink.xml"/>';
                            $resourceTagItems[] = '</resource>';
                        } else {
                            $resourceTagItems[] = '<resource identifier="I_'.$cur->terminal_objectives[$i]->enabling_objectives[$j]->id.''.$cur->terminal_objectives[$i]->enabling_objectives[$j]->files[$k]->id.'_R" type="imswl_xmlv1p1">';
                            //echo 
                            $resourceTagItems[] = '<file href="i_'.$cur->terminal_objectives[$i]->enabling_objectives[$j]->id.''.$cur->terminal_objectives[$i]->enabling_objectives[$j]->files[$k]->id.'_FURL/weblink.xml"/>';
                            $resourceTagItems[] = '</resource>';
                        }
                        // Create new Ressource Folder and File
                        $resourceFolderName = "/i_".$cur->terminal_objectives[$i]->enabling_objectives[$j]->id."".$cur->terminal_objectives[$i]->enabling_objectives[$j]->files[$k]->id."_FURL";
                        newUrlResource($resourceURL, $resourceFolderName , $cur->terminal_objectives[$i]->enabling_objectives[$j]->files[$k]->description, implode('/', array_slice(explode('/', 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']), 0, -1)).'/'.$cur->terminal_objectives[$i]->enabling_objectives[$j]->files[$k]->context_path.$cur->terminal_objectives[$i]->enabling_objectives[$j]->files[$k]->path.$cur->terminal_objectives[$i]->enabling_objectives[$j]->files[$k]->filename); //todo pfad überprüfen stimmt wahrscheinlich nicht
                        //End Case URL
                    }
                }
            }  
        }
        
        $manifestItems[] = '</item>';
    }
    $manifestItems[] = '</item>';

    return $manifestItems;
}

/**
  * Creates and returns manifest Resources for imsmanifest.xml
  * 
  * @since 0.5
  *
  * @param string $url                          url to tmp folder
  * @param string $folder                       tmp folder
  * @param string $resourceTagItems             resourceTagItems
  */
function manifestResources($url,$folder, $resourceTagItems){
    $manifestResources[] = '<resources>';
    // Topic 0 Forum
    $itemIdentifier = 'I_Topic0_Forum';
    $resourceURL = $url.''.$folder;             //URL to resource
    
    $manifestResources[] = '<resource identifier="'.$itemIdentifier.'" type="imsdt_xmlv1p1">';
    $manifestResources[] = '<file href="'.$itemIdentifier.'/discussion.xml"/>';
    $manifestResources[] = '</resource>';
    
    newDiscussion($resourceURL, $itemIdentifier);
    
    // add Resources Item    
    if (isset($resourceTagItems)){
    array_push($manifestResources, implode("\n",$resourceTagItems));                                     //Add Resources Items
    }
    ////hier können Resourcen eingefügt werden
    $manifestResources[] = '</resources>';
    
    return $manifestResources;
}

/**
  * Add Forum Resources to ItemResource-Folder
  * 
  * @since 0.5
  *
  * @param string $url                          url to tmp folder
  * @param string $folder                       tmp folder
  */
function newDiscussion($url,$folder){
    mkdir($url.'/'.$folder, 0777);                                                           //create ItemResouce-Folder                          
    $fileHandle = fopen($url.'/'.$folder.'/discussion.xml', 'w') or die("can't open file");  //create discussion.xml
    
    unset($fileContent);
    $fileContent = xmlHeader();                                                             //Header in fileContent-Variable schreiben
    array_push($fileContent, implode("\n",discussionTopicOpeningTag()));                    //Add discussionTopicOpeningTag()
    array_push($fileContent, implode("\n",discussionContent()));                            //Add discussionTopicClosingTag()
    array_push($fileContent, implode("\n",discussionTopicClosingTag()));                    //Add discussionTopicClosingTag()
    
    if (!fwrite($fileHandle, implode("\n", $fileContent))){
        echo "Can't write to discussion.xml";
        exit;
    }
    fclose($fileHandle);
 }   
 
 /**
  * Add discussion (opening Tag) 
  * 
  * @since 0.5
  *
  */
 function discussionTopicOpeningTag(){
     $discussionTopicOpeningTag[] = '<topic '.CC_XMLNS_DISCUSSION.' '.CC_XSI.' '.CC_SCHEMALOCATION_DISCUSSION.'>';
     return $discussionTopicOpeningTag;
 }
 
/**
* Add discussion (closing Tag) 
* 
* @since 0.5
*
*/
function discussionTopicClosingTag(){
    $discussionTopicClosingTag[] = '</topic>';
    return $discussionTopicClosingTag;

}

/**
* Add discussion content 
* 
* @since 0.5
*
*/
function discussionContent(){
    $discussionContent[] = CC_DISCUSSION_TITLE;
    $discussionContent[] = CC_DISCUSSION_DESCRIPTION;

    return $discussionContent;
}

/**
* Add URL Resources 
* 
* @since 0.5
*
* @param string $url                          url to tmp folder
* @param string $folder                       tmp folder
* @param string $title                        Title of Link
* @param string $webLink                      URL
*/
function newUrlResource($url, $folder, $title, $webLink){
    mkdir($url.''.$folder, 0777);                                                           //create UrlResouce-Folder                          
    $fileHandle = fopen($url.'/'.$folder.'/weblink.xml', 'w') or die("can't open file");  //create Weblink.xml
    
    unset($fileContent);
    $fileContent = xmlHeader();                                                             //Header in fileContent-Variable schreiben
    array_push($fileContent, implode("\n",urlResourceTopicOpeningTag()));                    //Add urlResourceTopicOpeningTag()
    array_push($fileContent, implode("\n",urlResourceContent($title, $webLink)));            //Add urlResourceTopicClosingTag()
    array_push($fileContent, implode("\n",urlResourceTopicClosingTag()));                    //Add urlResourceTopicClosingTag()
    
    if (!fwrite($fileHandle, implode("\n", $fileContent))){
        echo "Can't write to discussion.xml";
        exit;
    }
    fclose($fileHandle);
 } 
 
/**
* Add URL (opening tag) 
* 
* @since 0.5
*/
 function urlResourceTopicOpeningTag(){
     $urlResourceTopicOpeningTag[] = '<webLink '.CC_XMLNS_WEBLINK.' '.CC_XSI.' '.CC_SCHEMALOCATION_WEBLINK.'>';
     return $urlResourceTopicOpeningTag;
 }

 /**
* Add URL (closing tag) 
* 
* @since 0.5
*/
 function urlResourceTopicClosingTag(){
     $urlResourceTopicClosingTag[] = '</webLink>';
     return $urlResourceTopicClosingTag;
     
 }
 
/**
* Add URL (content) 
* 
* @since 0.5
* @param string $title                        Title of Link
* @param string $webLink                      URL
*/
 function urlResourceContent($title, $webLink){
     $urlResourceContent[] = '<title>'.$title.'</title>';
     $urlResourceContent[] = '<url href="'.str_replace("&", "-and-", $webLink).'" target="_self"/>'; //öffnet Weblink  in einem neuen Fenster
     //str_replace, da sonst '&'  einen fehler beim import in moodle verursacehn --> fehler bei moodle!
     
     return $urlResourceContent;
 }
 
/**
* zip backup file, move .imscc file to backupfolder and delete temp files
* 
* @since 0.5
* @param string $url                          url of backup folder (where imscc files are finally saved)
* @param string $backupURL                    url of tmp folder
* @param string $timestamp_folder              Name of timestamp Folder
* @param string $cur_id                       id of curriculum
* @param string $userID                      id of current user
*/
function zipBackup($url, $backupURL, $timestamp_folder, $cur_id, $userID){
   // ini_set("max_execution_time", 300);                                               // increase script timeout value
    
    $zip = new ZipArchive();                                                            // create object
    if ($zip->open($backupURL.''.$timestamp_folder.'.imscc', ZIPARCHIVE::CREATE) !== TRUE) {   // open archive
        die ("Could not open archive");
    }
    
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($url.''.$timestamp_folder));    // initialize an iterator // pass it the directory to be processed
    
    foreach ($iterator as $key=>$value) {     // iterate over the directory // add each file found to the archive
        if (substr($key, -2) != '/.' AND substr($key, -3) != '/..'){   //exclude . and ..
            $zip->addFile(realpath($key), str_replace($url, '/', $key)) or die ("ERROR: Could not add file: $key"); //str_replace: $url abschneiden, da sonst der komplette Pfad als Ordnerstuktur in der zip erscheinz
        }
    }
    $zip->close();
                                                                          // close and save archive
    
    //delete temporary files
    $files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($url.''.$timestamp_folder, RecursiveDirectoryIterator::SKIP_DOTS),
    RecursiveIteratorIterator::CHILD_FIRST);                                            //CHILD_FIRST ist wichtig, damit erst die unterordner/Dateien gelöscht werden
    foreach ($files as $fileinfo) {
        $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
        $todo($fileinfo->getRealPath());
    }
    rmdir($url.''.$timestamp_folder);                                                    // Grundfolder löschen
    
    $backup = new Backup();
    $backup->add($backupURL, $timestamp_folder.'.imscc', $cur_id, $userID);
 
    return $timestamp_folder.'.imscc'; 
}
?>