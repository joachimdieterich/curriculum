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
                        {Form::input_text('server_name', 'Domain', $server_name, $error, 'www.curriculumonline.de')}
                        {Form::input_text('app_url', 'URL-Pfad', $app_url, $error, 'curriculum')}
                        {Form::input_text('data_root', 'Datenpfad', $data_root, $error, 'e.g. /var/www/dataroot/ (folder should be outside of htmlroot)')}
                        <button type='submit' name='step_1' value='' class="btn btn-default pull-right">
                                <span class="fa fa-arrow-right" aria-hidden="true"></span> weiter
                        </button>
                    </div>
                {/if}

                {if $step == 2}
                    {*Serverdaten*}
                    <p><h3>Webseiten-Titel</h3></p>
                    <br>
                    <div class="form-horizontal col-xs-8">
                        {Form::input_text('app_title', 'Name der Seite', $app_title, $error, 'curriculum')}
                        {*<p><label>Beispieldaten installieren</label><input class="centervertical" type="checkbox" name="demo"/></p>{*not yet available - dedication incorrect*}
                        <button type='submit' name='step_2' value='' class="btn btn-default pull-right">
                                <span class="fa fa-arrow-right" aria-hidden="true"></span> weiter
                        </button>
                    </div>
                {/if}

                {if $step == 3}
                    {*Institution*}
                    <p><h3>Institution</h3></p><br>
                    <div class="form-horizontal col-xs-8">
                        {Form::input_text('institution', 'Institution / Schule', $institution, $error, 'Name der Institution/Schule')}
                        {Form::input_text('description', 'Beschreibung', $description, $error, 'z.B. Adresse')}
                        {Form::input_select('schooltype_id', 'Schulart', $schooltypes, 'schooltype', 'id', $schooltype_id , $error)}
                        {*Form::input_checkbox('btn_newSchooltype', 'Neuen Schultyp anlegen', $btn_newSchooltype, $error, 'checkbox', "toggle(['newSchooltype'], ['schooltype_id']);")*}
                        <div id="newSchooltype" {if !$new_schooltype} class="hidden"{/if}>
                            {Form::input_text('newSchool', 'Neue Schulart', $new_schooltype, $error, 'z. B. Medienzentrum Landau')}
                            {Form::input_text('schooltype_description', 'Beschreibung', $schooltype_description, $error, 'Beschreibung der neuen Schulart')}
                        </div>
                        {Form::input_select('country_id', 'Land', $countries, 'de', 'id', $country_id , $error, "getValues('state', this.value, 'state_id');")}
                        {Form::input_select('state_id', 'Bundesland/Region', $states, 'state', 'id', $state_id , $error)}
                        <button type='submit' name='step_3' value='' class="btn btn-default pull-right">
                                <span class="fa fa-arrow-right" aria-hidden="true"></span> weiter
                        </button>
                    </div>
                {/if}

                {if $step == 4}
                    {*Admindaten*}
                    <p><h3>Administrator</h3></p><br>
                    <div class="form-horizontal col-xs-8">
                        <input type='hidden' name='institution_id' id='institution_id' {if isset($institution_id)}value='{$institution_id}'{/if} />   
                        {Form::input_text('username', 'Benutzername', $username, $error, 'admin')}
                        {Form::input_text('firstname', 'Vorname', $firstname, $error, 'Max')}
                        {Form::input_text('lastname', 'Nachname', $lastname, $error, 'Mustermann')}
                        {Form::input_text('email', 'Email', $email, $error, 'mail@curriculumonline.de')}
                        {Form::input_text('postalcode', 'PLZ', $postalcode, $error, '')}
                        {Form::input_text('city', 'Ort', $city, $error, '')}
                        {Form::input_select('country_id', 'Land', $countries, 'de', 'id', $country_id , $error, "getValues('state', this.value, 'state_id');")}
                        {Form::input_select('state_id', 'Bundesland/Region', $states, 'state', 'id', $state_id , $error)}
                        {Form::input_text('pw', 'Kennwort', null, $error, '','password')}
                        {Form::input_checkbox('show_pw', 'Passwort anzeigen', $show_pw, $error, 'checkbox', "unmask('pw', this.checked);")}
                        <button type='submit' name='step_4' value='' class="btn btn-default pull-right">
                            <span class="fa fa-arrow-right" aria-hidden="true"></span> weiter
                        </button>
                    </div>
                {/if}

                {if $step == 5}
                {*Finished*}
                <p><h3>Administrator</h3></p><br>
                    <div class="form-horizontal col-xs-8">
                        <p>Die Installation wurde erfolgreich abgeschlossen.</p> 
                        <p>Alle bestehenden Demo-Benutzer können mit ihrem Passwort genutzt werden.</p>
                        <p>Mit dem Button gelangen sie zum Login.</p>
                        <button type='submit' name='step_5' value='' class="btn btn-default pull-right">
                            <span class="fa fa-arrow-right" aria-hidden="true"></span> Anmelden
                        </button>
                    </div>   
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
