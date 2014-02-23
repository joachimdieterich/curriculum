<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package core
 * @filename install.php
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

require_once(dirname(__FILE__).'../../setup.php');
require_once(dirname(__FILE__).'../../include.php');

global $TEMPLATE, $CFG, $PAGE;
$TEMPLATE->assign('db_host', '127.0.0.1');
$TEMPLATE->assign('install', 'Curriculum installieren');
$TEMPLATE->assign('my_username', '');
$TEMPLATE->assign('my_role_id', '-1');
$TEMPLATE->assign('step', 0);
$TEMPLATE->assign('countries', '');
$PAGE->message = '';
$cfg_file = dirname(__FILE__).'../../config.php';

                
if (isset($_GET)){ 
    switch ($_GET) {
        case isset($_GET['step']): load_Countries();
            $TEMPLATE->assign('step', $_GET['step']);
            break;

        default:
            break;
    }
}

if ($_POST){
    switch ($_POST) {
        case isset($_POST['step_0']):
            if (isset($_POST['license']) AND $_POST['license'] != '') {
            $TEMPLATE->assign('step', 1);
            }
            break;
        case isset($_POST['step_1']):
                    //echo $_SESSION['DOWNLOAD'];
                    if (isset($_SESSION['DOWNLOAD'])){ //go to page 2 - 
                        unset($_SESSION['DOWNLOAD']); 
                        $TEMPLATE->assign('step', 2);
                    } else {
                        $conn = mysql_connect($_POST['db_host'],$_POST['db_user'],$_POST['db_password']) 
                                or die('Verbindung schlug fehl: ' . mysql_error());
                        if (!$conn){
                            $PAGE->message[] = 'Kein Datenbankzugriff!';
                        } else {
                            $gump = new Gump();
                            $gump->validation_rules(array(
                                            'db_host'     => 'required',
                                            'db_user'     => 'required',
                                            'db_password' => 'required',
                                            'db_name'     => 'required'
                                            ));
                            $validated_data = $gump->run($_POST);
                            if($validated_data === false) {/* validation failed */
                                    foreach($_POST as $key => $value){
                                    $TEMPLATE->assign($key, $value);
                                    } 
                                    $TEMPLATE->assign('v_error', $gump->get_readable_errors());     
                                    $TEMPLATE->assign('step', 1); 
                                } else {/* validation successful */
                                    writeConfigFile($cfg_file, '$CFG->db_host', $_POST["db_host"]);
                                    writeConfigFile($cfg_file, '$CFG->db_user', $_POST["db_user"]);
                                    writeConfigFile($cfg_file, '$CFG->db_password ', $_POST["db_password"]);
                                    writeConfigFile($cfg_file, '$CFG->db_name', $_POST["db_name"]);
                                    writeConfigFile($cfg_file, '$CFG->BASE_URL', str_replace("public", "", implode('/', array_slice(explode('/', $_SERVER['REQUEST_URI']), 0, -1)))); //Generates BASE_URL
                                    //Backup erstellen
                                    if (mysql_select_db($_POST['db_name'])){
                                        $result = mysql_query("SHOW TABLES LIKE 'users'"); //check if DB is emty
                                        if (isset($_POST['dump']) AND $_POST['dump'] != '' AND mysql_num_rows($result) > 0) {
                                            /* Datei download erzwingen*/
                                            system( '/usr/bin/mysqldump -u' . $_POST['db_user']. ' -p' . escapeshellarg( $_POST['db_password'] ) . ' -h' . $_POST['db_host'] . ' ' . $_POST['db_name'] . ' >' . $CFG->sql_backup_root."dump.sql", $fp);
                                            if ( ( $fp==0 ) && ( false !== chmod( $CFG->sql_backup_root."dump.sql", 0666 ) ) ){
                                            $PAGE->message[]  =  "Daten exportiert";
                                            } else {
                                            $PAGE->message[]  =  "Es ist ein Fehler aufgetreten";
                                            }
                                            forceDownload($CFG->sql_backup_root."dump.sql");
                                            unlink($CFG->sql_backup_root."dump.sql"); //Datei vom Server löschen 
                                            $_SESSION['LASTPOST'] = NULL; // ermöglicht reload
                                            $_SESSION['DOWNLOAD'] = 1;    // ermöglicht go to page 2
                                            $PAGE->message[] = 'Datenbank erfolgreich gesichert!';
                                        }
                                        $PAGE->message[] = 'Datenbankzugriff funktioniert!';
                                        $TEMPLATE->assign('step', 2);
                                    } else { 
                                        $PAGE->message[] = 'Datenbank ('.$_POST['db_name'].') nicht gefunden oder leer.';
                                    }
                                } 

                        } 
                    }
                    
            break;
        case isset($_POST['step_2']):
                        $gump = new Gump();
                        $gump->validation_rules(array(
                                        'app_title'     => 'required'
                                        ));
                        $validated_data = $gump->run($_POST);
                                        if($validated_data === false) {/* validation failed */
                                                foreach($_POST as $key => $value){
                                                $TEMPLATE->assign($key, $value);
                                                } 
                                                $TEMPLATE->assign('v_error', $gump->get_readable_errors());     
                                                $TEMPLATE->assign('step', 2); 
                                            } else {/* validation successful */
                                                writeConfigFile($cfg_file, '$CFG->app_title', $_POST["app_title"]);
                                                $CFG->app_title = $_POST["app_title"];
                                                if (file_exists('/usr/bin/mysql')){ $path_to_mysqlfile = '/usr/bin/mysql';} 
                                                if (file_exists('/var/lib/mysql')){ $path_to_mysqlfile = '/var/lib/mysql';} 
                                                if (file_exists('/chroot/mysql')){ $path_to_mysqlfile = '/chroot/mysql';} 
                                                if (file_exists('/applications/mamp/library/bin/mysql')){ $path_to_mysqlfile = '/applications/mamp/library/bin/mysql';} 
                                                if (isset($_POST['demo'])){     // install demo or new db
                                                    
                                                    system( $path_to_mysqlfile.' -u' . $CFG->db_user . ' -p' . escapeshellarg( $CFG->db_password ) . ' -h' . $CFG->db_host . ' ' . $CFG->db_name . ' <' . $CFG->demo_root . 'demo.sql', $fp);
                                                    $TEMPLATE->assign('demo', true);
                                                } else {
                                                    system( $path_to_mysqlfile.' -u' . $CFG->db_user . ' -p' . escapeshellarg( $CFG->db_password ) . ' -h' . $CFG->db_host . ' ' . $CFG->db_name . ' <' . $CFG->demo_root . 'install.sql', $fp);  
                                                    $TEMPLATE->assign('demo', false);
                                                }    
                                                if ($fp==0) {
                                                    $PAGE->message[] = "Datenbank erfolgreich eingerichtet";  
                                                    load_Countries();
                                                    $TEMPLATE->assign('step', 3);
                                                } else {
                                                    $PAGE->message[] = "Bei der Einrichtung der Datenbank ist ein Fehler aufgetreten. Stellen Sie sicher, dass mysql unter /user/bin/ vorhanden ist. ";
                                                    $TEMPLATE->assign('step', 2);
                                                }
                                                //end import sql
                                                
                                                
                                            }
            break;
        case isset($_POST['step_3']):
                        $gump = new Gump();
                        $gump->validation_rules(array(
                                        'institution'      => 'required',
                                        'institution_description'     => 'required',
                                        /*'schooltype'      => 'required',
                                        'country'         => 'required',
                                        'state'           => 'required'*/
                                        ));
                        $validated_data = $gump->run($_POST);
                                        if (!isset($_POST['state'])){
                                            $_POST['state'] = 1;
                                        }
                                        if($validated_data === false) {/* validation failed */
                                                foreach($_POST as $key => $value){
                                                $TEMPLATE->assign($key, $value);
                                                } 
                                                $TEMPLATE->assign('v_error', $gump->get_readable_errors());   
                                                load_Countries();
                                                $TEMPLATE->assign('step', 3); 
                                            } else {
                                                if (isset($_POST['btn_newSchooltype'])){ 
                                                    $new_schooltype = new Schooltype();
                                                    $new_schooltype->schooltype  = $_POST['new_schooltype'];
                                                    $new_schooltype->description = $_POST['schooltype_description'];
                                                    $new_schooltype->country_id  = $_POST['country'];
                                                    $new_schooltype->state_id    = $_POST['state'];
                                                    $new_schooltype->creator_id = -1; 
                                                    $_POST['schooltype_id'] = $new_schooltype->add(); 
                                                }
                                                $new_institution = new Institution(); 
                                                $new_institution->institution   = $_POST['institution'];
                                                $new_institution->description   = $_POST['institution_description'];
                                                $new_institution->schooltype_id = $_POST['schooltype_id'];
                                                $new_institution->country_id    = $_POST['country'];
                                                $new_institution->state_id      = $_POST['state'];
                                                $new_institution->creator_id    = -1; // system user
                                                $new_institution->confirmed     = 1;  // institution is confirmed
                                                if ($_POST['demo']){
                                                    $institution_id = $new_institution->update(TRUE);
                                                } else {
                                                    $institution_id = $new_institution->add();
                                                    $user_config = new Config();                
                                                    $user_config->add('institution', $institution_id);
                                                }
                                                
                                                $TEMPLATE->assign('institution_id', $institution_id);   
                                            }
                                            load_Countries();
                                            $TEMPLATE->assign('step', 4);
                                            
                //Admin in alle Lehrpläne und in institution einschreiben
            break;     
        case isset($_POST['step_4']):
                    $gump = new Gump();
                         $institution = new Institution(); 
                        if (!isset($_POST['state'])){
                                            $_POST['state'] = 1; // eq not set
                                        }
                                        
                        $gump->validation_rules(array(
                                        'username'      => 'required',
                                        'firstname'     => 'required',
                                        'lastname'      => 'required',
                                        'email'         => 'required',
                                        'postalcode'    => 'required',
                                        'city'          => 'required',
                                        'country'       => 'required',
                                        'password'      => 'required', 
                                        'institution_id'=> 'required'
                                        ));
                        $validated_data = $gump->run($_POST);
                                        if($validated_data === false) {/* validation failed */
                                                foreach($_POST as $key => $value){
                                                $TEMPLATE->assign($key, $value);
                                                } 
                                                $TEMPLATE->assign('v_error', $gump->get_readable_errors());     
                                                load_Countries();
                                                $TEMPLATE->assign('step', 4); 
                                            } else {
                                                $new_user = new User(); 
                                                $new_user->username   = $_POST['username'];
                                                $new_user->firstname  = $_POST['firstname'];
                                                $new_user->lastname   = $_POST['lastname'];
                                                $new_user->email      = $_POST['email'];
                                                $new_user->postalcode = $_POST['postalcode'];
                                                $new_user->city       = $_POST['city'];
                                                $new_user->state_id      = $_POST['state'];
                                                $new_user->country_id    = $_POST['country'];
                                                $new_user->password   = $_POST['password'];
                                                $new_user->role_id    = 1;
                                                $new_user->creator_id = -1;
                                                $user_id = $new_user->add();
                                                $new_user->creator_id           = $user_id;
                                                $new_user->enroleToInstitution($_POST['institution_id']);
                                                /*$user_config = new Config(); //wird über user->add gemacht
                                                $user_config->add('user', $user_id);*/
                                                $new_user->dedicate();
                                                //Set creator_id of institution to admin_id
                                                //$institution = new Institution(); 
                                                $institution->id            = $_POST['institution_id']; 
                                                $institution->creator_id    = $user_id;
                                                /*$institution->load();
                                                $institution->creator_id    = $user_id;
                                                $institution->update(); */
                                                $institution->dedicate();
                                                //Set institution_id in subjects_db
                                                $subjects = new Subject(); 
                                                $subjects->institution_id   = $_POST['institution_id'];
                                                $subjects->creator_id       = $user_id;
                                                $subjects->dedicate();
                                                //Set institution_id in grade_db
                                                $grade = new Grade();
                                                $grade->institution_id      = $_POST['institution_id'];
                                                $grade->creator_id          = $user_id;
                                                $grade->dedicate();
                                                //Set institution_id in semester db
                                                $semester = new Semester();
                                                $semester->institution_id      = $_POST['institution_id'];
                                                $semester->creator_id          = $user_id;
                                                $semester->dedicate();
                                                //Set creator_id in files_db
                                                $files = new File();
                                                $files->creator_id          = $user_id;
                                                $files->dedicate();
                                                //Set creator_id in schooltypes
                                                $schooltypes = new Schooltype();
                                                $schooltypes->creator_id          = $user_id;
                                                $schooltypes->dedicate();
                                                //Set creator_id in user_roles db
                                                $roles = new Roles();
                                                $roles->creator_id          = $user_id;
                                                $roles->dedicate();
                                                $terminal_objective = new TerminalObjective();
                                                $terminal_objective->creator_id = $user_id; 
                                                $terminal_objective->dedicate();
                                                $enabling_objective = new EnablingObjective();
                                                $enabling_objective->creator_id = $user_id;
                                                $enabling_objective->dedicate();
                                                $group = new Group();
                                                $group->creator_id = $user_id;
                                                $group->dedicate();
                                                $curriculum = new Curriculum();
                                                $curriculum->creator_id = $user_id;
                                                $curriculum->dedicate();
                                                
                                                $TEMPLATE->assign('step', 5);
                                                session_destroy(); //important! reset $USER
                                            }
            break;
        case isset($_POST['step_5']):header('Location:index.php?action=login');
            break;
        
        default:
            break;
    }
}
                
/**
 * write config file
 * @param file $file
 * @param string $pattern
 * @param string $replace 
 */                
function writeConfigFile($file, $pattern, $replace){
    $lines = file($file);
    //print_r($lines);
    for ($i= 0; $i < count($lines); $i++){
        
        if(preg_match(sprintf('#\%s*.*=#',$pattern), $lines[$i])){
            $lines[$i] = $pattern."='".$replace."';\n"; 
            break;
            }
        }
        file_put_contents($file,$lines);
}

/**
 * load countries
 * @global object $TEMPLATE 
 */
function load_Countries(){
    global $TEMPLATE; 
    $country = new State(); 
    $countries = $country->getCountries();
    $TEMPLATE->assign('countries', $countries);

    $schooltype = new Schooltype();
    $schooltypes = $schooltype->getSchooltypes();
$TEMPLATE->assign('schooltype', $schooltypes);
}

$TEMPLATE->assign('page_message', $PAGE->message);	
?>