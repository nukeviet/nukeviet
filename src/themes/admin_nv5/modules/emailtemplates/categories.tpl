{* Danh sách danh mục email *}
{if not empty($LIST)}
<div class="card card-table">
    <div class="card-header">
        {$LANG->get('categories_list')}
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>{$LANG->get('order')}</th>
                        <th style="width: 70%;">{$LANG->get('categories_title')}</th>
                        <th style="width: 20%;" class="text-right">{$LANG->get('actions')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$LIST item=row}
                    <tr>
                        <td>
                            <select class="form-control form-control-xs w70" data-toggle="weightcat" data-catid="{$row.catid}">
                                {for $key=1 to $LISTCOUNT}
                                <option value="{$key}"{if $key eq $row.weight} selected="selected"{/if}>{$key}</option>
                                {/for}
                            </select>
                        </td>
                        <td class="mw200">{$row['title']}</td>
                        <td class="text-right text-nowrap">
                            {if not $row.is_system}
                            <a href="{$BASE_URL}&amp;catid={$row.catid}" class="btn btn-sm btn-hspace btn-secondary"><i class="icon icon-left fas fa-pencil-alt"></i> {$LANG->get('edit')}</a>
                            <a href="#" class="btn btn-sm btn-danger" data-toggle="delcat" data-catid="{$row.catid}"><i class="icon icon-left fas fa-trash-alt"></i> {$LANG->get('delete')}</a>
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
<div role="alert" class="alert alert-danger alert-dismissible">
    <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
    <div class="icon"><i class="far fa-times-circle"></i></div>
    <div class="message">{$ERROR}</div>
</div>
{/if}
{* Thêm/Sửa danh mục *}
<div class="card card-border-color card-border-color-primary" id="addedit-form">
    <div class="card-body">
        <form method="post" action="{$FORM_ACTION}" autocomplete="off">
            <h4 class="mb-0 mt-1 row">
                <span class="col-12 col-sm-8 col-lg-6 offset-sm-3">
                    {$CAPTION}
                </span>
            </h4>
            <div class="card-divider"></div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="cat_title">{$LANG->get('categories_title')} <i class="text-danger">(*)</i></label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <div class="flex-grow-1 flex-shrink-1">
                        <input type="text" class="form-control form-control-sm" id="cat_title" name="title" value="{$DATA['title']}">
                    </div>
                </div>
            </div>
            <div class="form-group row mb-0 pb-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right"></label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <button class="btn btn-space btn-primary" type="submit" name="submit">{$LANG->get('submit')}</button>
                </div>
            </div>
        </form>
    </div>
</div>
{if $DATA.catid or not empty($ERROR)}
<script>
$(document).ready(function() {
    $('html, body').animate({
        scrollTop: ($('#addedit-form').offset().top - 30)
    }, 200);

});
</script>
{/if}
