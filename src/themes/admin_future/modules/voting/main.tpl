<div class="card">
    <div class="table-responsive-lg table-card pb-1">
        <table class="table table-striped align-middle table-sticky mb-0">
            <thead>
                <tr>
                    <th class="text-nowrap" style="width: 5%;">{$LANG->getModule('voting_id')}</th>
                    <th class="text-nowrap" style="width: 45%;">{$LANG->getModule('voting_title')}</th>
                    <th class="text-nowrap text-center" style="width: 20%;">{$LANG->getModule('voting_hits')}</th>
                    <th class="text-nowrap text-center" style="width: 15%;">{$LANG->getModule('voting_active')}</th>
                    <th class="text-nowrap" style="width: 15%;">{$LANG->getModule('voting_func')}</th>
                </tr>
            </thead>
            <tbody>
                {foreach from=$DATA key=key item=row}
                <tr>
                    <td>{$row.vid}</td>
                    <td>{$row.question}</td>
                    <td class="text-center">{$row.totalvote|dnumber} {$LANG->getModule('voting_counter')}</td>
                    <td class="text-center form-switch">
                        <div class="d-inline-flex">
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" role="switch" aria-label="{$LANG->getModule('voting_active')}" data-toggle="changeActive" data-checkss="{$row.checksess}" data-vid="{$row.vid}" name="change_act_{$row.vid}" id="change_act_{$row.vid}" {if $row.status == 1} checked{/if}/>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="hstack gap-1">
                            <div class="text-nowrap">
                                <a href="#" class="btn btn-secondary btn-sm" data-toggle="viewresult" data-vid="{$row.vid}" data-checkss="{$row.checksess}"><i class="fa-solid fa-bar-chart" data-icon="fa-bar-chart"></i>{$LANG->getModule('voting_result')}</a>
                            </div>
                            <div class="text-nowrap">
                                <a href="{$row.url_edit}" class="btn btn-secondary btn-sm"><i class="fa-solid fa-pen"></i>{$LANG->getGlobal('edit')}</a>
                            </div>
                            <div class="text-nowrap">
                                <button type="button" class="btn btn-danger btn-sm" data-toggle="nv_del_voting" data-checkss="{$row.checksess}" data-vid="{$row.vid}"><i class="fa-solid fa-trash" data-icon="fa-trash"></i> {$LANG->getGlobal('delete')}</button>
                            </div>
                        </div>
                    </td>
                </tr>
                {/foreach}
            </tbody>
        </table>
    </div>
</div>
