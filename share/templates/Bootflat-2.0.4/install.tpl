{extends file="base.tpl"}

{block name=title}{$page_title}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}
{block name=additional_scripts}{$smarty.block.parent}{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}
{content_header p_title=$page_title help=''}      
<div class="documents">	
    <div class="row">
        <div class="col-xs-offset-1 col-xs-10 panel" style="min-height:550px;">
            <form class="panel-body col-xs-8" method='post' action='index.php?action=install' >   
                <input type='hidden' name='step' id='step' {if isset($step)}value='{$step}'{/if} />  
                {if isset($alert)}
                <div class="alert alert-danger">
                    {$alert}
                </div>
                {/if}
                {if isset($success)}
                <div class="alert alert-success">
                    {$success}
                </div>
                {/if}
                {if $step == 0}
                {*Serverdaten-Datenbank *}
                    <div class="text-center">
                            <img src="assets/images/favicon/apple-touch-icon-57x57.png"></img> <br>
                            <b>{$app_title}</b>
                            <p>Copyright © 2012 onwards Joachim Dieterich (http://www.joachimdieterich.de)</p>
                    </div><br>
                    <p>Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), 
                       to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, 
                       and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
                       The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
                       <br><br>
                       THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF 
                       MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, 
                       DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR 
                       THE USE OR OTHER DEALINGS IN THE SOFTWARE.</p><br>
                    <p>See the curriculum page for full details: http://www.curriculumonline.de<br><br>
                    <p><label class="col-xs-1"><input class="centervertical" type="checkbox" name="license"/></label>Hiermit bestätige ich die Lizenzbedingungen.</p>
                    <br>
                    <button type='submit' name='step_0' value='' class="btn btn-default pull-right">
                                <span class="fa fa-arrow-right" aria-hidden="true"></span> weiter
                    </button>
                {/if}
                {if $step == 1}
                {*Serverdaten-Datenbank *}
                    <p><h3>Serverumgebung</h3></p>
                    <br>
                    <div class="form-horizontal col-xs-8">
                        {Form::input_text('db_host', 'DB Host', $db_host, $error, '127.0.0.1')}
                        {Form::input_text('db_user', 'DB User', $db_user, $error, 'database username')}
                        {Form::input_text('db_password', 'DB Password', $db_password, $error, 'database password', 'password')}
                        {Form::input_text('db_name', 'DB Name', $db_name, $error, 'database name')}
                        {Form::input_text('dataroot', 'Datenpfad', $dataroot, $error, 'e.g. /var/www/dataroot/ (folder should be outside of htmlroot)')}
                        <button type='submit' name='step_1' value='' class="btn btn-default pull-right">
                                <span class="fa fa-arrow-right" aria-hidden="true"></span> weiter
                        </button>
                    </div>
                {/if}

                {if $step == 2}
                    {*Serverdaten*}
                    <p><h3>Titel und URL</h3></p>
                    <br>
                    <div class="form-horizontal col-xs-8">
                        {Form::input_text('app_title', 'Name der Seite', $app_title, $error, 'curriculum')}
                        {Form::input_text('app_url', 'URL-Pfad', $app_url, $error, 'curriculum')}
                        {Form::input_text('app_path', 'Pfad zum Datenverzeichnis', $app_path, $error, '/var/www/vhosts/meinedomain.de/curriculumdata')}
                        {*<p><label>Beispieldaten installieren</label><input class="centervertical" type="checkbox" name="demo"/></p>{*not yet available - dedication incorrect*}
                        <button type='submit' name='step_2' value='' class="btn btn-default pull-right">
                                <span class="fa fa-arrow-right" aria-hidden="true"></span> weiter
                        </button>
                    </div>
                {/if}

                {if $step == 3}
                    {*Serverdaten*}
                    <p>&nbsp;</p>
                    <p><h3>Institution</h3></p>
                    <input type='hidden' name='demo' id='demo' {if isset($demo)}value='{$demo}'{/if} />   
                    <p><label>Institution / Schule*: </label><input class='inputlarge' type='text' name='institution' id='institution' {if isset($institution)}value='{$institution}'{/if} /></p> 
                    {*validate_msg field='institution'*}
                    <p><label>Beschreibung*: </label><input class='inputlarge' type='institution_description' name='institution_description' {if isset($institution_description)}value='{$institution_description}'{/if}/></p>
                    {*validate_msg field='institution_description'*}
                    <p id="schooltype_list"><label>Schultyp: </label><select name="schooltype_id" >
                        {section name=res loop=$schooltype}  
                            <option value={$schooltype[res]->id}>{$schooltype[res]->schooltype}</option>
                        {/section}
                        </select></p>  
                    <p><label>Anderer Schultyp... </label><input class="centervertical" type="checkbox" name='btn_newSchooltype' value='Neuen Schultyp anlegen' onclick="checkbox_addForm(this.checked, 'inline', 'newSchooltype', 'schooltype_list')"/></p>
                    <div id="newSchooltype" style="display:none;">
                        <p><label>Schultyp: </label><input class='inputlarge' type='text' name='new_schooltype' id='schooltype_id' {if isset($new_schooltype)}value='{$new_schooltype}'{/if} /></p> 
                        <p><label>Beschreibung: </label><input class='inputlarge' type='text' name='schooltype_description' {if isset($schooltype_description)}value='{$schooltype_description}'{/if}/></p>
                    </div>
                    <p><label>Land: </label><select name="country" onchange="loadStates(this.value);">
                        {section name=res loop=$countries}  
                            <option label={$countries[res]->de} value={$countries[res]->id}>{$countries[res]->de}</option>
                        {/section}
                    </select></p>

                    <p id="states">

                    </p>
                    <p><label>&nbsp;</label><input type='submit' name='step_3' value='weiter' /></p>
                {/if}

                {if $step == 4}
                {*Admindaten*}
                <p>&nbsp;</p>
                <p><h3>Administrator</h3></p>
                <input type='hidden' name='institution_id' id='institution_id' {if isset($institution_id)}value='{$institution_id}'{/if} />   
                <p><label>Benutzername*:</label><input id='username' name='username' {if isset($username)}value='{$username}'{/if} /></p>
                {*validate_msg field='username'*}
                <p><label>Vorname*: </label><input name='firstname'{if isset($firstname)}value='{$firstname}'{/if}/></p>
                {*validate_msg field='firstname'*}
                <p><label>Nachname*: </label><input name='lastname'{if isset($lastname)}value='{$lastname}'{/if}/></p>
                {*validate_msg field='lastname'*}
                <p><label>Email*: </label><input name='email'{if isset($email)}value='{$email}'{/if}/></p>
                {*validate_msg field='email'*}
                <p><label>PLZ*: </label><input name='postalcode'{if isset($postalcode)}value='{$postalcode}'{/if}/></p>
                {*validate_msg field='postalcode'*}
                <p><label>Ort*: </label><input name='city' {if isset($city)}value='{$city}'{/if}/></p>
                {*validate_msg field='city'*}
                <p><label>Land: </label><select name='country' onchange="loadStates(this.value);">
                        {section name=res loop=$countries}  
                            <option label={$countries[res]->de} value={$countries[res]->id}>{$countries[res]->de}</option>
                        {/section}
                    </select></p>

                    <p id="states">
                    </p>
                <p><label>Passwort*: </label></td><td><input type="password" name='password' {if isset($password)}value='{$password}'{/if}/></p>                                       
                {*validate_msg field='password'*}
                    <p><label>&nbsp;</label><input type='submit' name='step_4' value='weiter' /></p>
                {/if}

                {if $step == 5}
                {*Finished*}
                <p>&nbsp;</p>
                <p><h3>Installation abgeschlossen</h3></p>
                <p>Die Installation wurde erfolgreich abgeschlossen.</p> 
                <p>Bitte löschen sie die Datei /share/controllers/install.php !</p>
                <p>Mit dem Button gelangen sie zum Login.</p>

                <p><label>&nbsp;</label><input type='submit' name='step_5' value='Zum Login' /></p>
                {/if}
            </form>
            <div class="col-xs-4">
                <div class="timeline" style="margin-left:-30px;">
                    <dt></dt>
                    <dl>
                        <dd class="pos-right clearfix">
                            <div class="circ {if $step eq 0}bg-green{else if $step >= 1}{else}bg-gray{/if}"></div>
                            <div class="time" style="text-align:left;margin-left:0;"><strong>Start</strong></div>
                            <div class="events">
                        </dd>
                    </dl>
                    <dl>
                        <dd class="pos-right clearfix">
                            <div class="circ {if $step eq 1}bg-green{else if $step >= 2}{else}bg-gray{/if}"></div>
                            <div class="time" style="text-align:left;margin-left:0;"><strong>Serverumgebung</strong></div>
                            <div class="events">
                        </dd>
                    </dl>
                    <dl>
                        <dd class="pos-right clearfix">
                            <div class="circ {if $step eq 2}bg-green{else if $step >= 3}{else}bg-gray{/if}"></div>
                            <div class="time" style="text-align:left;margin-left:0;"><strong>Seitentitel</strong></div>
                            <div class="events">
                        </dd>
                    </dl>
                    <dl>
                        <dd class="pos-right clearfix">
                            <div class="circ {if $step eq 3}bg-green{else if $step >= 4}{else}bg-gray{/if}"></div>
                            <div class="time" style="text-align:left;margin-left:0;"><strong>Institution</strong></div>
                            <div class="events">
                        </dd>
                    </dl>
                    <dl>
                        <dd class="pos-right clearfix">
                            <div class="circ {if $step eq 4}bg-green{else if $step >= 5}{else}bg-gray{/if}"></div>
                            <div class="time" style="text-align:left;margin-left:0;"><strong>Administration</strong></div>
                            <div class="events">
                        </dd>
                    </dl>
                    <dl>
                        <dd class="pos-right clearfix">
                            <div class="circ {if $step eq 5}bg-green{else}bg-gray{/if}"></div>
                            <div class="time" style="text-align:left;margin-left:0;"><strong>Fertig</strong></div>
                            <div class="events">
                        </dd>
                    </dl>
                </div>
            </div>
</div>
    </div>
</div>  
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}
