{extends file="base.tpl"}

{block name=title}{$page_title}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}
<meta http-equiv="refresh" content="5; url=http://domain.com/path/to/download" />
{block name=additional_scripts}{$smarty.block.parent}{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}
    
<div class="border-box">
    <div class="contentheader ">{$page_title}</div>
    <div>
	
        <form method='post' action='index.php?action=install'>   
            <input type='hidden' name='step' id='step' {if isset($step)}value='{$step}'{/if} />   
            {if $step == 0}
            {*Serverdaten-Datenbank *}
                <p>&nbsp;</p>
                <p><h3>CURRICULUM - Copyright notice</h3></p>
            <p>Copyright © 2012 onwards Joachim Dieterich (http://www.joachimdieterich.de)<br><br></p>
            <p>This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License <br>
               as published by the Free Software Foundation; either version 3 of the License, or any later version.<br><br>
               This program is distributed in the hope that it wil be useful, but WITHOUT ANY WARRANTY; <br>
               without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.<br><br></p>
            <p>See the curriculum License information page for full details: http://www.joachimdieterich.de<br><br>
            <p><label>Installieren?</label><input class="centervertical" type="checkbox" name="license"/></p><p>Bitte bestätigen sie, dass sie die Lizenzbedingungen gelesen und verstanden haben.</p>
            <p><label>&nbsp;</label><input type='submit' name='step_0' value='weiter' /></p>
            {/if}
            {if $step == 1}
            {*Serverdaten-Datenbank *}
                <p>&nbsp;</p>
                <p><h3>Datenbank</h3></p>
                <p><label>DB Host*: </label><input name='db_host'{if isset($db_host)}value='{$db_host}'{/if}/><label>= localhost</label></p>
                {validate_msg field='db_host'}
                <p><label>DB User*: </label><input name='db_user'{if isset($db_user)}value='{$db_user}'{/if}/></p>
                {validate_msg field='db_user'}
                <p><label>DB Password*: </label><input name='db_password'{if isset($db_password)}value='{$db_password}'{/if}/></p>
                {validate_msg field='db_password'}
                <p><label>DB Name*: </label><input name='db_name'{if isset($db_name)}value='{$db_name}'{/if}/></p>
                {validate_msg field='db_name'}
                {*<p><label>Bestehende Daten sichern?</label><input class="centervertical" type="checkbox" name="dump"/>!Achtung! nachdem die Datei automatisch herunter geladen wurde, müssen sie erneut auf "weiter" klicken!</p>*}{*realized but useless yet*}
                <p><label>&nbsp;</label><input type='submit' name='step_1' value='weiter' /></p>
            {/if}
        
            {if $step == 2}
                {*Serverdaten*}
                <p>&nbsp;</p>
                <p><h3>Titel und URL</h3></p>
                <p><label>Name der Seite*: </label><input name='app_title'{if isset($app_title)}value='{$app_title}'{/if}/></p>
                {validate_msg field='app_title'}
                <p><label>Beispieldaten installieren</label><input class="centervertical" type="checkbox" name="demo"/></p>{*not yet available - dedication incorrect*}
                <p><label>&nbsp;</label><input type='submit' name='step_2' value='weiter' /></p>
            {/if}
        
            {if $step == 3}
                {*Serverdaten*}
                <p>&nbsp;</p>
                <p><h3>Institution</h3></p>
                <input type='hidden' name='demo' id='demo' {if isset($demo)}value='{$demo}'{/if} />   
                <p><label>Institution / Schule*: </label><input class='inputlarge' type='text' name='institution' id='institution' {if isset($institution)}value='{$institution}'{/if} /></p> 
                {validate_msg field='institution'}
                <p><label>Beschreibung*: </label><input class='inputlarge' type='institution_description' name='institution_description' {if isset($institution_description)}value='{$institution_description}'{/if}/></p>
                {validate_msg field='institution_description'}
                <p id="schooltype_list"><label>Schultyp: </label><select name="schooltype_id" >
                    {section name=res loop=$schooltype}  
                        <option value={$schooltype[res]->id}>{$schooltype[res]->schooltype}</option>
                    {/section}
                    </select></p>  
                <p><label>Anderer Schultyp... </label><input class="centervertical" type="checkbox" name='btn_newSchooltype' value='Neuen Schultyp anlegen' onclick="checkbox_addForm(this.checked, 'inline', 'newSchooltype', 'schooltype_list')"/></p>
                <div id="newSchooltype" style="display:none;">
                    <p><label>Schultyp: </label><input class='inputlarge' type='text' name='new_schooltype' id='schooltype_id' {if isset($new_schooltype)}value='{$new_schooltype}'{/if} /></p> 
                    <p><label>Beschreibung: </label><input class='inputlarge' type='text' name='schooltype_description' {if isset($schooltype_description)}value='{$schooltype_description}'{/if}/></p>
                </div>
                <p><label>Land: </label><select name="country" onchange="loadStates(this.value);">
                    {section name=res loop=$countries}  
                        <option label={$countries[res]->de} value={$countries[res]->id}>{$countries[res]->de}</option>
                    {/section}
                </select></p>
                            
                <p id="states">
                    {* content is loaded via ajax --> request.php!
                    <SELECT name='state' id='state_id' />
                    {foreach key=s_id item=sta from=$state}
                        <OPTION {if $sta[$s_id]->id eq (1+{$standardstate})}selected{/if} value="{$sta[$s_id]->id}">{$sta[$s_id]->state}</OPTION>
                    {/foreach} 
                    </SELECT>*}
                </p>
                <p><label>&nbsp;</label><input type='submit' name='step_3' value='weiter' /></p>
            {/if}

            {if $step == 4}
            {*Admindaten*}
            <p>&nbsp;</p>
            <p><h3>Administrator</h3></p>
            <input type='hidden' name='institution_id' id='institution_id' {if isset($institution_id)}value='{$institution_id}'{/if} />   
            <p><label>Benutzername*:</label><input id='username' name='username' {if isset($username)}value='{$username}'{/if} /></p>
            {validate_msg field='username'}
            <p><label>Vorname*: </label><input name='firstname'{if isset($firstname)}value='{$firstname}'{/if}/></p>
            {validate_msg field='firstname'}
            <p><label>Nachname*: </label><input name='lastname'{if isset($lastname)}value='{$lastname}'{/if}/></p>
            {validate_msg field='lastname'}
            <p><label>Email*: </label><input name='email'{if isset($email)}value='{$email}'{/if}/></p>
            {validate_msg field='email'}
            <p><label>PLZ*: </label><input name='postalcode'{if isset($postalcode)}value='{$postalcode}'{/if}/></p>
            {validate_msg field='postalcode'}
            <p><label>Ort*: </label><input name='city' {if isset($city)}value='{$city}'{/if}/></p>
            {validate_msg field='city'}
            <p><label>Land: </label><select name='country' onchange="loadStates(this.value);">
                    {section name=res loop=$countries}  
                        <option label={$countries[res]->de} value={$countries[res]->id}>{$countries[res]->de}</option>
                    {/section}
                </select></p>
                            
                <p id="states">
                    {* content is loaded via ajax --> request.php!
                    <SELECT name='state' id='state_id' />
                    {foreach key=s_id item=sta from=$state}
                        <OPTION {if $sta[$s_id]->id eq (1+{$standardstate})}selected{/if} value="{$sta[$s_id]->id}">{$sta[$s_id]->state}</OPTION>
                    {/foreach} 
                    </SELECT>*}
                </p>
            <p><label>Passwort*: </label></td><td><input type="password" name='password' {if isset($password)}value='{$password}'{/if}/></p>                                       
            {validate_msg field='password'}
                <p><label>&nbsp;</label><input type='submit' name='step_4' value='weiter' /></p>
            {/if}
                   
            {if $step == 5}
            {*Finished*}
            <p>&nbsp;</p>
            <p><h3>Installation abgeschlossen</h3></p>
            <p>Die Installation wurde erfolgreich abgeschlossen.</p> 
            <p>Bitte löschen sie die Datei /share/controllers/install.php !</p>
            <p>Mit dem Button gelangen sie zum Login.</p>
            
            <p><label>&nbsp;</label><input type='submit' name='step_5' value='Zum Login' /></p>
            {/if}
        </form>
    </div>
</div>  <script src="{$media_url}scripts/script.js"></script>
        <script type='text/javascript'>
	document.getElementById('username').focus();
	</script>
            
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}
