{extends file="base.tpl"}

{block name=title}{$page_title}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}
    
<div class="container"> 
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>Login</h4>
            </div>
            <div class="panel-body">
                {$login_form}
            </div>
        </div>
    </div> 
     <script type='text/javascript'>document.getElementById('username').focus();</script>
</div>
    
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}
