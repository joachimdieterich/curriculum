{extends file="base.tpl"}

{block name=title}Fehlende Berechtigung{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}
    <div class=" border-radius gray-border">	
    <div class="border-top-radius contentheader ">Fehler - "{$prev_page_name}" kann nicht aufgerufen werden</div>
    <div class="space-top-padding gray-gradient border-bottom-radius box-shadow ">
        <p>{$curriculum_exception}</p>
    </div>
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}
