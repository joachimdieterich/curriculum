{extends file="base.tpl"}

{block name=title}{$page_title}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}
    
<div class="border-box">
    <div class="contentheader">{$page_title}</div>
    <div class="space-top-padding box-shadow">
	<form name="file" enctype="multipart/form-data" action="index.php?action=userImport" method="post">
    <p>Nutzerkonten per CSV-Datei hochladen.</p>
    <p>&nbsp;</p>
    <p>Die CSV-Datei muss folgendes Format haben:</p>
    <p>- Die ersten Zeile muss die Schlüsselwerte enthalten (z.B.:username, password, firstname, lastname, email, role_id, confirmed, postalcode, city, state, country)</p>
    <p>- Die Schüsselwerte <strong>username, password, firstname, lastname </strong>und <strong>email</strong> müssen gesetzt werden. </p>
    <p>- Wird keine Benutzer-Rolle festgelegt (role_id) wird die Standard-Rolle der Institution verwendet.</p>
    <p>- Die maximale Dateigröße liegt bei {$filesize}MB und kann im Adminstrationsbereich festgelegt werden.</p>
    <p>- Die Datei muss im utf-8 Format gespeichert werden, sonst werden Umlaute und Sonderzeichen nicht korrekt importiert</p>
    {if count($my_institutions['id']) > 1}
        <p>- Bitte wählen sie die Institution in die die Benutzer importiert werden sollen.</p>
        <p><label>Institution / Schule*: </label>{html_options id='institution' name='institution' values=$my_institutions['id'] output=$my_institutions['institution']}</p>
    {elseif count($my_institutions['id']) eq 0}
        <p><strong>Sie müssen zuerst eine Institution anlegen</strong></p>
    {else}
        <input type='hidden' name='institution' id='institution' value='{$my_institutions['id'][0]}' /></p>       
    {/if}
    <p><h3>&nbsp;</h3></p>
    <p>Eine Vorlage für eine CSV-Import-Datei können sie hier herunterladen: </p><p>&nbsp;</p>
    <p><labe ><a class="url_btn floatleft" href="{$support_url}Vorlage-minimal.csv"> </a></label> Vorlage-Minimal.csv</p>
    <p>&nbsp;</p><p>&nbsp;</p>
    <p><h3>&nbsp;</h3></p>
<p><label>CSV-Datei hochladen: </label></p>
    
        <p><input type="file" name="datei" value=""><input type="submit" value="Importieren"> </p>
        </form>
    
        {if isset($results)}
        <p><h3>Importierte Benutzer</h3></p>
        <p >Neue Benutzerkonten (seit Login).</p>
        {* display pagination header *}
        <p>Datensätze {$newUsersPaginator.first}-{$newUsersPaginator.last} von {$newUsersPaginator.total} werden angezeigt.</p>
        {* display results *}  
        <table id="contenttable">
            <tr id="contenttablehead">
                    <td></td><td>Avatar</td>
                    <td>Benutzername</td>
                    <td>Vorname</td>
                    <td>Nachname</td>
                    <td>Email</td>
                    <td>PLZ</td>
                    <td>Ort</td>
                    <td>Bundesland</td>
                    <td>Land</td>
                    <td>Gruppe</td>
            </tr>
        {section name=res loop=$results}
                <tr class="contenttablerow" id="row{$results[res]->id}" onclick="checkrow({$results[res]->id})">
                    <td><input class="invisible" type="checkbox" id="{$results[res]->id}" name="id[]" value={$results[res]->id} /></td>
                    <td><img src="{$avatar_url}{$results[res]->avatar}" alt="Profilfoto" width="18"></td>
                    <td>{$results[res]->username}</td>
                    <td>{$results[res]->firstname}</td>
                    <td>{$results[res]->lastname}</td>
                    <td>{$results[res]->email}</td>
                    <td>{$results[res]->postalcode}</td>
                    <td>{$results[res]->city}</td>
                    <td>{$results[res]->state}</td>
                    <td>{$results[res]->country}</td>
                    <td>{$results[res]->role_name}</td>
                </tr>
            {/section}
        </table>
        {* display pagination info *}
        <p>{paginate_prev id="newUsersPaginator"} {paginate_middle id="newUsersPaginator"} {paginate_next id="newUsersPaginator"}</p>    
        {/if}
    </div>
</div>
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}