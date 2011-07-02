<!-- BEGIN: main --> <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>{LANG.report}: {ROW.title}</title>
        <!-- BEGIN: close -->
        <script type="text/javascript">
            var howLong = 3000;
            setTimeout("self.close()", howLong);
        </script>
        <!-- END: close -->
        <style type="text/css">
            body {
                font-family: Arial, Tahoma, Verdana;
                font-size: 12px;
                line-height: 1.2;
            }
			.title{ color:#FF0000; font-size:13px; font-weight:bold}
			.success { color:#FF0000 }
			.tab1 { 
				width:100%; border:1px solid #F0F0F0
			}
			.tab1 tr { background:#EEEEEE }
			.tab1 tr td{ padding:5px;}
        </style>
        <script src="{NV_BASE_SITEURL}js/jquery/jquery.min.js" type="text/javascript"></script>
    </head>
    <body onload="self.focus()">
    	<span class="title">{LANG.report_notice}</span>
        <form method="post" action="{ROW.action}">
        <table class="tab1">
        	<tr>
            	<td>
                	<input type="radio" name="report" value="1" id="report_0" checked="checked" />{LANG.report_linkdie}
                    <input type="radio" name="report" value="2" id="report_1" />{LANG.report_badlink}
                </td>
            </tr>
            <tr>
            	<td>
                	<input type="checkbox"  value="1" id="others"/><strong>{LANG.report_note}</strong>
                </td>
            </tr>
            <tbody id="other_show" style="display:none">
            <tr>
            	<td>
                	<textarea rows="3" style="width:98%; font-size:12px; font-family:Arial" name="report_note" id="report_3">{ROW.report_note}</textarea>
                </td>
            </tr>
            </tbody>
            <tr>
            	<td>
                	<input type="hidden" name="report_id" value="{ROW.id}" />
                    <input type="hidden" name="link" value="{ROW.link}" />
                    <input type="submit" name="submit" value="{LANG.report_confirm}" style="text-align: center;"/>
                </td>
            </tr>
        </table>
        </form>
        <!-- BEGIN: success -->
        <p class="success">{LANG.report_success}</p>
        <!-- END: success -->
        <!-- BEGIN: error -->
        <p class="success">{ROW.error}</p>
        <!-- END: error -->
        <script type="text/javascript">
        	$('#others').click(function(){
				if ( $(this).attr('checked') ){
					$('#other_show').show();
				}else {
					$('#other_show').hide();
				}
			});
        </script>
        {SCRIPT_JS}
    </body>
</html>
<!-- END: main -->