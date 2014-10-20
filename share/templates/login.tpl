{extends file="base.tpl"}

{block name=title}{$page_title}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}
<div>
    <!--<h3 class="{$page_name}">{$page_title}</h3>-->
    <div class="centerbox">
        <div class="centerboxcorrection ">	
            <div class="centerbox_header">Login</div> 
                <p class="linie">&nbsp;</p>        
                <form class="space-top" action='index.php?action=login' method='post'>
                    <p><label >Anmeldename </label><input type='text' name='username' id='username' {if isset($username)}value='{$username}'{/if}></p>
                    <p><label >Kennwort  </label><input type='password' name='password' ></p>
                    <p class="linie">&nbsp;</p>
                    <p><label ></label><input type='submit' name='login' value='Anmelden' >
                        <p>Cookies müssen aktiviert sein!</p>
                    </p>
                </form>	

        </div>
    </div>    
</div>    
        <script type='text/javascript'>
	document.getElementById('username').focus();
	</script>
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}
