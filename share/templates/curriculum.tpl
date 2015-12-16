{extends file="base.tpl"}

{block name=title}{$page_title}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}
<div class="border-box">
    <div class="contentheader ">{$page_title}<input class="curriculumdocsbtn floatright" type="button" name="help" onclick="curriculumdocs('http://docs.joachimdieterich.de/index.php?title=Lehrplan_anlegen');"/></div>
    {if !isset($showForm) && checkCapabilities('curriculum:add', $my_role_id, false)}
        <p class="floatleft  cssimgbtn gray-border">
            <a class="addbtn cssbtnmargin cssbtntext" href="index.php?action=curriculum&function=new">Lehrplan hinzufügen</a>
        </p>
    {else}
        <div id="right"><img id="icon_img"  src=""></div>
        <form id='curriculumForm' method='post' action='index.php?action=curriculum&next={if isset($currentUrlId)}{$currentUrlId}{/if}'>
        <input id="c_id" name="c_id" type="text" class="invisible" {if isset($c_id)}value='{$c_id}'{/if}>
        <p><label>Lehrplan-Name*: </label><input id='c_curriculum' name='c_curriculum' class='inputlarge' {if isset($c_curriculum)}value='{$c_curriculum}'{/if} /></p>   
        {validate_msg field='c_curriculum'}
        <p><label>Beschreibung*: </label><input name='c_description' class='inputlarge'{if isset($c_description)}value='{$c_description}'{/if}/></p>
        {validate_msg field='c_description'}
        <p><label>Fach: </label>
            <select  name="c_subject" class='inputlarge'>
            {section name=res loop=$subjects}  
            <option label={$subjects[res]->subject} value={$subjects[res]->id} {if $c_subject_id eq $subjects[res]->id}selected="selected"{/if}>{$subjects[res]->subject}</option>
            {/section}
            </select> 
        </p>
        {validate_msg field='c_subject'}
        <p><label>Fach-Icon: </label>
            <select id="icon" name="c_icon" class='inputlarge' onchange="showSubjectIcon('{$subjects_path}', this.options[this.selectedIndex].innerHTML);">
            {section name=res loop=$icons}  
                <option label={$icons[res]->title} value={$icons[res]->id} {if $c_icon_id eq $icons[res]->id}selected="selected"{/if}>{$icons[res]->filename}</option>
            {/section}
            </select> 
        </p>
        {validate_msg field='c_icon'}
        <p><label>Klassenstufe: </label>
            <select  name="c_grade" class='inputlarge'>
            {section name=res loop=$grades}  
                <option label="{$grades[res]->grade}" value={$grades[res]->id} {if $c_grade_id eq $grades[res]->id}selected="selected"{/if}>{$grades[res]->grade}</option>
            {/section}
            </select> 
        </p>
        {validate_msg field='c_grade'}
        <p><label>Schultyp: </label>
            <select  name="c_schooltype" class='inputlarge'>
            {section name=res loop=$schooltypes}  
                <option label={$schooltypes[res]->schooltype} value={$schooltypes[res]->id} {if $c_schooltype_id eq $schooltypes[res]->id}selected="selected"{/if}>{$schooltypes[res]->schooltype}</option>
            {/section}
            </select> 
        </p>
        {validate_msg field='c_schooltype'}
        <p><label>Bundesland/Region: </label>
            <select name="c_state" class='inputlarge'>
            {section name=res loop=$states}  
                <option label={$states[res]->state} value={$states[res]->id} {if $c_state_id eq $states[res]->id}selected="selected"{/if}>{$states[res]->state}</option>
            {/section}
            </select>
        {validate_msg field='c_state'}
        <p><label>Land: </label>
            <select name="c_country" class='inputlarge'>
            {section name=res loop=$countries}  
                <option label={$countries[res]->de} value={$countries[res]->id} {if $c_state_id eq $countries[res]->id}selected="selected"{/if}>{$countries[res]->de}</option>
            {/section}
            </select>
        </p>
        {validate_msg field='c_countries'}
        <p><label></label><input name="back" type='submit' value='zurück'/>
        {if isset($editBtn)} <input name="update"  type='submit' value='Lehrplan aktualisieren' /></p>
        {else} <input name="add" type='submit' value='Lehrplan hinzufügen' /></p>
        {/if}
        </form>
         <script type='text/javascript'>
	document.getElementById('c_curriculum').focus();
        document.getElementById('icon_img').src = '{$subjects_path}'+document.getElementById("icon").options[document.getElementById("icon").selectedIndex].innerHTML;
	</script>
    {/if}
   
    {html_paginator id='curriculumP' values=$cu_val config=$curriculumP_cfg}
</div>
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}
