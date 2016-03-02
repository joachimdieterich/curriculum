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
    <h3 class="page-header">{$page_title}<input class="curriculumdocsbtn pull-right" type="button" name="help" onclick="curriculumdocs('http://docs.joachimdieterich.de/index.php?title=Nachrichten');"/></h3>
    <form id='messages' method='post' action='index.php?action=messages&function='>  
        <input type='hidden' name='timestamp' value='{$timestamp}'/>  
        <p class="floatleft cssimgbtn gray-border ">
            <input class="mailinbtn cssbtnmargin " type='submit' name="showInbox" value='Posteingang' />
            <input class="mailoutbtn cssbtnmargin " type='submit' name="showOutbox" value='Postausgang' />
            <input class="mailnewbtn cssbtnmargin " type='submit' name="shownewMessage" value='Nachricht schreiben' />
            {if isset($showInbox)} 
                <a id="answer_btn" class="answerbtn cssbtnmargin cssbtntext" style="display: none;" href="">antworten</a>
            {/if}
        </p><p class="space-top-bottom"></p>
    </form>

    {if isset($shownewMessage)} 
    <form id='messages' method='post' action='index.php?action=messages'> 
      <p><h3>Neue Nachricht</h3></p>
      {if isset($class_members)}
         {if isset($receiver_id)}
              <p><label>An:</label>{html_options name='receiver_id' values=$class_members.id output=$class_members.user selected=$receiver_id}</p>    
         {else}
              <p><label>An:</label>{html_options name='receiver_id' values=$class_members.id output=$class_members.user}</p>  
         {/if}  
         <p><label>Betreff: </label><input class='inputlarge' name='subject' id='subject' {if isset($subject)}value='{$subject}'{/if}/></p> 
         {validate_msg field='subject'}
         <p><label>Nachricht: </label>{validate_msg field='message_text'}
              <textarea name="message_text">{if isset($message_text)}{$message_text}{/if}</textarea></p>    
         <p><label></label><input type='submit' name='sendMessage' value='Nachricht senden' /></p>
      {else}
          <p>Keine Gruppenmitglieder vorhanden.</p>
      {/if}
    </form>
    {/if}

    {if isset($showInbox) || isset($showOutbox)}
        {if isset($showInbox)}
            <p><h3>Empfangene Nachrichten</h3></p>
            {assign var="box"       value='inbox'}
            {if isset($inbox)}
                {assign var="messages"  value=$inbox}
            {/if}
            {assign var="new"       value='receiver_status'}
        {else}
            <p><h3>Gesendete Nachrichten</h3></p>
            {assign var="box"       value='outbox'} 
            {assign var="messages"  value=$outbox} 
            {assign var="new"       value='sender_status'}
        {/if}
        <div class="mailcontenttable">
            <div class="floatleft ">
                <form id='{$box}' method='post' action='index.php?action=messages'>
                {if isset($messages[0])}
                {* <p>Datensätze {${$box}Paginator.first}-{${$box}Paginator.last} von {${$box}Paginator.total} werden angezeigt.</p>*}
                <table id="contenttable" class="mailseperate">
                    <tr id="contenttablehead" >
                        <td></td>
                        <td>Nachrichten</td>
                    </tr>
                    {section name=mail loop=$messages}
                    <tr class="{if $messages[mail]->$new eq 0}mailnew{/if} contenttablerow " id="{$box}_{$messages[mail]->id}" onclick="loadmail({$messages[mail]->id}, '{$box}')">
                        <td><input class="invisible" type="checkbox" id="{$messages[mail]->id}" name="id[]" value={$messages[mail]->id} /></td>
                        <td class="mailcontenttable mailborder">
                            {if isset($showInbox)}
                            <p class="mailheader">{$messages[mail]->sender_firstname} {$messages[mail]->sender_lastname}({$messages[mail]->sender_username})
                            {else}
                            <p class="mailheader">{$messages[mail]->receiver_firstname} {$messages[mail]->receiver_lastname}({$messages[mail]->receiver_username})
                            {/if}    
                                <span class="floatright">{$messages[mail]->creation_time}</span>
                            </p>{$messages[mail]->subject|truncate:25:"...":true}<br>
                            <span class="mailpreview">{strip_tags($messages[mail]->message|truncate:100:"...":true)}
                        </td>
                    </tr>
                    {/section}
                </table>
                <p>{paginate_prev id="{$box}Paginator"} {paginate_middle id="{$box}Paginator"} {paginate_next id="{$box}Paginator"}</p>
                {else}
                    <p>Keine Nachrichten vorhanden</p>
                {/if}
            </div>
            <div id="mailbox" class="space-top">{if isset($html_mail)}{$html_mail}{/if}{*<textarea id="message_text" class="message_text"></textarea>*}</div>  
            
            <p class="space-bottom">&nbsp;</p>
            </form>  
        </div>
    {/if} 
{/block}


{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}