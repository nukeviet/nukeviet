<div class="container-fluid py-3">
    {if not empty($ROW.bid)}
    <div role="alert" class="alert alert-danger alert-dismissible">
        <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
        <div class="icon"><i class="far fa-times-circle"></i></div>
        <div class="message">{$LANG->get('block_group_notice')}</div>
    </div>
    {/if}
    {if not empty($ERROR)}
    <div role="alert" class="alert alert-danger alert-dismissible">
        <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
        <div class="icon"><i class="far fa-times-circle"></i></div>
        <div class="message">
            <strong>{$LANG->get('error')}:</strong><br />
            {foreach from=$ERROR item=error_i}
            {$error_i}<br />
            {/foreach}
        </div>
    </div>
    {/if}
    <form method="post" action="{$NV_BASE_ADMINURL}index.php?{$NV_LANG_VARIABLE}={$NV_LANG_DATA}&amp;{$NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$NV_OP_VARIABLE}={$OP}&amp;selectthemes={$SELECTTHEMES}&amp;blockredirect={$BLOCKREDIRECT}">
        <div class="card card-default">
            <div class="card-body pt-4">
                <div class="form-group row pb-0">
                    <label class="col-12 col-sm-3 col-form-label text-sm-right">{$LANG->get('block_type')}:</label>
                    <div class="col-12 col-sm-9">
                        <div class="row">
                            <div class="col-12 col-sm-6">
                                <select name="module_type" class="form-control form-control-sm mb-2">
                                    <option value="">{$LANG->get('block_select_type')}</option>
                                    <option value="theme"{if $ROW.module eq 'theme'} selected="selected"{/if}>{$LANG->get('block_type_theme')}</option>
                                    {foreach from=$ARRAY_MODULES item=module_i}
                                    <option value="{$module_i.key}"{if $ROW.module eq $module_i.key} selected="selected"{/if}>{$module_i.title}</option>
                                    {/foreach}
                                </select>
                            </div>
                            <div class="col-12 col-sm-6">
                                <select name="file_name" class="form-control form-control-sm mb-2">
                                    <option value="">{$LANG->get('block_select')}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clearfix" id="block_config"></div>
                <div class="form-group row">
                    <label class="col-12 col-sm-3 col-form-label text-sm-right">{$LANG->get('block_title')}:</label>
                    <div class="col-12 col-sm-9">
                        <input class="form-control form-control-sm" name="title" type="text" value="{$ROW.title}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-12 col-sm-3 col-form-label text-sm-right">{$LANG->get('block_link')}:</label>
                    <div class="col-12 col-sm-9">
                        <input class="form-control form-control-sm" name="link" type="text" value="{$ROW.link}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-12 col-sm-3 col-form-label text-sm-right">{$LANG->get('block_tpl')}:</label>
                    <div class="col-12 col-sm-5">
                        <select id="template" name="template" class="form-control form-control-sm">
                            <option value="">{$LANG->get('block_default')}</option>
                            {foreach from=$ARRAY_TEMPLATES item=template_i}
                            {if not empty($template_i) and $template_i neq 'default'}
                            <option value="{$template_i}"{if $template_i eq $ROW.template} selected="selected"{/if}>{$template_i}</option>
                            {/if}
                            {/foreach}
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-12 col-sm-3 col-form-label text-sm-right">{$LANG->get('block_pos')}:</label>
                    <div class="col-12 col-sm-5">
                        <select name="position" class="form-control form-control-sm">
                            {for $pos_i=0 to $POSITIONS_NUM}
                            <option value="{$POSITIONS[$pos_i]->tag}"{if $POSITIONS[$pos_i]->tag eq $ROW.position} selected="selected"{/if}>{$POSITIONS[$pos_i]->name}</option>
                            {/for}
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-12 col-sm-3 col-form-label text-sm-right">{$LANG->get('block_exp_time')}:</label>
                    <div class="col-12 col-sm-5">
                        <div class="input-group input-group-sm">
                            <input name="exp_time" id="exp_time" value="{if not empty($ROW.exp_time)}{"d/m/Y"|date:$ROW.exp_time}{/if}" maxlength="10" type="text" class="form-control" placeholder="dd/mm/yyyy">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-secondary" id="exp_time_btn">
                                    <i class="far fa-calendar-alt"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row pb-0">
                    <label class="col-12 col-sm-3 col-form-label text-sm-right">{$LANG->get('show_device')}:</label>
                    <div class="col-12 col-sm-9 mt-1">
                        {for $device_i=1 to 4}
                        <label class="custom-control custom-checkbox">
                            <input class="custom-control-input" type="checkbox" name="active_device[]" value="{$device_i}"{if in_array($device_i, $ROW.active_device)} checked="checked"{/if}><span class="custom-control-label">{$LANG->get("show_device_`$device_i`")}</span>
                        </label>
                        {/for}
                    </div>
                </div>
                <div class="form-group row pb-0">
                    <label class="col-12 col-sm-3 col-form-label text-sm-right">{$LANG->get('groups_view')}:</label>
                    <div class="col-12 col-sm-9 mt-1">
                        <div class="list-group-users nv-scroller">
                            <div>
                                {foreach from=$GROUPS_LIST key=group_id item=group_name}
                                <label class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" name="groups_view[]" value="{$group_id}"{if in_array($group_id, $GROUPS_VIEW)} checked="checked"{/if}><span class="custom-control-label">{$group_name}</span>
                                </label>
                                {/foreach}
                            </div>
                        </div>
                    </div>
                </div>
                {if not empty($ROW.bid)}
                <div class="form-group row pb-0">
                    <label class="col-12 col-sm-3 col-form-label text-sm-right">{$LANG->get('block_groupbl')} <span class="text-danger"><strong>{$ROW.bid}</strong></span>:</label>
                    <div class="col-12 col-sm-9 mt-1">
                        <label class="custom-control custom-checkbox">
                            <input class="custom-control-input" type="checkbox" name="leavegroup" value="1"><span class="custom-control-label">{$LANG->get('block_leavegroup')} ({$BLOCKS_NUM} {$LANG->get('block_count')})</span>
                        </label>
                    </div>
                </div>
                {/if}
                <div class="form-group row">
                    <label class="col-12 col-sm-3 col-form-label text-sm-right">{$LANG->get('add_block_module')}:</label>
                    <div class="col-12 col-sm-9 form-check mt-1">
                        {foreach from=$ARRAY_BLOCK_MODULES item=block_module_i}
                        <label class="custom-control custom-radio custom-control-inline" id="labelmoduletype{$block_module_i.stt}"{if not $block_module_i.show} style="display: none;"{/if}>
                            <input class="custom-control-input moduletype{$block_module_i.stt}" type="radio" name="all_func" value="{$block_module_i.key}"{if $ROW.all_func eq $block_module_i.key} checked="checked"{/if}><span class="custom-control-label">{$block_module_i.value}</span>
                        </label>
                        {/foreach}
                    </div>
                </div>
            </div>
        </div>
        <div class="card card-default" id="shows_all_func"{if $ROW.all_func} style="display: none;"{/if}>
            <div class="card-header card-header-divider mx-0 my-0 px-3">
                {$LANG->get('block_function')} <button type="button" name="checkallmod" class="btn btn-secondary btn-sm"><i class="icon icon-left fas fa-check"></i> {$LANG->get('block_check')}</button>
            </div>
            <div class="list-group list-group-flush">
                {foreach from=$ARRAY_FUNCS item=func_i}
                <div class="list-group-item funclist">
                    <dl class="row mb-0">
                        <dt class="col-12 col-sm-3">
                            <div class="text-truncate text-left">
                                <label><input type="checkbox" value="{$func_i.key}" class="checkmodule"> <strong>{{$func_i.title}}</strong></label>
                            </div>
                        </dt>
                    </dl>
                </div>
                {/foreach}
            </div>
        </div>
        <div class="card card-default mb-0">
            <div class="card-body px-2 py-2 text-center">
                <input type="hidden" name="bid" value="{$ROW.bid}" />
                <input type="submit" name="confirm" value="{$LANG->get('block_confirm')}" class="btn btn-primary">
                <input type="button" onclick="window.close();" value="{$LANG->get('back')}" class="btn btn-secondary">
            </div>
        </div>
    </form>
</div>
<script type="text/javascript">
var bid = parseInt('{$ROW.bid}');
var bid_module = '{$ROW.module}';
var selectthemes = '{$SELECTTHEMES}';
var lang_block_no_func = '{$LANG->get('block_no_func')}';
var lang_block_error_nogroup = '{$LANG->get('block_error_nogroup')}';
</script>
<script type="text/javascript" src="{$NV_BASE_SITEURL}{$NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{$NV_BASE_SITEURL}{$NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{$NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript" src="{$NV_BASE_SITEURL}themes/{$MODULE_THEME}/js/nv.block_content.js"></script>

{*
<!-- BEGIN: main -->
<div class="container block-content-wrap">
        <div class="panel panel-default panel-block-content">
            <div class="panel-heading">
            </div>
            <div class="list-group">
                <!-- BEGIN: loopfuncs -->
                <div class="list-group-item funclist" id="idmodule_{M_TITLE}">
                    <dl class="dl-horizontal">
                        <dd>
                            <div class="row">
                                <!-- BEGIN: fuc -->
                                <div class="col-xs-12 col-sm-6">
                                    <div class="ellipsis">
                                        <label title="{FUNCNAME}"><input type="checkbox"{SELECTED} name="func_id[]" value="{FUNCID}" /> {FUNCNAME}</label>
                                    </div>
                                </div>
                                <!-- END: fuc -->
                            </div>
                        </dd>
                    </dl>
                </div>
                <!-- END: loopfuncs -->
            </div>
        </div>
    </form>
</div>
</div>
<!-- END: main -->

<!-- BEGIN: blockredirect -->
<script type="text/javascript">
    alert('{BLOCKMESS}');
    <!-- BEGIN: redirect -->
    window.opener.location.href = '{BLOCKREDIRECT}';
    <!-- END: redirect -->
    <!-- BEGIN: refresh -->
    window.opener.location.href = window.opener.location.href
    <!-- END: refresh -->
    window.opener.focus();
    window.close();
</script>
<!-- END: blockredirect -->


*}
