<!-- BEGIN: main -->
<!-- BEGIN: loop -->
<!-- BEGIN: image -->
<div class="text-center m-bottom">
    <img src="{DEPARTMENT.image}" class="img-thumbnail" alt="{DEPARTMENT.full_name}" />
</div>
<!-- END: image -->
<p class="text-center m-bottom"><strong>{DEPARTMENT.full_name}</strong></p>
<ul class="list-none list-items">
    <!-- BEGIN: cd --><li style="display: flex;"><span class="margin-right-sm"><em class="fa {CD.icon}"></em></span><span style="flex-grow: 1">{CD.value}</span></li><!-- END: cd -->
    <!-- BEGIN: other --><li>{OTHER.name}:&nbsp;{OTHER.value}</li><!-- END: other -->
</ul>
<hr />
<!-- END: loop -->
<!-- END: main -->