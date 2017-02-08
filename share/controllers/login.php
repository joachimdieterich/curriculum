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
global $CFG, $TEMPLATE, $PAGE; 

$user       = new User();
$message    = '';
if(filter_input(INPUT_GET, 'install', FILTER_UNSAFE_RAW)) {
    if (file_exists(dirname(__FILE__).'/install.php')){
        unlink(dirname(__FILE__).'/install.php'); // delete install file after installation
    }
}
if (filter_input(INPUT_GET, 'username', FILTER_UNSAFE_RAW) AND filter_input(INPUT_GET, 'token', FILTER_UNSAFE_RAW)){
    $user->username = (filter_input(INPUT_GET,     'username', FILTER_UNSAFE_RAW));     
    $user->token    = (filter_input(INPUT_GET,     'token', FILTER_UNSAFE_RAW));     
    if($user->checkLoginData(true)) { 
        $user->load('username', $user->username, false);
        $user->set('confirmed', 2); //set confirmed to reset PW
        login($user);                                
    } else { 
        $PAGE->message[] = array('message' => 'Benutzername bzw. Passwort falsch.', 'icon' => 'fa-key text-warning');     
    } 
}
if(filter_input(INPUT_POST, 'guest', FILTER_UNSAFE_RAW) AND $CFG->guest_login == true) {
    $user->username = $CFG->guest_usr;
    $user->password = md5($CFG->guest_pwd);
    if($user->checkLoginData()) {
        login($user);
    }
} else if(filter_input(INPUT_POST, 'reset', FILTER_UNSAFE_RAW)) {
    $user->username = (filter_input(INPUT_POST,     'username', FILTER_UNSAFE_RAW));     
    $user->load('username', $user->username, false);
    /* write creator of this user to reset password. todo: write acutal teacher/admin*/
    $mail   = new Mail();
    $mail->sender_id    = $user->id;
    $institution        = new Institution();
    $institution_admins = $institution->getAdmin($user->id);
    if ($CFG->settings->messaging != 'email'){
        $mail->subject      = 'Passwort vergessen';
        $mail->message     .= '<p><strong>'.$user->resolveUserId($user->id, 'full').'</strong> hat das Passwort vergessen. Über den folgenden Link können Sie es zurücksetzen.';
        $mail->message     .= '<br><a onclick="formloader(\'password\',\'reset\','.$user->id.');">Passwort zurücksetzen</a></p>';   
        foreach ($institution_admins AS $admin_id){  //send mail to all institution admins... (if pw is reset, all mails to other admins will be deleted) todo. reset pw with email
            $mail->receiver_id  = $admin_id->user_id; 
            $mail->postMail('reset');
        }
    } else {
        $mail->subject      = 'Passwort zurücksetzen';
        $mail->message     .= '<p>Sehr geehrter '.$CFG->app_title.'- Nutzer, <br><br> Sie haben Ihr Passwort vergessen?<br><br> '
                           .  'Über den folgenden Link können Sie ein neues Passwort für Ihren Zugang festlegen. <a href="'.$CFG->base_url.'public/index.php?action=login&username='.$user->username.'&token='.$user->token.'"><strong> Passwort zurücksetzen</strong></a><br><br>'
                           .  'Wenn Ihnen Ihr bisheriges Passwort wieder eingefallen ist, können Sie sich wie gewohnt weiterhin unter <br>'.$CFG->base_url .' wieder anmelden. Danach verliert diese E-Mail ihre Gültigkeit.<br><br>'
                           .  'Wen sie keine Passwortänderung angefordert haben, können Sie diese E-Mail ignorieren.<br><br>Mit freundlichen Grüßen<br><br>Ihr curriculum-Server :)<br><br>'.$CFG->app_footer;
        if ($mail->postMail('reset')){
            $PAGE->message[] = array('message' => 'Email: "Passwort zurücksetzen" wurde erfolgreich verschickt.', 'icon' => 'fa-key text-success');
            $reset = true;
        }
    }
    $PAGE->message[] = array('message' => 'Ihr Passwort wird durch den Administrator zurückgesetzt.', 'icon' => 'fa-key text-success'); 
} else if(filter_input(INPUT_POST, 'login', FILTER_UNSAFE_RAW)) {
    $user->username = (filter_input(INPUT_POST,     'username', FILTER_UNSAFE_RAW));     
    $user->password = (md5(filter_input(INPUT_POST, 'password', FILTER_UNSAFE_RAW)));
    
    $TEMPLATE->assign('username', $user->username);                 // Benutzername bei falschem Passwort automatisch einsetzen.
    
    if($user->checkLoginData()) { 
        /* Check confirm status, if status == 2, but login data is correct set confirmed to 1 to prevent reset dialog */
        $c = $user->getConfirmed();
        if($c == 2){ 
            $user->load('username', $user->username, false);
            $user->set('confirmed', 1); 
        }
        login($user);
        
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

function login($user){
    global $CFG, $PAGE;
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
    if (($user->checkTermsOfUse() == false) OR ($user->username == $CFG->guest_usr)){
       header('Location:index.php?action=terms'); exit();
    }
    route($user);
}

function route($usr){
    global $PAGE,$CFG;
    $confirmed = $usr->getConfirmed();
    if(filter_input(INPUT_POST, 'reset', FILTER_UNSAFE_RAW)){ 
        $confirmed = 1; //hack to prevent case 2
    }
    switch ($confirmed) {
        case 1:     if (isset($PAGE->wantsurl)){            //if user wants a url -> redirect
                        header('Location:'.$PAGE->wantsurl); 
                    } else {
                        header('Location:index.php?action=dashboard'); 
                    }
            break;
        case 2:     $_SESSION['FORM']       = new stdClass();
                    $_SESSION['FORM']->id   = null;
                    $_SESSION['FORM']->form = 'password';
                    $_SESSION['FORM']->func = 'reset';
                    header('Location:'.$CFG->base_url.'public/index.php?action=dashboard');
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