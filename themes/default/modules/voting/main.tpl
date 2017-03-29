<!-- BEGIN: main -->
<div class=" col-md-12 col-sm-12 voting-col-1">
<!-- BEGIN: loop -->
<div class="page panel panel-default">
    <div class="panel-body">
        <form action="">
        	<h3>{VOTING.question}</h3>
        	<fieldset>
        		<!-- BEGIN: resultn -->
        		<div class="checkbox">
        		<label>
        			<input type="checkbox" name="option[]" value="{RESULT.id}" onclick="return nv_check_accept_number(this.form,'{VOTING.accept}','{VOTING.errsm}')">
        			{RESULT.title}
        		</label>
        		</div>
        		<!-- END: resultn -->
        		<!-- BEGIN: result1 -->
        		<div class="radio">
        			<label>
        		    	<input type="radio" name="option"  value="{RESULT.id}">
        		    	{RESULT.title}
        			</label>
        		</div>
        		<!-- END: result1 -->
        		<div class="clearfix">
        			<input class="btn btn-success btn-sm" type="button" value="{VOTING.langsubmit}" onclick="nv_sendvoting(this.form, '{VOTING.vid}', '{VOTING.accept}', '{VOTING.checkss}', '{VOTING.errsm}');" />
                    <input class="btn btn-primary btn-sm" type="button" value="{VOTING.langresult}" onclick="nv_sendvoting(this.form, '{VOTING.vid}', 0, '{VOTING.checkss}', '');"/>
        		</div>
        	</fieldset>
        </form>
    </div>
</div>
<!-- BEGIN: has_captcha -->
<div id="voting-modal-{VOTING.vid}" class="hidden">
    <div class="clearfix">
        <!-- BEGIN: basic -->
        <div class="m-bottom">
            <strong>{LANG.enter_captcha}</strong>
        </div>
        <div class="clearfix">
            <div class="margin-bottom">
                <div class="row">
                    <div class="col-xs-12">
                        <input type="text" class="form-control rsec" value="" name="captcha" maxlength="{GFX_MAXLENGTH}"/>
                    </div>
                    <div class="col-xs-12">
                        <img class="captchaImg display-inline-block" src="{SRC_CAPTCHA}" height="32" alt="{N_CAPTCHA}" title="{N_CAPTCHA}" />
        				<em class="fa fa-pointer fa-refresh margin-left margin-right" title="{CAPTCHA_REFRESH}" onclick="change_captcha('.rsec');"></em>
                    </div>
                </div>
            </div>
        </div>
        <!-- END: basic -->
        <!-- BEGIN: recaptcha -->
        <div class="m-bottom text-center">
            <strong>{N_CAPTCHA}</strong>
        </div>
        <div class="margin-bottom clearfix">
            <div class="nv-recaptcha-default"><div id="{RECAPTCHA_ELEMENT}" data-toggle="recaptcha"></div></div>
            <script type="text/javascript">
            nv_recaptcha_elements.push({
                id: "{RECAPTCHA_ELEMENT}",
                btn: $('[type="submit"]', $('#{RECAPTCHA_ELEMENT}').parent().parent().parent()),
                pnum: 3,
                btnselector: '[name="submit"]'
            })
            </script>
        </div>
        <!-- END: recaptcha -->
        <input type="button" name="submit" class="btn btn-primary btn-block" value="{VOTING.langsubmit}" onclick="nv_sendvoting_captcha(this, {VOTING.vid}, '{LANG.enter_captcha_error}');"/>
    </div>
</div>
<!-- END: has_captcha -->
<!-- END: loop -->
	<div class="margin">
	    <!-- BEGIN: note -->
	    <div class="alert alert-info">{VOTINGNOTE}</div>
	    <!-- END: note -->
	    <h3 class="text-primary text-center margin-bottom-lg">{LANG.voting_result}</h3>
	    <!-- BEGIN: result -->
	    <div class="row">
	        <div class="col-xs-24 col-md-12">{VOTING.title}</div>
	        <div class="col-xs-24 col-md-12">
	            <div class="progress">
	                <div class="progress-bar" role="progressbar" aria-valuenow="{WIDTH}" aria-valuemin="0" aria-valuemax="100" style="width: {WIDTH}%;"><span class="text-danger">{WIDTH}%</span></div>
	            </div>
	        </div>
	    </div>
		<!-- END: result -->
	    <p class="text-center">
	        <strong>{LANG.voting_total}</strong>: {TOTAL} {LANG.voting_counter} - <strong>{LANG.voting_pubtime}: </strong>{VOTINGTIME}
	    </p>
	</div>
</div>
<div class="col-md-12 col-sm-12 voting-col-2">
		<div class="col-sm-24 float-voting">

			<ul class="nav nav-tabs">
			  <li class="active"><a data-toggle="tab" href="#home">{LANG.voting_pro}</a></li>
			  <li><a data-toggle="tab" href="#menu1">{LANG.voting_hits_hot}</a></li>
			  <li><a data-toggle="tab" href="#menu2">{LANG.voting_hot}</a></li>
			</ul>
		</div>
		<div class="col-sm-24 padding-voting">
			<div class="tab-content">
				  <div id="home" class="tab-pane fade in active">
				  	<ul>
				  		<!-- BEGIN: loopvotingnew -->
				  		<li>
				  		<span>
				    		<a href="{LINKNEW}">{TITILENEW}</a>
				    	</span>
				  		</li>
				    	<!-- END: loopvotingnew -->
				  	</ul>
				  </div>
				  <div id="menu1" class="tab-pane fade">
				    <ul>
				  		<!-- BEGIN: loopvotinghithot -->
				  		<li>
				  		<span>
				    		<a href="{LINKNEW}">{TITILENEW}</a>
				    	</span>
				  		</li>
				    	<!-- END: loopvotinghithot -->
				  	</ul>
				  </div>
				  <div id="menu2" class="tab-pane fade">
				    <ul>
				  		<!-- BEGIN: loopvotinghot -->
				  		<li>
				  		<span>
				    		<a href="{LINKNEW}">{TITILENEW}</a>
				    	</span>
				  		</li>
				    	<!-- END: loopvotinghot -->
				  	</ul>
				  </div>
			</div>
		</div>
	</div>
<!-- END: main -->