{extends file="base.tpl"}

{block name=title}{$page_title}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}
    
<div class=" border-radius gray-border">	
    <div class="border-top-radius contentheader">{$page_title}</div>
    <div class="space-top-padding gray-gradient border-bottom-radius box-shadow ">
        {if !isset($showSemesterForm)}
        <p class="floatleft gray-gradient cssimgbtn border-radius gray-border">
            <a class="addbtn cssbtnmargin cssbtntext" href="index.php?action=semester&newSemester">Lernzeitraum hinzufügen</a>
        </p>
        <p>&nbsp;</p>
        {/if}
        {if isset($showSemesterForm)}
        <form id='addSemester' method='post' action='index.php?action=semester&next={$currentUrlId}'>
        <input class='inputlarge' type='hidden' name='id' id='id' {if isset($id)}value='{$id}'{/if} />       
        <p><label>Lernzeitraum*:</label><input class='inputlarge' name='semester' id='semester' {if isset($semester)}value='{$semester}'{/if} /></p>   
        {validate_msg field='semester'}
	<p><label>Beschreibung*:</label><input class='inputlarge' name='description' {if isset($description)}value='{$description}'{/if}/></p>
	{validate_msg field='description'}
        <p><label>Lernzeitraum-Beginn*:</label><input type='date' id='begin' name='begin' {if isset($begin)}value='{$begin}'{/if}/>
	{validate_msg field='begin'}
        <p><label>Lernzeitraum-Ende*:</label><input type='date' id='end' name='end' {if isset($end)}value='{$end}'{/if}/>
        {validate_msg field='end'}
        {if count($my_institutions['id']) > 1}
            <p><label>Institution / Schule*: </label>{html_options id='institution' name='institution' values=$my_institutions['id'] output=$my_institutions['institution']}</p>
        {elseif count($my_institutions['id']) eq 0}
            <p><strong>Sie müssen zuerst eine Institution anlegen</strong></p>
        {else}
            <input type='hidden' name='institution' id='institution' value='{$my_institutions['id'][0]}' /></p>       
        {/if}
            {if !isset($showeditSemesterForm)}
            <p><label></label><input type='submit' name="addSemester" value='Lernzeitraum erstellen' /></p>
            {else}
            <p><label></label><input type='submit' name="back" value='zurück'/><input type='submit' name="updateSemester" value='Lernzeitraum aktualisieren' /></p>
            {/if}
        
	</form>	
        {/if}
         
        <form id='semesterlist' method='post' action='index.php?action=semester&next={$currentUrlId}'>
            <p>&nbsp;</p>
    {if $data != null}
        {* display pagination header *}
        <p class="floatright">Datensätze {$semesterPaginator.first}-{$semesterPaginator.last} von {$semesterPaginator.total} werden angezeigt.</p>   
            <table id="contenttable">
                    <tr id="contenttablehead">
                    <td></td>    
                    <td>Lernzeitraum</td>
                    <td>Beschreibung</td>
                    <td>Lernzeitraum-Beginn</td>
                    <td>Lernzeitraum-Ende</td>
                    <td>Erstellungsdatum</td>
                    <td>Erstellt von</td>
                    
                    <td class="td_options">Optionen</td>
            </tr>
            {section name=semester loop=$semester_list}{* display results *}
                <tr class="contenttablerow" id="row{$semester_list[semester]->id}" onclick="checkrow({$semester_list[semester]->id})">
                    <td><input class="invisible" type="checkbox" id="{$semester_list[semester]->id}" name="id[]" value={$semester_list[semester]->id} /></td>
                    <td>{$semester_list[semester]->semester}</td>
                    <td>{$semester_list[semester]->description}</td>
                    <td>{$semester_list[semester]->begin}</td>
                    <td>{$semester_list[semester]->end}</td>
                    <td>{$semester_list[semester]->creation_time}</td>
                    <td>{$semester_list[semester]->creator_username}</td>
                   
                    <td>
                        <a class="deletebtn floatright" type="button" name="delete" onclick="del('semester',{$semester_list[semester]->id}, {$my_id})"></a>
                        <a class="editbtn floatright" href="index.php?action=semester&edit=true&id={$semester_list[semester]->id}"></a>
                        </td>
                </tr>
            {/section}
            </table>  
            <input class="invisible" type="checkbox" name="id[]" value="none" checked /><!--Hack für problem, dass kein Array gepostet wird, wenn nichts angewählt wird-->
            {* display pagination info *}
            <p class="floatright">{paginate_prev id="semesterPaginator"} {paginate_middle id="semesterPaginator"} {paginate_next id="semesterPaginator"}</p>
        {/if}

        </form>              
        <p>&nbsp;</p>
</div>
       {* <script type='text/javascript'>
	document.getElementById('semester').focus();
	</script>*}
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}