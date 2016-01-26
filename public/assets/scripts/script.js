/** This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package core
 * @filename script.js
 * @copyright 2013 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2015.09.19 13:26
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

var req;

function switchValue(ElementID){
    if (document.getElementById(ElementID).value === 'true'){
        document.getElementById(ElementID).value = 'false';
    } else {
        document.getElementById(ElementID).value = 'true';
    }
}

function toggle(){ // allgemeine definieren . arg 1 soll angezeigt werden und alle weiteren deaktiviert werden. Anz. der arg. soll variabel sein
	document.getElementById(arguments[0]).style.display = 'block';
	document.getElementById(arguments[1]).style.display = 'none';
	document.getElementById(arguments[2]).style.display = 'none';
}

function loadmail(mail_id, mailbox) {
    var url = "../share/request/getMail.php?mailID="+ mail_id+"&box="+mailbox; 

    req = XMLobject();
        if(req) {        
            req.onreadystatechange = function(){
                mail(mail_id, mailbox);
            };
            req.open("GET", url, true);
            req.send(null);
        }
 
    if (mailbox === 'inbox'){   // generate Link for answering Button  
        url = "../share/request/getMailAnswerBtn.php?mailID="+ mail_id; 
        req_1 = XMLobject();
        if(req_1) {        
            req_1.onreadystatechange = function (){
                if (req_1.readyState === 4 ) {  
                    if (req_1.status === 200) {
                       if (req_1.responseText.length !== 1){ //bei einem leeren responseText =1 ! wird das Fenster neu geladen
                              document.getElementById('answer_btn').href = "index.php?action=messages&function=shownewMessage&answer=true&receiver_id="+req_1.responseText;
                              document.getElementById('answer_btn').style.display = "inline";
                       } else {
                           window.location.reload();
                       }
                    }
                }
            };
            req_1.open("GET", url, true);
            req_1.send(null);
        }
    }
}

function mail(mail_id, mailbox) {
    if (req.readyState === 4) {  
        if (req.status === 200) {
           if (req.responseText.length !== 1){ //bei einem leeren responseText =1 ! wird das Fenster neu geladen
                  //tinyMCE.activeEditor.contentDocument.body.innerHTML = req.responseText;         
                  document.getElementById('mailbox').innerHTML = req.responseText;
                  document.getElementById(mailbox+'_'+mail_id).className = 'contenttablerow';
                  document.getElementById('mailbox').style.width = (document.body.offsetWidth - 555) +"px";
                  document.getElementById('border-box').style.height = "100%";
           } else {
               window.location.reload();
           }
        }
    }   
}
/**
 * 
 * @param {int} rowNr
 * @returns {undefined}
 */
function checkrow(/*rowNr,link*/) {
    if (arguments.length === 1) { // Auswahl eines Benutzers
        var ch = document.getElementById(arguments[0]);
        
        if(ch) {
            if (ch.checked === false){
                ch.checked = true; 
                document.getElementById('row'+arguments[0]).className = 'activecontenttablerow';
            } else {
                ch.checked = false;
                document.getElementById('row'+arguments[0]).className = 'contenttablerow';
            }  
        }
    }
    if (arguments.length === 4) { //multiple Auswahl über die Checkboxen (Lernstand)
        var values = new Array();           //Array of all checked values
        $.each($('[name="'+arguments[1]+'"]:checked'), function() {
            values.push($(this).val());                             
          });
          window.location.assign(arguments[3]+'&'+arguments[2]+'_sel_id='+values);        
    }
}



function checkfile(file){
    var ch = document.getElementById(file);
    if(ch) {
            if (ch.checked === false){
                ch.checked = true;
                document.getElementById('row'+file).className = 'filelist activefilenail';
            } else {
                ch.checked = false;
                document.getElementById('row'+file).className = 'filelist filenail';
            }  
        }
}

function raiseEvent (eventType, elementID){ 
    var o = document.getElementById(elementID); 
    if (document.createEvent) { 
        var evt = document.createEvent("Events"); 
        evt.initEvent(eventType, true, true); 
        o.dispatchEvent(evt); 
    } 
    else if (document.createEventObject) 
    {
        var evt = document.createEventObject(); 
        o.fireEvent('on' + eventType, evt); 
    } 
    o = null;
} 

/* XMLObject für SQL Abfragen*/
function XMLobject() {
    var request = false;

    if(window.XMLHttpRequest) {
        try { 
        request = new XMLHttpRequest();
        } catch(e) {
            request = false;
            }
    } else if(window.ActiveXObject) {
        try {
        request = new ActiveXObject("Msxml2.XMLHTTP");
        } catch(e) {
            try {
            request = new ActiveXObject("Microsoft.XMLHTTP");
            } catch(e) {
                request = false;
                }
            }   
        }

    return request;
}


function answer() {
    $('#popup').show(); 
    if (req.readyState === 4) {  
        if (req.status === 200) {   
           if (req.responseText.length !== 0){ //bei einem leeren responseText =1 ! wird das Fenster neu geladen --> auf 0 geändert - testen!
                if (document.getElementById('popup')){
                     document.getElementById('popup').innerHTML = req.responseText;   
                     $('#popup').show(); 
                     raiseEvent('load', 'popup');
                } else {
                    alert(req.responseText); //unschön, aber #popup ist vom modalframe aus nicht verfügbar
                }    
           } else {
               window.location.reload();
           }
        }
    }   
}

function reloadPage() {
    if (req.readyState === 4) { 
        if (req.status === 200) { 
        window.location.reload();
        }
    } 
}    

/**
 * 
 * @param {string} URL
 * @param {string} target
 * @returns {}
 */
function openLink(URL, target) {
    if (URL !== ''){ //Sortiert "leere" Anfragen aus.
        window.open(URL, target);
    } 
}
/**
 * 
 * @param {string} ID
 * @param {boolean} checked
 * @returns {undefined}
 */
function unmask(ID,checked){
    if (checked){
        document.getElementById(ID).type = 'text';
    } else {
       document.getElementById(ID).type = 'password'; 
    }
}


function hideFile() { //nach dem löschen wird das thumbnail ausgeblendet
    if (req.readyState === 4) {  
        if (req.status === 200) {
            
           if (req.responseText.length !== 1){ //bei einem leeren responseText =1 ! wird das Fenster neu geladen
               if (req.responseText !== 'OK'){alert(req.responseText);} //unschön, aber #popup ist vom modalframe aus nicht 
               if (document.getElementById('row_filelastuploadbtn'+arguments[0])) {
                   document.getElementById('row_filelastuploadbtn'+arguments[0]).style.visibility='hidden'; 
               }
               if (document.getElementById('row_curriculumfilesbtn'+arguments[0])) {          
                   document.getElementById('row_curriculumfilesbtn'+arguments[0]).style.visibility='hidden';
               }
               if (document.getElementById('row_solutionfilesbtn'+arguments[0])) {          
                   document.getElementById('row_solutionfilesbtn'+arguments[0]).style.visibility='hidden';
               }
               if (document.getElementById('row_avatarfilesbtn'+arguments[0])) {          
                   document.getElementById('row_avatarfilesbtn'+arguments[0]).style.visibility='hidden';           
               }
               if (document.getElementById('row_myfilesbtn'+arguments[0])) {          
                   document.getElementById('row_myfilesbtn'+arguments[0]).style.visibility='hidden';           
               }
               if (document.getElementById('material_btn'+arguments[0])) {          
                   document.getElementById('material_btn'+arguments[0]).style.visibility='hidden';           
               }
               if (document.getElementById('material_'+arguments[0])) {          
                   document.getElementById('material_'+arguments[0]).style.visibility='hidden';           
               }
           } else {
               window.location.reload();
           }
        }
    }   
}

function setAccomplishedObjectives(creatorID, userID, paginatorfirst, paginatorLimit, terminalObjectiveID, enablingObjectiveID, groupID){        
    var statusID = Number(document.getElementById(terminalObjectiveID+'_'+enablingObjectiveID).innerHTML); //convert html to int
    switch(statusID) {
    case 0:     statusID = 1;
        break;
    case 1:     statusID = 2;
        break;
    case 2:     statusID = 3;
        break;
    case 3:     statusID = 0;
        break;
    }
    
    var url = "../share/request/setAccObjectives.php?userID="+ userID +"&creatorID="+ creatorID +"&paginatorfirst="+ paginatorfirst +"&terminalObjectiveID="+ terminalObjectiveID +"&enablingObjectiveID="+ enablingObjectiveID+"&statusID="+statusID;

    req = XMLobject();
    if(req) {        
        req.onreadystatechange = function (){
            if (req.readyState===4 && req.status===200){
                if (req.responseText.length !== 1){
                }
                switch(statusID) {
                case 0:     document.getElementById(terminalObjectiveID+"style"+enablingObjectiveID).className = 'box gray-border boxred';
                            document.getElementById(terminalObjectiveID+"_"+enablingObjectiveID).innerHTML=0;
                    break;
                case 1:     document.getElementById(terminalObjectiveID+"style"+enablingObjectiveID).className = 'box gray-border boxgreen';
                            document.getElementById(terminalObjectiveID+"_"+enablingObjectiveID).innerHTML=1;
                    break;
                case 2:     document.getElementById(terminalObjectiveID+"style"+enablingObjectiveID).className = 'box gray-border boxorange';
                            document.getElementById(terminalObjectiveID+"_"+enablingObjectiveID).innerHTML=2;
                    break;
                case 3:     document.getElementById(terminalObjectiveID+"style"+enablingObjectiveID).className = 'box gray-border';
                            document.getElementById(terminalObjectiveID+"_"+enablingObjectiveID).innerHTML=3;
                    break;
                }
                
            }
        };
            req.open("GET", url, true);
            req.send(null);
        }  
}

//SetAccomplishedObjectivesBySolution
function setAccomplishedObjectivesBySolution(creatorID, userID, enablingObjectiveID, statusID){       
   /*var text = '';
   if (statusID === 0){
        text = 'Soll Ziel gesperrt werden?';
   } else { 
        text = 'Soll Ziel freigeschaltet werden?';
    }
   if (confirm(text)){*/
    var url = "../share/request/setAccObjectives.php?userID="+ userID +"&creatorID="+ creatorID +"&enablingObjectiveID="+ enablingObjectiveID+"&statusID="+statusID;
    req = XMLobject();
    if(req) {        
        req.onreadystatechange = function (){
            if (req.readyState===4 && req.status===200){
                switch(statusID) {
                case 0:     document.getElementById(enablingObjectiveID+"_green").className     = 'space-left checkgreenbtn pointer_hand';
                            document.getElementById(enablingObjectiveID+"_orange").className    = 'space-left checkorangebtn pointer_hand';
                            document.getElementById(enablingObjectiveID+"_white").className     = 'space-left checkwhitebtn pointer_hand';
                            document.getElementById(enablingObjectiveID+"_red").className       = 'space-left checkactiveredbtn pointer_hand';     
                    break;
                case 1:     document.getElementById(enablingObjectiveID+"_green").className     = 'space-left checkactivegreenbtn pointer_hand';
                            document.getElementById(enablingObjectiveID+"_orange").className    = 'space-left checkorangebtn pointer_hand';
                            document.getElementById(enablingObjectiveID+"_white").className     = 'space-left checkwhitebtn pointer_hand';
                            document.getElementById(enablingObjectiveID+"_red").className       = 'space-left checkredbtn pointer_hand';     
                    break;
                case 2:     document.getElementById(enablingObjectiveID+"_green").className     = 'space-left checkgreenbtn pointer_hand';
                            document.getElementById(enablingObjectiveID+"_orange").className    = 'space-left checkactiveorangebtn pointer_hand';
                            document.getElementById(enablingObjectiveID+"_white").className     = 'space-left checkwhitebtn pointer_hand';
                            document.getElementById(enablingObjectiveID+"_red").className       = 'space-left checkredbtn pointer_hand';     
                    break;
                case 3:     document.getElementById(enablingObjectiveID+"_green").className     = 'space-left checkgreenbtn pointer_hand';
                            document.getElementById(enablingObjectiveID+"_orange").className    = 'space-left checkorangebtn pointer_hand';
                            document.getElementById(enablingObjectiveID+"_white").className     = 'space-left checkactivewhitebtn pointer_hand';
                            document.getElementById(enablingObjectiveID+"_red").className       = 'space-left checkredbtn pointer_hand';     
                    break;
                }
            }
        };
            req.open("GET", url, true);
            req.send(null);
        }
    //}
}
/**
 * 
 * @param {string} url
 * @returns {html}
 */
function getRequest(url){
    req = XMLobject();
    if(req) {        
        req.onreadystatechange = answer;
        req.open("GET", url, true);
        req.send(null);
    }    
}

/**
 * shows description
 * @param string description
 * @returns html
 */
function showDescription(/*terID, enaID*/) {
    if (arguments.length === 1) {
        var url = "../share/request/showDescription.php?terminalObjectiveID="+ arguments[0]; 
    } else if (arguments.length === 2) {
        var url = "../share/request/showDescription.php?terminalObjectiveID="+ arguments[0]+"&enablingObjectiveID="+ arguments[1]; 
    }
    getRequest(url);
}

/**
 * Show material of a objective
 **/
function showMaterial() {
    if (arguments.length === 3) {
       var url = "../share/request/showMaterial.php?edit="+arguments[0]+"&terminalObjectiveID="+ arguments[1] +"&enablingObjectiveID="+ arguments[2];
    } else if (arguments.length === 2) {
       var url = "../share/request/showMaterial.php?edit="+arguments[0]+"&terminalObjectiveID="+ arguments[1];
    }
    getRequest(url);
}


/**
 * show Learner who have complete this objective
 * @param {int} group
 * @param {int} enablingObjectiveID
 * @returns {html}
 */
function getHelp(group, enablingObjectiveID) {
    getRequest("../share/request/getHelp.php?group="+ group +"&enablingObjectiveID="+ enablingObjectiveID);
}


function curriculumdocs(link) {
    window.open(link, '_blank', '')
}
/**
 * Add topic
 * @param {int} curriculumID
 * @returns {html}
 */
function addterminalObjective(curriculumID) {  
    getRequest("../share/request/terminalObjective.php?curriculumID="+ curriculumID);
}

/**
 * Add objective
 * @param {int} curriculumID
 * @param {int} terminalObjectiveID
 * @returns {html}
 */
function addenablingObjective(curriculumID, terminalObjectiveID) {
    var url = "../share/request/enablingObjective.php?curriculumID="+ curriculumID+"&terminalObjectiveID="+ terminalObjectiveID;
    getRequest(url);
}

function editObjective() {
    if (arguments.length === 2) {
       var url = "../share/request/terminalObjective.php?curriculumID="+ arguments[0] +"&terminalObjectiveID="+ arguments[1]+ "&edit=true"; 
    }
    else if (arguments.length === 3) {
       var url = "../share/request/enablingObjective.php?curriculumID="+ arguments[0] +"&terminalObjectiveID="+ arguments[1] +"&enablingObjectiveID="+ arguments[2] + "&edit=true"; 
    }
    getRequest(url);
}


function order() {
    if (arguments.length === 4) {
        var url = "../share/request/orderObjectives.php?order="+ arguments[0] +"&orderID="+ arguments[1]+"&curriculumID="+ arguments[2] +"&terminalObjectiveID="+ arguments[3];
    } else if (arguments.length === 5) {
        var url = "../share/request/orderObjectives.php?order="+ arguments[0] +"&orderID="+ arguments[1]+"&curriculumID="+ arguments[2] +"&terminalObjectiveID="+ arguments[3]+"&enablingObjectiveID="+ arguments[4]; 
    }
    req = XMLobject();
    if(req) {        
        req.onreadystatechange = answer; 
        req.open("GET", url, true);
        req.send(null);
    }
}

/**
 * delete a dataset in a db-table
 **/
function del() {
    if (confirm("Datensatz wirklich löschen?")) {   //Meldung "Wirklich löschen?"
        var url = "../share/request/delete.php?db="+arguments[0]+"&id="+ arguments[1]; 
        
        req = XMLobject();
        if(req) {      
            if (arguments[0] === 'message'){                                                    // Mail aus Liste entfernen und gelöschte Mail ausblenden
              document.getElementById(arguments[2]+'_'+arguments[1]).style.display='none';
              document.getElementById('mailbox').style.display='none';
            } else {
               req.onreadystatechange = answer; //Dialog mit Meldungen zeigen 
               //Reload erfolgt über Submit des Popups req.onreadystatechange = reloadPage; //window.location.reload() wichtig, damit Änderungen angezeigt werden
            }
            req.open("GET", url, true);
            req.send(null);
        }
    }
}

function removeMaterial(){
    if (confirm("Datei wirklich löschen?")) {   //Meldung "Wirklich löschen?"
        var url     = "../share/request/deleteFile.php?fileID="+ arguments[0];         //Link unterscheidet sich von den anderen Funktionen, da diese Funktion ((nur)vom upload_frame aufgerufen wird
        var fileID  = arguments[0];
        req         = XMLobject();
        if(req) {        
            req.onreadystatechange = function(){
                    hideFile(fileID);
                };
            req.open("GET", url, true);
            req.send(null);
        }
    }
}

function deleteFile() {
    if (confirm("Datei wirklich löschen?")) {   //Meldung "Wirklich löschen?"
        var url     = "deleteFile.php?fileID="+ arguments[0];         //Link unterscheidet sich von den anderen Funktionen, da diese Funktion ((nur)vom upload_frame aufgerufen wird
        var fileID  = arguments[0];
        req         = XMLobject();
        if(req) {        
            req.onreadystatechange = function(){
                    hideFile(fileID);
                };
            req.open("GET", url, true);
            req.send(null);
        }
    }
}

function expelUser() {
    if (confirm("Benutzer wirklich aus der Lerngruppe ausschreiben?")) {
        var url     = "../share/request/expelUser.php?groupsID="+ arguments[0] +"&userID="+ arguments[1]; 
        getRequest(url);
    }
}

function expelFromInstituion() {
    if (confirm("Benutzer wirklich aus der Institution ausschreiben?")) { 
        var url     = "../share/request/expelFromInstitution.php?institutionID="+ arguments[0] +"&userID="+ arguments[1]; 
        getRequest(url);
    }
}

function getStates(){  
    if (arguments[1]){
        var url = "../share/request/getStates.php?country_id="+ arguments[0] +"&name="+ arguments[1] +"&state_id="+ arguments[2] ;
    } else {
        var url = "../share/request/getStates.php?country_id="+ arguments[0];
    }
    req = XMLobject();
    if(req) {        
        req.onreadystatechange = setStates;
        req.open("GET", url, true);
        req.send(null);
    }
}

function setStates() {
    if (req.readyState === 4) {  
        if (req.status === 200) {
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
/**
 * argument[0] = institution_id
 * argument[0] = name des zu erstellenden select inputs -> wichtig für die weiterverarbeitung!
 */
function getGroups(){  
    
    if (arguments[1]){
        var url = "../share/request/getGroups.php?institution_id="+ arguments[0] +"&name="+ arguments[1] +"&group_id="+ arguments[2] ;
    } else {
        var url = "../share/request/getGroups.php?institution_id="+ arguments[0];
    }
    req = XMLobject();
    if(req) {        
        req.onreadystatechange = setGroups;
        req.open("GET", url, true);
        req.send(null);
    }
}

function setGroups() {
    if (req.readyState === 4) {  
        if (req.status === 200) {
           if (req.responseText.length != 1){ //bei einem leeren responseText =1 ! wird das Fenster neu geladen
                      if (document.getElementById('groups')){
                           document.getElementById('groups').innerHTML = req.responseText;
                      } else {
                          alert(req.responseText); //unschön, aber #popup ist vom modalframe aus nicht verfügbar
                      }  
           } else {
               window.location.reload();
           }
        }
    }   
}
//wenn checkbox angeklickt, dann wird element mit id angezeigt
function checkbox_addForm(){//arguments checked, style, id, invers_id -> if checked id is 'block' invers_id is 'none''
    if (arguments[0]) {
        document.getElementById(arguments[2]).style.display=arguments[1];
        document.getElementById(arguments[3]).style.display='none';
    } else {
        document.getElementById(arguments[2]).style.display='none';
        document.getElementById(arguments[3]).style.display=arguments[1];
    }
}

function hideMaterial(){
    document.getElementById('popup').style.visibility='hidden';
}





function checkPaginatorRow(paginatorName, rowNr) {
    var ch = document.getElementById(paginatorName+''+rowNr);
    if(ch) {
        if (ch.checked === false){
            ch.checked = true;
            document.getElementById('row'+rowNr).className = 'activecontenttablerow';
        } else {
            ch.checked = false;
            document.getElementById('row'+rowNr).className = 'contenttablerow';
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

//icon function
function showSubjectIcon(path, icon){
    document.getElementById('icon_img').src = path + icon;
}

/**
 * add a badge
 **/
function badge() {
   if (arguments.length === 4) {
        var url = "../share/request/addBadge.php?curriculumID="+ arguments[0] +"&terminalObjectiveID="+ arguments[1] +"&enablingObjectiveID=notset"+"&userID="+ arguments[2]+"&lastlogin="+ arguments[3]; 
    } else if (arguments.length === 5) { 
        var url = "../share/request/addBadge.php?curriculumID="+ arguments[0] +"&terminalObjectiveID="+ arguments[1] +"&enablingObjectiveID="+ arguments[2]+"&userID="+ arguments[3]+"&lastlogin="+ arguments[4]; 
    }
    getRequest(url);
}

function getBadge() {
    if (arguments.length === 2) {
       var url = "../share/request/getBadge.php?curriculumID="+ arguments[0] +"&terminalObjectiveID="+ arguments[1]; 
    } else if (arguments.length === 3) {
       var url = "../share/request/getBadge.php?curriculumID="+ arguments[0] +"&terminalObjectiveID="+ arguments[1] +"&enablingObjectiveID="+ arguments[2]; 
    }
    getRequest(url);
}
function addQuiz() {
    if (arguments.length === 2) {
       var url = "../share/request/addQuiz.php?curriculumID="+ arguments[0] +"&terminalObjectiveID="+ arguments[1]; 
    } else if (arguments.length === 3) {
       var url = "../share/request/addQuiz.php?curriculumID="+ arguments[0] +"&terminalObjectiveID="+ arguments[1] +"&enablingObjectiveID="+ arguments[2]; 
    }
    getRequest(url);
}
function showQuiz() {
    if (arguments.length === 2) {
       var url = "../share/request/showQuiz.php?curriculumID="+ arguments[0] +"&terminalObjectiveID="+ arguments[1]; 
    } else if (arguments.length === 3) {
       var url = "../share/request/showQuiz.php?curriculumID="+ arguments[0] +"&terminalObjectiveID="+ arguments[1] +"&enablingObjectiveID="+ arguments[2]; 
    }
    getRequest(url);
}

function sendForm(form_id, file){
    var form = $('#'+form_id);
    var data = form.serialize();
    //alert('test: ');
    $.post('../share/request/'+file, data, function(response) {
        $('#'+form_id+'_result').html(response)            
    });
    return false;           
    
}

function updateFileHits(){
     var url = "../share/request/updateFileHits.php?fileID="+ arguments[0];

    req = XMLobject();
    if(req) {        
            req.open("GET", url, true);
            req.send(null);
        }  
}

function editBulletinBoard() {
    var url = "../share/request/bulletinBoard.php";
    getRequest(url);
}


function popupFunction(){
    tinymce.init({  
        selector: "textarea",
        theme:     "modern",
        plugins: [ "advlist autolink code colorpicker lists link image charmap print preview hr anchor pagebreak",
                    "searchreplace wordcount visualblocks visualchars fullscreen",
                    "insertdatetime media nonbreaking save textcolor table contextmenu directionality",
                    "emoticons paste"],
        toolbar1:   "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media | forecolor backcolor emoticons | fileframe",      
    });
};

function setFormData() {
    var url     = "../share/request/setFormData.php?file="+ arguments[0];   
    
    req         = XMLobject();
    if(req) {        
        req.onloadend = function(){
            if (req.responseText.length > 1){
                //alert(req.responseText);
                c = JSON.parse(req.responseText);
                
                $('#c_curriculum', top.document).val(c.curriculum);
                $('#c_description', top.document).val(c.description);
                set_select('c_grade',       c.grade_id,         'value', 'top');
                set_select('c_subject',     c.subject_id,       'value', 'top');
                set_select('c_schooltype',  c.schooltype_id,    'value', 'top');
                set_select('c_state',       c.state_id,         'value', 'top');
                set_select('c_country',     c.country_id,       'value', 'top');
                set_select('c_icon',        c.icon_id,          'value', 'top');
            }   
        }
    };
    req.open("GET", url, true);
    req.send(null);  
}

function val_exist_in_select(element, val){
    for (i = 0; i < document.getElementById(element).length; ++i){
        if (document.getElementById(element).options[i].value == val){
          return true;
        } else {return false;}
    }
}

function set_select(element, val, field, level) {
    if (typeof(field)==='undefined')            field           = 'innerHTML';
    if (typeof(level)==='undefined')            level           = 'document';
    if (level === 'top'){
        var sel = top.document.getElementById(element);
        
    } else {
        var sel = document.getElementById(element);
    }
    
    for(var i = 0, j = sel.options.length; i < j; ++i) {
        if (field === 'innerHTML'){
            if(sel.options[i].innerHTML === val) {
               sel.selectedIndex = i;
               break;
            }
        } 
        if (field === 'value'){
            if(sel.options[i].value === val) {
               sel.selectedIndex = i;
               break;
            }
        }
    }
}