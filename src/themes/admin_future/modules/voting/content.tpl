<link type="text/css" href="{$smarty.const.ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet">
<script type="text/javascript" src="{$smarty.const.ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{$smarty.const.ASSETS_STATIC_URL}/js/language/jquery.ui.datepicker-{$smarty.const.NV_LANG_INTERFACE}.js"></script>

<form id="votingcontent" method="post" class="ajax-submit" novalidate
      action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}{if $VID}&amp;vid={$VID}{/if}">
    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="mb-3">
                        <label for="question" class="form-label">{$LANG->getModule('voting_question')} <span class="text-danger">(*)</span></label>
                        <input type="text" class="form-control required" id="question" name="question"
                               value="{$ROWVOTE.question}" maxlength="{$ROWVOTE.question_maxlength}"
                               placeholder="{$LANG->getModule('voting_question')}" autocomplete="off">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="link" class="form-label">{$LANG->getModule('voting_link')}</label>
                        <input type="text" class="form-control" id="link" name="link"
                               value="{$ROWVOTE.link}" autocomplete="url">
                    </div>
                    <div class="mb-3">
                        <div class="form-label">{$LANG->getModule('voting_time')}</div>
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            <div class="input-group flex-nowrap" style="width:auto">
                                <input type="text" name="publ_date" id="publ_date"
                                       value="{$PUBL_DATE}" class="form-control datepicker"
                                       style="width:110px" readonly autocomplete="off">
                                <button class="btn btn-secondary" type="button" id="publ_date_btn">
                                    <i class="fa-regular fa-calendar"></i>
                                </button>
                            </div>
                            <select class="form-select" name="phour" id="phour" style="width:80px">
                                {foreach from=$HOUR_OPTIONS item=h}
                                <option value="{$h.key}"{if $PHOUR == $h.key} selected{/if}>{$h.title}</option>
                                {/foreach}
                            </select>
                            <span>:</span>
                            <select class="form-select" name="pmin" id="pmin" style="width:80px">
                                {foreach from=$MIN_OPTIONS item=m}
                                <option value="{$m.key}"{if $PMIN == $m.key} selected{/if}>{$m.title}</option>
                                {/foreach}
                            </select>
                            <a href="javascript:void(0)" class="text-muted"
                               data-toggle="delval"
                               data-target="#publ_date"
                               data-select="#phour,#pmin">
                                <i class="fa-solid fa-circle-xmark"></i>
                            </a>
                        </div>
                    </div>
                    <div class="mb-0">
                        <div class="form-label">{$LANG->getModule('voting_timeout')}</div>
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            <div class="input-group flex-nowrap" style="width:auto">
                                <input type="text" name="exp_date" id="exp_date"
                                       value="{$EXP_DATE}" class="form-control datepicker"
                                       style="width:110px" readonly autocomplete="off">
                                <button class="btn btn-secondary" type="button" id="exp_date_btn">
                                    <i class="fa-regular fa-calendar"></i>
                                </button>
                            </div>
                            <select class="form-select" name="ehour" id="ehour" style="width:80px">
                                {foreach from=$HOUR_OPTIONS item=h}
                                <option value="{$h.key}"{if $EHOUR == $h.key} selected{/if}>{$h.title}</option>
                                {/foreach}
                            </select>
                            <span>:</span>
                            <select class="form-select" name="emin" id="emin" style="width:80px">
                                {foreach from=$MIN_OPTIONS item=m}
                                <option value="{$m.key}"{if $EMIN == $m.key} selected{/if}>{$m.title}</option>
                                {/foreach}
                            </select>
                            <a href="javascript:void(0)" class="text-muted"
                               data-toggle="delval"
                               data-target="#exp_date"
                               data-select="#ehour,#emin">
                                <i class="fa-solid fa-circle-xmark"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="card-title mb-0">{$LANG->getModule('voting_answer')}</h6>
                    <button type="button" class="btn btn-sm btn-info"
                            data-toggle="add-answer"
                            data-label="{$LANG->getModule('voting_question_num')}">
                        <i class="fa-solid fa-plus"></i> {$LANG->getModule('add_answervote')}
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped align-middle mb-0" id="items">
                            <thead>
                                <tr>
                                    <th class="text-nowrap" style="width:15%"></th>
                                    <th class="text-nowrap" style="width:50%">{$LANG->getModule('voting_answer')}</th>
                                    <th class="text-nowrap" style="width:35%">{$LANG->getModule('voting_link')}</th>
                                </tr>
                            </thead>
                            <tbody>
                                {foreach from=$ITEMS item=item key=idx}
                                <tr>
                                    <td class="text-end text-muted">{$LANG->getModule('voting_question_num')} {$idx + 1}</td>
                                    <td><input class="form-control form-control-sm" type="text" name="answervote[{$item.id}]" value="{$item.title}" maxlength="245" autocomplete="off"></td>
                                    <td><input class="form-control form-control-sm" type="text" name="urlvote[{$item.id}]" value="{$item.url}" maxlength="255" autocomplete="off"></td>
                                </tr>
                                {/foreach}
                                <tr>
                                    <td class="text-end text-muted">{$LANG->getModule('voting_question_num')} {$NEW_ITEM_NUM}{if empty($ITEMS)} <span class="text-danger">(*)</span>{/if}</td>
                                    <td><input class="form-control form-control-sm" type="text" name="answervotenews[]" value="" maxlength="245" autocomplete="off"></td>
                                    <td><input class="form-control form-control-sm" type="text" name="urlvotenews[]" value="" maxlength="255" autocomplete="off"></td>
                                </tr>
                                {if empty($ITEMS)}
                                <tr>
                                    <td class="text-end text-muted">{$LANG->getModule('voting_question_num')} {$NEW_ITEM_NUM + 1} <span class="text-danger">(*)</span></td>
                                    <td><input class="form-control form-control-sm" type="text" name="answervotenews[]" value="" maxlength="245" autocomplete="off"></td>
                                    <td><input class="form-control form-control-sm" type="text" name="urlvotenews[]" value="" maxlength="255" autocomplete="off"></td>
                                </tr>
                                {/if}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="mb-3">
                        <label for="maxoption" class="form-label">{$LANG->getModule('voting_maxoption')}</label>
                        <input class="form-control" type="number" id="maxoption" name="maxoption"
                               value="{$ROWVOTE.acceptcm}" min="1" autocomplete="off">
                    </div>
                    <div class="mb-3">
                        <div class="form-label">{$LANG->getGlobal('groups_view')}</div>
                        {foreach from=$GROUPS_LIST key=group_id item=group_name}
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="groups_view[]"
                                   value="{$group_id}" id="groups_view_{$group_id}"
                                   {if in_array($group_id, $GROUPS_VIEW)} checked{/if}>
                            <label class="form-check-label" for="groups_view_{$group_id}">{$group_name}</label>
                        </div>
                        {/foreach}
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="active_captcha"
                                   name="active_captcha" value="1"
                                   {if $ROWVOTE.active_captcha} checked{/if}>
                            <label class="form-check-label" for="active_captcha">{$LANG->getModule('voting_active_captcha')}</label>
                        </div>
                    </div>
                    <div class="mb-0">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="vote_one"
                                   name="vote_one" value="1"
                                   {if $ROWVOTE.vote_one} checked{/if}>
                            <label class="form-check-label" for="vote_one"><strong>{$LANG->getModule('voting_type')}</strong></label>
                        </div>
                        <div class="form-text">{$LANG->getModule('note_voting_type')}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="text-center mb-3">
        <input type="hidden" name="save" value="1">
        <input type="hidden" name="checkss" value="{$CHECKSS}">
        <button type="submit" class="btn btn-primary">
            <i class="fa-solid fa-floppy-disk"></i> {$LANG->getModule('voting_confirm')}
        </button>
    </div>
</form>
