<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" prefix="og: http://ogp.me/ns#">

<head>
    <title>{$LANG->getGlobal('flood_page_title')}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta http-equiv="expires" content="0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <style>
    {literal}body{padding-top:20px;margin:0;font-family:system-ui,-apple-system,"Segoe UI",Roboto,"Helvetica Neue",Arial,"Noto Sans","Liberation Sans",sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji";font-size:16px;font-weight:400;line-height:1.5;color:#212529;background-color:#6c757d}.wrap{display:flex;justify-content:center}.card{width:90%;max-width:500px;text-align:center;border-radius:5px;border:1px solid #dee2e6;background-color:#fff}@media (min-width:768px){.card{width:450px}}.card-body{padding:15px 20px}.card-footer{padding:10px 20px;border-bottom-left-radius:5px;border-bottom-right-radius:5px}.img{margin-bottom:10px}.title{font-size:16px;margin-bottom:8px}.message{margin-bottom:6px}.g-recaptcha{display:flex;justify-content:center}.img-color-1{fill:#495057;fill-rule:nonzero}.img-color-2{fill:#dc3545;fill-rule:nonzero}.loader{display:block;position:relative;height:16px;width:100%;background:#e9ecef;border-radius:10px;overflow:hidden}.loader::before{content:'';background:#dc3545;position:absolute;left:0;top:0;width:0;height:100%;animation:loading 10s linear infinite}.loader:after{content:'';position:absolute;left:0;top:0;width:100%;height:100%;text-align:center;font-size:12px;letter-spacing:1px;line-height:16px;font-weight:700;color:#6c757d;text-shadow:1px 1px 0 #fff,1px -1px 0 #fff,-1px 1px 0 #fff,-1px -1px 0 #fff,1px 0 0 #fff,0 1px 0 #fff,-1px 0 0 #fff,0px -1px 0 #fff,1px 1px 1px rgba(255,255,255,0);animation:percentage 10s linear infinite}@keyframes loading{0%{width:0}100%{width:100%}}@keyframes percentage{0%{content:"0%"}5%{content:"5%"}10%{content:"10%"}20%{content:"20%"}30%{content:"30%"}40%{content:"40%"}50%{content:"50%"}60%{content:"60%"}70%{content:"70%"}80%{content:"80%"}90%{content:"90%"}95%{content:"95%"}96%{content:"96%"}97%{content:"97%"}98%{content:"98%"}99%{content:"99%"}100%{content:"100%"}}{/literal}
    .loader::before,
    .loader::after {
        animation-duration: {$FLB->flood_block_time}s;
    }
    </style>
</head>

<body>
    <div class="wrap">
        <div class="card">
            <div class="card-body">
                <div class="img">
                    <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" style="shape-rendering:geometricPrecision;text-rendering:geometricPrecision;image-rendering:optimizeQuality;fill-rule:evenodd;clip-rule:evenodd" viewBox="0 0 4999.88 5000" width="80" height="80">
                        <path d="M2224.41 2229.73h-318.97V1313.6c0-33.69 9.97-66.12 27.06-92.73 3.34-5.41 7.08-10.82 10.82-15.42 4.15-5.37 8.34-9.93 12.49-14.12l.41-.41c12.9-12.9 27.91-23.72 44.92-32.43l.85-.45 8.34-3.74.85-.41c20.39-8.71 43.25-13.71 66.98-13.71h.82c47.85 0 91.51 19.57 122.68 50.74 12.9 12.86 23.68 27.87 32.47 44.92l.37.85 4.19 8.3.45.85c8.71 20.34 13.71 43.21 13.71 66.89V2230.16l-28.44-.43zm-674.15 121.87-6.23 6.27-49.48 46.18v-796.02c0-33.69 10.01-65.71 27.06-92.69 3.34-5.45 7.08-10.42 10.82-15.38 4.15-5.45 8.34-10.01 12.49-14.16l.37-.41c12.9-12.9 28.32-24.13 44.92-32.47l.81-.37 8.34-4.15.85-.45c20.34-8.71 43.25-13.71 66.98-13.71l.87.01c47.77 0 91.47 19.57 122.64 50.74 12.9 12.9 24.13 28.32 32.43 44.92l.45.85 3.7 8.34.45.85c8.71 20.34 13.71 43.21 13.71 66.93V2229.11l-27.02 1.67c-73.2 4.6-140.99 28.28-197.92 66.12-11.23 7.49-22.5 15.79-32.88 24.09-9.6 7.89-19.98 17.05-29.95 27.47l-3.41 3.14zm1505.89 495.72 29.09 10.82 12.49 9.2c2.08 2.89 4.56 5.41 7.89 7.53l3.74 2.03v.45c4.19 2.08 8.34 2.89 12.86 2.89h.86c4.19 0 7.89-.85 11.68-2.08l2.89-1.26 2.89-1.63.85-.41.85-.45c12.86-7.53 29.95-20.43 48.62-35.77 19.16-15.83 40.77-35.36 61.97-55.3 41.99-39.55 89.44-86.95 108.52-106.08l.85-.81c60.3-60.3 121.42-88.17 175.49-91.51 10.82-.85 22.05-.41 31.98.85 10.42 1.22 20.83 3.3 30.4 6.18l.46.02 1.18.45h.45c19.16 6.23 37.03 15.83 52.82 28.69l.85.85 7.89 5.86 1.26 1.22 7.04 7.08 1.22 1.26.45.37.45.45c13.31 14.53 24.13 32.02 31.98 51.59l.45.85 3.74 9.97.45 2.08 2.89 10.38.41 1.26.45 1.26c13.71 56.56 2.48 125.57-44.92 194.62-8.75 12.86-19.16 26.24-30.8 39.1-11.23 12.86-24.17 25.8-38.66 38.66l-2.12 1.67-2.48 2.12-1.67 1.63-.85.81-583.9 589.72-20.39 22.05-1.22-1.22c-32.02 29.09-63.64 54.48-95.7 76.09-38.21 25.8-76.5 46.59-116.41 61.97-40.32 15.79-82.32 27.42-126.83 34.87-44.51 7.08-91.47 10.82-141.4 10.82H2128.7c-126.02 0-243.28-36.99-341.83-100.63-18.68-12.08-37.39-25.8-55.3-40.36-17.86-14.53-34.91-29.91-50.33-45.74l-.37-.41c-47.85-48.66-88.22-105.22-118.98-167.19-5.86-12.45-11.64-24.94-17.01-36.95-32.47-76.99-50.33-161.86-50.33-250v-499.91c0-45.74 8.71-90.29 23.68-130.61v-.45c2.93-7.49 6.27-15.34 9.6-23.23 3.74-7.57 7.49-15.42 11.19-22.46l.45-.45.85-1.26.45-.81c14.53-23.27 32.43-44.92 51.92-64.05l.45-.45 10.82-10.42.85-.81 11.23-9.52.81-.85 1.3-.81c25.35-20.43 53.22-37.48 83.58-50.33 7.08-2.93 13.71-5.86 17.86-7.08 36.99-13.71 77.35-20.79 119.38-20.79l.88-.02h15.34l7.08.85 6.27 1.67 4.96.41H2736.85c60.3 0 117.67 15.38 167.6 42.4 9.97 5.41 19.49 11.23 28.24 17.09h.45c8.71 5.78 17.46 12.45 26.2 19.53l.45.41.41.41c43.25 35.32 78.61 81.09 101.07 133.1 4.56 10.82 8.71 21.2 12.04 31.21 3.74 10.82 6.18 21.61 9.16 32.43v.45l.41 1.63.45 2.52c.45 2.08.45 3.78.85 5.41l.41.85.85 2.52.45 1.22 2.89 8.75 1.26 15.38v.85l.37 6.23v.85l.45 10.82v152.3l-37.43-12.53c-58.59-19.12-114.34-32.43-167.15-39.51-12.08-1.63-23.23-2.89-33.69-4.19-10.82-.81-22.01-2.03-33.28-2.48l-24.09-.85c-67.79-.85-130.61 8.75-185.87 27.87-12.04 4.23-23.72 8.75-33.65 12.9-10.86 4.52-21.65 9.97-31.62 15.34l-17.05 9.6c-53.26 31.98-96.92 74.83-130.61 127.24-7.08 10.86-13.26 21.2-18.72 31.62-5.82 10.78-10.82 22.46-16.24 34.1l-.37.81c-16.24 38.7-27.87 81.14-34.55 126.87-1.22 10.38-2.89 20.79-3.74 30.31-.85 7.89-1.67 16.64-2.08 25.43l63.19.81c2.12-32.02 6.67-62.83 13.71-91.47 2.08-8.75 4.15-17.05 6.27-24.58 2.12-7.08 4.56-15.38 7.89-23.27l7.45-19.53c21.24-50.74 52-93.59 90.29-128.05 7.53-6.67 15.83-12.86 23.72-19.16 8.75-6.23 17.01-12.04 25.35-17.05l.45-.41.85-.45c40.28-24.13 87.69-41.18 140.5-49.93 10.86-1.63 22.54-3.3 34.14-4.19 11.64-1.22 23.72-1.67 35.32-2.03H2794.47c54.89.81 113.93 9.11 177.12 25.31 14.53 3.74 28.28 7.53 40.77 11.68 14.53 4.56 28.69 9.16 41.99 13.71l1.8 1.18zm-1187.33-541.86zm773.47-75.73H2323.4V1037.48c0-47.85 19.53-91.51 50.74-122.72 12.9-12.86 28.28-24.09 44.88-32.39l.85-.45 8.34-4.19.85-.37c20.39-8.71 43.25-13.71 66.98-13.71v-.45l.87.01v.45c47.85 0 91.51 19.49 122.68 50.74 12.9 12.9 23.72 27.87 32.47 44.88l.41.85 3.74 8.3.45.81c8.71 20.39 13.71 43.29 13.71 66.98v1193.51h-28.08zm128.58 1.3-5.45-.41-26.57-2.12V1316.07c0-47.85 19.57-91.51 50.74-122.68 12.86-12.9 28.28-23.72 44.92-32.47l.85-.37 8.3-3.74.85-.45c20.34-8.71 43.21-13.71 66.93-13.71l.87.01c33.65 0 65.67 9.97 92.69 27.02l.45.37c5.37 2.93 10.38 7.08 14.97 10.82l.41.45c4.96 3.74 9.56 8.3 14.16 12.49l.41.41c12.9 12.9 24.13 28.24 32.47 44.92l.45.81 4.11 8.3.45.85c8.71 20.34 13.71 43.25 13.71 66.93V2405.94l-49.03-48.14-5.41-5.41c-49.11-49.11-110.23-86.1-178.42-106.04-13.75-4.15-27.47-7.49-41.22-9.56-12.95-2.87-27.11-4.54-41.64-5.76z" class="img-color-1"/>
                        <path d="M2499.97 0C1121.48 0 0 1121.54 0 2500.12.14 3878.56 1121.62 5000 2499.97 5000c1378.46 0 2499.91-1121.45 2499.91-2499.91C4999.88 1121.54 3878.43 0 2499.97 0zm0 455.47c481.8 0 925.11 167.64 1274.94 447.53L902.96 3774.93c-279.83-349.8-447.44-793.08-447.49-1274.82 0-1127.42 917.16-2044.64 2044.5-2044.64zm0 4089.06c-481.78 0-925.13-167.64-1274.98-447.53l2871.99-2871.91c279.83 349.85 447.44 793.19 447.44 1275.01-.01 1127.3-917.14 2044.43-2044.45 2044.43z" class="img-color-2"/>
                    </svg>
                </div>
                <div class="title"><strong>{$LANG->getGlobal('flood_info1')}</strong></div>
                <div class="message">{$LANG->getGlobal('flood_info2')} <span id="secField">{$FLB->flood_block_time}</span> {$LANG->getGlobal('sec')}...</div>
                {if $CAPTCHA_PASS}
                <form method="post" action="{NV_BASE_SITEURL}" id="formPassFlood">
                    {if $GCONFIG.recaptcha_ver eq 2}
                    <p>{$LANG->getGlobal('flood_captcha_pass')}.</p>
                    <script src="https://www.google.com/recaptcha/api.js?hl={$smarty.const.NV_LANG_INTERFACE}&amp;onload=buildRecaptcha&amp;render=explicit" async defer></script>
                    <script type="text/javascript">
                    function buildRecaptcha() {
                        grecaptcha.render('g-recaptcha', {
                            'sitekey' : '{$GCONFIG.recaptcha_sitekey}',
                            'callback' : function(code) {
                                if (code) {
                                    document.getElementById("formPassFlood").submit();
                                }
                            },
                            'type' : '{$GCONFIG.recaptcha_type}'
                        });
                    }
                    </script>
                    <div class="g-recaptcha" id="g-recaptcha"></div>
                    {elseif $GCONFIG.recaptcha_ver eq 3}
                    <a href="javascript: void(0);" onclick="formSubmit(event)">{$LANG->getGlobal('flood_continue_access')}</a>
                    <script src="https://www.google.com/recaptcha/api.js?hl={$smarty.const.NV_LANG_INTERFACE}&amp;render={$GCONFIG.recaptcha_sitekey}"></script>
                    <script>
                    function formSubmit(e) {
                        e.preventDefault();
                        var form = document.getElementById("formPassFlood");
                        grecaptcha.ready(function () {
                            grecaptcha.execute('{$GCONFIG.recaptcha_sitekey}', { action: 'floodBlockerPage' }).then(function (token) {
                                var el = document.createElement("input");
                                el.type = "hidden";
                                el.name = "g-recaptcha-response";
                                el.value = token;
                                form.appendChild(el);
                                form.submit();
                            });
                        });
                    }
                    </script>
                    {/if}
                    <input type="hidden" name="captcha_pass_flood" value="1">
                    <input type="hidden" name="tokend" value="{$smarty.const.NV_CHECK_SESSION}">
                    <input type="hidden" name="redirect" value="{$REDIRECT}">
                </form>
                {/if}
            </div>
            <div class="card-footer"><span class="loader"></span></div>
        </div>
    </div>
    <script type="text/javascript">
        var Timeout = '{$FLB->flood_block_time}',
            timeBegin = new Date(),
            msBegin = timeBegin.getTime();
        function showSeconds() {
            var timeCurrent = new Date(),
            msCurrent = timeCurrent.getTime(),
            ms = Math.round((msCurrent - msBegin) / 1000);
            document.getElementById('secField').innerHTML = Timeout - ms;
            if (Timeout <= ms) {
                location.reload();
            }
        }

        timerID = setInterval("showSeconds()", 1000);
    </script>
</body>
</html>
