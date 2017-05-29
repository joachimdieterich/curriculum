{extends file="base.tpl"}

{block name=title}{$page_title}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}
<!-- jQuery UI used by sortable -->
<script src="{$media_url}scripts/jquery-ui.min.js"></script>
    {literal}<script>
    //Make the dashboard widgets sortable Using jquery UI
        $(".connectedSortable").sortable({
          placeholder         : "sort-highlight",
          connectWith         : ".connectedSortable",
          handle              : ".box-header, .alert-heading, .widget-user-header, .nav-tabs",
          forcePlaceholderSize: true,
          zIndex              : 999999,
          stop: function(e, ui) {
                      var element_weight = $.map($(".sortable"), function(el) {
                          return $(el).attr('id') + '=' + $(el).index();
                      });
                      var element_region = $.map($(".sortable"), function(el) {
                          return $(el).attr('id') + '=' + $(el).closest('section').attr('id');
                      })
                      processor('config','sortable', 'dashboard', {'element_weight': element_weight, 'element_region': element_region});
                      /*alert($.map($(".sortable"), function(el) {
                          return $(el).attr('id') + ' = ' + $(el).index();
                      }));*/
                  }

        });
        $(".connectedSortable .box-header, .alert-heading, .widget-user-header, .connectedSortable .nav-tabs-custom").css("cursor", "move");
    </script>{/literal}
{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}
<!-- Content Header (Page header) -->
{content_header p_title=$page_title pages=$breadcrumb help='http://docs.joachimdieterich.de/index.php?title=Startseite'}

<!-- Main content -->
<section class="content" >
    <!-- Info boxes -->
    <div class="row" >
        <section id="left" class="col-md-8 connectedSortable">
            <!-- Additional Blocks -->   
            {foreach key=blockid item=block from=$blocks}
                {if $block->region == 'left'}
                    {html_block blockdata=$block}
                {/if}
            {/foreach}  
        </section>
        
        <section id="right" class="col-md-4 connectedSortable">
            {*if checkCapabilities('dashboard:editBulletinBoard', $my_role_id, false) || $bulletinBoard} 
            <div class="box bottom-buffer-20">
                <div class="box-header with-border">
                  <h3 class="box-title">Pinnwand</h3>
                  <div class="box-tools pull-right">
                    {if checkCapabilities('dashboard:editBulletinBoard', $my_role_id, false)}  
                    <button class="btn btn-box-tool" data-widget="edit" onclick="formloader('bulletinBoard','edit');"><i class="fa fa-edit"></i></button>
                    {/if}
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-compress"></i></button>
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
            {/if*}

            <!-- Additional Blocks -->   
            {foreach key=blockid item=block from=$blocks}
                {*html_block block=$block->block configdata=$block->configdata visible=$block->visible*}
                {if $block->region == 'right'}
                    {html_block blockdata=$block}
                {/if}
            {/foreach} 
            <!-- Add Block -->
            {if checkCapabilities('block:add', $my_role_id, false)}
                <div class="box bottom-buffer-20">
                    <div class="box-header with-border">
                      <h3 class="box-title">Block hinzufügen</h3>
                      <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="add" onclick="formloader('block','new');"><i class="fa fa-plus"></i></button>
                      </div>
                    </div><!-- /.box-header -->
                </div><!-- /.box -->    
            {/if}
        </section>
    </div>
</section>                 
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}