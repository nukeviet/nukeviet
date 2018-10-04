{if isset($ERROR_WRITE_FILE)}
<div role="alert" class="alert alert-danger alert-dismissible">
    <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
    <div class="icon"><i class="far fa-times-circle"></i></div>
    <div class="message">{$LANG->get('robots_error_writable')}</div>
</div>
<pre class="mb-3"><code>{$ERROR_WRITE_FILE}</code></pre>
{/if}
<form method="post" action="{$FORM_ACTION}" autocomplete="off">
    <div class="card card-table">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th style="width: 10%;" class="text-nowrap">{$LANG->get('robots_number')}</th>
                            <th style="width: 45%;" class="text-nowrap">{$LANG->get('robots_filename')}</th>
                            <th style="width: 45%;" class="text-nowrap">{$LANG->get('robots_type')}</th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach from=$FILES item=file}
                        <tr>
                            <td>{$file.number}</td>
                            <td>
                                {if $file.isother}
                                <input class="form-control form-control-xs" type="text" value="{$file.filename}" name="fileother[{$file.number}]" />
                                {else}
                                {$file.filename}
                                {/if}
                            </td>
                            <td>
                                {if $file.isother}
                                <select name="optionother[{$file.number}]" class="form-control form-control-xs">
                                    {for $key=0 to 2}
                                    <option value="{$key}"{if $key eq $file.type} selected="selected"{/if}>{$LANG->get("robots_type_`$key`")}</option>
                                    {/for}
                                </select>
                                {else}
                                <select name="filename[{$file.filename}]" class="form-control form-control-xs">
                                    {for $key=0 to 2}
                                    <option value="{$key}"{if $key eq $file.type} selected="selected"{/if}>{$LANG->get("robots_type_`$key`")}</option>
                                    {/for}
                                </select>
                                {/if}
                            </td>
                        </tr>
                        {/foreach}
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-center">
                                <button class="btn btn-space btn-primary" type="submit" name="submit">{$LANG->get('submit')}</button>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</form>
