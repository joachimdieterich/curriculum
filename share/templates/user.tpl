{extends file="base.tpl"}

{block name=title}{$page_title}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}
    
<div class="border-box">
    <div class="contentheader ">{$page_title}<input class="curriculumdocsbtn floatright" type="button" name="help" onclick="curriculumdocs('http://docs.joachimdieterich.de/index.php?title=Benutzerverwaltung');"/></div>
    {if checkCapabilities('user:addUser', $my_role_id, false)}
        <p class="floatleft  cssimgbtn gray-border">
            <a class="addbtn cssbtnmargin cssbtntext" href="index.php?action=profile&function=new">Benutzer hinzufügen</a>
        {if checkCapabilities('menu:readuserImport', $my_role_id, false)}
            <a class="addbtn cssbtnmargin cssbtntext" href="index.php?action=userImport&reset=true">Benutzerliste importieren</a>
        {/if}
    </p>
    {/if}

    {if isset($us_val)}
    <form id='userlist' class="space-top-bottom" method='post' action="index.php?action=user&next={$currentUrlId}">
        {html_paginator id='userP' values=$us_val config=$userP_cfg}    
        <input class="invisible" type="checkbox" name="id[]" value="none" checked /><!--Hack - nothing selected-->
        <div style="clear: both;"></div>
        {if isset($showFunctions)}
        {if $showFunctions}  
            <div class="colII space-top">
            {if checkCapabilities('user:enroleToGroup', $my_role_id, false) OR checkCapabilities('user:expelFromGroup', $my_role_id, false)}
            <p><h3>Lerngruppe</h3></p>
            <p>Markierte Benutzer in Lerngruppe ein bzw. ausschreiben</p>
            {if isset($groups_array)}
                <p><label>Lerngruppe:</label>
                    <select name="groups">
                        {section name=res loop=$groups_array}  
                        <option label="{$groups_array[res]->group}" value={$groups_array[res]->id}> {$groups_array[res]->group} | {$groups_array[res]->semester} | {$groups_array[res]->institution}</option>
                        {/section}
                        </select> 
                </p> 
                <p><label></label>
                {if checkCapabilities('user:enroleToGroup', $my_role_id, false)}
                    <input type='submit' name='enroleGroups' value='einschreiben' />
                {/if}
                {if checkCapabilities('user:expelFromGroup', $my_role_id, false)}
                    <input type='submit' name='expelGroups' value='ausschreiben' />
                {/if} 
                </p>
                {else}p><strong>Sie müssen zuerst eine Lerngruppe anlegen</strong></p>{/if}
            {/if}
            </div>
            <div class="colII space-top">
            {if checkCapabilities('user:updateRole', $my_role_id, false)}
                <p><h3>Institution / Rolle</h3></p>
                <p>Beim Zuweisen einer Rolle werden die markierten Nutzer automatisch in die aktuelle/ausgewählte Institution eingeschrieben bzw. die Daten aktualisiert.</p>
                {if isset($myInstitutions)}
                    <p><label>Institution / Schule: </label>
                        <select name="institution" >
                            {section name=res loop=$myInstitutions}  
                                <option label="{$myInstitutions[res]->institution}" value={$myInstitutions[res]->id} {if $myInstitutions[res]->id eq $my_institution_id} selected="selected"{/if}>{$myInstitutions[res]->institution}</option>
                            {/section}
                    </select> 
                {/if}    
                <p><label>Benutzer-Rolle:</label>
                <SELECT  name='roles' id='roles' />
                {foreach key=rolid item=rol from=$roles}
                    <OPTION  value="{$rol->id}" >{$rol->role}</OPTION>
                {/foreach} 
                </SELECT>
                <p><label></label>
                {if checkCapabilities('user:enroleToInstitution', $my_role_id, false)}
                    <input type='submit' name='enroleInstitution' value='Rolle zuweisen / einschreiben' />
                {/if}
                {if checkCapabilities('user:expelFromInstitution', $my_role_id, false)}
                    <input type='submit' name='expelInstitution' value='ausschreiben' />
                {/if} 
                </p>
            {/if}     
            </div>   
            
            <div class="colII space-top">
            {if checkCapabilities('user:resetPassword', $my_role_id, false)}
            <p><h3>Passwort zurücksetzen</h3></p>
            <p>Neues Passwort für markierte Benutzer festlegen. Passwort muss mind. 6 Zeichen lang sein. </p>
            <p><label>Neues Passwort:</label><input  type='password' name='password' id='password'  {if isset($password)}value='{$password}'{/if}/>
            {validate_msg field='password'}
            <p><label>Passwort anzeigen: </label><input type="checkbox" class="centervertical" name='showpassword'  {if isset($inputshowpassword)}checked{/if} onclick="unmask('password', this.checked);"/></p>
            <p><label>Passwortänderung: </label><input type="checkbox" name='confirmed'  {if isset($inputconfirmed)}checked{/if}/></p>
            <p><label></label><input type='submit' name='resetPassword' value='Passwort zurücksetzen' /></p>
            {/if}
            </div>
            <div class="colII space-top">
            {if checkCapabilities('user:delete', $my_role_id, false)}    
            <p><h3>Benutzer</h3></p>
            <p><label>Markierte Benutzer löschen</label><input type='submit' name='deleteUser' value='löschen' /></p>
            {/if}
            </div>
            <div style="clear: both;"></div>
        </form> 
    {else}<p><input type='submit' name="back" value='Funktionen einblenden'/></p>{/if}{/if}{/if}
    
    {if !isset($groups_array)}<p>Sie können noch keine Benutzer verwalten, da sie entweder nicht über die nötigen Rechte verfügen, oder keine Benutzer in ihrer Institution vorhanden sind.</p><p>&nbsp;</p>{/if}

    {*Groups paginator*}
    {if isset($groupsPaginator)}
        <p><h3>Lerngruppen des Benutzers</h3></p>
        {html_paginator id='groupsPaginator' values=$gp_val config=$groupsPaginator_cfg}    
    {/if}

    {*Curriculum paginator*}
    {if isset($curriculumList)}
        <p><h3>Lehrpläne des Benutzers</h3></p>
        {html_paginator id='curriculumList' values=$cu_val config=$curriculumList_cfg}    
    {/if}

    {*Institution paginator*}
    {if isset($institutionList)}
        <p><h3>Institutionen des Benutzers</h3></p>
        {html_paginator id='institutionList' values=$ins_val config=$institutionList_cfg}    
    {/if}
</div>
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}