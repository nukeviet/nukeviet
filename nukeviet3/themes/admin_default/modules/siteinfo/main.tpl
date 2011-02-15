<!-- BEGIN: main -->
	<!-- BEGIN: main1 -->
		<table class="tab1">
		    <caption>
		        {CAPTION}
		    </caption>
		    <col valign="top" width="20%" />
		    <col valign="top" />
		    <col valign="top" width="10%" />
		    <thead>
		        <tr>
		            <td>
		                {LANG.moduleName}
		            </td>
		            <td>
		                {LANG.moduleContent}
		            </td>
		            <td style="text-align:right">
		                {LANG.moduleValue}
		            </td>
		        </tr>
		    </thead>
			<!-- BEGIN: loop -->
		    <tbody {CLASS}>
		        <tr>
		            <td>
		                {MODULE}
		            </td>
		            <td>
		                {KEY}
		            </td>
		            <td style="text-align:right">
		                {VALUE}
		            </td>
		        </tr>
		    </tbody>
		    <!-- END: loop -->
		</table>
	<!-- END: main1 -->
	
	<!-- BEGIN: main2 -->
		<table class="tab1">
		    <caption>
		        {CAPTION} <span style="font-weight:400">(<a href="{ULINK}">{CHECKVERSION}</a>)</span>
		    </caption>
		    <thead>
		        <tr>
		            <td>
		                {LANG.moduleContent}
		            </td>
		            <td style="text-align:right">
		                {LANG.moduleValue}
		            </td>
		        </tr>
		    </thead>
			<!-- BEGIN: loop -->
		    <tbody {CLASS}>
		        <tr>
		            <td>
		                {KEY}
		            </td>
		            <td style="text-align:right">
		                {VALUE}
		            </td>
		        </tr>
		    </tbody>
		    <!-- END: loop -->
		</table>
		<!-- BEGIN: inf -->
			<div class="newVesionInfo">{INFO}</div>
		<!-- END: inf -->
	<!-- END: main2 -->
<!-- END: main -->