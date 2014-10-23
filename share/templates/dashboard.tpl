{extends file="base.tpl"}

{block name=title}{$str_dashboard}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}
    
<div class="border-box">
    <div class="contentheader">{$str_dashboard}</div>
    <div>
    <p><h3>{$str_achievments_headline}</h3></p>
    {if isset($enabledObjectives)} 
    <p>{$str_achievments_txt1}</p>
    <p>&nbsp;</p>
    <table style="width:99%">
        <tr><td><div class="space-left"></div></td><td class="boxleftpadding">
        {foreach key=enaid item=ena from=$enabledObjectives}
            <div>
                <div class="box gray-gradient gray-border boxgreen">
                    <div class="boxheader">
                        {$ena->curriculum}<!--Kursvergleich--> 
                    </div>
                    <div class="boxscroll">
                        <div class="boxcontent">
                         {$ena->enabling_objective}<!--{$ena->description}-->
                        <div class="boxfooter"> 
                        </div>
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
            <tr class="contenttablerow" id="row{$myInstitutions[ins]->id}" onclick="checkrow({$myInstitutions[ins]->id})">
                <td><input class="invisible" type="checkbox" id="{$myInstitutions[ins]->id}" name="id[]" value={$myInstitutions[ins]->id} /></td>
                <td>{$myInstitutions[ins]->institution}</td>
                <td>{$myInstitutions[ins]->description}</td>
                <td>{$myInstitutions[ins]->schooltype_id}</td>
                <td>{$myInstitutions[ins]->state_id}</td>
                <td>{$myInstitutions[ins]->country}</td>
                <td>{$myInstitutions[ins]->creation_time}</td>
                <td>{$myInstitutions[ins]->creator_id}</td>
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
    {if checkCapabilities('page:showCronjob', $my_role_id, false)}
        <p><h3>Abgelaufene Ziele</h3></p>
    <p>{$cronjob}</p>
    <p>&nbsp;</p>
    {/if}
    <p><h3>{$str_manuals}</h3></p>
    <p>&nbsp;</p>
    {if checkCapabilities('page:showAdminDocu', $my_role_id, false)}
    <p><a class="pdf_btn floatleft" href="{$support_url}http://www.joachimdieterich.de/curriculum_supportfiles/documentation/doc_curriculum_joachimdieterich.de-Admin.pdf"></a> {$str_manuals_institution}</p>
    <p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>
    {/if}
    {if checkCapabilities('page:showTeacherDocu', $my_role_id, false)}
    <p><a class="pdf_btn floatleft" href="{$support_url}http://www.joachimdieterich.de/curriculum_supportfiles/documentation/doc_curriculum_joachimdieterich.de-Teacher.pdf"></a> {$str_manuals_teacher}</p>
    <p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>
    {/if}
    {if checkCapabilities('page:showStudentDocu', $my_role_id, false)}
    <p><a class="pdf_btn floatleft" href="{$support_url}http://www.joachimdieterich.de/curriculum_supportfiles/documentation/doc_curriculum_joachimdieterich.de-Student.pdf"></a> {$str_manuals_student}</p>
    <p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>
    {/if}
    </div>
    
</div>
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}