<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
  <!-- Create the tabs -->
  <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
    <li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
    <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
  </ul>
  <!-- Tab panes -->
  <div class="tab-content">
    <!-- Home tab content -->
    <div class="tab-pane" id="control-sidebar-home-tab">
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
        {/if}
      </ul><!-- /.control-sidebar-menu -->

      <h3 class="control-sidebar-heading">Anstehende Termine</h3>
      <ul class="control-sidebar-menu">
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