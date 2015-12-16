{extends file="base.tpl"}

{block name=title}{$page_title}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}
<div>
    <div class="centerbox">
        <div class="centerbox_header">Login</div> 
        <br>       
        <form class="space-top" action='index.php?action=login' method='post'>
            <p><label >Anmeldename </label><span {*class="tooltip" data-tooltip="Anmeldenamen eingeben"*} ><input id='username' name='username'{if isset($username)}value='{$username}'{/if}/></span></p>
            <p><label >Kennwort  </label><span {*class="tooltip" data-tooltip="Passwort eingeben"*}><input name='password' class="tooltip" data-tooltip="Anmeldenamen eingeben" type='password'/></span></p>
            <br>
            <p><label ></label><input name='login' type='submit' value='Anmelden' >
                <p>Cookies müssen aktiviert sein!</p>
            </p>
            <script type='text/javascript'>document.getElementById('username').focus();</script>
        </form>	
    </div>    
</div>    
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}
