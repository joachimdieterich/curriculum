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


class Pdf {
    public $creator         = 'curriculum';
    public $author          = 'www.joachimdieterich.de';
    public $title           = 'Zertifikat';
    
    public $font_size       =  18.0;
    public $font_name       = 'Helvetica-Bold';
    public $font_encoding   = 'utf8/unicode';
    public $content         = 'Hello world!'; 
    
    public $filename        = 'zertifikat.pdf';
    
    public $user_id        ;
    public $curriculum_id  ;
    public $group_id       ;
    
    
    public function generate_pdf($type = 'certificate'){
        if ($type == 'certificate'){
            $this->generate_certificate_content();       
        }
        if ($type == 'from_template'){
            $this->generate_certificate_from_template();       
        }

        
    }
    
    private function generate_certificate_from_template(){
        include(dirname(__FILE__).'/../libs/MPDF57/mpdf.php');
        
        $terminal_objectives = new TerminalObjective();         //load terminal objectives
        $ter = $terminal_objectives->getObjectives('curriculum', $this->curriculum_id);
        $user = new User();
        $mpdf = new mPDF('utf-8', 'A4');
        $stylesheet = file_get_contents(dirname(__FILE__).'/../../public/assets/stylesheets/certificate.css');
        $mpdf->WriteHTML($stylesheet,1);
        $enabling_objectives = new EnablingObjective();         //load enabling objectives
        $enabling_objectives->curriculum_id = $this->curriculum_id;
        $ena = $enabling_objectives->getObjectives('user', $this->user_id);

        $user = new User();
        $user->load('id', $this->user_id);
        
        //Textblöcke ersetzen
        $this->content = str_replace("{Vorname}", $user->firstname, $this->content);
        $this->content = str_replace("{Nachname}", $user->lastname, $this->content);
        $this->content = str_replace("{Datum}", date("d.m.Y"), $this->content);
        
        $start = stripos($this->content, "<!--Start-->");
        $end = stripos($this->content, "<!--Ende-->");
        
        $s_1 = substr($this->content, 0, $start);
        $s_2 = substr($this->content, $start, $end-$start);  
        $s_3 = substr($this->content, $end);
        $s_2 = str_replace("<!--Start-->", '', $s_2);
        $s_2 = str_replace("<!--Ende-->", '', $s_2);
        $s_3 = str_replace("<!--Ende-->", '', $s_3);
        
        $start = stripos($s_2, "<!--Ziel_Start-->");
        $end = stripos($s_2, "<!--Ziel_Ende-->");
        
        $o_1 = substr($s_2, 0, $start);
        $o_2 = substr($s_2, $start, $end-$start);
        $o_3 = substr($s_2, $end);
        $o_2 = str_replace("{Ziel_Start}", '', $o_2);
        $o_2 = str_replace("{Ziel_Ende}", '', $o_2);
        $o_3 = str_replace("{Ziel_Ende}", '', $o_3);
        $mpdf->WriteHTML($s_1, 2);
        
        foreach ($ter as $ter_value) {
            $t = $o_1;
            $t = str_replace("{Thema}", $ter_value->terminal_objective, $t);
            $mpdf->WriteHTML($t, 2);
            foreach ($ena as $ena_value) {
                if ($ter_value->id == $ena_value->terminal_objective_id){
                    $e = $o_2;
                    $e = str_replace("{Ziel}", $ena_value->enabling_objective, $e);
                    if ($ena_value->accomplished_status_id == 1){
                        $e = str_replace("{Ziel_erreicht}", 'x', $e);
                        $e = str_replace("{Ziel_offen}", '', $e);
                   } else {
                        $e = str_replace("{Ziel_erreicht}", '', $e);
                        $e = str_replace("{Ziel_offen}", 'x', $e);
                   }  
                   $mpdf->WriteHTML($e, 2);
                }
            }
            $mpdf->WriteHTML($o_3, 2);    
        }
        
        $mpdf->WriteHTML($s_3, 2);
        $mpdf->Output('Zertifikat_'.$user->lastname.'_'.$user->firstname.'.pdf', 'D');   
    }
    
    private function generate_certificate_content($full_certificate = true){
        include(dirname(__FILE__).'/../libs/MPDF57/mpdf.php');
        
        $terminal_objectives = new TerminalObjective();         //load terminal objectives
        
        $ter = $terminal_objectives->getObjectives('curriculum', $this->curriculum_id);
        $user = new User();
        if (isset($this->group_id)){
            $group_members = $user->getGroupMembers('group', $this->group_id);
            
            foreach($group_members as $key=>$member){
                
                $mpdf = new mPDF('utf-8', 'A4');
                $stylesheet = file_get_contents(dirname(__FILE__).'/../../public/assets/stylesheets/certificate.css');
                $mpdf->WriteHTML($stylesheet,1);
                $enabling_objectives = new EnablingObjective();         //load enabling objectives
                $enabling_objectives->curriculum_id = $this->curriculum_id;
                $ena = $enabling_objectives->getObjectives('user', $member);


                $user->load('id', $member);
                $mpdf->WriteHTML('<html><body>', 2);

                
                $logo_path = dirname(__FILE__).'/../../curriculumdata/userdata/102/logo.jpg';
                $mpdf->WriteHTML('<div class="center"><img class="logo" src="'.$logo_path.'"/></div>', 2);
                $mpdf->WriteHTML('<div class="center cleaner">Meine Institution | Hauptstraße 1 | 12345 Meine Stadt</div>');
                $mpdf->WriteHTML('<h1>Zertifikat</h1>');

                $mpdf->WriteHTML('<h2>Mein Lehrplan</h2></br>            
                                <h3>'.$user->firstname.' '.$user->lastname.'</h3> </br>hat erfolgreich die folgenden Ziele des Lehrplanes abgeschlossen. </p>',2);
                $mpdf->WriteHTML('<p></p>',2);
                $mpdf->WriteHTML('<p></p>',2);
                foreach ($ter as $ter_value) {
                    $mpdf->WriteHTML('<div class="topic">'.$ter_value->terminal_objective.'</div>', 2);
                    foreach ($ena as $ena_value) {
                        if ($ter_value->id == $ena_value->terminal_objective_id){
                            if ($ena_value->accomplished_status_id == 1){
                                $mpdf->WriteHTML('<div class="objective_green">... '.$ena_value->enabling_objective.'</div>', 2);
                            } else {
                                if ($full_certificate){
                                    $mpdf->WriteHTML('<div class="objective_red">... '.$ena_value->enabling_objective.'<div>', 2);
                                }
                            }   
                        } 
                   }
                    $mpdf->WriteHTML('<p></p>',2);
                }

                /* footer */
                $mpdf->WriteHTML('<p>Meine Stadt, '.date("d.m.Y").'</p><p></p><div>__________________________________</div> <div>Mein Name</div>',2);

                $mpdf->WriteHTML('</body></html>', 2);
                $mpdf->Output('../curriculumdata/output/Zertifikat_'.$user->lastname.'_'.$user->firstname.'.pdf', 'F');
                set_time_limit(30);
            }
            if (file_exists('../curriculumdata/temp/Zertifikate.zip')){
                unlink('../curriculumdata/temp/Zertifikate.zip'); 
            }
            $zip = new ZipArchive();                                                            // create object
            if ($zip->open('../curriculumdata/temp/Zertifikate.zip', ZIPARCHIVE::CREATE) !== TRUE) {   // open archive
                die ("Could not open archive");
            }
            
            $filelist = scandir('../curriculumdata/output/');    // initialize an iterator // pass  the directory to be processed
            foreach ($filelist as $key=>$value) {                                               // iterate over the directory // add each file found to the archive
                if (substr($value, -3) == 'pdf'){
                    $zip->addFile('../curriculumdata/output/'.$value, $value) or die ("ERROR: Could not add file: $key"); //str_replace: $url abschneiden, da sonst der komplette Pfad als Ordnerstuktur in der zip erscheinz
                }
            }
            $zip->close();
            
            foreach ($filelist as $key=>$value) { 
                if (substr($value, -3) == 'pdf'){
                    unlink('../curriculumdata/output/'.$value);
                }
            }
            header("Location: ../curriculumdata/temp/Zertifikate.zip");
            
        } else {
            $mpdf = new mPDF('utf-8', 'A4');
            $stylesheet = file_get_contents(dirname(__FILE__).'/../../public/assets/stylesheets/certificate.css');
            $mpdf->WriteHTML($stylesheet,1);
            $enabling_objectives = new EnablingObjective();         //load enabling objectives
            $enabling_objectives->curriculum_id = $this->curriculum_id;
            $ena = $enabling_objectives->getObjectives('user', $this->user_id);

            $user = new User();
            $user->load('id', $this->user_id);
            $mpdf->WriteHTML('<html><body>', 2);

            //$mpdf->WriteHTML('<div><img class="logo floatleft" src="http://localhost/curriculum/public/assets/images/basic/background.png"/>
            $logo_path = dirname(__FILE__).'/../../curriculumdata/userdata/102/logo.jpg';
            $mpdf->WriteHTML('<div class="center"><img class="logo" src="'.$logo_path.'"/></div>', 2);
            //$mpdf->WriteHTML('<div class="center"><img class="logo" src="http://localhost/curriculum/curriculumdata/userdata/102/logo.jpg"/></div>', 2);
            $mpdf->WriteHTML('<div class="center cleaner">Meine Institution | Hauptstraße 1 | 12345 Meine Stadt</div>');
            $mpdf->WriteHTML('<h1>Zertifikat</h1>');

            $mpdf->WriteHTML('<h2>Mein Lehrplan</h2></br>        
                            <h3>'.$user->firstname.' '.$user->lastname.'</h3> </br>hat erfolgreich die folgenden Ziele des Lehrplanes abgeschlossen. </p>',2);
            $mpdf->WriteHTML('<p></p>',2);
            $mpdf->WriteHTML('<p></p>',2);
            foreach ($ter as $ter_value) {
                $mpdf->WriteHTML('<div class="topic">'.$ter_value->terminal_objective.'</div>', 2);
                foreach ($ena as $ena_value) {
                    if ($ter_value->id == $ena_value->terminal_objective_id){
                        if ($ena_value->accomplished_status_id == 1){
                            $mpdf->WriteHTML('<div class="objective_green">... '.$ena_value->enabling_objective.'</div>', 2);
                        } else if ($ena_value->accomplished_status_id == 0){
                            if ($full_certificate){
                                $mpdf->WriteHTML('<div class="objective_red">... '.$ena_value->enabling_objective.'<div>', 2);
                            }    
                    }
                    
                    }
                }
                $mpdf->WriteHTML('<p></p>',2);
            }

            /* footer */
            $mpdf->WriteHTML('<p>Meine Stadt, '.date("d.m.Y").'</p><p></p><div>__________________________________</div> <div>Mein Name</div>',2);


            $mpdf->WriteHTML('</body></html>', 2);
            
            $mpdf->Output('Zertifikat_'.$user->lastname.'_'.$user->firstname.'.pdf', 'D');
        }
    }
    
}

?>