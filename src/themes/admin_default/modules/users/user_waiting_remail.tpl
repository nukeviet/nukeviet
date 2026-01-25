<!-- BEGIN: main -->
<div class="panel panel-default">
    <div class="panel-body">
        <form action="#" method="post" class="form-inline" id="resend-email-form">
            <div class="form-group">
                <label>{LANG.userwait_resend_per_email}</label>
                <select class="form-control" name="per_email">
                    <option value="1">1</option>
                    <option value="5">5</option>
                    <option value="10" selected="selected">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
            <div class="form-group">
                <label>{LANG.userwait_resend_pause_time}</label>
                <select class="form-control" name="pause_time">
                    <option value="1">1 {GLANG.sec}</option>
                    <option value="5">5 {GLANG.sec}</option>
                    <option value="10">10 {GLANG.sec}</option>
                    <option value="20">20 {GLANG.sec}</option>
                    <option value="30" selected="selected">30 {GLANG.sec}</option>
                    <option value="60">60 {GLANG.sec}</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary"><span class="load hidden"><i class="fa fa-spin fa-spinner"></i> </span>{GLANG.submit}</button>
        </form>
    </div>
</div>
<p class="hidden" id="resend-perload"></p>
<pre class="hidden" id="resend-result"><code></code></pre>
<script type="text/javascript">
$(document).ready(function() {
    var resendOffset, emailOffset = 0;
    var emailDelete = '';
    var runInterval, per_email, pause_time;
    $('#resend-email-form').on('submit', function(e) {
        var $this = $(this);
        e.preventDefault();
        if ($this.data('busy')) {
            return false;
        }

        per_email = parseInt($('[name="per_email"]', $this).val());
        pause_time = parseInt($('[name="pause_time"]', $this).val());

        $this.data('busy', true);
        $('.load', $this).removeClass('hidden');
        $('select', $this).prop('disabled', true);

        $('#resend-perload').removeClass('hidden');
        $('#resend-result').removeClass('hidden');

        $('#resend-result').html('{LANG.userwait_resend_start} ' + getDisplayTime() + '<br />');

        resendOffset = 0;
        emailOffset = 0;
        emailDelete = ''
        resendEmailRun();
    });

    function resendEmailRun() {
        resendOffset--;
        if (resendOffset <= 0) {
            $('#resend-perload').html('{LANG.userwait_resend_run}. {LANG.userwait_resend_note}');

            if (runInterval) {
                clearInterval(runInterval);
            }

            $.ajax({
                type: 'POST',
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=user_waiting_remail&nocache=' + new Date().getTime(),
                data: {
                    ajax: 1,
                    per_email: per_email,
                    offset: emailOffset,
                    useriddel: emailDelete,
                    tokend: '{TOKEND}'
                },
                dataType: 'json',
                cache: false,
                success: function(data) {
                    if (data.messages.length > 0) {
                        $('#resend-result').prepend(data.messages.join('<br />') + '<br />');
                    }
                    if (!data.continue) {
                        $('#resend-result').prepend('{LANG.userwait_resend_end} ' + getDisplayTime() + '<br />');
                        $('#resend-perload').html('{LANG.userwait_resend_complete}');

                        var form = $('#resend-email-form');
                        form.data('busy', false);
                        $('.load', form).addClass('hidden');
                        $('select', form).prop('disabled', false);

                        return true;
                    }
                    emailDelete = data.useriddel;
                    resendOffset = pause_time;
                    emailOffset += per_email;
                    runInterval = setInterval(function() {
                        resendEmailRun();
                    }, 1000);
                },
                error: function(jqXHR, exception) {
                    $('#resend-result').prepend('Error Request: ' + exception + '<br />');
                }
            });

            return;
        }

        $('#resend-perload').html('{LANG.userwait_resend_counter} <strong>' + resendOffset + ' {GLANG.sec}</strong>. {LANG.userwait_resend_note}');
    }

    function getDisplayTime() {
        var time = new Date();
        var hh = time.getHours();
        var mm = time.getMinutes();
        var ss = time.getSeconds();
        if (hh < 10) {
            hh = '0' + hh;
        }
        if (mm < 10) {
            mm = '0' + mm;
        }
        if (ss < 10) {
            ss = '0' + ss;
        }
        return (hh + ':' + mm + ':' + ss);
    }
});
</script>
<!-- END: main -->
