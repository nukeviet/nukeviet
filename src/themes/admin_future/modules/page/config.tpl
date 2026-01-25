<form method="post" class="ajax-submit" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}" novalidate>
    <div class="row g-3">
        <div class="col-xxl-6">
            <div class="card border-primary border-3 border-bottom-0 border-start-0 border-end-0">
                <div class="card-header fs-5 fw-medium">{$LANG->getModule('config_common')}</div>
                <div class="card-body pt-4">
                    <div class="row mb-3">
                        <label for="ele_viewtype" class="col-sm-3 col-xxl-4 col-form-label text-sm-end">{$LANG->getModule('config_view_type')}</label>
                        <div class="col-sm-4 col-lg-6 col-xxl-8">
                            <select class="form-select w-auto mw-100" id="ele_viewtype" name="viewtype">
                                {for $type=0 to 2}
                                <option value="{$type}"{if $type eq $DATA.viewtype} selected{/if}>{$LANG->getModule("config_view_type_`$type`")}</option>
                                {/for}
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="ele_per_page" class="col-sm-3 col-xxl-4 col-form-label text-sm-end">{$LANG->getModule('config_view_type_page')}</label>
                        <div class="col-sm-4 col-lg-6 col-xxl-8">
                            <select class="form-select w-auto mw-100" id="ele_per_page" name="per_page">
                                {for $value=2 to 30}
                                <option value="{$value}"{if $value eq $DATA.per_page} selected{/if}>{$value}</option>
                                {/for}
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="ele_related_articles" class="col-sm-3 col-xxl-4 col-form-label text-sm-end">{$LANG->getModule('config_view_related_articles')}</label>
                        <div class="col-sm-4 col-lg-6 col-xxl-8">
                            <select class="form-select w-auto mw-100" id="ele_related_articles" name="related_articles">
                                {for $value=2 to 30}
                                <option value="{$value}"{if $value eq $DATA.related_articles} selected{/if}>{$value}</option>
                                {/for}
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-8 col-lg-6 col-xxl-8 offset-sm-3 offset-xxl-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="news_first" value="1"{if $DATA.news_first} checked{/if} role="switch" id="element_news_first">
                                <label class="form-check-label" for="element_news_first">{$LANG->getModule('first_news')}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="ele_facebookapi" class="col-sm-3 col-xxl-4 col-form-label text-sm-end">{$LANG->getModule('config_facebookapi')}</label>
                        <div class="col-sm-4 col-lg-6 col-xxl-7">
                            <input type="text" class="form-control" id="ele_facebookapi" name="facebookapi" value="{$DATA.facebookapi}">
                            <div class="form-text">{$LANG->getModule('config_facebookapi_note')}</div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3 col-xxl-4 col-form-label text-sm-end pt-0">{$LANG->getModule('socialbutton')}</div>
                        <div class="col-sm-8 col-lg-6 col-xxl-8">
                            {foreach from=$SOCIAL_BUTTONS item=button}
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox"{if $button eq 'zalo' and empty($GCONFIG.zaloOfficialAccountID)} disabled{/if} name="socialbutton[]" value="{$button}"{if in_array($button, $DATA.socialbutton, true)} checked{/if} role="switch" id="element_socialbutton_{$button}">
                                <label class="form-check-label opacity-100" for="element_socialbutton_{$button}">
                                    {$button|ucfirst}{if $button eq 'zalo' and empty($GCONFIG.zaloOfficialAccountID)}
                                    (<a href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}=zalo&amp;{$smarty.const.NV_OP_VARIABLE}=settings">{$LANG->getModule('socialbutton_zalo_note')}</a>)
                                    {/if}
                                </label>
                            </div>
                            {/foreach}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-8 col-lg-6 col-xxl-8 offset-sm-3 offset-xxl-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="copy_page" value="1"{if $DATA.copy_page} checked{/if} role="switch" id="element_copy_page">
                                <label class="form-check-label" for="element_copy_page">{$LANG->getModule('setting_copy_page')}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-8 col-lg-6 col-xxl-8 offset-sm-3 offset-xxl-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="alias_lower" value="1"{if $DATA.alias_lower} checked{/if} role="switch" id="element_alias_lower">
                                <label class="form-check-label" for="element_alias_lower">{$LANG->getModule('config_alias_lower')}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-8 col-lg-6 col-xxl-8 offset-sm-3 offset-xxl-4">
                            <button type="submit" class="btn btn-primary">{$LANG->getGlobal('submit')}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-6">
            <div class="card border-primary border-3 border-bottom-0 border-start-0 border-end-0">
                <div class="card-header fs-5 fw-medium">{$LANG->getModule('config_dpost')}</div>
                <div class="card-body pt-4">
                    <div class="row mb-3">
                        <label for="schema_type" class="col-sm-3 col-xxl-4 col-form-label text-sm-end">{$LANG->getModule('schema_type')}</label>
                        <div class="col-sm-4 col-lg-6 col-xxl-8">
                            <select class="form-select w-auto mw-100" id="schema_type" name="schema_type">
                                {foreach from=$SCHEMA_TYPES key=key item=value}
                                <option value="{$key}"{if $key eq $DATA.schema_type} selected{/if}>{$value}</option>
                                {/foreach}
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3{if $DATA.schema_type neq 'webpage'} d-none{/if}" id="schema_about_container">
                        <label for="schema_about" class="col-sm-3 col-xxl-4 col-form-label text-sm-end">{$LANG->getModule('schema_about')}</label>
                        <div class="col-sm-4 col-lg-6 col-xxl-8">
                            <select class="form-select w-auto mw-100" id="schema_about" name="schema_about">
                                {foreach from=$SCHEMA_ABOUTS key=key item=value}
                                <option value="{$key}"{if $key eq $DATA.schema_about} selected{/if}>{$value}</option>
                                {/foreach}
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-8 col-lg-6 col-xxl-8 offset-sm-3 offset-xxl-4">
                            <button type="submit" class="btn btn-primary">{$LANG->getGlobal('submit')}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="save" value="1">
    <input type="hidden" name="checkss" value="{$smarty.const.NV_CHECK_SESSION}">
</form>
