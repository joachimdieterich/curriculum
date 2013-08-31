{extends file="base.tpl"}

{block name=title}Login{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}
    
<!--<h3 class="{$page_name}">{$login_stuff}</h3>-->
<div class="centerbox">
    <div class="centerboxcorrection gray-gradient border-radius box-shadow gray-border">	
        <div class="border-top-radius contentheader">Login</div>    			
            <form class="space-top" action='index.php?action=login' method='post'>
                <p ><h2>Zur Nutzung ist ein Login notwendig</h2></p>
                <p>&nbsp;</p>
                <p>Geben Sie Ihren Anmeldenamen und das Kennwort ein.</p>
                <p>(Cookies sowie Javascript müssen in Ihrem Browser aktiviert sein!)</p>
                <p><h3>&nbsp;</h3></p>
                <p><label >Benutzername: </label><input class="inputform" type='text' name='username' id='username' {if isset($username)}value='{$username}'{/if}></p>
                <p><label >Passwort:  </label><input class="inputform" type='password' name='password' ></p>
                <p class="linie">&nbsp;</p>
                <p><label ></label><input type='submit' name='login' value='Anmelden' >
                {*<input type='submit' name='register' value='Registrieren' >
                <input type='submit' value='Passwort vergessen' >*}</p>
            </form>	

    </div>
</div>            
        <script type='text/javascript'>
	document.getElementById('username').focus();
	</script>
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}
