<!-- BEGIN: main -->
<div class="row">
    <div class="col-24 col-md-16 col-lg-12">
        <form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
            <table class="table table-striped table-bordered">
                <tbody>
                    <tr>
                        <td>
                            {LANG.add_sitelinks_search_box_schema}
                            (<a class="small" href="https://developers.google.com/search/docs/appearance/structured-data/sitelinks-searchbox" target="_blank">{LANG.more_information}</a>)
                        </td>
                        <td><input type="checkbox" name="sitelinks_search_box_schema" value="1"{SEARCH_BOX_SCHEMA_CHECKED}/></td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" class="text-center">
                            <input type="hidden" name="checkss" value="{CHECKSS}"/>
                            <button type="submit" class="btn btn-primary">{GLANG.submit}</button>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </form>
    </div>
</div>
<!-- END: main -->