{extends file="base.tpl"}

{block name=title}Kompetenzraster: {$course[0]->curriculum}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}
<div class="border-radius gray-border">	
    
    {foreach key=curid name=curriculum item=con from=$course}
    <div class="border-top-radius contentheader">Lehrplan: {$con->curriculum} (Klasse {$con->grade}: {$con->subject})
        <div class="printbtn floatright" onclick="printPage('printContent');"> </div></div>
    
    <div class="space-top-padding gray-gradient border-bottom-radius box-shadow"><p>Beschreibung: {$con->description} ({$con->schooltype})</p>
            <p>Bundesland: {$con->state} ({$con->country})</p>
            
        <div id="printContent" class="scroll">            
            <!--For printing only-->
            <div class="printOnly" >
                <div id="printHeader"><p>{$app_title} :: {$con->curriculum}</p></div>
                <div id="printUser">
                    <!--<div class="printUserHeader">Benutzer</div>-->
                    <div><img id="printUserImg" src="{if isset($avatar)}{$avatar_url}{$avatar}{/if}"></div>
                    <div><p><strong>{if isset($firstname)}{$firstname}{/if} {if isset($lastname)}{$lastname}{/if}</strong></p>
                    <p>{if isset($group)}{$group->groups}</p>
                    <p>{$group->semester}{/if}</p>    
                    <p class="printUserLogin">Letzter Login: {if isset($last_login)}{$last_login}{/if}</p></div> 
              </div>
             <!-- end For printing only-->       
            </div>
             <table> <!-- sollte per css noch unten einen abstand zum nächsthöheren div bekommen-->
                {if $terminal_objectives != false}
                 {foreach key=terid item=ter from=$terminal_objectives}
                    <tr><td class="boxleftpadding"><div class="box gray-gradient border-radius box-shadow gray-border ">
                                <div class="boxheader border-top-radius">
                                {if isset($showaddObjectives)}
                                        <input class="deletebtn floatright" type="button" name="delete" onclick="deleteObjective({$con->curriculum_id},{$ter->id})">
                                        <input class="editbtn floatright" type="button" name="edit" onclick="editObjective({$con->curriculum_id},{$ter->id})">
                                        <input class="upbtn" type="button" name="delete" onclick="order('down', {$ter->order_id},{$con->curriculum_id},{$ter->id})" />
                                        {*{$ter->order_id}*}
                                {/if}<!--Thema-->
                                </div>
                                <div id="Anker_{$ter->id}" class="boxwrap">
                                    <div class="boxscroll">
                                        <div class="boxcontent">
                                            {$ter->terminal_objective}<!--{$ter->description}-->
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="boxfooter border-bottom-radius">
                                    {if isset($showaddObjectives)}
                                        <input class="downbtn" type="button" name="delete" onclick="order('up', {$ter->order_id},{$con->curriculum_id},{$ter->id})" />
                                    {/if}
                                </div> 
                            </div>
                        </td>
                        {if $enabledObjectives != false}
                            {foreach key=enaid item=ena from=$enabledObjectives}
                            {if $ena->terminal_objective_id eq $ter->id}
                            <td id="{$con->curriculum_id}&{$ter->id}&{$ena->id}">
                            <div class="box gray-gradient border-radius box-shadow gray-border {if $ena->accomplished_status_id eq 1} boxgreen {else} boxred{/if}">
                                <div class="boxheader border-top-radius ">
                                    {if isset($ena->accomplished_users) and isset($ena->enroled_users) and isset($ena->accomplished_percent)}
                                        {$ena->accomplished_users} von {$ena->enroled_users} ({$ena->accomplished_percent}%)<!--Ziel--> 

                                    {/if}
                                    {if !isset($showaddObjectives)}
                                        <a href="assets/scripts/libs/modal-upload/uploadframe.php?userID={$my_id}&context=userView&curID={$con->curriculum_id}&terID={$ter->id}&enaID={$ena->id}&placeValuesBeforeTB_=savedValues&TB_iframe=true&width=700&modal=true" class="thickbox">
                                        <input class="addsolutionbtn floatright" type="button" name="addMaterial"></a>
                                        {if $solutions != false}
                                            {foreach key=solID item=sol from=$solutions}
                                                {if $sol->enabling_objective_id eq $ena->id}
                                                    <input class="okbtn floatright" type="button" name="submitedMaterial"> 
                                                {/if}
                                            {/foreach}
                                        {/if}

                                    {/if} {if isset($showaddObjectives)}
                                        <input class="deletebtn floatright" type="button" name="delete" onclick="deleteObjective({$con->curriculum_id},{$ter->id},{$ena->id})" />
                                        <input class="editbtn floatright" type="button" name="edit" onclick="editObjective({$con->curriculum_id},{$ter->id},{$ena->id})">
                                {/if}   

                                </div>
                                <div class="boxwrap">
                                    <div class="boxscroll">
                                    <div class="boxcontent">
                                        {$ena->enabling_objective}<!--{$ena->description}-->
                                    </div>
                                    </div>
                                </div>
                                <div class="boxfooter border-bottom-radius">
                                    {if isset($showaddObjectives)}
                                        
                                        <a href="assets/scripts/libs/modal-upload/uploadframe.php?userID={$my_id}&context=curriculum&curID={$con->curriculum_id}&terID={$ter->id}&enaID={$ena->id}&placeValuesBeforeTB_=savedValues&TB_iframe=true&width=700&modal=true" class="thickbox">
                                        <input class="addmaterialbtn floatright" type="button" name="addMaterial"></a>
                                        <input class="editbtn floatright" type="button" name="editMaterial" onclick="editMaterial({$con->curriculum_id},{$ter->id},{$ena->id})">
                                    {/if}  
                                    <a class="text" onclick="showMaterial({$con->curriculum_id}, {$ter->id}, {$ena->id})">Material</a>
                                </div> 

                            </td>
                            </div>{/if}
                            {/foreach}
                        {/if}
                {if isset($showaddObjectives)}       
                 <td><div class="box gray-gradient border-radius box-shadow gray-border ">
                         <div class="boxheader border-top-radius ">
                            <p><input class="addbtn floatright" type="button" name="addenablingObjectiveButton" onclick="addenablingObjective({$con->curriculum_id},{$ter->id})"></p>
                            <label class="boxleftpadding">Ziel hinzufügen</label>
                         </div>
                 </td>   
                         
                {/if}  
            </tr>
            {/foreach}
           {/if}
                {if isset($showaddObjectives)}       
                 <td class="boxleftpadding"><div class="box gray-gradient border-radius box-shadow gray-border ">
                      <div class="boxheader border-top-radius ">   
                         <p><input class="addbtn floatright" type="button" name="addterminalObjectiveButton" onclick="addterminalObjective({$con->curriculum_id})"> </p>
                         <label class="boxleftpadding">Thema hinzufügen</label>
                      </div>
                 </td>                
                {/if}
       
          {/foreach}		
        </table>
        <p>&nbsp;</p>
        <!--For printing only-->
        <div id="printFooter" >
            <table>
                <tr>
                    <td><p>Erklärungen:</p></td>
                    <td><div class="boxgreen boxlegende"></div></td>
                    <td><p>Ziel wurde erreicht</p>
                    <td><div class="boxred boxlegende"></div></td>
                    <td><p>Ziel wurde noch nicht erreicht</p></td>
                </tr>
            </table>
            
        </div>
        <!--end For printing only-->
        </div>                
    </div> 
</div>
        
 <!--jump to actual row-->      
{if isset($scrollto)}
<script type="text/javascript">
window.location.hash="Anker_{$scrollto}"; //diese funktion global machen  ??? --> das manuelle scrollen ist sonst sehr nervig!
</script>      
{/if}
            
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}