<div class="card border-primary border-3 border-bottom-0 border-start-0 border-end-0">
    <div class="card-body">
        <div class="table-responsive-lg table-card">
            <table class="table table-striped align-middle table-sticky mb-0">
                <thead class="text-muted">
                    <tr>
                        <th class="text-center text-nowrap" style="width: 1%;">{$LANG->getModule('nv_lang_nb')}</th>
                        <th class="text-nowrap" style="width: 19%;">{$LANG->getModule('nv_lang_module')}</th>
                        <th class="text-nowrap" style="width: 20%;">{$LANG->getModule('nv_lang_area')}</th>
                        <th class="text-nowrap" style="width: 20%;">{$LANG->getModule('nv_lang_author')}</th>
                        <th class="text-nowrap" style="width: 20%;">{$LANG->getModule('nv_lang_createdate')}</th>
                        <th class="text-center text-nowrap" style="width: 20%;">{$LANG->getModule('nv_lang_func')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$ARRAY key=stt item=row}
                    <tr>
                        <td class="text-center">
                            {$stt + 1}
                        </td>
                        <td>{$row.module}</td>
                        <td>{$row.langsitename}</td>
                        <td>
                            {$row.author}
                        </td>
                        <td>
                            {$row.createdate}
                        </td>
                        <td class="text-center">
                            <div class="hstack gap-1">
                                <a href="{$row.url_edit}" class="btn btn-secondary btn-sm text-nowrap"><i class="fa-solid fa-pen"></i> {$LANG->getModule('nv_admin_edit')}</a>
                                {if $row.allowed_write}
                                <button type="button" data-url="{$row.url_export}" class="btn btn-secondary btn-sm text-nowrap" data-toggle="lang_export"><i class="fa-solid fa-file-export" data-icon="fa-file-export"></i> {$LANG->getModule('nv_admin_write')}</button>
                                {/if}
                            </div>
                        </td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>
