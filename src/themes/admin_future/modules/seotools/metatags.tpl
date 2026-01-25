<form method="post" class="ajax-submit" id="metatags-manage" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}" novalidate>
    <div class="card mb-4">
        <div class="card-body">
            <div class="table-responsive-lg table-card">
                <table class="table table-striped align-middle mb-0">
                    <thead class="text-muted">
                        <tr>
                            <th class="text-nowrap text-center" style="width: 1%;">{$LANG->getModule('metaTagsGroupName')}</th>
                            <th class="text-nowrap text-center" style="width: 1%;">{$LANG->getModule('metaTagsGroupValue')} (*)</th>
                            <th class="text-nowrap">{$LANG->getModule('metaTagsContent')} (**)</th>
                        </tr>
                    </thead>
                    <tbody class="items">
                        {foreach from=$METAS key=key item=meta}
                        {assign var="disabled" value=(not empty($GCONFIG.idsite) and $meta.group eq 'name' and in_array($meta.value, ['author', 'copyright'])) nocache}
                        <tr class="item">
                            <td>
                                <select name="metaGroupsName[]" class="form-select fw-125"{if $disabled} disabled{/if}>
                                    <option value="name"{if $meta.group eq 'name'} selected{/if}>name</option>
                                    <option value="property"{if $meta.group eq 'property'} selected{/if}>property</option>
                                    <option value="http-equiv"{if $meta.group eq 'http-equiv'} selected{/if}>http-equiv</option>
                                </select>
                            </td>
                            <td>
                                <div class="input-group flex-nowrap metaGroupsValue-dropdown">
                                    <input class="form-control fw-200" type="text" value="{$meta.value}"{if $disabled} disabled{/if} name="metaGroupsValue[]" aria-label="{$LANG->getModule('metaTagsGroupValue')}">
                                    <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"></button>
                                    <ul class="dropdown-menu dropdown-menu-end metaGroupsValue-opt"></ul>
                                </div>
                            </td>
                            <td>
                                <div class="hstack gap-1 align-items-center">
                                    <div class="flex-grow-1">
                                        <div class="input-group flex-nowrap">
                                            <input class="form-control fw-200" type="text" value="{$meta.content}"{if $disabled} disabled{/if} name="metaContents[]" aria-label="{$LANG->getModule('metaTagsContent')}">
                                            <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"></button>
                                            <ul class="dropdown-menu dropdown-menu-end metaContents-opt">
                                                {foreach from=$META_CTLISTS item=value}
                                                <li><a class="metacontent dropdown-item" href="#">{$value}</a></li>
                                                {/foreach}
                                            </ul>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-secondary del-meta-tag" aria-label="{$LANG->getGlobal('delete')}">-</button>
                                    <button type="button" class="btn btn-secondary add-meta-tag" aria-label="{$LANG->getGlobal('add')}">+</button>
                                </div>
                            </td>
                        </tr>
                        {/foreach}
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer border-top">
            <div class="vstack gap-1">
                <div>*: {$NOTE}</div>
                <div>**: {$VARS}</div>
                <div>***: {$LANG->getModule('metaTagsOgpNote')}</div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header py-2 fw-medium fs-5">{$LANG->getModule('metaTagsSettings')}</div>
        <div class="card-body pt-4">
            <div class="row mb-3">
                <div class="col-sm-8 col-lg-6 col-xxl-5 offset-sm-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="element_metaTagsOgp" name="metaTagsOgp" value="1"{if not empty($GCONFIG.metaTagsOgp)} checked{/if} role="switch">
                        <label class="form-check-label" for="element_metaTagsOgp">{$LANG->getModule('metaTagsOgp')}</label>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="element_ogp_image" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('ogp_image')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <div class="input-group">
                        <input type="text" class="form-control" id="element_ogp_image" name="ogp_image" value="{$OGP_IMAGE}" maxlength="250" aria-describedby="element_ogp_image_btn">
                        <button type="button" class="btn btn-secondary" id="element_ogp_image_btn" aria-label="{$LANG->getGlobal('browse_image')}" data-toggle="selectfile" data-target="element_ogp_image" data-type="image" title="{$LANG->getGlobal('browse_image')}"><i class="fa-solid fa-folder-open"></i></button>
                    </div>
                    <div class="form-text">{$LANG->getModule('ogp_image_help')}</div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-8 col-lg-6 col-xxl-5 offset-sm-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="element_private_site" name="private_site" value="1"{if not empty($GCONFIG.private_site)} checked{/if} role="switch">
                        <label class="form-check-label" for="element_private_site">{$LANG->getModule('private_site')}</label>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="element_description_length" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('description_length')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <input type="number" step="1" min="0" max="1024" class="form-control" id="element_description_length" name="description_length" value="{$GCONFIG.description_length}">
                    <div class="form-text">{$LANG->getModule('description_note')}</div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-8 col-lg-6 col-xxl-5 offset-sm-3">
                    <input type="hidden" name="checkss" value="{$CHECKSS}">
                    <button type="submit" class="btn btn-primary">{$LANG->getGlobal('submit')}</button>
                </div>
            </div>
        </div>
    </div>
</form>
<ul id="meta-name-list" class="d-none">
    {foreach from=$META_NAME_LIST item=value}
    <li><a class="groupvalue dropdown-item" href="#">{$value}</a></li>
    {/foreach}
</ul>
<ul id="meta-property-list" class="d-none">
    {foreach from=$META_PROPERTY_LIST item=value}
    <li><a class="groupvalue dropdown-item" href="#">{$value}</a></li>
    {/foreach}
</ul>
<ul id="meta-http-equiv-list" class="d-none">
    {foreach from=$META_HTTP_EQUIV_LIST item=value}
    <li><a class="groupvalue dropdown-item" href="#">{$value}</a></li>
    {/foreach}
</ul>
