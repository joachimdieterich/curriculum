{extends file="base.tpl"}

{block name=title}{$teacherConfirm}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}
    
<div class=" border-radius gray-border">	
    <div class="border-top-radius contentheader ">{$teacherConfirm}</div>
    <div class="space-top-padding gray-gradient border-bottom-radius box-shadow ">
        {if isset($results)}
        <p><h3>Benutzer</h3></p>
        <p >Hier können neu registrierte Benutzer freigegeben werden</p>
        <p><h3>&nbsp;</h3></p>
        {* display pagination header *}
        <p>Datensätze {$usersPaginator.first}-{$usersPaginator.last} von {$usersPaginator.total} werden angezeigt.</p>
    
        <form id='userlist' method='post' action='index.php?action=teacherConfirm&next={$currentUrlId}'>
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
                {* display results *}    
                {section name=res loop=$results}
                    <tr class="contenttablerow" id="row{$results[res]->id}" onclick="checkrow({$results[res]->id})">
                       <td><input class="invisible" type="checkbox" id="{$results[res]->id}" name="id_user[]" value={$results[res]->id} /></td>
                       <td><img src="{$avatar_url}{$results[res]->avatar}" alt="Profilfoto" width="18"></td>
                       <td>{$results[res]->username}</td>
                       <td>{$results[res]->firstname}</td>
                       <td>{$results[res]->lastname}</td>
                       <td>{$results[res]->email}</td>
                       <td>{$results[res]->postalcode}</td>
                       <td>{$results[res]->city}</td>
                       <td>{$results[res]->state}</td>
                       <td>{$results[res]->country}</td>
                       <td>{$results[res]->role_id}</td>
                    </tr>
                {/section}
		</table>
        {* display pagination info *}
        <p>{paginate_first id="usersPaginator"} {paginate_middle id="usersPaginator"} {paginate_next id="usersPaginator"}</p>
        
        <input class="invisible" type="checkbox" name="idUser[]" value="none" checked /><!--Hack - nothing selected-->
        
        <p><h3>Benutzer</h3></p>
        <p><label>Markierte Benutzer</label><input type='submit' name='confirmUser' value='Benutzer freigeben' />
            <input type='submit' name='deleteUser' value='löschen' /></p>
        {else}
        <p>Es liegen keine unbestätigten Registrierungen vor. </p>
        {/if}
        </form> 
        <p>&nbsp;</p>
</div>
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}
