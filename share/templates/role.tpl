{extends file="base.tpl"}

{block name=title}Rollen verwalten{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}
    
<div class=" border-radius gray-border">	
    <div class="border-top-radius contentheader ">Rollen verwalten</div>
    <div class="space-top-padding gray-gradient border-bottom-radius box-shadow ">
        
        {if !isset($showRoleForm)}
        <p class="floatleft gray-gradient cssimgbtn border-radius gray-border">
            <a class="addbtn cssbtnmargin cssbtntext" href="index.php?action=role&function=newRole">Rolle hinzufügen</a>
        </p>
        
        {/if}
        <p>&nbsp;</p><p>&nbsp;</p>
        {if isset($showRoleForm)}
        <form id='addRole' method='post' action='index.php?action=role&next={$currentUrlId}'>
        <input type='hidden' name='id' id='id' {if isset($id)}value='{$id}'{/if} />  
        <p><label>Rollennamen</label><input class='inputformlong' type='text' name='role' id='role' {if isset($role)}value='{$role}'{/if} /></p>   
        {validate_msg field='role'}
	<p><label>Beschreibung</label><input class='inputformlong' type='description' name='description' {if isset($description)}value='{$description}'{/if}/></p>
        {validate_msg field='description'}
        <script type='text/javascript'>
	document.getElementById('role').focus();
	</script>
        <p>&nbsp;</p>
        {*capabilities*}
        {assign var="section" value=""}
        {section name=cap loop=$capabilities}
            {assign var="colon" value=$capabilities[cap]->capability|strpos:":"}
            {if $section neq $capabilities[cap]->capability|substr:0:$colon}
                <div class="contentheader">{$capabilities[cap]->capability|substr:0:$colon}</div> 
            {/if}
             <p><label>{$capabilities[cap]->name}</label>
                <input type="radio" name="{$capabilities[cap]->capability}" value="true"{if $capabilities[cap]->permission eq 1}checked{/if}> erlaubt
                <input type="radio" name="{$capabilities[cap]->capability}" value="false"{if $capabilities[cap]->permission eq 0}checked{/if}> nicht erlaubt
            <p style=font-size:80%;">{$capabilities[cap]->capability}</p></br>
            </p>   
            {assign var="section" value=$capabilities[cap]->capability|substr:0:$colon}
        {/section}    
        
        {*End capabilities*}
        
        {if !isset($showeditRoleForm)}
        <p><label></label><input type='submit' name="addRole" value='Rolle hinzufügen' /></p>
        {else}
        <p><label></label><input type='submit' name="back" value='zurück'/><input type='submit' name="updateRole" value='Rolle aktualisieren' /></p>
        {/if}
	</form>	
        {/if}
         
        <form id='classlist' method='post' action='index.php?action=role&next={$currentUrlId}'>
            <p>&nbsp;</p>
    {if $data != null}
        {* display pagination header *}
        <p class="floatright">Datensätze {$rolePaginator.first}-{$rolePaginator.last} bis {$rolePaginator.total}</p>   
            <table id="contenttable">
                    <tr id="contenttablehead">
                    <td></td>    
                    <td>Rolle</td>
                    <td>Beschreibung</td>
                    <td class="td_options">Optionen</td>
            </tr>
            {section name=role loop=$role_list}{* display results *}
                <tr class="contenttablerow" id="row{$role_list[role]->role_id}" onclick="checkrow({$role_list[role]->role_id})">
                    <td><input class="invisible" type="checkbox" id="{$role_list[role]->id}" name="id[]" value={$role_list[role]->role_id} /></td>
                    <td>{$role_list[role]->role}</td>
                    <td>{$role_list[role]->description}</td>
                    <td class="td_options">
                        <a class="deletebtn floatright" type="button" name="delete" onclick="del('role', {$role_list[role]->role_id}, {$my_id})"></a>
                        <a class="editbtn floatright" href="index.php?action=role&edit=true&id={$role_list[role]->role_id}"></a>
                        </td>
                </tr>
            {/section}
            </table>  
            <input class="invisible" type="checkbox" name="id[]" value="none" checked /><!--Hack für problem, dass kein Array gepostet wird, wenn nichts angewählt wird-->
            {* display pagination info *}
            <p class="floatright">{paginate_prev id="rolePaginator"} {paginate_middle id="rolePaginator"} {paginate_next id="rolePaginator"}</p>
            <p>&nbsp;</p>
        {/if}
        </form>              
        
</div>
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}