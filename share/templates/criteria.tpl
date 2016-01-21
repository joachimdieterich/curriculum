{extends file="base.tpl"}

{block name=title}{$page_title}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}
<div>
    <div class="centerbox">	
        <div class="centerbox_header">Kriterien</div> 
            <br>
            <p>{$criteria}</p>
            <br>
            <form>
                <p class="center"><input type="button" value="Fenster schließen" onClick="window.close();"></p>
            </form>
    </div>    
</div>    
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}