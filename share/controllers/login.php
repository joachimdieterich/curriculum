<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename login.php
* @copyright  2013 Joachim Dieterich  {@link http://www.joachimdieterich.de}
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
global $TEMPLATE, $PAGE; 

$user       = new User();
$message    = '';
if(filter_input(INPUT_POST, 'reset', FILTER_UNSAFE_RAW)) {
    $user->username = (filter_input(INPUT_POST,     'username', FILTER_UNSAFE_RAW));     
    $user->load('username', $user->username, true);
    /* write creator of this user to reset password. todo: write acutal teacher/admin*/
    $mail   = new Mail();
    $mail->sender_id    = $user->id;
    $mail->receiver_id  = $user->creator_id; //current Teacher
    $mail->subject      = 'Passwort vergessen';
    $mail->message     .= '<p><strong>'.$user->resolveUserId($user->id, 'full').'</strong> hat das Passwort vergessen. Über den folgenden Link können Sie es zurücksetzen.';
    $mail->message     .= '<br><a onclick="formloader(\'password\',\'reset\','.$user->id.');">Passwort zurücksetzen</a></p>';
    $mail->postMail('reset');
    $PAGE->message[] = array('message' => 'Ihr Passwort wird durch den Administrator zurückgesetzt.', 'icon' => 'fa-key text-success'); 
    
    
} else if(filter_input(INPUT_POST, 'login', FILTER_UNSAFE_RAW)) {
    $user->username = (filter_input(INPUT_POST,     'username', FILTER_UNSAFE_RAW));     
    $user->password = (md5(filter_input(INPUT_POST, 'password', FILTER_UNSAFE_RAW)));
    $TEMPLATE->assign('username', $user->username);                 // Benutzername bei falschem Passwort automatisch einsetzen.
    
    if($user->checkLoginData()) { 
        if (isset($_SESSION['wantsurl'])){  
            $PAGE->wantsurl = $_SESSION['wantsurl'];                // save wantsurl in $PAGE, session gets destroyed!
        }
        session_destroy();                                          // Verhindert, dass eine bestehende Session genutzt wird --> verursacht Probleme (token / uploadframe)
        session_start();
        
        $_SESSION['username']   = $user->username;
        $_SESSION['timein']     = time();
        $user->load('username', $user->username, true);
        
        $user->setLastLogin();
        
        //Nutzungsbedingungen akzeptiert?
        if ($user->checkTermsOfUse() == false){
           header('Location:../share/request/getTermsofUse.php'); exit();
        }
        route($user); 
    } else { 
        $PAGE->message[] = array('message' => 'Benutzername bzw. Passwort falsch.', 'icon' => 'fa-key text-warning');     
    }  
    
} else if (filter_input(INPUT_POST, 'terms', FILTER_UNSAFE_RAW)){
    switch (filter_input(INPUT_POST, 'Submit', FILTER_UNSAFE_RAW)) {
        case 'Ja':  $user->load('username', $_SESSION['username'], true);
                    $user->acceptTerms();
                    route($user);
            break;
        case 'Nein':header('Location:index.php?action=logout'); 
            break;
        default:
            break;
    }
}

$TEMPLATE->assign('page_title',  'Login');
$TEMPLATE->assign('breadcrumb',  array('Login' => 'index.php?action=login'));
$TEMPLATE->assign('message',     $message);

function route($usr){
    global $PAGE;
    $confirmed = $usr->getConfirmed();
    switch ($confirmed) {
        case 1:     if (isset($PAGE->wantsurl)){            //if user wants a url -> redirect
                        header('Location:'.$PAGE->wantsurl); 
                    } else {
                        header('Location:index.php?action=dashboard'); 
                    }
            break;
        case 2:     //header('Location:index.php?action=password&login=first');//ab Version 0.5 BETA nicht verwendet // --> 1. Login nach erfolgreichem Registrieren
            break;
        case 3:     $_SESSION['FORM']       = new stdClass();
                    $_SESSION['FORM']->id   = null;
                    $_SESSION['FORM']->form = 'password';
                    $_SESSION['FORM']->func = 'changePW';
                    header('Location:index.php?action=dashboard');
            break;
        case 4:     //$PAGE->message[] = 'Ihr Account wurde noch nicht durch den Administrator freigegeben. Bitte probieren Sie es später noch einmal.'; //wurde für die PL Version herausgenommen // --> noch nicht freigegeben
            break; 
        default:    break;
    }   
}