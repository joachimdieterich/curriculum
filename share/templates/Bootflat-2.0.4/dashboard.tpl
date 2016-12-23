{extends file="base.tpl"}

{block name=title}{$page_title}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}
{block name=additional_scripts}{$smarty.block.parent}{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content} 
<!-- Content Header (Page header) -->
{content_header p_title=$page_title pages=$breadcrumb help='http://docs.joachimdieterich.de/index.php?title=Startseite'}
<div class="documents">
    <div class="row">
        <!-- Upcoming Events -->
        {if isset($upcoming_events)}
        <div class="col-md-12 col-sm-12 col-xs-12 ">
            {foreach item=ue from=$upcoming_events} 
            <div class="alert alert-info alert-dismissable" ><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="fa fa-calendar"></i> {$ue->event}</h4>
                    <p>{$ue->timestart} - {$ue->timeend}</p>
                    <p>{$ue->description}</p><br>    
            </div>
            {/foreach}
        </div>
        {/if}
        <!-- ./Upcoming Events -->
        
        <!-- Erfolge -->
        {if isset($enabledObjectives)}    
        <div class="col-md-4">
            <div class="panel panel-default dashbox" > 
                <div class="panel-heading">
                    <i class="pull-right fa fa-thumbs-o-up primary" style=" font-size: 65px;"></i>
                    Erfolge | <small>Ziele die in den vergangenen <strong>{$my_acc_days}</strong> Tagen den Status geändert haben.</small>
                </div>
                <div class="panel-group panel-group-lists collapse in" id="accordion_erfolg" style="overflow: scroll; width: 100%; max-height: 300px;">
                {foreach key=enaid item=ena from=$enabledObjectives}
                <div class="panel {$box_bg[$ena->accomplished_status_id]}">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion_erfolg" href="#tab_ena_{$ena->id}" class="collapsed">
                                 {$ena->curriculum}<span class="badge pull-right" data-toggle="tooltip" title="Lernstand gesetzt von ...">{$ena->accomplished_teacher}</span><br>
                                 <small>{strip_tags($ena->enabling_objective|truncate:100)}</small>
                            </a>
                        </h4>
                    </div>
                    <div id="tab_ena_{$ena->id}" class="panel-collapse collapse" style="height: 0px;">
                        <div class="panel-body" >
                            asdf
                        </div>
                    </div>
                </div>
                {/foreach}
                </div>
            </div>
        </div>
        {/if}
        <!-- ./Erfolg -->
        
        <!-- Pinnwand -->
        {if checkCapabilities('dashboard:editBulletinBoard', $my_role_id, false) || $bulletinBoard} 
        <div class="col-md-8 col-sm-12 col-xs-12 ">
            <div class="panel panel-primary dashbox">
                <div class="panel-heading">Pinnwand
                  <div class="box-tools pull-right">
                    {if checkCapabilities('dashboard:editBulletinBoard', $my_role_id, false)}  
                    <span class="fa fa-edit" data-widget="edit" onclick="formloader('bulletinBoard','edit');"></span>
                    {/if}
                  </div>
                </div><!-- /.box-header -->
                <div class="panel-body">
                {if $bulletinBoard}
                    <h4>{$bulletinBoard->title}</h4>
                    {$bulletinBoard->text}
                {else}
                    Um einen Eintrag zu erstellen klicken Sie auf das <i class="fa fa-edit"></i>-Symbol.
                {/if}
                </div>
            </div>  
        </div>  
        {/if}
        <!-- ./Pinnwand -->
        
        <!-- Aufgaben -->
        {if !empty($upcoming_tasks)}
            <div class="col-md-4 ">
                <div class="panel panel-default dashbox">
                    <div class="panel-heading ">
                      <i class="pull-right fa fa-tasks primary" style=" font-size: 65px;"></i>
                      Aufgaben
                    </div>
                    <ul class="nav nav-stacked" style="overflow: scroll; width: 100%; max-height: 200px;">
                        {foreach item=tsk from=$upcoming_tasks} 
                            <li><a><strong>{$tsk->task}</strong><input type="checkbox" class="pull-right" onchange="processor('accomplish','task', {$tsk->id});" {if isset($tsk->accomplished->status_id)}{if $tsk->accomplished->status_id eq 2}checked{/if}{/if}><p>{$tsk->timestart} - {$tsk->timeend}</p>
                                {if isset($tsk->accomplished->status_id)}{if  $tsk->accomplished->status_id eq 2}
                                    <p class="text-green">Erledigt am {$tsk->accomplished->accomplished_time}</p>
                                {/if}{/if}
                                </a>
                            </li>
                        {/foreach}
                    </ul>
                  
                </div><!-- /.widget-user -->
            </div><!-- /.col -->  
        {/if}
        <!-- ./Aufgaben -->
        
        <!-- Institution -->
        {if isset($myInstitutions)}     
        <div class="col-md-4">
            <div class="panel panel-default dashbox"> 
                <div class="panel-heading ">
                    <i class="pull-right fa fa-university primary" style=" font-size: 65px;"></i>
                    Institutionen
                </div>
                <div class="panel-group panel-group-lists collapse out" id="accordion1" style="overflow: scroll; width: 100%; max-height: 300px;">
                {foreach key=insid item=ins from=$myInstitutions}
                <div class="panel">
                  <div class="panel-heading">
                    <h4 class="panel-title">
                      <a data-toggle="collapse" data-parent="#accordion1" href="#tab_ins_{$ins->id}" class="collapsed">
                          {$ins->institution}<br><small>{$ins->description}</small>
                      </a>
                    </h4>
                  </div>
                  <div id="tab_ins_{$ins->id}" class="panel-collapse collapse" style="height: 0px;">
                    <div class="panel-body" style="background: linear-gradient(rgba(0,0,0,0.5),rgba(100,100,100,0.5)), url('{$access_file}{$ins->file_id|resolve_file_id:"l"}') center right;background-size: cover; background-repeat: no-repeat;">
                      <div class="col-xs-4"><span class="badge badge-default">
                            <i class="fa fa-user" data-toggle="tooltip" title="Schüler">
                                {if isset($ins->statistic.$institution_std_role)}{$ins->statistic.$institution_std_role}{else}0 {/if}</i></span>
                        </div>
                        <div class="col-xs-4"><span class="badge badge-default"><i class="fa fa-check-circle-o" data-toggle="tooltip" title="Erreichte Ziele">
                            {if isset($ins->statistic.accomplished)}{$ins->statistic.accomplished}{else}0 {/if}</i></span>
                        </div>
                        <div class="col-xs-4"><span class="badge badge-default"><i class="fa fa-graduation-cap" data-toggle="tooltip" title="Lehrer">
                            {if isset($ins->statistic.7)}{$ins->statistic.7}{else}0 {/if}</i></span>
                        </div>
                    </div>
                  </div>
                </div>
                {/foreach}
                </div>
            </div>
        </div>
        {/if}
        <!-- ./Institution -->
        
        <!-- Groups -->
        {if isset($myClasses)}
        <div class="col-md-4">
            <div class="panel panel-default dashbox"> 
                <div class="panel-heading ">
                    <i class="pull-right fa fa-group primary" style=" font-size: 65px;"></i>
                    Lerngruppen
                </div>
                <div class="panel-group panel-group-lists collapse out" id="accordion2" style="overflow: scroll; width: 100%; max-height: 300px;">
                    {foreach key=claid item=cla from=$myClasses}    
                        <div class="panel">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion2" href="#tab_cla_{$cla->id}" class="collapsed">
                                        {$cla->group}<br><small>{$cla->institution_id|truncate:50}</small>
                                    </a>
                                </h4>
                            </div>
                            <div id="tab_cla_{$cla->id}" class="panel-collapse collapse" style="height: 0px;"> 
                                <div class="panel-body" style="padding:0px 10px 0px 10px;">
                                    <ul class="list-group" >
                                    {if $my_enrolments}
                                    {foreach item=cur_menu from=$my_enrolments}
                                        {if $cur_menu->group_id eq $cla->id}
                                            <li class="list-group-sub-item"><a href="index.php?action=view&curriculum_id={$cur_menu->id}&group={$cur_menu->group_id}">{$cur_menu->curriculum} </a></li>
                                        {/if}
                                    {/foreach}
                                    {/if}
                                    </ul>
                                </div>
                            </div>
                        </div>
                    {/foreach}  
                </div>
            </div>
        </div>
        {/if}    
        <!-- ./Groups -->
        
        {if isset($stat_users_online) && checkCapabilities('user:userListComplete', $my_role_id, false)}
        <!-- Statistics -->
        <div class="col-md-4">
            <div class="panel panel-default dashbox"> 
                <div class="panel-heading ">
                    <i class="pull-right fa fa-pie-chart primary" style=" font-size: 65px;"></i>
                    Statistik
                </div>
                <div class="panel-body">
                    <ul class="nav">
                    <li><strong>Erreichte Ziele</strong></li>
                    <li class="list-group-item">Gesamt <span class="pull-right text-green">{$stat_acc_all}</span></li>
                    <li class="list-group-item">davon Heute <span class="pull-right text-green">{$stat_acc_today}</span></li>
                  </ul>
                  <br>
                  {if checkCapabilities('page:showCronjob', $my_role_id, false)}
                      <ul class="nav">
                          <li><strong>Wiederholungen</strong></li>
                          <li class="list-group-item">Ziele die Wiederholt werden müssen<span class="pull-right text-red">{*$cronjob*}0</span></li>
                      </ul>
                  {/if}
                  <br>
                  <ul class="nav">
                    <li><strong>Online</strong></a></li>
                    <li class="list-group-item">Jetzt online <span class="pull-right">{$stat_users_online}</span></li>
                    <li class="list-group-item">Heute <span class="pull-right"> {$stat_users_today}</span></li>
                  </ul>
                </div>
            </div>
        </div>        
        <!-- ./Statistics -->
        {/if}
        
        <!-- Additional Bocks -->
        {foreach key=blockid item=block from=$blocks}
            {html_block blockdata=$block}
        {/foreach} 
        <!-- ./Additional Blocks -->  
     
        <!-- Add Block -->
        {if checkCapabilities('block:add', $my_role_id, false)}
        <div class="col-md-4 ">
            <div class="panel panel-default ">
                <div class="panel-heading">
                  Block hinzufügen
                  <div class="box-tools pull-right">
                    <span class="fa fa-plus" data-widget="add" onclick="formloader('block','new');"></span>
                  </div>
                </div><!-- /.panel-header -->
            </div><!-- /.panel -->
        </div>    
        {/if}
    </div><!-- ./row -->  
</div>        
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}