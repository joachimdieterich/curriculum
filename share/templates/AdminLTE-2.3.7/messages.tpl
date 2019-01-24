{extends file="base.tpl"}

{block name=title}{$page_title}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}
    {if isset($load_id)}
        <script type="text/javascript" > 
            $(document).ready(processor("mail","get",{$load_id}, {literal}{'mailbox': 'inbox', 'reload': 'false', 'callback': 'innerHTML', 'element_id': 'mailbox'}{/literal})); 
        </script>
    {/if}
{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}
{content_header p_title=$page_title pages=$breadcrumb help='https://curriculumonline.gitbook.io/documentation/'}   

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
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-compress"></i></button>
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
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-compress"></i></button>
                </div>
            </div>
            <div class="box-body no-padding" style="height: 600px;overflow:auto">
                <ul class="nav nav-pills nav-stacked">
                {if isset($messages[0])}  
                    {section name=mail loop=$messages start=$index max=$my_paginator_limit}
                    <li name='{$box}_{$messages[mail]->id}' id='{$box}_{$messages[mail]->id}'><a onclick="processor('mail','get',{$messages[mail]->id}, {literal}{'mailbox': '{/literal}{$box}{literal}', 'reload': 'false', 'callback': 'innerHTML', 'element_id': 'mailbox'}{/literal});"><i class="fa fa-circle-o text-light-blue"></i> 
                            {if isset($showInbox)}
                                {if $messages[mail]->receiver_status == 0}<strong>{/if}
                                {$messages[mail]->sender_firstname} {$messages[mail]->sender_lastname}&nbsp;({$messages[mail]->sender_username})
                                {if $messages[mail]->receiver_status == 0}</strong>{/if}
                            {else}
                                {if $messages[mail]->sender_status == 0}<strong>{/if}
                                {$messages[mail]->receiver_firstname} {$messages[mail]->receiver_lastname}&nbsp;({$messages[mail]->receiver_username})
                                {if $messages[mail]->sender_status == 0}</strong>{/if}
                            {/if}
                            <br><small>{$messages[mail]->subject|truncate:25:"...":true}</small>
                            <br><small>{strip_tags($messages[mail]->message|truncate:60:"...":true)}</small>
                        </a>
                        {if ($smarty.section.mail.index eq ($index+$my_paginator_limit-1))}
                        <li><span class="pull-right" style="padding-right:20px;">{$index+1} - {$smarty.section.mail.index+1}</span><a href="index.php?action=messages&function={$mailbox_func}&index={$smarty.section.mail.index}" class="text-center"><i class="fa fa-plus text-light-blue"></i>Weitere Nachrichten laden</li></a>
                        {/if}
                    </li>        
                    {/section}
                {else}
                    <li><a href="#">Keine Nachrichten vorhanden</a></li>
                {/if} 
                </ul>
            </div><!-- /.box-body -->
          </div><!-- /.box -->
        </div><!-- /.col -->
        <!-- /mail menu-->

        <div class="col-md-9 " id="mailbox"> <!-- placeholder for mails--></div><!-- /.col -->
        {/if}       
    </div><!-- /.row -->
</section>   
    
<input type='hidden' name='timestamp' value='{$timestamp}'/>
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}