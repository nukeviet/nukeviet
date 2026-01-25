<!-- BEGIN: main -->
<!-- BEGIN: isUnfollowers -->
<div class="table-responsive">
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>{LANG.user_id}</th>
                <th>{LANG.display_name}</th>
                <th>{LANG.user_gender}</th>
                <th class="text-center">{LANG.updatetime}</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <!-- BEGIN: follower -->
            <tr>
                <td class="text-nowrap" style="width:5%">{UNFOLLOWER.user_id}</td>
                <td>{UNFOLLOWER.display_name}</td>
                <td>{UNFOLLOWER.user_gender}</td>
                <td class="text-nowrap text-right" style="width:5%">{UNFOLLOWER.updatetime_format}</td>
                <td class="text-nowrap text-right" style="width:5%">
                    <button type="button" class="btn btn-primary btn-xs" data-toggle="follower_details" data-user-id="{UNFOLLOWER.user_id}">{LANG.details}</button>
                </td>
            </tr>
            <!-- END: follower -->
        </tbody>
        <!-- BEGIN: generate_page -->
        <tfoot>
            <tr>
                <td colspan="5" class="text-center">
                    {GENERATE_PAGE}
                </td>
            </tr>
        </tfoot>
        <!-- END: generate_page -->
    </table>
</div>
<!-- END: isUnfollowers -->
<!-- END: main -->