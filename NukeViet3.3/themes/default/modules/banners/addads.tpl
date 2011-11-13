<!-- BEGIN: main -->
<!-- BEGIN: management -->
<div style="border:1px #ccc solid;padding:5px">
<h3 style="margin-bottom:15px"><strong>{LANG.tool_management}</strong></h3>
<div class="plan_title" style="float:left"><a href="{clientinfo_link}">{LANG.client_info}</a></div>
<div class="plan_title" style="float:left;margin-left:10px"><a href="{clientinfo_addads}">{LANG.client_addads}</a></div>
<div class="plan_title" style="float:left;margin-left:10px"><a href="{clientinfo_stats}">{LANG.client_stats}</a></div>
<div style="clear:both"></div>
</div>
<!-- END: management -->
<div id="clinfo" style="color:red;font-weight:bold">{pagetitle}{errorinfo}</div>
<div id="clinfo">
<form action="" method="post" enctype="multipart/form-data">
    <div class="act">
        <div class="value"><input type="text" name="title" value="" style="width:250px"/></div>{LANG.addads_title} (<span style="color:red">*</span>):
    </div>
    <div class="deact">
        <div class="value">
        <select name="block">
        	<!-- BEGIN: blockitem -->
        	<option value="{blockitem.id}">{blockitem.title}</option>
        	<!-- END: blockitem -->
        </select>
        </div>{LANG.addads_block} (<span style="color:red">*</span>):
    </div>
    <div class="act">
        <div class="value"><input type="file" name="image" value=""/></div>{LANG.addads_adsdata} (<span style="color:red">*</span>):
    </div>
    <div class="deact">
        <div class="value"><input type="text" name="description" value="" style="width:250px"/></div>{LANG.addads_description}:
    </div>
    <div class="act">
        <div class="value"><input type="text" name="url" value="" style="width:250px"/></div>{LANG.addads_url}:
    </div>
    <div class="deact">
        <div class="value"><input type="text" readonly="readonly" maxlength="10" style="width: 150px;" value="" id="begintime" name="begintime"> <img height="17" alt="" onclick="popCalendar.show(this, 'begintime', 'dd.mm.yyyy', false);" style="cursor: pointer; vertical-align: middle;" src="{NV_BASE_SITEURL}images/calendar.jpg"></div>{LANG.addads_timebegin}:
    </div>
    <div class="act">
        <div class="value"><input type="text" readonly="readonly" maxlength="10" style="width: 150px;" value="" id="endtime" name="endtime"> <img height="17" alt="" onclick="popCalendar.show(this, 'endtime', 'dd.mm.yyyy', false);" style="cursor: pointer; vertical-align: middle;" src="{NV_BASE_SITEURL}images/calendar.jpg"></div>{LANG.addads_timeend}:
    </div>
    <div class="deact" style="text-align:center">
        <input type="submit" name="confirm" value="{LANG.addads_confirm}"/>
    </div>
</form>
</div>
<!-- END: main -->