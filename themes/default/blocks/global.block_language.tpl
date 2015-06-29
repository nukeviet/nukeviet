<!-- BEGIN: main -->
    <!-- BEGIN: language -->
        <div class="language">
            {SELECT_LANGUAGE}:
            <select name="lang">
                <!-- BEGIN: langitem -->
                    <option value="{LANGSITEURL}" title="{SELECTLANGSITE}">{LANGSITENAME}</option>
                <!-- END: langitem -->
                <!-- BEGIN: langcuritem -->
                    <option value="{LANGSITEURL}" title="{SELECTLANGSITE}" selected="selected">{LANGSITENAME}</option>
                <!-- END: langcuritem -->
            </select>
            <script type="text/javascript">
                $(function(){
                    $("select[name=lang]").change(function(){
                        var reurl = $("select[name=lang]").val();
                        document.location = reurl;
                    });
                });
            </script>
        </div>
    <!-- END: language -->
<!-- END: main -->