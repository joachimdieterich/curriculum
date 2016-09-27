<?php
/**
* This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename pdf.class.php
* @copyright 2013 joachimdieterich
* @author joachimdieterich
* @date 2014.07.30 10:06
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

class Pdf {
    public $creator         = 'curriculum';
    public $author          = 'www.joachimdieterich.de';
    public $title           = 'Zertifikat';
    public $font_size       =  10.0;
    public $font_name       = 'Helvetica';
    public $font_encoding   = 'utf8/unicode';
    public $template;
    public $filename        = 'zertifikat.pdf';
    public $user_id        ;
    public $curriculum_id  ;
    public $group_id       ;
    
    public $content        = ''; 
    
    
    public function generate_certificate_from_template(){
        global $USER, $CFG;
        include(dirname(__FILE__).'/../libs/MPDF57/mpdf.php');
        
        $terminal_objectives = new TerminalObjective();         //load terminal objectives
        $ter = $terminal_objectives->getObjectives('curriculum', $this->curriculum_id);
        $user = new User();
        $enabling_objectives = new EnablingObjective();         //load enabling objectives
        $enabling_objectives->curriculum_id = $this->curriculum_id;
       
        foreach($this->user_id as $key=>$member){
            $this->content  = $this->template;
            $mpdf           = new mPDF($this->font_encoding, 'A4', $this->font_size, $this->font_name);
            $stylesheet     = file_get_contents(dirname(__FILE__).'/../../public/assets/stylesheets/certificate.css');
            $mpdf->WriteHTML($stylesheet,1);
            $ena            = $enabling_objectives->getObjectives('user', $member);
            $user->load('id', $member);

            //Textblöcke ersetzen
            $this->content = str_replace("<!--Vorname-->", $user->firstname, $this->content);
            $this->content = str_replace("<!--Nachname-->", $user->lastname, $this->content);
            $this->content = str_replace("<!--Datum-->", date("d.m.Y"), $this->content);
            $this->content = str_replace("<!--Ort-->", '---', $this->content);
            $this->content = str_replace("<!--Unterschrift-->", $USER->firstname.' '.$USER->lastname, $this->content);

            $start  = stripos($this->content, "<!--Start-->");
            $end    = stripos($this->content, "<!--Ende-->");

            $s_1    = substr($this->content, 0, $start);
            $s_2    = substr($this->content, $start, $end-$start);  
            $s_3    = substr($this->content, $end);
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
                if ($enabling_objectives->calcTerminalPercentage($bereich_id, $member) <= 0.6){// wenn nicht genügend Ziele erreicht wurden (hier 60 %) dann wird dieser Bereich ausgelassen
                    $s_2 = substr($s_2, 0, $bereich_begin) . substr($s_2, $offset); // Wenn Bedingung nicht erfüllt ist, wird Bereich ausgeschnitten  
                } else {
                    $s_2 = substr($s_2, 0, $bereich_begin) . substr($bereich_content, $bereich_id_end+4, -15) . substr($s_2, $offset);
                }
            } 
            //Ende Bereiche
            
            $start  = stripos($s_2, "<!--Ziel_Start-->");
            $end    = stripos($s_2, "<!--Ziel_Ende-->");

            $o_1    = substr($s_2, 0, $start);
            $o_2    = substr($s_2, $start, $end-$start);
            $o_3    = substr($s_2, $end);
            $o_2    = str_replace("<!--Ziel_Start-->", '', $o_2);
            $o_2    = str_replace("<!--Ziel_Ende-->", '', $o_2);
            $o_3    = str_replace("<!--Ziel_Ende-->", '', $o_3);
            $mpdf->WriteHTML($s_1, 2);
            
           if ($end != 0){ 
                foreach ($ter as $ter_value) {
                    $t  = $o_1;
                    $t  = str_replace("<!--Thema-->", $ter_value->terminal_objective, $t);
                    $mpdf->WriteHTML($t, 2);

                    foreach ($ena as $ena_value) {
                        if ($ter_value->id == $ena_value->terminal_objective_id){
                            $e = $o_2;
                            $e = str_replace("<!--Ziel-->", $ena_value->enabling_objective, $e);
                            if ($ena_value->accomplished_status_id == 1){
                                $e = str_replace("<!--Ziel_erreicht-->", '<span style="font-family: Arial Unicode MS, Lucida Grande">&#10004;</span>', $e);
                                $e = str_replace("<!--Ziel_mit_Hilfe_erreicht-->", '', $e);
                                $e = str_replace("<!--Ziel_offen-->", '', $e);
                           } else if ($ena_value->accomplished_status_id == 2){
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
                                if ($egl->accomplished_status_id == $r[1] ){
                                    return '<div '.$r[2].'>- '.$egl->enabling_objective.'</div>';
                                } else if ($egl->accomplished_status_id == '') {
                                    return '<div>- '.$egl->enabling_objective.'</div>';
                                } else {
                                    return '';
                                }
                            }, $e); 
                            /* <ziel></ziel> auflösen */ 
                           $mpdf->WriteHTML($e, 2);
                        }
                    }
                    $mpdf->WriteHTML($o_3, 2);    
                }
            } else {
                $mpdf->WriteHTML($s_2, 2);
            }
            $mpdf->WriteHTML($s_3, 2); // Print footer
            silent_mkdir($CFG->curriculumdata_root.'user/'.$USER->id.'/pdf/');
            $mpdf->Output($CFG->curriculumdata_root.'user/'.$USER->id.'/pdf/Zertifikat_'.$user->lastname.'_'.$user->firstname.'.pdf', 'F');
            set_time_limit(30);
        }
        
        if (file_exists($CFG->curriculumdata_root.'user/'.$USER->id.'/Zertifikate.zip')){
            unlink($CFG->curriculumdata_root.'user/'.$USER->id.'/Zertifikate.zip'); 
        }
        $zip = new ZipArchive();                                                            // create object
        if ($zip->open($CFG->curriculumdata_root.'user/'.$USER->id.'/Zertifikate.zip', ZIPARCHIVE::CREATE) !== TRUE) {   // open archive
            die ("Could not open archive");
        }

        $filelist = scandir($CFG->curriculumdata_root.'user/'.$USER->id.'/pdf/');    // initialize an iterator // pass  the directory to be processed
        foreach ($filelist as $key=>$value) {                                               // iterate over the directory // add each file found to the archive
            if (substr($value, -3) == 'pdf'){
                $zip->addFile($CFG->curriculumdata_root.'user/'.$USER->id.'/pdf/'.$value, $value) or die ("ERROR: Could not add file: $key"); //str_replace: $url abschneiden, da sonst der komplette Pfad als Ordnerstuktur in der zip erscheinz
            }
        }
        $zip->close();

        foreach ($filelist as $key=>$value) { 
            if (substr($value, -3) == 'pdf'){
                unlink($CFG->curriculumdata_root.'user/'.$USER->id.'/pdf/'.$value);
            }
        }
        header("Location: ".$CFG->access_file_url."user/".$USER->id."/Zertifikate.zip");
        die(); //important
    } 
    
    public function generate(){
        global $USER, $CFG;

        include(dirname(__FILE__).'/../libs/MPDF57/mpdf.php');
        $mpdf           = new mPDF($this->font_encoding, 'A4', $this->font_size, $this->font_name);
        //$stylesheet   = file_get_contents(dirname(__FILE__).'/../../public/assets/stylesheets/certificate.css');
        
        $stylesheet     = file_get_contents(dirname(__FILE__).'/../../public/assets/templates/AdminLTE-2.3.0/bootstrap/css/bootstrap.css');
        //$stylesheet     .= file_get_contents(dirname(__FILE__).'/../libs/font-awesome-4.6.1/css/font-awesome.min.css');
        //$stylesheet     .= file_get_contents(dirname(__FILE__).'/../../public/assets/stylesheets/google-fonts.css');
        $stylesheet     .= file_get_contents(dirname(__FILE__).'/../../public/assets/templates/AdminLTE-2.3.0/dist/css/AdminLTE.min.css');
        $stylesheet     .= file_get_contents(dirname(__FILE__).'/../../public/assets/templates/AdminLTE-2.3.0/dist/css/skins/_all-skins.min.css');
        //$stylesheet     .= file_get_contents(dirname(__FILE__).'/../../public/assets/templates/AdminLTE-2.3.0/plugins/colorpicker/bootstrap-colorpicker.min.css');
        $stylesheet     .= file_get_contents(dirname(__FILE__).'/../../public/assets/stylesheets/all-bs.css');
        //$stylesheet     .= file_get_contents(dirname(__FILE__).'/../../public/assets/stylesheets/buttons.css');
        //$stylesheet     .= file_get_contents(dirname(__FILE__).'/../../public/assets/jquery.nyroModal/styles/nyroModal.css');
        $mpdf->WriteHTML($stylesheet,1);
        $mpdf->WriteHTML($this->content, 2);
         if (file_exists($CFG->curriculumdata_root.'user/'.$USER->id.'/'.$this->filename)){
            unlink($CFG->curriculumdata_root.'user/'.$USER->id.'/'.$this->filename); 
        }
        $mpdf->Output($CFG->curriculumdata_root.'user/'.$USER->id.'/'.$this->filename, 'F');
        set_time_limit(30);
        header("Location: ".$CFG->access_file_url."user/".$USER->id."/".$this->filename);
    }
}