/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename uploadframe.js
* @copyright 2015 Joachim Dieterich
* @author Joachim Dieterich
* @date 2015.08.10 13:26
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

