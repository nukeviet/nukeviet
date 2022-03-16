<!-- BEGIN: step -->
<table id="checkserver" cellspacing="0" summary="{LANG.checkserver_detail}">
    <caption>{LANG.if_server} <span class="highlight_red">{LANG.not_compatible}</span>.
    {LANG.please_checkserver}.</caption>
    <tr>
        <th scope="col" class="nobg">{LANG.server_request}</th>
        <th scope="col">{LANG.note}</th>
        <th scope="col">{LANG.result}</th>
    </tr>
    <tr>
        <th scope="row" class="spec">{LANG.php_version}: {DATA_REQUEST.php_version}</th>
        <td>{LANG.required_on} &gt;= {DATA_REQUEST.php_required_min} {LANG.and} &lt;= {DATA_REQUEST.php_allowed_max}</td>
        <td><span class="{DATA_REQUEST.class_php_support}">{DATA_REQUEST.php_support}</span></td>
    </tr>
    <tr>
        <th scope="row" class="specalt">{LANG.pdo_support} (PDO)</th>
        <td class="alt">{LANG.required_on}</td>
        <td class="alt"><span class="{DATA_REQUEST.class_pdo_support}">{DATA_REQUEST.pdo_support}</span></td>
    </tr>
    <tr>
        <th scope="row" class="spec">{LANG.opendir_support}</th>
        <td>{LANG.request}</td>
        <td><span class="{DATA_REQUEST.class_opendir_support}">{DATA_REQUEST.opendir_support}</span></td>
    </tr>
    <tr>
        <th scope="row" class="specalt">{LANG.gd_support}</th>
        <td class="alt">{LANG.request}</td>
        <td class="alt"><span class="{DATA_REQUEST.class_gd_support}">{DATA_REQUEST.gd_support}</span></td>
    </tr>
    <tr>
        <th scope="row" class="spec">{LANG.xml_support}</th>
        <td>{LANG.request}</td>
        <td><span class="{DATA_REQUEST.class_xml_support}">{DATA_REQUEST.xml_support}</span></td>
    </tr>
    <tr>
        <th scope="row" class="specalt">{LANG.openssl_support}</th>
        <td class="alt">{LANG.request}</td>
        <td class="alt"><span class="{DATA_REQUEST.class_openssl_support}">{DATA_REQUEST.openssl_support}</span></td>
    </tr>
    <tr>
        <th scope="row" class="spec">{LANG.session_support}</th>
        <td>{LANG.request}</td>
        <td><span class="{DATA_REQUEST.class_session_support}">{DATA_REQUEST.session_support}</span></td>
    </tr>
    <tr>
        <th scope="row" class="specalt">{LANG.fileuploads_support}</th>
        <td class="alt">{LANG.request}</td>
        <td class="alt"><span class="{DATA_REQUEST.class_fileuploads_support}">{DATA_REQUEST.fileuploads_support}</span></td>
    </tr>
    <tr>
        <th scope="row" class="specalt">{LANG.json_support}</th>
        <td class="alt">{LANG.request}</td>
        <td class="alt"><span class="{DATA_REQUEST.class_json_support}">{DATA_REQUEST.json_support}</span></td>
    </tr>
</table>
<table id="recommend" cellspacing="0" summary="{LANG.recommnet}">
    <tr>
        <th scope="col" class="nobg">{LANG.request_more}</th>
        <th scope="col">{LANG.note}</th>
        <th scope="col">{LANG.result}</th>
    </tr>
    <tr>
        <th scope="row" class="spec">{LANG.supports_rewrite}</th>
        <td>{LANG.is_support}</td>
        <td><span class="{DATA_SUPPORT.class_supports_rewrite}">{DATA_SUPPORT.supports_rewrite}</span></td>
    </tr>
    <tr>
        <th scope="row" class="specalt">Extension Mbstring Support</th>
        <td class="alt">{LANG.is_support}</td>
        <td class="alt"><span class="{DATA_SUPPORT.class_mbstring_support}">{DATA_SUPPORT.mbstring_support}</span></td>
    </tr>
    <tr>
        <th scope="row" class="spec">Output Buffering</th>
        <td>{LANG.turnoff}</td>
        <td><span class="{DATA_SUPPORT.class_output_buffering}">{DATA_SUPPORT.output_buffering}</span></td>
    </tr>
    <tr>
        <th scope="row" class="specalt">Session Auto Start</th>
        <td class="alt">{LANG.turnoff}</td>
        <td class="alt"><span class="{DATA_SUPPORT.class_session_auto_start}">{DATA_SUPPORT.session_auto_start}</span></td>
    </tr>
    <tr>
        <th scope="row" class="spec">Display Errors</th>
        <td>{LANG.turnoff}</td>
        <td><span class="{DATA_SUPPORT.class_display_errors}">{DATA_SUPPORT.display_errors}</span></td>
    </tr>
    <tr>
        <th scope="row" class="specalt">Set_time_limit()</th>
        <td class="alt">{LANG.turnon}</td>
        <td class="alt"><span class="{DATA_SUPPORT.class_allowed_set_time_limit}">{DATA_SUPPORT.allowed_set_time_limit}</span></td>
    </tr>
    <tr>
        <th scope="row" class="spec">Zlib Compression Support</th>
        <td>{LANG.is_support}</td>
        <td><span class="{DATA_SUPPORT.class_zlib_support}">{DATA_SUPPORT.zlib_support}</span></td>
    </tr>
    <tr>
        <th scope="row" class="specalt">Extension Zip Support</th>
        <td class="alt">{LANG.is_support}</td>
        <td class="alt"><span class="{DATA_SUPPORT.class_zip_support}">{DATA_SUPPORT.zip_support}</span></td>
    </tr>
    <tr>
        <th scope="row" class="spec">Client URL Library (curl)</th>
        <td>{LANG.is_support}</td>
        <td><span class="{DATA_SUPPORT.class_curl_support}">{DATA_SUPPORT.curl_support}</span></td>
    </tr>
</table>
<ul class="control_t fr">
    <li><span class="back_step"><a
        href="{BASE_SITEURL}install/index.php?{LANG_VARIABLE}={CURRENTLANG}&amp;step=3&t={NV_CURRENTTIME}">{LANG.previous}</a></span></li>
    <!-- BEGIN: nextstep -->
    <li><span class="next_step"><a
        href="{BASE_SITEURL}install/index.php?{LANG_VARIABLE}={CURRENTLANG}&amp;step=5&t={NV_CURRENTTIME}">{LANG.next_step}</a></span></li>
    <!-- END: nextstep -->
</ul>
<!-- END: step -->