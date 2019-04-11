<div class="card card-table">
    <div class="card-body">
        <div class="row card-body-search-form">
            <div class="col-12 col-lg-12 col-xl-6 mb-lg-2 mb-xl-0">
                {if $UPLOAD_ALLOWED}
                <div class="btn-group">
                    <button type="button" data-toggle="dropdown" class="btn btn-primary dropdown-toggle">{$LANG->get('install_package')} <span class="icon-dropdown fas fa-chevron-down"></span></button>
                    <div role="menu" class="dropdown-menu keep-open">
                        <div class="px-2 mw300">
                            {if $ZLIB_SUPPORT}
                            <form onsubmit="return checkform();" class="form-inline" method="post" enctype="multipart/form-data" action="{$SUBMIT_URL}">
                                <input class="inputfile" id="extfile" type="file" name="extfile" data-multiple-caption="[count] files selected" data-lang-nofile="{$LANG->get('install_error_nofile')}" data-lang-filetype="{$LANG->get('install_error_filetype')}">
                                <label class="btn-secondary mxw210 text-truncate" for="extfile"> <i class="icon fas fa-upload"></i><span>{$LANG->get('browse_file')}...</span></label>
                                <input type="submit" name="submit" class="btn btn-primary ml-2" value="{$LANG->get('install_submit')}">
                            </form>
                            {else}
                            <span class="text-danger"><strong>{$LANG->get('error_zlib_support')}</strong></span>
                            {/if}
                        </div>
                    </div>
                </div>
                {/if}
            </div>
            <div class="col-12 col-lg-12 col-xl-6 search-form-right">
                <form action="{$NV_BASE_ADMINURL}index.php" method="get" class="form-inline" id="exttypeFilter">
                    <input type="hidden" name="{$NV_LANG_VARIABLE}" value="{$NV_LANG_DATA}">
                    <input type="hidden" name="{$NV_NAME_VARIABLE}" value="{$MODULE_NAME}">
                    <input type="hidden" name="{$NV_OP_VARIABLE}" value="{$OP}">
                    <label>{$LANG->get('extType_filter')}: </label>
                    <select class="form-control form-control-xs" name="selecttype">
                        <option value="">{$LANG->get('extType_all')}</option>
                        {foreach from=$ARRAY_EXTTYPE item=exttype}
                        <option value="{$exttype}"{if $SELECTTYPE eq $exttype} selected="selected"{/if}>{$LANG->get("extType_`$exttype`")}</option>
                        {/foreach}
                    </select>
                </form>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th style="width: 20%;" class="text-nowrap">{$LANG->get('ext_type')}</th>
                        <th style="width: 20%;" class="text-nowrap">{$LANG->get('extname')}</th>
                        <th style="width: 20%;" class="text-nowrap">{$LANG->get('file_version')}</th>
                        <th style="width: 40%;" class="text-nowrap">{$LANG->get('author')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$ARRAY item=row}
                    <tr>
                        <td class="text-nowrap">{$row.type}</td>
                        <td>
                            {$row.basename}
                            {if not empty($row.icon)}
                            <div class="float-right text-right">
                                {foreach from=$row.icon item=icon}
                                <i class="fa {$icon} ml-1"></i>
                                {/foreach}
                            </div>
                            {/if}
                        </td>
                        <td class="text-nowrap">{$row.version}</td>
                        <td>
                            {$row.author|htmlspecialchars}
                            <div class="float-right text-right">
                                <a href="{$row.url_package}" data-toggle="tooltip" data-placement="top" title="{$LANG->get('package')}" data-boundary="window"><i class="fa fa-cloud-download-alt"></i></a>
                                {if $row.delete_allowed}
                                <a href="{$row.url_delete}" class="delete-ext text-danger ml-1" data-toggle="tooltip" data-placement="top" title="{$LANG->get('delete')}" data-boundary="window" data-lang-confirm="{$LANG->get('delele_ext_confirm')}"><i class="fa fa-trash-alt"></i></a>
                                {/if}
                            </div>
                        </td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="mb-3 clearfix">
    <div class="float-right">
        <span class="mr-2"><i class="fa {$THEME_CONFIG.sys_icon}"></i> {$LANG->get('extType_sys')}</span>
        <i class="fa {$THEME_CONFIG.admin_icon}"></i> {$LANG->get('extType_admin')}
    </div>
</div>
<script>
$(document).ready(function() {
    $('[name="selecttype"]').on('change', function() {
        $('#exttypeFilter').submit();
    });
    $('.dropdown-menu.keep-open').on('click', function(e) {
        e.stopPropagation();
    });
    $(".inputfile").each(function() {
        var e = $(this);
        var t = e.next("label");
        var i = t.html();
        e.on("change", function(e) {
            var a = "";
            if (this.files && this.files.length > 1) {
                a = (this.getAttribute("data-multiple-caption") || "").replace("[count]", this.files.length);
            } else if (e.target.value) {
                a = e.target.value.split('\\\').pop();
            }
            if (a) {
                t.find("span").html(a);
            } else {
                t.html(i);
            }
        });
    });
});
</script>
