<link rel="StyleSheet" href="{$CSS}">
<!-- START FORFOOTER -->
<script src="{$JS}"></script>
<div id="contactButton" class="box-shadow">
    <button type="button" class="ctb btn btn-primary btn-sm" data-module="{$MODULE}"><em class="fa fa-pencil-square-o"></em>{$LANG->getGlobal('feedback')}</button>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <button type="button" class="close">&times;</button>
            {$LANG->getGlobal('feedback')}
        </div>
        <div class="panel-body" data-cs="{$smarty.const.NV_CHECK_SESSION}"></div>
    </div>
</div>
<!-- END FORFOOTER -->
