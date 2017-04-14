<!-- BEGIN: main -->
<form class="form-inline" action="" method="post">
	<div role="tabpanel">

		<!-- Nav tabs -->
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation" class="active">
				<a href="#payment_docpay" aria-controls="payment_guide" role="tab" data-toggle="tab">{LANG.document_payment_docpay}</a>
			</li>
			<li role="presentation">
				<a href="#payment_email_order" aria-controls="payment_email_notify" role="tab" data-toggle="tab">{LANG.document_payment_email_order}</a>
			</li>
			<li role="presentation">
				<a href="#payment_email_order_payment" aria-controls="messages" role="tab" data-toggle="tab">{LANG.document_payment_email_order_payment}</a>
			</li>
		</ul>

		<div class="tab-content">
			<div role="tabpanel" class="tab-pane active" id="payment_docpay">
				<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover">
						<tr>
							<td style="padding:10px"><strong>{LANG.setting_intro_pay}</strong>
							<br />
							<span style="font-style:italic">{LANG.document_payment_note}</span></td>
						</tr>
						<tr>
							<td>{content_docpay}</td>
						</tr>
					</table>
				</div>
			</div>
			<div role="tabpanel" class="tab-pane" id="payment_email_order">
				<div class="table-responsive">
					<table class="table table-bordered">
						<tr>
							<td>
								<strong>{LANG.document_payment_email_order_note}</strong>
								<br />
								<div class="row docpay">
									<!-- BEGIN: order_loop -->
									<div class="col-xs-8">
										<strong>{{ORDER.key}}</strong>{ORDER.value}
									</div>
									<!-- END: order_loop -->
								</div>
							</td>
						</tr>
						<tr>
							<td>{content_order}</td>
						</tr>
					</table>
				</div>
			</div>
			<div role="tabpanel" class="tab-pane" id="payment_email_order_payment">
				<div class="table-responsive">
					<table class="table table-bordered">
						<tr>
							<td>
								<strong>{LANG.document_payment_email_order_note}</strong>
								<br />
								<div class="row docpay">
									<!-- BEGIN: order_payment_loop -->
									<div class="col-xs-8">
										<strong>{{ORDER.key}}</strong>{ORDER.value}
									</div>
									<!-- END: order_payment_loop -->
								</div>
							</td>
						</tr>
						<tr>
							<td>{content_order_payment}</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="text-center">
		<input class="btn btn-primary" type="submit" value="{LANG.save}" name="Submit1" />
		<input type="hidden" value="1" name="saveintro">
	</div>
</form>
<!-- END: main -->