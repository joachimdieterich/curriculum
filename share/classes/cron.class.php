<?php
/**
* cron object can add, update, delete and get data from roles db
* 
* @abstract This file is part of curriculum - http://www.joachimdieterich.de
* @package core
* @filename cron.class.php
* @copyright 2013 Joachim Dieterich
* @author Joachim Dieterich
* @date 2013.07.26 12:00
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
class Cron {
    public $id;
    public $cronjob;
    public $creation_time;
    public $creator_id;
    public $log;
    
    /**
     * add cronjob log into db
     * @param string $cronjob
     * @param int $user_id
     * @param string $log
     * @return boolean 
     */
    public function add($cronjob,$user_id,$log){
        $db = DB::prepare('INSERT INTO cronjobs (cronjob,creator_id,log) VALUES(?,?,?)');
        return $db->execute(array($cronjob, $user_id, $log));
    }
    
    public function load($id){
        $db = DB::prepare('SELECT * FROM cronjobs WHERE id = ?');
        $db->execute(array($id));
        $result                  = $db->fetchObject();
        $this->id                = $result->id;
        $this->cronjob           = $result->cronjob;
        $this->creation_time     = $result->creation_time;
        $this->creator_id        = $result->creator_id;
        $this->log               = $result->log;
        return $this;
    }
    
     /**
     * get last time where 'detectExpiredObjective' was called
     * @return object|boolean 
     */
    public function check_cronjob(){
        $db = DB::prepare('SELECT MAX(creation_time) as max FROM cronjobs WHERE cronjob = "detectExpiredObjective"');
        $db->execute();
        $result = $db->fetchObject();            
        
        return $result->max; 
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
        $pos1   = strpos($format, 'd'); 
        $pos2   = strpos($format, 'm'); 
        $pos3   = strpos($format, 'Y'); 

        $begin  = explode($sep,$begin); 
        $end    = explode($sep,$end); 

        $first  = GregorianToJD($end[$pos2],$end[$pos1],$end[$pos3]); 
        $second = GregorianToJD($begin[$pos2],$begin[$pos1],$begin[$pos3]); 

        if($first > $second){ return $first - $second;  } 
        else                { return $second - $first;  }
    }

    /**
     * detect expired objectibes
     * @param int $execUser default -1 = System
     */
    function detectExpiredObjective($execUser = -1){
        global $PAGE, $LOG; 
        $lastcheck                   = $this->check_cronjob();
        if (strtotime($lastcheck)+86400 < time()){        //only check this once a day --> if condition doesn't care about daylight savings!
            $user                    = new User();
            $message                 = 'Cronjob: Abgelaufene Ziele finden (detectExpiredObjective) | Letzte Überprüfung war am: '.$lastcheck.'<br>';
            $accomplished_objectives = new EnablingObjective();
            $objectives              = $accomplished_objectives->getRepeatingObjectives();
            if ($objectives){
                for ($i = 0; $i < count($objectives); $i++) {                
                    $days = $this->seDay(date("Y-m-d", strtotime($objectives[$i]->accomplished_time)), date("Y-m-d"),'Ymd','-'); // vergangene Tage ermitteln
                    if ($objectives[$i]->repeat_interval < $days) {
                        $user->load('id', $objectives[$i]->accomplished_users);
                        $message           .= 'Benutzer: <strong>'.$user->firstname.' '.$user->lastname.' ('.$user->username.')</strong><br>'
                                            . 'Ziel: <strong>'.$objectives[$i]->enabling_objective.'</strong> ist abgelaufen.<br>'
                                            . 'Anfang: '.$objectives[$i]->accomplished_time.', Ende: '.date("Y-m-d").'<br>'
                                            . 'Vergangene Tage: '.$days;
                        $mail = new Mail();
                        $mail->sender_id    = $execUser;
                        $mail->receiver_id  = $objectives[$i]->accomplished_users;
                        $mail->subject      = 'Lernziel "'.$objectives[$i]->enabling_objective; 
                        $mail->message      = 'Lernziel "'.$objectives[$i]->enabling_objective.'" muss erneut nachgewiesen werden. Bitte Lernziel-Nachweis einreichen.';
                        $mail->status       = true; 
                        $mail->postMail();
                        $message           .= '- Benachrichtigung verschickt.<br>';
                        $objectives[$i]->setAccomplishedStatus('cron',$objectives[$i]->accomplished_users, 0, 2);
                        $message           .= '- Status auf deaktiviert gesetzt (Wiederholung erforderlich).<br>';
                    } else { $message      .= "Keine abgelaufenen Ziele vorhanden<br>"; }   
                }
            } else { $message              .= "Keine Ziele mit Wiederholungen vorhanden<br>"; }
            $LOG->add(-1, 'cronjob', $PAGE->url,  $message);
            $cronjob = new Cron();
            $cronjob->add(__FUNCTION__, $execUser, 'DB auf abgelaufene Ziele überprüft.');
        }
    }
 
}