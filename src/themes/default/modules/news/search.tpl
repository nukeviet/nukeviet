<link rel="stylesheet" href="{$smarty.const.ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.css">
<script src="{$smarty.const.ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.js"></script>
<script src="{$smarty.const.ASSETS_LANG_STATIC_URL}/js/language/jquery.ui.datepicker-{$smarty.const.NV_LANG_INTERFACE}.js"></script>
<div class="card mb-4">
    <div class="card-body">
        <h1 class="h5 border-bottom pb-3 mb-3"><i class="fa-solid fa-magnifying-glass"></i> {$LANG->getModule('info_title')}</h1>
        <form action="{$smarty.const.NV_BASE_SITEURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=search" method="get" data-form="newsSearch" data-precheck="nv_precheck_form" novalidate>
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label" for="newsSearchKey">{$LANG->getModule('key_title')}</label>
                    <input type="text" class="form-control" id="newsSearchKey" name="q" value="{$KEY}" placeholder="{$LANG->getModule('key_title')}" minlength="{$smarty.const.NV_MIN_SEARCH_LENGTH}" maxlength="{$smarty.const.NV_MAX_SEARCH_LENGTH}" data-valid data-allowed-empty="1" data-error-type="feedback">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="newsSearchChoose">{$LANG->getModule('type_title')}</label>
                    <select class="form-select" id="newsSearchChoose" name="choose">
                        <option value="0"{if $CHOOSE eq 0} selected{/if}>{$LANG->getModule('find_all')}</option>
                        <option value="1"{if $CHOOSE eq 1} selected{/if}>{$LANG->getModule('find_content')}</option>
                        <option value="2"{if $CHOOSE eq 2} selected{/if}>{$LANG->getModule('find_author')}</option>
                        <option value="3"{if $CHOOSE eq 3} selected{/if}>{$LANG->getModule('find_resource')}</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="newsSearchCat">{$LANG->getModule('search_cat')}</label>
                    <select class="form-select" id="newsSearchCat" name="catid">
                        {foreach from=$CATS item=cat}
                        <option value="{$cat.catid}"{if $cat.selected} selected{/if}>{for $i=1 to $cat.lev}&nbsp;&nbsp;&nbsp;{/for}{$cat.title}</option>
                        {/foreach}
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="newsSearchFromDate">{$LANG->getModule('from_date')}</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="newsSearchFromDate" name="from_date" value="{$DATE.from_date}" maxlength="10" autocomplete="off" data-provide="datepicker">
                        <button type="button" class="btn btn-secondary" data-toggle="newsSearchDateBtn" aria-label="{$LANG->getModule('from_date')}"><i class="fa-solid fa-calendar-days"></i></button>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="newsSearchToDate">{$LANG->getModule('to_date')}</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="newsSearchToDate" name="to_date" value="{$DATE.to_date}" maxlength="10" autocomplete="off" data-provide="datepicker">
                        <button type="button" class="btn btn-secondary" data-toggle="newsSearchDateBtn" aria-label="{$LANG->getModule('to_date')}"><i class="fa-solid fa-calendar-days"></i></button>
                    </div>
                </div>
            </div>
            <div class="d-flex flex-wrap align-items-center gap-3 mt-4">
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i> {$LANG->getModule('search_title')}</button>
                <a href="#" role="button" data-toggle="newsSearchOnSite" data-href="{$smarty.const.NV_BASE_SITEURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}=seek&amp;q=">{$LANG->getModule('search_on_site')}</a>
            </div>
        </form>
    </div>
</div>
