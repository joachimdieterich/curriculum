{extends file="base.tpl"}

{block name=title}{$page_title}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}
    
{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}
    
<div class=" border-radius gray-border">	
    <div class="border-top-radius contentheader">{$page_title}</div>
    <div class="space-top-padding gray-gradient border-bottom-radius box-shadow" >
    
    {if isset($results)}
    {* display pagination header *}
        <div> <p>Datensätze {$userPaginator.first}-{$userPaginator.last} von {$userPaginator.total} werden angezeigt.</p>
		<table id="contentsmalltable">
		<tr id="contenttablehead">
                    <td></td>
                        <td>Vorname</td>
                        <td>Nachname</td>
		</tr>
                {* display results *}    
                {section name=res loop=$results}
                    <tr class="{if isset($selected_user_id) AND $selected_user_id eq $results[res]->id} activecontenttablerow {else}contenttablerow{/if}" id="row{$smarty.section.res.index}" onclick="window.location.assign('index.php?action=report&id='+document.getElementById('userID{$smarty.section.res.index}').value);">
                       <td><input class="invisible" type="checkbox" id="userID{$smarty.section.res.index}" name="userID" value={$results[res]->id} {if isset($selected_user_id) AND $selected_user_id eq $results[res]->id} checked{/if}/></td>
                       <td>{$results[res]->firstname}</td>
                       <td>{$results[res]->lastname}</td>
                    </tr>
                {/section}
		</table>
        {* display pagination info *}
        <p>{paginate_prev id="userPaginator"} {paginate_middle id="userPaginator"} {paginate_next id="userPaginator"}</p>
        </div>
       {/if} 
        
         <div> <p>Abgeschlossene Ziele</p>
            <canvas id="canvas" width="800" height="400"></canvas>
        </div> 
    </div>
    
    {literal}<script>
    
        var lineChartData = {
            labels : [ {/literal}{$report_acc_date}{literal}],
            datasets : [{           
                            fillColor : "rgba(151,187,205,0.5)",
                            strokeColor : "rgba(151,187,205,1)",
                            pointColor : "rgba(151,187,205,1)",
                            pointStrokeColor : "#fff", 
                            data : [{/literal}{$report_id}{literal}]
                    },]	
        }

	var myLine = new Chart(document.getElementById("canvas").getContext("2d")).Line(lineChartData);
	</script>
    {/literal}
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}
