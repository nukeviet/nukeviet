{if not empty($SEARCHENGINES) and not empty($SITEMAPFILES)}
<form method="post" action="{$FORM_ACTION}" autocomplete="off">
    {if not empty($SUBMIT_INFO)}
    <div role="alert" class="alert alert-primary alert-dismissible">
        <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
        <div class="icon"><i class="fas fa-info-circle"></i></div>
        <div class="message">{$SUBMIT_INFO}</div>
    </div>
    {/if}
    <div class="card card-border-color card-border-color-primary">
        <div class="card-header card-header-divider mx-0 px-4">
            <i class="fas fa-shipping-fast mr-2"></i><a href="{$URL_SITEMAP}">{$LANG->get('sitemapPing')}</a>
        </div>
        <div class="card-body">
            <div class="form-inline mt-2">
                <select name="searchEngine" class="form-control form-control-sm btn-space">
                    <option value="">{$LANG->get('searchEngineSelect')}</option>
                    {foreach from=$SEARCHENGINES item=row}
                    {if $row.active}
                    <option value="{$row.name}"{if $SUBMIT_SEARCHENGINE eq $row.name}{/if}>{$row.name}</option>
                    {/if}
                    {/foreach}
                </select>
                <select name="in_module" class="form-control form-control-sm btn-space">
                    <option value="">{$LANG->get('sitemapModule')}</option>
                    {foreach from=$SITEMAPFILES key=key item=row}
                    <option value="{$key}"{if $SUBMIT_MODULE eq $key}{/if}>{$row}</option>
                    {/foreach}
                </select>
                <button class="btn btn-space btn-primary btn-input-sm" type="submit" name="ping">{$LANG->get('sitemapSend')}</button>
            </div>
        </div>
    </div>
</form>
{/if}
{if $ALLOWED_MANAGE and not empty($SEARCHENGINES)}
<form method="post" action="{$FORM_ACTION}" autocomplete="off">
    <div class="card card-table">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th style="width: 45%;" class="text-nowrap">{$LANG->get('searchEngineName')}</th>
                            <th style="width: 45%;" class="text-nowrap">{$LANG->get('searchEngineValue')}</th>
                            <th style="width: 10%;" class="text-nowrap">{$LANG->get('searchEngineActive')}</th>
                        </tr>
                    </thead>
                    <tbody>
                        {assign var="stt" value="0" nocache}
                        {foreach from=$SEARCHENGINES key=key item=row}
                        <tr>
                            <td><input class="form-control form-control-xs" type="text" value="{$row.name}" name="searchEngineName[]"></td>
                            <td><input class="form-control form-control-xs" type="text" value="{$row.value}" name="searchEngineValue[]"></td>
                            <td>
                                <div class="switch-button switch-button-lg">
                                    <input type="checkbox" name="searchEngineActive_{$stt++}" value="1" id="swt{$key}"{if $row.active} checked="checked"{/if}><span><label for="swt{$key}"></label></span>
                                </div>
                            </td>
                        </tr>
                        {/foreach}
                        <tr>
                            <td><input class="form-control form-control-xs" type="text" value="" name="searchEngineName[]"></td>
                            <td><input class="form-control form-control-xs" type="text" value="" name="searchEngineValue[]"></td>
                            <td>
                                <div class="switch-button switch-button-lg">
                                    <input type="checkbox" name="searchEngineActive_{$stt++}" value="1" id="swt_xxxx"><span><label for="swt_xxxx"></label></span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><input class="form-control form-control-xs" type="text" value="" name="searchEngineName[]"></td>
                            <td><input class="form-control form-control-xs" type="text" value="" name="searchEngineValue[]"></td>
                            <td>
                                <div class="switch-button switch-button-lg">
                                    <input type="checkbox" name="searchEngineActive_{$stt++}" value="1" id="swt_yyyy"><span><label for="swt_yyyy"></label></span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer text-center">
            <button class="btn btn-space btn-primary" type="submit" name="submit">{$LANG->get('submit')}</button>
        </div>
    </div>
</form>
{/if}
