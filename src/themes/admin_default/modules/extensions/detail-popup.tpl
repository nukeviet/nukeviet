<div class="ext-detail-container">
    <div class="px-4 tab-header">
        <ul class="nav nav-tabs nav-justified" id="tab-ext-detail">
            <li class="nav-item">
                <a class="nav-link text-truncate active" id="link-info" data-bs-toggle="tab" data-bs-target="#tab-info" aria-current="true" role="tab" aria-controls="tab-info" aria-selected="true" href="#">{$LANG->getModule('tab_info')}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-truncate" id="link-guide" data-bs-toggle="tab" data-bs-target="#tab-guide" aria-current="false" role="tab" aria-controls="tab-guide" aria-selected="false" href="#">{$LANG->getModule('tab_guide')}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-truncate" id="link-images" data-bs-toggle="tab" data-bs-target="#tab-images" aria-current="false" role="tab" aria-controls="tab-images" aria-selected="false" href="#">{$LANG->getModule('tab_images')}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-truncate" id="link-files" data-bs-toggle="tab" data-bs-target="#tab-files" aria-current="false" role="tab" aria-controls="tab-files" aria-selected="false" href="#">{$LANG->getModule('tab_files')}</a>
            </li>
        </ul>
    </div>
    <div class="tab-content">
        <div class="tab-pane show active" id="tab-info" role="tabpanel" aria-labelledby="link-info" tabindex="0">
            <div class="px-4 pt-3">
                <div class="row g-3">
                    <div class="col-lg-8 order-2 order-lg-1">
                        {$DATA.description}
                    </div>
                    <div class="col-lg-4 order-1 order-lg-2">
                        {if not empty($DATA.compatible) and ($GCONFIG.extension_setup eq 2 or $GCONFIG.extension_setup eq 3)}
                        <div class="d-grid mb-2">
                            <a href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=install&amp;id={$DATA.id}" class="btn btn-primary btn-lg btn-block">{$LANG->getModule('install')}</a>
                        </div>
                        {/if}
                        <ul class="list-group">
                            <li class="list-group-item">
                                <div class="fw-bold text-{empty($DATA.compatible) ? 'danger' : 'success'}">
                                    {$LANG->getModule(empty($DATA.compatible) ? 'incompatible' : 'compatible')}
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="row g-2">
                                    <div class="col-6 fw-medium">{$LANG->getModule('newest_version')}</div>
                                    <div class="col-6">{$DATA.newest_version}</div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="row g-2">
                                    <div class="col-6 fw-medium">{$LANG->getModule('updatetime')}</div>
                                    <div class="col-6">{$DATA.updatetime|ddatetime:1}</div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="row g-2">
                                    <div class="col-6 fw-medium">{$LANG->getModule('view_hits')}</div>
                                    <div class="col-6">{$DATA.view_hits|dnumber}</div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="row g-2">
                                    <div class="col-6 fw-medium">{$LANG->getModule('download_hits')}</div>
                                    <div class="col-6">{$DATA.download_hits|dnumber}</div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="row g-2">
                                    <div class="col-6 fw-medium">{$LANG->getModule('rating_text')}</div>
                                    <div class="col-6">{$LANG->getModule('rating_text_detail', $DATA.rating_totals|dnumber, $DATA.rating_hits|dnumber)}</div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="row g-2">
                                    <div class="col-6 fw-medium">{$LANG->getModule('license')}</div>
                                    <div class="col-6">{$DATA.license}</div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="row g-2">
                                    <div class="col-6 fw-medium">{$LANG->getModule('author')}</div>
                                    <div class="col-6">{$DATA.username}</div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="row g-2">
                                    <div class="col-6 fw-medium">{$LANG->getModule('ext_type')}</div>
                                    <div class="col-6">{$DATA.types}</div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="row g-2">
                                    <div class="col-6 fw-medium">{$LANG->getModule('price')}</div>
                                    <div class="col-6">
                                        {if empty($DATA.price)}{$LANG->getModule('free')}{else}
                                        {if $DATA.currency eq 'VND'}
                                        {$DATA.price|dcurrency:'vi'}
                                        {else}
                                        {$DATA.price|dcurrency:'en'}
                                        {/if}
                                        {/if}
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="tab-guide" role="tabpanel" aria-labelledby="link-guide" tabindex="0">
            <div class="px-4 pt-3">
                {if empty($DATA.documentation)}
                <div class="alert alert-warning" role="alert">{$LANG->getModule('detail_empty_documentation')}</div>
                {else}
                {$DATA.documentation}
                {/if}

            </div>
        </div>
        <div class="tab-pane" id="tab-images" role="tabpanel" aria-labelledby="link-images" tabindex="0">
            <div class="px-4 pt-3">
                {if empty($DATA.image_demo)}
                <div class="alert alert-warning" role="alert">{$LANG->getModule('detail_empty_images')}</div>
                {else}
                <div class="row g-3">
                    {foreach from=$DATA.image_demo item=image}
                    <div class="col-6 col-sm-4 col-lg-3 col-xxl-2">
                        <a href="{$image}" target="_blank"><img alt="{$DATA.title}" src="{$image}" class="img-fluid"></a>
                    </div>
                    {/foreach}
                </div>
                {/if}
            </div>
        </div>
        <div class="tab-pane" id="tab-files" role="tabpanel" aria-labelledby="link-files" tabindex="0">
            <div class="px-4 pt-3">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th class="text-nowrap" style="width: 40%;">{$LANG->getModule('file_name')}</th>
                                <th class="text-nowrap" style="width: 15%;">{$LANG->getModule('file_version')}</th>
                                <th class="text-nowrap" style="width: 15%;">{$LANG->getModule('price')}</th>
                                <th class="text-nowrap" style="width: 15%;">{$LANG->getModule('compatible')}</th>
                                <th class="text-nowrap text-center" style="width: 15%;">{$LANG->getGlobal('function')}</th>
                            </tr>
                        </thead>
                        <tbody>
                            {foreach from=$DATA.files item=file}
                            <tr>
                                <td>{$file.title}</td>
                                <td>{$file.ver}</td>
                                <td>
                                    {if empty($file.price)}{$LANG->getModule('free')}{else}
                                    {if $file.currency eq 'VND'}
                                    {$file.price|dcurrency:'vi'}
                                    {else}
                                    {$file.price|dcurrency:'en'}
                                    {/if}
                                    {/if}
                                </td>
                                <td>
                                    <div class="fw-bold text-{empty($file.compatible) ? 'danger' : 'success'}">
                                        {$LANG->getModule(empty($file.compatible) ? 'incompatible' : 'compatible')}
                                    </div>
                                </td>
                                <td class="text-center">
                                    {if $file.type eq 1 and not empty($file.compatible) and ($GCONFIG.extension_setup eq 2 or $GCONFIG.extension_setup eq 3)}
                                    <a href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=install&amp;id={$DATA.id}&amp;fid={$file.id}" class="btn btn-primary btn-sm text-nowrap" title="{$LANG->getModule('install_note')}" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-title="{$LANG->getModule('install_note')}"><i class="fa-solid fa-cloud-arrow-down"></i> {$LANG->getModule('install')}</a>
                                    {else}
                                    <a href="{$file.origin_link}" class="btn btn-primary btn-sm text-nowrap" target="_blank" title="{$LANG->getModule('download_note')}" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-title="{$LANG->getModule('download_note')}"><i class="fa-solid fa-file-export"></i> {$LANG->getModule('download')}</a>
                                    {/if}
                                </td>
                            </tr>
                            {/foreach}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
