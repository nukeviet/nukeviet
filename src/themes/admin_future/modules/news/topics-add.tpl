<div class="card">
    <div class="card-body">
        <div class="table-responsive-lg table-card pb-1">
            <table class="table table-striped align-middle table-sticky mb-0">
                <thead>
                    <tr>
                        <th class="text-nowrap" style="width:1%">
                            <input type="checkbox" data-toggle="checkAll" checked name="checkAll[]" class="form-check-input m-0 align-middle" aria-label="{$LANG->getGlobal('toggle_checkall')}">
                        </th>
                        <th class="text-nowrap" style="width:99%">{$LANG->getModule('name')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$ROWS item=row}
                    <tr>
                        <td>
                            <input type="checkbox" data-toggle="checkSingle" name="idcheck[]" value="{$row.id}"
                                   class="form-check-input m-0 align-middle"
                                   aria-label="{$LANG->getGlobal('toggle_checksingle')}"{if $row.checked} checked{/if}>
                        </td>
                        <td>{$row.title nofilter}</td>
                    </tr>
                    {foreachelse}
                    <tr>
                        <td colspan="2" class="text-center text-muted py-4">{$LANG->getModule('no_data')}</td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer border-top">
        <div class="d-flex flex-wrap align-items-center gap-2">
            <input type="checkbox" data-toggle="checkAll" checked name="checkAll[]" class="form-check-input m-0 align-middle flex-shrink-0" aria-label="{$LANG->getGlobal('toggle_checkall')}">
            <select class="form-select form-select-sm w-auto" name="topicsid" id="topicsid">
                {foreach from=$TOPICS item=topic}
                <option value="{$topic.key}">{$topic.title}</option>
                {/foreach}
            </select>
            <button type="button" class="btn btn-sm btn-primary"
                    data-toggle="addtotopics-save"
                    data-tokend="{$CHECKSS}"
                    data-msgnocheck="{$LANG->getModule('topic_nocheck')}">
                <i class="fa-solid fa-floppy-disk" data-icon="fa-floppy-disk"></i> {$LANG->getGlobal('save')}
            </button>
        </div>
    </div>
</div>
