<!-- BEGIN: main -->
<!-- BEGIN: deps_tab -->
<div class="margin-bottom-lg">
    <span class="hide"><a id="a-tab" href="#">tab</a></span>
    <select class="form-control tablist">
        <!-- BEGIN: option -->
        <option value="#dep-{DEP.id}" {DEP.sel}>{DEP.full_name}</option>
        <!-- END: option -->
    </select>
    <script>
        $(function() {
            $('.tablist').on('change', function() {
                $('#a-tab').attr('href', $(this).val());
                $('#a-tab').tab('show')
            })
        })
    </script>
</div>
<!-- END: deps_tab -->

<div class="tab-content">
    <!-- BEGIN: deps_content -->
    <div role="tabpanel" class="tab-pane fade<!-- BEGIN: active --> in active<!-- END: active -->" id="dep-{DEP.id}">
        <ul class="list-unstyled">
            <!-- BEGIN: supporter -->
            <li class="margin-bottom-lg">
                <div class="m-bottom" style="display:flex;align-items:center;">
                    <img src="{SUPPORTER.image}" style="width:40px;height:40px;border:1px solid #dadada;border-radius:50%;margin-right:7px;margin-top:-5px" alt="" />
                    <strong>{SUPPORTER.full_name}</strong>
                </div>
                <!-- BEGIN: cd -->
                <p style="display:flex">
                    <span><em class="fa {CD.icon} fa-horizon margin-right-sm"></em></span><span style="flex-grow: 1">{CD.name}: {CD.value}</span>
                </p>
                <!-- END: cd -->
                <!-- BEGIN: hr --><hr/><!-- END: hr -->
            </li>
            <!-- END: supporter -->
        </ul>
    </div>
    <!-- END: deps_content -->
</div>
<!-- END: main -->