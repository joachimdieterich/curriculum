{extends file="base.tpl"}

{block name=title}{$teacherSubject}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}
    
<div class=" border-radius gray-border">	
    <div class="border-top-radius contentheader ">{$teacherSubject}</div>
    <div class="space-top-padding gray-gradient border-bottom-radius box-shadow ">
        {if !isset($showSubjectForm)}
        <p class="floatleft gray-gradient cssimgbtn border-radius gray-border">
            <a class="addbtn cssbtnmargin cssbtntext" href="index.php?action=teacherSubject&function=newSubject">Fach hinzufügen</a>
        </p>
        
        {/if}
        <p>&nbsp;</p><p>&nbsp;</p>
        {if isset($showSubjectForm)}
        <form id='addSubject' method='post' action='index.php?action=teacherSubject&next={$currentUrlId}'>
        <input type='hidden' name='id' id='id' {if isset($id)}value='{$id}'{/if} />   
        <p><label>Fach-Name*: </label><input class='inputformlong' type='text' name='subject' id='subject' {if isset($subject)}value='{$subject}'{/if} /></p>   
        {validate_msg field='subject'}
        <p><label>Fach-Kürzel*: </label><input class='inputformlong' type='text' name='subject_short' id='subject_short' {if isset($subject_short)}value='{$subject_short}'{/if} /></p>   
        {validate_msg field='subject_short'}
	<p><label>Beschreibung*: </label><input class='inputformlong' type='text' name='description' {if isset($description)}value='{$description}'{/if}/></p>
        {validate_msg field='description'}
        {if count($my_institutions['id']) > 1}
            <p><label>Institution / Schule*: </label>{html_options id='institution' name='institution' values=$my_institutions['id'] output=$my_institutions['institution']}</p>
        {elseif count($my_institutions['id']) eq 0}
            <p><strong>Sie müssen zuerst eine Institution anlegen</strong></p>
        {else}
            <input type='hidden' name='institution' id='institution' value='{$my_institutions['id'][0]}' /></p>       
        {/if}
        
        {if !isset($showeditSubjectForm)}
        <p><label></label><input type='submit' name='addSubject' value='Fach hinzufügen' /></p>
        {else}
        <p><label></label><input type='submit' name="back" value='zurück'/><input type='submit' name="updateSubject" value='Fach aktualisieren' /></p>
	{/if}
        </form>	
        {/if}
         
        <form id='subjectlist' method='post' action='index.php?action=teacherSubject&next={$currentUrlId}'>
            <p>&nbsp;</p>
    {if $data != null}
        {* display pagination header *}
        <p class="floatright">Datensätze {$subjectsPaginator.first}-{$subjectsPaginator.last} von {$subjectsPaginator.total} werden angezeigt.</p>   
            <table id="contenttable">
                    <tr id="contenttablehead">
                        <td></td>    
                    {*<td>Fach-ID</td>*}
                    <td>Fächername</td>
                    <td>Kürzel</td>
                    <td>Beschreibung</td>
                    <td class="td_options">Optionen</td>
            </tr>
            
            {* display results *}    
            {section name=subject loop=$subject_list}
                <tr class="contenttablerow" id="row{$subject_list[subject]->id}" onclick="checkrow({$subject_list[subject]->id})">
                    <td><input class="invisible" type="checkbox" id="{$subject_list[subject]->id}" name="id[]" value={$subject_list[subject]->id} /></td>
                    {*<td>{$subject_list[subject]->id}</td>*}
                    <td>{$subject_list[subject]->subject}</td>
                    <td>{$subject_list[subject]->subject_short}</td>
                    <td>{$subject_list[subject]->description}</td>
                    <td class="td_options">
                        <a class="deletebtn floatright" type="button" name="delete" onclick="deleteSubject({$subject_list[subject]->id})"></a>
                        <a class="editbtn floatright" href="index.php?action=teacherSubject&edit=true&id={$subject_list[subject]->id}"></a>
                        </td>
                </tr>
            {/section}
            
            </table>

                    <!--Hack für problem, dass kein Array gepostet wird, wenn nichts angewählt wird-->
            <input class="invisible" type="checkbox" name="id[]" value="none" checked />
            {* display pagination info *}
            <p class="floatright">{paginate_prev id="subjectsPaginator"} {paginate_middle id="subjectsPaginator"} {paginate_next id="subjectsPaginator"}</p>
             <p>&nbsp;</p>
        {/if}
        </form>              
        
</div>
        <script type='text/javascript'>
	document.getElementById('subject').focus();
	</script>
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}
