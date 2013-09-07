{if $my_role_id != -1}{*bei der Registrierung wird kein Menü angezeigt*}
<nav role="user" class="space-top gray-gradient border-radius box-shadow gray-border">
<ul class="group">
        <li class="border-top-radius contentheader">Mein Profil</li>
        <div ><img src="{$avatar_url}{$my_avatar}"></div>
        <div ><p><strong>{$my_firstname} {$my_lastname}</strong></p>
            <p><a href="index.php?action=profile">Profil bearbeiten</a><p>
            <p><a href="index.php?action=password">Password ändern</a><p> 
            <p><a href="index.php?action=messages">Mitteilungen</a><p> 
            <p><a href="assets/scripts/libs/modal-upload/uploadframe.php?userID={$my_id}&context=userFiles&target=NULL&format=1&placeValuesBeforeTB_=savedValues&TB_iframe=true&width=710&modal=true" class="thickbox">Meine Dateien</a><p> 
            
        <p>Letzter Login: {$my_last_login}</p>
        </div> 
    </ul>
</nav>

<nav role="curriculum" class="space-top gray-gradient border-radius box-shadow gray-border">
    <ul class="group">
        <li class="border-top-radius contentheader">Meine Lehrpläne</li>
        {if $my_enrolments != ''}
            {foreach item=cur from=$my_enrolments}
                <li><p><a href="index.php?action=view&curriculum={$cur.id}&group={$cur.group_id}">{$cur.curriculum}<span> {$cur.groups}</span></a></p></li>
            {/foreach}
        {else}<li><p>Sie sind in keinem Lehrplan eingeschrieben</p></li>
        {/if}    
    </ul>
</nav>
{/if}    
    
{if $my_role_id != -1}
    <nav role="edit" class="space-top gray-gradient border-radius box-shadow gray-border">
        <ul class="group">
            <li class="border-top-radius contentheader">Meine Institution</li>
  {if $my_role_id == 1 or $my_role_id == 3 or $my_role_id == 4}
            <li><p><a href="index.php?action=teacherObjectives&reset=true">Lernstand</a></p></li>            
            <li><p><a href="index.php?action=teacherCurriculum&reset=true">Lehrpläne</a></p></li>
            <li><p><a href="index.php?action=teacherGroups&reset=true">Lerngruppen verwalten</a></p></li>
            {*<li><p><a href="index.php?action=teacherProfile">Benutzer anlegen</a></p></li>*}
            {*<li><p><a href="index.php?action=teacherUserImport&reset=true">Benutzer-Liste hochladen</a></p></li>*}
            <li><p><a href="index.php?action=teacherUser&reset=true">Benutzerverwaltung</a></p></li>
  {if $my_role_id == 1 or $my_role_id == 4}
            <li><p><a href="index.php?action=teacherGrade">Klassenstufen</a></p></li>
            <li><p><a href="index.php?action=teacherSubject&reset=true">Fächer</a></p></li>
            <li><p><a href="index.php?action=teacherSemester&reset=true">Lernzeiträume verwalten</a></p></li>
            <li><p><a href="index.php?action=teacherBackup&reset=true">Backup erstellen</a></p></li>
            <li><p><a href="index.php?action=teacherConfirm&reset=true">Freigaben</a></p></li>
  {/if} {/if}
  
            <li><p><a href="index.php?action=config">Einstellungen</a></p></li>
          
        </ul>
    </nav>   
{/if}


{if $my_role_id == 1}
    <nav role="log" class="space-top gray-gradient border-radius box-shadow gray-border">
        <ul class="group">
            <li class="border-top-radius contentheader">Administration Log</li>
            <li><p><a href="index.php?action=adminLog">Logfiles</a></p></li>
        </ul>
    </nav> 
{/if}
{if $my_role_id == -1 and isset($install)}
    <nav role="log" class="space-top gray-gradient border-radius box-shadow gray-border">
        <ul class="group">
            <li class="border-top-radius contentheader">Installation</li>
            <li><p>{*<a href="index.php?action=install&step=1">*}{if $step eq 1}<strong>{/if}1 Datenbank einrichten{if $step eq 1}</strong>{/if}{*</a>*}</p></li>
            <li><p>{*<a href="index.php?action=install&step=2">*}{if $step eq 2}<strong>{/if}2 Curriculum einrichten{if $step eq 2}</strong>{/if}{*</a>*}</p></li>
            <li><p>{*<a href="index.php?action=install&step=3">*}{if $step eq 3}<strong>{/if}3 Institution einrichten{if $step eq 3}</strong>{/if}{*</a>*}</p></li>
            <li><p>{*<a href="index.php?action=install&step=4">*}{if $step eq 4}<strong>{/if}4 Administrator einrichten{if $step eq 4}</strong>{/if}{*</a>*}</p></li>
            
        </ul>
    </nav> 
{/if}

<p>&nbsp;</p><p>&nbsp;</p>