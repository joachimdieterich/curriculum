{extends file="base.tpl"}

{block name=title}{$page_title}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}
    
<div class=" border-radius gray-border">	
    <div class="border-top-radius contentheader ">{$page_title}</div>
    <div class="space-top-padding gray-gradient border-bottom-radius box-shadow ">
      <form method='post' action='index.php?action=backup'>
        {if isset($user_avatar) and $user_avatar != 'noprofile.jpg'}
            <div id="right">
                <img class="border-radius gray-border" src="{$avatar_url}{$user_avatar}" alt="Profilfoto">
            </div>
        {/if}    
        {if isset($courses)}
          <p>
              <select id='course' name='course' onchange="window.location.assign('index.php?action=backup&course='+this.value);"> {*_blank global regeln*}
                  <option value="-1" data-skip="1">Lehrplan wählen...</option>
                  {section name=res loop=$courses}
                    <option value="{$courses[res]->id}" 
                    {if $courses[res]->id eq $selected_curriculum} selected {/if} 
                    data-icon="{$data_url}subjects/{$courses[res]->icon}" data-html-text="{$courses[res]->group} - {$courses[res]->curriculum}&lt;i&gt;
                    {$courses[res]->description}&lt;/i&gt;">{$courses[res]->group} - {$courses[res]->curriculum}</option>  
                  {/section} 
              </select>    </p>
        {else}<p><strong>Sie haben noch keine Lehrpläne angelegt bzw. noch keine Klassen eingeschrieben.</strong></p>{/if}
        
        {if isset($zipURL)}
        <p><h3>Kurssicherungen</h3></p>
            <p>Folgende Backups können heruntergeladen werden.</p>
        <p>&nbsp;</p>    
        <p><labe ><a class="url_btn floatleft" href={$zipURL} > </a></label> Aktuelle Sicherungsdatei herunterladen.</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        {/if} 
        </form>
        
        {if $data != null}
        {* display pagination header *}
        <p>Datensätze {$fileBackupPaginator.first}-{$fileBackupPaginator.last} von {$fileBackupPaginator.total} werden angezeigt.</p>
        <form id='userlist' method='post' action='index.php?action=backup&next={$currentUrlId}'>
		<table id="contenttable">
		<tr id="contenttablehead">
			<td></td><td>Dateiname</td>
                        <td>Curriculum</td>
                        <td>Datum</td>
                        <td>Erstellt durch</td>
                        <td></td>

		</tr>
                {* display results *}    
                {section name=res loop=$results}
                    <tr class="contenttablerow" id="row{$results[res]->id}" onclick="checkrow({$results[res]->id})">
                       <td><input class="invisible" type="checkbox" id="{$results[res]->id}" name="id[]" value={$results[res]->id} /></td>
                       <td>{$results[res]->file_name}</td>
                       <td>{$results[res]->curriculum}</td>
                       <td>{$results[res]->creation_time}</td>
                       <td>{$results[res]->creator}</td>
                       <td><a href="{$web_backup_url}{$results[res]->file_name}">Herunterladen</a></td>
                    </tr>
                {/section}
		</table>
        {* display pagination info *}
        <p>{paginate_prev id="fileBackupPaginator"} {paginate_middle id="fileBackupPaginator"} {paginate_next id="fileBackupPaginator"}</p>
        </form>
        {/if}
        <p>&nbsp;</p>
        <p>&nbsp;</p>
    </div>
</div>
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}