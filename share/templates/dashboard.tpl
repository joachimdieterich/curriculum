{extends file="base.tpl"}

{block name=title}{$str_dashboard}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}
    
<div class=" border-radius gray-border">	
    <div class="border-top-radius contentheader">{$str_dashboard}</div>
    <div class="space-top-padding gray-gradient border-bottom-radius box-shadow">
    <p><h3>{$str_achievments_headline}</h3></p>
    {if isset($enabledObjectives)} 
    <p>{$str_achievments_txt1}</p>
    <p>&nbsp;</p>
    <table style="width:100%">
        <tr><td><div class="space-left"></div></td><td>
        {foreach key=enaid item=ena from=$enabledObjectives}
            <div>
                <div class="box gray-gradient border-radius box-shadow gray-border boxgreen">
                    <div class="boxheader border-top-radius ">
                        {$ena.curriculum}<!--Kursvergleich--> 
                    </div>
                        <div class=" boxcontent ">
                        {$ena.enablingObjective}<!--{$ena.description}-->
                    <div class="boxfooter border-bottom-radius"> 
                    </div> 
                </div>
           </div>
        {/foreach}
         </td>
        </tr>
    </table>
        {else}<p>{$str_achievments_txt2}</p>{/if}
        <p>&nbsp;</p>
    
    <p><h3>{$str_institution_headline}</h3></p>
    {if isset($myInstitutions)} 
    <form id='institutionlist' method='post' action='index.php?action=dashboard&next={$currentUrlId}'>
        <table id="contenttable">
		<tr id="contenttablehead">
                <td></td>
                <td>{$str_institution}</td>
                <td>{$str_description}</td>
                <td>{$str_institution_schooltype}</td>
                <td>{$str_institution_state}</td>
                <td>{$str_institution_countries}</td>
                <td>{$str_creationtime}</td>
                <td>{$str_creator}</td>
        </tr>
        {* display myInstitutions *}    
        {section name=ins loop=$myInstitutions}
            <tr class="contenttablerow" id="row{$myInstitutions[ins].id}" onclick="checkrow({$myInstitutions[ins].id})">
                <td><input class="invisible" type="checkbox" id="{$myInstitutions[ins].id}" name="id[]" value={$myInstitutions[ins].id} /></td>
                <td>{$myInstitutions[ins].institution}</td>
                <td>{$myInstitutions[ins].description}</td>
                <td>{$myInstitutions[ins].schooltype_id}</td>
                <td>{$myInstitutions[ins].state_id}</td>
                <td>{$myInstitutions[ins].country}</td>
                <td>{$myInstitutions[ins].creation_time}</td>
                <td>{$myInstitutions[ins].creator_id}</td>
            </tr>
        {/section}
        </table>
     {else}<p>{$str_institution_notassigned}</p>{/if}   
    <p>&nbsp;</p>
    
    <p><h3>{$str_classes_headline}</h3></p>
    {if isset($myClasses)}
     <table id="contenttable">
                    <tr id="contenttablehead">
                        <td></td>    
                    <td>{$str_classes_classes}</td>
                    <td>{$str_classes_class}</td>
                    <td>{$str_description}</td>
                    <td>{$str_classes_semester}</td>
                    <td>{$str_institution}</td>
                    <td>{$str_creationtime}m</td>
                    <td>{$str_creator}</td>
            </tr>
            
            {* display myClasses *}    
            {section name=cla loop=$myClasses}
                <tr class="contenttablerow" id="row{$myClasses[cla]->id}" onclick="checkrow({$myClasses[cla]->id})">
                    <td><input class="invisible" type="checkbox" id="{$myClasses[cla]->id}" name="id[]" value={$myClasses[cla]->id} /></td>
                    <td>{$myClasses[cla]->group}</td>
                    <td>{$myClasses[cla]->grade}</td>
                    <td>{$myClasses[cla]->description}</td>
                    <td>{$myClasses[cla]->semester_id}</td>
                    <td>{$myClasses[cla]->institution_id}</td>
                    <td>{$myClasses[cla]->creation_time}</td>
                    <td>{$myClasses[cla]->creator_id}</td>
                </tr>
            {/section}
            
            </table>
    {else}<p>{$str_classes_notassigned}</p>{/if}
    <p>&nbsp;</p>
    
    {* <p><h3>{$str_curriculum_headline}</h3></p>
    {if $curriculumNavMenu != ''}
            {foreach item=cur from=$curriculumNavMenu}
            <li class="contentlist"><p><a href="index.php?action=view&curriculum={$cur.id}">{$cur.curriculum}</a></p></li>
            {/foreach}
        {else}<p>{$str_curriculum_notassigned}</p>
        {/if} 
    <p>&nbsp;</p>*}
    
    <p><h3>{$str_oldcurriculum_headline}</h3></p>
    <p>{$str_oldcurriculum_notavailable} </p>
    <p>&nbsp;</p>
    {if $my_role_id == 1 or $my_role_id == 4}
        <p><h3>Abgelaufene Ziele</h3></p>
    <p>{$cronjob}</p>
    <p>&nbsp;</p>
    {/if}
    <p><h3>{$str_manuals}</h3></p>
    <p>&nbsp;</p>
    {if $my_role_id == 1 or $my_role_id == 4}
    <p><a class="pdf_btn floatleft" href="{$support_url}doc_curriculum.joachimdieterich.de_admin.pdf"></a> {$str_manuals_institution}</p>
    <p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>
    {/if}
    {if $my_role_id == 1 or $my_role_id == 3}
    <p><a class="pdf_btn floatleft" href="{$support_url}doc_curriculum.joachimdieterich.de_teacher.pdf"></a> {$str_manuals_teacher}</p>
    <p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>
    {/if}
    {if $my_role_id == 1 or $my_role_id == 0}
    <p><a class="pdf_btn floatleft" href="{$support_url}doc_curriculum.joachimdieterich.de_student.pdf"></a> {$str_manuals_student}</p>
    <p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>
    {/if}
    </div>
    
</div>
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}