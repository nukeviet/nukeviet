<div class="card card-table">
    <div class="card-header">
        {$CAPTION}
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <colgroup>
                    <col style="width: 50%">
                    <col style="width: 50%">
                </colgroup>
                <tbody>
                    {foreach from=$DATA key=key item=value}
                    <tr>
                        <td>{$LANG->get($key)}</td>
                        <td>{$value}</td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>
<div id="show_tables"></div>
<script type="text/javascript">nv_show_dbtables();</script>
