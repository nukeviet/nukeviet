<!-- BEGIN: main -->
<table class="tab1">
<caption>
    {MODULE}
</caption>
<col span="1" valign="top" width="40%" />
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
    <td colspan="2">
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