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
                margin: 0;
                padding: 0;
            }
            
            #wrapper {
                width: 400px;
                height: 400px;
                margin: 10px auto;
            }
            
            #report {
                margin: 20px auto;
                width: 300px;
            }
            
            #success {
                color: #ff0000;
            }
            
            #success p {
                padding: 10px 20px;
            }
            
            #report .row {
                height: 20px;
                line-height: 20px;
            }
            
            h1 {
                font-size: 120%;
                margin: 0;
                padding: 0;
            }
            
            form {
                text-align: left;
            }
            
            input {
                float: left;
            }
            
            label {
                float: left;
                padding: 0 10px;
                text-align: left;
                width: 200px;
            }
        </style>
    </head>
    <body onload="self.focus()">
        <div id="wrapper">
            <h1 style="color:#ff0000;text-align:center;">{LANG.report_notice}</h1>
            <form method="post" action="{ROW.action}">
                <div id="report">
                    <div class="row">
                        <input type="radio" name="report" value="1" id="report_0" checked="checked" />
                        <label>{LANG.report_linkdie}</label>
                    </div>
                    <div style="clear: both;"></div>
                    <div class="row">
                        <input type="radio" name="report" value="2" id="report_1" />
                        <label>{LANG.report_badlink}</label>
                    </div>
                    <div style="clear: both;"></div>
                    <div>{LANG.report_note}</div>
                    <div>
                        <textarea rows="3" cols="30" name="report_note" id="report_3">{ROW.report_note}</textarea>
                    </div>
                    <div style="clear: both;"></div>
                    <div class="row" style="text-align: center;">
                        <input type="hidden" name="report_id" value="{ROW.id}" /><input type="hidden" name="link" value="{ROW.link}" /><input type="submit" name="submit" value="{LANG.report_confirm}" style="text-align: center;"/>
                    </div>
                </div>
            </form><!-- BEGIN: success -->
            <div id="success">
                <p>{LANG.report_success}</p>
            </div>
            <!-- END: success -->
            <!-- BEGIN: error -->
            <div id="success">
                <p>{ROW.error}</p>
            </div>
            <!-- END: error -->
        </div>
        {SCRIPT_JS}
    </body>
</html>
<!-- END: main -->
