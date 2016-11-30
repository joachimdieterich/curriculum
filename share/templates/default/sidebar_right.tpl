<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
  <!-- Create the tabs -->
  <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
    <li class="active"><a href="#control-sidebar-home-tab" data-toggle="tab" ><i class="fa fa-home"></i></a></li>
    <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
  </ul>
  <!-- Tab panes -->
  <div class="tab-content">
    <!-- Home tab content -->
    <div class="tab-pane active" id="control-sidebar-home-tab">
      <h3 class="control-sidebar-heading">Letzte Nachrichten</h3>
      <ul class="control-sidebar-menu">
        {if isset($recent_mails)}  
            {foreach item=rm from=$recent_mails}  
            <li>
              <a href="index.php?action=messages&function=showInbox&id={$rm->id}">
                  <img src="{$access_file_id}{$rm->sender_file_id}" class="user-image menu-icon fa" alt="User Image"></img>
                <div class="menu-info">
                  <h4 class="control-sidebar-subheading">{$rm->sender_firstname}</h4>
                  <p>{$rm->subject}</p>
                </div>
              </a>
            </li>
            {/foreach}
        {else}
            <li><a href="#">Keine Nachrichten vorhanden</a></li>
        {/if}
      </ul><!-- /.control-sidebar-menu -->

      {*<h3 class="control-sidebar-heading">Anstehende Termine</h3>
      <ul class="control-sidebar-menu">
        {if !empty($upcoming_events)}  
            {foreach item=ue from=$upcoming_events} 
            <li>
              <a href="#">
                <i class="menu-icon fa fa-calendar bg-blue"></i>
                <div class="menu-info">
                  <h4 class="control-sidebar-subheading">{$ue->event}</h4>
                  <p>{$ue->timestart} - {$ue->timeend}</p>
                </div>
              </a>
            </li>
            {/foreach}
        {else}
            <li><a href="#">Keine Termine vorhanden</a></li>
        {/if}
      </ul><!-- /.control-sidebar-menu -->*}
      
      <h3 class="control-sidebar-heading">Anstehende Aufgaben</h3>
      <ul class="control-sidebar-menu">
        {if !empty($upcoming_tasks)}
            {foreach item=tsk from=$upcoming_tasks} 
            <li>
              <a href="#">
                <i class="menu-icon fa fa-tasks bg-red"></i>
                <div class="menu-info">
                    <input type="checkbox" class="pull-right" onchange="processor('accomplish','task', {$tsk->id});" {if isset($tsk->accomplished->status_id)}{if $tsk->accomplished->status_id eq 2}checked{/if}{/if}>
                    <h4 class="control-sidebar-subheading">{$tsk->task}</h4>
                    <p>{$tsk->timestart} - {$tsk->timeend}</p>
                    {if isset($tsk->accomplished->status_id)}{if  $tsk->accomplished->status_id eq 2}
                        <p class="text-green">Erledigt am {$tsk->accomplished->accomplished_time}</p>
                    {/if}{/if}
                </div>
              </a>
            </li>
            {/foreach}
        {else}
            <li><a href="#">Keine Aufgaben</a></li>
        {/if}
        
      </ul><!-- /.control-sidebar-menu -->
    </div><!-- /.tab-pane -->

    <!-- Settings tab content -->
    <div class="tab-pane" id="control-sidebar-settings-tab">
      <form method="post">
        <h3 class="control-sidebar-heading">Listen</h3>
        <div class="form-group">
          <label class="control-sidebar-subheading">
            Datensätze pro Seite
            <input type="number" class="pull-right color-palette bg-primary" min="5" max="100" value="{$my_paginator_limit}" onchange="processor('config','user_paginator', this.value);">
          </label>
          <p>Legt fest, wie viele Einträge pro Seite angezeigt werden.</p>
        </div><!-- /.form-group -->
      </form>
    </div><!-- /.tab-pane -->
  </div>
</aside><!-- /.control-sidebar -->
<!-- Add the sidebar's background. This div must be placed
     immediately after the control sidebar -->
<div class="control-sidebar-bg"></div>