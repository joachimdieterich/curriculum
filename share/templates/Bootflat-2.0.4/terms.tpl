{extends file="base.tpl"}

{block name=title}Nutzungsbedingungen{/block}
{block name=description}{$smarty.block.parent}{/block}

{block name=content}
    <form action="index.php?action=login" method="post"><input name="terms" type="hidden" value="terms" />
        <div class="btn-group" role="group" aria-label="...">
            <button type="submit" class="btn btn-default" name="Submit" value="Ja" >
                 <span class="fa fa-thumbs-o-up text-green"></span> Ja, ich stimme zu.
            </button>
            <button type="submit" class="btn btn-default" name="Submit" value="Nein" >
                <span class="fa fa-thumbs-o-down text-red"></span> Nein, ich stimme nicht zu.
            </button>
        </div>
    </form>
    <div class="panel" style=" padding:0 10px;">
    {foreach item=t from=$terms name=term}
        <h2>{$t->title}</h2>
        {$t->content}
    {/foreach}
    </div>
{/block}