{extends file="base.tpl"}

{block name=title}{$page_title}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}
<div class="border-box">
    <h3 class="page-header">{$page_title}<input class="curriculumdocsbtn pull-right" type="button" name="help" onclick="curriculumdocs('http://docs.joachimdieterich.de/index.php?title=F%C3%A4cher_anlegen');"/></h3>
    {if !isset($showForm) && checkCapabilities('subject:add', $my_role_id, false)}
        <div class="btn-group" role="group" aria-label="...">
            <button type="button" class="btn btn-default"><a  href="index.php?action=subject&function=new">
                    <span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> Fach hinzufügen</a>
            </button>
        </div>
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
    
    {html_paginator id='subjectP'}
</div>
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}