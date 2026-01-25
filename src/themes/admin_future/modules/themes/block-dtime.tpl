<div class="row mb-3">
    <div class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium" title="{$LANG->getModule('dtime_details')}">{$LANG->getModule('dtime_details')}:</div>
    <div class="col-sm-9">
        {if $DTIME_TYPE eq 'specific'}
        <div class="row g-2 dtime">
            {for $key=0 to ($CFG_LINE - 1)}
            <div class="col-12 dtime_details">
                <div class="row g-1 flex-sm-nowrap align-items-center">
                    <div class="col-auto flex-shrink-1">
                        <input type="text" name="start_date[]" class="form-control date" value="{if isset($DETAILS[$key])}{$DETAILS[$key].start_date}{/if}" maxlength="4">
                    </div>
                    <div class="col-auto">
                        <select name="start_h[]" class="form-select fw-hm">
                            {for $hour=0 to 23}
                            <option value="{$hour}"{if isset($DETAILS[$key], $DETAILS[$key].start_h) and $DETAILS[$key].start_h eq $hour} selected{/if}>{$hour|str_pad:2:'0':STR_PAD_LEFT}</option>
                            {/for}
                        </select>
                    </div>
                    <div class="col-auto">
                        <select name="start_i[]" class="form-select fw-hm">
                            {for $min=0 to 59}
                            <option value="{$min}"{if isset($DETAILS[$key], $DETAILS[$key].start_i) and $DETAILS[$key].start_i eq $min} selected{/if}>{$min|str_pad:2:'0':STR_PAD_LEFT}</option>
                            {/for}
                        </select>
                    </div>
                    <div class="col-auto">-</div>
                    <div class="col-auto flex-shrink-1">
                        <input type="text" name="end_date[]" class="form-control date" value="{if isset($DETAILS[$key])}{$DETAILS[$key].end_date}{/if}" maxlength="4">
                    </div>
                    <div class="col-auto">
                        <select name="end_h[]" class="form-select fw-hm">
                            {for $hour=0 to 23}
                            <option value="{$hour}"{if isset($DETAILS[$key], $DETAILS[$key].end_h) and $DETAILS[$key].end_h eq $hour} selected{/if}>{$hour|str_pad:2:'0':STR_PAD_LEFT}</option>
                            {/for}
                        </select>
                    </div>
                    <div class="col-auto">
                        <select name="end_i[]" class="form-select fw-hm">
                            {for $min=0 to 59}
                            <option value="{$min}"{if isset($DETAILS[$key], $DETAILS[$key].end_i) and $DETAILS[$key].end_i eq $min} selected{/if}>{$min|str_pad:2:'0':STR_PAD_LEFT}</option>
                            {/for}
                        </select>
                    </div>
                    <div class="col-auto">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-secondary del_dtime" aria-label="{$LANG->getModule('add')}">-</button>
                            <button type="button" class="btn btn-secondary add_dtime" aria-label="{$LANG->getModule('delete')}">+</button>
                        </div>
                    </div>
                </div>
            </div>
            {/for}
        </div>
        <div class="form-text">{$LANG->getModule('dtime_type_specific_note')}</div>
        {elseif $DTIME_TYPE eq 'daily'}
        <div class="row g-2">
            {for $key=0 to ($CFG_LINE - 1)}
            <div class="col-12">
                <div class="row g-1 flex-sm-nowrap align-items-center">
                    <div class="col-auto">
                        <select name="start_h[]" class="form-select fw-hm">
                            {for $hour=0 to 23}
                            <option value="{$hour}"{if isset($DETAILS[$key], $DETAILS[$key].start_h) and $DETAILS[$key].start_h eq $hour} selected{/if}>{$hour|str_pad:2:'0':STR_PAD_LEFT}</option>
                            {/for}
                        </select>
                    </div>
                    <div class="col-auto">
                        <select name="start_i[]" class="form-select fw-hm">
                            {for $min=0 to 59}
                            <option value="{$min}"{if isset($DETAILS[$key], $DETAILS[$key].start_i) and $DETAILS[$key].start_i eq $min} selected{/if}>{$min|str_pad:2:'0':STR_PAD_LEFT}</option>
                            {/for}
                        </select>
                    </div>
                    <div class="col-auto">-</div>
                    <div class="col-auto">
                        <select name="end_h[]" class="form-select fw-hm">
                            {for $hour=0 to 23}
                            <option value="{$hour}"{if isset($DETAILS[$key], $DETAILS[$key].end_h) and $DETAILS[$key].end_h eq $hour} selected{/if}>{$hour|str_pad:2:'0':STR_PAD_LEFT}</option>
                            {/for}
                        </select>
                    </div>
                    <div class="col-auto">
                        <select name="end_i[]" class="form-select fw-hm">
                            {for $min=0 to 59}
                            <option value="{$min}"{if isset($DETAILS[$key], $DETAILS[$key].end_i) and $DETAILS[$key].end_i eq $min} selected{/if}>{$min|str_pad:2:'0':STR_PAD_LEFT}</option>
                            {/for}
                        </select>
                    </div>
                    <div class="col-auto">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-secondary del_dtime" aria-label="{$LANG->getModule('add')}">-</button>
                            <button type="button" class="btn btn-secondary add_dtime" aria-label="{$LANG->getModule('delete')}">+</button>
                        </div>
                    </div>
                </div>
            </div>
            {/for}
        </div>
        <div class="form-text">{$LANG->getModule('dtime_type_daily_note')}</div>
        {elseif $DTIME_TYPE eq 'weekly'}
        <div class="row g-2">
            {for $key=0 to ($CFG_LINE - 1)}
            <div class="col-12">
                <div class="row g-1 flex-sm-nowrap align-items-center">
                    <div class="col-auto">
                        <select name="day_of_week[]" class="form-select fw-75">
                            {for $day=1 to 7}
                            <option value="{$day}"{if isset($DETAILS[$key], $DETAILS[$key].day_of_week) and $DETAILS[$key].day_of_week eq $day} selected{/if}>{$LANG->getModule("day_of_week_`$day`")}</option>
                            {/for}
                        </select>
                    </div>
                    <div class="col-auto">:</div>
                    <div class="col-auto">
                        <select name="start_h[]" class="form-select fw-hm">
                            {for $hour=0 to 23}
                            <option value="{$hour}"{if isset($DETAILS[$key], $DETAILS[$key].start_h) and $DETAILS[$key].start_h eq $hour} selected{/if}>{$hour|str_pad:2:'0':STR_PAD_LEFT}</option>
                            {/for}
                        </select>
                    </div>
                    <div class="col-auto">
                        <select name="start_i[]" class="form-select fw-hm">
                            {for $min=0 to 59}
                            <option value="{$min}"{if isset($DETAILS[$key], $DETAILS[$key].start_i) and $DETAILS[$key].start_i eq $min} selected{/if}>{$min|str_pad:2:'0':STR_PAD_LEFT}</option>
                            {/for}
                        </select>
                    </div>
                    <div class="col-auto">-</div>
                    <div class="col-auto">
                        <select name="end_h[]" class="form-select fw-hm">
                            {for $hour=0 to 23}
                            <option value="{$hour}"{if isset($DETAILS[$key], $DETAILS[$key].end_h) and $DETAILS[$key].end_h eq $hour} selected{/if}>{$hour|str_pad:2:'0':STR_PAD_LEFT}</option>
                            {/for}
                        </select>
                    </div>
                    <div class="col-auto">
                        <select name="end_i[]" class="form-select fw-hm">
                            {for $min=0 to 59}
                            <option value="{$min}"{if isset($DETAILS[$key], $DETAILS[$key].end_i) and $DETAILS[$key].end_i eq $min} selected{/if}>{$min|str_pad:2:'0':STR_PAD_LEFT}</option>
                            {/for}
                        </select>
                    </div>
                    <div class="col-auto">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-secondary del_dtime" aria-label="{$LANG->getModule('add')}">-</button>
                            <button type="button" class="btn btn-secondary add_dtime" aria-label="{$LANG->getModule('delete')}">+</button>
                        </div>
                    </div>
                </div>
            </div>
            {/for}
        </div>
        <div class="form-text">{$LANG->getModule('dtime_type_weekly_note')}</div>
        {elseif $DTIME_TYPE eq 'monthly'}
        <div class="row g-2">
            {for $key=0 to ($CFG_LINE - 1)}
            <div class="col-12">
                <div class="row g-1 flex-sm-nowrap align-items-center">
                    <div class="col-auto">
                        <select name="day[]" class="form-select fw-hm">
                            {for $day=1 to 31}
                            <option value="{$day}"{if isset($DETAILS[$key], $DETAILS[$key].day) and $DETAILS[$key].day eq $day} selected{/if}>{$day}</option>
                            {/for}
                        </select>
                    </div>
                    <div class="col-auto">:</div>
                    <div class="col-auto">
                        <select name="start_h[]" class="form-select fw-hm">
                            {for $hour=0 to 23}
                            <option value="{$hour}"{if isset($DETAILS[$key], $DETAILS[$key].start_h) and $DETAILS[$key].start_h eq $hour} selected{/if}>{$hour|str_pad:2:'0':STR_PAD_LEFT}</option>
                            {/for}
                        </select>
                    </div>
                    <div class="col-auto">
                        <select name="start_i[]" class="form-select fw-hm">
                            {for $min=0 to 59}
                            <option value="{$min}"{if isset($DETAILS[$key], $DETAILS[$key].start_i) and $DETAILS[$key].start_i eq $min} selected{/if}>{$min|str_pad:2:'0':STR_PAD_LEFT}</option>
                            {/for}
                        </select>
                    </div>
                    <div class="col-auto">-</div>
                    <div class="col-auto">
                        <select name="end_h[]" class="form-select fw-hm">
                            {for $hour=0 to 23}
                            <option value="{$hour}"{if isset($DETAILS[$key], $DETAILS[$key].end_h) and $DETAILS[$key].end_h eq $hour} selected{/if}>{$hour|str_pad:2:'0':STR_PAD_LEFT}</option>
                            {/for}
                        </select>
                    </div>
                    <div class="col-auto">
                        <select name="end_i[]" class="form-select fw-hm">
                            {for $min=0 to 59}
                            <option value="{$min}"{if isset($DETAILS[$key], $DETAILS[$key].end_i) and $DETAILS[$key].end_i eq $min} selected{/if}>{$min|str_pad:2:'0':STR_PAD_LEFT}</option>
                            {/for}
                        </select>
                    </div>
                    <div class="col-auto">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-secondary del_dtime" aria-label="{$LANG->getModule('add')}">-</button>
                            <button type="button" class="btn btn-secondary add_dtime" aria-label="{$LANG->getModule('delete')}">+</button>
                        </div>
                    </div>
                </div>
            </div>
            {/for}
        </div>
        <div class="form-text">{$LANG->getModule('dtime_type_monthly_note')}</div>
        {elseif $DTIME_TYPE eq 'yearly'}
        <div class="row g-2">
            {for $key=0 to ($CFG_LINE - 1)}
            <div class="col-12">
                <div class="row g-1 flex-sm-nowrap align-items-center">
                    <div class="col-auto">
                        <select name="month[]" class="form-select fw-hm">
                            {for $month=1 to 12}
                            <option value="{$month}"{if isset($DETAILS[$key], $DETAILS[$key].month) and $DETAILS[$key].month eq $month} selected{/if}>{$month}</option>
                            {/for}
                        </select>
                    </div>
                    <div class="col-auto">
                        <select name="day[]" class="form-select fw-hm">
                            {for $day=1 to 31}
                            <option value="{$day}"{if isset($DETAILS[$key], $DETAILS[$key].day) and $DETAILS[$key].day eq $day} selected{/if}>{$day}</option>
                            {/for}
                        </select>
                    </div>
                    <div class="col-auto">:</div>
                    <div class="col-auto">
                        <select name="start_h[]" class="form-select fw-hm">
                            {for $hour=0 to 23}
                            <option value="{$hour}"{if isset($DETAILS[$key], $DETAILS[$key].start_h) and $DETAILS[$key].start_h eq $hour} selected{/if}>{$hour|str_pad:2:'0':STR_PAD_LEFT}</option>
                            {/for}
                        </select>
                    </div>
                    <div class="col-auto">
                        <select name="start_i[]" class="form-select fw-hm">
                            {for $min=0 to 59}
                            <option value="{$min}"{if isset($DETAILS[$key], $DETAILS[$key].start_i) and $DETAILS[$key].start_i eq $min} selected{/if}>{$min|str_pad:2:'0':STR_PAD_LEFT}</option>
                            {/for}
                        </select>
                    </div>
                    <div class="col-auto">-</div>
                    <div class="col-auto">
                        <select name="end_h[]" class="form-select fw-hm">
                            {for $hour=0 to 23}
                            <option value="{$hour}"{if isset($DETAILS[$key], $DETAILS[$key].end_h) and $DETAILS[$key].end_h eq $hour} selected{/if}>{$hour|str_pad:2:'0':STR_PAD_LEFT}</option>
                            {/for}
                        </select>
                    </div>
                    <div class="col-auto">
                        <select name="end_i[]" class="form-select fw-hm">
                            {for $min=0 to 59}
                            <option value="{$min}"{if isset($DETAILS[$key], $DETAILS[$key].end_i) and $DETAILS[$key].end_i eq $min} selected{/if}>{$min|str_pad:2:'0':STR_PAD_LEFT}</option>
                            {/for}
                        </select>
                    </div>
                    <div class="col-auto">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-secondary del_dtime" aria-label="{$LANG->getModule('add')}">-</button>
                            <button type="button" class="btn btn-secondary add_dtime" aria-label="{$LANG->getModule('delete')}">+</button>
                        </div>
                    </div>
                </div>
            </div>
            {/for}
        </div>
        <div class="form-text">{$LANG->getModule('dtime_type_yearly_note')}</div>
        {/if}
    </div>
</div>
