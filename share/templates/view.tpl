{extends file="base.tpl"}

{block name=title}Kompetenzraster: {$course[0]->curriculum}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}

<div class="border-box">    
    {foreach key=curid name=curriculum item=con from=$course}
    <div class="contentheader">Lehrplan: {$con->curriculum} ({$con->grade}: {$con->subject})</div>
    
    <div><p>Beschreibung: {$con->description} ({$con->schooltype})</p>
         <p>Bundesland: {$con->state} ({$con->country})</p>
        {*<form name="file" enctype="multipart/form-data" action="index.php?action=view&function=addObjectives" method="post">  
            <input type='hidden' name='curriculum_id' value='{$con->curriculum} '/>
            <p><label>Themen/Ziele importieren: </label><input type="file" name="datei" value=""><input type="submit" name="import" value="Importieren"> </p>    
        </form>*} 
        <div id="printContent" class="scroll">     
             <table> <!-- sollte per css noch unten einen abstand zum nächsthöheren div bekommen-->
                {if $terminal_objectives != false}
                 {assign var="sol_btn" value="false"}   
                 {foreach key=terid item=ter from=$terminal_objectives}
                    <tr><td class="boxleftpadding"><div class="box gray-border gray-gradient">
                                <div class="boxheader">
                                {if isset($showaddObjectives)}
                                        <input class="deletebtn floatright" type="button" name="delete" onclick="deleteObjective({$con->curriculum_id},{$ter->id})">
                                        <input class="editbtn floatright" type="button" name="edit" onclick="editObjective({$con->curriculum_id},{$ter->id})">
                                        {if $ter->order_id neq '1'}
                                            <input class="upbtn" type="button" name="orderdown" onclick="order('down', {$ter->order_id},{$con->curriculum_id},{$ter->id})" />
                                        {/if}
                                        {*{$ter->order_id}*}
                                {/if}<!--Thema-->
                                </div>
                                <div id="ter_{$ter->id}" class="boxwrap">
                                    <div class="boxscroll">
                                        <div class="boxcontent">
                                            {$ter->terminal_objective}<!--{$ter->description}-->
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="boxfooter">
                                    {if isset($showaddObjectives)}
                                        <input class="downbtn" type="button" name="orderup" onclick="order('up', {$ter->order_id},{$con->curriculum_id},{$ter->id})" />
                                    {/if}
                                </div> 
                            </div>
                        </td>
                        {if $enabledObjectives != false}
                            {foreach key=enaid item=ena from=$enabledObjectives}
                            {if $ena->terminal_objective_id eq $ter->id}
                            <td id="{$con->curriculum_id}&{$ter->id}&{$ena->id}">
                            <div class="box gray-border {if $ena->accomplished_status_id eq 1} boxgreen {elseif $ena->accomplished_status_id eq 2} boxorange {elseif $ena->accomplished_status_id eq '0'} boxred {else} box {/if}">
                                <div class="boxheader ">
                                    {if checkCapabilities('groups:showAccomplished', $my_role_id, false)}
                                        {if isset($ena->accomplished_users) and isset($ena->enroled_users) and isset($ena->accomplished_percent)}
                                            {$ena->accomplished_users} von {$ena->enroled_users} ({$ena->accomplished_percent}%)<!--Ziel-->  
                                        {/if}
                                    {/if}
                                    {if !isset($showaddObjectives) AND checkCapabilities('file:solutionUpload', $my_role_id, false)}
                                        {if checkCapabilities('file:upload', $my_role_id, false)}
                                            <a href="assets/scripts/libs/modal-upload/uploadframe.php?userID={$my_id}&token={$my_token}&last_login={$my_last_login}&context=userView&curID={$con->curriculum_id}&terID={$ter->id}&enaID={$ena->id}&placeValuesBeforeTB_=savedValues&TB_iframe=true&width=700&modal=true" class="thickbox">
                                            <input class="addsolutionbtn floatright" type="button" name="addMaterial"></a>
                                        {/if}    
                                        {if $solutions != false}
                                            {foreach key=solID item=sol from=$solutions}
                                                {if $sol->enabling_objective_id eq $ena->id AND $sol_btn neq $ena->id}
                                                    <input class="okbtn floatright" type="button" name="submitedMaterial"> 
                                                    {assign var="sol_btn" value=$ena->id}
                                                {/if}
                                            {/foreach}
                                        {/if}
                                    {/if}
                                    {if isset($showaddObjectives)}
                                        <input class="rightbtn floatright" type="button" name="orderright" onclick="order('up', {$ena->order_id},{$con->curriculum_id},{$ter->id},{$ena->id})" />
                                        <input class="deletebtn floatright" type="button" name="delete" onclick="deleteObjective({$con->curriculum_id},{$ter->id},{$ena->id})" />
                                        <input class="editbtn floatright" type="button" name="edit" onclick="editObjective({$con->curriculum_id},{$ter->id},{$ena->id})">
                                        {if $ena->order_id neq '1'}
                                            <input class="leftbtn" type="button" name="orderleft" onclick="order('down', {$ena->order_id},{$con->curriculum_id},{$ter->id},{$ena->id})" />
                                        {/if}
                                     {/if}   
                                </div>
                                <div id="ena_{$ena->id}" class="boxwrap">
                                    <div class="boxscroll">
                                    <div class="boxcontent">    
                                        {$ena->enabling_objective}<!--{$ena->description}-->
                                    </div>
                                    </div>
                                </div>
                                <div class="boxfooter">
                                    {if isset($showaddObjectives)}
                                        {if checkCapabilities('file:upload', $my_role_id, false)}
                                            <a href="assets/scripts/libs/modal-upload/uploadframe.php?userID={$my_id}&token={$my_token}&last_login={$my_last_login}&context=curriculum&curID={$con->curriculum_id}&terID={$ter->id}&enaID={$ena->id}&placeValuesBeforeTB_=savedValues&TB_iframe=true&width=700&modal=true" class="thickbox">
                                            <input class="addmaterialbtn floatright" type="button" name="addMaterial"></a>
                                        
                                        {/if} 
                                        {if checkCapabilities('file:editMaterial', $my_role_id, false)}
                                            <input class="editbtn floatright" type="button" name="editMaterial" onclick="editMaterial({$con->curriculum_id},{$ter->id},{$ena->id})">
                                        {/if}    
                                    {else}
                                        {if checkCapabilities('user:getHelp', $my_role_id, false)}
                                            <input class="helpbtn floatright" type="button" name="help" onclick="getHelp({$page_group}, {$con->curriculum_id},{$ter->id},{$ena->id})">
                                        {/if}    
                                    {/if}  
                                    {if checkCapabilities('file:loadMaterial', $my_role_id, false)}
                                        <a class="text" onclick="showMaterial({$con->curriculum_id}, {$ter->id}, {$ena->id})">Material</a>
                                    {/if}
                                    
                                </div> 

                            </td>
                            </div>{/if}
                            {/foreach}
                        {/if}
                {if isset($showaddObjectives)}       
                 <td><div class="box gray-border ">
                         <div class="boxheader">
                            <p><input class="addbtn floatright" type="button" name="addenablingObjectiveButton" onclick="addenablingObjective({$con->curriculum_id},{$ter->id})"></p>
                            <label class="boxleftpadding">Ziel hinzufügen</label>
                         </div>
                 </td>   
                         
                {/if}  
            </tr>
            {/foreach}
           {/if}
                {if isset($showaddObjectives)}       
                 <td class="boxleftpadding"><div class="box gray-border ">
                      <div class="boxheader ">   
                         <p><input class="addbtn floatright" type="button" name="addterminalObjectiveButton" onclick="addterminalObjective({$con->curriculum_id})"> </p>
                         <label class="boxleftpadding">Thema hinzufügen</label>
                      </div>
                 </td>                
                {/if}
       
          {/foreach}		
        </table>
        <p>&nbsp;</p>
        </div>                
    </div> 
</div>
        
 <!--jump to actual row-->      
{if isset($scrollto)}
<script type="text/javascript">
window.location.hash="{$scrollto}"; //todo: diese funktion auf allen Seiten implementieren --> das manuelle scrollen ist sehr nervig!
</script>      
{/if}
            
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}