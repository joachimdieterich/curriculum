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
        
        {if checkCapabilities('institution:add', $my_role_id, false)}
            {if !isset($showInstitutionForm)}
            <p class="floatleft  cssimgbtn gray-border">
                <a class="addbtn cssbtnmargin cssbtntext" href="index.php?action=institution&function=newInstitution">Institution hinzufügen</a>
            </p>
            {/if}
        {/if}
        <p>&nbsp;</p><p>&nbsp;</p>
        {if isset($showInstitutionForm)}
        <form method='post' action='index.php?action=institution'>
        <p>&nbsp;</p>
        <p><h3>Institution</h3></p>
        <input type='hidden' name='id' id='id' {if isset($id)}value='{$id}'{/if} />   
        <p><label>Institution / Schule*: </label><input class='inputlarge' type='text' name='institution' id='institution' {if isset($institution)}value='{$institution}'{/if} /></p> 
        {validate_msg field='institution'}
        <p><label>Beschreibung*: </label><input class='inputlarge' type='description' name='description' {if isset($description)}value='{$description}'{/if}/></p>
        {validate_msg field='institution_description'}
        <p id="schooltype_list"><label>Schultyp: </label><select name="schooltype_id" >
            {section name=res loop=$schooltype}  
                <option value={$schooltype[res]->id} {if isset($schooltype_id) AND $schooltype[res]->id eq $schooltype_id}selected="selected"{/if}>{$schooltype[res]->schooltype}</option>
            {/section}
            </select></p>  
        <p><label>Anderer Schultyp... </label><input class="centervertical" type="checkbox" name='btn_newSchooltype' value='Neuen Schultyp anlegen' onclick="checkbox_addForm(this.checked, 'inline', 'newSchooltype', 'schooltype_list')"/></p>
        <div id="newSchooltype" style="display:none;">
            <p><label>Schultyp: </label><input class='inputlarge' type='text' name='new_schooltype' id='schooltype_id' {if isset($new_schooltype)}value='{$new_schooltype}'{/if} /></p> 
            <p><label>Beschreibung: </label><input class='inputlarge' type='text' name='schooltype_description' {if isset($schooltype_description)}value='{$schooltype_description}'{/if}/></p>
        </div>
        <p><label>Land: </label><select name="country" id="country" onchange="loadStates(this.value);">
            {section name=res loop=$countries}  
                <option label={$countries[res]->de} value={$countries[res]->id} {if isset($country_id) AND $countries[res]->id eq $country_id}selected="selected"{/if}>{$countries[res]->de} </option>
            {/section}
        </select></p>

        <p id="states">
            {if isset($state_id)}
            <label>Bundesland / Region: </label><SELECT name="state" />
            {section name=s_id  loop=$state}
                <OPTION label={$state[s_id]->state} value={$state[s_id]->id} {if $state[s_id]->id eq ($state_id)}selected="selected"{/if}>{$state[s_id]->state}</OPTION>
            {/section} 
            </SELECT>
            {else}<script src="{$media_url}scripts/script.js"></script>
                  <script type='text/javascript'>loadStates(document.getElementById('country').value);</script>{/if}
        </p>
        
        {if !isset($showeditInstitutionForm)}
        <p><label></label><input type='submit' name='addInstitution' value='Institution hinzufügen' /></p>
        {else}
        <p><label></label><input type='submit' name="back" value='zurück'/><input type='submit' name="updateInstitution" value='Institution aktualisieren' /></p>
	{/if}
        </form>	
        {/if}
    </div>
         <form id='institutionlist' method='post' action='index.php?action=institution&next={$currentUrlId}'>
            <p>&nbsp;</p>
    {if $data != null}
        {* display pagination header *}
        <p class="floatright">Datensätze {$institutionPaginator.first}-{$institutionPaginator.last} von {$institutionPaginator.total} werden angezeigt.</p>   
            <table id="contenttable">
                    <tr id="contenttablehead">
                        <td></td>    
                    {*<td>Fach-ID</td>*}
                    <td>Institution</td>
                    <td>Beschreibung</td>
                    <td>Schultyp</td>
                    <td>Bundesland/Region</td>
                    <td>Land</td>
                    <td>Erstellungsdatum</td>
                    <td>Administrator</td>
                    
                    <td class="td_options">Optionen</td>
            </tr>
            
            {* display results *}    
            {section name=ins loop=$institution_list}
                <tr class="contenttablerow" id="row{$institution_list[ins]->id}" onclick="checkrow({$institution_list[ins]->id})">
                    <td><input class="invisible" type="checkbox" id="{$institution_list[ins]->id}" name="id[]" value={$institution_list[ins]->id} /></td>
                    {*<td>{$institution_list[institution]->id}</td>*}
                    <td>{$institution_list[ins]->institution}</td>
                    <td>{$institution_list[ins]->description}</td>
                    <td>{$institution_list[ins]->schooltype_id}</td>
                    <td>{$institution_list[ins]->state_id}</td>
                    <td>{$institution_list[ins]->country}</td>
                    <td>{$institution_list[ins]->creation_time}</td>
                    <td>{$institution_list[ins]->creator_id}</td>
                    <td class="td_options">
                        {if checkCapabilities('institution:delete', $my_role_id, false)}
                            <a class="deletebtn floatright" type="button" name="delete" onclick="del('institution',{$institution_list[ins]->id}, {$my_id})"></a>
                        {else}
                            <a class="deletebtn deactivatebtn floatright" type="button"></a>
                        {/if}
                        {if checkCapabilities('institution:update', $my_role_id, false)}
                            <a class="editbtn floatright" href="index.php?action=institution&edit=true&id={$institution_list[ins]->id}"></a>
                        {else}
                            <a class="editbtn deactivatebtn floatright"></a>
                        {/if}
                        </td>
                </tr>
            {/section}
            
            </table>

                    <!--Hack für problem, dass kein Array gepostet wird, wenn nichts angewählt wird-->
            <input class="invisible" type="checkbox" name="id[]" value="none" checked />
            {* display pagination info *}
            <p class="floatright">{paginate_prev id="institutionPaginator"} {paginate_middle id="institutionPaginator"} {paginate_next id="institutionPaginator"}</p>
             <p>&nbsp;</p>
        {/if}
        </form>          
            
            
</div>  <script src="{$media_url}scripts/script.js"></script>
        <script type='text/javascript'>
	document.getElementById('institution').focus();
	</script>            
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}