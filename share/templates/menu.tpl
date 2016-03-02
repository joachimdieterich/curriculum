{if isset($mySemester) AND count($mySemester) > 1}
<ul class="nav nav-sidebar">
    <li class="text-uppercase"><a href="#"><strong class="hidden-sm">Lernzeitraum</strong></a></li>
    
        
    <div class="dropdown hidden-sm" style="margin-left:20px;"><ico class="clockbtn invert"></ico>
        <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
          {$my_semester}
          <span class="caret"></span>
        </button>
        <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
            {section name=res loop=$mySemester}  
                <li><a href="index.php?action={$page_action}&mySemester={$mySemester[res]->id}">{$mySemester[res]->semester} ({$mySemester[res]->institution})</a></li>
                
            {/section}
            
            <OPTION label="{$mySemester[res]->semester} ({$mySemester[res]->institution})" value={$mySemester[res]->id} {if isset($my_semester_id)}{if $mySemester[res]->id eq $my_semester_id}selected{/if}{/if}>{$mySemester[res]->semester} ({$mySemester[res]->institution})</OPTION>
        </ul>
    </div>

</ul>
{/if}

{if checkCapabilities('menu:readMyCurricula', $my_role_id, false)}
<ul class="nav nav-sidebar">
    <li class="text-uppercase"><a href="#"><strong class="hidden-sm">Lehrpläne</strong></a></li>
    {if $my_enrolments != ''}
        {foreach item=cur_menu from=$my_enrolments}
            {if $cur_menu->semester_id eq $my_semester_id}
            <li {if ($page_curriculum eq $cur_menu->id) && ($page_group eq $cur_menu->group_id)} class="active"{/if}>
                <a href="index.php?action=view&curriculum={$cur_menu->id}&group={$cur_menu->group_id}">
                    <ico class="listbtn invert"></ico><span class="hidden-sm">{$cur_menu->curriculum}<span class="nav-title floatright"> {$cur_menu->groups}</span></span>
                </a>
            </li>
            {/if}
        {/foreach}
    {else}<li><p>Sie sind in keinem Lehrplan eingeschrieben</p></li>
    {/if}    
</ul>
{/if}      

{if checkCapabilities('menu:readMyInstitution', $my_role_id, false)}
<ul class="nav nav-sidebar">
    <li class="text-uppercase"><a href="#"><strong class="hidden-sm">Institution</strong></a></li>
    {if checkCapabilities('menu:readObjectives', $my_role_id, false)}
        <li {if $page_action eq 'objectives'}class="active"{/if}>
            <a href="index.php?action=objectives&reset=true">
                <ico class="checkbtn invert"></ico><span class="hidden-sm">Lernstand</span>
            </a>
        </li> 
    {/if}
    {if checkCapabilities('menu:readCurriculum', $my_role_id, false)}
        <li {if $page_action eq 'curriculum'}class="active"{/if}>
            <a href="index.php?action=curriculum&reset=true">
                <ico class="smallthumbbtn invert"></ico><span class="hidden-sm">Lehrpläne</span>
            </a>
        </li>                    
    {/if}
    {if checkCapabilities('menu:readGroup', $my_role_id, false)}
        <li {if $page_action eq 'group'}class="active"{/if}>
            <a href="index.php?action=group&reset=true">
                <ico class="groupbtn invert"></ico><span class="hidden-sm">Lerngruppen</span>
            </a>
        </li>
    {/if}
    {if checkCapabilities('menu:readUser', $my_role_id, false)}
        <li {if $page_action eq 'user'}class="active"{/if}>
            <a href="index.php?action=user&reset=true">
                <ico class="userbtn invert"></ico><span class="hidden-sm">Benutzer</span>
            </a>
        </li>
    {/if}
    {if checkCapabilities('menu:readRole', $my_role_id, false)}
        <li {if $page_action eq 'role'}class="active"{/if}>
            <a href="index.php?action=role&reset=true">
                <ico class="rolebtn invert"></ico><span class="hidden-sm">Rollenverwaltung</span>
            </a>
        </li>
    {/if}
    {if checkCapabilities('menu:readGrade', $my_role_id, false)}
        <li {if $page_action eq 'grade'}class="active"{/if}>
            <a href="index.php?action=grade">
                <ico class="numberedbtn invert"></ico><span class="hidden-sm">Klassenstufen</span>
            </a>
        </li>
    {/if}
    {if checkCapabilities('menu:readSubject', $my_role_id, false)}
        <li {if $page_action eq 'subject'}class="active"{/if}>
            <a href="index.php?action=subject&reset=true">
                <ico class="tagsbtn invert"></ico><span class="hidden-sm">Fächer</span>
            </a>
        </li>
    {/if}
    {if checkCapabilities('menu:readSemester', $my_role_id, false)}
        <li {if $page_action eq 'semester'}class="active"{/if}>
            <a href="index.php?action=semester&reset=true">
                <ico class="clockbtn invert"></ico><span class="hidden-sm">Lernzeiträume</span>
            </a>
        </li>
    {/if}
    {if checkCapabilities('menu:readBackup', $my_role_id, false)}
        <li {if $page_action eq 'backup'}class="active"{/if}>
            <a href="index.php?action=backup&reset=true">
                <ico class="downbtn invert"></ico><span class="hidden-sm">Backup</span>
            </a>
        </li>
    {/if}
    {if checkCapabilities('menu:readCertificate', $my_role_id, false)}   
        <li {if $page_action eq 'certificate'}class="active"{/if}>
            <a href="index.php?action=certificate&reset=true">
                <ico class="certificatebtn invert"></ico><span class="hidden-sm">Zertifikate</span>
            </a>
        </li>
    {/if}
    {if checkCapabilities('menu:readInstitution', $my_role_id, false)}   
    <li {if $page_action eq 'institution'}class="active"{/if}>
        <a href="index.php?action=institution&reset=true">
            <ico class="institutionbtn invert"></ico><span class="hidden-sm">Institutionen</span>
        </a>
    </li>
    {/if} 
</ul>
{/if}

{if checkCapabilities('menu:readLog', $my_role_id, false)}
<ul class="nav nav-sidebar">
    <li class="text-uppercase"><a href="#"><strong class="hidden-sm">Administration</strong></a></li>
    <li {if $page_action eq 'log'}class="active"{/if}>
        <a href="index.php?action=log">
            <ico class="listaltbtn invert"></ico><span class="hidden-sm">Berichte</span>
        </a>
    </li>
</ul>
{/if}