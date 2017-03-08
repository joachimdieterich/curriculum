{extends file="base.tpl"}

{block name=title}Fehler{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content} 
    <ol class="breadcrumb breadcrumb-arrow pull-right">
        <li><a href="index.php?action=dashboard"> Home </a></li>
        <li class="active"><span>403 Error</span></li>
    </ol>
    <h3><i class="fa fa-warning text-yellow"></i>Error 403: Fehlende Berechtigung.</h3>
    <p><strong>{$curriculum_exception}</strong> <br>Die Seite <strong>{$page_name}</strong> kann nicht angezeigt werden.<br><br>
        Hier gehts zurück auf die  <a href="index.php?action=dashboard">Startseite</a>.
    </p>
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}