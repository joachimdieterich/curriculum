<?php
/* 
 * add following to /etc/cron.daily/
 * replace "/path/to" with your path to curriculum
 * -------------------------------------
 * $ cat /etc/cron.daily/eportfolio
 *
 * test -f /path/to/share/scripts/anonymize_log.php || exit 0
 * /usr/bin/php5 /path/to/share/scripts/anonymize_log.php
 * 
 * @abstract This file is part of curriculum - http://www.joachimdieterich.de
 * @package core
 * @filename anonymize_log
 * @copyright 2018 Fabian Werner
 * @author Fabian Werner
 * @date 2018.10.01 16:07
 * @license: 
 * The MIT License
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

$base_url   = dirname(__FILE__).'/../';
include($base_url.'setup.php');  //Läd Klassen, DB Zugriff und Funktionen
$data   = new stdClass();
if (isset($CFG->settings->anonymize_days)) {
    $anonymize_days = $CFG->settings->anonymize_days;
} else {
    $anonymize_days = 30;
    $db = DB::prepare('INSERT INTO config (name,value,context_id,reference_id,type) VALUES (?,?,?,?,?)');
    $db->execute(array('anonymize_days', '30', '19', '0', 'int'));
}

$db     = DB::prepare('SELECT id,ip FROM log WHERE creation_time <  NOW() - INTERVAL ? DAY AND ip NOT LIKE \'%XXX\';');
$db->execute(array($anonymize_days));

while($result = $db->fetchObject()) { 
    $id = $result->id;
    $ip = $result->ip;
    if (strpos($ip, '.') !== false) {                   // ipv4
        $ip_arr         = explode(".", $ip);
        $n              = count($ip_arr);
        if ($ip_arr[$n - 1] == "XXX") {continue;}
        $ip_arr[$n - 1] = $ip_arr[$n - 2] = "XXX";
        $new_ip         = implode(".", $ip_arr);
    } else {
        if (strpos($ip, ':') !== false) {               // ipv6
            $ip_arr         = explode(":", $ip);
            $n              = count($ip_arr);
            if ($ip_arr[$n - 1] == "XXXX") {continue;}
            $ip_arr[$n - 1] = $ip_arr[$n - 2] = "XXXX";
            $new_ip         = implode(":", $ip_arr);
        } else {                                       // keine IP
            continue;
        }
    }
$dbu = DB::prepare('UPDATE log SET ip = ? WHERE id = ?');
$dbu->execute(array($new_ip, $id));
}
?>