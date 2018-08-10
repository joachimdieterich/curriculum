<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename update.php
* @copyright 2018 Joachim Dieterich
* @author Joachim Dieterich
* @date 2018.02.21 19:06
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
global $CFG, $PAGE, $USER, $TEMPLATE;
$TEMPLATE->assign('breadcrumb',  array('Update' => 'index.php?action=update'));
$TEMPLATE->assign('page_title', 'Update');  
$search = false;
$update          = new Updates();

if (isset($_GET) ){
    if (isset($_GET['filename'])){
        $update->load('filename', $_GET['filename']);
        if ($update->status != 1){
            $_GET['execute'] = true;
            include($CFG->share_root.'update/'.$_GET['filename'].'.php');
            
            $update->load('filename', $_GET['filename']);
            if ($UPDATE->installed == true){
                $update->status = 1;
            } else if ($UPDATE->installed == false) {
                $update->status = 2;     
            }
            $update->log    = $UPDATE->log;
            $update->update();
        }        
    }
} 

if (checkCapabilities('system:update', $USER->role_id, false)){
    $update_files    = scandir($CFG->share_root.'update');              // get update files
    if (count($update_files) == 2){ 
        $last_update = false; // no update file available
    } else {
        foreach ($update_files as $value) {
            switch ($value) {
                case '.':
                case '..':
                case '.DS_Store': //fix 
                    break;
                default:    //error_log($value);
                            if (!$update->load('filename', $value)){            // update(file) not in db --> add to db
                                global $UPDATE;
                                include($CFG->share_root.'update/'.$value);
                                
                                $update->filename       = $value;
                                $update->description    = $UPDATE->info;
                                $update->status         = 0;
                                error_log($value);
                                $update->add();
                            }
                            
                    break;
            }   
        }
    }

    
    $update_db       = $update->get(); //get update_list
    $TEMPLATE->assign('updates', $update_db);
    
    /*if ($last_update){
        global $UPDATE;
        include($CFG->share_root.'update/'.$last_update);
        $TEMPLATE->assign('updateinfo', $UPDATE->info);   
    }*/
    
    $TEMPLATE->assign('system_update', true);
}

