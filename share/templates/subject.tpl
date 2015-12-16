{extends file="base.tpl"}

{block name=title}{$page_title}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}
<div class="border-box">
    <div class="contentheader ">{$page_title}<input class="curriculumdocsbtn floatright" type="button" name="help" onclick="curriculumdocs('http://docs.joachimdieterich.de/index.php?title=F%C3%A4cher_anlegen');"/></div>
    {if !isset($showForm) && checkCapabilities('subject:add', $my_role_id, false)}
        <p class="floatleft  cssimgbtn gray-border">
            <a class="addbtn cssbtnmargin cssbtntext" href="index.php?action=subject&function=new">Fach hinzufügen</a>
        </p>
    {else}
        <form id='subjectForm' method='post' action='index.php?action=subject&next={$currentUrlId}'>
        <input type='hidden' name='id' id='id' {if isset($id)}value='{$id}'{/if} />    
        <p><label>Fach-Name*: </label>      <input id='subject' name='subject' class='inputlarge' {if isset($subject)}value='{$subject}'{/if} /></p>   
        {validate_msg field='subject'}
        <p><label>Fach-Kürzel*: </label>    <input id='subject_short' name='subject_short' class='inputlarge' {if isset($subject_short)}value='{$subject_short}'{/if} /></p>   
        {validate_msg field='subject_short'}
        <p><label>Beschreibung*: </label>   <input name='description' class='inputlarge' {if isset($description)}value='{$description}'{/if}/></p>
        {validate_msg field='description'}
        <p><label>Institution / Schule*:</label><SELECT  name='institution_id' id='institution_id' />
            {foreach key=insid item=ins from=$my_institutions}
                <OPTION  value="{$ins->institution_id}"  {if $ins->institution_id eq $institution_id}selected="selected"{/if}>{$ins->institution}</OPTION>
            {/foreach} 
        </SELECT></p>
        {if !isset($editBtn)}
            <p><label></label><input name='add' type='submit'  value='Fach hinzufügen' /></p>
        {else}
            <p><label></label><input name="back" type='submit' value='zurück'/><input name="update" type='submit' value='Fach aktualisieren' /></p>
        {/if}
        </form>
        <script type='text/javascript'> document.getElementById('subject').focus(); </script>
    {/if}
    
    {html_paginator id='subjectP' values=$su_val config=$subjectP_cfg}
</div>
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}