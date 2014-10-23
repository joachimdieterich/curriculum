{extends file="base.tpl"}

{block name=title}{$page_title}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}
    
<div class="border-box">
    <div class="contentheader">{$page_title}</div>
    <div>
    
    <form id='messages' method='post' action='index.php?action=messages'>  
    <input type='hidden' name='timestamp' value='{$timestamp}'/>  
    <p><input type='submit' name="showInbox" value='Posteingang' />
       <input type='submit' name="showOutbox" value='Postausgang' />
       <input type='submit' name="shownewMessage" value='Nachricht schreiben' /></p>
    </form>
    {if isset($shownewMessage)} 
      <form id='messages' method='post' action='index.php?action=messages'> 
        <p><h3>Neue Nachricht</h3></p>
      {if $class_members != false}
        {if isset($receiver_id)}
            <p><label>An:</label>{html_options name='receiver_id' values=$class_members.id output=$class_members.user selected=$receiver_id}</p>    
        {else}
            <p><label>An:</label>{html_options name='receiver_id' values=$class_members.id output=$class_members.user}</p>  
        {/if}  
      
        <p><label>Betreff: </label><input class='inputlarge' type='text' name='subject' id='subject' {if isset($subject)}value='{$subject}'{/if}/></p> 
        {validate_msg field='subject'}
        <p><label>Nachricht: </label>{validate_msg field='message_text'}
            <textarea name="message_text">{if isset($message_text)}{$message_text}{/if}</textarea></p>    
        <p><label></label><input type='submit' name='sendMessage' value='Nachricht senden' /></p>
      {else}<p>Keine Gruppenmitglieder vorhanden.</p>
        {/if}
      <p>&nbsp;</p>
      </form>
    {/if}
    {if isset($showOutbox)} 
    <p><h3>Gesendete Nachrichten</h3></p>
<div class="mailcontenttable">
 <div class="floatleft">
     <form id='outbox' method='post' action='index.php?action=messages'>
     {if isset($outbox[0])}
         <table id="contenttable" class="mailseperate">
		<tr id="contenttablehead" >
                <td></td>
                <td>Nachrichten</td>
        </tr>
        
        {* display outbox *}  
        <p>Nachricht {$outboxPaginator.first}-{$outboxPaginator.last} von {$outboxPaginator.total} werden angezeigt.</p>  
        {section name=mail loop=$outbox}  
            <tr class="{if $outbox[mail]->status eq 0}mailnew{/if} contenttablerow " id="outbox_{$outbox[mail]->id}" onclick="loadmail({$outbox[mail]->id},'outbox')">
                <td><input class="invisible" type="checkbox" id="{$outbox[mail]->id}" name="id[]" value={$outbox[mail]->id} /></td>
                <td class="mailcontenttable mailborder"><p class="mailheader floatleft">{$outbox[mail]->sender_username} ({$outbox[mail]->sender_firstname} {$outbox[mail]->sender_lastname})</p>
                    <p class="floatright">{$outbox[mail]->creation_time}</p><br>
                         {$outbox[mail]->subject|truncate:70:"...":true}</td>
            </tr>
        {/section}
        </table>
        <p>{paginate_prev id="outboxPaginator"} {paginate_middle id="outboxPaginator"} {paginate_next id="outboxPaginator"}</p>
        {else}<tr><td><p>Keine Nachrichten vorhanden</p></td></tr>{/if}
        
        
     
  </div>
        <div id="mailbox"></div>   
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    </form>  
</div>
{/if}


    {if isset($showInbox)} 
    <p><h3>Empfangene Nachrichten</h3></p>
<div class="mailcontenttable">
 <div class="floatleft">
     <form id='inbox' method='post' action='index.php?action=messages'>
     {if isset($inbox[0])}
         <table id="contenttable" class="mailseperate">
		<tr id="contenttablehead" >
                <td></td>
                <td>Nachrichten</td>
        </tr>
        {* display inbox *}
        <p>Datensätze {$inboxPaginator.first}-{$inboxPaginator.last} von {$inboxPaginator.total} werden angezeigt.</p>
        {section name=mail loop=$inbox}
            <tr class="{if $inbox[mail]->status eq 0}mailnew{/if} contenttablerow " id="inbox_{$inbox[mail]->id}" onclick="loadmail({$inbox[mail]->id},'inbox')">
                <td><input class="invisible" type="checkbox" id="{$inbox[mail]->id}" name="id[]" value={$inbox[mail]->id} /></td>
                <td class="mailcontenttable mailborder"><p class="mailheader ">{$inbox[mail]->sender_username} ({$inbox[mail]->sender_firstname} {$inbox[mail]->sender_lastname})</p>
                    <p class="floatright">{$inbox[mail]->creation_time}</p><br>
                         {$inbox[mail]->subject|truncate:70:"...":true}</td>
            </tr>
        {/section}
        </table>
        <p>{paginate_prev id="inboxPaginator"} {paginate_middle id="inboxPaginator"} {paginate_next id="inboxPaginator"}</p>
        {else}<tr><td><p>Keine Nachrichten vorhanden</p></td></tr>{/if}
        
     
  </div>
        <div id="mailbox">{*<textarea id="message_text" class="message_text"></textarea>*}</div>   
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    </form>  
</div>
{/if}
</div> 

</div>
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}
