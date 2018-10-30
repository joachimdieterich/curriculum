<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* FormProcessor
* @package core
* @filename fp_wallet_sharing.php
* @copyright 2017 Joachim Dieterich
* @author Joachim Dieterich
* @date 2017.01.24 13:19
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
include_once(dirname(__FILE__).'/../setup.php');  // Klassen, DB Zugriff und Funktionen
include_once(dirname(__FILE__).'/../login-check.php');  //check login status and reset idletimer
global $USER, $CFG;
$USER            = $_SESSION['USER'];
if (!isset($_SESSION['PAGE']->target_url)){     //if target_url is not set -> use last PAGE url
    $_SESSION['PAGE']->target_url       = $_SESSION['PAGE']->url;
}

$wallet_sharing = new WalletSharing(); 

$gump                  = new Gump();    /* Validation */
$_POST                 = $gump->sanitize($_POST);       //sanitize $_POST
$gump->validation_rules(array(
'id'         => 'required',
/*'user_list'  => 'required',*/
'permission' => 'required',
'timerange'     => 'required'
));

$validated_data  = $gump->run($_POST);
if($validated_data === false) {/* validation failed */
    $_SESSION['FORM']            = new stdClass();
    $_SESSION['FORM']->form      = 'wallet_sharing';
    foreach($_POST as $key => $value){                                         
        $_SESSION['FORM']->$key  = $value;
    }
    $_SESSION['FORM']->error     = $gump->get_readable_errors();
    $_SESSION['FORM']->func      = $_POST['func']; 
} else {
    $wallet_sharing->wallet_id   = filter_input(INPUT_POST, 'id',          FILTER_VALIDATE_INT);
    $wallet_sharing->permission  = filter_input(INPUT_POST, 'permission',  FILTER_VALIDATE_INT);    
    $wallet_sharing->timerange   = filter_input(INPUT_POST, 'timerange',   FILTER_UNSAFE_RAW); // copy timestart and timeend from timerage
    
    /* Sharing wallet with user(s)*/
    if (isset($_POST['user_list'])){
        $wallet_sharing->context_id = $_SESSION['CONTEXT']['userFiles']->context_id;
        foreach ($_POST['user_list'] as $wallet_sharing->reference_id) {
            if ($wallet_sharing->isShared()){
                if ($wallet_sharing->update()){
                    $_SESSION['PAGE']->message[] = array('message' => 'Sammelmappenteilung aktualisiert', 'icon' => 'fa-share-alt text-success');
                }
            } else {
                if ($wallet_sharing->add()){
                    $_SESSION['PAGE']->message[] = array('message' => 'Sammelmappe geteilt', 'icon' => 'fa-share-alt text-success');
                }
            }
        }
    }
    
    /* Sharing wallet with groups(s)*/
    if (isset($_POST['group_list'])){       $wallet_sharing->context_id = $_SESSION['CONTEXT']['group']->context_id; }
    
    /* Sharing wallet with institution(s)*/
    if (isset($_POST['institution_list'])){ $wallet_sharing->context_id = $_SESSION['CONTEXT']['institution']->context_id; }
    
    $_SESSION['FORM']            = null;                     // reset Session Form object 
}

header('Location:'.$_SESSION['PAGE']->target_url);
