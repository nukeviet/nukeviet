<div id="show_funcs">
    <div class="text-center">
        <i class="fas fa-spinner fa-pulse fa-2x"></i>
    </div>
</div>
<div id="show_funcs_action">
    {if $DATA.isfuncs}
    <div class="text-center">
        <i class="fas fa-spinner fa-pulse fa-2x"></i>
    </div>
    {/if}
</div>
<script>nv_show_funcs('show_funcs');</script>
{if $DATA.isfuncs}
<script>nv_bl_list('{$DATA.func_id}', '{$DATA.pos}', 'show_funcs_action');</script>
{/if}
