<div class="dropup-center dropup site-qrcode">
    <button type="button" class="btn btn-secondary" aria-label="{$QRCODE.title}" data-toggle="siteQrCode" data-load="no" data-url="{$QRCODE.selfurl}" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
        <i class="fa-solid fa-qrcode"></i> QR-code
    </button>
    <ul class="dropdown-menu p-2">
        <img src="{$smarty.const.ASSETS_STATIC_URL}/images/pix.svg" alt="{$QRCODE.title}" title="{$QRCODE.title}">
        <div class="loader position-absolute top-50 start-50 translate-middle">
            <div class="spinner-grow text-primary" role="status">
                <span class="visually-hidden">{$LANG->getGlobal('wait_page_load')}</span>
            </div>
        </div>
    </ul>
</div>
