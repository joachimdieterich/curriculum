{extends file="base.tpl"}

{block name=title}Systemeinstellungen von curriculum{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}
    
<div class=" border-radius gray-border">	
    <div class="border-top-radius contentheader ">Systemeinstellungen von curriculum</div>
    <div class="space-top-padding gray-gradient border-bottom-radius box-shadow ">
        <form method='post' action='index.php?action=config'>
            <p><h3>Persönliche Einstellungen</h3></p>
            <p>&nbsp;</p>
            
            <input type='hidden' name='user_id' id='user_id' {if isset($user_id)}value='{$user_id}'{/if} /> 
            
            <p><h3>Sprache</h3></p>
            <p >Legt die Menüsprache von curriculum fest</p>
            <p><label>Sprache: </label>
                    <SELECT name='user_language' id='user_language' />
                    {foreach key=langid item=lan from=$language}
                        <OPTION {if $lan.dir eq {$user_language}}selected{/if} value="{$lan.id}">{$lan.dir}</OPTION>
                    {/foreach} 
                    </SELECT></p>
                    {validate_msg field='user_language'}<p>&nbsp;</p>
            <p><h3>Erscheinungsbild</h3></p>
            <p>Legt fest nach wie viele Einträge in einer Liste sind</p>
            <p><label>Anzahl: </label><input type='number' min="5" max="150" name='user_paginator_limit' id='user_paginator_limit' value={$user_paginator_limit} /></p>
            {validate_msg field='user_paginator_limit'}<p>&nbsp;</p>
            <p><h3>Dashboard</h3></p>
            <p >Legt fest wie viel Tage die zuletzt erreichten Ziele angezeigt werden.</p>
            <p><label>Anzahl: </label><input type='number' min="1" max="365" name='user_acc_days' id='user_acc_days' value={$user_acc_days} /></p>
            {validate_msg field='user_acc_days'}<p>&nbsp;</p><p>&nbsp;</p>
            {*Admin*}
            {if checkCapabilities('menu:readInstitutionConfig', $my_role_id)}
                <input type='hidden' name='institution_id' id='institution_id' {if isset($institution_id)}value='{$institution_id}'{/if} />     
                <p><h3>Einstellungen für Institution</h3></p>
                <p>&nbsp;</p>
                <p><h3>Erscheinungsbild</h3></p>
                <p>Legt fest nach wie viele Einträge in einer Liste sind</p>
                <p><label>Anzahl: </label><input type='number' min="5" max="150" name='institution_paginator_limit' id='institution_paginator_limit' value={$institution_paginator_limit} /></p>
                {validate_msg field='institution_paginator_limit'}<p>&nbsp;</p>
                <p><h3>Dashboard</h3></p>
                <p>Legt fest wie viel Tage die zuletzt erreichten Ziele angezeigt werden.</p>
                <p><label>Anzahl: </label><input type='number' min="1" max="365" name='institution_acc_days' id='institution_acc_days' value={$institution_acc_days} /></p>
                {validate_msg field='institution_acc_days'}<p>&nbsp;</p>
                <p><label>Timeout (Minuten): </label><input type='number' min="1" max="240" name='institution_timeout' id='institution_timeout' value={$institution_timeout} /></p>
                {validate_msg field='institution_timeout'}<p>&nbsp;</p>
                <p><h3>Sprache</h3></p>
                <p >Legt die Menüsprache von curriculum fest</p>
                <p><label>Sprache: </label>
                        <SELECT name='institution_language' id='institution_language' />
                        {foreach key=langid item=lan from=$language}
                            <OPTION {if $lan.dir eq {$institution_language}}selected{/if} value="{$lan.id}">{$lan.dir}</OPTION>
                        {/foreach} 
                        </SELECT></p>
                {validate_msg field='institution_language'}<p>&nbsp;</p>
                <p><h3>Rollen</h3></p>
                <p><label>Standard-Rolle: </label>
                        <SELECT class="centervertical" name='institution_std_role' id='institution_std_role' />
                        {foreach key=rolid item=rol from=$roles}
                            <OPTION {if $rol->role_id eq $institution_standard_role}selected{/if} value="{$rol->role_id}">{$rol->role}</OPTION>
                        {/foreach} 
                        </SELECT></p> 
                {validate_msg field='institution_std_role'}<p>&nbsp;</p>        
                <p><h3>Landeseinstellungen</h3></p>
                <p><label>Land: </label><select name="institution_standard_country" onchange="loadStates(this.value, 'institution_standard_state', {$institution_standard_state});">
                    {section name=res loop=$countries}  
                        <option label={$countries[res]->de} {if $countries[res]->id eq $institution_standard_country}selected{/if}  value={$countries[res]->id}>{$countries[res]->de}</option>
                    {/section}
                </select></p>
                                          
                <p id="states"><label>Bundesland: </label><select name="institution_standard_state">
                    {section name=s_id loop=$states}
                        <OPTION label="{$states[s_id]->state}" value="{$states[s_id]->id}" {if $states[s_id]->id eq $institution_standard_state}selected{/if}>{$states[s_id]->state}</OPTION>
                    {/section}   
                </select></p>                     
                {validate_msg field='institution_standard_state'}<p>&nbsp;</p>
                <p><h3>Dateien und Pfade</h3></p>
                <p>Legt fest wie groß Dateien beim Upload sein dürfen.</p>
                <p> Maximale Uploadgröße dieses Servers = {$post_max_size} ({$byte} Byte)</p>
                <p>&nbsp;</p>
                <p><label>csv-Dateien: </label><input type='number' min="5000" max="1048576" name='institution_csv_size' id='institution_csv_size' value={$institution_csv_size} /> csv-Dateien für den Benutzerimport</p>
                {validate_msg field='institution_csv_size'}
                <p><label>Profilfotos: </label><input type='number' min="5000" max="1048576" name='institution_avatar_size' id='institution_avatar_size' value={$institution_avatar_size} /> Bild-Dateien für das Profilfoto</p>
                {validate_msg field='institution_avatar_size'}
                <p><label>sonst. Dateien: </label><input type='number' min="5000" max="1048576" name='institution_material_size' id='institution_material_size' value={$institution_material_size} /> Dateien für Materialupload</p>
                {validate_msg field='institution_material_size'}
                <p><h3>&nbsp;</h3></p>
            {/if}
                <p><label></label>
                    <input type='submit' value='Konfiguration speichern' /></p>
	</form>	

	<script type='text/javascript'>
	document.getElementById('paginatorLimit').focus();
	</script>

    </div>
</div>
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}
