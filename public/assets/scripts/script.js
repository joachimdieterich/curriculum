/** This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package core
 * @filename script.js
 * @copyright 2013 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2019.09.19 13:26
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



/**
 * Open Link in new window
 **/
function openLink(URL, target) {
    if (URL != ''){ //Sortiert "leere" Anfragen aus.
        window.open(URL, target);
    } 
}
/**
 * Unmask password fields
 **/
function unmask(ID,checked){
    
    if (checked){
        document.getElementById(ID).type = 'text';
    } else {
       document.getElementById(ID).type = 'password'; 
    }
    }

/**
 * Show material of a objective
 **/
function showMaterial( curriculumID, terminalObjectiveID, enablingObjectiveID) {
    var url = "assets/scripts/request.php?function=showMaterial&curriculumID="+ curriculumID +"&terminalObjectiveID="+ terminalObjectiveID +"&enablingObjectiveID="+ enablingObjectiveID;

req = XMLobject();
    if(req) {        
        req.onreadystatechange = answer;
        req.open("GET", url, true);
        req.send(null);
    }
}

/**
 * show Learner who have complete this objective
 **/
function getHelp(group,curriculumID, terminalObjectiveID, enablingObjectiveID) {
    var url = "assets/scripts/request.php?function=getHelp&group="+ group +"&curriculumID="+ curriculumID +"&terminalObjectiveID="+ terminalObjectiveID +"&enablingObjectiveID="+ enablingObjectiveID;

req = XMLobject();
    if(req) {        
        req.onreadystatechange = answer;
        req.open("GET", url, true);
        req.send(null);
    }
}

/**
 * add a topic
 **/
function addterminalObjective(curriculumID) {
    var url = "assets/scripts/request.php?function=addterminalObjective&curriculumID="+ curriculumID;
    
    req = XMLobject();
    if(req) {        
        req.onreadystatechange = answer;
        req.open("GET", url, true);
        req.send(null);
    }
}

/**
 * add objective
 **/ 
function addenablingObjective(curriculumID, terminalObjectiveID) {
    var url = "assets/scripts/request.php?function=addenablingObjective&curriculumID="+ curriculumID+"&terminalObjectiveID="+ terminalObjectiveID;
    
    req = XMLobject();
    if(req) {        
        req.onreadystatechange = answer;
        req.open("GET", url, true);
        req.send(null);
    }
}

/**
 * delete a Objective
 **/
function deleteObjective() {

    if (confirm("Thema bzw. Ziel wirklich löschen?")) {   //Meldung "Wirklich löschen?"
        if (arguments.length == 2) {
        var url = "assets/scripts/request.php?function=deleteObjective&curriculumID="+ arguments[0] +"&terminalObjectiveID="+ arguments[1] +"&enablingObjectiveID=notset"; 
        }
        else if (arguments.length == 3) { 
        var url = "assets/scripts/request.php?function=deleteObjective&curriculumID="+ arguments[0] +"&terminalObjectiveID="+ arguments[1] +"&enablingObjectiveID="+ arguments[2]; 
        }
        
        req = XMLobject();
        if(req) {        
            req.onreadystatechange = answer; //Dialog mit Meldungen zeigen
            req.open("GET", url, true);
            req.send(null);
        }
    }
}

/**
 * delete a curriculum
 **/
function deleteCurriculum() {
    if (confirm("Lehrplan wirklich löschen?")) {   //Meldung "Wirklich löschen?"
        var url = "assets/scripts/request.php?function=deleteCurriculum&curriculumID="+ arguments[0]; 
        
        req = XMLobject();
        if(req) {        
            req.onreadystatechange = answer; //Dialog mit Meldungen zeigen
            req.open("GET", url, true);
            req.send(null);
        }
    }
}


/**
 * delete a dataset in a db-table
 **/
function del() {
    if (confirm("Datensatz wirklich löschen?")) {   //Meldung "Wirklich löschen?"
        var url = "assets/scripts/request.php?function=delete&db="+arguments[0]+"&id="+ arguments[1]+"&creator_id="+ arguments[2]; 
        
        req = XMLobject();
        if(req) {      
            if (arguments[0] == 'message'){             
              document.getElementById('inbox_'+arguments[1]).style.display='none';
            } else {
               req.onreadystatechange = answer; //Dialog mit Meldungen zeigen 
            }
            
            req.open("GET", url, true);
            req.send(null);
        }
    }
}

function deleteFile() {
    if (confirm("Datei wirklich löschen?")) {   //Meldung "Wirklich löschen?"
        //Link unterscheidet sich von den anderen funktionen, da diese funktion vom upload_frame aufgerufen wird
        var url = "../../../../assets/scripts/request.php?function=deleteFile&fileID="+ arguments[1]; 
        //var prefix = arguments[0];
        var fileID = arguments[1]
        req = XMLobject();
        if(req) {        
            req.onreadystatechange = function(){
                    hideFile(fileID);
                }
            req.open("GET", url, true);
            req.send(null);
        }
    }
}

function expelUser() {
    if (confirm("Benutzer wirklich aus der Lerngruppe ausschreiben?")) {   //Meldung "Wirklich löschen?"
        var url = "assets/scripts/request.php?function=expelUser&groupsID="+ arguments[0] +"&userID="+ arguments[1]; 
       
        req = XMLobject();
        if(req) {        
            req.onreadystatechange = answer; //Dialog mit Meldungen zeigen
            req.open("GET", url, true);
            req.send(null);
        }
    }
}
function expelFromInstituion() {
    if (confirm("Benutzer wirklich aus der Institution ausschreiben?")) {   //Meldung "Wirklich löschen?"
        var url = "assets/scripts/request.php?function=expelFromInstituion&institutionID="+ arguments[0] +"&userID="+ arguments[1]; 
       
        req = XMLobject();
        if(req) {        
            req.onreadystatechange = answer; //Dialog mit Meldungen zeigen
            req.open("GET", url, true);
            req.send(null);
        }
    }
}

function order() {
    if (arguments.length == 4) {
        var url = "assets/scripts/request.php?function=order&order="+ arguments[0] +"&order_id="+ arguments[1]+"&curriculum_id="+ arguments[2] +"&terminal_objective_id="+ arguments[3];
    } else if (arguments.length == 5) {
        var url = "assets/scripts/request.php?function=order&order="+ arguments[0] +"&order_id="+ arguments[1]+"&curriculum_id="+ arguments[2] +"&terminal_objective_id="+ arguments[3]+"&enabling_objective_id="+ arguments[4]; 
    }
    req = XMLobject();
    if(req) {        
        req.onreadystatechange = answer; 
        req.onreadystatechange = reloadPage; //window.location.reload() wichtig!
        req.open("GET", url, true);
        req.send(null);
    }
}

function editObjective() {
    if (arguments.length == 2) {
       var url = "assets/scripts/request.php?function=editterminalObjective&curriculumID="+ arguments[0] +"&terminalObjectiveID="+ arguments[1] +"&enablingObjectiveID=notset"; 
    }
    else if (arguments.length == 3) {
       var url = "assets/scripts/request.php?function=editenablingObjective&curriculumID="+ arguments[0] +"&terminalObjectiveID="+ arguments[1] +"&enablingObjectiveID="+ arguments[2]; 
    }
    req = XMLobject();
    if(req) {        
        req.onreadystatechange = answer; 
        req.open("GET", url, true);
        req.send(null);
    }
}

function editMaterial() {
    if (arguments.length == 3) {
       var url = "assets/scripts/request.php?function=editMaterial&curriculumID="+ arguments[0] +"&terminalObjectiveID="+ arguments[1] +"&enablingObjectiveID="+ arguments[2]; 
    }
    
    req = XMLobject();
    if(req) {        
        req.onreadystatechange = answer; 
        req.open("GET", url, true);
        req.send(null);
    }
}

function hideMaterial (){
    document.getElementById('popup').style.visibility='hidden';
}

//wenn checkbox angeklickt, dann wird element mit id angezeigt
function checkbox_addForm (){//arguments checked, style, id, invers_id -> if checked id is 'block' invers_id is 'none''
    
    if (arguments[0]) {
        document.getElementById(arguments[2]).style.display=arguments[1];
        document.getElementById(arguments[3]).style.display='none';
    } else {
        document.getElementById(arguments[2]).style.display='none';
        document.getElementById(arguments[3]).style.display=arguments[1];
    }
}


function reloadPage() {
    if (req.readyState == 4) { 
        if (req.status == 200) { 
        window.location.reload();
        }
    } 
}    

function answer() {
    if (req.readyState == 4) {  
        if (req.status == 200) {   
           if (req.responseText.length != 1){ //bei einem leeren responseText =1 ! wird das Fenster neu geladen
                if (document.getElementById('popup')){
                     document.getElementById('popup').innerHTML = req.responseText;   
                     $('#popup').show();           
                } else {
                    alert(req.responseText); //unschön, aber #popup ist vom modalframe aus nicht verfügbar
                }    
           } else {
               window.location.reload();
           }
        }
    }   
}


function hideFile() { //nach dem löschen wird das thumbnail ausgeblendet
    if (req.readyState == 4) {  
        if (req.status == 200) {
            
           if (req.responseText.length != 1){ //bei einem leeren responseText =1 ! wird das Fenster neu geladen
               alert(req.responseText); //unschön, aber #popup ist vom modalframe aus nicht 
               if (document.getElementById('row_filelastupload'+arguments[0])) {
                   document.getElementById('row_filelastupload'+arguments[0]).style.visibility='hidden'; 
               }
               if (document.getElementById('row_curriculumfiles'+arguments[0])) {          
                   document.getElementById('row_curriculumfiles'+arguments[0]).style.visibility='hidden';
               }
               if (document.getElementById('row_solutionfiles'+arguments[0])) {          
                   document.getElementById('row_solutionfiles'+arguments[0]).style.visibility='hidden';
               }
               if (document.getElementById('row_avatarfiles'+arguments[0])) {          
                   document.getElementById('row_avatarfiles'+arguments[0]).style.visibility='hidden';           
               }
               if (document.getElementById('row_myfiles'+arguments[0])) {          
                   document.getElementById('row_myfiles'+arguments[0]).style.visibility='hidden';           
               }
           } else {
               window.location.reload();
           }
        }
    }   
}

/* XMLObject für SQL Abfragen*/
function XMLobject() {
    var req = false;

    if(window.XMLHttpRequest) {
        try { 
        req = new XMLHttpRequest();
        } catch(e) {
            req = false;
            }
    } else if(window.ActiveXObject) {
        try {
        req = new ActiveXObject("Msxml2.XMLHTTP");
        } catch(e) {
            try {
            req = new ActiveXObject("Microsoft.XMLHTTP");
            } catch(e) {
                req = false;
                }
            }   
        }

return req;
}

function checkrow(rowNr) {
    var ch = document.getElementById(rowNr);

if(ch) {
        if (ch.checked == false){
            ch.checked = true;
            document.getElementById('row'+rowNr).className = 'activecontenttablerow';
        } else {
            ch.checked = false;
            document.getElementById('row'+rowNr).className = 'contenttablerow';
        }  
    }
}
function checkfile(file){
    var ch = document.getElementById(file);
    if(ch) {
            if (ch.checked == false){
                ch.checked = true;
                document.getElementById('row'+file).className = 'filelist activefilenail';
            } else {
                ch.checked = false;
                document.getElementById('row'+file).className = 'filelist filenail';
            }  
        }
}

function checkPaginatorRow(paginatorName, rowNr) {
    var ch = document.getElementById(paginatorName+''+rowNr);
    if(ch) {
        if (ch.checked == false){
            ch.checked = true;
            document.getElementById('row'+rowNr).className = 'activecontenttablerow';
        } else {
            ch.checked = false;
            document.getElementById('row'+rowNr).className = 'contenttablerow';
        }  
    }
}


function setAccomplishedObjectives(creatorID, userID, paginatorfirst, paginatorLimit, terminalObjectiveID, enablingObjectiveID, groupID){    
    
    var statusID = document.getElementById(terminalObjectiveID+'_'+enablingObjectiveID).innerHTML;
    if (statusID == 2) {
        statusID = 0;
    } else if(statusID == 1){
         statusID = 2;
    } else {
        statusID = 1;
    }
        
    //prüfen wie viele ausgewählt sind
    if (userID == 'all'){ 
        var url = "assets/scripts/request.php?function=setAccomplishedObjectives&userID="+ userID +"&creatorID="+ creatorID +"&paginatorfirst="+ paginatorfirst +"&terminalObjectiveID="+ terminalObjectiveID +"&enablingObjectiveID="+ enablingObjectiveID+"&statusID="+statusID+"&groupID="+groupID;
    //} else if(userID == '') {
    //  alert('Es muss ein Benutzer ausgewählt sein');
    } else {
        var url = "assets/scripts/request.php?function=setAccomplishedObjectives&userID="+ userID +"&creatorID="+ creatorID +"&paginatorfirst="+ paginatorfirst +"&terminalObjectiveID="+ terminalObjectiveID +"&enablingObjectiveID="+ enablingObjectiveID+"&statusID="+statusID;
    }
    req = XMLobject();
    if(req) {        
        req.onreadystatechange = function (){
            if (req.readyState==4 && req.status==200){
                if (req.responseText.length != 1){
                }
                if (statusID==0) {
                        document.getElementById(terminalObjectiveID+"style"+enablingObjectiveID).className = 'box gray-border boxred';
                        document.getElementById(terminalObjectiveID+"_"+enablingObjectiveID).innerHTML=0;
                }
                if (statusID==1) {
                        document.getElementById(terminalObjectiveID+"style"+enablingObjectiveID).className = 'box gray-border boxgreen';
                        document.getElementById(terminalObjectiveID+"_"+enablingObjectiveID).innerHTML=1;
                }
                if (statusID==2) {
                        document.getElementById(terminalObjectiveID+"style"+enablingObjectiveID).className = 'box gray-border boxorange';
                        document.getElementById(terminalObjectiveID+"_"+enablingObjectiveID).innerHTML=2;
                }
            }
        }
            req.open("GET", url, true);
            req.send(null);
        }  
}

//SetAccomplishedObjectivesBySolution
function setAccomplishedObjectivesBySolution(creatorID, userID, enablingObjectiveID, statusID){       
   var text = '';
   if (statusID == 0){
        text = 'Soll Ziel gesperrt werden?'
   } else { 
        text = 'Soll Ziel freigeschaltet werden?'
    }
   if (confirm(text)){
    var url = "assets/scripts/request.php?function=setAccomplishedObjectives&userID="+ userID +"&creatorID="+ creatorID +"&enablingObjectiveID="+ enablingObjectiveID+"&statusID="+statusID;

    req = XMLobject();
    if(req) {        
        req.onreadystatechange = function (){
            if (req.readyState==4 && req.status==200){
            }
        }
            req.open("GET", url, true);
            req.send(null);
        }
    }
}

//Fileuploadframe ausblenden
function hideUploadframe(){
       document.getElementById('uploadframe').style.display = 'none';
}


function confirmDialog(text) {
    if (confirm(text)){
        return true; 
    } else {
        return false;
    }
}


// Message functions

function loadmail(mail_id, mailbox) {
    var url = "assets/scripts/request.php?function=loadMail&mailID="+ mail_id; 

    req = XMLobject();
        if(req) {        
            req.onreadystatechange = function(){
                mail(mail_id, mailbox);
            }
            req.open("GET", url, true);
            req.send(null);
        }
    // generate Link for answering Button    
    url = "assets/scripts/request.php?function=loadMailanswerbtn&mailID="+ mail_id; 
    req_1 = XMLobject();
    if(req_1) {        
        req_1.onreadystatechange = function (){
            if (req_1.readyState == 4 ) {  
                if (req_1.status == 200) {
                   if (req_1.responseText.length != 1){ //bei einem leeren responseText =1 ! wird das Fenster neu geladen
                          document.getElementById('answer_btn').href = "index.php?action=messages&shownewMessage&answer=true&receiver_id="+req_1.responseText;
                          document.getElementById('answer_btn').style.display = "inline";
                   } else {
                       window.location.reload();
                   }
                }
            }
        }
        req_1.open("GET", url, true);
        req_1.send(null);
    }
}

function mail(mail_id, mailbox) {
    if (req.readyState == 4) {  
        if (req.status == 200) {
            
           if (req.responseText.length != 1){ //bei einem leeren responseText =1 ! wird das Fenster neu geladen
                  //tinyMCE.activeEditor.contentDocument.body.innerHTML = req.responseText;         
                  document.getElementById('mailbox').innerHTML = req.responseText;
                  document.getElementById(mailbox+'_'+mail_id).className = 'contenttablerow';
                  document.getElementById('mailbox').style.width = (document.body.offsetWidth - 555) +"px";
           } else {
               window.location.reload();
           }
        }
    }   
}

function loadStates(){    
    if (arguments[1]){
        var url = "assets/scripts/request.php?function=loadStates&country_id="+ arguments[0] +"&name="+ arguments[1] +"&state_id="+ arguments[2] ;
    } else {
        var url = "assets/scripts/request.php?function=loadStates&country_id="+ arguments[0];
    }

req = XMLobject();
    if(req) {        
        req.onreadystatechange = setStates;
        req.open("GET", url, true);
        req.send(null);
    }
}

function setStates() {
    if (req.readyState == 4) {  
        if (req.status == 200) {
           if (req.responseText.length != 1){ //bei einem leeren responseText =1 ! wird das Fenster neu geladen
                      if (document.getElementById('states')){
                           document.getElementById('states').innerHTML = req.responseText;
                      } else {
                          alert(req.responseText); //unschön, aber #popup ist vom modalframe aus nicht verfügbar
                      }  
           } else {
               window.location.reload();
           }
        }
    }   
}

//icon function
function showSubjectIcon(path, icon){
    document.getElementById('icon').src = path + icon;
}