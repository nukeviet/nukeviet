{* Danh sách danh mục email *}
{if not empty($LIST)}
<div class="panel panel-primary">
    <div class="panel-heading">
        <i class="fa fa-list" aria-hidden="true"></i> {$LANG->get('categories_list')}
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
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
                        <select class="form-control input-sm" data-toggle="weightcat" data-catid="{$row.catid}" data-checksess="{$smarty.const.NV_CHECK_SESSION}">
                            {for $key=1 to $LISTCOUNT}
                            <option value="{$key}"{if $key eq $row.weight} selected="selected"{/if}>{$key}</option>
                            {/for}
                        </select>
                    </td>
                    <td>{$row['title']}</td>
                    <td class="text-center text-nowrap">
                        {if not $row.is_system}
                        <a href="{$BASE_URL}&amp;catid={$row.catid}" class="btn btn-sm btn-default"><i class="fa fa-pencil"></i> {$LANG->get('edit')}</a>
                        <a href="#" class="btn btn-sm btn-danger" data-toggle="delcat" data-checksess="{$smarty.const.NV_CHECK_SESSION}" data-catid="{$row.catid}"><i class="fa fa-trash"></i> {$LANG->get('delete')}</a>
                        {/if}
                    </td>
                </tr>
                {/foreach}
            </tbody>
        </table>
    </div>
</div>
{/if}
{* Lỗi *}
{if not empty($ERROR)}
<div class="alert alert-danger">{$ERROR}</div>
{/if}
{* Thêm/Sửa danh mục *}
<div class="panel panel-primary" id="addedit-form">
    <div class="panel-heading"><i class="fa fa-pencil" aria-hidden="true"></i> {$CAPTION}</div>
    <div class="panel-body">
        <form method="post" action="{$FORM_ACTION}" autocomplete="off" class="form-horizontal">
            <div class="form-group">
                <label class="col-xs-24 col-sm-6 control-label" for="cat_title">{$LANG->get('categories_title')} <i class="text-danger">(*)</i></label>
                <div class="col-xs-24 col-sm-16 col-lg-12">
                    <input type="text" class="form-control" id="cat_title" name="title" value="{$DATA['title']}">
                </div>
            </div>
            <div class="form-group row py-0">
                <div class="col-xs-24 col-sm-16 col-lg-12 col-sm-offset-6">
                    <label>
                        <input type="checkbox" id="cat_status" name="status" value="1"{if $DATA['status']} checked="checked"{/if}>{$LANG->get('categories_show')}
                    </label>
                </div>
            </div>
            <div class="form-group row mb-0 pb-0">
                <div class="col-xs-24 col-sm-16 col-sm-offset-6">
                    <button class="btn btn-space btn-primary" type="submit" name="saveform" value="{$smarty.const.NV_CHECK_SESSION}">{$LANG->get('submit')}</button>
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
