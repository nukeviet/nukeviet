{if not empty($SEARCHENGINES.searchEngine) and not empty($SITEMAPFILES)}
<form method="post" class="ajax-submit" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}" novalidate>
    <div class="card mb-3">
        <div class="card-header fs-5 fw-medium"><a target="_blank" href="{$URL_SITEMAP}" aria-label="Sitemap"><i class="fa-solid fa-sitemap"></i></a> {$LANG->getModule('sitemapPing')}</div>
        <div class="card-body">
            <div class="row mb-3">
                <label for="element_searchEngine" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('searchEngineSelect')} <span class="text-danger">(*)</span></label>
                <div class="col-sm-4 col-lg-6 col-xxl-5">
                    <select class="form-select" id="element_searchEngine" name="searchEngine">
                        <option value="">{$LANG->getModule('searchEngineSelect')}</option>
                        {foreach from=$SEARCHENGINES.searchEngine item=value}
                        <option value="{$value.name}"{if empty($value.active)} disabled{/if}>{$value.name}</option>
                        {/foreach}
                    </select>
                    <div class="invalid-feedback">{$LANG->getGlobal('required_invalid')}</div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="element_in_module" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('sitemapModule')} <span class="text-danger">(*)</span></label>
                <div class="col-sm-4 col-lg-6 col-xxl-5">
                    <select class="form-select" id="element_in_module" name="in_module">
                        <option value="">{$LANG->getModule('sitemapModule')}</option>
                        {foreach from=$SITEMAPFILES key=key item=value}
                        <option value="{$key}">{$value}</option>
                        {/foreach}
                    </select>
                    <div class="invalid-feedback">{$LANG->getGlobal('required_invalid')}</div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-8 col-lg-6 col-xxl-5 offset-sm-3">
                    <input type="hidden" name="checkss1" value="{$CHECKSS}">
                    <input name="ping" type="hidden" value="1">
                    <button type="submit" class="btn btn-primary">{$LANG->getModule('sitemapSend')}</button>
                </div>
            </div>
        </div>
    </div>
</form>
{/if}
{if empty($GCONFIG.idsite)}
<form method="post" class="ajax-submit" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}" novalidate>
    <div class="card">
        <div class="card-header fs-5 fw-medium">{$LANG->getModule('searchEngineConfig')}</div>
        <div class="card-body">
            <div class="table-responsive-lg table-card" id="list-news-items">
                <table class="table table-striped align-middle table-sticky mb-0">
                    <thead class="text-muted">
                        <tr>
                            <th class="text-nowrap" style="width: 40%;">{$LANG->getModule('searchEngineName')}</th>
                            <th class="text-nowrap" style="width: 40%;">{$LANG->getModule('searchEngineValue')}</th>
                            <th class="text-nowrap text-center" style="width: 20%;">{$LANG->getModule('searchEngineActive')}</th>
                        </tr>
                    </thead>
                    <tbody>
                        {assign var="rows" value=($SEARCHENGINES.searchEngine ?: [])}
                        {assign var="rows" value=array_merge($rows, [
                            ['name' => '', 'value' => '', 'active' => 0],
                            ['name' => '', 'value' => '', 'active' => 0]
                        ])}
                        {foreach from=$rows item=row}
                        <tr>
                            <td>
                                <input class="form-control" type="text" value="{$row.name}" name="searchEngineName[]">
                            </td>
                            <td>
                                <input class="form-control" type="text" value="{$row.value}" name="searchEngineValue[]">
                            </td>
                            <td class="text-center">
                                <div class="d-inline-flex">
                                    <div class="form-check form-switch mb-0">
                                        <input class="form-check-input" type="checkbox" role="switch" aria-label="{$LANG->getGlobal('activate')}" name="searchEngineActive[]" {if not empty($row.active)} checked{/if} value="1">
                                    </div>
                                </div>
                            </td>
                        </tr>
                        {/foreach}
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer border-top">
            <input type="hidden" name="checkss2" value="{$CHECKSS}">
            <button type="submit" class="btn btn-primary">{$LANG->getGlobal('submit')}</button>
        </div>
    </div>
</form>
{/if}
