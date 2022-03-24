<!-- BEGIN: main -->
<button type="button" class="btn btn-primary active btn-xs text-black" title="{LANG.viewstats}" data-toggle="ftip" data-target=".view-stats" data-click="y"><em class="fa fa-eye"></em>&nbsp;{LANG.online}: {COUNT_ONLINE}</button>
<div class="view-stats hidden">
<ul class="counter list-none display-table">
	<li><span><em class="fa fa-eye fa-lg fa-horizon"></em>{LANG.online}</span><span>{COUNT_ONLINE}</span></li>
	<!-- BEGIN: users --><li><span><em class="fa fa-user fa-lg fa-horizon"></em>{LANG.users}</span><span>{COUNT_USERS}</span></li><!-- END: users -->
	<!-- BEGIN: bots --><li><span><em class="fa fa-magic fa-lg fa-horizon"></em>{LANG.bots}</span><span>{COUNT_BOTS}</span></li><!-- END: bots -->
	<!-- BEGIN: guests --><li><span><em class="fa fa-bullseye fa-lg fa-horizon"></em>{LANG.guests}</span><span>{COUNT_GUESTS}</span></li><!-- END: guests -->
    <li><span><em class="icon-today icon-lg icon-horizon margin-top-lg"></em>{LANG.today}</span><span class="margin-top-lg">{COUNT_DAY}</span></li>
	<li><span><em class="fa fa-calendar-o fa-lg fa-horizon"></em>{LANG.current_month}</span><span>{COUNT_MONTH}</span></li>
	<li><span><em class="fa fa-bars fa-lg fa-horizon"></em>{LANG.hits}</span><span>{COUNT_ALL}</span></li>
</ul>
</div>
<!-- END: main -->