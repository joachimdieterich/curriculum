{extends file="base.tpl"}

{block name=title}Nutzungsbedingungen{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{*{$smarty.block.parent}*}{/block}

{block name=additional_scripts}{$smarty.block.parent}{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}
    <section class="content-header"><h1>Zustimmungserklärung</h1></section>
        <div class="row ">
            <div class="col-xs-11 ">
                <div class="content">
                    <h4 >Lesen Sie diese Zustimmungserklärung sorgfältig. Sie müssen erst zustimmen, um diese Webseite nutzen zu können. Stimmen Sie zu?</h4>
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
                    <div>
                        {foreach item=t from=$terms name=term}
                            <h2>{$t->title}</h2>
                            {$t->content}
                        {/foreach}
                    </div> 
                </div>
            </div>
        </div>
    </section>
{/block}

{block name=sidebar}{*{$smarty.block.parent}*}{/block}
{block name=footer}{*{$smarty.block.parent}*}{/block}
