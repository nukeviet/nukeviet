<div role="alert" class="alert alert-primary alert-dismissible">
    <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
    <div class="icon"><i class="fas fa-info-circle"></i></div>
    <div class="message">{$LANG->get('nv_lang_note_edit')}: {$ALLOWED_HTML_LANG}.</div>
</div>
<form action="{$NV_BASE_ADMINURL}index.php" method="post">
    <input type="hidden" name="pozauthor[langtype]" value="{$LANGTYPE}">
    <input type="hidden" name="{$NV_NAME_VARIABLE}" value="{$MODULE_NAME}">
    <input type="hidden" name="{$NV_OP_VARIABLE}" value="{$OP}">
    <input type="hidden" name="idfile" value="{$IDFILE}">
    <input type="hidden" name="dirlang" value="{$DIRLANG}">
    <input type="hidden" name="savedata" value="{$NV_CHECK_SESSION}">
    <div class="card card-table">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th style="width: 5%;" class="text-center">{$LANG->get('nv_lang_nb')}</th>
                            <th style="width: 40%;" class="text-right">{$LANG->get('nv_lang_key')}</th>
                            <th style="width: 55%;">{$LANG->get('nv_lang_value')}</th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach from=$ARRAY_TRANSLATOR key=lang_key item=lang_value}
                        {if $lang_key neq 'langtype'}
                        <tr>
                            <td class="text-center">&nbsp;</td>
                            <td class="text-right">{$lang_key}</td>
                            <td class="text-left">
                                <input type="text" value="{$lang_value|htmlspecialchars}" name="pozauthor[{$lang_key}]" class="form-control form-control-xs">
                            </td>
                        </tr>
                        {/if}
                        {/foreach}
                        {for $stt=1 to 2}
                        <tr>
                            <td class="text-center">{$stt}</td>
                            <td class="text-right">
                                <input type="text" value="" name="pozlangkey[{$stt}]" class="form-control form-control-xs">
                            </td>
                            <td class="text-left">
                                <input type="text" value="" name="pozlangval[{$stt}]" class="form-control form-control-xs">
                            </td>
                        </tr>
                        {/for}
                        {foreach from=$ARRAY_DATA item=row}
                        <tr>
                            <td class="text-center">{$row.key}</td>
                            <td class="text-right">{$row.lang_key}</td>
                            <td class="text-left">
                                <input type="text" value="{$row.value}" name="pozlang[{$row.id}]" class="form-control form-control-xs">
                            </td>
                        </tr>
                        {/foreach}
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer text-center">
            <input type="submit" value="{$LANG->get('nv_admin_edit_save')}" class="btn btn-primary">
        </div>
    </div>
</form>
