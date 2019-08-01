{if isset($SET_LAYOUT_SITE)}
<div role="alert" class="alert alert-success alert-dismissible">
    <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
    <div class="icon"><i class="fas fa-check"></i></div>
    <div class="message">{$LANG->get('setup_updated_layout')}</div>
</div>
{/if}
<div class="card card-flat">
    <div class="card-body pt-4">
        <form method="post" action="{$FORM_ACTION}" class="form-inline">
            <div class="form-group">
                <label class="mr-1">{$LANG->get('setup_select_layout')}: </label>
                <select name="layout" class="form-control form-control-sm mr-1">
                    <option value="">{$LANG->get('setup_select_layout')}</option>
                    {foreach from=$LAYOUT_ARRAY item=layout}
                    <option value="{$layout}">{$layout}</option>
                    {/foreach}
                </select>
            </div>
            <div class="form-group">
                <label class="mr-1"> {$LANG->get('add_block_module')}: </label>
                <select name="block_module" class="form-control form-control-sm mr-1">
                    <option value="">{$LANG->get('add_block_all_module')}</option>
                    {foreach from=$ARRAY_MODULES item=module}
                    <option value="{$module.title}">{$module.custom_title}</option>
                    {/foreach}
                </select>
            </div>
            <div class="form-group">
                <input name="saveall" type="submit" value="{$LANG->get('setup_save_layout')}" class="btn btn-primary btn-input-sm">
            </div>
        </form>
    </div>
</div>

<form method="post" action="{$FORM_ACTION}">
    <div class="row">
        {foreach from=$ARRAY_MODULES item=module}
        <div class="col-12 col-sm-6 col-md-4">
            <div class="card card-flat">
                <div class="card-header card-header-divider">
                    {$module.custom_title}
                </div>
                <div class="card-body">
                    {if isset($ARRAY_LAYOUT_FUNC[$module.title])}
                    {foreach from=$ARRAY_LAYOUT_FUNC[$module.title] key=func_name item=func_arr_val}
                    <div class="row my-2">
                        <div class="col-12 col-md-6">
                            {$func_arr_val.1}
                        </div>
                        <div class="col-12 col-md-6">
                            <select class="form-control form-control-sm" name="func[{$func_arr_val.0}]">
                                {foreach from=$LAYOUT_ARRAY item=layout}
                                <option value="{$layout}"{if $layout eq $func_arr_val.2} selected="selected"{/if}>{$layout}</option>
                                {/foreach}
                            </select>
                        </div>
                    </div>
                    {/foreach}
                    {/if}
                </div>
            </div>
        </div>
        {/foreach}
    </div>

    <div class="card card-flat">
        <div class="card-body pt-4">
            <div class="text-center">
                <input name="save" type="submit" value="{$LANG->get('setup_save_layout')}" class="btn btn-primary">
            </div>
        </div>
    </div>
</form>
