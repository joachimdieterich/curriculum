<?php
/**
 * cron object can add, update, delete and get data from user_roles db
 * 
 * @abstract This file is part of curriculum - http://www.joachimdieterich.de
 * @package core
 * @filename cron.class.php
 * @copyright 2013 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2013.07.26 12:00
 * @license 
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
class Cron {
    /**
     * add cronjob log into db
     * @param string $cronjob
     * @param int $user_id
     * @param string $log
     * @return boolean 
     */
    public function add($cronjob,$user_id,$log){
        $query = sprintf("INSERT INTO cronjobs (cronjob,creator_id,log) VALUES('%s','%s','%s')",
                mysql_real_escape_string($cronjob),
                mysql_real_escape_string($user_id),
                mysql_real_escape_string($log));
        return mysql_query($query);
    }
    
    /**
     * get last time where 'detectExpiredObjective' was called
     * @return object|boolean 
     */
    public function check_cronjob(){
        $query = sprintf("SELECT DISTINCT creation_time FROM cronjobs WHERE cronjob = 'detectExpiredObjective'");
        $result = mysql_query($query);
        if ($result && mysql_num_rows($result)){
            while($row = mysql_fetch_assoc($result)) { 
                    $this->creation_time = $row['creation_time'];
                    
            }
                return $this;
        } else {
            return false;
        }

    }
    
/**
 * function seDay von http://gd.tuwien.ac.at/languages/php/selfphp/kochbuch/kochbuch11.html
 * @param timestamp $begin
 * @param timestamp $end
 * @param string $format
 * @param string $sep
 * @return int 
 */
function seDay($begin,$end,$format,$sep){ //
    $pos1 = strpos($format, 'd'); 
    $pos2 = strpos($format, 'm'); 
    $pos3 = strpos($format, 'Y'); 

    $begin = explode($sep,$begin); 
    $end = explode($sep,$end); 

    $first = GregorianToJD($end[$pos2],$end[$pos1],$end[$pos3]); 
    $second = GregorianToJD($begin[$pos2],$begin[$pos1],$begin[$pos3]); 

    if($first > $second) {
    return $first - $second; 
    } else {
    return $second - $first; 
    }
}

/**
 * detect expired objectibes
 * @param int $execUser default -1 = System
 */
function detectExpiredObjective($execUser = -1){
    global $PAGE; 
    $user = new User();
    $PAGE->message[] = "Cronjob: detectExpiredObjective<br>";
    $PAGE->message[] = "===============================<br>";
    $accomplished_objectives = new EnablingObjective();
    $objectives = $accomplished_objectives->getRepeatingObjectives();
    if ($objectives){
        for ($i = 0; $i < count($objectives); $i++) {
            // vergangene Tage ermitteln
                $days = seDay(date("Y-m-d", strtotime($objectives[$i]->accomplished_time)), date("Y-m-d"),'Ymd','-');

            if ($objectives[$i]->repeat_interval < $days) {
                $user->load('id', $objectives[$i]->accomplished_users);
                $PAGE->message[] = '- Benutzer: <strong>'.$user->firstname.' '.$user->lastname.' ('.$user->username.')</strong><br> - Ziel: <strong>'.$objectives[$i]->enabling_objective.'</strong> ist abgelaufen.<br>';
                $PAGE->message[] = '- Anfang: '.$objectives[$i]->accomplished_time.', Ende: '.date("Y-m-d").'<br>';
                $PAGE->message[] = '- Vergangene Tage: '.$days.'<br>';
                $mail = new Mail();
                $mail->sender_id = $execUser;
                $mail->receiver_id = $objectives[$i]->accomplished_users;
                $mail->subject = 'Lernziel "'.$objectives[$i]->enabling_objective; 
                $mail->message = 'Lernziel "'.$objectives[$i]->enabling_objective.'" muss erneut nachgewiesen werden. Bitte Lernziel-Nachweis einreichen.';
                $mail->status = true; 
                $mail->postMail();
                $PAGE->message[] = '- Benachrichtigung verschickt.<br>';
                $objectives[$i]->setAccomplishedStatus();;
                $PAGE->message[] = '- Status auf deaktiviert gesetzt (Wiederholung erforderlich).<br>';
                $PAGE->message[] = "===============================<br>";
            } else {
                $PAGE->message[] = "Keine abgelaufenen Ziele vorhanden<br>";
                $PAGE->message[] = "===============================<br>";
            }   
        }
    } else {
        $PAGE->message[] = "Keine Ziele mit Wiederholungen vorhanden<br>";
        $PAGE->message[] = "===============================<br>";
    }
    
    $cronjob = new Cron();
    $cronjob->add(__FUNCTION__, $execUser, 'DB auf abgelaufene Ziele überprüft.');
}
}
?>