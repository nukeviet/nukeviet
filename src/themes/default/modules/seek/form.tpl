<div class="seek-page">
    <h1 class="fs-3 fw-bold mb-4 text-center">{$LANG->getModule('info_title')}</h1>
    <div id="search-form" class="mb-4">
        <form action="{$smarty.const.NV_BASE_SITEURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}" name="form_search" method="get" id="form_search">
            <div class="mb-3">
                <label class="visually-hidden" for="search_query">{$LANG->getModule('key_title')}</label>
                <input class="form-control" id="search_query" name="q" value="{$DATA.key}" maxlength="{$smarty.const.NV_MAX_SEARCH_LENGTH}" data-minlength="{$smarty.const.NV_MIN_SEARCH_LENGTH}" placeholder="{$LANG->getModule('key_title')}">
            </div>
            <div class="mb-3">
                <label class="visually-hidden" for="search_query_mod">{$LANG->getModule('type_search')}</label>
                <select name="m" id="search_query_mod" class="form-select" data-alert="{$LANG->getModule('chooseModule')}">
                    <option value="all">{$LANG->getModule('search_on_site')}</option>
                    {foreach from=$MODS item=mod}
                    <option data-adv="{if $mod.adv_search}true{else}false{/if}" data-url="{$mod.url}" value="{$mod.value}"{if $mod.is_selected} selected{/if}>{$mod.custom_title}</option>
                    {/foreach}
                </select>
            </div>
            <div class="mb-3 d-flex flex-wrap gap-3 align-items-center justify-content-center">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-magnifying-glass"></i> {$LANG->getModule('search_title')}
                </button>
                <a href="#" class="advSearch">{$LANG->getModule('search_title_adv')}</a>
            </div>
            <div class="d-flex flex-wrap justify-content-center gap-3">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="l" id="search_logic_and" value="1"{if $DATA.logic == 1} checked{/if}>
                    <label class="form-check-label" for="search_logic_and">{$LANG->getModule('logic_and')}</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="l" id="search_logic_or" value="0"{if $DATA.logic != 1} checked{/if}>
                    <label class="form-check-label" for="search_logic_or">{$LANG->getModule('logic_or')}</label>
                </div>
            </div>
        </form>
    </div>

    {if $SEARCH_ENGINE_ID ne ''}
    <script async src="//cse.google.com/cse.js?cx={$SEARCH_ENGINE_ID}"></script>
    <div class="text-center mb-4">
        <a href="#" class="IntSearch"><i class="fa-solid fa-eye" aria-hidden="true"></i> {$LANG->getModule('search_adv_internet')}</a>
    </div>
    <div id="gcse" class="d-none">
        <div class="gcse-search"></div>
    </div>
    {/if}

    {if $IS_SEARCH}
    <div id="search_result">
        <hr>
        {if $DATA.is_error}
        <span class="text-danger">{$DATA.errorInfo}</span>
        {else}
        {$DATA.content nofilter}
        {/if}
    </div>
    {/if}
</div>
