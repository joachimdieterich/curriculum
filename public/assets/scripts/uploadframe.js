/** This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package core
 * @filename uploadframe.js
 * @copyright 2015 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2015.08.10 13:26
 * @license 
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by  
 * the Free Software Foundation; either version 3 of the License, or (at your option) any later version.                                   
 *                                                                       
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of        
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details:                          
 *                                                                       
 * http://www.gnu.org/copyleft/gpl.html      
 */
$(document).ready(function() { 
    $("#uploadbtn").click(function() {document.getElementById('TB_progressBar').style.display = 'block';});
        
    $(".unav").click(function() {
        elements = new Array("div_fileuplbtn","div_fileURLbtn","div_filelastuploadbtn","div_myfilesbtn","div_curriculumfilesbtn","div_solutionfilesbtn","div_avatarfilesbtn");
        for (var i=0; i<elements.length; i++) {
            if (elements[i] === 'div_'+this.id){
                if (this.id === 'fileuplbtn' || this.id === 'fileURLbtn'){              //fileupl und fileURL nutzen das gleiche Formular
                    document.getElementById('div_file_url').style.display   = 'block';
                } else {
                    document.getElementById('div_file_url').style.display   = 'none';
                }
                document.getElementById('div_'+this.id).style.display       = 'block';
            } else {
                document.getElementById(elements[i]).style.display          = 'none';
            }
        }
    });
        
});

function previewFile(URL, FILE, POSTFIX, TITLE, DESCRIPTON, AUTHOR, LICENSE) {
   var dot = FILE.lastIndexOf( "." ) ;
   var filename  = FILE.substr( 0 , dot );
   
   document.getElementById('img_FilePreview').src                           = URL+filename+'_t.png'; //Zugriff per accessfile.php
   document.getElementById('div_FilePreview').style.display                 = 'block'; 
   document.getElementById(POSTFIX + 'p_author').innerHTML                  =  AUTHOR; 
   document.getElementById(POSTFIX + 'p_license').innerHTML                 =  LICENSE;
   document.getElementById(POSTFIX + 'p_title').innerHTML                   =  TITLE; 
   document.getElementById(POSTFIX + 'p_description').innerHTML             =  DESCRIPTON;  
   document.getElementById(POSTFIX + 'p_information').style.display         = 'block'; 
   document.getElementById(POSTFIX + 'uploadframe_info').style.visibility   = 'visible'; 
   document.getElementById(POSTFIX + 'p_information').style.visibility      = 'visible'; 
}

function exitpreviewFile(POSTFIX) {
   document.getElementById('div_FilePreview').style.display                 = 'none';  
   document.getElementById(POSTFIX + 'p_information').style.visibility      = 'hidden'; 
   document.getElementById(POSTFIX + 'uploadframe_info').style.visibility   = 'hidden'; 
}      

//Funktion zum auslesen von Checkboxes
function iterateListControl(containerId,checkboxnameroot,targetID,returnFormat,multipleFiles){
 var containerRef = document.getElementById(containerId);
 var inputRefArray = containerRef.getElementsByTagName('input');
 var returnList = '';
 
 for (var i=0; i<inputRefArray.length; i++){
  var inputRef = inputRefArray[i];

  if ( inputRef.type.substr(0, 8) === 'checkbox' && inputRef.checked === true ){
    if (returnList === '') {
        returnList = inputRef.id.substr(checkboxnameroot.length);
    } else {   
        returnList = returnList + ',' + inputRef.id.substr(checkboxnameroot.length);   //Kommagetrennte Liste mit den ausgewählten Dateien (Referenz ist die ID aus der files DB)
    }
  }
 }
    // Aufbereiten der Rückgabedaten 
   switch (returnFormat) {
        case "0": var returnListArray = returnList.split(","); // case 0 = returns FILE ID
                  var processedreturnListArray = '';
                  for (var i=0; i<returnListArray.length; i++) {
                      if (processedreturnListArray === '') {
                          processedreturnListArray = returnListArray[i]; //File ID
                      } else {
                          processedreturnListArray = processedreturnListArray + ',' + returnListArray[i];  //Gibt nur den Dateinamen aus                       
                      }
                  }
                  returnList = processedreturnListArray;
                  break; // FILE ID 
        case "1": var returnListArray = returnList.split(","); // case 1 = returns FILE NAME
                  var processedreturnListArray = '';
                  for (var i=0; i<returnListArray.length; i++) {
                      if (processedreturnListArray === '') {
                          processedreturnListArray = document.getElementById('href_' + returnListArray[i]).innerHTML; //Gibt nur den Dateinamen aus
                      } else {
                          processedreturnListArray = processedreturnListArray + ',' + document.getElementById('href_' + returnListArray[i]).innerHTML;  //Gibt nur den Dateinamen aus
                      }
                  }
                  returnList = processedreturnListArray;
                  break; //Es wird der Dateinamen zurückgegeben
                  
        case "2": var returnListArray = returnList.split(","); // case 2 = returns FILE PATH AND NAME
                  var processedreturnListArray = '';
                  for (var i=0; i<returnListArray.length; i++) {
                      if (processedreturnListArray === '') {
                          processedreturnListArray = document.getElementById('href_a_' + returnListArray[i]).href; //Gibt kompletten Link aus  
                      } else {
                          processedreturnListArray = processedreturnListArray + ',' + document.getElementById('href_a_' + returnListArray[i]).href;  //Gibt Kompletten Link aus
                      }
                  }    
                  returnList = processedreturnListArray;
                  break; //Es wird die URL zurückgegeben        
                  
        default: break;
   }
   switch (multipleFiles) {
        case "false": if (returnList.indexOf(',') !== -1){ //returns first file only
                      var index = returnList.indexOf(','); 
                      var processedreturnList = returnList.slice(0,index);
                    } else {
                          processedreturnList = returnList;
                    }
                    break; 
        case "true" : var processedreturnList = returnList; //returns filelist
                    break; 
        default: break;
   }
   
   // Auswahl an top.document weitergeben lassen, wenn bestehende datei gewählt wird
    if (((typeof top.tinyMCE.activeEditor === 'object') && top.tinyMCE.activeEditor !== null) && '<?php echo $context; ?>' === 'editor'){ //if tinyMCE is available
            top.tinyMCE.activeEditor.insertContent('<img src="'+processedreturnList+'">');
        } else {
            $('#'+targetID, top.document).val(processedreturnList);    //
        }
    self.parent.tb_remove();
}
