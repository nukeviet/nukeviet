<form method="post" action="{$FORM_ACTION}" autocomplete="off">
    <div class="card card-border-color card-border-color-primary">
        <div class="card-header card-header-divider mx-0 px-3 mb-0">
            <div class="d-flex align-items-center flex-wrap">
                <div class="flex-grow-0 mr-4">
                    <label class="custom-control custom-control-sm custom-checkbox m-0 p-0">
                        <input class="custom-control-input" type="checkbox" name="check_all[]" value="yes" onclick="nv_checkAll(this.form, 'deltype[]', 'check_all[]', this.checked);"><span class="custom-control-label"></span>
                    </label>
                </div>
                <div class="flex-grow-1">
                    {$LANG->get('checkContent')}
                </div>
            </div>
        </div>
        <div class="list-group list-group-flush">
            <div class="list-group-item">
                <div class="d-flex align-items-center flex-wrap">
                    <div class="flex-grow-0 mr-4">
                        <label class="custom-control custom-control-sm custom-checkbox m-0 p-0">
                            <input class="custom-control-input" type="checkbox" name="deltype[]" value="clearcache" onclick="nv_UncheckAll(this.form, 'deltype[]', 'check_all[]', this.checked);"><span class="custom-control-label"></span>
                        </label>
                    </div>
                    <div class="flex-grow-1">
                        {$LANG->get('clearcache')}
                    </div>
                </div>
            </div>
            {if $IS_GOD_ADMIN}
            <div class="list-group-item">
                <div class="d-flex align-items-center flex-wrap">
                    <div class="flex-grow-0 mr-4">
                        <label class="custom-control custom-control-sm custom-checkbox m-0 p-0">
                            <input class="custom-control-input" type="checkbox" name="deltype[]" value="clearfiletemp" onclick="nv_UncheckAll(this.form, 'deltype[]', 'check_all[]', this.checked);"><span class="custom-control-label"></span>
                        </label>
                    </div>
                    <div class="flex-grow-1">
                        {$LANG->get('clearfiletemp')}
                    </div>
                </div>
            </div>
            <div class="list-group-item">
                <div class="d-flex align-items-center flex-wrap">
                    <div class="flex-grow-0 mr-4">
                        <label class="custom-control custom-control-sm custom-checkbox m-0 p-0">
                            <input class="custom-control-input" type="checkbox" name="deltype[]" value="clearerrorlogs" onclick="nv_UncheckAll(this.form, 'deltype[]', 'check_all[]', this.checked);"><span class="custom-control-label"></span>
                        </label>
                    </div>
                    <div class="flex-grow-1">
                        {$LANG->get('clearerrorlogs')}
                    </div>
                </div>
            </div>
            <div class="list-group-item">
                <div class="d-flex align-items-center flex-wrap">
                    <div class="flex-grow-0 mr-4">
                        <label class="custom-control custom-control-sm custom-checkbox m-0 p-0">
                            <input class="custom-control-input" type="checkbox" name="deltype[]" value="clearip_logs" onclick="nv_UncheckAll(this.form, 'deltype[]', 'check_all[]', this.checked);"><span class="custom-control-label"></span>
                        </label>
                    </div>
                    <div class="flex-grow-1">
                        {$LANG->get('clearip_logs')}
                    </div>
                </div>
            </div>
            {/if}
        </div>
        <div class="card-footer text-center">
            <input type="submit" name="submit" value="{$LANG->get('submit')}" class="btn btn-primary"/>
        </div>
    </div>
</form>
{if not empty($FILES)}
<h3>{$LANG->get('deletedetail')}</h3>
<pre><code>{foreach from=$FILES item=file}{$file}<br />{/foreach}</code></pre>
{/if}
