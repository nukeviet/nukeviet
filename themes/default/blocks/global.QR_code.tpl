<!-- BEGIN: main -->
<button type="button" class="qrcode btn btn-primary active btn-xs" title="{QRCODE.title}" data-target="#barcode" data-img="#qr-code" data-url="{QRCODE.selfurl}" data-level="{QRCODE.level}" data-ppp="{QRCODE.pixel_per_point}" data-of="{QRCODE.outer_frame}" style="color:#000"><em class="fa fa-barcode fa-lg margin-right-sm"></em>&nbsp;QR-code</button>
<div id="barcode" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-body text-center">
        <img id="qr-code" src="{NV_BASE_SITEURL}images/pix.gif" alt="{QRCODE.title}" title="{QRCODE.title}">
      </div>
    </div>
  </div>
</div>
<!-- END: main -->