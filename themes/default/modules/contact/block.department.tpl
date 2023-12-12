<!-- BEGIN: main -->
<div class="panel panel-default">
    <a href="{DEPARTMENT.url}" class="panel-heading" style="display:block"><strong>{DEPARTMENT.full_name}</strong></a>
    <ul class="list-group">
        <!-- BEGIN: note -->
        <li class="list-group-item">{DEPARTMENT.note}</li>
        <!-- END: note -->
        <!-- BEGIN: address -->
        <li class="list-group-item">{LANG.address}: {DEPARTMENT.address}</li>
        <!-- END: address -->
        <!-- BEGIN: cd -->
        <li class="list-group-item">
            <div style="display: flex;">
                <span class="margin-right-sm">{CD.name}:</span>
                <span style="flex-grow: 1">{CD.value}</span>
            </div>
        </li>
    <!-- END: cd -->
    </ul>
</div>
<!-- END: main -->