{*
Xóa tệp này khỏi giao diện của bạn nếu không có nhu cầu phát triển giao diện thông báo inline
Khi đó hệ thống sẽ lấy từ themes/default/system/alert.tpl
*}
<div class="text-center">
    <div class="alert alert-{$TYPE}" role="alert">
        {if not empty($TITLE)}
        <div class="fs-5 fw-medium mb-2">{$TITLE}</div>
        {/if}
        <div>{$CONTENT}</div>
        {if not empty($URL_BACK)}
        <script>
        setTimeout(() => {
            window.location.href = "{$URL_BACK}";
        }, {$TIME_BACK * 1000});
        </script>
        <div class="mt-2">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">{$LANG->getGlobal('wait_page_load')}</span>
            </div>
        </div>
        {if not empty($LANG_BACK)}
        <div class="mt-1">
            <a href="{$URL_BACK}" title="{$LANG_BACK}">[{$LANG_BACK}]</a>
        </div>
        {/if}
        {/if}
    </div>
</div>
