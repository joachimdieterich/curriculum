<?php
/**
*  This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename function.php - global functions
* @copyright 2013 Joachim Dieterich
* @author Joachim Dieterich
* @date 2013.03.08 13:26
* @license: 
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

global $CFG;
include ($CFG->document_root.'assets/scripts/libs/backup/imscc/cc_export.php'); //IMS CC Export-Funktion  anders einbinden
 
/**
 * Force Download of a given file
 * @param type $file 
 */
function forceDownload($file){
    $filename = basename($file);
    $size = filesize($file);
    header("Content-Type: application/force-download");
    header("Content-Disposition: attachment; filename=".$filename);
    header("Content-Length:".$size);
    readfile($file);
    
}

/**
 * get browser
 * @return string 
 */
function getagent(){
  if (strstr($_SERVER['HTTP_USER_AGENT'],'Opera')) {    
     $brows='Opera';
  } elseif (strstr($_SERVER['HTTP_USER_AGENT'],'MSIE'))
     $brows='InternetExplorer';
  elseif (strstr($_SERVER['HTTP_USER_AGENT'],'Firefox'))
     $brows='Firefox';
  elseif (strstr($_SERVER['HTTP_USER_AGENT'],'Chrome'))
     $brows='Chrome';
  elseif (strstr($_SERVER['HTTP_USER_AGENT'],'Safari'))
     $brows='Safari';
  elseif (strstr($_SERVER['HTTP_USER_AGENT'],'Mozilla'))
     $brows='Mozilla';     
  else
     $brows=$_SERVER['HTTP_USER_AGENT'];
  return $brows;
} 

/**
 * php file functions
 * @param string $request
 * @param string $dir
 * @return array 
 */
function read_folder_directory($dir, $request = "allDirFiles") { 
    switch ($request) {
        case "allDirFiles":    
                            $listDir = array(); 
                            if($handler = opendir($dir)) { 
                                while (($sub = readdir($handler)) !== FALSE) { 
                                    if ($sub != "." && $sub != ".." && $sub != "Thumb.db" && $sub != "Thumbs.db") { 
                                        if(is_file($dir."/".$sub)) { 
                                            $listDir[] = $sub; 
                                        }elseif(is_dir($dir."/".$sub)){ 
                                            $listDir[$sub] = read_folder_directory($dir."/".$sub, $request); 
                                        } 
                                    } 
                                } 
                                closedir($handler); 
                            } 
                            return $listDir;
                            break;
        case "thisDir":     $i = 0;
                            $listDir = array(); 
                            if($handler = opendir($dir)) { 
                                while (($sub = readdir($handler)) !== FALSE) { 
                                    if ($sub != "." && $sub != ".." && $sub != "Thumb.db" && $sub != "Thumbs.db") { 
                                        if (is_dir($dir."/".$sub)){ 
                                            $dirArray['id'] = $i;
                                            $dirArray['dir'] = $sub;
                                            $i++;
                                            $listDir[] = $dirArray;  //dirArray muss als Array übergeben werden, sonst funktioniert PulldownFeld nicht
                                        } 
                                    } 
                                } 
                                closedir($handler); 
                            } 
                            return $listDir;
                            break;
   }
                        
} 

/**
 * get current page url
 * @return string 
 */
function curPageURL() {
 $pageURL = 'http';
 //if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";} //??? funktioniert in MAMP nicht
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}

/**
 * get current page name
 * @return string 
 */
function curPageName() {
 return substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
}

/**
 * set paginator
 * @global object $CFG
 * @param string $instance
 * @param array $template
 * @param array $resultData
 * @param string $returnVar
 * @param string $currentURL 
 */
function setPaginator($instance, $template, $data, $returnVar, $currentURL) {
    global $CFG, $INSTITUTION;
    $SmartyPaginate = new SmartyPaginate(); //???neue SmartyPaginate registrieren
    $SmartyPaginate->connect($instance);
    $CFG->paginator_name = &$instance;
    
    $SmartyPaginate->setLimit($INSTITUTION->institution_paginator_limit, $instance);
    $SmartyPaginate->setUrl($currentURL, $instance);//$currentURL, $instance);
    $SmartyPaginate->setUrlVar($instance, $instance);

    if ($data == false){ //Hack Bugfix damit alle datensätze gelöscht werden können
        $template->assign('data', null); //keine Datensätze vorhanden
        $PAGE->message[] = 'Keine Datensätze vorhanden.';
        $template->assign('message', $PAGE->message);
    } else {
        $template->assign('data', true); //keine Datensätze vorhanden
        $SmartyPaginate->setTotal(count($data), $instance);
        if ($SmartyPaginate->getCurrentIndex($instance) >= count($data)){ //setzt paginator index zurück, wenn datensatz gelöscht wird, und auf der neuen seite (aktueller index) keine datensätze mehr sind
            $SmartyPaginate->setCurrentItem(1, $instance); 
        }
        $template->assign($returnVar, array_slice($data, $SmartyPaginate->getCurrentIndex($instance), $SmartyPaginate->getLimit($instance)), $instance);
    }
    $template->assign('currentUrlId', $SmartyPaginate->getCurrentIndex($instance)+1); //damit nach funktionen die selbe Seite angezeigt werden kann
    $SmartyPaginate->assign($template, $instance, $instance);
}

/**
 * reset paginator instance
 * @param string $instance 
 */
function resetPaginator($instance){  //Setzt den Paginator zurück auf den index 1
    $SmartyPaginate = new SmartyPaginate(); 
    $SmartyPaginate->connect($instance);
    $SmartyPaginate->setCurrentItem(1, $instance);
}

/**
 * convert mb to byte
 * @param int $mb
 * @return int 
 */
function convertMbToByte($mb){   
    return $mb*1048576;
}

/**
 * convert byte to mb
 * @param int $byte
 * @return int 
 */
function convertByteToMb($byte){
    return $byte/1048576;
}

/**
 * convert int kbyte to byte
 * @param int $kbyte
 * @return int 
 */
function convertKbyteToByte($kbyte){
    return $kbyte*1024;
}

/**
 * vonvert byte to kbyte
 * @param int $byte
 * @return int 
 */
function convertByteToKbyte($byte){
    return $byte/1024;
}
  
/**
 * Die Funktion detect_reload() wird über die index.php aufgerufen und verhindert, dass Formulare mit identischem Inhalt 2x abgeschickt werden. 
 */
function detect_reload(){    
 if (isset($_SESSION['LASTPOST'])){
        if ($_POST === $_SESSION['LASTPOST'] ){ 
            $_POST = NULL;
        } else { //für Firefox
            $_SESSION['LASTPOST'] = $_POST; 
        }
    } else { //Für Safari
        $_SESSION['LASTPOST'] = $_POST; 
    }
} 

/**
 * Die Funktion renderList() erzeugt die Dateilisten im Uploadframe
 * @param string $formname
 * @param array $files
 * @param string $data_dir
 * @param int $ID_Postfix
 * @param int $targetID
 * @param string $returnFormat
 * @param string $multipleFiles 
 */
function renderList($formname, $files, $data_dir, $ID_Postfix, $targetID, $returnFormat, $multipleFiles){
?><form name="<?php echo $formname ?>" action="<?php echo $formname ?>" method="post" enctype="multipart/form-data">
        <div> 
        <table>
            <tr><?php 
            if (!empty($files)){
            for ($i=0; $i < count($files); $i++){
            ?><td>
            <?php //wenn filetype unbekannt dann var setzen!!!
            echo '<div  class="filelist filenail" id="row'.$ID_Postfix.''.$files[$i]->id.'"' ?>  
                onclick="checkfile('<?php echo $ID_Postfix.''.$files[$i]->id;?>')"  
                onmouseover="previewFile('<?php echo $data_dir.''.$files[$i]->context_path.''.$files[$i]->path.''.$files[$i]->filename;?>')" 
                onmouseout="exitpreviewFile()"> <?php
            echo    '<a href="'.$data_dir.''.$files[$i]->context_path.''.$files[$i]->path.''.$files[$i]->filename.'"  target="_blank">
                     <div class="downloadbtn floatleft"></div>
                     </a><div class="deletebtn floatright" style="margin-right: -4px !important; "'?>  
                        onclick="deleteFile('<?php echo $ID_Postfix; ?>', '<?php echo $files[$i]->id;?>')"> <?php
            echo    '</div><p class="'.ltrim ($files[$i]->filetype, '.').'_btn filelisticon" ></p>
                     <p id="href_'.$files[$i]->id.'" class="filelink">'.$files[$i]->filename.'</p>
                     <input class="invisible" type="checkbox" id="'.$ID_Postfix.''.$files[$i]->id.'" name="id'.$ID_Postfix.'[]" value='.$files[$i]->id.' />
                     </div>';                             
            ?></td> 
            <?php if (($i+1) % 5 == 0) { 
            ?> </tr><tr > <?php }    
            }
        } else { ?>
            <p>Es wurden noch keine Dateien hochgeladen.</p><?php
        }            
        ?>    
        </tr>
    </table>
    </div>
</form>
<div class="uploadframe_footer">
    <input type="submit" name="Submit" value="Fenster schließen" onclick="self.parent.tb_remove();"/>
    <?php if ($targetID != 'NULL'){ // verhindert, dass der Button angezeigt wird wenn das Target NULL ist
    ?>
    <input type="submit"  value="Datei(en) verwenden" onclick="iterateListControl('<?php echo 'div'.$ID_Postfix ?>','<?php echo $ID_Postfix ?>','<?php echo $targetID;?>','<?php echo $returnFormat;?>','<?php echo $multipleFiles;?>');"/>
    <?php } ?>
</div>
<?php   
}


/**
 * Die Funktion renderDeleteMessage() erzeugt die Dateilisten im Uploadframe
 * @param string $message 
 */
function renderDeleteMessage($message) {
echo '<div class="border-top-radius contentheader">Information</div>
    <div id="popupcontent">
    <p>'.$message.'</p>
    <p><label></label><input type="submit" value="OK" onclick="reloadPage()"></p>
    </div>';
}


/**
 * add message
 * @global object $CFG
 * @param type $message 
 */
function addMessage($message){
    global $PAGE;
    $PAGE->message[] = $message;
}

/**
 * checks capability for a given role
 * @param string $capability
 * @param int $role_id
 * @return boolean 
 */
function checkCapabilities($capability = null, $role_id = null){
    $capabilities = new Capability();
    $capabilities->capability = $capability; 
    $capabilities->role_id    = $role_id; 
    
    return $capabilities->checkCapability();
}

/**
 * Converts a multidimensional array to string
 * @author http://blog.perplexedlabs.com/2008/02/04/php-array-to-string/
 * @param array $array
 * @param string $pre
 * @param string $pad
 * @param string $sep
 * @return string 
 */
function array2str($array, $pre = ' ', $pad = '', $sep = ', ')
{
    $str = '';
    if(is_array($array)) {
        if(count($array)) {
            foreach($array as $v) {
                if(is_array($v))
                $str .= array2str($v, $pre, $pad, $sep);
                else
                $str .= $pre.$v.$pad.$sep; 
            }
            $str = substr($str, 0, -strlen($sep));
        }
    } else {
        $str .= $pre.$array.$pad;
    }

    return $str;
}

/**
 * debug objects
 * @param object $obj
 * @param int $level 
 */
 function object_to_array($obj, $level = 0) {       
        $arrObj = is_object($obj) ? get_object_vars($obj) : $obj;
        foreach ($arrObj as $key => $val) {  
            echo '<p style="text-indent:'.$level.'00px; margin-bottom: -10px;">';
            echo '<label>',$key,': </label>';
            $val = (is_array($val) || is_object($val)) ? object_to_array($val,$level+1) : $val;    
            if  ($val != (is_array($val) || is_object($val))){
                echo $val,'</p>';
            }
        }
}

?>