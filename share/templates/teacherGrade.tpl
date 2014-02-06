{extends file="base.tpl"}

{block name=title}{$str_adminGrade}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}
    
<div class=" border-radius gray-border">	
    <div class="border-top-radius contentheader ">{$str_adminGrade}</div>
    <div class="space-top-padding gray-gradient border-bottom-radius box-shadow ">
        
        {if !isset($showGradeForm)}
        <p class="floatleft gray-gradient cssimgbtn border-radius gray-border">
            <a class="addbtn cssbtnmargin cssbtntext" href="index.php?action=teacherGrade&function=newGrade">Klassenstufe hinzufügen</a>
        </p>
        
        {/if}
        <p>&nbsp;</p><p>&nbsp;</p>
        {if isset($showGradeForm)}
        <form id='addGrade' method='post' action='index.php?action=teacherGrade&next={$currentUrlId}'>
        <input type='hidden' name='id' id='id' {if isset($id)}value='{$id}'{/if} /></p>   
        <p><label>{$str_adminGrade_addGradeName}</label><input class='inputformlong' type='text' name='grade' id='grade' {if isset($grade)}value='{$grade}'{/if} /></p>   
        {validate_msg field='grade'}
	<p><label>{$str_description}</label><input class='inputformlong' type='description' name='description' {if isset($description)}value='{$description}'{/if}/></p>
        {validate_msg field='description'}
        {if count($my_institutions['id']) > 1}
            <p><label>Institution / Schule*: </label>{html_options id='institution' name='institution' values=$my_institutions['id'] output=$my_institutions['institution']}</p>
        {elseif count($my_institutions['id']) eq 0}
            <p><strong>Sie müssen zuerst eine Institution anlegen</strong></p>
        {else}
            <input type='hidden' name='institution' id='institution' value='{$my_institutions['id'][0]}' /></p>       
        {/if}
        
        <script type='text/javascript'>
	document.getElementById('grade').focus();
	</script>
        
        {if !isset($showeditGradeForm)}
        <p><label></label><input type='submit' name="addGrade" value='{$str_adminGrade_addbtn}' /></p>
        {else}
        <p><label></label><input type='submit' name="back" value='zurück'/><input type='submit' name="updateGrade" value='Klassenstufe aktualisieren' /></p>
        {/if}
	</form>	
        {/if}
         
        <form id='classlist' method='post' action='index.php?action=teacherGrade&next={$currentUrlId}'>
            <p>&nbsp;</p>
    {if $data != null}
        {* display pagination header *}
        <p class="floatright">{$str_adminGrade_pagItem} {$gradePaginator.first}-{$gradePaginator.last} {$str_adminGrade_pagTo} {$gradePaginator.total}</p>   
            <table id="contenttable">
                    <tr id="contenttablehead">
                    <td></td>    
                    <td>{$str_adminGrade_Grade}</td>
                    <td>{$str_description}</td>
                    <td class="td_options">Optionen</td>
            </tr>
            {section name=grade loop=$grade_list}{* display results *}
                <tr class="contenttablerow" id="row{$grade_list[grade]->id}" onclick="checkrow({$grade_list[grade]->id})">
                    <td><input class="invisible" type="checkbox" id="{$grade_list[grade]->id}" name="id[]" value={$grade_list[grade]->id} /></td>
                    <td>{$grade_list[grade]->grade}</td>
                    <td>{$grade_list[grade]->description}</td>
                    <td class="td_options">
                        <a class="deletebtn floatright" type="button" name="delete" onclick="del('grade',{$grade_list[grade]->id})"></a>
                        <a class="editbtn floatright" href="index.php?action=teacherGrade&edit=true&id={$grade_list[grade]->id}"></a>
                        </td>
                </tr>
            {/section}
            </table>  
            <input class="invisible" type="checkbox" name="id[]" value="none" checked /><!--Hack für problem, dass kein Array gepostet wird, wenn nichts angewählt wird-->
            {* display pagination info *}
            <p class="floatright">{paginate_prev id="gradePaginator"} {paginate_middle id="gradePaginator"} {paginate_next id="gradePaginator"}</p>
            <p>&nbsp;</p>
        {/if}
        </form>              
        
</div>
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}