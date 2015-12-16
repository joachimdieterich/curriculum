{if $my_role_id != -1}{*bei der Registrierung und installation wird kein Menü angezeigt*}
    {if isset($mySemester) AND count($mySemester) > 1}
    <nav role="user" class="menu border-box">
        <ul class="group">
            <li class="menuheader"> Lernzeitraum</li>
            <li><form method='post' action='index.php?action={$page_action}'>
                <select name="mySemester" onchange="this.form.submit()">
                {section name=res loop=$mySemester}  
                    <OPTION label="{$mySemester[res]->semester} ({$mySemester[res]->institution})" value={$mySemester[res]->id} {if isset($my_semester_id)}{if $mySemester[res]->id eq $my_semester_id}selected{/if}{/if}>{$mySemester[res]->semester} ({$mySemester[res]->institution})</OPTION>
                {/section}
                </select>
            </form></li>
        </ul>
    </nav>
    {/if}

    {if checkCapabilities('menu:readMyCurricula', $my_role_id, false)}
    <nav role="curriculum" class="menu border-box ">
        <ul class="group">
            <li class="menuheader">Lehrpläne</li>
            {if $my_enrolments != ''}
                {foreach item=cur_menu from=$my_enrolments}
                    {if $cur_menu->semester_id eq $my_semester_id}
                    <li><p><a href="index.php?action=view&curriculum={$cur_menu->id}&group={$cur_menu->group_id}">{$cur_menu->curriculum}<span> {$cur_menu->groups}</span></a></p></li>
                    {/if}
                {/foreach}
            {else}<li><p>Sie sind in keinem Lehrplan eingeschrieben</p></li>
            {/if}    
        </ul>
    </nav>
    {/if}      
        
    {if checkCapabilities('menu:readMyInstitution', $my_role_id, false)}
    <nav role="edit" class="menu border-box">
        <ul class="group">
            <li class="menuheader">Institutionen</li>
            {if checkCapabilities('menu:readObjectives', $my_role_id, false)}
                <li><p><a href="index.php?action=objectives&reset=true">Lernstand</a></p></li> 
            {/if}
            {if checkCapabilities('menu:readCurriculum', $my_role_id, false)}
                <li><p><a href="index.php?action=curriculum&reset=true">Lehrpläne</a></p></li>                    
            {/if}
            {if checkCapabilities('menu:readGroup', $my_role_id, false)}
                <li><p><a href="index.php?action=group&reset=true">Lerngruppen</a></p></li>
            {/if}
            {if checkCapabilities('menu:readUser', $my_role_id, false)}
                <li><p><a href="index.php?action=user&reset=true">Benutzerverwaltung</a></p></li>
            {/if}
            {if checkCapabilities('menu:readRole', $my_role_id, false)}
                <li><p><a href="index.php?action=role&reset=true">Rollenverwaltung</a></p></li>
            {/if}
            {if checkCapabilities('menu:readGrade', $my_role_id, false)}
                <li><p><a href="index.php?action=grade">Klassenstufen</a></p></li>
            {/if}
            {if checkCapabilities('menu:readSubject', $my_role_id, false)}
                <li><p><a href="index.php?action=subject&reset=true">Fächer</a></p></li>
            {/if}
            {if checkCapabilities('menu:readSemester', $my_role_id, false)}
                <li><p><a href="index.php?action=semester&reset=true">Lernzeiträume</a></p></li>
            {/if}
            {if checkCapabilities('menu:readBackup', $my_role_id, false)}
                <li><p><a href="index.php?action=backup&reset=true">Backup</a></p></li>
            {/if}
            {if checkCapabilities('menu:readCertificate', $my_role_id, false)}   
            <li><p><a href="index.php?action=certificate&reset=true">Zertifikate einrichten</a></p></li>
            {/if}
            {if checkCapabilities('menu:readInstitution', $my_role_id, false)}   
            <li><p><a href="index.php?action=institution&reset=true">Institutionen</a></p></li>
            {/if}
        </ul>
    </nav>   
    {/if}


    {if checkCapabilities('menu:readLog', $my_role_id, false)}
    <nav role="log" class="menu border-box">
        <ul class="group">
            <li class="menuheader">Administration</li>
            <li><p><a href="index.php?action=log">Berichte</a></p></li>
        </ul>
    </nav> 
    {/if}
{/if}

{if $my_role_id == -1 and isset($install)}
<nav role="log" class="menu border-box">
    <ul class="group">
        <li class="menuheader">Installation</li>
        <li><p>{*<a href="index.php?action=install&step=1">*}{if $step eq 1}<strong>{/if}1 Datenbank einrichten{if $step eq 1}</strong>{/if}{*</a>*}</p></li>
        <li><p>{*<a href="index.php?action=install&step=2">*}{if $step eq 2}<strong>{/if}2 Curriculum einrichten{if $step eq 2}</strong>{/if}{*</a>*}</p></li>
        <li><p>{*<a href="index.php?action=install&step=3">*}{if $step eq 3}<strong>{/if}3 Institution einrichten{if $step eq 3}</strong>{/if}{*</a>*}</p></li>
        <li><p>{*<a href="index.php?action=install&step=4">*}{if $step eq 4}<strong>{/if}4 Administrator einrichten{if $step eq 4}</strong>{/if}{*</a>*}</p></li>

    </ul>
</nav> 
{/if}