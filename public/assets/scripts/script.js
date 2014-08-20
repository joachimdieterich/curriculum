/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
// Animationen
/*
 * Fadeout
 */
function fadeout() {
    alert(test);
  $('#messagebox').fadeOut('slow', complete)
}

function showMessagebox(){
   $("#messagebox").slideToggle();   
}
/* Link in (neuem Fenster öffnen)*/
 function openLink(URL, target) {
    if (URL != ''){ //Sortiert "leere" Anfragen aus.
        window.open(URL, target);
    }
  
}
/*
 * div submit button 
 */
function submitOnClick(formName){
        document.forms[formName].submit();
    }

/*
 * unmask password fields
 */
function unmask(ID,checked){
    
    if (checked){
        document.getElementById(ID).type = 'text';
    } else {
       document.getElementById(ID).type = 'password'; 
    }
    }

/* XMLObject für SQL Abfragen*/
function showMaterial( curriculumID, terminalObjectiveID, enablingObjectiveID) {
    var url = "assets/scripts/request.php?ajax=on&function=showMaterial&curriculumID="+ curriculumID +"&terminalObjectiveID="+ terminalObjectiveID +"&enablingObjectiveID="+ enablingObjectiveID;

req = XMLobject();
    if(req) {        
        req.onreadystatechange = answer;
        req.open("GET", url, true);
        req.send(null);
    }
}

function getHelp(group,curriculumID, terminalObjectiveID, enablingObjectiveID) {
    var url = "assets/scripts/request.php?ajax=on&function=getHelp&group="+ group +"&curriculumID="+ curriculumID +"&terminalObjectiveID="+ terminalObjectiveID +"&enablingObjectiveID="+ enablingObjectiveID;

req = XMLobject();
    if(req) {        
        req.onreadystatechange = answer;
        req.open("GET", url, true);
        req.send(null);
    }
}

function addterminalObjective(curriculumID) {
    var url = "assets/scripts/request.php?ajax=on&function=addterminalObjective&curriculumID="+ curriculumID;
    
    req = XMLobject();
    if(req) {        
        req.onreadystatechange = answer;
        req.open("GET", url, true);
        req.send(null);
    }
}

function addenablingObjective(curriculumID, terminalObjectiveID) {
    var url = "assets/scripts/request.php?ajax=on&function=addenablingObjective&curriculumID="+ curriculumID+"&terminalObjectiveID="+ terminalObjectiveID;
    
    req = XMLobject();
    if(req) {        
        req.onreadystatechange = answer;
        req.open("GET", url, true);
        req.send(null);
    }
}

function deleteObjective() {

    if (confirm("Thema bzw. Ziel wirklich löschen?")) {   //Meldung "Wirklich löschen?"
        if (arguments.length == 2) {
        var url = "assets/scripts/request.php?ajax=on&function=deleteObjective&curriculumID="+ arguments[0] +"&terminalObjectiveID="+ arguments[1] +"&enablingObjectiveID=notset"; 
        }
        else if (arguments.length == 3) { 
        var url = "assets/scripts/request.php?ajax=on&function=deleteObjective&curriculumID="+ arguments[0] +"&terminalObjectiveID="+ arguments[1] +"&enablingObjectiveID="+ arguments[2]; 
        }
        
        req = XMLobject();
        if(req) {        
            req.onreadystatechange = answer; //Dialog mit Meldungen zeigen
            req.onreadystatechange = reloadPage; //window.location.reload() wichtig!
            req.open("GET", url, true);
            req.send(null);
        }
    }
}

function deleteCurriculum() {
    if (confirm("Lehrplan wirklich löschen?")) {   //Meldung "Wirklich löschen?"
        var url = "assets/scripts/request.php?ajax=on&function=deleteCurriculum&curriculumID="+ arguments[0]; 
        
        req = XMLobject();
        if(req) {        
            req.onreadystatechange = answer; //Dialog mit Meldungen zeigen
            req.open("GET", url, true);
            req.send(null);
        }
    }
}

function deleteGroup() {
    if (confirm("Lerngruppe wirklich löschen?")) {   //Meldung "Wirklich löschen?"
        var url = "assets/scripts/request.php?ajax=on&function=deleteGroup&group_id="+ arguments[0]; 
        
        req = XMLobject();
        if(req) {        
            req.onreadystatechange = answer; //Dialog mit Meldungen zeigen
            req.open("GET", url, true);
            req.send(null);
        }
    }
}

function deleteSemester() {
    if (confirm("Lernzeitraum wirklich löschen?")) {   //Meldung "Wirklich löschen?"
        var url = "assets/scripts/request.php?ajax=on&function=delete_semester&semester_id="+ arguments[0]; 
        
        req = XMLobject();
        if(req) {        
            req.onreadystatechange = answer; //Dialog mit Meldungen zeigen
            req.open("GET", url, true);
            req.send(null);
        }
    }
}

function deleteSubject() {
    if (confirm("Fach wirklich löschen?")) {   //Meldung "Wirklich löschen?"
        var url = "assets/scripts/request.php?ajax=on&function=delete_subject&subject_id="+ arguments[0]; 
        
        req = XMLobject();
        if(req) {        
            req.onreadystatechange = answer; //Dialog mit Meldungen zeigen
            req.open("GET", url, true);
            req.send(null);
        }
    }
}

function del() {
    if (confirm("Datensatz wirklich löschen?")) {   //Meldung "Wirklich löschen?"
        var url = "assets/scripts/request.php?ajax=on&function=delete&db="+arguments[0]+"&id="+ arguments[1]+"&creator_id="+ arguments[2]; 
        
        req = XMLobject();
        if(req) {        
            req.onreadystatechange = answer; //Dialog mit Meldungen zeigen
            req.open("GET", url, true);
            req.send(null);
        }
    }
}

function deleteFile() {
    if (confirm("Datei wirklich löschen?")) {   //Meldung "Wirklich löschen?"
        //Link unterscheidet sich von den anderen funktionen, da diese funktion vom upload_frame aufgerufen wird
        var url = "../../../../assets/scripts/request.php?ajax=on&function=deleteFile&fileID="+ arguments[1]; 
        var prefix = arguments[0];
        var fileID = arguments[1]
        req = XMLobject();
        if(req) {        
            req.onreadystatechange = function(){
                    hideFile(prefix+''+fileID);
                }
            req.open("GET", url, true);
            req.send(null);
        }
    }
}

function expelUser() {
    if (confirm("Benutzer wirklich aus der Lerngruppe ausschreiben?")) {   //Meldung "Wirklich löschen?"
        var url = "assets/scripts/request.php?ajax=on&function=expelUser&groupsID="+ arguments[0] +"&userID="+ arguments[1]; 
       
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
        var url = "assets/scripts/request.php?ajax=on&function=order&order="+ arguments[0] +"&order_id="+ arguments[1]+"&curriculum_id="+ arguments[2] +"&terminal_objective_id="+ arguments[3];
    } else if (arguments.length == 5) {
        var url = "assets/scripts/request.php?ajax=on&function=order&order="+ arguments[0] +"&order_id="+ arguments[1]+"&curriculum_id="+ arguments[2] +"&terminal_objective_id="+ arguments[3]+"&enabling_objective_id="+ arguments[4]; 
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
       var url = "assets/scripts/request.php?ajax=on&function=editterminalObjective&curriculumID="+ arguments[0] +"&terminalObjectiveID="+ arguments[1] +"&enablingObjectiveID=notset"; 
    }
    else if (arguments.length == 3) {
       var url = "assets/scripts/request.php?ajax=on&function=editenablingObjective&curriculumID="+ arguments[0] +"&terminalObjectiveID="+ arguments[1] +"&enablingObjectiveID="+ arguments[2]; 
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
       var url = "assets/scripts/request.php?ajax=on&function=editMaterial&curriculumID="+ arguments[0] +"&terminalObjectiveID="+ arguments[1] +"&enablingObjectiveID="+ arguments[2]; 
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
               document.getElementById('row'+arguments[0]).style.visibility='hidden';           
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


function setAccomplishedObjectives(creatorID, userID, paginatorfirst, paginatorLimit, terminalObjectiveID, enablingObjectiveID){    
    
    var statusID = document.getElementById(terminalObjectiveID+'_'+enablingObjectiveID).innerHTML;
    if (statusID == 1) {
        statusID = 0;
    } else {
        statusID = 1;
    }
        
    //prüfen wie viele ausgewählt sind
    if (userID.length > 1){
        alert('Es darf nur ein Benutzer ausgewählt sein');
    } else if(userID == '') {
        alert('Es muss ein Benutzer ausgewählt sein');
    } else {
    var url = "assets/scripts/request.php?ajax=on&function=setAccomplishedObjectives&userID="+ userID +"&creatorID="+ creatorID +"&paginatorfirst="+ paginatorfirst +"&terminalObjectiveID="+ terminalObjectiveID +"&enablingObjectiveID="+ enablingObjectiveID+"&statusID="+statusID;

    req = XMLobject();
    if(req) {        
        req.onreadystatechange = function (){
            if (req.readyState==4 && req.status==200){
                if (statusID==0) {
                        document.getElementById(terminalObjectiveID+"style"+enablingObjectiveID).className = 'box gray-gradient border-radius box-shadow gray-border boxred';
                        document.getElementById(terminalObjectiveID+"_"+enablingObjectiveID).innerHTML=0;
                }
                if (statusID==1) {
                        document.getElementById(terminalObjectiveID+"style"+enablingObjectiveID).className = 'box gray-gradient border-radius box-shadow gray-border boxgreen';
                        document.getElementById(terminalObjectiveID+"_"+enablingObjectiveID).innerHTML=1;
                }
            }
        }
            req.open("GET", url, true);
            req.send(null);
        }
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
    var url = "assets/scripts/request.php?ajax=on&function=setAccomplishedObjectives&userID="+ userID +"&creatorID="+ creatorID +"&enablingObjectiveID="+ enablingObjectiveID+"&statusID="+statusID;

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


//Messagebox ausblenden
function hideMessagebox(){
       document.getElementById('messagebox').style.display = 'none';
}

//Fileuploadframe ausblenden
function hideUploadframe(){
       document.getElementById('uploadframe').style.display = 'none';
}

//Druckfunktion
/*function printPage(printpage){
    var headstr = "<html><head><title></title></head><body>";
    var footstr = "</body>";
   //var newstr = document.all.item(printpage).innerHTML; 
    var newstr = document.getElementById(printpage).innerHTML;  //so funktioniert es auch im firefox
    var oldstr = document.body.innerHTML;
    document.body.innerHTML = headstr+newstr+footstr;
    window.print();
    document.body.innerHTML = oldstr;
    return false;
}*/

function confirmDialog(text) {
    if (confirm(text)){
        return true; 
    } else {
        return false;
    }
}


// Message functions

function loadmail(mail_id, mailbox) {
    var url = "assets/scripts/request.php?ajax=on&function=loadMail&mailID="+ mail_id; 

    req = XMLobject();
        if(req) {        
            req.onreadystatechange = function(){
                mail(mail_id, mailbox);
            }
            req.open("GET", url, true);
            req.send(null);
        }
}

function mail(mail_id, mailbox) {
    if (req.readyState == 4) {  
        if (req.status == 200) {
            
           if (req.responseText.length != 1){ //bei einem leeren responseText =1 ! wird das Fenster neu geladen
                  document.getElementById('mailbox').innerHTML = req.responseText;
                  document.getElementById(mailbox+'_'+mail_id).className = 'contenttablerow';
                  document.getElementById('mailbox').style.width = (document.body.offsetWidth - 540) +"px";
           } else {
               window.location.reload();
           }
        }
    }   
}

function loadStates(){    
    if (arguments[1]){
        var url = "assets/scripts/request.php?ajax=on&function=loadStates&country_id="+ arguments[0] +"&name="+ arguments[1] +"&state_id="+ arguments[2] ;
    } else {
        var url = "assets/scripts/request.php?ajax=on&function=loadStates&country_id="+ arguments[0];
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
// end Message functions

//icon function
function showSubjectIcon(path, icon){
    document.getElementById('icon').src = path + icon;
}