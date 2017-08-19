<?php
/**
* Certificate Class - Zertifikate erstellen
* 
* @abstract This file is part of curriculum - http://www.joachimdieterich.de
* @package core
* @filename certificate.class.php
* @copyright 2014 Joachim Dieterich
* @author Joachim Dieterich
* @date 2014.12.28 12:21
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
class Certificate {
    /**
     * ID of Grade
     * @var int
     */
    public $id;
    /**
     * Name of certificate-template
     * @var string
     */
    public $certificate; 
    /**
     * Description of certificate-template
     * @var string
     */
    public $description; 
    /**
     * HTML certificate-template 
     * @var html 
     */
    public $template;
    /**
     * Timestamp when certificate-template was created
     * @var timestamp
     */
    public $creation_time; 
    /**
     * id of User who created this certificate-template
     * @var int
     */
    public $creator_id; 
    public $creator;
    /**
     * id of institution to which certificate-template belongs to
     * @var int
     */
    public $institution_id; 
    public $institution;
    /**
     * id of curriculum to wich certificate-template belongs to
     * @var int 
     */
    public $curriculum_id;
    public $curriculum;
   
    /**
     * type of certificate e.g. user, group, institution
     * @var type string
     */
    public $type;
    
    /**
     * add certificate
     * @return mixed 
     */
    public function add(){
        global $USER;
        checkCapabilities('certificate:add', $USER->role_id);
        $db = DB::prepare('INSERT INTO certificate (certificate,description,template,creator_id,institution_id,curriculum_id) VALUES (?,?,?,?,?,?)');
        return $db->execute(array($this->certificate, $this->description, $this->template, $USER->id, $this->institution_id, $this->curriculum_id));
    }
    
    /**
     * Update certificate
     * @return boolean 
     */
    public function update(){
        global $USER;
        checkCapabilities('certificate:update', $USER->role_id);  
        $db = DB::prepare('UPDATE certificate SET certificate = ?, description = ?, template = ?, institution_id = ?, curriculum_id = ? WHERE id = ?');
        return $db->execute(array($this->certificate, $this->description, $this->template, $this->institution_id, $this->curriculum_id, $this->id));
    }
    
    /**
     * delete certificate
     * @global object $USER
     * @param int $creator_id
     * @return boolean 
     */
    public function delete(){
        global $USER, $LOG;
        checkCapabilities('certificate:delete', $USER->role_id);
        $this->load();
        $LOG->add($USER->id, 'certificate.class.php', dirname(__FILE__), 'Delete certificate: '.$this->certificate.', curriculum_id: '.$this->curriculum_id.' institution_id: '.$this->institution_id);
        $db = DB::prepare('DELETE FROM certificate WHERE id = ?');
        return $db->execute(array($this->id));
    } 
    
    /**
     * Load certificate with id $this->id 
     */
    public function load(){
        $db = DB::prepare('SELECT * FROM certificate WHERE id = ?');
        $db->execute(array($this->id));
        
        $result = $db->fetchObject();
        $this->certificate       = $result->certificate;
        $this->description       = $result->description;
        $this->template          = $result->template;
        $this->institution_id    = $result->institution_id;
        $this->curriculum_id     = $result->curriculum_id;
    }
    
    /**
     * Get all availible certificates of current institution
     * @return array of certificates objects 
     */
    public function getCertificates($paginator = ''){
        global $USER;
        $order_param    = orderPaginator($paginator,array('id' => 'ce',
                                                          'certificate' => 'ce',
                                                          'description' => 'ce',
                                                          'template'    => 'ce',
                                                          'creation_time' => 'ce',
                                                          'username'    => 'us',
                                                          'institution' => 'ins')); 
        $certificates   = array();                      //Array of certificates
        if (isset($this->curriculum_id)){
            $db             = DB::prepare('SELECT ce.*, us.username, ins.institution FROM certificate AS ce, users AS us, institution AS ins
                               WHERE ce.institution_id = ANY (SELECT institution_id FROM institution_enrolments WHERE us.id = user_id AND institution_id = ins.id AND user_id = ?) 
                               AND ins.id = ce.institution_id 
                               AND us.id = ?
                               AND (ce.curriculum_id = ? OR ce.curriculum_id = 0) '.$order_param);
            $db->execute(array($USER->id, $USER->id, $this->curriculum_id)); //AND us.id = ? is used to speed up query
        } else {
            $db             = DB::prepare('SELECT ce.*, us.username, ins.institution FROM certificate AS ce, users AS us, institution AS ins
                               WHERE ce.institution_id = ANY (SELECT institution_id FROM institution_enrolments WHERE us.id = user_id AND institution_id = ins.id AND user_id = ?) 
                               AND ins.id = ce.institution_id '.$order_param);
            $db->execute(array($USER->id));
        }
        
        while($result = $db->fetchObject()) { 
                $this->id              = $result->id;
                $this->certificate     = $result->certificate;
                $this->description     = $result->description;
                $this->template        = $result->template;
                $this->creation_time   = $result->creation_time;
                $this->creator_id      = $result->creator_id;
                $this->creator         = $result->username;
                $this->institution_id  = $result->institution_id;
                $this->institution     = $result->institution;
                /*$this->curriculum_id   = $result->curriculum_id;
                $this->curriculum      = $result->curriculum; */
                
                $certificates[] = clone $this;        //it has to be clone, to get the object and not the reference       
        } 
        
        return $certificates;
    }
    
    public function generate_from_template($type, $userlist, $date, $deliver){
        global $USER, $CFG;
        
        $ter_obj                = new TerminalObjective();         //load terminal objectives
        $ter                    = $ter_obj->getObjectives('certificate', $this->curriculum_id);
        $user                   = new User();
        $ena_obj                = new EnablingObjective();         //load enabling objectives
        $ena_obj->curriculum_id = $this->curriculum_id;
       
        foreach($userlist as $key=>$member){
            $output             = '';
            $template           = str_replace("../", "../../", $this->template); //hack to get path working --> todo: store fiele-ids in templates
            $ena                = $ena_obj->getObjectives('user', $member);
            $user->load('id', $member);

            //Textblöcke ersetzen
            $template     = str_replace("<!--Vorname-->", $user->firstname, $template);
            $template     = str_replace("<!--Nachname-->", $user->lastname, $template);
            if ($date == false){
                $template = str_replace("<!--Datum-->", date("d.m.Y"), $template);
            } else {
                $template = str_replace("<!--Datum-->", $date, $template);            //use Date defined in certificate dialog
            }
            $template     = str_replace("<!--Ort-->", '---', $template);
            $template     = str_replace("<!--Unterschrift-->", $USER->firstname.' '.$USER->lastname, $template);

            $start  = stripos($template, "<!--Start-->");
            $end    = stripos($template, "<!--Ende-->");

            $s_1    = substr($template, 0, $start);
            $s_2    = substr($template, $start, $end-$start);  
            $s_3    = substr($template, $end);
            $s_2    = str_replace("<!--Start-->", '', $s_2);
            $s_2    = str_replace("<!--Ende-->", '', $s_2);
            $s_3    = str_replace("<!--Ende-->", '', $s_3);

            //Bereiche //evtl. besser über regex realisieren z.B. /<bereich value="[(\d+),]+">.+<\/bereich>/g
            $anz_bereiche           = substr_count($s_2, '<!--Bereich');
            $offset                 = 0;
            for ($i = 1; $i <= $anz_bereiche; $i++){
                $bereich_begin      = stripos($s_2, "<!--Bereich"     ); // besser über regex lösen
                $bereich_end        = stripos($s_2, "<!--/Bereich-->");
                $offset             = $bereich_end+15;   
                $bereich_content    = substr($s_2, $bereich_begin, $offset-$bereich_begin);
                $bereich_id_begin   = stripos($bereich_content, "{")+1; 
                $bereich_id_end     = stripos($bereich_content, "}"); 
                $bereich_id         = substr($bereich_content, $bereich_id_begin, $bereich_id_end-$bereich_id_begin); 
                if ($ena_obj->calcTerminalPercentage($bereich_id, $member) <= 0.6){// wenn nicht genügend Ziele erreicht wurden (hier 60 %) dann wird dieser Bereich ausgelassen
                    $s_2 = substr($s_2, 0, $bereich_begin) . substr($s_2, $offset); // Wenn Bedingung nicht erfüllt ist, wird Bereich ausgeschnitten  
                } else {
                    $s_2 = substr($s_2, 0, $bereich_begin) . substr($bereich_content, $bereich_id_end+4, -15) . substr($s_2, $offset);
                }
            } 
            //Ende Bereiche
            
            $start      = stripos($s_2, "<!--Ziel_Start-->");
            $end        = stripos($s_2, "<!--Ziel_Ende-->");

            $o_1        = substr($s_2, 0, $start);
            $o_2        = substr($s_2, $start, $end-$start);
            $o_3        = substr($s_2, $end);
            $o_2        = str_replace("<!--Ziel_Start-->", '', $o_2);
            $o_2        = str_replace("<!--Ziel_Ende-->", '', $o_2);
            $o_3        = str_replace("<!--Ziel_Ende-->", '', $o_3);
            $output    .= $s_1;
            
           if ($end != 0){ 
                foreach ($ter as $ter_value) {
                    $t          = $o_1;
                    $t          = str_replace("<!--Thema-->", strip_tags($ter_value->terminal_objective), $t);
                    $output    .= $t;

                    foreach ($ena as $ena_value) {
                        if ($ter_value->id == $ena_value->terminal_objective_id){
                            $e = $o_2;
                            $e = str_replace("<!--Ziel-->", strip_tags($ena_value->enabling_objective), $e);
                            if (in_array($ena_value->accomplished_status_id, array("01","1","x1","11","21","31")) ){
                                $e = str_replace("<!--Ziel_erreicht-->", '<span style="font-family: Arial Unicode MS, Lucida Grande">&#10004;</span>', $e);
                                $e = str_replace("<!--Ziel_mit_Hilfe_erreicht-->", '', $e);
                                $e = str_replace("<!--Ziel_offen-->", '', $e);
                           } else if (in_array($ena_value->accomplished_status_id, array("02","2","x2","12","22","32"))){
                                $e = str_replace("<!--Ziel_mit_Hilfe_erreicht-->", '<span style="font-family: Arial Unicode MS, Lucida Grande">&#10004;</span>', $e);
                                $e = str_replace("<!--Ziel_offen-->", '', $e);
                           } else {
                                $e = str_replace("<!--Ziel_erreicht-->", '', $e);
                                $e = str_replace("<!--Ziel_mit_Hilfe_erreicht-->", '', $e);
                                $e = str_replace("<!--Ziel_offen-->", '<span style="font-family: Arial Unicode MS, Lucida Grande">&#10004;</span>', $e);
                           } 
                           
                            /* <ziel></ziel> auflösen */        
                           global $egl; 
                            $egl = $ena_value;   
                            $e   =  preg_replace_callback('/<ziel status="(\d+)+" (class="[\w ]+")? *(style="[\!;\-:\w ]+")?><\/ziel>/',      
                                function($r){ 
                                global $egl;
                                if ($egl->accomplished_status_id == '') {$egl->accomplished_status_id = 3;} // wenn Status noch nicht gesetzt wurde
                                if (in_array($egl->accomplished_status_id, array("01","1","x1","11","21","31")) AND in_array($r[1] , array("01","1","x1","11","21","31"))){
                                    return '<div '.$r[2].'> '.strip_tags($egl->enabling_objective).'</div>';
                                } else if (in_array($egl->accomplished_status_id, array("02","2","x2","12","22","32")) AND in_array($r[1] , array("02","2","x2","12","22","32"))){
                                    return '<div '.$r[2].'> '.strip_tags($egl->enabling_objective).'</div>';
                                } else if (in_array($egl->accomplished_status_id, array("03","3","x3","13","23","33")) AND in_array($r[1] , array("03","3","x3","13","23","33"))){
                                    return '<div '.$r[2].'> '.strip_tags($egl->enabling_objective).'</div>';
                                } 
                            }, $e); 
                            /* <ziel></ziel> auflösen */ 
                           $output    .= $e;
                        }
                    }
                    $output    .= $o_3;
                }
            } else {
                $output    .= $s_2;
            }
            $output    .= $s_3; // Print footer
            
            $pdf            = new Pdf();
            $pdf->content   = $output;
            $pdf->path      = 'user/'.$USER->id.'/pdf/';
            $pdf->filename  = date("Y-m-d_H-i-s").'_Zertifikat_'.$user->lastname.'_'.$user->firstname.'.pdf';
            $pdf->generate();
          
            if ($deliver){      //copy file to users folder
                $this->deliver_file($user, $pdf->filename);
            }
            set_time_limit(30);
        }
        $this->download($pdf->path);    
    }
    
    /**
     * Download certificate(s)
     * @global type $CFG
     * @global object $USER
     * @param type $path
     */
    public function download($path){
        global $CFG, $USER;
        if (file_exists($CFG->curriculumdata_root.'user/'.$USER->id.'/Zertifikate.zip')){
            unlink($CFG->curriculumdata_root.'user/'.$USER->id.'/Zertifikate.zip'); 
        }
        $zip = new ZipArchive();                                                            // create object
        if ($zip->open($CFG->curriculumdata_root.'user/'.$USER->id.'/Zertifikate.zip', ZIPARCHIVE::CREATE) !== TRUE) {   // open archive
            die ("Could not open archive");
        }

        $filelist = scandir($CFG->curriculumdata_root.$path);    // initialize an iterator // pass  the directory to be processed
        foreach ($filelist as $key=>$value) {                                               // iterate over the directory // add each file found to the archive
            if (substr($value, -3) == 'pdf'){
                $zip->addFile($CFG->curriculumdata_root.$path.$value, $value) or die ("ERROR: Could not add file: $key"); //str_replace: $url abschneiden, da sonst der komplette Pfad als Ordnerstuktur in der zip erscheinz
            }
        }
        $zip->close();

        foreach ($filelist as $key=>$value) { 
            if (substr($value, -3) == 'pdf'){
                unlink($CFG->curriculumdata_root.$path.$value);
            }
        }
        header("Location: ".$CFG->access_file_url."user/".$USER->id."/Zertifikate.zip");
        die(); //important
    }
    
    public function deliver_file($user, $filename){ //user who gets file
        global $CFG, $USER;
        $file               = $CFG->curriculumdata_root.'user/'.$USER->id.'/pdf/'.$filename;
        $user_path          = $CFG->curriculumdata_root.'user/'.$user->id.'/certificates/';
        silent_mkdir($user_path);
        $copy               = $user_path.$filename;

        if (!copy($file, $copy)) {
            error_log("error while copying $file");
        } else {
            $f               = new File();
            $f->title        = 'Zertifikat_'.$user->lastname.'_'.$user->firstname;
            $f->filename     = $filename;
            $f->description  = '';
            $f->author       = $USER->username;
            $f->license      = 2;
            $f->type         = '.pdf';
            $f->path         = $user->id.'/certificates/';
            $f->reference_id = $user->id;
            $f->context_id   = 22;
            $f->file_context = 4;
            $f->creator_id   = $USER->id;

            if ($f->add()){
                generateThumbnail($user_path, $f->filename , $f->context_id); //generate certificate Thumb
            }
        }
    } 
    
    public function test_group_list($userlist){
        global $CFG, $USER;
        $row = 0;
        
        $ter_obj                = new TerminalObjective();         //load terminal objectives
        $ter                    = $ter_obj->getObjectives('certificate', $this->curriculum_id);
        $user                   = new User();
        $ena_obj                = new EnablingObjective();         //load enabling objectives
        $ena_obj->curriculum_id = $this->curriculum_id;
       
        foreach($userlist as $key=>$member){
            $ena[$member]      = $ena_obj->getObjectives('user', $member);
        }
        $output  = '<table repeat_header="1" style="width: 100%;padding-bottom: 10px;" border="0"><tbody>';
        $output .= '<thead><tr><td style="border-bottom: 1px solid silver;"><strong>Ziele / Namen</strong></td>';
        foreach($userlist as $key=>$member){
            $user->load('id', $member);
            $output .= '<td style="border-bottom: 1px solid silver;border-right: 1px solid silver;"><strong>'.$user->firstname.' '.$user->lastname.'</strong></td>';
        }
        $output .= '</tr></thead>';
      
        foreach ($ter as $ter_value) {
            $output .= '<tr><td style="border-bottom: 1px solid silver;border-right: 1px solid silver;"><strong>'.strip_tags($ter_value->terminal_objective).'</strong></td>';
            foreach($userlist as $key=>$member){
                $output .= '<td style="border-bottom: 1px solid silver;border-right: 1px solid silver;"></td>';
            }
            $output .= '</tr>';
            for ($i = 0; $i < count($ena[$member]); $i++) {
                if ($ter_value->id == $ena[$member][$i]->terminal_objective_id){
                    $output .= '<tr><td style="width: 25%;border-bottom: 1px solid silver;border-right: 1px solid silver;">'.strip_tags($ena[$member][$i]->enabling_objective).'</td>';
                    foreach($userlist as $key=>$member){
                        if ($i == 0) error_log(json_encode($ena[$member]));
                        $output .='<td style="text-align: center; border-bottom: 1px solid silver;border-right: 1px solid silver;">';
                        if (in_array($ena[$member][$i]->accomplished_status_id, array("01","1","x1","11","21","31")) ){
                            $output .='<span style="text-align: center; font-family: Arial Unicode MS, Lucida Grande">&#10004;</span>';
                        } else if (in_array($ena[$member][$i]->accomplished_status_id, array("02","2","x2","12","22","32"))){
                            $output .='<span style="text-align: center; font-family: Arial Unicode MS, Lucida Grande">(&#10004;)</span>';
                        } else {
                            $output .='<span style="text-align: center; font-family: Arial Unicode MS, Lucida Grande"></span>';
                        } 
                        $output .= '</td>';
                    }

                    $output .= '</tr>';
                }
            }
        }
        $output .= '</tr>';
            
        
        $output .='</tbody></table>';
        

        $pdf            = new Pdf();
        $pdf->page_format = 'A4-L';
        $pdf->content   = $output;
        $pdf->path      = 'user/'.$USER->id.'/pdf/';
        $pdf->filename  = date("Y-m-d_H-i-s").'_Zertifikat_'.$user->lastname.'_'.$user->firstname.'.pdf';
        $pdf->generate();
        $this->download($pdf->path);  
    }
    /**
    * function used during the install process to set up creator id to new admin
    * @return boolean
    */
    public function dedicate(){ // only use during install
        $db = DB::prepare('UPDATE certificate SET creator_id = ?');        
        return $db->execute(array($this->creator_id));
    }
}