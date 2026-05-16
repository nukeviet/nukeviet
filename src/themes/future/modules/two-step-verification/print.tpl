<div class="p-4">
    <div class="print-codes">
        <h1 class="mb-4 text-center">{$LANG->getModule('recovery_codes')}</h1>
        <div class="row">
            {foreach from=$CODES item=code}
            <div class="col-6 col-md-4 text-center">
                <div class="recovery-code">
                    <span class="h1">{$code.code}</span>
                </div>
            </div>
            {/foreach}
        </div>
    </div>
</div>
<script>
    window.print();
</script>
