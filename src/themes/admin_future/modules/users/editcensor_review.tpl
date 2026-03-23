{* Template: editcensor_review.tpl
 * Form kiểm duyệt thông tin cập nhật của thành viên
 *}

<link type="text/css" href="{$smarty.const.ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet">
<script type="text/javascript" src="{$smarty.const.ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{$smarty.const.ASSETS_LANG_STATIC_URL}/js/language/jquery.ui.datepicker-{$smarty.const.NV_LANG_INTERFACE}.js"></script>

<form method="post" class="ajax-submit" novalidate
      action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}&amp;reviewuid={$REVIEWUID}">
    <input type="hidden" name="checkss" value="{$CHECKSS}">
    <input type="hidden" name="confirm" value="1">

    {* Phần thông tin cơ bản *}
    {if $HAVE_BASIC}
    <div class="card mb-3">
        <div class="card-header">
            <h5 class="card-title mb-0">{$LANG->getModule('editcensor_info_basic')}</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th class="text-nowrap" style="width: 20%;">{$LANG->getModule('editcensor_field')}</th>
                            <th class="text-nowrap" style="width: 30%;">{$LANG->getModule('editcensor_current')}</th>
                            <th class="text-nowrap" style="width: 50%;">{$LANG->getModule('editcensor_new')}</th>
                        </tr>
                    </thead>
                    <tbody>
                        {* Trường họ tên — thứ tự theo name_show *}
                        {if $HAVE_NAME_FIELD}
                            {if $GCONFIG.name_show == 0}
                                {* Họ trước, Tên sau *}
                                {if isset($BASIC_FIELDS.last_name)}
                                {assign var="bf" value=$BASIC_FIELDS.last_name}
                                <tr>
                                    <td>{$bf.title}{if $bf.required} <span class="text-danger">(*)</span>{/if}</td>
                                    <td>{$bf.valueold}</td>
                                    <td>
                                        <input class="form-control" type="text" name="last_name" value="{$bf.value}" autocomplete="family-name">
                                        {if $bf.required}<div class="invalid-feedback"></div>{/if}
                                        {if $bf.description}<div class="form-text">{$bf.description}</div>{/if}
                                    </td>
                                </tr>
                                {/if}
                                {if isset($BASIC_FIELDS.first_name)}
                                {assign var="bf" value=$BASIC_FIELDS.first_name}
                                <tr>
                                    <td>{$bf.title}{if $bf.required} <span class="text-danger">(*)</span>{/if}</td>
                                    <td>{$bf.valueold}</td>
                                    <td>
                                        <input class="form-control" type="text" name="first_name" value="{$bf.value}" autocomplete="given-name">
                                        {if $bf.required}<div class="invalid-feedback"></div>{/if}
                                        {if $bf.description}<div class="form-text">{$bf.description}</div>{/if}
                                    </td>
                                </tr>
                                {/if}
                            {else}
                                {* Tên trước, Họ sau *}
                                {if isset($BASIC_FIELDS.first_name)}
                                {assign var="bf" value=$BASIC_FIELDS.first_name}
                                <tr>
                                    <td>{$bf.title}{if $bf.required} <span class="text-danger">(*)</span>{/if}</td>
                                    <td>{$bf.valueold}</td>
                                    <td>
                                        <input class="form-control" type="text" name="first_name" value="{$bf.value}" autocomplete="given-name">
                                        {if $bf.required}<div class="invalid-feedback"></div>{/if}
                                        {if $bf.description}<div class="form-text">{$bf.description}</div>{/if}
                                    </td>
                                </tr>
                                {/if}
                                {if isset($BASIC_FIELDS.last_name)}
                                {assign var="bf" value=$BASIC_FIELDS.last_name}
                                <tr>
                                    <td>{$bf.title}{if $bf.required} <span class="text-danger">(*)</span>{/if}</td>
                                    <td>{$bf.valueold}</td>
                                    <td>
                                        <input class="form-control" type="text" name="last_name" value="{$bf.value}" autocomplete="family-name">
                                        {if $bf.required}<div class="invalid-feedback"></div>{/if}
                                        {if $bf.description}<div class="form-text">{$bf.description}</div>{/if}
                                    </td>
                                </tr>
                                {/if}
                            {/if}
                        {/if}

                        {* Giới tính *}
                        {if isset($BASIC_FIELDS.gender)}
                        {assign var="bf" value=$BASIC_FIELDS.gender}
                        <tr>
                            <td>{$bf.title}{if $bf.required} <span class="text-danger">(*)</span>{/if}</td>
                            <td>{$bf.gender_old}</td>
                            <td>
                                <select class="form-select" name="gender" style="max-width: 220px;">
                                    {foreach from=$bf.gender_options item=g}
                                    <option value="{$g.key}"{if $g.selected} selected{/if}>{$g.title}</option>
                                    {/foreach}
                                </select>
                                {if $bf.required}<div class="invalid-feedback"></div>{/if}
                                {if $bf.description}<div class="form-text">{$bf.description}</div>{/if}
                            </td>
                        </tr>
                        {/if}

                        {* Ngày sinh *}
                        {if isset($BASIC_FIELDS.birthday)}
                        {assign var="bf" value=$BASIC_FIELDS.birthday}
                        <tr>
                            <td>{$bf.title}{if $bf.required} <span class="text-danger">(*)</span>{/if}</td>
                            <td>{$bf.valueold}</td>
                            <td>
                                <div class="input-group" style="max-width: 180px;">
                                    <input name="birthday" id="birthday" class="form-control datepicker" value="{$bf.value}" maxlength="10" type="text" autocomplete="off">
                                    <button type="button" class="btn btn-outline-secondary" data-toggle="focusDate" aria-label="{$bf.title}"><i class="fa-solid fa-calendar-days"></i></button>
                                </div>
                                {if $bf.required}<div class="invalid-feedback"></div>{/if}
                                {if $bf.description}<div class="form-text">{$bf.description}</div>{/if}
                            </td>
                        </tr>
                        {/if}

                        {* Chữ ký *}
                        {if isset($BASIC_FIELDS.sig)}
                        {assign var="bf" value=$BASIC_FIELDS.sig}
                        <tr>
                            <td class="align-top">{$bf.title}{if $bf.required} <span class="text-danger">(*)</span>{/if}</td>
                            <td class="align-top">{$bf.valueold}</td>
                            <td>
                                <textarea name="sig" class="form-control" rows="4" autocomplete="off">{$bf.value}</textarea>
                                {if $bf.required}<div class="invalid-feedback"></div>{/if}
                                {if $bf.description}<div class="form-text">{$bf.description}</div>{/if}
                            </td>
                        </tr>
                        {/if}

                        {* Hiển thị email *}
                        <tr>
                            <td>{$LANG->getModule('show_email')}</td>
                            <td>{$VIEW_MAIL_OLD}</td>
                            <td>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="view_mail" id="view_mail" value="1"{if $VIEW_MAIL_NEW} checked{/if}>
                                    <label class="form-check-label" for="view_mail">{$LANG->getModule('show_email')}</label>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    {/if}

    {* Phần thông tin tùy biến *}
    {if $HAVE_CUSTOM}
    <div class="card mb-3">
        <div class="card-header">
            <h5 class="card-title mb-0">{$LANG->getModule('editcensor_info_custom')}</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th class="text-nowrap" style="width: 20%;">{$LANG->getModule('editcensor_field')}</th>
                            <th class="text-nowrap" style="width: 30%;">{$LANG->getModule('editcensor_current')}</th>
                            <th class="text-nowrap" style="width: 50%;">{$LANG->getModule('editcensor_new')}</th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach from=$CUSTOM_FIELDS item=cf}
                        <tr>
                            <td class="align-top">
                                {$cf.title}{if $cf.required} <span class="text-danger">(*)</span>{/if}
                            </td>
                            <td class="align-top">
                                {$cf.valueold}
                            </td>
                            <td>
                                {if $cf.field_type == 'textbox' || $cf.field_type == 'number'}
                                <input class="form-control" type="text" name="custom_fields[{$cf.field}]" value="{$cf.value}" autocomplete="off">
                                {elseif $cf.field_type == 'date'}
                                <div class="input-group" style="max-width: 180px;">
                                    <input class="form-control datepicker" type="text" name="custom_fields[{$cf.field}]" value="{$cf.value}" autocomplete="off">
                                    <button type="button" class="btn btn-outline-secondary" data-toggle="focusDate" aria-label="{$cf.title}"><i class="fa-solid fa-calendar-days"></i></button>
                                </div>
                                {elseif $cf.field_type == 'textarea'}
                                <textarea class="form-control" rows="4" name="custom_fields[{$cf.field}]" autocomplete="off">{$cf.value}</textarea>
                                {elseif $cf.field_type == 'editor'}
                                    {if $cf.has_editor}
                                    {$cf.editor_html nofilter}
                                    {else}
                                    <textarea class="form-control" rows="4" name="custom_fields[{$cf.field}]" autocomplete="off">{$cf.value}</textarea>
                                    {/if}
                                {elseif $cf.field_type == 'select'}
                                <select class="form-select" name="custom_fields[{$cf.field}]" style="max-width: 220px;">
                                    {foreach from=$cf.choices_options item=opt}
                                    <option value="{$opt.key}"{if $opt.selected} selected{/if}>{$opt.value}</option>
                                    {/foreach}
                                </select>
                                {elseif $cf.field_type == 'radio'}
                                {foreach from=$cf.choices_options item=opt}
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="custom_fields[{$cf.field}]" value="{$opt.key}" id="lb_{$opt.id}"{if $opt.checked} checked{/if}>
                                    <label class="form-check-label" for="lb_{$opt.id}">{$opt.value}</label>
                                </div>
                                {/foreach}
                                {elseif $cf.field_type == 'checkbox'}
                                {foreach from=$cf.choices_options item=opt}
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="custom_fields[{$cf.field}][]" value="{$opt.key}" id="lb_{$opt.id}"{if $opt.checked} checked{/if}>
                                    <label class="form-check-label" for="lb_{$opt.id}">{$opt.value}</label>
                                </div>
                                {/foreach}
                                {elseif $cf.field_type == 'multiselect'}
                                <select class="form-select" name="custom_fields[{$cf.field}][]" multiple="multiple" style="max-width: 220px;">
                                    {foreach from=$cf.choices_options item=opt}
                                    <option value="{$opt.key}"{if $opt.selected} selected{/if}>{$opt.value}</option>
                                    {/foreach}
                                </select>
                                {elseif $cf.field_type == 'file'}
                                <div class="filelist" data-field="{$cf.field}" data-oclass="{$cf.class}" data-maxnum="{$cf.filemaxnum}">
                                    <ul class="list-unstyled items mb-1">
                                        {foreach from=$cf.all_files item=fi}
                                        <li class="d-flex align-items-center gap-1 mb-1">
                                            <input type="checkbox" class="form-check-input {$cf.class}" name="custom_fields[{$cf.field}][]" value="{$fi.key}"{if $fi.checked} checked{/if}>
                                            <button type="button" class="btn btn-success btn-sm btn-file type-{$fi.type}" data-url="{$fi.url}">{$fi.value}</button>
                                            <button type="button" class="btn btn-link btn-sm" data-toggle="thisfile_del">{$LANG->getModule('delete')}</button>
                                        </li>
                                        {/foreach}
                                    </ul>
                                    <div>
                                        <button type="button" class="btn btn-info btn-sm" data-toggle="addfilebtn" data-modal="uploadfile_{$cf.field}"{if $cf.hide_addfile} style="display:none"{/if}>
                                            <i class="fa-solid fa-upload"></i> {$LANG->getModule('addfile')}
                                        </button>
                                    </div>
                                    <div class="modal fade uploadfile" tabindex="-1" id="uploadfile_{$cf.field}"
                                         data-url="{$smarty.const.NV_BASE_SITEURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}"
                                         data-field="{$cf.field}"
                                         data-csrf="{$cf.csrf}"
                                         data-accept="{$cf.fileaccept}"
                                         data-maxsize="{$cf.filemaxsize}"
                                         data-ext-error="{$LANG->getModule('addfile_ext_error')}"
                                         data-size-error="{$LANG->getModule('addfile_size_error')}"
                                         data-size-error2="{$LANG->getModule('addfile_size_error2')}"
                                         data-delete="{$LANG->getModule('delete')}">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">{$LANG->getModule('addfile')}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{$LANG->getGlobal('close')}"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p class="fileinput" style="display:flex;justify-content:center;margin-top:20px;margin-bottom:20px"></p>
                                                    <ul class="list-unstyled small text-muted">
                                                        <li>- {$LANG->getModule('accepted_extensions')}: {$cf.fileaccept}</li>
                                                        <li>- {$LANG->getModule('field_file_max_size')}: {$cf.filemaxsize_format}</li>
                                                        {if $cf.widthlimit}<li>- {$cf.widthlimit}</li>{/if}
                                                        {if $cf.heightlimit}<li>- {$cf.heightlimit}</li>{/if}
                                                    </ul>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{$LANG->getGlobal('close')}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {/if}
                                {if $cf.description}<div class="form-text">{$cf.description}</div>{/if}
                                {if $cf.required}<div class="invalid-feedback"></div>{/if}
                            </td>
                        </tr>
                        {/foreach}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    {/if}

    <div class="d-flex gap-2 justify-content-center">
        <button type="submit" class="btn btn-success">
            <i class="fa-solid fa-check"></i> {$LANG->getModule('approved')}
        </button>
        <button type="button" class="btn btn-danger"
                data-toggle="deny-censor"
                data-userid="{$REVIEWUID}"
                data-checkss="{$CHECKSS}"
                data-msgconfirm="{$LANG->getModule('editcensor_confirm_denied')}"
                data-icon="fa-trash">
            <i class="fa-solid fa-trash" data-icon="fa-trash"></i> {$LANG->getModule('denied')}
        </button>
    </div>
</form>
