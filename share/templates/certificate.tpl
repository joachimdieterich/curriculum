{extends file="base.tpl"}

{block name=title}{$page_title}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}
<div class="border-box">
    <div class="contentheader">{$page_title}<input class="curriculumdocsbtn floatright" type="button" name="help" onclick="curriculumdocs('http://docs.joachimdieterich.de/index.php?title=Zertifikatvorlage_einrichten');"/></div>
    {if !isset($showForm) && checkCapabilities('certificate:add', $my_role_id, false)}
        <p class="floatleft  cssimgbtn gray-border">
            <a class="addbtn cssbtnmargin cssbtntext" href="index.php?action=certificate&function=new">Zertifikat hinzufügen</a>
        </p>
    {else}
        <form id='addCertificate' method='post' action='index.php?action=certificate'>
        <input id='id' name='id' type='hidden' {if isset($id)}value='{$id}'{/if} /></p>   
        <p><label>Name des Zertifikat-Vorlage</label><input class='inputlarge' name='certificate' id='certificate' {if isset($certificate)}value='{$certificate}'{/if} /></p>   
        {validate_msg field='certificate'}
        <p><label>Beschreibung</label><input class='inputlarge' name='description' {if isset($description)}value='{$description}'{/if}/></p>
        {validate_msg field='description'}
         <p><label>Institution / Schule*:</label><SELECT  name='institution_id' id='institution_id' />
            {foreach key=insid item=ins from=$my_institutions}
                <OPTION  value="{$ins->institution_id}"  {if $ins->institution_id eq $institution_id}selected="selected"{/if}>{$ins->institution}</OPTION>
            {/foreach} 
        </SELECT></p>
        <p>In diesem Editor können sie das Zertifikat einrichten. Die folgenden Felder dürfen / müssen dabei verwendet werden. 
            Felder mit * sind obligatorisch. </br>Die &lt;!--Start--&gt; und &lt;!--Ende--&gt; Feld legen fest welcher Bereich abhängig von den vorhandenen Zielen immer wieder wiederholt wird. </p>    
        <p>*&lt;!--Vorname--&gt;</br> 
           *&lt;!--Nachname--&gt;</br>
           *&lt;!--Start--&gt;</br>
           *&lt;!--Ende--&gt</br>
            &lt;!--Ort--&gt;</br>
            &lt;!--Datum--&gt;</br>                
            &lt;!--Unterschrift--&gt;</br>
            &lt;!--Thema--&gt;</br>
            &lt;!--Ziel--&gt;</br>
            &lt;!--Ziel_mit_Hilfe_erreicht--&gt;</br>
            &lt;!--Ziel_erreicht--&gt;</br>
            &lt;!--Ziel_offen--&gt;</br>
            &lt;!--Bereich{literal}{{/literal}terminal_objective_id,...{literal}}{/literal}--&gt;HTML&lt;!--/Bereich--&gt;</br>
            &lt;!--Unterschrift--&gt;
        <p><textarea id="tmce_editor" name="template">{if isset($template)}{$template}{/if}</textarea></p>  
        {validate_msg field='template'}
        <script type='text/javascript'>document.getElementById('certificate').focus();</script>

        {if !isset($editBtn)}
            <p><label></label><input type='submit' name="back" value='zurück'/><input type='submit' name="addCertificate" value='Vorlage hinzufügen' /></p>
        {else}
            <p><label></label><input type='submit' name="back" value='zurück'/><input type='submit' name="updateCertificate" value='Vorlage aktualisieren' /></p>
        {/if}
        </form>	
    {/if}    
    
    {html_paginator id='certificateP' values=$ct_val config=$certificateP_cfg}
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}