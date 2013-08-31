{extends file="base.tpl"}

{block name=title}{$teacherCurriculum}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}
    
<div class=" border-radius gray-border">	
    <div class="border-top-radius contentheader ">{$teacherCurriculum}</div>
    <div class="space-top-padding gray-gradient border-bottom-radius box-shadow ">
        {if !isset($edit_form)}
        <p class="floatleft gray-gradient cssimgbtn border-radius gray-border">
            <a class="addbtn cssbtnmargin cssbtntext" href="index.php?action=teacherCurriculum&function=new_curriculum">Lehrplan hinzufügen</a>
        </p>
        <p>&nbsp;</p>
        {/if}
        
        {if isset($edit_form)}
            <div id="right">
                <img id="icon"  src="">
            </div>
            <form id='add' method='post' action='index.php?action=teacherCurriculum&next={if isset($currentUrlId)}{$currentUrlId}{/if}'>
            <input class="invisible" type="text" id="edit_curriculum_id" name="edit_curriculum_id" {if isset($edit_curriculum_id)}value='{$edit_curriculum_id}'{/if}>
            <p><label>Lehrplan-Name*: </label><input class='inputformlong' type='text' name='curriculum' id='curriculum' {if isset($curriculum)}value='{$curriculum}'{/if} /></p>   
            {validate_msg field='curriculum'}
            <p><label>Beschreibung*: </label><input class='inputformlong' type='description' name='description' {if isset($description)}value='{$description}'{/if}/></p>
            {validate_msg field='description'}
            <p><label>Fach: </label>
                <select class='inputformlong' name="subject">
                {section name=res loop=$subjects}  
                <option label={$subjects[res]->subject} value={$subjects[res]->id} {if $subject_id eq $subjects[res]->id}selected="selected"{/if}>{$subjects[res]->subject}</option>
                {/section}
                </select> 
            </p>
            {validate_msg field='subject'}
            <p><label>Fach-Icon: </label>
                <select class='inputformlong' name="icon" onchange="showSubjectIcon('{$subjects_url}', this.options[this.selectedIndex].innerHTML);">
                {section name=res loop=$icons}  
                <option label={$icons[res]->title} value={$icons[res]->id} {if $icon_id eq $icons[res]->id}selected="selected"{/if}>{$icons[res]->filename}</option>
                {/section}
                </select> 
            </p>
            {validate_msg field='icon'}
            <p><label>Klassenstufe: </label>
                <select class='inputformlong' name="grade">
                {section name=res loop=$grades}  
                <option label="{$grades[res]->grade}" value={$grades[res]->id} {if $grade_id eq $grades[res]->id}selected="selected"{/if}>{$grades[res]->grade}</option>
                {/section}
                </select> 
            </p>
            {validate_msg field='grade'}
            <p><label>Schultyp: </label>
                <select class='inputformlong' name="schooltype">
                {section name=res loop=$schooltypes}  
                <option label={$schooltypes[res]->schooltype} value={$schooltypes[res]->id} {if $schooltype_id eq $schooltypes[res]->id}selected="selected"{/if}>{$schooltypes[res]->schooltype}</option>
                {/section}
                </select> 
            </p>
            {validate_msg field='schooltype'}
            <p><label>Bundesland/Region: </label>
                <select name="state">
                    {section name=res loop=$states}  
                        <option label={$states[res]->state} value={$states[res]->id} {if $state_id eq $states[res]->id}selected="selected"{/if}>{$states[res]->state}</option>
                    {/section}
                </select>
            {validate_msg field='state'}
            <p><label>Land: </label>
                <select name="country">
                    {section name=res loop=$countries}  
                        <option label={$countries[res]->de} value={$countries[res]->id} {if $state_id eq $countries[res]->id}selected="selected"{/if}>{$countries[res]->de}</option>
                    {/section}
                </select>
                
            {validate_msg field='countries'}

            {if !isset($showeditCurriculumForm)}
                <p><label></label><input type='submit' name="back" value='zurück'/><input type='submit' name="add" value='Lehrplan hinzufügen' /></p>
            {/if}
            {if isset($showeditCurriculumForm)}
                <p><label></label><input type='submit' name="back" value='zurück'/><input type='submit' name="update" value='Lehrplan aktualisieren' /></p>
            {/if}
            </form>	
        {/if}
        
        <form id='curriculumlist' method='post' action='index.php?action=teacherCurriculum&next={if isset($currentUrlId)}{$currentUrlId}{/if}'>
            <p>&nbsp;</p>
    {if $data != null}
        {* display pagination header *}
        <p class="floatright">Datensätze {$curriculumPaginator.first}-{$curriculumPaginator.last} von {$curriculumPaginator.total} werden angezeigt.</p>   
            <table id="contenttable">
                    <tr id="contenttablehead">
                    {*<td>Curriculum-ID</td>*}
                    <td></td>
                    <td></td>
                    <td>Lehrplan</td>
                    <td>Beschreibung</td>
                    <td>Fach</td>
                    <td>Klasse</td>
                    <td>Schultyp</td>
                    <td>Bundesland/Region</td>
                    <td>Land</td>
                    {if !isset($edit_form)}
                        <td class="td_options">Optionen</td>    
                    {/if}    
            </tr>
            
            {* display results *}    
            {section name=res loop=$results}
                <tr {if isset($selectedID) AND $selectedID eq $results[res].id} class="activecontenttablerow"{/if} class="contenttablerow" id="row{$results[res].id}" name="row{$results[res].id}" onclick="checkrow({$results[res].id})">
                    <td><input class="invisible" type="checkbox" id="{$results[res].id}" name="id[]" value={$results[res].id} {if isset($selectedID) AND $selectedID eq $results[res].id} checked{/if}/></td>
                    {*<td>{$results[res].id}</td>*}
                    <td><img class="icon_tiny icon_listposition" src="{$subjects_url}{$results[res].filename}"></td>
                    <td>{$results[res].curriculum}</td>
                    <td>{$results[res].description}</td>
                    <td>{$results[res].subject}</td>
                    <td>{$results[res].grade}</td>
                    <td>{$results[res].schooltype}</td>
                    <td>{$results[res].state}</td>
                    <td>{$results[res].de}</td>
                    {if !isset($edit_form)}
                    <td class="td_options">{*<div class="editbtn"  onmouseup="document.getElementById('editCurriculum').click();""></div>*}
                        <a class="deletebtn floatright" type="button" name="delete" onclick="deleteCurriculum({$results[res].id})"></a>
                        <a class="addbtn floatright" href="index.php?action=view&function=addObjectives&curriculum={$results[res].id}"></a>
                        <a class="editbtn floatright" href="index.php?action=teacherCurriculum&function=edit&edit_curriculum_id={$results[res].id}"></a>
                    </td>
                    {/if}
                </tr>
            {/section} 
            </table>
            <input class="invisible" type="checkbox" name="id[]" value="none" checked />
            {* display pagination info *}
            <p class="floatright">{paginate_first id="curriculumPaginator"} {paginate_middle id="curriculumPaginator"} {paginate_next id="curriculumPaginator"}</p>
        
        {/if}
        <p>&nbsp;</p>
</div>
        <script type='text/javascript'>
	document.getElementById('curriculum').focus();
        document.getElementById('icon').src = '{$subjects_url}'+document.getElementById("icons").options[document.getElementById("icons").selectedIndex].innerHTML;
        
	</script>
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}
