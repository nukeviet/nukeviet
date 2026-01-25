{* Danh sách danh mục email *}
{if not empty($LIST)}
<div class="card mb-3">
    <div class="card-body">
        <div class="table-responsive-lg table-card">
            <table class="table table-striped align-middle table-sticky mb-0">
                <thead class="text-muted">
                    <tr>
                        <th style="width: 10%;" class="text-center text-nowrap">{$LANG->get('order')}</th>
                        <th style="width: 50%;" class="text-nowrap">{$LANG->get('categories_title')}</th>
                        <th style="width: 40%;" class="text-center text-nowrap">{$LANG->get('actions')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$LIST item=row}
                    <tr>
                        <td>
                            <select class="form-select form-select-sm fw-75" data-toggle="weightcat" data-catid="{$row.catid}" data-checksess="{$smarty.const.NV_CHECK_SESSION}">
                                {for $key=1 to $LISTCOUNT}
                                <option value="{$key}"{if $key eq $row.weight} selected="selected"{/if}>{$key}</option>
                                {/for}
                            </select>
                        </td>
                        <td>{$row['title']}</td>
                        <td class="text-center text-nowrap">
                            {if not $row.is_system}
                            <a href="{$BASE_URL}&amp;catid={$row.catid}" class="btn btn-sm btn-secondary"><i class="fa-solid fa-pencil"></i> {$LANG->get('edit')}</a>
                            <a href="#" class="btn btn-sm btn-danger" data-toggle="delcat" data-checksess="{$smarty.const.NV_CHECK_SESSION}" data-catid="{$row.catid}"><i class="fa-solid fa-trash" data-icon="fa-trash"></i> {$LANG->get('delete')}</a>
                            {/if}
                        </td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>
{/if}
{* Lỗi *}
{if not empty($ERROR)}
<div class="alert alert-danger" role="alert">{$ERROR}</div>
{/if}
{* Thêm/Sửa danh mục *}
<div class="card" id="addedit-form">
    <div class="card-header fw-medium fs-5 py-2"><i class="fa-solid fa-pencil"></i> {$CAPTION}</div>
    <div class="card-body">
        <form method="post" action="{$FORM_ACTION}" autocomplete="off">
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label text-sm-end" for="cat_title">{$LANG->get('categories_title')} <i class="text-danger">(*)</i></label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <input type="text" class="form-control" id="cat_title" name="title" value="{$DATA['title']}">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-8 col-lg-6 col-xxl-5 offset-sm-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="cat_status" name="status" value="1"{if $DATA['status']} checked="checked"{/if}>
                        <label class="form-check-label" for="cat_status">{$LANG->get('categories_show')}</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-8 offset-sm-3">
                    <button class="btn btn-space btn-primary" type="submit" name="saveform" value="{$smarty.const.NV_CHECK_SESSION}">{$LANG->get('submit')}</button>
                </div>
            </div>
        </form>
    </div>
</div>
{if $DATA.catid or not empty($ERROR)}
<script>
$(function() {
    $('html, body').animate({
        scrollTop: ($('#addedit-form').offset().top - 30)
    }, 200);
});
</script>
{/if}
