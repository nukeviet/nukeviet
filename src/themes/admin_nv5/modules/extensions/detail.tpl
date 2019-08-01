{if not empty($ERROR)}
<div class="card-body pt-4">
    <div role="alert" class="alert alert-danger alert-dismissible">
        <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
        <div class="icon"><i class="far fa-times-circle"></i></div>
        <div class="message">{$ERROR}</div>
    </div>
</div>
{else}
<div class="tab-container">
    <ul role="tablist" class="nav nav-tabs">
        <li class="nav-item"><a href="#extDetailInfo" data-toggle="tab" role="tab" class="nav-link active show" aria-selected="true">{$LANG->get('tab_info')}</a></li>
        <li class="nav-item"><a href="#extDetailGuide" data-toggle="tab" role="tab" class="nav-link" aria-selected="false">{$LANG->get('tab_guide')}</a></li>
        <li class="nav-item"><a href="#extDetailImages" data-toggle="tab" role="tab" class="nav-link" aria-selected="false">{$LANG->get('tab_images')}</a></li>
        <li class="nav-item"><a href="#extDetailFiles" data-toggle="tab" role="tab" class="nav-link" aria-selected="false">{$LANG->get('tab_files')}</a></li>
    </ul>
    <div class="tab-content">
        <div id="extDetailInfo" role="tabpanel" class="tab-pane active show">
            <div class="row">
                <div class="col-12 col-sm-12 col-md-8 col-lg-8 col-xl-9 order-sm-2 order-md-1">
                    <div class="ext-detail-bodyhtml mt-3 mt-md-0">
                        {$DATA.description}
                    </div>
                </div>
                <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-3 order-sm-1 order-md-2">
                    {if not empty($DATA.compatible) and $ALLOW_INSTALL}
                    <a href="{$DATA.install_link}" class="w-100 btn btn-success btn-lg mb-3" role="button">{$LANG->get('install')}</a>
                    {/if}
                    <ul class="list-group">
                        <li class="list-group-item p-2">
                            <strong class="{$DATA.compatible_class}">{$DATA.compatible_title}</strong>
                        </li>
                        <li class="list-group-item p-2">
                            <div class="row">
                                <div class="col-5">{$LANG->get('newest_version')}</div>
                                <div class="col-7"><strong>{$DATA.newest_version}</strong></div>
                            </div>
                        </li>
                        <li class="list-group-item p-2">
                            <div class="row">
                                <div class="col-5">{$LANG->get('updatetime')}</div>
                                <div class="col-7"><strong>{$DATA.updatetime}</strong></div>
                            </div>
                        </li>
                        <li class="list-group-item p-2">
                            <div class="row">
                                <div class="col-5">{$LANG->get('view_hits')}</div>
                                <div class="col-7"><strong>{$DATA.view_hits}</strong></div>
                            </div>
                        </li>
                        <li class="list-group-item p-2">
                            <div class="row">
                                <div class="col-5">{$LANG->get('download_hits')}</div>
                                <div class="col-7"><strong>{$DATA.download_hits}</strong></div>
                            </div>
                        </li>
                        <li class="list-group-item p-2">
                            <div class="row">
                                <div class="col-5">{$LANG->get('rating_text')}</div>
                                <div class="col-7"><strong>{$DATA.rating_text}</strong></div>
                            </div>
                        </li>
                        <li class="list-group-item p-2">
                            <div class="row">
                                <div class="col-5">{$LANG->get('license')}</div>
                                <div class="col-7"><strong>{$DATA.license}</strong></div>
                            </div>
                        </li>
                        <li class="list-group-item p-2">
                            <div class="row">
                                <div class="col-5">{$LANG->get('author')}</div>
                                <div class="col-7"><strong>{$DATA.username}</strong></div>
                            </div>
                        </li>
                        <li class="list-group-item p-2">
                            <div class="row">
                                <div class="col-5">{$LANG->get('ext_type')}</div>
                                <div class="col-7"><strong>{$DATA.types}</strong></div>
                            </div>
                        </li>
                        <li class="list-group-item p-2">
                            <div class="row">
                                <div class="col-5">{$LANG->get('price')}</div>
                                <div class="col-7"><strong>{$DATA.price}</strong></div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div id="extDetailGuide" role="tabpanel" class="tab-pane">
            {if empty($DATA.documentation)}
            <div role="alert" class="alert alert-primary alert-dismissible">
                <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
                <div class="icon"><i class="fas fa-info-circle"></i></div>
                <div class="message">{$LANG->get('detail_empty_documentation')}</div>
            </div>
            {/if}
            <div class="ext-detail-bodyhtml">
                {$DATA.documentation}
            </div>
        </div>
        <div id="extDetailImages" role="tabpanel" class="tab-pane">
            {if empty($ARRAY_IMAGES)}
            <div role="alert" class="alert alert-primary alert-dismissible">
                <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
                <div class="icon"><i class="fas fa-info-circle"></i></div>
                <div class="message">{$LANG->get('detail_empty_images')}</div>
            </div>
            {else}
            <div class="row">
                {foreach from=$ARRAY_IMAGES item=image}
                <div class="col-6 col-md-3">
                    <a href="{$image}" target="_blank"><img src="{$image}" class="img-fluid mb-3" alt=""></a>
                </div>
                {/foreach}
            </div>
            {/if}
        </div>
        <div id="extDetailFiles" role="tabpanel" class="tab-pane">
            {foreach from=$ARRAY_FILES item=file}
            <div class="ext-file mb-3">
                <div class="mb-2 clearfix">
                    <h4 class="my-0 mr-2 float-left">{$file.title}</h4>
                    {if $file.type eq 1 and not empty($file.compatible) and $ALLOW_INSTALL}
                    <a href="{$file.install_link}" class="btn btn-primary btn-sm ext-install float-right" title="{$LANG->get('install_note')}" data-toggle="tooltip" data-placement="top" data-boundary="window">{$LANG->get('install')}</a>
                    {else}
                    <a href="{$file.origin_link}" class="btn btn-primary btn-sm float-right" target="_blank" title="{$LANG->get('download_note')}" data-toggle="tooltip" data-placement="top" data-boundary="window">{$LANG->get('download')}</a>
                    {/if}
                </div>
                <ul class="list-group">
                    <li class="list-group-item p-2">
                        <div class="ext-file-item clearfix">
                            <span class="mr-2">{$LANG->get('file_version')}:</span>
                            <strong class="float-right">{$file.ver}</strong>
                        </div>
                        <hr class="mt-2 mb-2">
                        <div class="ext-file-item clearfix">
                            <span class="mr-2">{$LANG->get('compatible')}:</span>
                            <strong class="float-right {$file.compatible_class}">{$file.compatible_title}</strong>
                        </div>
                        <hr class="mt-2 mb-2">
                        <div class="ext-file-item clearfix">
                            <span class="mr-2">{$LANG->get('price')}:</span>
                            <strong class="float-right">{$file.price}</strong>
                        </div>
                    </li>
                </ul>
            </div>
            {/foreach}
        </div>
    </div>
</div>
{/if}
