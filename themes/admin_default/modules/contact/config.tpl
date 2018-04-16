<!-- BEGIN: main -->
<form action="{FORM_ACTION}" method="post">
    <div class="form-horizontal">
        <div class="row">
            <label class="col-sm-10 col-md-8 col-lg-6 control-label" for="sendcopymode"><strong>{LANG.config_sendcopymode}:</strong></label>
            <div class="col-sm-14 col-md-10 col-lg-8">
                <select class="form-control" name="sendcopymode" id="sendcopymode">
                    <!-- BEGIN: sendcopymode --><option value="{SENDCOPYMODE.key}"{SENDCOPYMODE.selected}>{SENDCOPYMODE.title}</option><!-- END: sendcopymode -->
                </select>
            </div>
        </div>
        <hr />
    </div>
    <div class="form-group">
        <label class="control-label"><strong>{LANG.content}:</strong></label>
        {DATA.bodytext}
    </div>
    <div class="form-horizontal">
        <div class="row">
            <div class="col-sm-24 col-md-18 col-lg-14 text-right">
                <input type="submit" name="submit" value="{LANG.save}" class="btn btn-primary"/>
            </div>
        </div>
    </div>
</form>
<!-- END: main -->