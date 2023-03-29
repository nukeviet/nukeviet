<!-- BEGIN: main -->
<!-- BEGIN: regular -->
<script type="text/javascript">
$(document).ready(function() {
    $("#openidResult", opener.document).attr("data-redirect", "{OPIDRESULT.redirect}").attr("data-result","{OPIDRESULT.status}").html('{OPIDRESULT.mess}<!-- BEGIN: success --><span class="load-bar"></span><!-- END: success -->').addClass("nv-info {OPIDRESULT.status}");
    $("#openidBt", opener.document).click();
    window.close();
});
</script>
<!-- END: regular -->
<!-- BEGIN: client -->
<script type="text/javascript">
var msg = {
    code: 'oauthback',
    redirect: '{OPIDRESULT.redirect}',
    status: '{OPIDRESULT.status}',
    mess: '{OPIDRESULT.mess}'
};
var redirect = '';
var post = false;
try {
    if (window.opener && window.opener.location.origin == window.location.origin) {
        redirect = '{OPIDRESULT.client}';
    } else {
        post = true;
    }
} catch(err) {
    post = true;
}
if (redirect) {
    window.location = redirect;
}
if (post) {
    window.opener.postMessage(msg, '{OPIDRESULT.client}');
    window.close();
}
</script>
<!-- END: client -->
<!-- END: main -->
