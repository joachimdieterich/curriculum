{extends file="base.tpl"}

{block name=title}{$page_title}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}
<div class="border-box">
    <div class="contentheader ">Klassenstufen verwalten<input class="curriculumdocsbtn floatright" type="button" name="help" onclick="curriculumdocs('http://docs.joachimdieterich.de/index.php?title=Klassenstufen_anlegen');"/></div>
    {if !isset($showForm) AND checkCapabilities('grade:add', $my_role_id, false)}
    <p class="floatleft cssimgbtn gray-border">
        <a class="addbtn cssbtnmargin cssbtntext" href="index.php?action=grade&function=new">Klassenstufe hinzufügen</a>
    </p>
    {else}
        <form id='gradeForm' method='post' action='index.php?action=grade&next={$currentUrlId}'>
            <input id='id' name='id' type='hidden' {if isset($id)}value='{$id}'{/if} /></p>   
            <p><label>Klassenstufen-Name</label><input id='grade' name='grade' class='inputlarge' {if isset($grade)}value='{$grade}'{/if} /></p>
            {validate_msg field='grade'}
            <p><label>Beschreibung</label><input name='description' class='inputlarge' {if isset($description)}value='{$description}'{/if}/></p>
            {validate_msg field='description'}
            <p><label>Institution / Schule*:</label><SELECT  name='institution_id' id='institution_id' />
            {foreach key=insid item=ins from=$my_institutions}
                <OPTION  value="{$ins->institution_id}"  {if $ins->institution_id eq $institution_id}selected="selected"{/if}>{$ins->institution}</OPTION>
            {/foreach} 
            </SELECT></p>
            <script type='text/javascript'> document.getElementById('grade').focus(); </script>
            <p><label></label>
                {if !isset($editBtn)}
                    <input name="add" type='submit' value='Klassenstufe hinzufügen' 
                {else}
                    <input name="back" type='submit' value='zurück'/><input name="update" type='submit' value='Klassenstufe aktualisieren' />
                {/if}
            </p>
        </form>	
    {/if}
    
{html_paginator id='gradeP' values=$gr_val config=$gradeP_cfg}
</div>
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}