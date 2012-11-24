<!-- BEGIN: main -->
<table summary="CAPTION}" class="tab1">
<caption>
    {CAPTION}
</caption>
<col span="1" valign="top" />
<thead>
    <tr>
        <td>
            {THEAD0}
        </td>
        <td>
            {THEAD1}
        </td>
        <td>
            {THEAD2}
        </td>
    </tr>
</thead>
<!-- BEGIN: loop -->
<tbody{CLASS}>
<tr>
    <td>
        {KEY}
    </td>
    <!-- BEGIN: if -->
    <td>
        {VALUE}
    </td>
    <td>
        {VALUE}
    </td>
    <!-- END: if -->
    <!-- BEGIN: else -->
    <th>
        {VALUE0}
    </th>
    <th>
        {VALUE1}
    </th>
    <!-- END: else -->
</tr>
</tbody>
<!-- END: loop -->
</table>
<!-- END: main -->