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
        
        <p class="floatleft gray-gradient cssimgbtn border-radius gray-border">
            <a class="addbtn cssbtnmargin cssbtntext" href="index.php?action=profileAdmin">Benutzer hinzufügen</a>
            <a class="addbtn cssbtnmargin cssbtntext" href="index.php?action=userImport&reset=true">Benutzerliste importieren</a>
        </p>
        <p>&nbsp;</p><p>&nbsp;</p>
        
        {if isset($results)}
        {* display pagination header *}
        <p class="floatright">Datensätze {$userPaginator.first}-{$userPaginator.last} von {$userPaginator.total} werden angezeigt.</p>
    <p>&nbsp;</p>
        <form id='userlist' method='post' action='index.php?action=user&next={$currentUrlId}'>
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
                        <td>Rolle</td>
                        <td>Optionen</td>
		</tr>
                {* display results *}    
                {section name=res loop=$results}
                    <tr class="contenttablerow" id="row{$results[res]->id}" onclick="checkrow({$results[res]->id})">
                       <td><input class="invisible" type="checkbox" id="{$results[res]->id}" name="id[]" value={$results[res]->id} /></td>
                       <td><img src="{$avatar_url}{$results[res]->avatar}" alt="Profilfoto" width="18"></td>
                       <td>{$results[res]->username}</td>
                       <td>{$results[res]->firstname}</td>
                       <td>{$results[res]->lastname}</td>
                       <td>{$results[res]->email}</td>
                       <td>{$results[res]->postalcode}</td>
                       <td>{$results[res]->city}</td>
                       <td>{$results[res]->state}</td>
                       <td>{$results[res]->country}</td>
                       <td>{$results[res]->role_name}</td>
                       <td>
                        <a class="deletebtn floatright" type="button" onclick="del('user',{$results[res]->id}, {$my_id})"></a>
                        <a class="editbtn floatright" href="index.php?action=profile&function=editUser&userID={$results[res]->id}"></a>
                        <a class="groupbtn floatright" href="index.php?action=user&function=showGroups&userID={$results[res]->id}"></a>
                        <a class="listbtn floatright" href="index.php?action=user&function=showCurriculum&userID={$results[res]->id}"></a>                 
                       </td>    
                    </tr>
                {/section}
		</table>
        {* display pagination info *}
        <p class="floatright">{paginate_prev id="userPaginator"} {paginate_middle id="userPaginator"} {paginate_next id="userPaginator"}</p>
        
        <input class="invisible" type="checkbox" name="id[]" value="none" checked /><!--Hack - nothing selected-->
        
        {if isset($showFunctions)}
        {if $showFunctions}    
        <p><h3>Lerngruppe</h3></p>
        <p>Markierte Benutzer in Lerngruppe ein bzw. ausschreiben</p>
        {if isset($groups_array)}
        <p><label>Lerngruppe:</label>
            <select name="groups">
                {section name=res loop=$groups_array}  
                <option label="{$groups_array[res]->group}" value={$groups_array[res]->id}> {$groups_array[res]->group} | {$groups_array[res]->semester} | {$groups_array[res]->institution}</option>
                {/section}
                </select>       
        <input type='submit' name='enroleGroups' value='einschreiben' />
        <input type='submit' name='expelGroups' value='ausschreiben' /></p>
        {else}<p>&nbsp;</p><p><strong>Sie müssen zuerst eine Lerngruppe anlegen</strong></p>{/if}
        <p>&nbsp;</p>
        <p><h3>Rolle</h3></p>
        <p><label>Benutzer-Rolle festlegen</label>
        <SELECT  name='roles' id='roles' />
            {foreach key=rolid item=rol from=$roles}
                <OPTION  value="{$rol->role_id}">{$rol->role}</OPTION>
            {/foreach} 
            </SELECT><input type='submit' name='setRole' value='Rolle zuweisen' /></p>
           
        <p>&nbsp;</p>
        <p><h3>Passwort zurücksetzen</h3></p>
        <p>Neues Passwort für markierte Benutzer festlegen. Passwort muss mind. 6 Zeichen lang sein. </p>
        <p><label>Neues Passwort:</label><input  type='password' name='password' id='password'  {if isset($password)}value='{$password}'{/if}/>
            <input type='submit' name='resetPassword' value='Passwort zurücksetzen' /></p>
        {validate_msg field='password'}
        <p><label>Passwort anzeigen: </label><input type="checkbox" class="centervertical" name='showpassword'  {if isset($inputshowpassword)}checked{/if} onclick="unmask('password', this.checked);"/></p>
        <p><label>Passwortänderung: </label><input type="checkbox" name='confirmed'  {if isset($inputconfirmed)}checked{/if}/></p>

        <p>&nbsp;</p>    
        <p><h3>Benutzer</h3></p>
        <p><label>Markierte Benutzer löschen</label><input type='submit' name='deleteUser' value='löschen' /></p>
	</form> 
        {else}<p><input type='submit' name="back" value='Funktionen einblenden'/></p>{/if}{/if}{/if}
        {if !isset($groups_array)}<p>Sie können noch keine Benutzer Verwalten, da sie entweder nicht über die nötigen Rechte verfügen, oder keine Benutzer in ihrer Institution vorhanden sind.</p><p>&nbsp;</p>{/if}
               
        {*Groups paginator*}
        {if isset($showenroledGroups)}
        {if isset($groups_list)}    
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
            {section name=res loop=$groups_list}
                <tr class="contenttablerow" id="row{$groups_list[res]->id}" onclick="checkrow({$groups_list[res]->id})">
                    <td><input class="invisible" type="checkbox" id="{$groups_list[res]->id}" name="id[]" value={$groups_list[res]->id} /></td>
                    {*<td>{$results[res]->id}</td>*}
                    <td>{$groups_list[res]->groups}</td>
                    <td>{$groups_list[res]->grade}</td>
                    <td>{$groups_list[res]->description}</td>
                    <td>{$groups_list[res]->semester}</td>
                    <td>{$groups_list[res]->institution}</td>
                    <td>{$groups_list[res]->creation_time}</td>
                    <td>{$groups_list[res]->creator}</td>
                    <td class="td_options">
                        <a class="deletebtn floatright" type="button" name="delete" onclick="expelUser({$groups_list[res]->id},{$selectedUserID})"></a>
                        </td>
                </tr>
            {/section}            
            </table>
                    <!--Hack für problem, dass kein Array gepostet wird, wenn nichts angewählt wird-->
            <input class="invisible" type="checkbox" name="id[]" value="none" checked />
            {* display pagination info *}
            <p class="floatright">{paginate_prev id="groupsPaginator"} {paginate_middle id="groupsPaginator"} {paginate_next id="groupsPaginator"}</p>
        {else}<p><strong>Der Benutzer ist in keiner Lerngruppe eingeschrieben.</strong></p><p>&nbsp;</p>{/if}{/if}
        {*Ende Groups paginator*}
             
        {*Curriculum paginator*}
        {if isset($curriculumList)}
        <p><h3>Lehrpläne des Benutzers</h3></p>
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
            </tr>
            
            {* display results *}    
            {section name=res loop=$resultscurriculumList}
                <tr {if isset($selectedID) AND $selectedID eq $resultscurriculumList[res]->id} class="activecontenttablerow"{/if} class="contenttablerow" id="row{$resultscurriculumList[res]->id}" name="row{$resultscurriculumList[res]->id}" onclick="checkrow({$resultscurriculumList[res]->id})">
                    <td><input class="invisible" type="checkbox" id="{$resultscurriculumList[res]->id}" name="id[]" value={$resultscurriculumList[res]->id} {if isset($selectedID) AND $selectedID eq $resultscurriculumList[res]->id} checked{/if}/></td>
                    {*<td>{$results[res]->id}</td>*}
                    <td><img class="icon_tiny icon_listposition" src="{$subjects_url}{$resultscurriculumList[res]->filename}"></td>
                    <td>{$resultscurriculumList[res]->curriculum}</td>
                    <td>{$resultscurriculumList[res]->description}</td>
                    <td>{$resultscurriculumList[res]->subject}</td>
                    <td>{$resultscurriculumList[res]->grade}</td>
                    <td>{$resultscurriculumList[res]->schooltype}</td>
                    <td>{$resultscurriculumList[res]->state}</td>
                    <td>{$resultscurriculumList[res]->de}</td>
                </tr>
            {/section}
            
            </table>
            <input class="invisible" type="checkbox" name="id[]" value="none" checked /><!--Hack für problem, dass kein Array gepostet wird, wenn nichts angewählt wird-->
            {* display pagination info *}
            <p class="floatright">{paginate_first id="curriculumList"} {paginate_middle id="curriculumList"} {paginate_next id="curriculumList"}</p>
        {else if isset($showenroledCurriculum)}
            <p><strong>Der gewählte Benutzer ist in keinen Lehrplan eingeschrieben.</strong></p>
            <p>&nbsp;</p>
        {/if}
        {*Ende Curriculum paginator*}
    </div>
</div>
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}