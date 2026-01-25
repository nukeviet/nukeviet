<script src="{$smarty.const.ASSETS_STATIC_URL}/js/luxon/luxon.min.js"></script>
<div class="row">
    <div class="col-lg-12 col-xl-8 col-xxl-6 order-1 order-xl-0">
        <div class="card">
            <div class="card-header card-header-tabs">
                <ul class="nav nav-tabs nav-justified" id="tab-region">
                    <li class="nav-item">
                        <a class="nav-link text-truncate{$TAB eq 'numbers' ? ' active' : ''}" data-bs-toggle="tab" id="link-numbers" data-tab="numbers" data-bs-target="#tab-numbers" aria-current="{$TAB eq 'numbers' ? 'true' : 'false'}" role="tab" aria-controls="tab-numbers" aria-selected="{$TAB eq 'numbers' ? 'true' : 'false'}" href="#" data-location="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}&amp;tab=numbers">{$LANG->getModule('region_numbers')}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-truncate{$TAB eq 'currency' ? ' active' : ''}" data-bs-toggle="tab" id="link-currency" data-tab="currency" data-bs-target="#tab-currency" aria-current="{$TAB eq 'currency' ? 'true' : 'false'}" role="tab" aria-controls="tab-currency" aria-selected="{$TAB eq 'currency' ? 'true' : 'false'}" href="#" data-location="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}&amp;tab=currency">{$LANG->getModule('region_currency')}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-truncate{$TAB eq 'date' ? ' active' : ''}" data-bs-toggle="tab" id="link-date" data-tab="date" data-bs-target="#tab-date" aria-current="{$TAB eq 'numbers' ? 'date' : 'false'}" role="tab" aria-controls="tab-date" aria-selected="{$TAB eq 'date' ? 'true' : 'false'}" href="#" data-location="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}&amp;tab=date">{$LANG->getModule('region_date')}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-truncate{$TAB eq 'time' ? ' active' : ''}" data-bs-toggle="tab" id="link-time" data-tab="time" data-bs-target="#tab-time" aria-current="{$TAB eq 'time' ? 'true' : 'false'}" role="tab" aria-controls="tab-time" aria-selected="{$TAB eq 'time' ? 'true' : 'false'}" href="#" data-location="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}&amp;tab=time">{$LANG->getModule('region_time')}</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <form id="form-region" method="post" class="ajax-submit" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}" novalidate>
                    <div class="tab-content">
                        <div class="tab-pane fade{$TAB eq 'numbers' ? ' show active' : ''}" id="tab-numbers" role="tabpanel" aria-labelledby="link-numbers" tabindex="0">
                            <div class="form-contents">
                                <div class="row mb-3">
                                    <label for="element_decimal_symbol" class="col-12 col-sm-3 col-form-label text-sm-end">{$LANG->getModule('decimal_symbol')} <span class="text-danger">(*)</span></label>
                                    <div class="col-12 col-sm-8 col-lg-6 col-xxl-5">
                                        <input type="text" class="form-control required" id="element_decimal_symbol" name="decimal_symbol" value="{$DATA.decimal_symbol}" maxlength="1" list="decimal_symbol_lists">
                                        <datalist id="decimal_symbol_lists">
                                            <option value=",">{$LANG->getModule('comma')}</option>
                                            <option value=".">{$LANG->getModule('dot')}</option>
                                            <option value="'">{$LANG->getModule('single_quote')}</option>
                                        </datalist>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="element_decimal_length" class="col-12 col-sm-3 col-form-label text-sm-end">{$LANG->getModule('decimal_length')}</label>
                                    <div class="col-12 col-sm-8 col-lg-6 col-xxl-5">
                                        <select class="form-select" id="element_decimal_length" name="decimal_length">
                                            {for $length=0 to 9}
                                            <option value="{$length}"{if $length eq $DATA.decimal_length} selected{/if}>{$length}</option>
                                            {/for}
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="element_thousand_symbol" class="col-12 col-sm-3 col-form-label text-sm-end">{$LANG->getModule('thousand_symbol')} <span class="text-danger">(*)</span></label>
                                    <div class="col-12 col-sm-8 col-lg-6 col-xxl-5">
                                        <input type="text" class="form-control required" id="element_thousand_symbol" name="thousand_symbol" value="{$DATA.thousand_symbol}" maxlength="1" list="thousand_symbol_lists">
                                        <datalist id="thousand_symbol_lists">
                                            <option value=",">{$LANG->getModule('comma')}</option>
                                            <option value=".">{$LANG->getModule('dot')}</option>
                                            <option value="'">{$LANG->getModule('single_quote')}</option>
                                        </datalist>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-12 col-sm-8 col-lg-6 col-xxl-5 offset-sm-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="leading_zero"{if $DATA.leading_zero} checked{/if} value="1" role="switch" id="element_leading_zero">
                                            <label class="form-check-label" for="element_leading_zero">{$LANG->getModule('leading_zero')}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-12 col-sm-8 col-lg-6 col-xxl-5 offset-sm-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="trailing_zero" value="1"{if $DATA.trailing_zero} checked{/if} role="switch" id="element_trailing_zero">
                                            <label class="form-check-label" for="element_trailing_zero">{$LANG->getModule('trailing_zero')}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-sm-8 col-lg-6 col-xxl-5 offset-sm-3">
                                        <button type="submit" class="btn btn-primary">{$LANG->getGlobal('submit')}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade{$TAB eq 'currency' ? ' show active' : ''}" id="tab-currency" role="tabpanel" aria-labelledby="link-currency" tabindex="0">
                            <div class="form-contents">
                                <div class="row mb-3">
                                    <label for="element_currency_symbol" class="col-12 col-sm-3 col-form-label text-sm-end">{$LANG->getModule('currency_symbol')} <span class="text-danger">(*)</span></label>
                                    <div class="col-12 col-sm-8 col-lg-6 col-xxl-5">
                                        <input type="text" class="form-control required" id="element_currency_symbol" name="currency_symbol" value="{$DATA.currency_symbol}" maxlength="10" list="currency_symbol_lists">
                                        <datalist id="currency_symbol_lists">
                                            <option value="đ">đ</option>
                                            <option value="$">$</option>
                                            <option value="€">€</option>
                                        </datalist>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="element_currency_display" class="col-12 col-sm-3 col-form-label text-sm-end">{$LANG->getModule('currency_display')}</label>
                                    <div class="col-12 col-sm-8 col-lg-6 col-xxl-5">
                                        <select class="form-select" id="element_currency_display" name="currency_display">
                                            {for $display=0 to 3}
                                            <option value="{$display}"{$DATA.currency_display eq $display ? ' selected' : ''}>{$LANG->getModule("currency_display`$display`")}</option>
                                            {/for}
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="element_currency_decimal_symbol" class="col-12 col-sm-3 col-form-label text-sm-end">{$LANG->getModule('decimal_symbol')} <span class="text-danger">(*)</span></label>
                                    <div class="col-12 col-sm-8 col-lg-6 col-xxl-5">
                                        <input type="text" class="form-control required" id="element_currency_decimal_symbol" name="currency_decimal_symbol" value="{$DATA.currency_decimal_symbol}" maxlength="1" list="currency_decimal_symbol_lists">
                                        <datalist id="currency_decimal_symbol_lists">
                                            <option value=",">{$LANG->getModule('comma')}</option>
                                            <option value=".">{$LANG->getModule('dot')}</option>
                                            <option value="'">{$LANG->getModule('single_quote')}</option>
                                        </datalist>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="element_currency_decimal_length" class="col-12 col-sm-3 col-form-label text-sm-end">{$LANG->getModule('decimal_length')}</label>
                                    <div class="col-12 col-sm-8 col-lg-6 col-xxl-5">
                                        <select class="form-select" id="element_currency_decimal_length" name="currency_decimal_length">
                                            {for $length=0 to 9}
                                            <option value="{$length}"{if $length eq $DATA.currency_decimal_length} selected{/if}>{$length}</option>
                                            {/for}
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="element_currency_thousand_symbol" class="col-12 col-sm-3 col-form-label text-sm-end">{$LANG->getModule('thousand_symbol')} <span class="text-danger">(*)</span></label>
                                    <div class="col-12 col-sm-8 col-lg-6 col-xxl-5">
                                        <input type="text" class="form-control required" id="element_currency_thousand_symbol" name="currency_thousand_symbol" value="{$DATA.currency_thousand_symbol}" maxlength="1" list="currency_thousand_symbol_lists">
                                        <datalist id="currency_thousand_symbol_lists">
                                            <option value=",">{$LANG->getModule('comma')}</option>
                                            <option value=".">{$LANG->getModule('dot')}</option>
                                            <option value="'">{$LANG->getModule('single_quote')}</option>
                                        </datalist>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-12 col-sm-8 col-lg-6 col-xxl-5 offset-sm-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="currency_trailing_zero" value="1"{$DATA.currency_trailing_zero} checked/if} role="switch" id="element_currency_trailing_zero">
                                            <label class="form-check-label" for="element_currency_trailing_zero">{$LANG->getModule('trailing_zero')}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-sm-8 col-lg-6 col-xxl-5 offset-sm-3">
                                        <button type="submit" class="btn btn-primary">{$LANG->getGlobal('submit')}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade{$TAB eq 'date' ? ' show active' : ''}" id="tab-date" role="tabpanel" aria-labelledby="link-date" tabindex="0">
                            <div class="form-contents">
                                <div class="row mb-3">
                                    <label for="element_date_short" class="col-12 col-sm-3 col-form-label text-sm-end">{$LANG->getModule('region_display_short')} <span class="text-danger">(*)</span></label>
                                    <div class="col-12 col-sm-8 col-lg-6 col-xxl-5">
                                        <input type="text" class="form-control required" id="element_date_short" name="date_short" value="{$DATA.date_short}" maxlength="50" list="element_date_short_lists">
                                        <datalist id="element_date_short_lists">
                                            <option value="d/m/Y">
                                            <option value="d/m/y">
                                            <option value="d-m-y">
                                            <option value="d-m-Y">
                                            <option value="m/d/Y">
                                            <option value="m/d/y">
                                            <option value="m-d-Y">
                                            <option value="m-d-y">
                                            <option value="Y/m/d">
                                            <option value="Y-m-d">
                                        </datalist>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="element_date_long" class="col-12 col-sm-3 col-form-label text-sm-end">{$LANG->getModule('region_display_long')} <span class="text-danger">(*)</span></label>
                                    <div class="col-12 col-sm-8 col-lg-6 col-xxl-5">
                                        <input type="text" class="form-control required" id="element_date_long" name="date_long" value="{$DATA.date_long}" maxlength="50" list="element_date_long_lists">
                                        <datalist id="element_date_long_lists">
                                            <option value="l, d F Y">
                                            <option value="d F Y">
                                        </datalist>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="element_first_day_of_week" class="col-12 col-sm-3 col-form-label text-sm-end">{$LANG->getModule('first_day_of_week')}</label>
                                    <div class="col-12 col-sm-8 col-lg-6 col-xxl-5">
                                        <select class="form-select" id="element_first_day_of_week" name="first_day_of_week">
                                            <option value="0"{if $DATA.first_day_of_week eq 0} selected{/if}>{$LANG->getGlobal('monday')}</option>
                                            <option value="1"{if $DATA.first_day_of_week eq 1} selected{/if}>{$LANG->getGlobal('sunday')}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="element_date_get" class="col-12 col-sm-3 col-form-label text-sm-end">{$LANG->getModule('date_get')}</label>
                                    <div class="col-12 col-sm-8 col-lg-6 col-xxl-5">
                                        <select class="form-select" id="element_date_get" name="date_get">
                                            {foreach from=$FORMAT_GET item=fmt}
                                            <option value="{$fmt}"{if $DATA.date_get eq $fmt} selected{/if}>{$fmt}</option>
                                            {/foreach}
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="element_date_post" class="col-12 col-sm-3 col-form-label text-sm-end">{$LANG->getModule('date_post')}</label>
                                    <div class="col-12 col-sm-8 col-lg-6 col-xxl-5">
                                        <select class="form-select" id="element_date_post" name="date_post">
                                            {foreach from=$FORMAT_POST item=fmt}
                                            <option value="{$fmt}"{if $DATA.date_post eq $fmt} selected{/if}>{$fmt}</option>
                                            {/foreach}
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-sm-8 col-lg-6 col-xxl-5 offset-sm-3">
                                        <button type="submit" class="btn btn-primary">{$LANG->getGlobal('submit')}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade{$TAB eq 'time' ? ' show active' : ''}" id="tab-time" role="tabpanel" aria-labelledby="link-time" tabindex="0">
                            <div class="form-contents">
                                <div class="row mb-3">
                                    <label for="element_time_short" class="col-12 col-sm-3 col-form-label text-sm-end">{$LANG->getModule('region_display_short')} <span class="text-danger">(*)</span></label>
                                    <div class="col-12 col-sm-8 col-lg-6 col-xxl-5">
                                        <input type="text" class="form-control required" id="element_time_short" name="time_short" value="{$DATA.time_short}" maxlength="50" list="element_time_short_lists">
                                        <datalist id="element_time_short_lists">
                                            <option value="g:i A">
                                            <option value="H:i">
                                        </datalist>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="element_time_long" class="col-12 col-sm-3 col-form-label text-sm-end">{$LANG->getModule('region_display_long')} <span class="text-danger">(*)</span></label>
                                    <div class="col-12 col-sm-8 col-lg-6 col-xxl-5">
                                        <input type="text" class="form-control required" id="element_time_long" name="time_long" value="{$DATA.time_long}" maxlength="50" list="element_time_long_lists">
                                        <datalist id="element_time_long_lists">
                                            <option value="g:i:s A">
                                            <option value="H:i:s">
                                        </datalist>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="element_am_char" class="col-12 col-sm-3 col-form-label text-sm-end">{$LANG->getModule('char_am')} <span class="text-danger">(*)</span></label>
                                    <div class="col-12 col-sm-8 col-lg-6 col-xxl-5">
                                        <input type="text" class="form-control required" id="element_am_char" name="am_char" value="{$DATA.am_char}" maxlength="50" list="element_am_char_lists">
                                        <datalist id="element_am_char_lists">
                                            <option value="SA">
                                            <option value="AM">
                                        </datalist>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="element_pm_char" class="col-12 col-sm-3 col-form-label text-sm-end">{$LANG->getModule('char_pm')} <span class="text-danger">(*)</span></label>
                                    <div class="col-12 col-sm-8 col-lg-6 col-xxl-5">
                                        <input type="text" class="form-control required" id="element_pm_char" name="pm_char" value="{$DATA.pm_char}" maxlength="50" list="element_pm_char_lists">
                                        <datalist id="element_pm_char_lists">
                                            <option value="CH">
                                            <option value="PM">
                                        </datalist>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-sm-8 col-lg-6 col-xxl-5 offset-sm-3">
                                        <button type="submit" class="btn btn-primary">{$LANG->getGlobal('submit')}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="tab" value="numbers">
                    <input type="hidden" name="saveform" value="{$smarty.const.NV_CHECK_SESSION}">
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-xl-4 col-xxl-6 order-0 order-xl-1">
        <div class="sticky-xl-top">
            <div class="card mb-4">
                <div class="card-body" id="preview-region">
                    <h5 class="card-title">{$LANG->getGlobal('preview')}</h5>
                    <div data-toggle="preview" data-tab="numbers" id="preview-numbers"{$TAB neq 'numbers' ? ' class="d-none"' : ''}>
                        <div class="row">
                            <div class="col-4 mb-2">
                                {$LANG->getModule('region_numbers_p')}
                            </div>
                            <div class="col-8 mb-2" id="lbl_demo_numbers_p">
                            </div>
                            <div class="col-4">
                                {$LANG->getModule('region_numbers_n')}
                            </div>
                            <div class="col-8" id="lbl_demo_numbers_n">
                            </div>
                        </div>
                    </div>
                    <div data-toggle="preview" data-tab="currency" id="preview-currency"{$TAB neq 'currency' ? ' class="d-none"' : ''}>
                        <div class="row">
                            <div class="col-4 mb-2">
                                {$LANG->getModule('region_currency_p')}
                            </div>
                            <div class="col-8 mb-2" id="lbl_demo_currency_p">
                            </div>
                            <div class="col-4">
                                {$LANG->getModule('region_currency_n')}
                            </div>
                            <div class="col-8" id="lbl_demo_currency_n">
                            </div>
                        </div>
                    </div>
                    <div data-toggle="preview" data-tab="date" id="preview-date"{$TAB neq 'date' ? ' class="d-none"' : ''}>
                        <div class="row">
                            <div class="col-4 mb-2">
                                {$LANG->getModule('region_display_short')}
                            </div>
                            <div class="col-8 mb-2" id="lbl_demo_date_short">
                            </div>
                            <div class="col-4 mb-2">
                                {$LANG->getModule('region_display_long')}
                            </div>
                            <div class="col-8 mb-2" id="lbl_demo_date_long">
                            </div>
                            <div class="col-6 mb-2">
                                {$LANG->getModule('date_get')}
                            </div>
                            <div class="col-6 mb-2" id="lbl_demo_date_get">
                            </div>
                            <div class="col-6">
                                {$LANG->getModule('date_post')}
                            </div>
                            <div class="col-6" id="lbl_demo_date_post">
                            </div>
                        </div>
                    </div>
                    <div data-toggle="preview" data-tab="time" id="preview-time"{$TAB neq 'time' ? ' class="d-none"' : ''}>
                        <div class="row">
                            <div class="col-4 mb-2">
                                {$LANG->getModule('region_display_short')}
                            </div>
                            <div class="col-8 mb-2" id="lbl_demo_time_short">
                            </div>
                            <div class="col-4">
                                {$LANG->getModule('region_display_long')}
                            </div>
                            <div class="col-8" id="lbl_demo_time_long">
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 border-top pt-3">
                        <p class="mb-2">{$LANG->getGlobal('required')}</p>
                        <p class="mb-0">
                            {$LANG->getModule('datetime_format_guide')} <a href="https://www.php.net/manual/en/datetime.format.php" target="_blank" rel="nofollow">{$LANG->getModule('datetime_format_guide1')}</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
