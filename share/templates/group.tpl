{extends file="base.tpl"}

{block name=title}{$page_title}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}
<div class="border-box">
    <div class="contentheader ">{$page_title}<input class="curriculumdocsbtn floatright" type="button" name="help" onclick="curriculumdocs('http://docs.joachimdieterich.de/index.php?title=Lerngruppen_anlegen');"/></div>
        {if !isset($showForm) && checkCapabilities('groups:add', $my_role_id, false)}    
            <p class="floatleft  cssimgbtn gray-border">
                <a class="addbtn cssbtnmargin cssbtntext" href="index.php?action=group&function=new">Lerngruppe hinzufügen</a>
            </p>
        {else}
            <form id='GroupForm' method='post' action='index.php?action=group&next={$currentUrlId}'>
            {if isset($edit_group_form) OR isset($new_semester_form)}
                <input id="g_id" name="g_id" type="text" class="hidden" value={$g_id}>
            {/if} 
            <p><label>Lerngruppen-Name: </label>    <input id='g_group' name='g_group' class='inputlarge' {if isset($g_group)}value='{$g_group}'{/if} /></p>   
            {validate_msg field='group'}
            <p><label>Beschreibung: </label>        <input name='g_description' class='inputlarge'{if isset($g_description)}value='{$g_description}'{/if}/></p>
            {validate_msg field='description'}
            <p><label>Klassenstufe:</label>
            <select name="grade" class='inputlarge'>
                {section name=res loop=$grade}  
                    <option label="{$grade[res]->grade}" value={$grade[res]->id} {if $g_grade_id eq $grade[res]->id}selected="selected"{/if}>{$grade[res]->grade}</option>
                {/section}
            </select> 
            {validate_msg field='grade'}
            {if $semester}
                <p><label>Lernzeitraum: </label>
                <select name="semester" class='inputlarge'>
                    {section name=res loop=$semester}  
                        <option label="{$semester[res]->semester}" value={$semester[res]->id} {if $g_semester_id eq $semester[res]->id}selected="selected"{/if}>{$semester[res]->semester}</option>
                    {/section}
                </select> 
                {validate_msg field='semester'}  
            {else}<p><strong>Keine Lernzeiträume vorhanden! Um Lerngruppen anzulegen müssen zuerst Lernzeiträume angelegt werden.</strong></p>{/if}
            {if isset($myInstitutions)}
                <p><label>Institution / Schule*: </label>
                    <select name="institution" class='inputlarge'>
                    {section name=res loop=$myInstitutions}  
                        <option label="{$myInstitutions[res]->institution}" value={$myInstitutions[res]->id} {if $g_institution_id eq $myInstitutions[res]->id}selected="selected"{/if}>{$myInstitutions[res]->institution}</option>
                    {/section}
                </select> 
            {else}<p><strong>Sie müssen zuerst eine Institution anlegen</strong></p>{/if}
            {if isset($new_semester_form)}
                <p><label>Personen übernehmen?: </label>
                    <input id="assumeUsers" name="assumeUsers" type="checkbox" checked="checked" /> Um eine leere Lerngruppe zu erstellen, Haken entfernen.
                </p>  
            {/if}
            <p><label></label><input name="back" type='submit' value='zurück'/>
            {if isset($edit_group_form) AND !isset($new_semester_form)}
                <input name="update" type='submit' value='Lerngruppe aktualisieren' /></p>
            {elseif isset($new_semester_form)}
                <input name="change" type='submit' value='Lernzeitraum ändern' /></p>
            {else}
                <input name="add" type='submit' value='Lerngruppe hinzufügen' /></p>
            {/if}
             <script type='text/javascript'> document.getElementById('g_group').focus(); </script>
            </form>	
        {/if}
        <form id='classlist' method='post' action='index.php?action=group&next={$currentUrlId}'>
        {html_paginator id='groupP' values=$gp_val config=$groupP_cfg}
        
            {if !isset($showForm)}
                {if checkCapabilities('groups:enrol', $my_role_id, false) OR checkCapabilities('groups:expel', $my_role_id, false)}
                    {if isset($curriculum_list)}
                    <p><h3>Markierte Lerngruppe(n)in Lehrplan ein- und ausschreiben:</h3>
                    <p class="floatleft">
                        <select class='floatleft' name="curriculum">
                            {section name=res loop=$curriculum_list}  
                                <option label="{$curriculum_list[res]->curriculum} | {$curriculum_list[res]->grade} | {$curriculum_list[res]->description}" value="{$curriculum_list[res]->id}">{$curriculum_list[res]->curriculum} | {$curriculum_list[res]->grade} | {$curriculum_list[res]->description}</option>
                            {/section}
                        </select> 
                        
                            <div class=" floatleft cssimgbtn gray-border">
                        {if checkCapabilities('groups:enrol', $my_role_id, false)}
                            <a class="inbtn block cssbtnmargin cssbtntext " onclick="document.getElementById('enrole').click();">einschreiben</a>
                        {else}
                            <a class="inbtn block deactivatebtn cssbtnmargin cssbtntext " >einschreiben</a>
                        {/if}
                            </div><input class="invisible" type='submit' id='enrole' name='enrole' value='einschreiben' />
                        
                        <div class="floatleft  cssimgbtn gray-border">
                        {if checkCapabilities('groups:expel', $my_role_id, false)}
                            <a class="outbtn block cssbtnmargin cssbtntext" onclick="document.getElementById('expel').click();">ausschreiben</a>
                        {else}
                            <a class="outbtn block deactivatebtn cssbtnmargin cssbtntext " >ausschreiben</a>
                        {/if}    
                        </div><input class="invisible" type='submit' id='expel' name='expel' value='ausschreiben' />
                    </p>
                    {/if}
                 {/if}   
            {/if}
        </form> 
        {if isset($cu_val)}
            {html_paginator id='curriculumP' values=$cu_val config=$curriculumP_cfg action="index.php?action=groups&next={$currentUrlId}"}
        {/if}
  
</div>
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}