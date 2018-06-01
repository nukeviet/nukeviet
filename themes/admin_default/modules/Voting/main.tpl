<!-- BEGIN: main -->
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th class="w100">{LANG.voting_id}</th>
                <th>{LANG.voting_title}</th>
                <th class="w200">{LANG.voting_stat}</th>
                <th class="w100">{LANG.voting_active}</th>
                <th class="w250 text-center">{LANG.voting_func}</th>
            </tr>
        </thead>
        <tbody>
            <!-- BEGIN: loop -->
            <tr>
                <td class="text-center">{ROW.vid}</td>
                <td>{ROW.question}</td>
                <td>{ROW.totalvote} {LANG.voting_totalcm}</td>
                <td class="text-center">{ROW.status}</td>
                <td class="text-center">
                    <a href="#" class="btn btn-default btn-xs" data-toggle="viewresult" data-vid="{ROW.vid}" data-checkss="{ROW.checksess}" data-title="{LANG.voting_result}">{LANG.voting_result}</a>
                    <a href="{ROW.url_edit}" class="btn btn-default btn-xs"><i class="fa fa-fw fa-edit"></i>{GLANG.edit}</a>
                    <a class="btn btn-danger btn-xs" href="javascript:void(0);" onclick="nv_del_content({ROW.vid}, '{ROW.checksess}')"><i class="fa fa-fw fa-trash"></i>{GLANG.delete}</a>
                </td>
            </tr>
            <!-- END: loop -->
        </tbody>
    </table>
</div>
<!-- END: main -->