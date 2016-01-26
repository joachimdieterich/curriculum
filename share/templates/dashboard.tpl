{extends file="base.tpl"}

{block name=title}{$page_title}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}
    
<div class="border-box">
    <div class="contentheader">Startseite<input class="curriculumdocsbtn floatright" type="button" name="help" onclick="curriculumdocs('http://docs.joachimdieterich.de/index.php?title=Startseite');"/></div>
        <div class="colIII space-top">
            <p><h3>Erfolge</h3></p>
            {if isset($enabledObjectives)} 
            <p>Hier siehst du, welche Ziele du in den letzten <strong>{$my_acc_days}</strong> Tagen erreicht hast.</p>
                {foreach key=enaid item=ena from=$enabledObjectives}
                    <div class="card3 boxgreen">
                        <div>
                            {$ena->curriculum}<span>{$ena->accomplished_teacher}</span>
                        </div>
                        <div>
                            {$ena->enabling_objective|truncate:100}<!--{$ena->description}-->
                        </div> 
                   </div>
                {/foreach}
            {else}<p>In den letzten <strong>{$my_acc_days}</strong> Tagen hast du keine Ziele abgeschlossen.</p>{/if}
        </div>

        <div class="colIII space-top">
            <p><h3>Nachrichten</h3></p>
            {if isset($mails)} 
                {section name=mes loop=$mails}
                    <p class="notificationContent">
                        <img src="{$access_file}{$mails[mes]->sender_file_id|resolve_file_id:"xs"}"/>
                        <a href="index.php?action=messages&function=showInbox&id={$mails[mes]->id}">{$mails[mes]->subject}</a> ({$mails[mes]->sender_username})<br>
                        {strip_tags($mails[mes]->message|truncate:100:"...":true)}</p>
                {/section}
            {else}<p>Keine ungelesenen Nachrichen vorhanden.</p>{/if}
        </div>            
        <div class="colIII space-top">
            <p><h3>Pinnwand{if checkCapabilities('dasboard:editBulletinBoard', $my_role_id, false)}
                <a class="editbtn floatright" onclick="editBulletinBoard();"></a>
            {/if}</h3></p>
            {if $bulletinBoard}
                <p><strong>{$bulletinBoard->title}</strong><br>{$bulletinBoard->text}</p>
            {/if}
        </div>            

        <div class="colIII space-top">
            <p><h3>Meine Lerngruppen / Klassen</h3></p>
            {if isset($myClasses)} 
                {foreach key=claid item=cla from=$myClasses}
                    <div class="card2">
                         <div> {*Header*}
                            <strong>{$cla->group} ({$cla->grade})</strong><br>
                            {$cla->description} <br>
                            {$cla->institution_id|truncate:50}
                        </div>
                        <div> {*Content*}
                            <strong>Lehrpläne</strong><br>
                            {foreach item=cur_menu from=$my_enrolments}
                                {if $cur_menu->group_id eq $cla->id}
                                    <a href="index.php?action=view&curriculum={$cur_menu->id}&group={$cur_menu->group_id}">{$cur_menu->curriculum}</a><br>
                                {/if}
                            {/foreach}
                        </div>
                    </div>
                {/foreach}        
            {else}<p>Sie sind in keiner Institution / Schule eingeschrieben.</p>{/if} 
        </div>

        <div class="colIII space-top">
            <p><h3>Meine Institutionen / Schulen</h3></p>
            {if isset($myInstitutions)} 
                {foreach key=insid item=ins from=$myInstitutions}
                    <div class="card1">
                        <div>
                            <img src="{$access_file}{$ins->file_id|resolve_file_id}">
                        </div>
                        <div>
                            <strong>{$ins->institution}</strong><br>
                            {$ins->schooltype_id}<br><br>
                            {$ins->description}<br>

                            {$ins->state_id}, {$ins->country}<br>
                            {*{$ins->creation_time}<br>*}
                            {$ins->creator_id}
                        </div>
                    </div>
                {/foreach}
            {else}<p>Sie sind in keiner Institution / Schule eingeschrieben.</p>{/if} 
        </div>

         <div class="colIII space-top">
            {if checkCapabilities('page:showCronjob', $my_role_id, false)}
                <p><h3>Abgelaufene Ziele</h3></p>
                <p>{*$cronjob*}</p>
            {/if}
        </div>

        <div class="colIII space-top">
            <p><h3>Hilfe</h3></p>
            <p class="center"><a href="http://docs.joachimdieterich.de"><img src="{$media_url}/images/wiki.png"></a></p>
        </div>    
        <div style="clear: both;"></div>
        
        <div class="colIII space-top">
            <p><h3>Allgemeine Informationen</h3></p>
        <p><strong>Datenschutzerklärung und Nutzungsbedingungen</strong><br>
            Die Datenschutzerklärung und Nutzungsbedingungen für diese Lernplattform können Sie <a href="{$media_url}/docs/curriculum_Terms_Of_Use_2015.pdf">hier</a> einsehen. <br><br>
            <strong>Ansprechpartner</strong><br>
            Die Ansprechpartner für diese Zertifizierungsplattform können Sie unter folgender Emailadresse mail@joachimdieterich.de erreichen.<br> <br>
            <strong>Impressum</strong><br>
            Das Impressum dieses System können Sie <a href="http://joachimdieterich.de/index.php/impressum">hier</a> einsehen.</p>
        </div>    
        <div style="clear: both;"></div>
</div>
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}