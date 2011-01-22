<div id="footer" class="clearfix">
    <div class="copyright">
        {NV_DB_NUM_QUERIES}: {COUNT_QUERY_STRS}/{NV_TOTAL_TIME} <a href="#queries" onclick="nv_show_hidden('div_hide',2);">{NV_SHOW_QUERIES}</a>
        <br/>
        <strong>{NV_COPYRIGHT}</strong>
    </div>
    <div class="imgstat">
        <a title="NUKEVIET CMS" href="http://nukeviet.vn" target="_blank"><img alt="NUKEVIET CMS" title="NUKEVIET CMS" src="{NV_BASE_SITEURL}images/banner_nukeviet_88x15.jpg" width="88" height="15" /></a>
        <br/>
    </div>
</div>
<div id="div_hide" style="visibility:hidden;display:none;">
    <!-- BEGIN: nv_show_queries --><a name="queries"></a>
    <table summary="{NV_SHOW_QUERIES}" class="tab1">
        <caption>
            {NV_SHOW_QUERIES}
        </caption>
        <col width="16" /><!-- BEGIN: nv_show_queries_loop -->
        <tbody {NV_SHOW_QUERIES_CLASS}>
            <tr>
                <td>
                    {NV_FIELD1}
                </td>
                <td>
                    {NV_FIELD}
                </td>
            </tr>
        </tbody>
        <!-- END: nv_show_queries_loop -->
    </table>
    <br/>
    <br/>
    <!-- END: nv_show_queries -->
</div>
<div id="run_cronjobs" style="visibility:hidden;display:none;">
<img alt="" title="" src="{NV_BASE_ADMINURL}index.php?second=cronjobs&amp;p={NV_GENPASS}" width="1" height="1" />
</div>
</div>
<script type="text/javascript">
    nv_DigitalClock('digclock');
</script>
<!-- BEGIN: nv_if_mudim -->
<script type="text/javascript" src="{NV_BASE_SITEURL}js/mudim.js">
</script>
<!-- END: nv_if_mudim -->
</body>
</html>
