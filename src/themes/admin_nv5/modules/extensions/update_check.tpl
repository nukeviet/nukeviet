{if not empty($ERROR)}
<div role="alert" class="alert alert-danger alert-dismissible">
    <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
    <div class="icon"><i class="far fa-times-circle"></i></div>
    <div class="message">{$ERROR}</div>
</div>
{else}
<div class="card card-table">
    <div class="card-header pt-2">
        {$LANG->get('extUpdCheck')}
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <tbody>
                    <tr>
                        <td style="width: 30%;" class="text-nowrap">{$LANG->get('extname')}</td>
                        <td style="width: 70%;">{$DATA.title}</td>
                    </tr>
                    {if $DATA.fileInfo eq 'ready'}
                    {* Sẵn sàng để tải gói cập nhật *}
                    <tr>
                        <td class="text-nowrap">{$LANG->get('extUpdCheckStatus')}</td>
                        <td><i class="fas fa-check text-success"></i> {$LANG->get('extUpdCheckSuccess')}</td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <div id="upd-getfile" class="text-center"></div>
                            <script type="text/javascript">
                            $(function() {
                                $('#upd-getfile').html('<div class="mb-3">{$LANG->get('extUpdCheckSuccessNote')}</div><i class="fa fa-spin fa-spinner fa-2x m-bottom wt-icon-loading"></i>').load('{$LINK_DOWNLOAD}');
                            });
                            </script>
                        </td>
                    </tr>
                    {elseif $DATA.fileInfo eq 'notlogin'}
                    {* Đăng nhập để kiểm tra *}
                    <tr>
                        <td class="text-nowrap">{$LANG->get('extUpdCheckStatus')}</td>
                        <td><i class="far fa-frown warning"></i> {$LANG->get('extUpdNotLogin')}</td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <strong class="text-warning">{$LANG->get('extUpdLoginRequire', $LINK_LOGIN)}</strong>
                        </td>
                    </tr>
                    {elseif $DATA.fileInfo eq 'unpaid'}
                    {* Cần mua gói cập nhật *}
                    <tr>
                        <td class="text-nowrap">{$LANG->get('extUpdCheckStatus')}</td>
                        <td><i class="far fa-frown warning"></i> {$LANG->get('extUpdUnpaid')}</td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <strong class="text-warning">{$LANG->get('extUpdPaidRequire', $DATA.link)}</strong>
                        </td>
                    </tr>
                    {else}
                    {* Lỗi không xác định *}
                    <tr>
                        <td class="text-nowrap">{$LANG->get('extUpdCheckStatus')}</td>
                        <td><i class="far fa-frown danger"></i> {$LANG->get('extUpdInvalid')}</td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <strong class="text-danger">{$LANG->get('extUpdInvalidNote')}</strong>
                        </td>
                    </tr>
                    {/if}
                </tbody>
            </table>
        </div>
    </div>
</div>
{/if}
