{if not empty($ERROR)}
<div role="alert" class="alert alert-danger alert-dismissible">
    <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
    <div class="icon"><i class="far fa-times-circle"></i></div>
    <div class="message">{$ERROR}</div>
</div>
{/if}
<div class="card card-border-color card-border-color-primary">
    <div class="card-body">
        <form method="post" action="{$FORM_ACTION}" autocomplete="off">
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="mod_name">{$LANG->get('module_name')} <i class="text-danger">(*)</i></label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <input type="text" class="form-control form-control-sm" id="mod_name" name="mod_name" value="{$DATA['mod_name']}" readonly="readonly" maxlength="55">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="custom_title">{$LANG->get('custom_title')} <i class="text-danger">(*)</i></label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <input type="text" class="form-control form-control-sm" id="custom_title" name="custom_title" value="{$DATA['custom_title']}" maxlength="100">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="admin_title">{$LANG->get('admin_title')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <input type="text" class="form-control form-control-sm" id="admin_title" name="admin_title" value="{$DATA['admin_title']}" maxlength="100">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="theme">{$LANG->get('theme')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <select class="form-control form-control-sm" id="theme" name="theme">
                        <option value="">{$LANG->get('theme_default')}</option>
                        {foreach from=$THEME_LIST item=theme}
                        <option value="{$theme}"{if $theme eq $DATA.theme} selected="selected"{/if}>{$theme}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            {if not empty($THEME_MOBILE_DEFAULT) and not empty($THEME_MOBILE_LIST)}
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="mobile">{$LANG->get('mobile')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <select class="form-control form-control-sm" id="mobile" name="mobile">
                        {foreach from=$THEME_MOBILE_DEFAULT item=theme}
                        <option value="{$theme.key}"{if $theme.key eq $DATA.mobile} selected="selected"{/if}>{$theme.title}</option>
                        {/foreach}
                        {foreach from=$THEME_MOBILE_LIST item=theme}
                        <option value="{$theme}"{if $theme eq $DATA.mobile} selected="selected"{/if}>{$theme}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            {/if}
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="site_title">{$LANG->get('site_title')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <input type="text" class="form-control form-control-sm" id="site_title" name="site_title" value="{$DATA['site_title']}" maxlength="255">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="description">{$LANG->get('description')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <input type="text" class="form-control form-control-sm" id="description" name="description" value="{$DATA['description']}" maxlength="255">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="keywords">{$LANG->get('keywords')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <input type="text" class="form-control form-control-sm" id="keywords" name="keywords" value="{$DATA['keywords']}" maxlength="255">
                    <span class="form-text text-muted">{$LANG->get('keywords_info')}</span>
                </div>
            </div>
            <div class="form-group row pt-1">
                <label class="col-12 col-sm-3 col-form-label text-sm-right">{$LANG->get('active')}</label>
                <div class="col-12 col-sm-8 col-lg-6 pt-1">
                    <div class="switch-button switch-button-yesno">
                        <input type="checkbox" name="act" id="act"{if $DATA.act} checked="checked"{/if} value="1"><span><label for="act"></label></span>
                    </div>
                </div>
            </div>
            {if isset($DATA.rss)}
            <div class="form-group row pt-1">
                <label class="col-12 col-sm-3 col-form-label text-sm-right">{$LANG->get('activate_rss')}</label>
                <div class="col-12 col-sm-8 col-lg-6 pt-1">
                    <div class="switch-button switch-button-yesno">
                        <input type="checkbox" name="rss" id="rss"{if $DATA.rss} checked="checked"{/if} value="1"><span><label for="rss"></label></span>
                    </div>
                </div>
            </div>
            {/if}
            {if isset($DATA.sitemap)}
            <div class="form-group row pt-1">
                <label class="col-12 col-sm-3 col-form-label text-sm-right">{$LANG->get('activate_sitemap')}</label>
                <div class="col-12 col-sm-8 col-lg-6 pt-1">
                    <div class="switch-button switch-button-yesno">
                        <input type="checkbox" name="sitemap" id="sitemap"{if $DATA.sitemap} checked="checked"{/if} value="1"><span><label for="sitemap"></label></span>
                    </div>
                </div>
            </div>
            {/if}
            {if $SITE_HOME_MODULE neq $DATA.mod_name}
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right">{$LANG->get('groups_view')}</label>
                <div class="col-12 col-sm-8 col-lg-6 mt-1">
                    {foreach $GROUPS_LIST key=groupid item=groupname}
                    <label class="custom-control custom-checkbox">
                        <input class="custom-control-input" type="checkbox" name="groups_view[]" value="{$groupid}"{if in_array($groupid, $DATA.groups_view)} checked="checked"{/if}><span class="custom-control-label">{$groupname}</span>
                    </label>
                    {/foreach}
                </div>
            </div>
            {/if}
            <div class="form-group row mb-0 pb-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right"></label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <input name="save" id="save" type="hidden" value="1">
                    <input name="module_theme" type="hidden" value="{$DATA.module_theme}" />
                    <button class="btn btn-space btn-primary" type="submit" name="go_add">{$LANG->get('submit')}</button>
                </div>
            </div>
        </form>
    </div>
</div>
