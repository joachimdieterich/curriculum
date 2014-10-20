{extends file="base.tpl"}

{block name=title}{$page_title}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}
    
<div class="border-box">
    <div class="contentheader">{$page_title}</div>
   
    <p>In diesem Editor können sie das Zertifikat einrichten. Die folgenden Felder dürfen / müssen dabei verwendet werden. 
        Felder mit * sind obligatorisch. </br>Die {literal}{{/literal}Start{literal}}{/literal} und {literal}{{/literal}Ende{literal}}{/literal} Feld legen fest welcher Bereich abhängig von den vorhandenen Zielen immer wieder wiederholt wird. </p>    
    <p><ol>
        <li>*{literal}{{/literal}Vorname{literal}}{/literal}</li>
        <li>*{literal}{{/literal}Nachname{literal}}{/literal}</li>
        <li>*{literal}{{/literal}Start{literal}}{/literal}</li>
        <li>*{literal}{{/literal}Ende{literal}}{/literal}</li>
        <li>{literal}{{/literal}Ort{literal}}{/literal}</li>
        <li>{literal}{{/literal}Datum{literal}}{/literal}</li>
        <li>{literal}{{/literal}Unterschrift{literal}}{/literal}</li>
        <li>{literal}{{/literal}Thema{literal}}{/literal}</li>
        <li>{literal}{{/literal}Ziel{literal}}{/literal}</li>
        <li>{literal}{{/literal}Ziel_erreicht{literal}}{/literal}</li>
        <li>{literal}{{/literal}Ziel_offen{literal}}{/literal}</li>
        <li>{literal}{{/literal}Unterschrift{literal}}{/literal}</li>  
    </ol></p>
    {if isset($courses)}
    <p>
        <select id='course' name='course' onchange="window.location.assign('index.php?action=certificate&course='+this.value);"> {*_blank global regeln*}
            <option value="-1" data-skip="1">Lehrplan wählen...</option>
            {section name=res loop=$courses}
            <option value="{$courses[res]->id}" 
            {if $courses[res]->id eq $selected_curriculum} selected {/if} 
            data-icon="{$data_url}subjects/{$courses[res]->icon}" data-html-text="{$courses[res]->group} - {$courses[res]->curriculum}&lt;i&gt;
            {$courses[res]->description}&lt;/i&gt;">{$courses[res]->group} - {$courses[res]->curriculum}</option>  
            {/section} 
        </select>    
    </p>
    {else}<p><strong>Sie haben noch keine Lehrpläne angelegt bzw. noch keine Klassen eingeschrieben.</strong></p>{/if}
    <p>&nbsp;</p>
         
    {if isset($userPaginator)}   
    <p>Datensätze {$userPaginator.first}-{$userPaginator.last} von {$userPaginator.total} werden angezeigt.</p>
        <table id="contentsmalltable">
            <tr id="contenttablehead">
                    <td></td><td>Vorname</td><td>Nachname</td>
            </tr>   
        {section name=res loop=$results}
            <tr class="{if isset($selected_user_id) AND $selected_user_id eq $results[res]->id} activecontenttablerow {else}contenttablerow{/if}{if $results[res]->completed eq 100} completed{/if}" id="row{$smarty.section.res.index}" onclick="window.location.assign('index.php?action=certificate&course='+document.getElementById('course').value+'&userID='+document.getElementById('userID{$smarty.section.res.index}').value);">
                <td><input class="invisible" type="checkbox" id="userID{$smarty.section.res.index}" name="userID" value={$results[res]->id} {if isset($selected_user_id) AND $selected_user_id eq $results[res]->id} checked{/if}/></td>
                <td>{$results[res]->firstname}</td><td>{$results[res]->lastname}</td>
            </tr>
        {/section}
        </table>
    <p>{paginate_prev id="userPaginator"} {paginate_middle id="userPaginator"} {paginate_next id="userPaginator"}</p>

    <input class="invisible" type="checkbox" name="userID" value="none" checked /><!--Hack if no Array -->
    {elseif $showuser eq true} <p>Keine eingeschriebenen Benutzer</p><p>&nbsp;</p>{/if}
    
    <form id='certificate' method='post' action='index.php?action=certificate'> 
        <input type='hidden' name='sel_curriculum' value='{$sel_curriculum}'/>
            <input type='hidden' name='sel_user_id' value='{$selected_user_id}'/>
            <input type='hidden' name='sel_group_id' value='{$sel_group_id}'/>
    <p><textarea name="certificate_html">{if isset($certificate_html)}{$certificate_html}{/if}</textarea></p>  
    <p>&nbsp;</p>
    <p><label></label><input type='submit' name='generateCertificate' value='Zertifikat erstellen' /></p>
    </form>

{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}