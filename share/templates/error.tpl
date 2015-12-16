{extends file="base.tpl"}

{block name=title}Fehler{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content} 
    <div class="contentheader ">Fehler - "{$prev_page_name}" kann nicht aufgerufen werden</div>
    <div>
        <p>{$curriculum_exception}</p>
    </div>
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}