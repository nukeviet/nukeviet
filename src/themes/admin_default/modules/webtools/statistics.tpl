<script src="{$smarty.const.ASSETS_STATIC_URL}/js/select2/select2.min.js"></script>
<script src="{$smarty.const.ASSETS_STATIC_URL}/js/select2/i18n/{$smarty.const.NV_LANG_INTERFACE}.js"></script>
<div class="card border-primary border-3 border-bottom-0 border-start-0 border-end-0">
    <div class="card-body pt-4">
        <form method="post" class="ajax-submit" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}" novalidate>
            <div class="row mb-3">
                <label for="element_statistics_timezone" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('statistics_timezone')}</label>
                <div class="col-sm-4 col-lg-6 col-xxl-5">
                    <select class="form-select select2" id="element_statistics_timezone" name="statistics_timezone">
                        {foreach from=$TIMEZONE_ARRAY item=timezone}
                        <option value="{$timezone}"{if $timezone eq $GCONFIG.statistics_timezone} selected{/if}>{$timezone}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-8 col-lg-6 col-xxl-5 offset-sm-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="online_upd" value="1"{if not empty($GCONFIG.online_upd)} checked{/if} role="switch" id="element_online_upd">
                        <label class="form-check-label" for="element_online_upd">{$LANG->getModule('online_upd')}</label>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-8 col-lg-6 col-xxl-5 offset-sm-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="statistic" value="1"{if not empty($GCONFIG.statistic)} checked{/if} role="switch" id="element_statistic">
                        <label class="form-check-label" for="element_statistic">{$LANG->getModule('statistic')}</label>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-8 col-lg-6 col-xxl-5 offset-sm-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="stat_excl_bot" value="1"{if not empty($GCONFIG.stat_excl_bot)} checked{/if} role="switch" id="element_stat_excl_bot">
                        <label class="form-check-label" for="element_stat_excl_bot">{$LANG->getModule('stat_excl_bot')}</label>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-8 col-lg-6 col-xxl-5 offset-sm-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="referer_blocker" value="1"{if not empty($GCONFIG.referer_blocker)} checked{/if} role="switch" id="element_referer_blocker">
                        <label class="form-check-label" for="element_referer_blocker">{$LANG->getModule('referer_blocker')}</label>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="element_googleAnalytics4ID" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('googleAnalytics4ID')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <input type="text" class="form-control" id="element_googleAnalytics4ID" name="googleAnalytics4ID" value="{$GCONFIG.googleAnalytics4ID}" maxlength="20">
                    <div class="form-text">{$LANG->getModule('googleAnalytics4ID_help')}</div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="element_googleAnalyticsID" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('googleAnalyticsID')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <input type="text" class="form-control" id="element_googleAnalyticsID" name="googleAnalyticsID" value="{$GCONFIG.googleAnalyticsID}" maxlength="20">
                    <div class="form-text">{$LANG->getModule('googleAnalyticsID_help')}</div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="element_google_tag_manager" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('google_tag_manager')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <input type="text" class="form-control" id="element_google_tag_manager" name="google_tag_manager" value="{$GCONFIG.google_tag_manager}" maxlength="20">
                    <div class="form-text">{$LANG->getModule('google_tag_manager_help')}</div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-8 col-lg-6 col-xxl-5 offset-sm-3">
                    <input type="hidden" name="checkss" value="{$CHECKSS}">
                    <button type="submit" class="btn btn-primary">{$LANG->getGlobal('submit')}</button>
                </div>
            </div>
        </form>
    </div>
</div>
