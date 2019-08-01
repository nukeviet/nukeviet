<form method="post" action="{$FORM_ACTION}" autocomplete="off">
    <div class="card card-table">
        <div class="card-header">
            <div class="card-subtitle">{$LANG->get('thumb_note')}</div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th style="width: 22.5%;">{$LANG->get('thumb_dir')}</th>
                            <th style="width: 22.5%;">{$LANG->get('thumb_type')}</th>
                            <th style="width: 22.5%;">{$LANG->get('thumb_width_height')}</th>
                            <th style="width: 22.5%;">{$LANG->get('thumb_quality')}</th>
                            <th style="width: 10%;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach from=$ARRAY_DIRS key=key item=value}
                        <tr>
                            <td>
                                <strong>{$value.dirname}</strong>
                            </td>
                            <td>
                                <select class="form-control form-control-sm" name="thumb_type[{$value.did}]">
                                    {for $tkey=$value.forid to 5}
                                    <option value="{$tkey}"{if $tkey eq $value.thumb_type} selected="selected"{/if}>{if $tkey eq 0}--{else}{$LANG->get("thumb_type_`$tkey`")}{/if}</option>
                                    {/for}
                                </select>
                            </td>
                            <td>
                                <div class="d-inline-flex align-items-center">
                                    <div class="flex-grow-1 flex-shrink-1">
                                        <input type="text" class="form-control form-control-sm" name="thumb_width[{$value.did}]" value="{$value.thumb_width}" maxlength="3">
                                    </div>
                                    <div class="px-1">x</div>
                                    <div class="flex-grow-1 flex-shrink-1">
                                        <input type="text" class="form-control form-control-sm" name="thumb_height[{$value.did}]" value="{$value.thumb_height}" maxlength="3">
                                    </div>
                                </div>
                            </td>
                            <td>
                                <input type="text" class="form-control form-control-sm" name="thumb_quality[{$value.did}]" value="{$value.thumb_quality}" maxlength="3">
                            </td>
                            <td>
                                <a data-toggle="thumbCfgViewEx" data-did="{$value.did}" data-errmsg="{$LANG->get('prViewExampleError')}" href="#" class="btn btn-secondary btn-input-sm"><span class="text-blackzz"><i class="fas fa-search"></i> {$LANG->get('prViewExample')}</span></a>
                            </td>
                        </tr>
                        {/foreach}
                        <tr>
                            <td>
                                <select class="form-control form-control-sm" name="other_dir">
                                    <option value="">--</option>
                                    {foreach from=$ARRAY_OTHER_DIRS key=key item=value}
                                    <option value="{$value.did}">{$value.dirname}</option>
                                    {/foreach}
                                </select>
                            </td>
                            <td>
                                <select class="form-control form-control-sm" name="other_type">
                                    <option value="0">--</option>
                                    {for $key=1 to 4}
                                    <option value="{$key}">{$LANG->get("thumb_type_`$key`")}</option>
                                    {/for}
                                </select>
                            </td>
                            <td>
                                <div class="d-inline-flex align-items-center">
                                    <div class="flex-grow-1 flex-shrink-1">
                                        <input type="text" class="form-control form-control-sm" name="other_thumb_width" value="200" maxlength="3">
                                    </div>
                                    <div class="px-1">x</div>
                                    <div class="flex-grow-1 flex-shrink-1">
                                        <input type="text" class="form-control form-control-sm" name="other_thumb_height" value="350" maxlength="3">
                                    </div>
                                </div>
                            </td>
                            <td>
                                <input type="text" class="form-control form-control-sm" name="other_thumb_quality" value="90" maxlength="3">
                            </td>
                            <td>
                                <a data-toggle="thumbCfgViewEx" data-did="-1" data-errmsg="{$LANG->get('prViewExampleError')}" href="#" class="btn btn-secondary btn-input-sm"><span class="text-blackzz"><i class="fas fa-search"></i> {$LANG->get('prViewExample')}</span></a>
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
<div id="thumbprewiew"></div>
<div id="thumbprewiewtmp" class="d-none">
    <div class="card card-border-color card-border-color-primary">
        <div class="card-header card-header-divider">
            {$LANG->get('prViewExample')}
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-6 text-center">
                    <h4>{$LANG->get('original_image')}</h4>
                    <img src="{$NV_BASE_SITEURL}{$NV_ASSETS_DIR}/images/pix.gif" class="img-fluid imgorg">
                </div>
                <div class="col-6 text-center">
                    <h4>{$LANG->get('thumb_image')}</h4>
                    <img src="{$NV_BASE_SITEURL}{$NV_ASSETS_DIR}/images/pix.gif" class="img-fluid imgthumb">
                </div>
            </div>
        </div>
    </div>
</div>
