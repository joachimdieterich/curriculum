{extends file="base.tpl"}

{block name=title}{$page_title}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}
 
{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}
    
<!-- Content Header (Page header) -->
{content_header p_title=$page_title pages=$breadcrumb help='http://docs.joachimdieterich.de/index.php?title=Startseite'}    
 
<!-- Main content -->
<section class="content">
    <!-- Info boxes -->
    <div class="row" >
        
         <div class="col-md-4 ">
            <div class="box box-primary">
                <div class="box-header with-border">
                      <h3 class="box-title">Erfolge</h3>
                      <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                      </div>
                    </div><!-- /.box-header -->
                <div class="box-body">
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
        
        {if checkCapabilities('dashboard:editBulletinBoard', $my_role_id, false) || $bulletinBoard} 
        <div class="col-md-8 col-sm-12 col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Pinnwand</h3>
                  <div class="box-tools pull-right">
                    {if checkCapabilities('dashboard:editBulletinBoard', $my_role_id, false)}  
                    <button class="btn btn-box-tool" data-widget="edit" onclick="formloader('bulletinBoard','edit');"><i class="fa fa-edit"></i></button>
                    {/if}
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                </div><!-- /.box-header -->
                <div class="box-body">
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
       
   
        {if isset($myInstitutions)}     
        {foreach key=insid item=ins from=$myInstitutions}
            <div class="col-md-4">
                <div class="box box-widget widget-user">
                  <!-- Add the bg color to the header using any of the bg-* classes -->
                    <div class="widget-user-header bg-aqua-active" style="background: url('{$access_file}{$ins->file_id|resolve_file_id:"l"}') center right;background-size: cover; background-repeat: no-repeat;">
                    {*<i class="pull-right fa fa-institution" style="font-size: 90px;"></i>*}
                    <h3 class="widget-user-username" style="text-shadow: 1px 1px #ff0000;">{$ins->institution}</h3>
                    <h5 class="widget-user-desc" style="text-shadow: 1px 1px #ff0000;">{$ins->description}</h5>
                  </div>
                  <div class="box-body">
                    <div class="row">
                      <div class="col-sm-4 border-right">
                        <div class="description-block">
                          <h5 class="description-header">{if isset($ins->statistic.$institution_std_role)}
                                                            {$ins->statistic.$institution_std_role}
                                                         {else}0 
                                                         {/if}</h5>
                          <span class="description-text">SCHÜLER</span>
                        </div><!-- /.description-block -->
                      </div><!-- /.col -->
                      <div class="col-sm-4 border-right">
                        <div class="description-block">
                          <h5 class="description-header">{if isset($ins->statistic.accomplished)}
                                                            {$ins->statistic.accomplished}
                                                         {else}0 
                                                         {/if}</h5>
                          <span class="description-text">ERREICHTE ZIELE</span>
                        </div><!-- /.description-block -->
                      </div><!-- /.col -->
                      <div class="col-sm-4">
                        <div class="description-block">
                          <h5 class="description-header">{if isset($ins->statistic.7)}
                                                            {$ins->statistic.7}
                                                         {else}0 
                                                         {/if}</h5>
                          <span class="description-text">LEHRER</span>
                        </div><!-- /.description-block -->
                      </div><!-- /.col -->
                    </div><!-- /.row -->
                  </div>
                </div><!-- /.widget-user -->
           </div><!-- /.col -->
        {/foreach}
        {/if}
        
        {if isset($myClasses)}
        {foreach key=claid item=cla from=$myClasses}    
        <div class="col-md-4 ">
            <!-- Widget: user widget style 1 -->
            <div class="box box-widget widget-user">
              <!-- Add the bg color to the header using any of the bg-* classes -->
              <div class="widget-user-header bg-yellow">
                <i class="pull-right fa fa-group" style="font-size: 90px;"></i>
                <h3 class="widget-user-username">{$cla->group}</h3>
                <h5 class="widget-user-desc">{$cla->institution_id|truncate:50}</h5>
              </div>
              <div class="box-footer no-padding">
                <ul class="nav nav-stacked">
                    {if $my_enrolments}
                    {foreach item=cur_menu from=$my_enrolments}
                        {if $cur_menu->group_id eq $cla->id}
                            <li><a href="index.php?action=view&curriculum_id={$cur_menu->id}&group={$cur_menu->group_id}">{$cur_menu->curriculum} </a></li>
                        {/if}
                    {/foreach}
                    {/if}
                </ul>
              </div>
            </div><!-- /.widget-user -->
        </div><!-- /.col -->        
        {/foreach}  
        {/if}    
        
        
        
        {if isset($stat_users_online) && checkCapabilities('user:userListComplete', $my_role_id, false)}
        <div class="col-md-4 col-sm-12 col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Statistik</h3>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                </div><!-- /.box-header -->
                <div class="box-footer no-padding">
                  <ul class="nav nav-pills nav-stacked">
                    <li><a href="#"><strong>Erreichte Ziele</strong></a></li>
                    <li><a href="#">Gesamt <span class="pull-right text-green">{$stat_acc_all}</span></a></li>
                    <li><a href="#">davon Heute <span class="pull-right text-green">{$stat_acc_today}</span></a></li>
                  </ul>
                  
                  {if checkCapabilities('page:showCronjob', $my_role_id, false)}
                      <ul class="nav nav-pills nav-stacked">
                          <li><a href="#"><strong>Wiederholungen</strong></a></li>
                          <li><a href="#">Ziele die Wiederholt werden müssen<span class="pull-right text-red">{*$cronjob*}0</span></a></li>
                      </ul>
                  {/if}
                  <ul class="nav nav-pills nav-stacked">
                    <li><a href="#"><strong>Online</strong></a></li>
                    <li><a href="#">Jetzt online <span class="pull-right">{$stat_users_online}</span></a></li>
                    <li><a href="#">Heute <span class="pull-right"> {$stat_users_today}</span></a></li>
                  </ul>
                  
                </div><!-- /.footer -->
            </div><!-- /.box -->
        </div>    
        {/if}
      
        <!-- Additional Blocks -->   
        {foreach key=blockid item=block from=$blocks}
            {*html_block block=$block->block configdata=$block->configdata visible=$block->visible*}
            {html_block blockdata=$block}
        {/foreach}  
        <!-- Add Block -->
        {if checkCapabilities('block:add', $my_role_id, false)}
        <div class="col-md-4 col-sm-12 col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Block hinzufügen</h3>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="add" onclick="formloader('block','new');"><i class="fa fa-plus"></i></button>
                  </div>
                </div><!-- /.box-header -->
            </div><!-- /.box -->
        </div>    
        {/if}
    </div>
</section>                 
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}