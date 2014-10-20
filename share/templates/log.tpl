{extends file="base.tpl"}

{block name=title}{$page_title}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}
    
<div class="border-box">
    <div class="contentheader ">{$page_title}</div>
    <div>
    {if isset($ccs_page_log)}
        
        {* display pagination header *}
        <p>Datensätze {$logPaginator.first}-{$logPaginator.last} von {$logPaginator.total} werden angezeigt.</p>
        <table id="contenttable">
                <tr id="contenttablehead">
                    <td></td>
                <td>ID</td>
                <td>Datum/Zeit</td>
                <td>IP</td>
                <td>UserID</td>
                <td>Action</td>
                <td>URL</td>
                <td>Info</td>
        </tr>
        {* display results *} 
        {section name=res loop=$results}
            <tr class="contenttablerow" id="row{$results[res]->id}" onclick="checkrow({$results[res]->id})">
                <td><input class="invisible" type="checkbox" id="{$results[res]->id}" name="id[]" value={$results[res]->id} /></td>
                <td>{$results[res]->id}</td>
                <td>{$results[res]->creation_time}</td>
                <td>{$results[res]->ip}</td>
                <td>{$results[res]->username}</td>
                <td>{$results[res]->action}</td>
                <td>{$results[res]->url}</td>
                <td>{$results[res]->info}</td>
            </tr>
        {/section}
        </table>

        {* display pagination info *}
        <p>{paginate_first id="logPaginator"} {paginate_middle id="logPaginator"} {paginate_next id="logPaginator"}</p>
    {else}<p>Sie haben keine Berechtigung für diesen Bereich.</p>{/if} 
    </div>
</div>
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}