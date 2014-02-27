<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename login.php
* @copyright  2013 Joachim Dieterich  {@link http://www.joachimdieterich.de}
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

global $TEMPLATE; 

$user = new User();
$message = '';              //Achtung, nicht $PAGE-> da Sessionabhängig! die Session wird  nach der Anmeldung erzeugt
$TEMPLATE->assign('login_stuff', 'Login');
//addLog($_SESSION['username'], 'view', curPageURL(), 'login'); //Addlog
$TEMPLATE->assign('my_username', ''); //Loginname setzen für header setzen --> Leer

if((isset($_POST['login']) OR isset($_GET['username'])) AND !isset($_GET['newregister']) ) {
    
    $user->username = (isset($_GET['username']) && trim($_GET['username'] != '') ? $_GET['username'] : $_POST['username']);     
    $user->password = (isset($_GET['password']) && trim($_GET['password'] != '') ? $_GET['password'] : md5($_POST['password']));

    $TEMPLATE->assign('username', $user->username);  //wenn Benutzername eingegeben wurde und zb password falsch name wieder anzeigen

    if($user->checkLoginData()) { 
        $cron = new Cron;
        $cron->detectExpiredObjective();
        loginExistingUser($user->username, $user->password);
    } else {  
            $PAGE->message[] =  'Benutzername bzw. Passwort falsch.';   
    }  
}
/*elseif (isset($_POST['register'])){
    header('Location:index.php?action=register'); //
}*/
elseif (isset($_GET['newregister'])){  //nur ausführen wenn seite nicht automatisch von registerview zurüchgeleitet wurde  
    $TEMPLATE->assign('username', $_GET['username']);  //wenn Benutzername eingegeben wurde und zb password falsch name wieder anzeigen
}
elseif (isset($_GET['token'])){ //wenn über soap (webservice angemeldet
    loginWithToken($_GET['token']);
}

switch (getagent()){ //Info für den IE
    case "InternetExplorer":    $PAGE->message[] = 'Diese Seite wird im Internetexplorer evtl. falsch dargestellt. Bitte nutzen sie <strong>Firefox, Google Crome oder Safari</strong>';
                                break;
    case "Firefox": // $PAGE->message[] = 'Meldung für FF...';
                                break;
    default:                    break;
} 
      $TEMPLATE->assign('message', $message);
   
      
/*
 * Funktionen
 */      
      
/**
 * Login with token
 * @param string $token 
 */        
function loginWithToken($token) {
        $authenticate = new Authenticate();
        $authenticate->token = $token;
        $authenticate->getUser('token');
        
        switch($authenticate->status){// 1 == User exists, 0 == Register New User
            case 0:  loginNewUser($authenticate->username, $authenticate->password, $authenticate->creation_time);
                     break; 
            case 1:  loginExistingUser($authenticate->username, $authenticate->password, $authenticate->creation_time);
                     break; 
            default: break; 
        }
}

/**
 * Login existing User
 * @param string $username
 * @param string $password md5 string
 * @param timestamp $creation_time 
 */
function loginExistingUser($username, $password, $creation_time = 0){ //if tokencreationtime ist not set then it has to be 0
    $existing_user = new User();
    $existing_user->username = $username;
    $existing_user->password = $password;
    
    if ($creation_time-time() <= 60){ //Timeout == 60 sec Global regeln
        $_SESSION['authenticated'] = true;
        $_SESSION['username'] = $existing_user->username;
        $_SESSION['timein'] = time();

        $existing_user->setLastLogin();
        $confirmed = $existing_user->getConfirmed();
        if($confirmed == 3) {
            header('Location:index.php?action=password&login=webservice');
        } elseif ($confirmed == 4) {
            $PAGE->message[] = 'Ihr Account wurde noch nicht durch den Administrator freigegeben. Bitte probieren Sie es später noch einmal.';
        } else {
            header('Location:index.php?action=dashboard');				
        }
    } else {
        $PAGE->message[] = 'Das Token ist abgelaufen (> 60 Sekunden).';
    }
}

/**
 * login new user
 * @param string $username
 * @param string $password md5 string
 * @param timestamp $creation_time 
 */
function loginNewUser($username, $password, $creation_time= 0){
    if ($creation_time-time() <= 60){ //Timeout == 60 sec Global regeln
        $_SESSION['authenticated'] = true;
        $_SESSION['username'] = $username;
        $_SESSION['timein'] = time();
        
        $authenticate = new Authenticate();
        $authenticate->username = $username;
        $authenticate->password = $password;
        $authenticate->getUser('login');
        $ws_username = $authenticate->ws_username;
        
        $new_user = new User(); 
        $new_user->firstname    = $authenticate->firstname;
        $new_user->lastname     = $authenticate->lastname;
        $new_user->email        = $authenticate->email;
        $new_user->password     = $authenticate->password;
        $new_user->confirmed    = 1;
        //??? experimental
        $new_user->creator_id   = -1; //shoult be id of webservice
        object_to_array($new_user);
        global $USER; 
        $USER->role_id = 1;
        //??? experimental
        if ($new_user->add() == false) {  //Wenn Benutzeranlegung in DB fehlgeschlagen ist
            $PAGE->message[] = 'Der Benuzerkonnte ist bereits vergeben. Bitte verwenden sie einen anderen Benutzernamen. ';
        } else {
            $institution = new Institution();
            if ($new_user->enroleToInstitution($institution->getInstitutionByUserName($ws_username))) { //Wenn einschreiben erfolgreich war getINstitutionByUserName ermittelt die Institution des ws_users --> Achtung problematisch, wenn dieser in mehrere Institutionen eingeschrieben ist.
                $PAGE->message[] = 'Sie wurden erfolgreich angemeldet.';
            }
        }
        $new_user->setLastLogin();
        $confirmed = $new_user->getConfirmed();
        if($confirmed == 3) {
            header('Location:index.php?action=password&login=webservice');
        } elseif ($confirmed == 4) {
            $PAGE->message[] = 'Ihr Account wurde noch nicht durch den Administrator freigegeben. Bitte probieren Sie es später noch einmal.';
        } else {
            header('Location:index.php?action=dashboard');				
        }
    } else {
        $PAGE->message[] = 'Das Token ist abgelaufen (> 60 Sekunden).';
    }
}
?>