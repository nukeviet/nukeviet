<div class="table-responsive">
    <table class="table table-sm table-bordered table-striped table-hover align-middle mb-0">
        <thead>
            <tr>
                <th>{$LANG->getModule('update_mod_list')}</th>
                <th>{$LANG->getModule('update_mod_version')}</th>
                <th>{$LANG->getModule('update_mod_author')}</th>
                <th>{$LANG->getModule('update_mod_note')}</th>
            </tr>
        </thead>
        <tbody>
            {foreach from=$MODS item=row}
            <tr>
                <td class="fw-medium">{$row.name}</td>
                <td>{$row.version} <span class="text-body-secondary">({$row.date})</span></td>
                <td>{$row.author}</td>
                <td>{$row.note}</td>
            </tr>
            {/foreach}
        </tbody>
    </table>
</div>
