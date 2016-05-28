{extends file="base.tpl"}

{block name=title}{$page_title}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}
<!-- Content Header (Page header) -->
{content_header p_title=$page_title pages=$breadcrumb help='http://docs.joachimdieterich.de/index.php?title=Mein_Profil'}      
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-body"></div>

        
    {if isset($p_avatar)}
        {if checkCapabilities('file:uploadAvatar', $my_role_id, false)}
        <div id="right"><img class="border-radius" src="{$access_file}{$p_avatar}" alt="Profilfoto" onclick="tb_show('','../share/request/uploadframe.php?userID={$my_id}&last_login={$my_last_login}&context=avatar&target=p_avatar_id&format=0&multiple=false&modal=true&TB_iframe=true&width=710')" href="#" class="thickbox"></div>
        {/if}
    {/if}
        <form method='post' action='index.php?action=profile'>
        <p class="hidden"><label></label><input name='p_id' {if isset($p_id)}value={$p_id}{/if}  readonly="readonly" /></p>
        <input class="hidden" id="p_avatar_id" name='p_avatar_id' {if isset($p_avatar_id)}value='{$p_avatar_id}'{/if}/>
        {validate_msg field='avatar_id'}
        <p><label>Benutzername:</label><input name='p_username' id='username'{if isset($p_username)}value={$p_username}{/if} {if $readonly eq true} readonly="readonly"{/if}/></p>
        {validate_msg field='p_username'}
        <p><label>Vorname*: </label><input  name='p_firstname' id='firstname'{if isset($p_firstname)}value='{$p_firstname}'{/if}/></p>
        {validate_msg field='p_firstname'}
        <p><label>Nachname*: </label><input  name='p_lastname'{if isset($p_lastname)}value='{$p_lastname}'{/if}/></p>
        {validate_msg field='p_lastname'}
        <p><label>Email*: </label><input  name='p_email'{if isset($p_email)}value='{$p_email}'{/if}/></p>
        {validate_msg field='p_email'}
        <p><label>PLZ: </label><input  name='p_postalcode'{if isset($p_postalcode)}value='{$p_postalcode}'{/if}/></p>
        {validate_msg field='p_postalcode'}
        <p><label>Ort: </label><input  name='p_city' {if isset($p_city)}value='{$p_city}'{/if}/></p>
        {validate_msg field='p_city'}
        <p><label>Land: </label><select name="p_country_id" id="country_id" onchange="getStates(this.value, 'p_state_id');">
            {section name=res loop=$countries}  
                <option label={$countries[res]->de} value={$countries[res]->id} {if isset($p_country_id) AND $countries[res]->id eq $p_country_id}selected="selected"{/if}>{$countries[res]->de} </option>
            {/section}
            </select>
        </p>
        <p id="states">
            {if isset($p_state_id)}
                <label>Bundesland / Region: </label><SELECT name="p_state_id" onchange="getStates(this.value);"/>
                {section name=s_id  loop=$state}
                    <OPTION label={$state[s_id]->state} value={$state[s_id]->id} {if $state[s_id]->id eq $p_state_id}selected="selected"{/if}>{$state[s_id]->state}</OPTION>
                {/section} 
                </SELECT>
            {else}
                <script type='text/javascript'>getStates(document.getElementById('country').value);</script>
            {/if}
        </p>
        {if $readonly eq false} 
            <p><label>Passwort*: </label><input  type='password' id='password' name='p_password' {if isset($p_password)}value='{$p_password}'{/if}/></p>
            {validate_msg field='password'}
            <p><label>Passwort anzeigen: </label><input type="checkbox" class="centervertical" name='p_showpassword'  {if isset($showpassword)}checked{/if} onclick="unmask('password', this.checked);"/></p>
            <p><label>Passwortänderung: </label><input type="checkbox" class="centervertical" name='p_confirmed'  {if isset($p_confirmed)}checked{/if}/></p>
        {/if}

        {if $p_id eq false}{*Auswahl nur beim hinzufügen von Nutzern anzeigen*}
         <p><label>Institution / Schule*:</label><SELECT  name='p_institution_id' id='p_institution_id' onchange="getGroups(this.value, 'p_group_id');"/>
        {foreach key=insid item=ins from=$my_institutions}
            <OPTION  value="{$ins->institution_id}"  {*if $ins->id eq $p_ins_id}selected="selected"{/if*}>{$ins->institution}</OPTION>
        {/foreach} 
        </SELECT></p>
        <p id="groups">
            {if isset($p_group_id)}
                <label>Lerngruppe: </label><SELECT name="p_group_id" />
                {section name=g_id  loop=$group}
                    <OPTION label={$group[g_id]->group} value={$group[g_id]->id} {if $group[g_id]->id eq $p_group_id}selected="selected"{/if}>{$group[g_id]->group}</OPTION>
                {/section} 
                </SELECT>
            {else}
                <script type='text/javascript'>getGroups(document.getElementById('institution').value);</script>
            {/if}
        </p>
        {/if}
        {if $readonly eq false} 
        <p><label>Rolle</label><SELECT  name='p_role_id' id='p_role_id' />
            {foreach key=rolid item=rol from=$roles}
            <OPTION  value="{$rol->id}"  {if $rol->id eq $p_role_id}selected="selected"{/if}>{$rol->role}</OPTION>
            {/foreach} 
        </SELECT></p>
        {/if}
        {if $readonly eq false}
            <p><label></label><input type='submit' name='add' value='Benutzer anlegen' /></p>
        {else}
            <p><label></label><input type='submit' name='edit' value='Benutzer aktualisieren' /></p>
        {/if}
        <script type='text/javascript'>document.getElementById({if $readonly eq false}'username'{else}'firstname'{/if}).focus();</script>            
    </form>
    {if isset($nusr_val)}
        {html_paginator id='newUsersPaginator'}     
    {/if}
                </div>
            </div>
        </div>
    </div>
</section>
        
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}
