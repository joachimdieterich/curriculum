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
* This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by  
* the Free Software Foundation; either version 3 of the License, or (at your option) any later version.                                   
*                                                                       
* This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of        
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details:                          
*                                                                       
* http://www.gnu.org/copyleft/gpl.html      
*/

/**
 * Assign array to $TEMPLATE
 * @global object $TEMPLATE
 * @param mixed $var
 * @param string $prefix
 */
function assign_to_template($var, $prefix = ''){
    global $TEMPLATE;
    foreach($var as $key => $value){
        $TEMPLATE->assign($prefix.$key, $value);                      /**$TEMPLATE->assign('my_username',  $_SESSION['USER']->username);*/
    }       
}

/**
 * php file functions 
 * http://php.net/manual/de/function.readdir.php#87733
 * @param string $dir
 * @param string $request
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
                            break;
   }
   return $listDir;
} 

/**
 * get current page url with with port and protocol
 * @return string 
 */
function curPageURL() {
 $pageURL = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
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
 * Set paginator
 * @global object $CFG, $INSTITUTION, PAGE
 * @param string $instance
 * @param array $template
 * @param array $resultData
 * @param string $returnVar
 * @param string $currentURL 
 */
function setPaginator($instance, $template, $data, $returnVar, $currentURL, $config = false) {
    global $CFG, $USER;
    $SmartyPaginate         = new SmartyPaginate(); 
    $SmartyPaginate->connect($instance);
    $CFG->paginator_name    = &$instance;  
    if ($instance == 'inboxPaginator' || $instance == 'outboxPaginator'){       // Mail Paginatoren haben anderes Limit, evtl. für jeden Paginator indiv. machen
        $SmartyPaginate->setLimit($CFG->mail_paginator_limit, $instance);
    } else {          
        if (filter_input(INPUT_GET, 'paginator_limit', FILTER_UNSAFE_RAW) && filter_input(INPUT_GET, 'paginator', FILTER_UNSAFE_RAW)){
            SmartyPaginate::setLimit(filter_input(INPUT_GET, 'paginator_limit', FILTER_UNSAFE_RAW), filter_input(INPUT_GET, 'paginator', FILTER_UNSAFE_RAW));
        } else {
            $SmartyPaginate->setLimit($USER->paginator_limit, $instance);
        }
    }
    $SmartyPaginate->setUrl($currentURL, $instance);
    $SmartyPaginate->setUrlVar($instance, $instance);
    $SmartyPaginate->setPrevText('zurück',$instance);
    $SmartyPaginate->setNextText('vor',$instance);
    $SmartyPaginate->setFirstText('Anfang',$instance);
    $SmartyPaginate->setLastText('Ende',$instance);       
    if ($data){
        $SmartyPaginate->setTotal(count($data), $instance);
        if ($SmartyPaginate->getCurrentIndex($instance) >= count($data)){ //resets paginators current index (if data was deleted)
            $SmartyPaginate->setCurrentItem(1, $instance); 
        }
        /* get all ids*/
        $all = array();
        foreach($data as $d){ $all[] = $d->id; }
        SmartyPaginate::setSelectAll(implode(",", $all), $instance);    //set all ids of data to paginator selectall
        
        $template->assign($returnVar, array_slice($data, $SmartyPaginate->getCurrentIndex($instance), $SmartyPaginate->getLimit($instance)), $instance); //hack for message paginator
        SmartyPaginate::setData(array_slice($data, $SmartyPaginate->getCurrentIndex($instance), $SmartyPaginate->getLimit($instance)), $instance);
        SmartyPaginate::setConfig($config, $instance); // set config
    } else {
        $template->assign($returnVar. NULL);
    }
    
    $template->assign('currentUrlId', $SmartyPaginate->getCurrentIndex($instance)+1); 
    $SmartyPaginate->assign($template, $instance, $instance);
}

/**
 * reset paginator instance
 * @param string $instance 
 */
function resetPaginator($instance){  //resets Paginator to index 1
    $SmartyPaginate = new SmartyPaginate(); 
    $SmartyPaginate->connect($instance);
    $SmartyPaginate->reset($instance);
    
}

/**
 * returns ORDER BY string
 * @param string $instance
 * @param table array table shortcuts
 * @return string
 */
function orderPaginator($instance, $table=null){
    $search = SmartyPaginate::getSort('search', $instance);
    
    if ($table){
       if (strpos(strtoupper($search), 'LIKE')){
            $t      = $table[SmartyPaginate::_getOrder($instance)]; //get proper table shortcut
            $search = substr_replace($search, $t.'.', 5, 0);        //add "table." to query
        } 
    }
    $order  = SmartyPaginate::getSort('order', $instance);
    $sort   = SmartyPaginate::getSort('sort', $instance) ;
    if ($order){ 
        return $search.' '. $order.' '.$sort;             
    } else {
       return '';
    }
}



function removeUrlParameter($url, $param) {
    if (is_array($param)){
            $u = $url;
        foreach($param as $p){
            $u = preg_replace('/[?&]'.$p.'=[^&]+(&|$)/','$1',$u);
        }
        return $u;
    } else {
        return preg_replace('/[?&]'.$param.'=[^&]+(&|$)/','$1',$url);
    }
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
 * convert byte to kbyte
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
        if ($_POST === $_SESSION['LASTPOST']){ 
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
function renderList($formname, $dependency, $data_dir, $ID_Postfix, $targetID, $returnFormat, $multipleFiles, $id){
    global $CFG;
    $file = new File();
    $files = $file->getFiles($dependency, $id);?>
    <div id="div<?php echo $ID_Postfix ?>" class="floatleft" style="display:none;">
    <form name="<?php echo $formname ?>" action="<?php echo $formname ?>" method="post" enctype="multipart/form-data">
        <div> 
        <table>
            <tr><?php 
            if (!empty($files)){
                for ($i=0; $i < count($files); $i++){?>
                    <td>  
                       <?php echo RENDER::filenail($files, $ID_Postfix, $i, true, true); ?>
                    </td> <?php 
                    if (($i+1) % 5 == 0) { ?> 
                    </tr><tr> <?php 
                    }    
                }
            } else { ?>
                   <td><div  class="filelist filenail">Keine Dateien vorhanden. </div></td><?php
            } ?>    
        </tr>
    </table>
    </div>
</form>
 <?php if ($targetID != 'NULL'){ // verhindert, dass der Button angezeigt wird wenn das Target NULL ist ?>
    <div id="uploadframe_footer" class="uploadframe_footer" >
        <input type="submit"  value="Datei(en) verwenden" onclick="iterateListControl('<?php echo 'div'.$ID_Postfix ?>','<?php echo $ID_Postfix ?>','<?php echo $targetID;?>','<?php echo $returnFormat;?>','<?php echo $multipleFiles;?>');"/>
    </div>
    <?php } ?>        
<div id="<?php echo $ID_Postfix; ?>uploadframe_info" class="uploadframe_footer" style="visibility:hidden;">
    <div id="<?php echo $ID_Postfix; ?>p_information" class="floatleft" style="width:593px; ">
        <table>
            <tr><td class="p_info"><strong>Titel:</strong></td>        <td id="<?php echo $ID_Postfix; ?>p_title" class="p_info "></td></tr>
            <tr><td class="p_info"><strong>Beschreibung:</strong></td> <td id="<?php echo $ID_Postfix; ?>p_description" class="p_info "></td></tr>
            <tr><td class="p_info"><strong>Author:</strong></td>       <td id="<?php echo $ID_Postfix; ?>p_author" class="p_info "></td></tr>
            <tr><td class="p_info"><strong>Lizenz</strong></td>        <td id="<?php echo $ID_Postfix; ?>p_licence" class="p_info "></td></tr>
        </table>
    </div>
</div>
   
</div>
<?php   
}


function renderSelect($name, $label, $values, $select){?>
    <p><label><?php echo $name;?></label>
        <select id="<?php echo $label;?>" name="<?php echo $label;?>" class="centervertical"> <?php 
            foreach ($values as $value) {
                ?><option value="<?php echo $value['value'];?>" data-skip="1" <?php if ($select == $value['value']){echo 'selected';}?> > <?php echo $value['label'];?></option><?php
            }?>
        </select>
    </p><?php
}

/**
 * checks capability for a given role
 * @param string $capability
 * @param int $role_id
 * @param boolean $thow_exception 
 * @return boolean 
 */
function checkCapabilities($capability = null, $role_id = null, $thow_exception = true){
    $capabilities = new Capability();
    $capabilities->capability = $capability; 
    $capabilities->role_id    = $role_id; 
    
    if ($capabilities->checkCapability()){
        return true;
    } else {
        if ($thow_exception){
            $role       = new Roles();
            $role->id   = $capabilities->role_id;
            $role->load();
            throw new CurriculumException('Als <strong>'.$role->role.'</strong> verfügen Sie nicht über die erforderliche Berechtigung ('.$capabilities->capability.').', 1);
        }
        return false; 
    }
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
function array2str($array, $pre = ' ', $pad = '', $sep = ', '){
    $str = '';
    if(is_array($array)) {
        if(count($array)) {
            foreach($array as $v) {
                if(is_array($v)) {
                    $str .= array2str($v, $pre, $pad, $sep);
                } else {
                    $str .= $pre.$v.$pad.$sep; 
                }
            }
            $str = substr($str, 0, -strlen($sep));
        }
    } else {
        $str .= $pre.$array.$pad;
    }
    return $str;
}

/**
 * for debugging objects
 * @param object $obj
 * @param int $level 
 */
 function object_to_array($obj, $level = 0) {       
        echo'<div style="margin-top: 70px;"></div>';
        $arrObj = is_object($obj) ? get_object_vars($obj) : $obj;
        if (is_object($obj)){ echo '<details style=""><summary>'.get_class($obj).'</summary>';}
        foreach ($arrObj as $key => $val) {  
            echo '<p style="text-indent:'.$level.'00px; margin-bottom: -2px;">';
            echo '<label>',$key,': </label>';
            $val = (is_array($val) || is_object($val)) ? object_to_array($val,$level+1) : $val;    
            if  ($val != (is_array($val) || is_object($val))){
                echo $val,'</p>';
            }
        }
        echo '</details>';
}

/**
    * get token
    * @return string 
    */
function getToken() { 
    $s = strtoupper(md5(uniqid(rand(),true))); 
    $uniquetoken = 
        substr($s,0,8) . 
        substr($s,8,4) . 
        substr($s,12,4). 
        substr($s,16,4). 
        substr($s,20); 
    return $uniquetoken;
}

/**
 * rotate Images based on exif info 
 * @param type $imagePath
 * @return type
 */
function getOrientedImage($imagePath){
        $image = imagecreatefromstring(file_get_contents($imagePath));
        $exif = exif_read_data($imagePath);
        if(!empty($exif['Orientation'])) {
            switch($exif['Orientation']) {
                case 8:
                    $image = imagerotate($image,90,0);
                    break;
                case 3:
                    $image = imagerotate($image,180,0);
                    break;
                case 6:
                    $image = imagerotate($image,-90,0);
                    break;
            }
        }
        return $image;
    }  

 /**
  * 
  * @param string $saveToDir
  * @param string $imagePath
  * @param string $imageName
  * @param int $max_x
  * @param int $max_y
  * @param string $context
  */   
function saveThumbnail($saveToDir, $imageName, $max_x, $max_y, $size = '') {  
    $ext = array(); //preg_matches
    preg_match("'^(.*)\.(gif|jpe?g|png)$'i", $imageName, $ext);
    if (isset($ext[2])){
        switch (strtolower($ext[2])) {
            case 'jpg' : 
            case 'jpeg': $im    = getOrientedImage($saveToDir.$imageName);        //use this function to check if Image has an other orientation
                         break;
            case 'gif' : $im    = imagecreatefromgif($saveToDir.$imageName);
                         break;
            case 'png' : $im    = imagecreatefrompng($saveToDir.$imageName);
                         break;
            default    : $stop  = true;
                         break;
        }
    } else {$stop  = true;}
    
    if (!isset($stop)) {
        $x = imagesx($im);
        $y = imagesy($im);
    
        if (($max_x/$max_y) < ($x/$y)) {
            $save = imagecreatetruecolor($x/($x/$max_x), $y/($x/$max_x));
        } else {
            $save = imagecreatetruecolor($x/($y/$max_y), $y/($y/$max_y));
        }
        
        //imagecopyresized($save, $im, 0, 0, 0, 0, imagesx($save), imagesy($save), $x, $y);
        imagecopyresampled($save, $im, 0, 0, 0, 0, imagesx($save), imagesy($save), $x, $y); 
        imagepng($save, "{$saveToDir}{$ext[1]}_".$size.".png", 5);
        imagedestroy($im);
        imagedestroy($save);
    }
} 
/**
 *  Generate Thumbnails
 *  Sizes of Thumbnails
 *  saveThumbnail($upload_dir, $filename,  18,  27,'xt');
 *  saveThumbnail($upload_dir, $filename, 100, 100,'t');
 *  saveThumbnail($upload_dir, $filename, 150, 125,'qs');
 *  saveThumbnail($upload_dir, $filename, 150, 225,'xs');
 *  saveThumbnail($upload_dir, $filename, 226, 340,'s');
 *  saveThumbnail($upload_dir, $filename, 400, 600,'m');
 *  saveThumbnail($upload_dir, $filename, 534, 800,'l');
 * @param string $upload_dir
 * @param string $filename
 * @param string $context
 */
function generateThumbnail($upload_dir, $filename, $context){   
    saveThumbnail($upload_dir, $filename,  100,  100,'t'); // !!! For FilePreview    
    switch ($context){ 
            case "userFiles":   break;
            case "curriculum":
            case "institution":
            case "userView":    saveThumbnail($upload_dir, $filename, 150, 225,'xs');
                                saveThumbnail($upload_dir, $filename, 534, 800,'l');
                                break;  
            case "subjectIcon": break;
            case "avatar":      saveThumbnail($upload_dir, $filename,  18,  27,'xt');
                                saveThumbnail($upload_dir, $filename, 150, 225,'xs');
                                break;
            case "badge":       saveThumbnail($upload_dir, $filename, 150, 125,'qs');
                                break;
            case "editor":      saveThumbnail($upload_dir, $filename, 100,  100,'t');
                                break;  
        }
}


function human_filesize($bytes, $decimals = 2) {
  $sz = 'BKMGTP';
  $factor = floor((strlen($bytes) - 1) / 3);
  return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
}

function translate_size($size){
    switch ($size){
            case "xt":   return 'Thumbnail klein'; 
            case "t" :   return 'Thumbnail';
            case "qs":   return 'kleines Quadrat';
            case "xs":   return 'extra klein'; 
            case "s" :   return 'klein';
            case "m" :   return 'medium';
            case "l" :   return 'groß';
            default  :   return ''; 
    }
}

function validate_msg($v_error, $field){
   if (isset($v_error[$field]['message'][0])){ 
       echo '<div class="error">',$v_error[$field]['message'][0],'</div>';     
   } 
}

function str_lreplace($search, $replace, $subject)
{
    $pos = strrpos($subject, $search);

    if($pos !== false)
    {
        $subject = substr_replace($subject, $replace, $pos, strlen($search));
    }

    return $subject;
}

/**
 * Löscht alle Datien / Unterordner im angegebenen Ordner 
 * @param type $folder
 */
function delete_folder($folder){
    $files      = new RecursiveIteratorIterator(                                                    // Lösche temporäre Dateien
    new RecursiveDirectoryIterator($folder, RecursiveDirectoryIterator::SKIP_DOTS),
    RecursiveIteratorIterator::CHILD_FIRST);                                                        // CHILD_FIRST wichtig, damit erst die unterordner/Dateien gelöscht werden
    foreach ($files as $fileinfo) {
        $todo   = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
        $todo($fileinfo->getRealPath());
    }
    rmdir($folder);
}

/**
 * Prüft ob ein Verzeichnis existiert und legt dieses an, falls es nicht existiert.
 * Im Gegensatz zu mkdir wird keine Warnung ausgegeben, falls Verzeichnis existiert.
 * @param string $path
 */
function silent_mkdir($path, $permission = 0775){
    if (!file_exists($path)) {
        umask(0);
        if (mkdir($path, $permission, true)){  // legt Verzeichnis an    
            return true;
        }
    } else {
        return true;
    }
}


/**
 * Traverse an elements children and collect those nodes that
 * have the tagname specified in $tagName. Non-recursive
 * Source: http://stackoverflow.com/questions/3049648/php-domelementgetelementsbytagname-anyway-to-get-just-the-immediate-matching 
 *
 * @param DOMElement $element
 * @param string $tagName
 * @return array
 */
function getImmediateChildrenByTagName(DOMElement $element, $tagName)
{
    $result = array();
    foreach($element->childNodes as $child)
    {
        if($child instanceof DOMElement && $child->tagName == $tagName)
        {
            $result[] = $child;
        }
    }
    return $result;
}