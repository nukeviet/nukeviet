<div role="alert" class="alert alert-primary alert-dismissible">
    <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
    <div class="icon"><i class="fas fa-info-circle"></i></div>
    <div class="message">{$LANG->get('xcopyblock_notice')}</div>
</div>
<div class="card card-border-color card-border-color-primary">
    <div class="card-body">
        <form method="post" action="{$FORM_ACTION}" autocomplete="off">
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="theme1">{$LANG->get('xcopyblock')} {$LANG->get('xcopyblock_from')}</label>
                <div class="col-12 col-sm-7 col-md-5 col-lg-4 col-xl-3">
                    <select class="form-control form-control-sm" id="theme1" name="theme1">
                        <option value="0">{$LANG->get('autoinstall_method_theme_none')}</option>
                        {foreach from=$THEME_DBS key=key item=theme_i}
                        {if in_array($theme_i, $THEME_LIST)}
                        <option value="{$theme_i}">{$theme_i}</option>
                        {/if}
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="theme2">{$LANG->get('xcopyblock_to')}</label>
                <div class="col-12 col-sm-7 col-md-5 col-lg-4 col-xl-3">
                    <select class="form-control form-control-sm" id="theme2" name="theme2">
                        <option value="0">{$LANG->get('autoinstall_method_theme_none')}</option>
                        {foreach from=$THEME_DBS key=key item=theme_i}
                        {if in_array($theme_i, $THEME_LIST)}
                        <option value="{$theme_i}"{if $theme_i eq $SELECTTHEME and $SELECTTHEME neq 'default'} selected="selected"{/if}>{$theme_i}</option>
                        {/if}
                        {/foreach}
                    </select>
                </div>
            </div>
            <div id="loadposition"></div>
            <div class="form-group row mb-0 pb-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right"></label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <button class="btn btn-space btn-primary" type="button" name="continue" data-checksess="{$NV_CHECK_SESSION}">{$LANG->get('xcopyblock_process')}</button>
                    <button class="btn btn-space btn-secondary" type="button" data-toggle="checkallpos" data-target="[name='position[]']">{$LANG->get('block_checkall')}</button>
                    <span class="d-none" data-toggle="themeloader"><i class="fas fa-spinner fa-pulse"></i></span>
                    <div class="mt-4 d-none" data-toggle="resarea">
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
LANG.error = "{$LANG->get('error')}";
LANG.autoinstall_package_processing = "{$LANG->get('autoinstall_package_processing')}";
LANG.xcopyblock_no_position = "{$LANG->get('xcopyblock_no_position')}";
</script>
