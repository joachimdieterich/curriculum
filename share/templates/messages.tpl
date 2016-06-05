{extends file="base.tpl"}

{block name=title}{$page_title}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}
    {if isset($load_id)}
        <script type="text/javascript" > 
            $(document).ready(loadmail({$load_id}, "inbox")); 
        </script>
    {/if}
{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}
{content_header p_title=$page_title pages=$breadcrumb help='http://docs.joachimdieterich.de/index.php?title=Nachrichten'}   

<!-- Main content -->
<section class="content">
          <div class="row">
            <!-- mail menu -->  
            <div class="col-md-3">
              <a href="#" class="btn btn-primary btn-block margin-bottom" onclick="formloader('mail','new');">Nachricht schreiben </a>
              <div class="box box-solid">
                <div class="box-header with-border">
                  <h3 class="box-title">Postfächer</h3>
                  <div class="box-tools">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                  </div>
                </div>
                
                <div class="box-body no-padding" style="display: block;">
                  <ul class="nav nav-pills nav-stacked">
                    <li class="{if isset($showInbox)}active{/if}"><a href="index.php?action=messages&function=showInbox"><i class="fa fa-inbox"></i> Posteingang <span class="label label-primary pull-right">{if isset($showInbox)}{$inbox|@count}{/if}</span></a></li>
                    <li class="{if isset($showOutbox)}active{/if}"><a href="index.php?action=messages&function=showOutbox"><i class="fa fa-envelope-o"></i> Postausgang <span class="label label-primary pull-right">{if isset($showOutbox)}{$outbox|@count}{/if}</span></a></li>
                    <!--li><a href="#"><i class="fa fa-file-text-o"></i> Drafts</a></li-->
                    <!--li><a href="#"><i class="fa fa-filter"></i> Junk <span class="label label-warning pull-right">65</span></a></li-->
                    <!--li><a href="#"><i class="fa fa-trash-o"></i> Papierkorb</a></li-->
                  </ul>
                </div><!-- /.box-body -->
             </div><!-- /. box -->
         
            {if isset($showInbox) || isset($showOutbox)}
                {if isset($showInbox)}
                    {assign var="box"       value='inbox'}
                    {if isset($inbox)}
                        {assign var="messages"  value=$inbox}
                    {/if}
                    {assign var="new"       value='receiver_status'}
                {else}
                    {assign var="box"       value='outbox'} 
                    {assign var="messages"  value=$outbox} 
                    {assign var="new"       value='sender_status'}
                {/if}
             
             
             <div class="box box-solid">
                <div class="box-header with-border">
                  <h3 class="box-title">{if $box eq 'inbox'}Posteingang{else if $box eq 'outbox'}Postausgang{/if}</h3>
                  <div class="box-tools">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                  </div>
                </div>
                <div class="box-body no-padding">
                  <ul class="nav nav-pills nav-stacked">
                    {if isset($messages[0])}  
                        {section name=mail loop=$messages}
                        <li name='{$box}_{$messages[mail]->id}' id='{$box}_{$messages[mail]->id}'><a onclick="loadmail({$messages[mail]->id}, '{$box}')"><i class="fa fa-circle-o text-light-blue"></i> 
                                {if isset($showInbox)}
                                    {$messages[mail]->sender_firstname} {$messages[mail]->sender_lastname}&nbsp;({$messages[mail]->sender_username})
                                {else}
                                    {$messages[mail]->receiver_firstname} {$messages[mail]->receiver_lastname}&nbsp;({$messages[mail]->receiver_username})
                                {/if}
                                <br><small>{$messages[mail]->subject|truncate:25:"...":true}</small>
                                <br><small>{strip_tags($messages[mail]->message|truncate:60:"...":true)}</small>
                            </a></li>
                        {/section}
                    {else}
                        <li><a href="#">Keine Nachrichten vorhanden</a></li>
                    {/if} 
                  
                  </ul>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
             
            </div><!-- /.col -->
            <!-- /mail menu-->
            
    
 
                {*<form id='{$box}' method='post' action='index.php?action=messages'>
                 <!--tr class="{if $messages[mail]->$new eq 0}mailnew{/if} contenttablerow " id="{$box}_{$messages[mail]->id}" onclick="loadmail({$messages[mail]->id}, '{$box}')"-->
                <p>{paginate_prev id="{$box}Paginator"} {paginate_middle id="{$box}Paginator"} {paginate_next id="{$box}Paginator"}</p>*}
  
            <!-- /mail list-->
            {*<div class="col-md-9">
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">{if $box eq 'inbox'}Posteingang{else if $box eq 'outbox'}Postausgang{/if}</h3>
                  <div class="box-tools pull-right">
                    <div class="has-feedback">
                      <input type="text" class="form-control input-sm" placeholder="Search Mail">
                      <span class="glyphicon glyphicon-search form-control-feedback"></span>
                    </div>
                  </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class="box-body no-padding">
                  <div class="mailbox-controls">
                    <!-- Check all button -->
                    <button class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i></button>
                    <div class="btn-group">
                      <button class="btn btn-default btn-sm"><i class="fa fa-trash-o"></i></button>
                      <button class="btn btn-default btn-sm"><i class="fa fa-reply"></i></button>
                      <button class="btn btn-default btn-sm"><i class="fa fa-share"></i></button>
                    </div><!-- /.btn-group -->
                    <button class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></button>
                    <div class="pull-right">
                      1-50/200
                      <div class="btn-group">
                        <button class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></button>
                        <button class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></button>
                      </div><!-- /.btn-group -->
                    </div><!-- /.pull-right -->
                  </div>
                  <div class="table-responsive mailbox-messages">
                      
                    {if isset($messages[0])}
                    <table class="table table-hover table-striped">
                      <tbody>
                        {section name=mail loop=$messages}
                        <tr>
                          <td><div class="icheckbox_flat-blue" aria-checked="false" aria-disabled="false" style="position: relative;"><input id="{$messages[mail]->id}" name="id[]" value={$messages[mail]->id} type="checkbox" style="position: absolute; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background-color: rgb(255, 255, 255); border: 0px; opacity: 0; background-position: initial initial; background-repeat: initial initial;"></ins></div></td>
                          <td class="mailbox-star"><a href="#"><i class="fa fa-star text-yellow"></i></a></td>
                          <td class="mailbox-name"><a href="read-mail.html">
                                {if isset($showInbox)}
                                    {$messages[mail]->sender_firstname} {$messages[mail]->sender_lastname}&nbsp;({$messages[mail]->sender_username})
                                {else}
                                    {$messages[mail]->receiver_firstname} {$messages[mail]->receiver_lastname}&nbsp;({$messages[mail]->receiver_username})
                                {/if}
                              </a></td>
                              <td class="mailbox-subject"><b>{$messages[mail]->subject|truncate:25:"...":true}</b>&nbsp;{strip_tags($messages[mail]->message|truncate:80:"...":true)}</td>
                          <td class="mailbox-attachment"></td>
                          <td class="mailbox-date">{$messages[mail]->creation_time}</td>
                        </tr>
                        {/section}
                      </tbody>
                    </table><!-- /.table -->
                    {/if}
                  </div><!-- /.mail-box-messages -->
                </div><!-- /.box-body -->
                <div class="box-footer no-padding">
                  <div class="mailbox-controls">
                    <!-- Check all button -->
                    <button class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i></button>
                    <div class="btn-group">
                      <button class="btn btn-default btn-sm"><i class="fa fa-trash-o"></i></button>
                      <button class="btn btn-default btn-sm"><i class="fa fa-reply"></i></button>
                      <button class="btn btn-default btn-sm"><i class="fa fa-share"></i></button>
                    </div><!-- /.btn-group -->
                    <button class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></button>
                    <div class="pull-right">
                      1-50/200
                      <div class="btn-group">
                        <button class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></button>
                        <button class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></button>
                      </div><!-- /.btn-group -->
                    </div><!-- /.pull-right -->
                  </div>
                </div>
              </div><!-- /. box -->
            </div><!-- /.col -->
            <!-- /mail list -->
            *}
            
            <div class="col-md-9 " id="mailbox">
              
            </div><!-- /.col -->
    {/if}       
          </div><!-- /.row -->
        </section>   
    
    <input type='hidden' name='timestamp' value='{$timestamp}'/>  
    {if isset($showInbox)} 
        <a id="answer_btn" class="answerbtn  " style="display: none;" href="">antworten</a>
    {/if}
{/block}


{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}