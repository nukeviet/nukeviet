{if not empty($ERROR)}
<div role="alert" class="alert alert-danger alert-dismissible">
    <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
    <div class="icon"><i class="far fa-times-circle"></i></div>
    <div class="message">{$ERROR}</div>
</div>
{/if}
<form method="post" action="{$FORM_ACTION}" autocomplete="off">
    <div class="card card-border-color card-border-color-primary">
        <div class="card-body">
            {if $IS_HOOK_MODULE and empty($HOOK_MODS)}
            <div role="alert" class="alert alert-primary alert-dismissible">
                <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
                <div class="icon"><i class="far fa-times-circle"></i></div>
                <div class="message">{$NO_HOOK_MODULE}</div>
            </div>
            {/if}
            {if empty($RECEIVE_MODS)}
            <div role="alert" class="alert alert-primary alert-dismissible">
                <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
                <div class="icon"><i class="far fa-times-circle"></i></div>
                <div class="message">{$NO_RECEIVE_MODULE}</div>
            </div>
            {/if}
            {if $IS_HOOK_MODULE and not empty($HOOK_MODS)}
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="hook_module">{$LANG->get('plugin_choose_hook_module')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <select class="form-control form-control-sm" id="hook_module" name="hook_module">
                        {foreach from=$HOOK_MODS item=row}
                        <option value="{$row.key}"{if $row.key eq $PLUGIN_HOOK_MODULE} selected="selected"{/if}>{$row.title}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            {/if}
            {if not empty($RECEIVE_MODS)}
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="receive_module">{$LANG->get('plugin_choose_receive_module')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <select class="form-control form-control-sm" id="receive_module" name="receive_module">
                        {foreach from=$RECEIVE_MODS item=row}
                        <option value="{$row.key}"{if $row.key eq $PLUGIN_MODULE_NAME} selected="selected"{/if}>{$row.title}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            {/if}
            {if $SUBMIT_ALLOWED}
            <div class="form-group row mb-0 pb-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right"></label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <button class="btn btn-space btn-primary" type="submit" name="submit">{$LANG->get('submit')}</button>
                </div>
            </div>
            {/if}
        </div>
    </div>
</form>
