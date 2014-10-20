{extends file="base.tpl"}

{block name=title}{$page_title}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}
    
<div class="border-box">
    <div class="contentheader ">{$page_title}</div>
    <div>
        
        <p class="floatleft  cssimgbtn gray-border">
            {if checkCapabilities('user:addUser', $my_role_id, false)}
                <a class="addbtn cssbtnmargin cssbtntext" href="index.php?action=profileAdmin">Benutzer hinzufügen</a>
            {/if}
            {if checkCapabilities('menu:readuserImport', $my_role_id, false)}
                <a class="addbtn cssbtnmargin cssbtntext" href="index.php?action=userImport&reset=true">Benutzerliste importieren</a>
            {/if}
        </p>
        <p>&nbsp;</p><p>&nbsp;</p>
        
        {if isset($results)}
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
                        <tr class="contenttablerow {if isset($selectedUserID) AND $results[res]->id eq $selectedUserID}activecontenttablerow{/if}" id="row{$results[res]->id}" onclick="checkrow({$results[res]->id})">
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
                            {if checkCapabilities('user:delete', $my_role_id, false)}   
                                <a class="deletebtn floatright" type="button" onclick="del('user',{$results[res]->id}, {$my_id})"></a>
                            {else}
                                 <a class="deletebtn deactivatebtn floatright" type="button"></a>
                            {/if}    
                            {if checkCapabilities('user:updateUser', $my_role_id, false)}   
                                <a class="editbtn floatright" href="index.php?action=profile&function=editUser&userID={$results[res]->id}"></a>
                            {else}
                                <a class="editbtn deactivatebtn floatright" ></a>
                            {/if}
                            {if checkCapabilities('user:getGroups', $my_role_id, false)}
                                <a class="groupbtn floatright" href="index.php?action=user&function=showGroups&userID={$results[res]->id}"></a>
                            {else}
                                <a class="groupbtn deactivatebtn floatright"></a>
                            {/if}
                            {if checkCapabilities('user:getCurricula', $my_role_id, false)}
                                <a class="listbtn floatright" href="index.php?action=user&function=showCurriculum&userID={$results[res]->id}"></a>
                            {else}
                                <a class="listbtn deactivatebtn floatright" ></a>
                            {/if}
                            {if checkCapabilities('user:getInstitution', $my_role_id, false)}
                                <a class="institutionbtn floatright" href="index.php?action=user&function=showInstitution&userID={$results[res]->id}"></a>
                            {else}
                                <a class="institutionbtn deactivatebtn floatright" ></a>
                            {/if}
                        </td>    
                        </tr>
                    {/section}
                    </table>
            {* display pagination info *}
            <p class="floatright">{paginate_prev id="userPaginator"} {paginate_middle id="userPaginator"} {paginate_next id="userPaginator"}</p>

            <input class="invisible" type="checkbox" name="id[]" value="none" checked /><!--Hack - nothing selected-->

            {if isset($showFunctions)}
            {if $showFunctions}    
                {if checkCapabilities('user:enroleToGroup', $my_role_id, false) OR checkCapabilities('user:expelFromGroup', $my_role_id, false)}
                <p><h3>Lerngruppe</h3></p>
                <p>Markierte Benutzer in Lerngruppe ein bzw. ausschreiben</p>
                {if isset($groups_array)}
                    <p><label>Lerngruppe:</label>
                        <select name="groups">
                            {section name=res loop=$groups_array}  
                            <option label="{$groups_array[res]->group}" value={$groups_array[res]->id}> {$groups_array[res]->group} | {$groups_array[res]->semester} | {$groups_array[res]->institution}</option>
                            {/section}
                            </select>  </p> 
                    <p><label></label>
                    {if checkCapabilities('user:enroleToGroup', $my_role_id, false)}
                        <input type='submit' name='enroleGroups' value='einschreiben' />
                    {/if}
                    {if checkCapabilities('user:expelFromGroup', $my_role_id, false)}
                        <input type='submit' name='expelGroups' value='ausschreiben' />
                    {/if} 
                    </p>
                    {else}<p>&nbsp;</p><p><strong>Sie müssen zuerst eine Lerngruppe anlegen</strong></p>{/if}
                {/if}
                
                {if checkCapabilities('user:enroleToInstitution', $my_role_id, false) OR checkCapabilities('user:expelFromInstitution', $my_role_id, false)}
                <p><h3>Institution</h3></p>
                <p>Markierte Benutzer in Institution ein bzw. ausschreiben</p>
                    {if isset($myInstitutions)}
                        <p><label>Institution / Schule: </label>
                            <select name="institution" >
                                {section name=res loop=$myInstitutions}  
                                    <option label="{$myInstitutions[res]->institution}" value={$myInstitutions[res]->id}>{$myInstitutions[res]->institution}</option>
                                {/section}
                        </select> 
                        <p><label></label>
                        {if checkCapabilities('user:enroleToInstitution', $my_role_id, false)}
                            <input type='submit' name='enroleInstitution' value='einschreiben' />
                        {/if}
                        {if checkCapabilities('user:expelFromInstitution', $my_role_id, false)}
                            <input type='submit' name='expelInstitution' value='ausschreiben' />
                        {/if} 
                        </p>
                    {else}<p><strong>Sie müssen zuerst eine Institution anlegen</strong></p>{/if}
                {/if}    

                {if checkCapabilities('user:updateRole', $my_role_id, false)}
                <p>&nbsp;</p>
                <p><h3>Rolle</h3></p>
                <p><label>Benutzer-Rolle festlegen</label>
                <SELECT  name='roles' id='roles' />
                    {foreach key=rolid item=rol from=$roles}
                        <OPTION  value="{$rol->role_id}">{$rol->role}</OPTION>
                    {/foreach} 
                    </SELECT><input type='submit' name='setRole' value='Rolle zuweisen' /></p>
                {/if} 

                {if checkCapabilities('user:resetPassword', $my_role_id, false)}
                <p>&nbsp;</p>
                <p><h3>Passwort zurücksetzen</h3></p>
                <p>Neues Passwort für markierte Benutzer festlegen. Passwort muss mind. 6 Zeichen lang sein. </p>
                <p><label>Neues Passwort:</label><input  type='password' name='password' id='password'  {if isset($password)}value='{$password}'{/if}/>
                    <input type='submit' name='resetPassword' value='Passwort zurücksetzen' /></p>
                {validate_msg field='password'}
                <p><label>Passwort anzeigen: </label><input type="checkbox" class="centervertical" name='showpassword'  {if isset($inputshowpassword)}checked{/if} onclick="unmask('password', this.checked);"/></p>
                <p><label>Passwortänderung: </label><input type="checkbox" name='confirmed'  {if isset($inputconfirmed)}checked{/if}/></p>
                {/if}

                {if checkCapabilities('user:delete', $my_role_id, false)}
                <p>&nbsp;</p>    
                <p><h3>Benutzer</h3></p>
                <p><label>Markierte Benutzer löschen</label><input type='submit' name='deleteUser' value='löschen' /></p>
                {/if}
            </form> 
            
        {else}<p><input type='submit' name="back" value='Funktionen einblenden'/></p>{/if}{/if}{/if}
        {if !isset($groups_array)}<p>Sie können noch keine Benutzer verwalten, da sie entweder nicht über die nötigen Rechte verfügen, oder keine Benutzer in ihrer Institution vorhanden sind.</p><p>&nbsp;</p>{/if}
               
        {*Groups paginator*}
        {if isset($showenroledGroups)}
        {if isset($groups_list)}    
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
                    <td>Optionen</td>
            </tr>  
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
                        {if checkCapabilities('user:expelFromGroup', $my_role_id, false)}
                            <a class="deletebtn floatright" type="button" name="delete" onclick="expelUser({$groups_list[res]->id},{$selectedUserID})"></a>
                        {else}
                            <a class="deletebtn deactivatebtn floatright" type="button"></a>
                        {/if}
                        </td>
                </tr>
            {/section}            
            </table>
            <input class="invisible" type="checkbox" name="id[]" value="none" checked /><!--Hack für problem, dass kein Array gepostet wird, wenn nichts angewählt wird-->
            <p class="floatright">{paginate_prev id="groupsPaginator"} {paginate_middle id="groupsPaginator"} {paginate_next id="groupsPaginator"}</p>
        {else}<p><strong>Der Benutzer ist in keiner Lerngruppe eingeschrieben.</strong></p><p>&nbsp;</p>{/if}{/if}
        {*Ende Groups paginator*}
             
        {*Curriculum paginator*}
        {if isset($curriculumList)}
        <p><h3>Lehrpläne des Benutzers</h3></p>
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
            <p class="floatright">{paginate_first id="curriculumList"} {paginate_middle id="curriculumList"} {paginate_next id="curriculumList"}</p>
        {else if isset($showenroledCurriculum)}
            <p><strong>Der gewählte Benutzer ist in keinen Lehrplan eingeschrieben.</strong></p>
            <p>&nbsp;</p>
        {/if}
        {*end curriculum paginator*}
        
        {*Institution paginator*}
        {if isset($institutionList)}
        <p><h3>Institutionen des Benutzers</h3></p>
        <p class="floatright">Datensätze {$institutionList.first}-{$institutionList.last} von {$institutionList.total} werden angezeigt.</p>   
            <table id="contenttable">
                    <tr id="contenttablehead">
                    <td>Institution</td>
                    <td>Beschreibung</td>
                    <td>Schultyp</td>
                    <td>Bundesland/Region</td>
                    <td>Land</td>
                    <td>Erstellungsdatum</td>
                    <td>Administrator</td>    
                    <td>Optionen</td>    
            </tr>   
            {section name=res loop=$resultsinstitutionList}
                <tr class="contenttablerow" id="row{$resultsinstitutionList[res]->id}" name="row{$resultsinstitutionList[res]->id}" onclick="checkrow({$resultsinstitutionList[res]->id})">
                    <td>{$resultsinstitutionList[res]->institution}</td>
                    <td>{$resultsinstitutionList[res]->description}</td>
                    <td>{$resultsinstitutionList[res]->schooltype_id}</td>
                    <td>{$resultsinstitutionList[res]->state_id}</td>
                    <td>{$resultsinstitutionList[res]->country}</td>
                    <td>{$resultsinstitutionList[res]->creation_time}</td>
                    <td>{$resultsinstitutionList[res]->creator_id}</td>
                    <td>{if checkCapabilities('user:expelFromInstitution', $my_role_id, false)}
                            <a class="deletebtn floatright" type="button" name="delete" onclick="expelFromInstituion({$resultsinstitutionList[res]->id},{$selectedUserID})"></a>
                        {else}
                            <a class="deletebtn deactivatebtn floatright" type="button"></a>
                        {/if}
                    </td>
                </tr>
            {/section}
            
            </table>
            <input class="invisible" type="checkbox" name="id[]" value="none" checked /><!--Hack für problem, dass kein Array gepostet wird, wenn nichts angewählt wird-->
            <p class="floatright">{paginate_first id="institutionList"} {paginate_middle id="institutionList"} {paginate_next id="institutionList"}</p>
        {else if isset($showenroledInstitution)}
            <p><strong>Der gewählte Benutzer ist in keine Institution eingeschrieben.</strong></p>
            <p>&nbsp;</p>
        {/if}
        {*end institution paginator*}
    </div>
</div>
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}