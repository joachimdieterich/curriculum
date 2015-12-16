{extends file="base.tpl"}

{block name=title}{$page_title}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content} 

<div class="border-box">
    <div class="contentheader ">{$page_title}<input class="curriculumdocsbtn floatright" type="button" name="help" onclick="curriculumdocs('http://docs.joachimdieterich.de/index.php?title=Lernstand');"/></div>
        {if isset($user->avatar)}
            <div id="right">
                <img class="gray-border" src="{$access_file}{$user->avatar}" alt="Profilfoto">
            </div>
        {/if}    
                        
        {if isset($courses)}<p>
            <select  class='floatleft' id='course' name='course' onchange="window.location.assign('index.php?action=objectives&course='+this.value);"> {*_blank global regeln*}
                <option value="-1" data-skip="1">Lehrplan wählen...</option>
                {section name=res loop=$courses}
                    {if $courses[res]->semester_id eq $my_semester_id}
                      <option value="{$courses[res]->id}" 
                      {if $courses[res]->id eq $selected_curriculum} selected {/if} 
                      data-icon="{$subjects_path}/{$courses[res]->icon}" data-html-text="{$courses[res]->group} - {$courses[res]->curriculum}&lt;i&gt;
                      {$courses[res]->description}&lt;/i&gt;">{$courses[res]->group} - {$courses[res]->curriculum}</option>  
                    {/if}
                {/section} 
            </select> 
            {if $show_course != '' and $terminalObjectives != false or !isset($selected_user_id)}{*Zertifikat*}
            <form method='post' action='index.php?action=objectives&course={$selected_curriculum}&userID={implode(',',$selected_user_id)}&next={$currentUrlId}'>
            <select class='floatleft space-left' id='certificate_template' name='certificate_template' onchange=""> 
                <option value="-1" data-skip="1">Zertifikatvorlage wählen...</option>
                {section name=res loop=$certificate_templates}
                    <option value="{$certificate_templates[res]->id}" 
                        {if $certificate_templates[res]->id eq $selected_certificate_template} selected {/if}>
                        {$certificate_templates[res]->certificate} - {$certificate_templates[res]->description}
                    </option>  
                {/section} 
            </select>    
                <input type='hidden' name='sel_curriculum' value='{$sel_curriculum}'/>
                <input type='hidden' name='sel_user_id' value='{implode(',',$selected_user_id)}'/>
                <input type='hidden' name='sel_group_id' value='{$sel_group_id}'/>
                <input class='menusubmit space-left' type='submit' name="printCertificate" value={if count($selected_user_id) > 1}'Zertifikate erstellen'{else} 'Zertifikat erstellen'{/if} /> 
            </form></p>
            {else}
                <select class='hidden floatleft space-left' id='certificate_template' name='certificate_template' onchange=""> {*hack, damit bei checkrow die Auswahl erhalten bleibt bzw. keine Fehler entstehen*}
                    <option value="-1" data-skip="1">Zertifikatvorlage wählen...</option>
                </select> </p><br>
            {/if}{*To show "Datensätze x von y..." properly*}
        {else}<strong>Sie haben noch keine Lehrpläne angelegt bzw. noch keine Klassen eingeschrieben.</strong></p>
        {/if}
         
        {if isset($userPaginator)}   
        <p>Datensätze {$userPaginator.first}-{$userPaginator.last} von {$userPaginator.total} werden angezeigt.</p>
    	<table id="contentsmalltable">
            <tr id="contenttablehead">
                <td></td>
                <td>Benutzername</td>
                <td>{paginate_order id="userPaginator" key="firstname" text="Vorname"}</td>
                <td>{paginate_order id="userPaginator" key="lastname" text="Nachname"}</td>
                <td>erledigt</td>
                <td>Rolle</td>
                <td>Optionen</td>
            </tr>    
            {section name=res loop=$results}
                <tr class="{if isset($selected_user_id) && in_array($results[res]->id, $selected_user_id) OR $selected_user_id eq 'all'} activecontenttablerow {else}contenttablerow{/if}{if $results[res]->completed eq 100} completed{/if}" id="row{$smarty.section.res.index}">
                   <td onclick="checkrow('{$smarty.section.res.index}', 'userID[]', 'index.php?action=objectives&course='+document.getElementById('course').value+'&certificate_template='+document.getElementById('certificate_template').value);"><input class="checkbox" type="checkbox" id="{$smarty.section.res.index}" name="userID[]" value="{$results[res]->id}" {if isset($selected_user_id) && in_array($results[res]->id, $selected_user_id)} checked{/if} /></td>
                   <!--<td>{$results[res]->username}</td>-->
                   <td onclick="window.location.assign('index.php?action=objectives&course='+document.getElementById('course').value+'&userID='+document.getElementById('{$smarty.section.res.index}').value+'&certificate_template='+document.getElementById('certificate_template').value);">{$results[res]->username} </td>
                   <td onclick="window.location.assign('index.php?action=objectives&course='+document.getElementById('course').value+'&userID='+document.getElementById('{$smarty.section.res.index}').value+'&certificate_template='+document.getElementById('certificate_template').value);">{$results[res]->firstname} </td>
                   <td onclick="window.location.assign('index.php?action=objectives&course='+document.getElementById('course').value+'&userID='+document.getElementById('{$smarty.section.res.index}').value+'&certificate_template='+document.getElementById('certificate_template').value);">{$results[res]->lastname} </td>
                   <td onclick="window.location.assign('index.php?action=objectives&course='+document.getElementById('course').value+'&userID='+document.getElementById('{$smarty.section.res.index}').value+'&certificate_template='+document.getElementById('certificate_template').value);">{$results[res]->completed} </td>
                   <td onclick="window.location.assign('index.php?action=objectives&course='+document.getElementById('course').value+'&userID='+document.getElementById('{$smarty.section.res.index}').value+'&certificate_template='+document.getElementById('certificate_template').value);">{$results[res]->role_name} </td>
                   <td onclick="window.location.assign('index.php?action=objectives&course='+document.getElementById('course').value+'&userID='+document.getElementById('{$smarty.section.res.index}').value);">
                    {if checkCapabilities('mail:postMail', $my_role_id, false)}
                        <a class="mailnewbtn floatright" type="button" name="newMail" href="index.php?action=messages&function=shownewMessage&subject=-&receiver_id={$results[res]->id}&answer=true"></a>
                    {else}
                        <a class="mailnewbtn deactivatebtn floatright" type="button"></a>
                    {/if}

                    </td>
                </tr>
            {/section}
	</table>
        <p><input class="inputsmall" type="checkbox" id="allUser" {if isset($selected_user_id) AND $selected_user_id eq 'all'} value="none"{else} value="all"{/if} name="allUser" {if isset($selected_user_id) AND $selected_user_id eq 'all'} checked{/if} onclick="window.location.assign('index.php?action=objectives&course='+document.getElementById('course').value+'&userID={$userlist}');"/>Alle auswählen{paginate_prev id="userPaginator"} {paginate_middle id="userPaginator"} {paginate_next id="userPaginator"}</p>       
        <input class="invisible" type="checkbox" name="userID" value="none" checked /><!--Hack für Problem, dass kein Array gepostet wird, wenn nichts angewählt wird-->
        
        {elseif $showuser eq true} <p>Keine eingeschriebenen Benutzer</p>{else}<p class="space-top-bottom"></p>{/if}
        {if $show_course != '' and $terminalObjectives != false or !isset($selected_user_id)} 
            <div id="printContent" class="scroll space-bottom">
            <table> 
                {foreach key=terid item=ter from=$terminalObjectives}
                    <tr>
                        <td class="boxleftpadding">
                            <div class="box gray-border gray-gradient">
                                <div class="boxheader"></div>
                                <div class="boxwrap">
                                    <div class="boxscroll">
                                        <div class="boxcontent">
                                            {$ter->terminal_objective}<!--{$ter->description}-->
                                        </div>
                                    </div>
                                </div>
                                <div class="boxfooter"><!--Options...--></div> 
                            </div>
                        </td>
                        {foreach key=enaid item=ena from=$enabledObjectives}
                        {if $ena->terminal_objective_id eq $ter->id}
                        <td id="{$ter->id}&{$ena->id}">
                            <div style="display:none" id="{$ter->id}_{$ena->id}">{0+$ena->accomplished_status_id}</div><!--Container für Variable-->
                            <div id="{$ter->id}style{$ena->id}" class="box gray-border {if $ena->accomplished_status_id eq 1} boxgreen {elseif $ena->accomplished_status_id eq 2} boxorange {elseif $ena->accomplished_status_id eq '0'} boxred {else} box {/if}">
                                <div class="boxheader">
                                    {if isset($ena->accomplished_users) and isset($ena->enroled_users) and isset($ena->accomplished_percent)}
                                        {$ena->accomplished_users} von {$ena->enroled_users} ({$ena->accomplished_percent}%)<!--Ziel--> 
                                    {/if}
                                </div>
                                <div class="boxwrap">
                                    <div class="boxscroll" onclick="setAccomplishedObjectives({$my_id}, '{implode(',',$selected_user_id)}', {$userPaginator.first}, {if isset($paginatorLimit)}{$paginatorLimit}{else}10{/if}, {$ter->id}, {$ena->id}, {$sel_group_id});">
                                        <div class="boxcontent" >
                                             {$ena->enabling_objective}
                                        </div>
                                    </div>
                                </div>

                                <div class="boxfooter" onclick="">
                                   {if $addedSolutions != false} 
                                        {assign var="firstrun" value="true"} 
                                        {foreach key=solID item=sol from=$addedSolutions}
                                            {if $sol->enabling_objective_id eq $ena->id}
                                                {if $firstrun eq "true"}
                                                    {if $page_browser != 'Safari'} {*For cross browser compatibility*}
                                                        <select class="selSolution" name="select_{$ena->id}" onchange="openLink(this.options[this.selectedIndex].value, '_blank');"> 
                                                    {else}
                                                        <select class="selSolution" name="select_{$ena->id}" onclick="openLink(this.options[this.selectedIndex].value, '_blank');"> 
                                                    {/if}
                                                        <option value="">Abgaben...</option>
                                                    {assign var="firstrun" value="false"}
                                                {/if}
                                                <option value="{$solutions_path}{$sol->path}{$sol->filename}">({$sol->lastname}, {$sol->firstname}) {$sol->filename}{$sol->type}</a></option>
                                            {/if}
                                        {/foreach}
                                        </select>
                                    {/if} <!--Options...-->
                                </div>  
                            </div>
                        </td>
                        {/if}
                        {/foreach} 
                   </tr>
                {/foreach}		
            </table>
            </div>   
        {else}
            {if isset($selected_user_id) and $show_course != ''}
                <p>Es wurden noch keine Lernziele eingegeben.</p>
                <p>Dies können sie unter Lehrpläne --> Lernziele/Kompetenzen hinzufügen machen.</p>
            {else} 
                {if isset($curriculum_id)}<!--Wenn noch keine Lehrpläne angelegt wurden-->
                <p>Bitte wählen sie einen Benutzer aus.</p>
                {/if}            
            {/if}
        {/if} 
</div>
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}