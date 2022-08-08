<!-- BEGIN: main -->
<p>{LANG.custom_configs_note}</p>
<p><strong>{LANG.config_key}</strong>: {LANG.config_key_note}<br /><strong>{LANG.config_name}</strong>: {LANG.config_name_note}</p>
<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <colgroup>
                <col>
                <col>
                <col>
                <col style="width: 1%;">
            </colgroup>
            <thead>
                <tr class="bg-primary">
                    <th>{LANG.config_key}</th>
                    <th>{LANG.config_name}</th>
                    <th>{LANG.config_value}</th>
                    <th></th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <td colspan="4" class="text-center">
                        <input type="hidden" name="checkss" value="{CHECKSS}" />
                        <button type="button" class="btn btn-default addconfig">{LANG.addconfig}</button>
                        <input type="submit" name="submit" value="{LANG.submit}" class="btn btn-primary w100" />
                    </td>
                </tr>
            </tfoot>
            <tbody>
                <tr class="sample hidden">
                    <td>
                        <input type="text" class="form-control required" name="config_key[]" value="" maxlength="30">
                    </td>
                    <td>
                        <input type="text" class="form-control" name="config_name[]" value="" maxlength="255">
                    </td>
                    <td>
                        <input type="text" class="form-control required" name="config_value[]" value="">
                    </td>
                    <td>
                        <button type="button" class="btn btn-default del-item">{GLANG.delete}</button>
                    </td>
                </tr>
                <!-- BEGIN: loop -->
                <tr>
                    <td>
                        <input type="text" class="form-control required" name="config_key[]" value="{CUSTOM.key}" maxlength="30">
                    </td>
                    <td>
                        <input type="text" class="form-control" name="config_name[]" value="{CUSTOM.name}" maxlength="255">
                    </td>
                    <td>
                        <input type="text" class="form-control required" name="config_value[]" value="{CUSTOM.value}">
                    </td>
                    <td>
                        <button type="button" class="btn btn-default del-item">{GLANG.delete}</button>
                    </td>
                </tr>
                <!-- END: loop -->
            </tbody>
        </table>
    </div>
</form>
<script>
    $(function() {
        $('[name^=config_key]').on('input', function(e) {
            $(this).val($(this).val().replace(/[^A-Za-z0-9\_]/gi, ''))
        });

        $('.addconfig').on('click', function(e) {
            e.preventDefault();
            var tb = $(this).parents('table');
            $('tbody', tb).append('<tr>' + $('tbody .sample', tb).html() + '</tr>')
        });

        $('body').on('click', '.del-item', function(e) {
            e.preventDefault();
            $(this).parents('tr').remove()
        })
    })
</script>
<!-- END: main -->