{extends file="base.tpl"}

{block name=title}{$page_title}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}
    <div class="page-title">
        <h3 class="page-header">Startseite<input class="curriculumdocsbtn pull-right" type="button" name="help" onclick="curriculumdocs('http://docs.joachimdieterich.de/index.php?title=Startseite');"/></h3>
    </div>

    <div class="col-sm-6 col-md-6 col-lg-4">
          <div class="panel panel-default">
              <div class="panel-heading">
                <h4>Pinnwand
                      {if checkCapabilities('dasboard:editBulletinBoard', $my_role_id, false)}
                          <a class="editbtn pull-right" onclick="editBulletinBoard();"></a>
                      {/if}
                    </h4>
              </div>
              <div class="panel-body">
              {if $bulletinBoard}
                  <h4>{$bulletinBoard->title}</h4>
                  {$bulletinBoard->text}
              {/if}
            </div>
        </div>  
    </div>  
    
    <div class="col-sm-6 col-md-6 col-lg-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                  <h4>Nachrichten</h4>
                </div>
                <div class="panel-body">
                    {if isset($mails)} 
                        {section name=mes loop=$mails}
                            <div class="panel panel-success">
                                <div class="panel-heading">
                                  <h3 class="panel-title"> {$mails[mes]->subject}<span class="pull-right "><img style="margin-top: 8px; height:40px;" class="img-circle" src="{$access_file}{$mails[mes]->sender_file_id|resolve_file_id:"xs"}"/></span></h3>
                                </div>
                                <div class="panel-body">
                                    <a href="index.php?action=messages&function=showInbox&id={$mails[mes]->id}">{$mails[mes]->subject}</a> ({$mails[mes]->sender_username})<br>
                                {strip_tags($mails[mes]->message|truncate:100:"...":true)}
                                </div> 
                            </div>
                        {/section}
                    {else}<p>Keine ungelesenen Nachrichen vorhanden.</p>{/if}                    
            </div>
        </div>
    </div>  

    <div class="col-sm-6 col-md-6 col-lg-4">
        <div class="panel panel-default">
            <div class="panel-heading">
              <h4>Erfolge</h4>
            </div>
            <div class="panel-body">
              {if isset($enabledObjectives)} 
                Hier siehst du, welche Ziele du in den letzten <strong>{$my_acc_days}</strong> Tagen erreicht hast.
                    {foreach key=enaid item=ena from=$enabledObjectives}
                        <div class="panel panel-success">
                            <div class="panel-heading">
                              <h3 class="panel-title"> {$ena->curriculum}<span class="pull-right">{$ena->accomplished_teacher}</span></h3>
                            </div>
                            <div class="panel-body">
                                {$ena->enabling_objective|truncate:100}<!--{$ena->description}-->
                            </div> 
                        </div>
                    {/foreach}
                {else}<p>In den letzten <strong>{$my_acc_days}</strong> Tagen hast du keine Ziele abgeschlossen.</p>{/if}
            </div>
          </div>  
    </div>

         

        <div class="col-sm-6 col-md-6 col-lg-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                  <h4>Meine Lerngruppen / Klassen
                      </h4>
                </div>
                <div class="panel-body">
                    {if isset($myClasses)} 
                        {foreach key=claid item=cla from=$myClasses}
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <strong>{$cla->group} ({$cla->grade})</strong><br>
                                    {$cla->description} <br>
                                    {$cla->institution_id|truncate:50}
                                </div>
                                <div class="panel-body">
                                    <strong>Lehrpläne</strong><br>
                                    {foreach item=cur_menu from=$my_enrolments}
                                        {if $cur_menu->group_id eq $cla->id}
                                            <a href="index.php?action=view&curriculum={$cur_menu->id}&group={$cur_menu->group_id}">{$cur_menu->curriculum}</a><br>
                                        {/if}
                                    {/foreach}
                                </div>
                            </div>
                        {/foreach}  
                     {else}Sie sind in keiner Institution / Schule eingeschrieben.{/if}     
                </div>
            </div>
        </div>
                
        <div class="col-sm-6 col-md-6 col-lg-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Meine Institutionen / Schulen</h4>
                </div>
                <div class="panel-body"> 
                    {if isset($myInstitutions)} 
                        {foreach key=insid item=ins from=$myInstitutions}
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4><img style="height: 64px;" src="{$access_file}{$ins->file_id|resolve_file_id}"></h4>
                                </div>
                                <div class="panel-body">
                                    <strong>{$ins->institution}</strong><br>
                                    {$ins->schooltype_id}<br><br>
                                    {$ins->description}<br>

                                    {$ins->state_id}, {$ins->country}<br>
                                    {$ins->creator_id}
                                </div>
                            </div>
                        {/foreach}
                    {else}Sie sind in keiner Institution / Schule eingeschrieben.{/if} 
                </div>
            </div>
        </div>
        
        {if checkCapabilities('page:showCronjob', $my_role_id, false)}
        <div class="col-sm-6 col-md-6 col-lg-4">
             <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Abgelaufene Ziele</h4>
                </div>
                <div class="panel-body">
                {*$cronjob*}
                </div>
             </div>
        </div>
        {/if}
        
 
       <div class="col-sm-6 col-md-6 col-lg-4">
             <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Hilfe</h4>
                </div>
                <div class="panel-body">
                    {if $my_role_id eq 0}
                    <video style="display: block;margin: 0 auto;" width="480" controls preload="none">
                        <source src="{$media_url}/docs/Teilnehmer.mp4" type="video/mp4">
                    </video>
                    {/if}
                    {if $my_role_id eq 7}
                    <video style="display: block;margin: 0 auto;" width="480" controls preload="none">
                        <source src="{$media_url}/docs/Lehrer.mp4" type="video/mp4">
                    </video>
                    {/if}
                    {if $my_role_id eq 6}
                    <video style="display: block;margin: 0 auto;" width="480" controls preload="none">
                        <source src="{$media_url}/docs/Schuladmin.mp4" type="video/mp4">
                    </video>
                    {/if}
                    <a href="http://docs.joachimdieterich.de"><img src="{$media_url}/images/wiki.png"></a>
                </div>  
            </div>
       </div>
        <div style="clear: both;"></div>
        
        <div class="col-sm-6 col-md-6 col-lg-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Allgemeine Informationen</h4>
                </div>
                <div class="panel-body">
                    <strong>Datenschutzerklärung und Nutzungsbedingungen</strong><br>
                     Die Datenschutzerklärung und Nutzungsbedingungen für diese Lernplattform können Sie <a href="{$media_url}/docs/curriculum_Terms_Of_Use_2015.pdf">hier</a> einsehen. <br><br>
                     <strong>Ansprechpartner</strong><br>
                     Die Ansprechpartner für diese Zertifizierungsplattform können Sie unter folgender Emailadresse mail@joachimdieterich.de erreichen.<br> <br>
                     <strong>Impressum</strong><br>
                     Das Impressum dieses System können Sie <a href="http://joachimdieterich.de/index.php/impressum">hier</a> einsehen.
                </div>    
            </div>
        </div>
        <div style="clear: both;"></div>

{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}