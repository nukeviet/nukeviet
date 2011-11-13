<!-- BEGIN: main -->
<!-- BEGIN: hour -->
<table summary="{CTS.caption}" class="statistics">
    <caption> {CTS.caption}</caption>
    <tbody class="second">
        <tr>
            <!-- BEGIN: loop -->
            <td class="col1">
                <!-- BEGIN: img -->{M}
                <br/>
                <img alt="" src="{SRC}" height="{HEIGHT}" width="10" /><!-- END: img -->
            </td><!-- END: loop -->
        </tr>
    </tbody>
    <tbody>
        <tr>
            <!-- BEGIN: loop_1 -->
            <th class="head1">
                <!-- BEGIN: h --><strong><span style="text-decoration: underline;">{KEY}</span></strong>
                <!-- END: h --><!-- BEGIN: h_o -->{KEY}<!-- END: h_o -->
            </th>
            <!-- END: loop_1 -->
        </tr>
    </tbody>
    <tbody class="second">
        <tr>
            <td colspan="24" style="text-align: right;">
                {CTS.total.0}: <strong>{CTS.total.1}</strong>
            </td>
        </tr>
    </tbody>
</table>
<br/>
<!-- END: hour -->

<!-- BEGIN: day_k -->
<table summary="{CTS.caption}" class="statistics">
    <caption> {CTS.caption}</caption>
    <tbody class="second">
        <tr>
            <!-- BEGIN: loop -->
            <td class="col1">
                <!-- BEGIN: img -->{M.count}
                <br/>
                <img alt="" src="{SRC}" height="{HEIGHT}" width="10" /><!-- END: img -->
            </td><!-- END: loop -->
        </tr>
    </tbody>
    <tbody>
        <tr>
            <!-- BEGIN: loop_1 -->
            <th class="head1">
                <!-- BEGIN: dc --><strong><span style="text-decoration: underline;">{M.fullname}</span></strong>
                <!-- END: dc --><!-- BEGIN: dc_o -->{M.fullname}<!-- END: dc_o -->
            </th>
            <!-- END: loop_1 -->
        </tr>
    </tbody>
    <tbody class="second">
        <tr>
            <td colspan="7" style="text-align: right;">
                {CTS.total.0}: <strong>{CTS.total.1}</strong>
            </td>
        </tr>
    </tbody>
</table>
<br/>
<!-- END: day_k -->

<!-- BEGIN: day_m -->
<table summary="{CTS.caption}" class="statistics">
    <caption> {CTS.caption}</caption>
    <tbody class="second">
        <tr>
            <!-- BEGIN: loop -->
            <td class="col1">
                <!-- BEGIN: img -->{M}
                <br/>
                <img alt="" src="{SRC}" height="{HEIGHT}" width="10" /><!-- END: img -->
            </td><!-- END: loop -->
        </tr>
    </tbody>
    <tbody>
        <tr>
            <!-- BEGIN: loop_1 -->
            <th class="row1">
                <!-- BEGIN: dc --><strong><span style="text-decoration: underline;">{KEY}</span></strong>
                <!-- END: dc --><!-- BEGIN: dc_o -->{KEY}<!-- END: dc_o -->
            </th>
            <!-- END: loop_1 -->
        </tr>
    </tbody>
    <tbody class="second">
        <tr>
            <td colspan="{CTS.numrows}" style="text-align: right;">
                {CTS.total.0}: <strong>{CTS.total.1}</strong>
            </td>
        </tr>
    </tbody>
</table>
<br/>
<!-- END: day_m -->

<!-- BEGIN: month -->
<table summary="{CTS.caption}" class="statistics">
    <caption> {CTS.caption}</caption>
    <tbody class="second">
        <tr>
            <!-- BEGIN: loop -->
            <td class="col1">
                <!-- BEGIN: img -->{M.count}
                <br/>
                <img alt="" src="{SRC}" height="{HEIGHT}" width="10" /><!-- END: img -->
            </td><!-- END: loop -->
        </tr>
    </tbody>
    <tbody>
        <tr>
            <!-- BEGIN: loop_1 -->
            <th class="row1">
                <!-- BEGIN: mc --><strong><span style="text-decoration: underline;">{M.fullname}</span></strong>
                <!-- END: mc --><!-- BEGIN: mc_o -->{M.fullname}<!-- END: mc_o -->
            </th>
            <!-- END: loop_1 -->
        </tr>
    </tbody>
    <tbody class="second">
        <tr>
            <td colspan="12" style="text-align: right;">
                {CTS.total.0}: <strong>{CTS.total.1}</strong>
            </td>
        </tr>
    </tbody>
</table>
<br/>
<!-- END: month -->


<!-- BEGIN: year -->
<table summary="{CTS.caption}" class="statistics">
    <caption> {CTS.caption}</caption>
    <tbody class="second">
        <tr>
            <!-- BEGIN: loop -->
            <td class="col1">
                <!-- BEGIN: img -->{M}
                <br/>
                <img alt="" src="{SRC}" height="{HEIGHT}" width="10" /><!-- END: img -->
            </td><!-- END: loop -->
        </tr>
    </tbody>
    <tbody>
        <tr>
            <!-- BEGIN: loop_1 -->
            <th class="row1">
                <!-- BEGIN: yc --><strong><span style="text-decoration: underline;">{KEY}</span></strong>
                <!-- END: yc --><!-- BEGIN: yc_o -->{KEY}<!-- END: yc_o -->
            </th>
            <!-- END: loop_1 -->
        </tr>
    </tbody>
    <tbody class="second">
        <tr>
            <td colspan="12" style="text-align: right;">
                {CTS.total.0}: <strong>{CTS.total.1}</strong>
            </td>
        </tr>
    </tbody>
</table>
<br/>
<!-- END: year -->

<!-- BEGIN: ct -->
<table summary="{CTS.caption}" class="statistics">
    <caption> {CTS.caption}</caption>
    <tbody>
        <tr>
            <th colspan="2">{CTS.thead.0}</th>
            <th style="text-align: right;">{CTS.thead.1}</th>
            <th></th>
            <th>{CTS.thead.2}</th>
        </tr>
    </tbody>
    <!-- BEGIN: loop -->
    <tbody {CLASS}>
        <tr>
            <td>{VALUE.0}</td>
            <td>{KEY}</td>
            <td style="text-align: right;">{VALUE.1}</td>
            <td class="col2">
                <!-- BEGIN: img --><img alt="" src="{SRC}" height="10" width="{WIDTH}" /><!-- END: img -->
            </td>
            <td style="width: 250px;">{VALUE.2}</td>
        </tr>
    </tbody>
    <!-- END: loop --><!-- BEGIN: ot -->
    <tbody {CLASS}>
        <tr>
            <td>{CTS.others.0}</td>
            <td class="align_r">{CTS.others.1}</td>
            <td colspan="3">
                <a href="{URL}">{CTS.others.2}</a>
            </td>
        </tr>
    </tbody>
    <!-- END: ot -->
</table>
<br/>
<!-- END: ct --><!-- BEGIN: br -->
<table summary="{CTS.caption}" class="statistics">
    <caption> {CTS.caption}</caption>
    <tbody>
        <tr>
            <th>{CTS.thead.0}</th>
            <th style="text-align: right;">{CTS.thead.1}</th>
            <th></th>
            <th>{CTS.thead.2}</th>
        </tr>
    </tbody>
    <!-- BEGIN: loop -->
    <tbody {CLASS}>
        <tr>
            <td>{KEY}</td>
            <td style="text-align: right;">{VALUE.0}</td>
            <td class="col2">
                <!-- BEGIN: img --><img alt="" src="{SRC}" height="10" width="{WIDTH}" /><!-- END: loop -->
            </td>
            <td style="width: 250px;">{VALUE.1}</td>
        </tr>
    </tbody>
    <!-- END: loop --><!-- BEGIN: ot -->
    <tbody {CLASS}>
        <tr>
            <td>{CTS.others.0}</td>
            <td class="align_r">{CTS.others.1}</td>
            <td colspan="2">
                <a href="{URL}">{CTS.others.2}</a>
            </td>
        </tr>
    </tbody>
    <!-- END: ot -->
</table>
<br/>
<!-- END: br --><!-- BEGIN: os -->
<table summary="{CTS.caption}" class="statistics">
    <caption> {CTS.caption}</caption>
    <tbody>
        <tr>
            <th>{CTS.thead.0}</th>
            <th style="text-align: right;">{CTS.thead.1}</th>
            <th></th>
            <th>{CTS.thead.2}</th>
        </tr>
    </tbody>
    <!-- BEGIN: loop -->
    <tbody {CLASS}>
        <tr>
            <td>{KEY}</td>
            <td style="text-align: right;">{VALUE.0}</td>
            <td class="col2">
                <!-- BEGIN: img --><img alt="" src="{SRC}" height="10" width="{WIDTH}" /><!-- END: img -->
            </td>
            <td style="width: 250px;">{VALUE.1}</td>
        </tr>
    </tbody>
    <!-- END: loop --><!-- BEGIN: ot -->
    <tbody {CLASS}>
        <tr>
            <td>{CTS.others.0}</td>
            <td class="align_r">{CTS.others.1}</td>
            <td colspan="2">
                <a href="{URL}">{CTS.others.2}</a>
            </td>
        </tr>
    </tbody>
    <!-- END: ot -->
</table>
<!-- END: os -->
<!-- END: main -->
