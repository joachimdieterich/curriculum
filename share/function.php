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

/**
 * Assign array to $TEMPLATE
 * @global object $TEMPLATE
 * @param mixed $var
 * @param string $prefix
 */
function assign_to_template($var, $prefix = '') {
    global $TEMPLATE;
    foreach ($var as $key => $value) {
        $TEMPLATE->assign($prefix . $key, $value);                      /*         * $TEMPLATE->assign('my_username',  $_SESSION['USER']->username); */
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
            if ($handler = opendir($dir)) {
                while (($sub = readdir($handler)) !== FALSE) {
                    if ($sub != "." && $sub != ".." && $sub != "Thumb.db" && $sub != "Thumbs.db") {
                        if (is_file($dir . "/" . $sub)) {
                            $listDir[] = $sub;
                        } elseif (is_dir($dir . "/" . $sub)) {
                            $listDir[$sub] = read_folder_directory($dir . "/" . $sub, $request);
                        }
                    }
                }
                closedir($handler);
            }
            break;
        case "thisDir": $i = 0;
            $listDir = array();
            if ($handler = opendir($dir)) {
                while (($sub = readdir($handler)) !== FALSE) {
                    if ($sub != "." && $sub != ".." && $sub != "Thumb.db" && $sub != "Thumbs.db") {
                        if (is_dir($dir . "/" . $sub)) {
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
        $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
    }
    return $pageURL;
}

/**
 * get current page name
 * @return string 
 */
function curPageName() {
    return substr($_SERVER["SCRIPT_NAME"], strrpos($_SERVER["SCRIPT_NAME"], "/") + 1);
}

/**
 * Set paginator
 * @global object $CFG, $INSTITUTION, PAGE
 * @param string $instance
 * @param array $resultData
 * @param string $returnVar
 * @param string $currentURL 
 * @param array $config
 * @param string $width 
 */
function setPaginator($instance, $data, $returnVar, $currentURL, $config = false, $width = 'col-sm-12') {
    global $CFG, $USER, $TEMPLATE;
    $SmartyPaginate = new SmartyPaginate();
    $SmartyPaginate->connect($instance);
    //$CFG->paginator_name    = &$instance;  
    /* if ($instance == 'inboxPaginator' || $instance == 'outboxPaginator'){       // Mail Paginatoren haben anderes Limit, evtl. für jeden Paginator indiv. machen
      $SmartyPaginate->setLimit($CFG->settings->mail_paginator_limit, $instance);
      } else {
      if (filter_input(INPUT_GET, 'paginator_limit', FILTER_UNSAFE_RAW) && filter_input(INPUT_GET, 'paginator', FILTER_UNSAFE_RAW)){
      SmartyPaginate::setLimit(filter_input(INPUT_GET, 'paginator_limit', FILTER_UNSAFE_RAW), filter_input(INPUT_GET, 'paginator', FILTER_UNSAFE_RAW));
      }
      } */
        $SmartyPaginate->setLimit($_SESSION['USER']->paginator_limit, $instance); //get paginator_limit from USER
    $SmartyPaginate->setUrl($currentURL, $instance);
    $SmartyPaginate->setWidth($width, $instance);
    $SmartyPaginate->setUrlVar($instance, $instance);
    $SmartyPaginate->setPrevText('zurück', $instance);
    $SmartyPaginate->setNextText('vor', $instance);
    $SmartyPaginate->setFirstText('Anfang', $instance);
    $SmartyPaginate->setLastText('Ende', $instance);
    if ($data) {
        //$SmartyPaginate->setTotal(count($data), $instance);
        //if ($SmartyPaginate->getCurrentIndex($instance) >= count($data)){ //resets paginators current index (if data was deleted)
        if ($SmartyPaginate->getCurrentIndex($instance) >= $_SESSION['SmartyPaginate'][$instance]['item_total']) { //resets paginators current index (if data was deleted)
            $SmartyPaginate->setCurrentItem(1, $instance);
        }
        /* get all ids */
        $all = array();
        foreach ($data as $d) {
            $all[] = $d->id;
        }
        
        SmartyPaginate::setSelectAll($all, $instance);    //set all ids of data to paginator selectall

        $TEMPLATE->assign($returnVar, array_slice($data, $SmartyPaginate->getCurrentIndex($instance), $SmartyPaginate->getLimit($instance)), $instance); //hack for message paginator
        SmartyPaginate::setData($data, $instance);
        SmartyPaginate::setConfig($config, $instance); // set config
    } else {
        SmartyPaginate::setData(null, $instance);
    }

    //$TEMPLATE->assign('currentUrlId', $SmartyPaginate->getCurrentIndex($instance)+1); //paginator benutzt keine url mehr
    $SmartyPaginate->assign($TEMPLATE, $instance, $instance);
}

/**
 * reset paginator instance
 * @param string $instance 
 */
function resetPaginator($instance) {  //resets Paginator to index 1
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
function orderPaginator($instance, $table = null, $id = 'id') {
    $SmartyPaginate = new SmartyPaginate();
    $SmartyPaginate->connect($instance);
    $SmartyPaginate->setLimit($_SESSION['USER']->paginator_limit, $instance); //get paginator_limit from USER
    //error_log(json_encode($_SESSION['SmartyPaginate'][$instance]));
    if ($instance != '') { //if paginator is set
        if (SmartyPaginate::_getSearch($instance) != null) {
            $search = ' AND (';
            $fields = SmartyPaginate::_getSearchField($instance);
            for ($i = 0; $i < count($fields); $i++) {           // generates sql search query based on field array
                $t = $table[$fields[$i]]; //get proper table shortcut

                $search .= $t . '.' . $fields[$i] . ' LIKE \'%' . SmartyPaginate::_getSearch($instance) . '%\' ';
                if ($i + 1 != count($fields)) {
                    $search .= 'OR ';
                } else {
                    $search .= ')';
                }
            }
        } else {
            $search = ''; //if no search is set, set default to get shorter query
        }
        $order = SmartyPaginate::_getOrder($instance);

        if ($order != '' AND isset($table[$order])) {
            $order = ' ORDER BY ' . $table[$order] . '.' . $order;
        } else {
            $order = ' ORDER BY ' . $id . $order; //hack to prevent blank pages if $table[$order !isset]
        }
        $limit = SmartyPaginate::getLimit($instance);
        if ($limit != false) {
            if (((int) $_SESSION['SmartyPaginate'][$instance]['current_item'] - 1) < 0) { // -1 would return all table entries
                $begin = 0;
            } else {
                $begin = ((int) $_SESSION['SmartyPaginate'][$instance]['current_item'] - 1);
            }
            $limit = " LIMIT " . $begin . ", " . $limit;
        } else {
            $limit = '';
        }
        //error_log($limit);
        $sort = SmartyPaginate::getSort('sort', $instance);

        return $search . ' ' . $order . ' ' . $sort . ' ' . $limit;
    } else { //if Paginator is not set yet get set Limit
        return "";
    }
}

function set_item_total($paginator) {
    $dbc = DB::prepare('SELECT FOUND_ROWS()');
    $dbc->execute(array());

    $_SESSION['SmartyPaginate'][$paginator]['item_total'] = $dbc->fetchColumn();
}

function removeUrlParameter($url, $param) {
    if (is_array($param)) {
        $u = $url;
        foreach ($param as $p) {
            $u = preg_replace('/[?&]' . $p . '=[^&]+(&|$)/', '$1', $u);
        }
        return $u;
    } else {
        return preg_replace('/[?&]' . $param . '=[^&]+(&|$)/', '$1', $url);
    }
}

/**
 * convert mb to byte
 * @param int $mb
 * @return int 
 */
function convertMbToByte($mb) {
    return $mb * 1048576;
}

/**
 * convert byte to mb
 * @param int $byte
 * @return int 
 */
function convertByteToMb($byte) {
    return $byte / 1048576;
}

/**
 * convert int kbyte to byte
 * @param int $kbyte
 * @return int 
 */
function convertKbyteToByte($kbyte) {
    return $kbyte * 1024;
}

/**
 * convert byte to kbyte
 * @param int $byte
 * @return int 
 */
function convertByteToKbyte($byte) {
    return $byte / 1024;
}

function return_bytes($val) {
    $val = trim($val);
    $last = $val[strlen($val) - 1];
    $val = substr($val, 0, -1); //remove size extention
    switch (strtolower($last)) {
        case 'g': $val *= (1024 * 1024 * 1024); //1073741824 // The 'G' modifier is available since PHP 5.1.0
            break;
        case 'm': $val *= (1024 * 1024); //1048576
            break;
        case 'k': $val *= 1024;
            break;
    }

    return $val;
}

/**
 * Die Funktion detect_reload() wird über die index.php aufgerufen und verhindert, dass Formulare mit identischem Inhalt 2x abgeschickt werden. 
 */
function detect_reload() {
    if (isset($_SESSION['LASTPOST'])) {
        if ($_POST === $_SESSION['LASTPOST']) {
            $_POST = NULL;
        } else { //für Firefox
            $_SESSION['LASTPOST'] = $_POST;
        }
    } else { //Für Safari
        $_SESSION['LASTPOST'] = $_POST;
    }
}

function renderSelect($name, $label, $values, $select) {
    ?>
    <p><label><?php echo $name; ?></label>
        <select id="<?php echo $label; ?>" name="<?php echo $label; ?>" class="centervertical"> <?php
    foreach ($values as $value) {
        ?><option value="<?php echo $value['value']; ?>" data-skip="1" <?php if ($select == $value['value']) {
            echo 'selected';
        } ?> > <?php echo $value['label']; ?></option><?php }
    ?>
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
function checkCapabilities($capability = null, $role_id = null, $thow_exception = true, $modal = false) {
    $capabilities = new Capability();
    $capabilities->capability = $capability;
    $capabilities->role_id = $role_id;

    if ($capabilities->checkCapability()) {
        return true;
    } else {
        if ($modal AND $thow_exception == true) { // Rendert den Fehler im modal
            $html = Form::error(array('capability' => $capability,
                        'page_name' => ''));
            $html = Form::modal(array('title' => 'Fehler', 'content' => $html));
            echo json_encode(array('html' => $html));
            die();
        }
        if ($thow_exception) {
            global $USER;
            throw new CurriculumException('Als <strong>' . $USER->role_name . '</strong> verfügen Sie nicht über die erforderliche Berechtigung (' . $capability . ').', 1);
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
function array2str($array, $pre = ' ', $pad = '', $sep = ', ') {
    $str = '';
    if (is_array($array)) {
        if (count($array)) {
            foreach ($array as $v) {
                if (is_array($v)) {
                    $str .= array2str($v, $pre, $pad, $sep);
                } else {
                    $str .= $pre . $v . $pad . $sep;
                }
            }
            $str = substr($str, 0, -strlen($sep));
        }
    } else {
        $str .= $pre . $array . $pad;
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
    if (is_object($obj)) {
        echo '<details style=""><summary>' . get_class($obj) . '</summary>';
    }
    foreach ($arrObj as $key => $val) {
        echo '<p style="text-indent:' . $level . '00px; margin-bottom: -2px;">';
        echo '<label>', $key, ': </label>';
        $val = (is_array($val) || is_object($val)) ? object_to_array($val, $level + 1) : $val;
        if ($val != (is_array($val) || is_object($val))) {
            echo $val, '</p>';
        }
    }
    echo '</details>';
}

/**
 * get token
 * @return string 
 */
function getToken() {
    $s = strtoupper(md5(uniqid(rand(), true)));
    $uniquetoken = substr($s, 0, 8) .
            substr($s, 8, 4) .
            substr($s, 12, 4) .
            substr($s, 16, 4) .
            substr($s, 20);
    return $uniquetoken;
}

/**
 * rotate Images based on exif info 
 * @param type $imagePath
 * @return type
 */
function getOrientedImage($imagePath) {
    $image = imagecreatefromstring(file_get_contents($imagePath));
    $exif = exif_read_data($imagePath);
    if (!empty($exif['Orientation'])) {
        switch ($exif['Orientation']) {
            case 8:
                $image = imagerotate($image, 90, 0);
                break;
            case 3:
                $image = imagerotate($image, 180, 0);
                break;
            case 6:
                $image = imagerotate($image, -90, 0);
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
    preg_match("'^(.*)\.(gif|jpe?g|png|pdf)$'i", $imageName, $ext);
    if (isset($ext[2])) {
        switch (strtolower($ext[2])) {
            case 'jpg' :
            case 'jpeg': $im = getOrientedImage($saveToDir . $imageName);        //use this function to check if Image has an other orientation
                break;
            case 'gif' : $im = imagecreatefromgif($saveToDir . $imageName);
                break;
            case 'png' : $im = imagecreatefrompng($saveToDir . $imageName);
                break;

            case 'pdf' : global $CFG;
                if (exec($CFG->settings->ghostscript_path . 'gs -version') != null) { //ghostscript not available
                    exec($CFG->settings->ghostscript_path . 'gs -dFirstPage=1 -dLastPage=1 -sDEVICE=pngalpha -sOutputFile=' . $saveToDir . basename($imageName, '.pdf') . '_t.png -r144 ' . $saveToDir . $imageName);
                    //error_log('generate pdf thumb:'.$saveToDir.basename($imageName, '.pdf').' from'.$saveToDir.$imageName);
                }
                $stop = true;
                break;
            default : $stop = true;
                break;
        }
    } else {
        $stop = true;
    }

    if (!isset($stop)) {
        $x = imagesx($im);
        $y = imagesy($im);

        if (($max_x / $max_y) < ($x / $y)) {
            $save = imagecreatetruecolor($x / ($x / $max_x), $y / ($x / $max_x));
        } else {
            $save = imagecreatetruecolor($x / ($y / $max_y), $y / ($y / $max_y));
        }

        //imagecopyresized($save, $im, 0, 0, 0, 0, imagesx($save), imagesy($save), $x, $y);
        imagecopyresampled($save, $im, 0, 0, 0, 0, imagesx($save), imagesy($save), $x, $y);
        imagepng($save, "{$saveToDir}{$ext[1]}_" . $size . ".png", 5);
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
function generateThumbnail($upload_dir, $filename, $context) {
    saveThumbnail($upload_dir, $filename, 100, 100, 't'); // !!! For FilePreview    
    switch ($context) {
        case "userFiles": break;
        case "curriculum":
        case "institution":
        case "solution":
        case "generate_certificate":
            saveThumbnail($upload_dir, $filename, 150, 225, 'xs');
            saveThumbnail($upload_dir, $filename, 534, 800, 'l');
            break;
        case "subjectIcon": break;
        case "avatar": saveThumbnail($upload_dir, $filename, 18, 27, 'xt');
            saveThumbnail($upload_dir, $filename, 150, 225, 'xs');
            break;
        case "badge": saveThumbnail($upload_dir, $filename, 150, 125, 'qs');
            break;
        case "editor": saveThumbnail($upload_dir, $filename, 100, 100, 't');
            break;
    }
}

function human_filesize($bytes, $decimals = 1) {
    $sz = 'BKMGTP';
    $factor = floor((strlen($bytes) - 1) / 3);
    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
}

function translate_size($size) {
    switch ($size) {
        case "xt": return 'Thumbnail klein';
        case "t" : return 'Thumbnail';
        case "qs": return 'kleines Quadrat';
        case "xs": return 'extra klein';
        case "s" : return 'klein';
        case "m" : return 'medium';
        case "l" : return 'groß';
        default : return '';
    }
}

/**
 * Returns status bootstrap class (true) or error Message (default) 
 * @param array $v_error
 * @param string $field
 * @param boolean $return_status
 * @return string
 */
function validate_msg($v_error, $field, $return_status = false) {
    if (!is_array($v_error)) {
        return;
    }
    switch ($return_status) {
        case true: if (isset($v_error[$field]['message'][0])) {
                return 'has-error'; //-> bootstrap class
            } else {
                return 'has-success';
            }
            break;

        default: if (isset($v_error[$field]['message'][0])) {
                return '<i class="fa fa-times-circle-o"></i> ' . $v_error[$field]['message'][0];
            }
            break;
    }
}

function str_lreplace($search, $replace, $subject) {
    $pos = strrpos($subject, $search);
    if ($pos !== false) {
        $subject = substr_replace($subject, $replace, $pos, strlen($search));
    }

    return $subject;
}

/**
 * Löscht alle Datien / Unterordner im angegebenen Ordner 
 * @param type $folder
 */
function delete_folder($folder) {
    $files = new RecursiveIteratorIterator(// Lösche temporäre Dateien
            new RecursiveDirectoryIterator($folder, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST);                                                        // CHILD_FIRST wichtig, damit erst die unterordner/Dateien gelöscht werden
    foreach ($files as $fileinfo) {
        $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
        $todo($fileinfo->getRealPath());
    }
    rmdir($folder);
}

/**
 * Prüft ob ein Verzeichnis existiert und legt dieses an, falls es nicht existiert.
 * Im Gegensatz zu mkdir wird keine Warnung ausgegeben, falls Verzeichnis existiert.
 * @param string $path
 */
function silent_mkdir($path, $permission = 0775) {
    if (!file_exists($path)) {
        umask(0);
        if (mkdir($path, $permission, true)) {  // legt Verzeichnis an    
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
function getImmediateChildrenByTagName(DOMElement $element, $tagName) {
    $result = array();
    foreach ($element->childNodes as $child) {
        if ($child instanceof DOMElement && $child->tagName == $tagName) {
            $result[] = $child;
        }
    }
    return $result;
}

function resolveFileTypeById($id) {
    $f = new File();
    $f->load($id);
    return resolveFileType($f->id);
}

function resolveFileType($type) {
    switch ($type) {
        /* Archive */
        case '.cab':
        case '.lha':
        case '.lzh':
        case '.z7':
        case '.rar':
        case '.bz2':
        case '.tar': $class = 'fa fa-file-archive-o';
            break;
        /* Data-Images */
        case '.dmg':
        case '.img':
        case '.iso': $class = 'fa fa-hdd-o';
            break;
        /* Audio */
        case '.acc':
        case '.aif':
        case '.aiff':
        case '.mp3':
        case '.wav':
        case '.wma': $class = 'fa fa-file-audio-o';
            break;
        /* Videos */
        case '.avi':
        case '.flv':
        case '.mp4':
        case '.mpg':
        case '.mpeg':
        case '.mov':
        case '.swf':
        case '.wmv': $class = 'fa fa-file-movie-o';
            break;
        /* Images */
        case '.bmp':
        case '.gif':
        case '.ai':
        case '.png':
        case '.svg':
        case '.psd':
        case '.tif':
        case '.tiff':
        case '.jpeg':
        case '.jpg': $class = 'fa fa-file-image-o';
            break;

        /* Text */
        case '.txt':
        case '.rtf':
            $class = 'fa fa-file-text-o';
            break;
        /* Word */
        case '.doc':
        case '.docx':
        case '.dot': $class = 'fa fa-file-word-o';
            break;

        /* Excel */
        case '.xlsx':
        case '.xlsm':
        case '.xlsb':
        case '.xltx':
        case '.xltm':
        case '.xlt':
        case '.xlm':
        case '.xlw':
        case '.xls': $class = 'fa fa-file-word-o';
            break;
        /* Code */
        case '.class':
        case '.php':
        case '.cpp':
        case '.htm':
        case '.html':
        case '.xml':
        case '.js': $class = 'fa fa-file-word-o';
            break;
        /* PDF */
        case '.pdf': $class = 'fa fa-file-pdf-o';
            break;
        /* Powerpoint */
        case '.ppa':
        case '.pps':
        case '.ppsm':
        case '.ppsx':
        case '.pot':
        case '.potx':
        case '.potm':
        case '.ppt':
        case '.pptm':
        case '.pptx': $class = 'fa fa-file-powerpoint-o';
            break;
        case '.url': $class = 'fa fa-link';
            break;
        default: $class = 'fa fa-file-o';
            break;
    }
    return $class;
}

function PHPArrayObjectSorter($array, $sortBy, $direction = 'asc') {
    $sortedArray = array();
    $tmpArray = array();
    foreach ($array as $obj) {
        $tmpArray[] = $obj->$sortBy;
    }

    if ($direction == 'asc') {
        asort($tmpArray);
    } else {
        arsort($tmpArray);
    }

    foreach ($tmpArray as $k => $tmp) {
        $sortedArray[] = $array[$k];
    }

    return $sortedArray;
}

function truncate($text, $chars = 25) {
    $text = substr($text, 0, $chars);
    if (strlen($text) >= $chars) {
        $text = $text . "...";
    }
    return $text;
}

/**
 * Returns an plugin instance.
 *
 * @param string $type type of plugin
 * @param string $plugin name of plugin
 * @return An instance of the required  plugin.
 */
function get_plugin($type, $plugin) {
    global $CFG;
    // Check the plugin exists first.
    if (!exists_plugin($type, $plugin)) {
        error_log($type . 'pluginnotfound: ' . $plugin);
    }

    // Return plugin instance.
    require_once("{$CFG->plugins_root}$type/$plugin/plugin.php");
    $class = $type . '_plugin_' . $plugin;

    return new $class;
}

/**
 * Returns whether a given plugin exists.
 * @param string $type type of plugin
 * @param string $plugin to check for. Defaults to the global setting in {@link $CFG}.
 * @return boolean Whether the plugin is available.
 */
function exists_plugin($type, $plugin) {
    global $CFG;
    if (file_exists("{$CFG->plugins_root}/$type/$plugin/plugin.php")) {
        return is_readable("{$CFG->plugins_root}/$type/$plugin/plugin.php");
    }
    return false;
}

/**
 * reload user
 */
function session_reload_user() {
    global $USER, $CFG, $TEMPLATE;
    $USER = new User();

    $USER->load('username', $_SESSION['username']);                             // Benutzer aus DB laden
    $USER->password = '';                                               // Passwort aus Session löschen
    $_SESSION['USER'] = & $USER;
    assign_to_template($_SESSION['USER'], 'my_');

    $semester = new Semester();                                                 // akt. Semester /Lernzeitraum  laden
    $_SESSION['SEMESTER'] = $semester->getMySemesters($USER->id);             // .todo. akt. Semester der Institution laden, da sonst bei neu angelegten Benutzern semester_id evtl. nicht stimmt (wenn Benutzer in anderer Institution angelegt wurden)

    $institution = new Institution();
    $CFG->settings->timeout = $institution->getTimeout($USER->institution_id);            // Set timeout based on Institution
    $TEMPLATE->assign('global_timeout', $CFG->settings->timeout);
}

/**
 * Generates HTML element based on element template
 * @global type $CFG
 * @param type $element
 * @param type $object
 * @return type
 */
function element($element, $object, $string = NULL, $prefix = NULL) {
    global $CFG;
    if ($string == NULL) {
        $string = file_get_contents(dirname(__FILE__) . '/elements/e_' . $element . '.html');
    }

    foreach ($object as $key => $value) {
        if (!is_array($value) AND ! is_object($value)) {
            $string = str_replace('{{' . $prefix . $key . '}}', $value, $string);
        } else {
            $string = element(null, $value, $string, $key . '->'); //call element again to replace next level
        }
    }

    /* replace elements */
    /* if ($prefix != NULL){
      $string = element_functions($string, $prefix, $object);
      }
      $string = element_functions($string, 'this', $object); */
    return $string;
}

/* based on https://gist.github.com/colourstheme/d992abc081df381ce656 */

function ak_convert_hex2rgba($color, $opacity = false) {
    $default = 'rgb(0,0,0)';

    if (empty($color))
        return $default;

    if ($color[0] == '#')
        $color = substr($color, 1);
    if (strlen($color) == 6)
        $hex = array($color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5]);
    elseif (strlen($color) == 3)
        $hex = array($color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2]);
    elseif (strlen($color) == 8) {
        $hex = array($color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5]);
        $hex_opacity = $color[6] . $color[7];
    } elseif (strlen($color) == 4) {
        $hex = array($color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2]);
        $hex_opacity = $color[3] . $color[3];
    } else
        return $default;

    $rgb = array_map('hexdec', $hex);
    if (isset($hex_opacity)) {
        $opacity = implode(array_map('hexdec', array($hex_opacity)));
        $opacity = round(1 / 255 * $opacity, 2);
    }
    if ($opacity) {
        if (abs($opacity) > 1)
            $opacity = 1.0;

        $output = 'rgba(' . implode(",", $rgb) . ',' . $opacity . ')';
    } else {
        $output = 'rgb(' . implode(",", $rgb) . ')';
    }
    return $output;
}

function random_file($dir, $type = 'jpg') {
    $files = glob($dir . '/*.' . $type);
    $file = array_rand($files);
    return basename($files[$file]);
}

function setChildren() {
    global $TEMPLATE, $USER;
    if (isset($_GET['child_id'])) {
        $_SESSION['USER']->child_selected = $_GET['child_id'];
    }
    $user = new User();
    $user->id = $USER->id;
    $children = $user->getChildren();
    if (!empty($children)) {
        $TEMPLATE->assign('myChildren', $children);
        if (isset($_SESSION['USER']->child_selected)) {
            $TEMPLATE->assign('my_child_id', $_SESSION['USER']->child_selected);
        } else {
            $TEMPLATE->assign('my_child_id', '');
        }
    }
}

function getContrastColor($hexcolor, $darkcolor = '#000000', $lightcolor = '#FFFFFF') {
    $hexcolor = str_replace('#', '', $hexcolor);
    switch ($len = strlen($hexcolor)) {
        case 3:
            $hexcolor = preg_replace("/(.)(.)(.)/", "\\1\\1\\2\\2\\3\\3", $hexcolor);
            break;
        case 6:
            break;
        default:
            error_log("Invalid hex length ($len). Must be a minimum length of (3) or maxium of (6) characters");
    }
    $r = hexdec(substr($hexcolor, 0, 2));
    $g = hexdec(substr($hexcolor, 2, 2));
    $b = hexdec(substr($hexcolor, 4, 2));
    $yiq = (($r * 299) + ($g * 587) + ($b * 114)) / 1000;
    return ($yiq >= 128) ? $darkcolor : $lightcolor;
}

/**
 * Filter an array of objects. https://gist.github.com/amnuts/6b907dce2ccf0c553e8c86380f786c6f
 *
 * You can pass in one or more properties on which to filter.
 *
 * If the key of an array is an array, then it will filtered down to that
 * level of node.
 * 
 * Example usages:
 * <code>
 * ofilter($items, 'size'); // filter anything that has value in the 'size' property
 * ofilter($items, ['size' => 3, 'name']); // filter anything that has the size property === 3 and a 'name' property with value
 * ofilter($items, ['size', ['user', 'forename' => 'Bob'], ['user', 'age' => 30]) // filter w/ size, have the forename value of 'Bob' on the user object of and age of 30
 * ofilter($items, ['size' => function($prop) { return ($prop > 18 && $prop < 50); }]);
 * </code>
 *
 * @param  array $array
 * @param  string|array $properties
 * @return array
 */
function ofilter($array, $properties) {
    if (empty($array)) {
        return;
    }
    if (is_string($properties)) {
        $properties = [$properties];
    }
    $isValid = function($obj, $propKey, $propVal) {
        if (is_int($propKey)) {
            if (!property_exists($obj, $propVal) || empty($obj->{$propVal})) {
                return false;
            }
        } else {
            if (!property_exists($obj, $propKey)) {
                return false;
            }
            if (is_callable($propVal)) {
                return call_user_func($propVal, $obj->{$propKey});
            }
            if (strtolower($obj->{$propKey}) != strtolower($propVal)) {
                return false;
            }
        }
        return true;
    };
    return array_filter($array, function($v) use ($properties, $isValid) {
        foreach ($properties as $propKey => $propVal) {
            if (is_array($propVal)) {
                $prop = array_shift($propVal);
                if (!property_exists($v, $prop)) {
                    return false;
                }
                reset($propVal);
                $key = key($propVal);
                if (!$isValid($v->{$prop}, $key, $propVal[$key])) {
                    return false;
                }
            } else {
                if (!$isValid($v, $propKey, $propVal)) {
                    return false;
                }
            }
        }
        return true;
    });
}

/*
 * generate input values for select out of an array 
 */

function generate_select_object($array) {
    $obj = new stdClass();
    foreach ($array as $key => $value) {
        $obj->label = $key;
        $obj->value = $value;
        $o[] = clone $obj;
    }
    return $o;
}

/*
function element_functions($string, $prefix, $object){
    global $$prefix;        //defines object with dynamic obj name
    global $temp_prefix;    
    $$prefix     = $object;
    $temp_prefix = $prefix;
    $string      = preg_replace_callback('/{{2}\b'.$prefix.'->\b([a-zA-Z:_]+\((([a-zA-Z0-9\$\'\_\:]+,?\s*)+)\))}{2}/', 
                        function($r){ 
                            global $temp_prefix;
                            global $$temp_prefix;
                            global $USER;
                            $content = '';
                            foreach($$temp_prefix AS $v){
                                $func       = str_replace("'", '', $r[2]);
                                $parameter  = explode(',', $func);
                                //error_log(json_encode($parameter[2]));
                                if (trim($parameter[1]) == '$this'){
                                    $content   .= element($parameter[0],$v,null, 'this'); //yet only function element() can be called|  $r[1] returns every function found by preg_replace_callback
                                }
                                if (isset($parameter[2])){  //check permissen 
                                    if (checkCapabilities(json_encode($parameter[2], $USER->role_id, false))){ 
                                        $content   .= element($parameter[0],$v); //yet only function element() can be called|  $r[1] returns every function found by preg_replace_callback
                                    }
                                } else { 
                                    $content   .= element($parameter[0],$v); //yet only function element() can be called|  $r[1] returns every function found by preg_replace_callback
                                }
                            }
                            return $content;
                        }, $string);
    unset($$prefix);
    unset($temp_prefix);
    return $string;
}*/
