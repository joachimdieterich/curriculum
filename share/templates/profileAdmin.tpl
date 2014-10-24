{extends file="base.tpl"}

{block name=title}{$page_title}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}
    
<div class="border-box">
    <div class="contentheader ">{$page_title}</div>
    <div>
        
       <!-- <div id="right">
        <img class="border-radius gray-border" src="{$avatar_url}{$avatar}" alt="Profilfoto">
        </div>-->
	
        <form method='post' action='index.php?action=profileAdmin'>	
            <p><label>Benutzername*:</label><input  name='username' id='username' {if isset($username)}value='{$username}'{/if} /></p>
            {validate_msg field='username'}
            <p><label>Vorname*: </label><input  name='firstname'{if isset($firstname)}value='{$firstname}'{/if}/></p>
            {validate_msg field='firstname'}
            <p><label>Nachname*: </label><input  name='lastname'{if isset($lastname)}value='{$lastname}'{/if}/></p>
            {validate_msg field='lastname'}
            <p><label>Email*: </label><input  name='email'{if isset($email)}value='{$email}'{/if}/></p>
            {validate_msg field='email'}
            <p><label>PLZ: </label><input  name='postalcode'{if isset($postalcode)}value='{$postalcode}'{/if}/></p>
            {validate_msg field='postalcode'}
            <p><label>Ort: </label><input  name='city' {if isset($city)}value='{$city}'{/if}/></p>
            {validate_msg field='city'}
            <p><label>Bundesland: </label><input  name='state'  {if isset($state)}value='{$state}'{/if}/></p>
            {validate_msg field='state'}
            <p><label>Land: </label><select name="country"  onchange="loadStates(this.value, 'state', {$state_id});">
                    {section name=res loop=$countries}  
                        <OPTION label="{$countries[res]->de}" value={$countries[res]->id} {if $countries[res]->id eq $country_id}selected{/if}>{$countries[res]->de}</OPTION>
                    {/section}
                </select></p>      
                {validate_msg field='country'}
            <p id="states"><label>Bundesland: </label><select name="state" >
                    {section name=s_id loop=$states}
                        <OPTION label="{$states[s_id]->state}" value="{$states[s_id]->id}" {if $states[s_id]->id eq $state_id}selected{/if}>{$states[s_id]->state}</OPTION>
                    {/section}   
                </select></p>
            {validate_msg field='state'}
            <p><label>Passwort*: </label><input  type='password' id='password' name='password' {if isset($password)}value='{$password}'{/if}/></p>
            {validate_msg field='password'}
            <p><label>Passwort anzeigen: </label><input type="checkbox" class="centervertical" name='showpassword'  {if isset($showpassword)}checked{/if} onclick="unmask('password', this.checked);"/></p>
            <p><label>Passwortänderung: </label><input type="checkbox" class="centervertical" name='confirmed'  {if isset($confirmed)}checked{/if}/></p>
            {if checkCapabilities('file:upload', $my_role_id, false)}
                <p><label>Avatar: </label><input  id="myfile" name='avatar_id' value={$newUserAvatar_id} readonly  onclick="tb_show('','assets/scripts/libs/modal-upload/uploadframe.php?userID={$my_id}&token={$my_token}&last_login={$my_last_login}&context=avatar&target=myfile&format=1&multiple=false&placeValuesBeforeTB_=savedValues&TB_iframe=true&width=710&modal=true')" href="#" class="thickbox"/>
                {validate_msg field='avatar_id'}
            {/if}
            {if isset($myInstitutions)}
                <p><label>Institution / Schule*: </label>
                    <select name="institution" >
                    {section name=res loop=$myInstitutions}  
                        <option label="{$myInstitutions[res]->institution}" value={$myInstitutions[res]->id}>{$myInstitutions[res]->institution}</option>
                    {/section}
                </select> 
            {else}
                <p><strong>Sie müssen zuerst eine Institution anlegen</strong></p>           
            {/if}
            <input class="invisible" name='role_id' value='{$standardrole}' readonly="readonly"/>
            <p><label>&nbsp;</label><input type='submit' name='addUser' value='Benutzer anlegen' /></p>
        </form>
            {if isset($results)}
            <p><h3>Neue Benutzer</h3></p>
            <p >Neue Benutzerkonten (seit Login).</p>
            {* display pagination header *}
            <p>Datensätze {$usersPaginator.first}-{$usersPaginator.last} von {$usersPaginator.total} werden angezeigt.</p>
            {* display results *}  
            <table id="contenttable">
		<tr id="contenttablehead">
			<td></td><td>Avatar</td>
                        <td>Benutzername</td>
                        <td>Vorname</td>
                        <td>Nachname</td>
                        <td>Email</td>
                        <td>PLZ</td>
                        <td>Ort</td>
                        <td>Bundesland</td>
                        <td>Land</td>
                        <td>Gruppe</td>
		</tr>
            {section name=res loop=$results}
                    <tr class="contenttablerow" id="row{$results[res]->id}" onclick="checkrow({$results[res]->id})">
                       <td><input class="invisible" type="checkbox" id="{$results[res]->id}" name="id[]" value={$results[res]->id} /></td>
                       <td><img src="{$avatar_url}{$results[res]->avatar}" alt="Profilfoto" width="18"></td>
                       <td>{$results[res]->username}</td>
                       <td>{$results[res]->firstname}</td>
                       <td>{$results[res]->lastname}</td>
                       <td>{$results[res]->email}</td>
                       <td>{$results[res]->postalcode}</td>
                       <td>{$results[res]->city}</td>
                       <td>{$results[res]->state}</td>
                       <td>{$results[res]->country}</td>
                       <td>{$results[res]->role_name}</td>
                    </tr>
                {/section}
            </table>
            {* display pagination info *}
            <p>{paginate_prev id="usersPaginator"} {paginate_middle id="usersPaginator"} {paginate_next id="usersPaginator"}</p>    
            {/if}
    </div>
</div>
            <script type='text/javascript'>
	document.getElementById('username').focus();
	</script>
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}
