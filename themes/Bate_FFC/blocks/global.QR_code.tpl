<!-- BEGIN: main -->
<button type="button" class="qrcode btn btn-primary active btn-xs text-black" title="{QRCODE.title}" data-toggle="ftip" data-target=".barcode" data-click="y" data-load="no" data-img=".barcode img" data-url="{QRCODE.selfurl}"><em class="icon-qrcode icon-lg"></em>&nbsp;QR-code</button>
<div class="barcode hidden">
    <img src="{NV_STATIC_URL}{NV_ASSETS_DIR}/images/pix.gif" alt="{QRCODE.title}" title="{QRCODE.title}" width="170" height="170">
</div>
<!-- END: main -->