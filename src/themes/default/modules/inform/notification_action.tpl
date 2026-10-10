<form method="post" action="{$MANAGER_PAGE_URL}" id="notification_action_form">
    <input type="hidden" name="action" value="{$DATA.id}">
    <input type="hidden" name="_csrf" value="{$CHECKSS}">
    <div class="mb-3">
        <label class="form-label">{$LANG->getModule('receiver')}</label>
        <select name="receiver_ids[]" class="receiver_ids form-select" multiple
                data-placeholder="{$LANG->getModule('to_group_all')}">
            {foreach $RECEIVER_IDS as $member}
            <option value="{$member.id}" selected>{$member.fullname|escape}</option>
            {/foreach}
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">{$LANG->getModule('content')}</label>
        <div class="vstack gap-2">
            {foreach $MESSAGES as $msg}
            <div class="card">
                <div class="card-header py-2">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span>{$msg.langname}</span>
                        <label class="d-flex align-items-center gap-1 mb-0 fw-normal">
                            <input type="radio" name="isdef" value="{$msg.lang}"{if $msg.lang == $DATA.isdef} checked{/if}>
                            {$LANG->getModule('default')}
                        </label>
                    </div>
                    <textarea name="message[{$msg.lang}]" class="form-control message" maxlength="2000" style="resize:none">{$msg.content}</textarea>
                </div>
            </div>
            {/foreach}
        </div>
        <div class="form-text">{$LANG->getModule('default_help')}</div>
    </div>
    <div class="mb-3">
        <label class="form-label">{$LANG->getModule('inform_link')}</label>
        <div class="vstack gap-2">
            {foreach $LINKS as $lnk}
            <div class="card">
                <div class="card-header py-2">
                    <div class="mb-2">{$lnk.langname}</div>
                    <input name="link[{$lnk.lang}]" class="form-control" maxlength="500" value="{$lnk.content|escape}">
                </div>
            </div>
            {/foreach}
        </div>
    </div>
    <div class="mb-3">
        <label class="form-label">{$LANG->getModule('add_time')}</label>
        <div class="d-flex flex-wrap align-items-center gap-2 add_time">
            <input type="text" name="add_time" value="{$DATA.add_time}" class="form-control" style="width:120px" maxlength="10">
            <select name="add_hour" class="form-select" style="width:auto">
                {foreach $HOURS as $hour}
                <option value="{$hour.val}"{if $hour.val == $DATA.add_hour} selected{/if}>{$hour.name}</option>
                {/foreach}
            </select>
            <select name="add_min" class="form-select" style="width:auto">
                {foreach $MINUTES as $minute}
                <option value="{$minute.val}"{if $minute.val == $DATA.add_min} selected{/if}>{$minute.name}</option>
                {/foreach}
            </select>
        </div>
    </div>
    <div class="mb-3">
        <label class="form-label">{$LANG->getModule('exp_time')}</label>
        <div class="vstack gap-2 exp_time">
            <select class="form-select w-auto sample_exp_time">
                <option value="0"></option>
                <option value="1">{$LANG->getModule('after_1_day')}</option>
                <option value="2">{$LANG->getModule('after_2_days')}</option>
                <option value="7">{$LANG->getModule('after_7_days')}</option>
                <option value="10">{$LANG->getModule('after_10_days')}</option>
                <option value="15">{$LANG->getModule('after_15_days')}</option>
                <option value="30">{$LANG->getModule('after_30_days')}</option>
            </select>
            <div class="d-flex flex-wrap align-items-center gap-2">
                <input type="text" name="exp_time" value="{$DATA.exp_time}" class="form-control" style="width:120px" maxlength="10">
                <select name="exp_hour" class="form-select" style="width:auto">
                    <option value="-1"></option>
                    {foreach $HOURS as $hour}
                    <option value="{$hour.val}"{if $hour.val == $DATA.exp_hour} selected{/if}>{$hour.name}</option>
                    {/foreach}
                </select>
                <select name="exp_min" class="form-select" style="width:auto">
                    <option value="-1"></option>
                    {foreach $MINUTES as $minute}
                    <option value="{$minute.val}"{if $minute.val == $DATA.exp_min} selected{/if}>{$minute.name}</option>
                    {/foreach}
                </select>
            </div>
            <div class="form-text">{$LANG->getModule('empty_is_unlimited')}</div>
        </div>
    </div>
    <div class="text-center">
        <button type="submit" class="btn btn-primary">
            <i class="fa-solid fa-floppy-disk"></i> {$LANG->getGlobal('save')}
        </button>
        <button type="button" class="btn btn-secondary" data-toggle="notification_action_cancel">
            {$LANG->getGlobal('cancel')}
        </button>
    </div>
</form>
