<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">{$LANG->getModule('addquestion')}</h5>
    </div>
    <div class="card-body">
        <div class="row g-3 align-items-center">
            <label for="new_title" class="col-sm-2 col-form-label">{$LANG->getModule('question')}:</label>
            <div class="col-sm-7 col-lg-6">
                <input type="text" class="form-control" name="new_title" id="new_title" maxlength="255" autocomplete="off">
            </div>
            <div class="col-sm-3 col-lg-4">
                <button type="button" class="btn btn-primary" id="btn_add_question" data-checkss="{$CHECKSS}">
                    <i class="fa-solid fa-plus" data-icon="fa-plus"></i> {$LANG->getModule('addquestion')}
                </button>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">{$LANG->getModule('list_question')}</h5>
    </div>
    <div class="card-body">
        {if !empty($DATA)}
        <div class="table-responsive-lg table-card pb-1">
            <table class="table table-striped align-middle table-sticky mb-0">
                <thead>
                    <tr>
                        <th class="text-nowrap" style="width: 15%;">{$LANG->getModule('weight')}</th>
                        <th class="text-nowrap">{$LANG->getModule('question')}</th>
                        <th class="text-nowrap text-center" style="width: 18%;">{$LANG->getGlobal('actions')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$DATA item=row}
                    <tr data-qid="{$row.qid}">
                        <td>
                            <select class="form-select form-select-sm fw-75" name="weight_{$row.qid}" id="weight_{$row.qid}" data-qid="{$row.qid}" data-action="changeweight" data-checkss="{$CHECKSS}">
                                {foreach from=$row.weights item=weight}
                                <option value="{$weight.key}"{if $weight.selected} selected{/if}>{$weight.title}</option>
                                {/foreach}
                            </select>
                        </td>
                        <td>
                            <input type="hidden" name="hidden_{$row.qid}" id="hidden_{$row.qid}" value="{$row.title}">
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" name="title_{$row.qid}" id="title_{$row.qid}" value="{$row.title}" maxlength="240" autocomplete="off">
                                <button type="button" class="btn btn-primary" data-qid="{$row.qid}" data-action="save" aria-label="{$LANG->getGlobal('save')}" data-checkss="{$CHECKSS}">
                                    <i class="fa-solid fa-floppy-disk" data-icon="fa-floppy-disk"></i> {$LANG->getGlobal('save')}
                                </button>
                            </div>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-danger" data-qid="{$row.qid}" data-action="delete" aria-label="{$LANG->getGlobal('delete')}" data-checkss="{$CHECKSS}">
                                <i class="fa-solid fa-trash" data-icon="fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
        {else}
        <div class="alert alert-info">
            {$LANG->getGlobal('no_data')}
        </div>
        {/if}
    </div>
</div>
