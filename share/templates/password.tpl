{extends file="base.tpl"}

{block name=title}{$page_title}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}
<div class="border-box">
    <div class="contentheader ">{$page_title}</div>
    {$pw_form}
    {*<form method='post' action='index.php?action=password'>
        <p><label>Benutzername: </label><input name='username' value={$my_username} readonly /></p>	
        {if !isset($webservice)}
        <p><label>Altes Kennwort: </label><input name='oldpassword' id='oldpassword' type='password'/></p>
        {validate_msg field='oldpassword'}
        {else}<input id='oldpassword' name='oldpassword' type='hidden' value='{$oldpassword}'/>{/if}
        <p><label>Neues Kennwort: </label><input type='password' name='password' /></p>
        {validate_msg field='password'}
        <p><label>Kennwort bestätigen: </label><input type='password' name='confirm' /></p>
        {validate_msg field='confirm'}
        <p><label></label><input type='submit' value='Kennwort ändern' /></p>
        <script type='text/javascript'> document.getElementById('oldpassword').focus(); </script>
    </form>*}	
</div>
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}
