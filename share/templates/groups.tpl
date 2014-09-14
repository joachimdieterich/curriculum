{extends file="base.tpl"}

{block name=title}{$page_title}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}
    
<div class=" border-radius gray-border">	
    <div class="border-top-radius contentheader ">{$page_title}</div>
    <div class="space-top-padding gray-gradient border-bottom-radius box-shadow ">
        {if !isset($new_group_form)}
        {*<p >Hier können Lerngruppen angelegt werden</p>*}
        
        <p class="floatleft gray-gradient cssimgbtn border-radius gray-border">
            <a class="addbtn cssbtnmargin cssbtntext" href="index.php?action=groups&function=new_group">Lerngruppe hinzufügen</a>
        </p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        {/if}
    {*Neue Lerngruppe anlegen*}
        {if isset($new_group_form)}
        <form id='addClass' method='post' action='index.php?action=groups&next={$currentUrlId}'>
        {if isset($edit_group_form) OR isset($new_semester_form)}
             <input class="invisible" type="text" id="edit_group_id" name="edit_group_id" value={$group_id}>
        {else}<p><label> </label></p>{/if} 
        <p><label>Lerngruppen-Name: </label><input class='inputlarge' type='text' id='group' name='group' {if isset($group)}value='{$group}'{/if} /></p>   
        {validate_msg field='group'}
	<p><label>Beschreibung: </label><input class='inputlarge' type='description' name='description' {if isset($description)}value='{$description}'{/if}/></p>
        {validate_msg field='description'}
        <p><label>Klassenstufe:</label>
          <select name="grade" class='inputlarge'>
              {section name=res loop=$grade}  
                <option label="{$grade[res]->grade}" value={$grade[res]->id} {if $grade_id eq $grade[res]->id}selected="selected"{/if}>{$grade[res]->grade}</option>
              {/section}
          </select> 
        {validate_msg field='grade'}
        {if isset($semester)}
            <p><label>Lernzeitraum: </label>
            <select name="semester" class='inputlarge'>
                {section name=res loop=$semester}  
                    <option label="{$semester[res]->semester}" value={$semester[res]->id} {if $semester_id eq $semester[res]->id}selected="selected"{/if}>{$semester[res]->semester}</option>
                {/section}
            </select> 
            {validate_msg field='semester'}  
            {else}<p><strong>Keine Lernzeiträume vorhanden! Um Lerngruppen anzulegen müssen zuerst Lernzeiträume angelegt werden.</strong></p>{/if}
        {if count($my_institutions['id']) > 1}
            <p><label>Institution / Schule*: </label>{html_options id='institution' name='institution' values=$my_institutions['id'] output=$my_institutions['institution']}</p>
        {elseif count($my_institutions['id']) eq 0}
            <p><strong>Sie müssen zuerst eine Institution anlegen</strong></p>
        {else}
            <input type='hidden' name='institution' id='institution' value='{$my_institutions['id'][0]}' /></p>       
        {/if}
        {if isset($new_semester_form)}
            <p><label>Personen übernehmen?: </label>
                <input style="vertical-align: text-bottom;" type="checkbox" id="assumeUsers" name="assumeUsers" checked="checked" />
                Um eine leere Lerngruppe zu erstellen, Haken entfernen.
            </p>  
                
        {/if}
            {if isset($edit_group_form) AND !isset($new_semester_form)}
                <p><label></label><input type='submit' name="back" value='zurück'/><input type='submit' name="update_group" value='Lerngruppe aktualisieren' /></p>
            {elseif isset($new_semester_form)}
            <p><label></label><input type='submit' name="back" value='zurück'/><input type='submit' name="change_semester" value='Lernzeitraum ändern' /></p>
            {else}
            <p><label></label><input type='submit' name="back" value='zurück'/><input type='submit' name="add_group" value='Lerngruppe hinzufügen' /></p>
            {/if}
            </form>	
        {/if}
    
        <form id='classlist' method='post' action='index.php?action=groups&next={$currentUrlId}'>
    {if $data != null}
        {* display pagination header *}
        <p>&nbsp;</p>
        <p class="floatright">Datensätze {$groupsPaginator.first}-{$groupsPaginator.last} von {$groupsPaginator.total} werden angezeigt.</p>   
            <table id="contenttable">
                    <tr id="contenttablehead">
                        <td></td>    
                    {*<td>Lerngruppen-ID</td>*}
                    <td>Lerngruppenname</td>
                    <td>(Klassen)stufe</td>
                    <td>Beschreibung</td>
                    <td>Lernzeitraum</td>
                    <td>Institution / Schule</td>
                    <td>Erstellungsdatum</td>
                    <td>Erstellt durch</td>
                    <td class="td_options">Optionen</td>
            </tr>
            
            {* display results *}    
            {section name=res loop=$results}
                <tr class="contenttablerow" id="row{$results[res]->id}" onclick="checkrow({$results[res]->id})">
                    <td><input class="invisible" type="checkbox" id="{$results[res]->id}" name="id[]" value={$results[res]->id} /></td>
                    {*<td>{$results[res].id}</td>*}
                    <td>{$results[res]->group}</td>
                    <td>{$results[res]->grade}</td>
                    <td>{$results[res]->description}</td>
                    <td>{$results[res]->semester}</td>
                    <td>{$results[res]->institution}</td>
                    <td>{$results[res]->creation_time}</td>
                    <td>{$results[res]->creator}</td>
                    <td class="td_options">
                        <a class="deletebtn floatright" type="button" name="delete" onclick="del('group',{$results[res]->id}, {$my_id})"></a>
                        <a class="calbtn floatright" href="index.php?action=groups&function=semester&group_id={$results[res]->id}"></a>
                        <a class="editbtn floatright" href="index.php?action=groups&function=edit&group_id={$results[res]->id}"></a>
                        <a class="groupbtn floatright" href="index.php?action=groups&function=showUsers&group_id={$results[res]->id}"></a>
                        <a class="listbtn floatright" href="index.php?action=groups&function=showCurriculum&group_id={$results[res]->id}"></a>
                        </td>
                </tr>
            {/section}            
            </table>
                    <!--Hack für problem, dass kein Array gepostet wird, wenn nichts angewählt wird-->
            <input class="invisible" type="checkbox" name="id[]" value="none" checked />
            {* display pagination info *}
            <p class="floatright">{paginate_prev id="groupsPaginator"} {paginate_middle id="groupsPaginator"} {paginate_next id="groupsPaginator"}</p>
            {if !isset($new_group_form)}
            {if isset($curriculum_list)}
            <p><h3>Markierte Lerngruppe(n)in Lehrplan ein- und ausschreiben:</h3>
            <p class="floatleft">
                <select class='floatleft' name="curriculum">
                    {section name=res loop=$curriculum_list}  
                        <option label="{$curriculum_list[res]->curriculum} | {$curriculum_list[res]->grade} | {$curriculum_list[res]->description}" value="{$curriculum_list[res]->id}">{$curriculum_list[res]->curriculum} | {$curriculum_list[res]->grade} | {$curriculum_list[res]->description}</option>
                    {/section}
                </select> 
            <div class=" floatleft gray-gradient cssimgbtn border-radius gray-border">
                <a class="inbtn block cssbtnmargin cssbtntext " onclick="document.getElementById('enrole_group').click();">einschreiben</a>
                </div><input class="invisible" type='submit' id='enrole_group' name='enrole_group' value='einschreiben' />
                <div class="floatleft gray-gradient cssimgbtn border-radius gray-border">
                <a class="outbtn block cssbtnmargin cssbtntext" onclick="document.getElementById('expel_group').click();">ausschreiben</a>
                </div><input class="invisible" type='submit' id='expel_group' name='expel_group' value='ausschreiben' />
            </p>
            {/if}
            {/if}
        {/if}
       
        </form>  
        
        
        {if isset($resultscurriculumList)}
        <p><h3>Lehrpläne der Lerngruppe</h3></p>
         {* display pagination header *}
        <p class="floatright">Datensätze {$curriculumList.first}-{$curriculumList.last} von {$curriculumList.total} werden angezeigt.</p>   
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
                    {if !isset($showCurriculumForm)}
                       <td class="td_1options">Optionen</td>
                    {/if}    
            </tr>
            
            {* display results *}    
            {section name=res loop=$resultscurriculumList}
                <tr {if isset($selectedID) AND $selectedID eq $resultscurriculumList[res]->id} class="activecontenttablerow"{/if} class="contenttablerow" id="row{$resultscurriculumList[res]->id}" name="row{$resultscurriculumList[res]->id}" onclick="checkrow({$resultscurriculumList[res]->id})">
                    <td><input class="invisible" type="checkbox" id="{$resultscurriculumList[res]->id}" name="id[]" value={$resultscurriculumList[res]->id} {if isset($selectedID) AND $selectedID eq $resultscurriculumList[res]->id} checked{/if}/></td>
                    {*<td>{$results[res].id}</td>*}
                    <td><img class="icon_tiny icon_listposition" src="{$subjects_url}{$resultscurriculumList[res]->filename}"></td>
                    <td>{$resultscurriculumList[res]->curriculum}</td>
                    <td>{$resultscurriculumList[res]->description}</td>
                    <td>{$resultscurriculumList[res]->subject}</td>
                    <td>{$resultscurriculumList[res]->grade}</td>
                    <td>{$resultscurriculumList[res]->schooltype}</td>
                    <td>{$resultscurriculumList[res]->state}</td>
                    <td>{$resultscurriculumList[res]->de}</td>
                    {if !isset($showCurriculumForm)}
                    <td class="td_1options">
                        <a class="deletebtn floatright" href="index.php?action=groups&function=expel_group&curriculumID={$resultscurriculumList[res]->id}&group_id={$selected_group_id}"></a>
                    </td>
                    {/if}
                </tr>
            {/section}
            
            </table>
            <input class="invisible" type="checkbox" name="id[]" value="none" checked /><!--Hack für problem, dass kein Array gepostet wird, wenn nichts angewählt wird-->
            {* display pagination info *}
            <p class="floatright">{paginate_first id="curriculumList"} {paginate_middle id="curriculumList"} {paginate_next id="curriculumList"}</p>
        {else if isset($showenroledCurriculum)}
            <p><strong>Die gewählte Lerngruppe ist in keinen Lehrplan eingeschrieben.</strong></p>
            <p>&nbsp;</p>
        {/if}
        
        
        {if isset($showenroledUsers)}
            <p><h3>Eingeschriebene Benutzer</h3></p>
    {if !isset($userResults)}
        <p>&nbsp;</p>
        <p><strong>In dieser Lerngruppe sind bisher keine Benutzer eingeschrieben.</strong></p>
        <p>&nbsp;</p>
    {/if}
        {if isset($userResults)}
            
            <p class="floatright">Datensätze {$userPaginator.first}-{$userPaginator.last} von {$userPaginator.total} werden angezeigt.</p>
                    <table id="contenttable">
                    <tr id="contenttablehead">
                            <td></td><td>Avatar</td>
                            <td>Benutzername</td>
                            <td>Vorname</td>
                            <td>Nachname</td>
                            <td>Email</td>
                            <td>PLZ</td>
                            <td>Ort</td>
                            <td>Bundesland</td>
                            <td>Land</td>
                            <td class="td_1options">Optionen</td>
                    </tr>
                    {section name=res loop=$userResults}
                        <tr class="{if isset($selectedUserID) AND $selectedUserID eq $userResults[res]->id} activecontenttablerow {else}contenttablerow{/if}" id="row{$smarty.section.res.index}">
                        <td><input class="invisible" type="checkbox" id="userID{$smarty.section.res.index}" name="userID" value={$userResults[res]->id} {if isset($selectedUserID) AND $selectedUserID eq $userResults[res]->id} checked{/if}/></td>
                        <td><img src="{$avatar_url}{$userResults[res]->avatar}" alt="Profilfoto" width="18"></td>
                        <td>{$userResults[res]->username}</td>
                        <td>{$userResults[res]->firstname}</td>
                        <td>{$userResults[res]->lastname}</td>
                        <td>{$userResults[res]->email}</td>
                        <td>{$userResults[res]->postalcode}</td>
                        <td>{$userResults[res]->city}</td>
                        <td>{$userResults[res]->state}</td>
                        <td>{$userResults[res]->country}</td>
                        <td class="td_1options">
                            <a class="deletebtn floatright" type="button" name="expelUser" onclick="expelUser({$selected_group_id},{$userResults[res]->id})"></a>
                        </td>
                        
                        </tr>
                    {/section}
                    </table>
            <p class="floatright">{paginate_prev id="userPaginator"} {paginate_middle id="userPaginator"} {paginate_next id="userPaginator"}</p>
        {/if} 
        <p>&nbsp;</p><p>&nbsp;</p>
        {/if}
</div>
        <script type='text/javascript'>
	document.getElementById('group').focus();
</script>
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}
