<!-- BEGIN: main -->
<div class="panel-body">
	<!-- BEGIN: error -->
	<div class="alert alert-danger">{ERROR}</div>
	<!-- END: error -->
	<!-- BEGIN: data -->
	<ul class="nav nav-tabs" id="tabs">
		<li class="active"><a href="#home" data-toggle="tab">Home</a></li>
		<li><a href="#profile" data-toggle="tab">Profile</a></li>
		<li><a href="#messages" data-toggle="tab">Messages</a></li>
		<li><a href="#settings" data-toggle="tab">Settings</a></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="home">...</div>
		<div class="tab-pane" id="profile">...</div>
		<div class="tab-pane" id="messages">...</div>
		<div class="tab-pane" id="settings">...</div>
	</div>
	<script type="text/javascript">
	$('#tabs a').click(function(e){
		e.preventDefault()
		$(this).tab('show')
	});	
	</script>
	<!-- END: data -->
</div>
<!-- END: main -->