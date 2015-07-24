<!-- BEGIN: main -->
<button type="button" class="btn btn-primary active btn-xs text-black" title="{LANG.viewstats}" data-toggle="ftip" data-target=".view-stats" data-click="y"><em class="fa fa-eye"></em>&nbsp;{LANG.online}: {COUNT_ONLINE}</button>
<div class="view-stats hidden">
<ul class="nv-list-item">
	<li><em class="fa fa-bolt fa-lg">&nbsp;</em> {LANG.online} <span class="pull-right">&nbsp; {COUNT_ONLINE}</span></li>
	<!-- BEGIN: users --><li><em class="fa fa-user fa-lg">&nbsp;</em> {LANG.users} <span class="pull-right">&nbsp; {COUNT_USERS}</span></li><!-- END: users -->
	<!-- BEGIN: bots --><li><em class="fa fa-magic fa-lg">&nbsp;</em> {LANG.bots} <span class="pull-right">&nbsp; {COUNT_BOTS}</span></li><!-- END: bots -->
	<!-- BEGIN: guests --><li><em class="fa fa-bullseye fa-lg">&nbsp;</em> {LANG.guests} <span class="pull-right">&nbsp; {COUNT_GUESTS}</span></li><!-- END: guests -->
</ul>
<div class="nv-hr">&nbsp;</div>
<ul class="nv-list-item">
	<li><em class="fa fa-filter fa-lg">&nbsp;</em> {LANG.today} <span class="pull-right">&nbsp; {COUNT_DAY}</span></li>
	<li><em class="fa fa-calendar-o fa-lg">&nbsp;</em> {LANG.current_month} <span class="pull-right">&nbsp; {COUNT_MONTH}</span></li>
	<li><em class="fa fa-bars fa-lg">&nbsp;</em> {LANG.hits} <span class="pull-right">&nbsp; {COUNT_ALL}</span></li>
</ul>
</div>
<!-- END: main -->