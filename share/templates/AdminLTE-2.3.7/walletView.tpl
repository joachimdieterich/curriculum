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
          handle              : ".wallet-content",
          forcePlaceholderSize: true,
          zIndex              : 999999,
          stop: function(e, ui) {
                      var element_weight = $.map($(".sortable"), function(el) {
                          return $(el).attr('id') + '=' + $(el).index();
                      });
                      var element_region = $.map($(".sortable"), function(el) {
                          return $(el).attr('id') + '=' + $(el).closest('section').attr('id');
                      })
                      //processor('config','sortable', 'dashboard', {'element_weight': element_weight, 'element_region': element_region});
                      /*alert($.map($(".sortable"), function(el) {
                          return $(el).attr('id') + ' = ' + $(el).index();
                      }));*/
                  }

        });
        $(".connectedSortable .wallet-content").css("cursor", "move");
    </script>{/literal}
    
    {if isset($walletuserPaginator)} 
    {literal}
    <script type="text/javascript" > 
        
        $(document).ready(function () {
            function findTopPos(obj) {
                var curleft = curtop = 0;
                if (obj.offsetParent) {
                  curtop = obj.offsetTop
                  while (obj = obj.offsetParent) {
                    curtop += obj.offsetTop
                  }
                }
                return curtop;
            }
            defaultTop  = findTopPos($("#container_walletuserPaginator")[0]);  
            small       = false;
            floating_table('body-wrapper', defaultTop, 'widgetuserPaginator', ['username'], 'menu_top_placeholder', 'container_walletuserPaginator', 'default_walletuserPaginator_position');
            
        });
    </script>
    
    {/literal}
    
    <!-- jQuery UI used by sortable -->
<script src="{$media_url}scripts/jquery-ui.min.js"></script>
    {literal}<script>
    //Make the dashboard widgets sortable Using jquery UI
        $(".connectedSortable").sortable({
          placeholder         : "sort-highlight",
          connectWith         : ".connectedSortable",
          handle              : ".wallet-content",
          forcePlaceholderSize: true,
          zIndex              : 999999,
          /*stop: function(e, ui) {
                      var element_weight = $.map($(".sortable"), function(el) {
                          return $(el).attr('id') + '=' + $(el).index();
                      });
                      var element_region = $.map($(".sortable"), function(el) {
                          return $(el).attr('id') + '=' + $(el).closest('section').attr('id');
                      })
                      processor('config','sortable', 'dashboard', {'element_weight': element_weight, 'element_region': element_region});
                      alert($.map($(".sortable"), function(el) {
                          return $(el).attr('id') + ' = ' + $(el).index();
                      }));
                  }*/

        });
        $(".wallet-content").css("cursor", "move");
    </script>{/literal}
{/if} 
    
{/block}
{block name=additional_stylesheets}{$smarty.block.parent}
<style>
    .sort-highlight {
    border: 1px dotted black;
    margin: 0 1em 1em 0;
    height: 50px;
  }
</style>
{/block}

{block name=content}
<!-- Content Header (Page header) -->
{content_header p_title=$page_title pages=$breadcrumb help=''}       
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="pull-right">
            {if isset($wallet_reset)}
                <a href="index.php?action=help" style="margin-left: 10px;" ><span class="fa fa-refresh"></span> Suche zurücksetzen</a>
            {/if}
            <div class="has-feedback" style="margin-right: 10px;width:150px;">
                <form id="view_search" method="post" action="index.php?action=help">
                    <input type="text" name="search" class="form-control input-sm" placeholder="Suchen">
                    <span class="fa fa-search form-control-feedback"></span>
                </form>
            </div>
        </div>
    </div>
    <div class="row top-buffer">
        <div class="col-xs-12">
            <div class="panel">
                <div class="panel-heading">
                    <div class="pull-right">
                        <div class="btn-group">
                            {if $wallet->creator_id eq $my_id OR $wallet->permission eq 2}
                                {if $edit eq true}
                                    <a class="btn btn-default" href="{removeUrlParameter($page_url, 'edit')}"><i class="fa fa-check"></i></a></button>
                                {else}
                                    <a class="btn btn-default" href="{$page_url}&edit=true"><i class="fa fa-edit"></i></a></button>
                                {/if}
                            {/if}    
                            {if $wallet->creator_id eq $my_id} 
                                {if checkCapabilities('wallet:delete', $my_role_id, false) AND $wallet->creator_id eq $my_id}  
                                    <button type="button" class="btn btn-default"><i class="fa fa-trash"></i></button>  
                                {/if}  
                                {if checkCapabilities('wallet:share', $my_role_id, false)}
                                    <button type="button" class="btn btn-default" onclick="formloader('wallet_sharing','edit',{$wallet->id});"><i class="fa fa-share-alt"></i></button>
                                {/if}
                            {/if}
                        </div>
                    </div>
                    {if $wallet->creator_id eq $my_id}
                        <div class="pull-right margin-r-5">
                            {Form::input_select('wallet_user_id', '', $userlist, 'firstname, lastname', 'id', $sel_user_id, null, "window.location.assign('index.php?action=walletView&wallet={$wallet->id}&user_id='+this.value);", 'Sammelmappe von Kursteilnehmer wählen...', '', 'col-sm-12')}
                        </div>
                    {/if}
                    <strong>{$wallet->title}</strong><br>{$wallet->timerange}
                </div>
                <div class="panel-body">
                    
                    {if isset($walletuserPaginator)}   
                    <p> Bitte  Schüler aus der Liste auswählen um die Sammelmappe zu sehen.</p>
                    <div id="default_walletuserPaginator_position" >
                            {html_paginator id='walletuserPaginator' title='Freigaben'} 
                    </div>
                {else}<p> Die Sammelmappe wurde keinem Benutzer freigegeben</p>{/if}
                    <div class="alert alert-info">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        {$wallet->description}<br>
                        
                        {foreach key=oid item=o from=$objectives}
                            {if $sel_user_id AND $my_role_id != 0}
                                <div style="display:inline-table">{RENDER::objective(["type" =>"enabling_objective", "objective" => $o , "user_id" => $sel_user_id])}</div>
                            {else}
                                <div style="display:inline-table">{RENDER::objective(["type" =>"enabling_objective", "objective" => $o ])}</div>
                            {/if}
                        {/foreach}
                    </div>
                    {assign var="row_id" value="-1"} 
                    
                    {foreach key=wcid item=wc from=$wallet->content}
                        {if $row_id neq $wc->row_id}
                            {if $row_id neq "-1"}    
                            </section>
                            </div><!-- ./row_x-->
                            </div><!-- ./panel-->
                            
                            {/if}
                            {assign var="row_id" value=$wc->row_id}
                            
                            <div class="col-xs-12 panel ">
                                <div id="row_{$wc->row_id}" class="row panel-default ">  
                                {if $edit eq true}
                                    <div class="panel-heading ">Block {$wc->row_id+1}
                                        <div class="box-tools pull-right" > Elemente hinzufügen
                                            <span class="fa fa-file-o margin-r-10" onclick='formloader("wallet_content", "new_file", {$wallet->id}, {["row_id" => $row_id]|@json_encode nofilter});'></span>
                                            <span class="fa fa-align-left" onclick='formloader("wallet_content", "new_content", {$wallet->id}, {["row_id" => $row_id]|@json_encode nofilter});'></span>
                                        </div>
                                    </div>
                                {/if}
                                    <section id="section_{$wc->row_id}" class="connectedSortable">
                        {/if}
                        {RENDER::wallet_content($wc,$edit)}
                            
                        
                    {/foreach}                    
                    {if !empty($wallet->content)}{* if bocks exist: close last block if *}
                            </div>
                        </div>
                    {/if}
                    
                    {if $edit eq true}
                        {assign var="row_id" value=$row_id+1} 
                        {if $edit eq true}
                            <div class="col-xs-12 panel">
                                <div id="row_{$wc->row_id}" class="row panel-default"> 
                                <div class="panel-heading">Block {$row_id+1}
                                    <div class="box-tools pull-right" > Elemente hinzufügen
                                        <span class="fa fa-file-o margin-r-10" onclick='formloader("wallet_content", "new_file", {$wallet->id}, {["row_id" => $row_id]|@json_encode nofilter});'></span>
                                        <span class="fa fa-align-left" onclick='formloader("wallet_content", "new_content", {$wallet->id}, {["row_id" => $row_id]|@json_encode nofilter});'></span>
                                    </div>
                                </div>
                                </div>
                            </div>
                        {/if}
                    {/if}
                
                    <h4>Kommentare <small>| Achtung! Kommentare sind für alle sichtbar, die diese Sammelmappe bearbeiten sollen.</small></h4>
                    {RENDER::comments(["comments" => $wallet->comments, "permission" => $wallet->permission])}

                    {if $wallet->permission > 0}
                        Neuen Kommentar hinzufügen
                        <textarea id="comment" name="comment"  style="width:100%;"></textarea>
                        <button type="submit" class="btn btn-primary pull-right" onclick="comment('new',{$wallet->id}, 18, document.getElementById('comment').value);"><i class="fa fa-commenting-o margin-r-10"></i> Kommentar abschicken</button>
                    {/if}
                </div>
            </div>
        </div>  
    </div>
</section>
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}