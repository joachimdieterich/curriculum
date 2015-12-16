{extends file="base.tpl"}

{block name=title}Kompetenzraster: {$course[0]->curriculum}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}

<div class="border-box">    
    {foreach key=curid name=curriculum item=con from=$course}
    <div class="contentheader">Lehrplan: {$con->curriculum} ({$con->grade}: {$con->subject})<input class="curriculumdocsbtn floatright" type="button" name="help" onclick="{if isset($showaddObjectives)}curriculumdocs('http://docs.joachimdieterich.de/index.php?title=Lehrplan_anlegen'){else}curriculumdocs('http://docs.joachimdieterich.de/index.php?title=Lehrplan'){/if};"/></div>
    <div><p>Beschreibung: {$con->description} ({$con->schooltype})</p>
        <p>Bundesland: {$con->state} ({$con->country})</p>
        <div id="printContent" class="scroll space-bottom">     
             <table> 
                {if $terminal_objectives != false}
                 {assign var="sol_btn" value="false"}   
                 {foreach key=terid item=ter from=$terminal_objectives}
                    <tr>
                        <td class="boxleftpadding" ><div class="box gray-border gray-gradient" >
                            <div class="boxheader" style="background: {$ter->color}">
                            {if isset($showaddObjectives)}
                                    <input class="deletebtn floatright" type="button" name="delete" onclick="del('terminalObjectives', {$ter->id}, {$my_id})">
                                    <input class="editbtn floatright" type="button" name="edit" onclick="editObjective({$con->curriculum_id},{$ter->id})">
                                    {if $ter->order_id neq '1'}
                                        <input class="upbtn" type="button" name="orderdown" onclick="order('down', {$ter->order_id},{$con->curriculum_id},{$ter->id})" />
                                    {/if}
                                        <input class="downbtn" type="button" name="orderup" onclick="order('up', {$ter->order_id},{$con->curriculum_id},{$ter->id})" />
                            {/if}<!--Thema-->
                            </div>
                            <div id="ter_{$ter->id}" class="boxwrap">
                                <div class="boxscroll">
                                    <div class="boxcontent">
                                        {$ter->terminal_objective}
                                    </div>
                                </div>
                            </div>

                            <div class="boxfooter">
                                <input class="infobtn floatright" type="button" name="descript" onclick="showDescription('{$ter->id}')">
                                {if isset($showaddObjectives)}
                                    {if checkCapabilities('badge:addBadge', $my_role_id, false)}
                                        <input class="bdgbtn floatright" type="button" name="badge" onclick="badge('{$con->curriculum_id}','{$ter->id}','{$my_id}','{$my_token}','{$my_last_login}')">
                                    {/if}
                                    {if checkCapabilities('file:upload', $my_role_id, false)}
                                        <a href="../share/request/uploadframe.php?userID={$my_id}&last_login={$my_last_login}&context=curriculum&curID={$con->curriculum_id}&terID={$ter->id}{$tb_param}" class="thickbox">
                                        <input class="addmaterialbtn floatright" type="button" name="addMaterial"></a>                        
                                    {/if} 
                                {else}
                                    {if checkCapabilities('badge:getBadge', $my_role_id, false)}
                                        <input class="bdgbtn floatright" type="button" name="getBadge" onclick="getBadge({$con->curriculum_id},{$ter->id})">
                                    {/if}
                                {/if}

                             {if checkCapabilities('file:loadMaterial', $my_role_id, false)}
                                    <a class="text pointer_hand" onclick="showMaterial({if isset($showaddObjectives)}true{else}false{/if},{$ter->id})">Material</a>
                                {/if}

                            </div> 
                            </div>
                        </td>
                        {*Ziele*}
                        {if $enabledObjectives != false}
                            {foreach key=enaid item=ena from=$enabledObjectives}
                            {if $ena->terminal_objective_id eq $ter->id}
                            <td id="{$con->curriculum_id}&{$ter->id}&{$ena->id}">
                            <div class="box gray-border {if $ena->accomplished_status_id eq 1} boxgreen {elseif $ena->accomplished_status_id eq 2} boxorange {elseif $ena->accomplished_status_id eq '0'} boxred {else} box {/if}">
                                <div class="boxheader" style="background: {$ter->color}">
                                    {if checkCapabilities('groups:showAccomplished', $my_role_id, false)}
                                        {if isset($ena->accomplished_users) and isset($ena->enroled_users) and isset($ena->accomplished_percent)}
                                            {$ena->accomplished_users} von {$ena->enroled_users} ({$ena->accomplished_percent}%)<!--Ziel-->  
                                        {/if}
                                    {/if}
                                    {if !isset($showaddObjectives) AND checkCapabilities('user:getHelp', $my_role_id, false)}
                                        <input class="helpbtn floatright" type="button" name="help" onclick="getHelp({$page_group}, {$ena->id})">
                                    {/if} 
                                    {if !isset($showaddObjectives) AND checkCapabilities('file:solutionUpload', $my_role_id, false)}
                                        {if $solutions != false}
                                            {foreach key=solID item=sol from=$solutions}
                                                {if $sol->enabling_objective_id eq $ena->id AND $sol_btn neq $ena->id}
                                                    {*<input class="ok2btn floatright" type="button" name="submitedMaterial"> *}
                                                    {assign var="sol_btn" value=$ena->id}
                                                {/if}
                                            {/foreach}
                                        {/if}
                                        {if checkCapabilities('file:upload', $my_role_id, false)}
                                            <a href="../share/request/uploadframe.php?userID={$my_id}&last_login={$my_last_login}&context=userView&curID={$con->curriculum_id}&terID={$ter->id}&enaID={$ena->id}{$tb_param}" class="thickbox">
                                            <input class="{if $sol_btn eq $ena->id OR $sol_btn eq false}ok2btn{else}addsolutionbtn{/if} floatright" type="button" name="addMaterial"></a>
                                        {/if}  
                                    {/if}
                                    {if isset($showaddObjectives)}
                                        <input class="rightbtn floatright" type="button" name="orderright" onclick="order('up', {$ena->order_id},{$con->curriculum_id},{$ter->id},{$ena->id})" />
                                        <input class="deletebtn floatright" type="button" name="delete" onclick="del('enablingObjectives', {$ena->id}, {$my_id})" />
                                        <input class="editbtn floatright" type="button" name="edit" onclick="editObjective({$con->curriculum_id},{$ter->id},{$ena->id})">
                                        {if $ena->order_id neq '1'}
                                            <input class="leftbtn" type="button" name="orderleft" onclick="order('down', {$ena->order_id},{$con->curriculum_id},{$ter->id},{$ena->id})" />
                                        {/if}
                                     {/if}   
                                </div>
                                <div id="ena_{$ena->id}" class="boxwrap">
                                    <div class="boxscroll"> 
                                        <div class="boxcontent">   
                                            {$ena->enabling_objective}
                                        </div>
                                    </div>
                                </div>
                                <div class="boxfooter">
                                     <input class="infobtn floatright" type="button" name="descript" onclick="showDescription('{$ter->id}','{$ena->id}')">
                                    {if isset($showaddObjectives)}
                                        {*<input class="bdgbtn floatright" type="button" name="badge" onclick="badge('{$con->curriculum_id}','{$ter->id}','{$ena->id}','{$my_id}','{$my_token}','{$my_last_login}')">*}
                                        <input class="checkbtn floatright" type="button" name="addtQuiz" onclick="addQuiz({$con->curriculum_id},{$ter->id},{$ena->id})">
                                        {if checkCapabilities('file:upload', $my_role_id, false)}
                                            <a href="../share/request/uploadframe.php?userID={$my_id}&last_login={$my_last_login}&context=curriculum&curID={$con->curriculum_id}&terID={$ter->id}&enaID={$ena->id}{$tb_param}" class="thickbox">
                                            <input class="addmaterialbtn floatright" type="button" name="addMaterial"></a>
                                        {/if} 
                                        
                                    {else}
                                        {*<input class="bdgbtn floatright" type="button" name="getBadge" onclick="getBadge({$con->curriculum_id},{$ter->id},{$ena->id})">*}
                                        {if checkCapabilities('quiz:showQuiz', $my_role_id, false)}
                                            <input class="checkbtn floatright" type="button" name="getQuiz" onclick="showQuiz({$con->curriculum_id},{$ter->id},{$ena->id})">
                                        {/if}
                                    {/if}  
                                    {if checkCapabilities('file:loadMaterial', $my_role_id, false)}
                                        <a class="text pointer_hand" onclick="showMaterial({if isset($showaddObjectives)}true{else}false{/if},{$ter->id}, {$ena->id})">Material</a>
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
        </div>                
    </div> 
</div>
        
 <!--jump to actual row-->      
{if isset($scrollto)}
    <script type="text/javascript"> window.location.hash="{$scrollto}"; </script>            
{/if}

{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}