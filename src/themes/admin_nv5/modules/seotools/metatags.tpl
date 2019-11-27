<form method="post" action="{$FORM_ACTION}" autocomplete="off">
    <div class="card card-table">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th style="width: 20%;" class="text-nowrap">{$LANG->get('metaTagsGroupName')}</th>
                            <th style="width: 30%;" class="text-nowrap">{$LANG->get('metaTagsGroupValue')} (*)</th>
                            <th style="width: 50%;" class="text-nowrap">{$LANG->get('metaTagsContent')} (**)</th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach from=$METAS item=row}
                        <tr>
                            <td>
                                <select class="form-control form-control-xs mw100" name="metaGroupsName[]">
                                    <option value="name"{if $row.group eq "name"} selected="selected"{/if}>name</option>
                                    <option value="property"{if $row.group eq "property"} selected="selected"{/if}>property</option>
                                    <option value="http-equiv"{if $row.group eq "http-equiv"} selected="selected"{/if}>http-equiv</option>
                                </select>
                            </td>
                            <td>
                                <input class="form-control-xs form-control" type="text" value="{$row.value}" name="metaGroupsValue[]">
                            </td>
                            <td>
                                <input class="form-control-xs form-control" type="text" value="{$row.content}" name="metaContents[]">
                            </td>
                        </tr>
                        {/foreach}
                        <tr>
                            <td colspan="2" class="text-right">
                                {$LANG->get('metaTagsOgp')} (***)
                            </td>
                            <td>
                                <label class="custom-control custom-checkbox custom-control-inline p-0 m-0">
                                    <input class="custom-control-input" type="checkbox" name="metaTagsOgp" value="1"{if $CONFIG['metaTagsOgp']} checked="checked"{/if}><span class="custom-control-label"></span>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="text-right">
                                {$LANG->get('private_site')}
                            </td>
                            <td>
                                <label class="custom-control custom-checkbox custom-control-inline p-0 m-0">
                                    <input class="custom-control-input" type="checkbox" name="private_site" value="1"{if $CONFIG['private_site']} checked="checked"{/if}><span class="custom-control-label"></span>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="text-right align-top">
                                <div class="pt-2">{$LANG->get('description_length')}</div>
                            </td>
                            <td class="align-top">
                                <input class="form-control form-control-sm" type="text" value="{$CONFIG['description_length']}" name="description_length" pattern="^[0-9]*$"  oninvalid="setCustomValidity(nv_digits);" oninput="setCustomValidity('')">
                                <span class="form-text text-muted">{$LANG->get('description_note')}</span>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2">&nbsp;</td>
                            <td>
                                <button class="btn btn-space btn-primary" type="submit" name="submit">{$LANG->get('submit')}</button>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</form>
<div role="alert" class="alert alert-primary alert-icon alert-icon-colored alert-dismissible">
    <div class="message">
        **: {$NOTE}<br>
        **: {$VARS}<br>
        ***: {$LANG->get('metaTagsOgpNote')}
    </div>
</div>
