{extends file="base.tpl"}

{block name=title}{$page_title}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}

<div class="border-radius gray-border">	
    <div class="border-top-radius contentheader ">{$page_title}</div>
    <div class="space-top-padding gray-gradient box-shadow ">
        
        <div id="right">
        <img class="border-radius gray-border" src="{$avatar_url}{$avatar}" alt="Profilfoto">
        </div>
	
        <form method='post' action='index.php?action=profile'>
            <p class="invisible"><label>ID: </label><input name='userID' value={$id} readonly="readonly" /></p>
            <p><label>Benutzername:</label><input name='username' value={$username} readonly="readonly"/></p>
            {validate_msg field='username'}
            
            <p><label>Vorname: </label><input name='firstname' id='firstname' value={$firstname} /></p>
            {validate_msg field='firstname'}
            
            <p><label>Nachname: </label><input name='lastname' value={$lastname} /></p>
            {validate_msg field='lastname'}
            
            <p><label>Email: </label><input name='email' value={$email} /></p>
            {validate_msg field='email'}

            <p><label>PLZ: </label><input  name='postalcode' value={$postalcode} /></p>
            <p><label>Ort: </label><input  name='city' value={$city} /></p>
            <p><label>Land: </label><select  name="country" onchange="loadStates(this.value, 'state', {$state_id});">
                    {section name=res loop=$countries}  
                        <OPTION label="{$countries[res]->de}" value={$countries[res]->id} {if $countries[res]->id eq $country_id}selected{/if}>{$countries[res]->de}</OPTION>
                    {/section}
                </select></p>            
            <p id="states"><label>Bundesland: </label><select  name="state">
                    {section name=s_id loop=$states}
                        <OPTION label="{$states[s_id]->state}" value="{$states[s_id]->id}" {if $states[s_id]->id eq $state_id}selected{/if}>{$states[s_id]->state}</OPTION>
                    {/section}   
                </select></p>
            <p><label>Avatar: </label><input  id="myfile" name='avatar' value={$avatar} readonly onclick="tb_show('','assets/scripts/libs/modal-upload/uploadframe.php?userID={$my_id}&token={$my_token}&last_login={$my_last_login}&context=avatar&target=myfile&format=1&multiple=false&placeValuesBeforeTB_=savedValues&TB_iframe=true&width=710&modal=true')" href="#" class="thickbox"/>
            <p><label>Rolle/Gruppe: </label><input class="hidden" name='role_id' value={$role_id} readonly="readonly"/><input  name='role_name' value={$role_name} readonly="readonly"/></p>
            <p><label>&nbsp;</label><input type='submit' value='Änderungen speichern' /></p>
        </form>
    
    </div>
</div>
        <script type='text/javascript'>
	document.getElementById('firstname').focus();
	</script>
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}
