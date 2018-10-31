{if isset($IS_EMPTY)}
<div role="alert" class="alert alert-primary alert-dismissible">
    <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
    <div class="icon"><i class="fas fa-info-circle"></i></div>
    <div class="message">{$LANG->get('nv_lang_error_exit')}</div>
</div>
<script>
setTimeout(function() {
    window.location = '{$URL_REDIRECT}';
}, 5000);
</script>
{else}
<div class="card card-table">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th style="width: 2%;" class="text-nowrap">{$LANG->get('nv_lang_nb')}</th>
                        <th style="width: 14%;" class="text-nowrap">{$LANG->get('nv_lang_module')}</th>
                        <th style="width: 14%;" class="text-nowrap">{$LANG->get('nv_lang_area')}</th>
                        <th style="width: 25%;" class="text-nowrap">{$LANG->get('nv_lang_author')}</th>
                        <th style="width: 25%;" class="text-nowrap">{$LANG->get('nv_lang_createdate')}</th>
                        <th style="width: 20%;" class="text-right text-nowrap">{$LANG->get('actions')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$ARRAY item=row}
                    <tr>
                        <td class="text-nowrap">{$row.stt}</td>
                        <td class="text-nowrap">{$row.module}</td>
                        <td class="text-nowrap">{$row.langsitename}</td>
                        <td>{$row.author}</td>
                        <td>{$row.createdate}</td>
                        <td class="text-right text-nowrap">
                            <a href="{$row.url_edit}" class="btn btn-sm btn-secondary"><i class="icon icon-left fas fa-pencil-alt"></i> {$LANG->get('edit')}</a>
                            {if $row.allow_write}<a href="{$row.url_export}" class="btn btn-sm btn-secondary"><i class="icon icon-left fas fa-marker"></i> {$LANG->get('nv_admin_write')}</a>{/if}
                        </td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>
{/if}
