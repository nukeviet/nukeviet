<!-- BEGIN: main -->
<div class="row">
    <div class="col-md-push-5 col-md-14 col-lg-push-7 col-lg-10">
        <!-- BEGIN: result -->
        <div class="alert alert-info">{LANG.clear_success}</div>
        <!-- END: result -->
        <form method="post" action="{FORM_ACTION}" id="formClearStatistics" data-msg="{LANG.clear_confirm}">
            <input type="hidden" name="submit" value="1"/>
            <div class="list-group">
                <!-- BEGIN: clearalllang1 -->
                <div class="list-group-item">
                    <div class="clearfix">
                        {LANG.clear_alllang}
                        <div class="pull-right">
                            <input type="checkbox" name="alllang" value="1"{ALLLANG}/>
                        </div>
                    </div>
                </div>
                <!-- END: clearalllang1 -->
                <div class="list-group-item">
                    <div class="clearfix">
                        {LANG.clear_bot}
                        <input type="submit" name="bot" value="{LANG.clear_submit}" class="btn btn-primary btn-xs pull-right"/>
                    </div>
                </div>
                <div class="list-group-item">
                    <div class="clearfix">
                        {LANG.clear_browser}
                        <input type="submit" name="browser" value="{LANG.clear_submit}" class="btn btn-primary btn-xs pull-right"/>
                    </div>
                </div>
                <div class="list-group-item">
                    <div class="clearfix">
                        {LANG.clear_country}
                        <input type="submit" name="country" value="{LANG.clear_submit}" class="btn btn-primary btn-xs pull-right"/>
                    </div>
                </div>
                <div class="list-group-item">
                    <div class="clearfix">
                        {LANG.clear_os}
                        <input type="submit" name="os" value="{LANG.clear_submit}" class="btn btn-primary btn-xs pull-right"/>
                    </div>
                </div>
                <div class="list-group-item">
                    <div class="clearfix">
                        {LANG.clear_referer}
                        <input type="submit" name="referer" value="{LANG.clear_submit}" class="btn btn-primary btn-xs pull-right"/>
                    </div>
                </div>
                <div class="list-group-item">
                    <div class="clearfix">
                        {LANG.clear_hit}
                        <input type="submit" name="hit" value="{LANG.clear_submit}" class="btn btn-primary btn-xs pull-right"/>
                    </div>
                </div>
                <div class="list-group-item">
                    <div class="clearfix">
                        {LANG.clear_all}
                        <input type="submit" name="all" value="{LANG.clear_submit}" class="btn btn-primary btn-xs pull-right"/>
                    </div>
                </div>
            </div>
        </form>
        <!-- BEGIN: clearalllang2 -->
        <i class="fa fa-info-circle fa-fw"></i><em>{ALLLANG_MSG}</em>
        <!-- END: clearalllang2 -->
    </div>
</div>
<!-- END: main -->