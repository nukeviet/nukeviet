<form method="post" action="{$MANAGER_PAGE_URL}" class="form-horizontal" id="notification_action_form">
    <input type="hidden" name="action" value="{$DATA.id}">
    <input type="hidden" name="_csrf" value="{$CHECKSS}">
    <div class="form-group">
        <label class="col-md-8 control-label">{$LANG->getModule('receiver')}</label>
        <div class="col-md-16">
            <select name="receiver_ids[]" class="receiver_ids" multiple="multiple" style="width: 100%;" data-placeholder="{$LANG->getModule('to_group_all')}">
{if !empty($DATA.receiver_ids)}
{foreach $DATA.receiver_ids as $id => $fullname}
                <option value="{$id}" selected="selected">{$fullname}</option>
{/foreach}
{/if}
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-8 control-label">{$LANG->getModule('content')}</label>
        <div class="col-md-16 panel-group mb-0">
{foreach $MESSS as $mess}
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="margin-bottom-sm" style="display:flex">
                        <div>{$mess.langname}</div>
                        <label class="text-normal" style="margin-left: auto;display:inline-flex;align-items:center">
                            <input type="radio" class="form-control" style="margin-top:0" name="isdef" value="{$mess.lang}"{if $mess.checked} checked="checked"{/if}> {$LANG->getModule('default')}
                        </label>
                    </div>
                    <textarea name="message[{$mess.lang}]" class="form-control message" maxlength="2000" style="resize:none;">{$mess.content}</textarea>
                </div>
            </div>
{/foreach}
            <div class="help-block">{$LANG->getModule('default_help')}</div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-8 control-label">{$LANG->getModule('inform_link')}</label>
        <div class="col-md-16">
{foreach $LINKS as $link}
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="d-flex mb-sm">
                        <div>{$link.langname}</div>
                    </div>
                    <input name="link[{$link.lang}]" class="form-control" maxlength="500" value="{$link.content}">
                </div>
            </div>
{/foreach}
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-8 control-label">{$LANG->getModule('add_time')}</label>
        <div class="col-md-16 add_time">
            <input type="text" name="add_time" value="{$DATA.add_time}" class="form-control" maxlength="10">
            <select name="add_hour" class="form-control">
{for $hour=0 to 23}
                <option value="{$hour}"{if $hour == $DATA.add_hour} selected="selected"{/if}>{$hour|string_format: "%02d"}</option>
{/for}
            </select>
            <select name="add_min" class="form-control">
{for $min=0 to 59}
                <option value="{$min}"{if $min == $DATA.add_min} selected="selected"{/if}>{$min|string_format: "%02d"}</option>
{/for}
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-8 control-label">{$LANG->getModule('exp_time')}</label>
        <div class="col-md-16 exp_time">
            <select class="form-control margin-bottom sample_exp_time">
                <option value="0"></option>
                <option value="1">{$LANG->getModule('after_1_day')}</option>
                <option value="2">{$LANG->getModule('after_2_days')}</option>
                <option value="7">{$LANG->getModule('after_7_days')}</option>
                <option value="10">{$LANG->getModule('after_10_days')}</option>
                <option value="15">{$LANG->getModule('after_15_days')}</option>
                <option value="30">{$LANG->getModule('after_30_days')}</option>
            </select><br />
            <input type="text" name="exp_time" value="{$DATA.exp_time}" class="form-control" maxlength="10">
            <select name="exp_hour" class="form-control">
                <option value="-1"></option>
{for $hour=0 to 23}
                <option value="{$hour}"{if $hour == $DATA.exp_hour} selected="selected"{/if}>{$hour|string_format: "%02d"}</option>
{/for}
            </select>
            <select name="exp_min" class="form-control">
                <option value="-1"></option>
{for $min=0 to 59}
                <option value="{$min}"{if $min == $DATA.exp_min} selected="selected"{/if}>{$min|string_format: "%02d"}</option>
{/for}
            </select>
            <div>{$LANG->getModule('empty_is_unlimited')}</div>
        </div>
    </div>
    <div class="text-center">
        <button type="submit" class="btn btn-primary">{$LANG->getGlobal('submit')}</button>
        <button type="button" class="btn btn-default" data-toggle="notification_action_cancel">{$LANG->getGlobal('cancel')}</button>
    </div>
</form>
<script>
    {literal}$(function() {
        var actionFormObj = $('#notification_action_form'),
            ajax_url = actionFormObj.attr('action');
        $('.receiver_ids', actionFormObj).select2({
            ajax: {
                type: 'POST',
                cache: !1,
                url: ajax_url,
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        get_user_json: 1,
                        q: params.term, // search term
                        page: params.page
                    };
                },
                processResults: function(data, params) {
                    params.page = params.page || 1;
                    return {
                        results: data,
                        pagination: {
                            more: (params.page * 30) < data.total_count
                        }
                    };
                },
                cache: true
            },
            escapeMarkup: function(markup) {
                return markup;
            },
            minimumInputLength: 3,
            templateResult: function(repo) {
                if (repo.loading) return repo.text;
                return '<div>' + repo.fullname + '<br/>(ID #' + repo.id + ', ' + repo.username + ', ' + repo.email + ')</div>';
            },
            templateSelection: function(repo) {
                return repo.fullname || repo.text;
            }
        });

        $('[name=add_time], [name=exp_time]', actionFormObj).datepicker({
            dateFormat: "dd/mm/yy",
            changeMonth: true,
            changeYear: true,
            showOtherMonths: true
        });

        $('.sample_exp_time', actionFormObj).on('change', function() {
            var val = parseInt($(this).val()),
                origTime = $('[name=add_time]', actionFormObj).val();
            if (val && origTime != '') {
                origTime = origTime.split('/');
                var date = new Date(origTime[2], origTime[1]-1, origTime[0], 0, 0, 0);
                date.setDate(date.getDate() + val);
                exp_time = date.getDate().toString().padStart(2, '0') + '/' + (date.getMonth()+1).toString().padStart(2, '0') + '/' + date.getFullYear();
                $('[name=exp_time]', actionFormObj).val(exp_time);
                $('[name=exp_hour]', actionFormObj).val($('[name=add_hour]', actionFormObj).val());
                $('[name=exp_min]', actionFormObj).val($('[name=add_min]', actionFormObj).val());
            }
            $(this).val('0')
        })
    }){/literal}
</script>