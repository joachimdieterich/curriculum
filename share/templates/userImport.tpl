{extends file="base.tpl"}

{block name=title}{$page_title}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}
    
<div class="border-box">
    <div class="contentheader">{$page_title}<input class="curriculumdocsbtn floatright" type="button" name="help" onclick="curriculumdocs('http://docs.joachimdieterich.de/index.php?title=Benutzerliste_importieren');"/></div>
    <div class="space-top">
        <form name="file" enctype="multipart/form-data" action="index.php?action=userImport" method="post">
            <p><h3>Nutzerkonten per CSV-Datei hochladen.</h3></p>
            <p>Die CSV-Datei muss folgendes Format haben:</p>
            <p>- Die ersten Zeile muss die Schlüsselwerte enthalten (z.B.:username, password, firstname, lastname, email, role_id, confirmed, postalcode, city, state, country)</p>
            <p>- Die Schüsselwerte <strong>username, password, firstname, lastname </strong>und <strong>email</strong> müssen gesetzt werden. </p>
            <p>- Wird keine Benutzer-Rolle festgelegt (role_id) wird die Standard-Rolle der Institution verwendet.</p>
            <p>- Die maximale Dateigröße liegt bei {$filesize}MB und kann im Adminstrationsbereich festgelegt werden.</p>
            <p>- Die Datei muss im utf-8 Format gespeichert werden, sonst werden Umlaute und Sonderzeichen nicht korrekt importiert</p>
            <p><label>Institution / Schule*:</label><SELECT  name='institution_id' id='institution_id' />
            {foreach key=insid item=ins from=$my_institutions}
                <OPTION  value="{$ins->institution_id}"  {if $ins->institution_id eq $institution_id}selected="selected"{/if}>{$ins->institution}</OPTION>
            {/foreach} 
            </SELECT></p>
            <p><h3>CSV-Vorlagen</h3></p>
            <p class="space-top" style="height:64px;"><a class="url_btn floatleft" href="{$support_path}Vorlage-min.csv"> </a> Vorlage (Minimal - nur benötigte Felder)</p>
            <p class="space-top" style="height:64px;"><a class="url_btn floatleft" href="{$support_path}Vorlage-max.csv"> </a> Vorlage (Alle möglichen Felder)</p>
            <p><h3>CSV-Datei hochladen</h3></p>
            <p><input name="datei" type="file" value=""><input type="submit" value="Importieren"> </p>
        </form>
        {if isset($nusr_val)}
            {html_paginator id='newUsersPaginator' values=$nusr_val config=$newUsersPaginator_cfg}     
        {/if}
    </div>
</div>
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}