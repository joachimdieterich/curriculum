{extends file="base.tpl"}

{block name=title}{$page_title}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}
{block name=additional_scripts}{$smarty.block.parent}{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}    
<div class="border-box">
    <div class="contentheader ">{$page_title}<input class="curriculumdocsbtn floatright" type="button" name="help" onclick="curriculumdocs('http://docs.joachimdieterich.de/index.php?title=Institutionen');"/></div>     
        {if !isset($showForm) && checkCapabilities('institution:add', $my_role_id, false)}
            <p class="floatleft  cssimgbtn gray-border">
                <a class="addbtn cssbtnmargin cssbtntext" href="index.php?action=institution&function=new">Institution hinzufügen</a>
            </p>            
        {else}
            <form id='institutionForm' method='post' action='index.php?action=institution'>
            <p><h3>Institution</h3></p>
            <input id='id' name='id' type='hidden' {if isset($id)}value='{$id}'{/if} />   
            <p><label>Institution / Schule*: </label>   <input id='institution' name='institution' class='inputlarge' {if isset($institution)}value='{$institution}'{/if} /></p> 
            {validate_msg field='institution'}
            <p><label>Beschreibung*: </label>           <input name='description'class='inputlarge' {if isset($description)}value='{$description}'{/if}/></p>
            {validate_msg field='description'}
            <p id="schooltype_list"><label>Schultyp: </label>
                <select name="schooltype_id" >
                {section name=res loop=$schooltype}  
                    <option value={$schooltype[res]->id} {if isset($schooltype_id) AND $schooltype[res]->id eq $schooltype_id}selected="selected"{/if}>{$schooltype[res]->schooltype}</option>
                {/section}
                </select>
            </p>  
            <p><label>Anderer Schultyp... </label>      <input name='btn_newSchooltype' type="checkbox" class="centervertical" value='Neuen Schultyp anlegen' onclick="checkbox_addForm(this.checked, 'inline', 'newSchooltype', 'schooltype_list');"/></p>
            <div id="newSchooltype" style="display:none;">
                <p><label>Schultyp: </label>            <input id='schooltype_id' name='new_schooltype' class='inputlarge' {if isset($new_schooltype)}value='{$new_schooltype}'{/if} /></p> 
                <p><label>Beschreibung: </label>        <input name='schooltype_description' class='inputlarge' {if isset($schooltype_description)}value='{$schooltype_description}'{/if}/></p>
            </div>
            <p><label>Land: </label>
                <select name="country_id" id="country_id" onchange="getStates(this.value);">
                {section name=res loop=$countries}  
                    <option label={$countries[res]->de} value={$countries[res]->id} {if isset($country_id) AND $countries[res]->id eq $country_id}selected="selected"{/if}>{$countries[res]->de} </option>
                {/section}
                </select>
            </p>
            <p id="states">
                {if isset($state_id)}
                    <label>Bundesland / Region: </label><SELECT name="state_id" />
                    {section name=s_id  loop=$state}
                        <OPTION label={$state[s_id]->state} value={$state[s_id]->id} {if $state[s_id]->id eq ($state_id)}selected="selected"{/if}>{$state[s_id]->state}</OPTION>
                    {/section} 
                    </SELECT>
                {else}
                    <script type='text/javascript'>getStates(document.getElementById('country').value);</script>
                {/if}
            </p>
            {if checkCapabilities('file:upload', $my_role_id, false)}
                <p><label>Logo: </label><input  id="file_id" name='file_id' {if isset($file_id)}value='{$file_id}'{/if} onclick="tb_show('','../share/request/uploadframe.php?userID={$my_id}&last_login={$my_last_login}&context=institution&curID={$id}&target=file_id&format=0&multiple=false&modal=true&TB_iframe=true&width=710')" href="#" class="thickbox"/>
                {validate_msg field='file_id'}
            {/if}
            
            <p><h3>Einstellungen</h3></p>
            <p><label>Rolle</label>
                <SELECT  name='std_role' id='std_role' />
                    {foreach key=rolid item=rol from=$roles}
                        <OPTION  value="{$rol->id}" {if $rol->id eq $std_role}selected="selected"{/if}>{$rol->role}</OPTION>
                    {/foreach} 
            </SELECT></p>
            <p><label>Anzahl der Listeneinträge: </label><input name='paginator_limit' type='number' min="5" max="150" {if isset($paginator_limit)}value='{$paginator_limit}'{/if}/></p>
            {validate_msg field='paginator_limit'}
            <p><label>Lernerfolge x Tage anzeigen : </label><input name='acc_days' type='number' min="1" max="365" {if isset($acc_days)}value='{$acc_days}'{/if}/></p>
            {validate_msg field='acc_days'}

            <p><label>Timeout (Minuten): </label><input id='timeout' name='timeout' type='number' min="1" max="240"  value={$timeout} /></p>
            {validate_msg field='timeout'}
            <p><label>Semester: </label>
                {if $my_semester_id != NULL}
                    <select name="semester_id"> 
                    {section name=res loop=$mySemester}  
                            <OPTION label="{$mySemester[res]->semester}" value={$mySemester[res]->id} {if $mySemester[res]->id eq $semester_id}selected{/if}>{$mySemester[res]->semester}</OPTION>{/section}
                    </select>
                    {else}Sie müssen zuerst einen Lernzeitraum anlegen{/if}{validate_msg field='semester_id'}
                            
            <p><h3>Dateien und Pfade</h3></p>
                <p>Legt fest wie groß Dateien beim Upload sein dürfen.</p>
                <p> Maximale Uploadgröße dieses Servers = {$post_max_size} ({$byte} Byte)</p>
                <p><label>csv-Dateien: </label><input id='csv_size' name='csv_size' type='number' min="5000" max="1048576" value={if isset($csv_size)}{$csv_size}{else}{$institution_csv_size}{/if} /> csv-Dateien für den Benutzerimport</p>
                {validate_msg field='csv_size'}
                <p><label>Profilfotos: </label><input id='avatar_size' name='avatar_size' type='number' min="5000" max="1048576" value={if isset($avatar_size)}{$avatar_size}{else}{$institution_avatar_size}{/if} /> Bild-Dateien für das Profilfoto</p>
                {validate_msg field='avatar_size'}
                <p><label>sonst. Dateien: </label><input id='material_size' name='material_size' type='number' min="5000" max="1048576" value={if isset($material_size)}{$material_size}{else}{$institution_material_size}{/if} /> Dateien für Materialupload</p>
                {validate_msg field='material_size'}                
            {if !isset($editBtn)}
                <p><label></label><input name='add' type='submit' value='Institution hinzufügen' /></p>
            {else}
                <p><label></label><input name='back' type='submit' value='zurück'/><input type='submit' name="update" value='Institution aktualisieren' /></p>
            {/if}
            <script type='text/javascript'>
                document.getElementById('institution').focus();
            </script>      
            </form>	
        {/if}
        
        {html_paginator id='institutionP'}
</div>  
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}