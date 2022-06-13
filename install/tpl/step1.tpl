<!-- BEGIN: step -->
<script type="text/javascript">
    function nv_checklang() {
        url = $("#lang").val();
        if (url == 'none') {
            alert('{LANG.select_language}');
            return false;
        } else if (url == 'other') {
            top.location.href = 'http://translate.nukeviet.vn/en/translate/download/';
        } else {
            top.location.href = url;
        }
    }
</script>
<!-- BEGIN: unofficial_mode -->
<div class="infoalert">{LANG.is_unofficial_mode}.</div>
<!-- END: unofficial_mode -->
<p>{LANG.select_lang_des}</p>
<form action="#" id="checklang">
    <p>
        <select class="select" id="lang" onchange="return nv_checklang()">
            <option value="none">{LANG.choose_lang}</option>
            <!-- BEGIN: languagelist -->
            <option value="{BASE_SITEURL}install/index.php?{LANG_VARIABLE}={LANGTYPE}&amp;step=1"{SELECTED}>{LANGNAME}</option>
            <!-- END: languagelist -->
            <option value="other">Other Language</option>
        </select>
    </p>
    <ul class="control_t fr">
        <li><span class="next_step" id="next_step"><a href="{BASE_SITEURL}install/index.php?{LANG_VARIABLE}={CURRENTLANG}&amp;step=2&t={NV_CURRENTTIME}">{LANG.next_step}</a></span></li>
    </ul>
</form>
<!-- BEGIN: check_supports_rewrite -->
<script type="text/javascript">
    $("#next_step").hide();
    var supports_rewrite = '';
    $.ajax({
        url : '{BASE_SITEURL}install/check.rewrite',
        type : 'GET',
        success : function(theResponse) {
            if (theResponse == "mod_rewrite works") {
                supports_rewrite = '{SUPPORTS_REWRITE}';
            }
            nv_setCookie("supports_rewrite", supports_rewrite, 86400);
            $("#next_step").show();
        },
        error : function(theResponse) {
            $("#next_step").show();
        }
    });
</script>
<!-- END: check_supports_rewrite -->
<div class="clearfix"></div>
<div id="thanks">
    <div class="administrator">
        <h1>{LANG.thanks}</h1>
        <p>
            {LANG.thanks_text}
        </p>
    </div>
    <div class="list-member">
        <div class="list-member-all">
            <h4>{LANG.thanks_list_member_all}</h4>
            VINADES.,JSC,
            <a target="_blank" href="https://github.com/vuthao">vuthao</a>,
            <a target="_blank" href="https://github.com/hoaquynhtim99">hoaquynhtim99</a>,
            <a target="_blank" href="https://github.com/anhtunguyen">anhtunguyen</a>,
            <a target="_blank" href="https://github.com/mynukeviet">mynukeviet</a>,
            <a target="_blank" href="https://github.com/tdfoss">tdfoss</a>,
            <a target="_blank" href="https://github.com/thehung">thehung</a>,
            <a target="_blank" href="https://github.com/tmsholdings">tmsholdings</a>,
            tuyenhv.abs, khoaij123, dat.huynh,
            dinhpc86 at gmail.com,
            <a target="_blank" href="https://github.com/anhyeuviolet">anhyeuviolet</a>,
            <a target="_blank" href="https://github.com/trinhthinhhp">trinhthinhhp</a>,
            nguyenhung2904 at gmail.com,
            <a target="_blank" href="https://github.com/htuyen9x">htuyen9x</a>,
            <a target="_blank" href="https://github.com/dangdlinhtu">dangdlinhtu</a>,
            <a target="_blank" href="https://github.com/thangbv">thangbv</a>,
            trankhuyen81 at gmail.com,
            <a target="_blank" href="https://github.com/thuvp1995">thuvp1995</a>,
            <a target="_blank" href="https://github.com/PhamQuocTien132">PhamQuocTien132</a>,
            mabubeo1990 at gmail.com,
            <a target="_blank" href="https://github.com/phongaz">phongaz</a>,
            tamahari at gmail.com,
            <a target="_blank" href="https://github.com/ngocphan12031995">ngocphan12031995</a>,
            hungtmit at gmail.com,
            <a target="_blank" href="https://github.com/tkhuyenbk">tkhuyenbk</a>,
            <a target="_blank" href="https://github.com/webvang">webvang</a>,
            <a target="_blank" href="https://github.com/hiidemo">hiidemo</a>,
            <a target="_blank" href="https://github.com/thethao">thethao</a>,
            <a target="_blank" href="https://github.com/tuanta">tuanta</a>,
            <a target="_blank" href="https://github.com/duyetdev">duyetdev</a>,
            ledinhhung87 at gmail.com,
            mtmost.com at gmail.com,
            <a target="_blank" href="https://github.com/truongdacngoc1993">truongdacngoc1993</a>,
            <a target="_blank" href="https://github.com/ThinhNguyenVB">ThinhNguyenVB</a>,
            Hoàng Tuyên at VINADES.,JSC,
            <a target="_blank" href="https://github.com/nvu-github">nvu-github</a>,
            <a target="_blank" href="https://github.com/NguyenDuong21">NguyenDuong21</a>,
            nguyenuc09112000, thien123111999
        </div>
        <div class="list-member-other-version">
            <h4>{LANG.thanks_list_member_other_version}:</h4>
            <div class="version">
                <h5>{LANG.thanks_other_version} 4.5.02</h5>
                    <a target="_blank" href="https://github.com/vinades">VINADES.,JSC (68 commits)</a>,
                    <a target="_blank" href="https://github.com/nvu-github">nvu-github (3 commits)</a>,
                    <a target="_blank" href="https://github.com/NguyenDuong21">NguyenDuong21 (2 commits)</a>,
                    nguyenuc09112000 (2 commits),
                    thien123111999 (1 commits),
                    <a target="_blank" href="https://github.com/tmsholdings">tmsholdings (1 commits)</a>
                    <br><br>
                <h5>{LANG.thanks_other_version} 4.5.01</h5>
                    <a target="_blank" href="https://github.com/vinades">VINADES.,JSC (70 commits)</a>,
                    <a target="_blank" href="https://github.com/tmsholdings">tmsholdings (3 commits)</a>,
                    tuyenhv.abs (3 commits),
                    <a target="_blank" href="https://github.com/hoaquynhtim99">hoaquynhtim99 (1 commits)</a>
                    <br><br>
                <h5>{LANG.thanks_other_version} 4.5.00</h5>
                    <a target="_blank" href="https://github.com/vinades">VINADES.,JSC (281 commits)</a>,
                    <a target="_blank" href="https://github.com/hoaquynhtim99">hoaquynhtim99 (9 commits)</a>,
                    khoaij123 (4 commits),
                    <a target="_blank" href="https://github.com/thehung">thehung (3 commits)</a>,
                    dat.huynh (3 commits),
                    <a target="_blank" href="https://github.com/vuthao">vuthao (2 commits)</a>,
                    <a target="_blank" href="https://github.com/hiidemo">hiidemo (1 commits)</a>,
                    tuyenh (1 commits),
                    anhtunguyen71 (1 commits)
                    <br><br>
                <h5>{LANG.thanks_other_version} 4.4.02</h5>
                    VINADES.,JSC (3 commits),
                    <a target="_blank" href="https://github.com/hoaquynhtim99">hoaquynhtim99 (2 commits)</a>,
                    <a target="_blank" href="https://github.com/anhtunguyen">anhtunguyen (1 commits)</a>
                <br><br>
                <h5>{LANG.thanks_other_version} 4.4.01</h5>
                    VINADES.,JSC (41 commits),
                    <a target="_blank" href="https://github.com/hoaquynhtim99">hoaquynhtim99 (5 commits)</a>,
                    Hoàng Tuyên at VINADES.,JSC (3 commits),
                    <a target="_blank" href="https://github.com/anhtunguyen">anhtunguyen (2 commits)</a>
                <br><br>
                <h5>{LANG.thanks_other_version} 4.4.00</h5>
                    VINADES.,JSC (49 commits),
                    <a target="_blank" href="https://github.com/vuthao">vuthao (7 commits)</a>,
                    <a target="_blank" href="https://github.com/thehung">thehung (5 commits)</a>,
                    <a target="_blank" href="https://github.com/hoaquynhtim99">hoaquynhtim99 (5 commits)</a>,
                    <a target="_blank" href="https://github.com/anhtunguyen">anhtunguyen (2 commits)</a>,
                    Hoàng Tuyên at VINADES.,JSC (2 commits)
                <br><br>
                <h5>{LANG.thanks_other_version} 4.3.08</h5>
                    VINADES.,JSC (28 commits),
                    Hoàng Tuyên at VINADES.,JSC (5 commits),
                    <a target="_blank" href="https://github.com/hoaquynhtim99">hoaquynhtim99 (2 commits)</a>,
                    <a target="_blank" href="https://github.com/tdfoss">TDFOSS.,LTD (1 commits)</a>
                <br><br>
                <h5>{LANG.thanks_other_version} 4.3.07</h5>
                    VINADES.,JSC (28 commits),
                    <a target="_blank" href="https://github.com/hoaquynhtim99">hoaquynhtim99 (2 commits)</a>,
                    <a target="_blank" href="https://github.com/ThinhNguyenVB">ThinhNguyenVB (2 commits)</a>,
                    <a target="_blank" href="https://github.com/tdfoss">TDFOSS.,LTD (1 commits)</a>
                <br><br>
                <h5>{LANG.thanks_other_version} 4.3.06</h5>
                    VINADES.,JSC (33 commits),
                    <a target="_blank" href="https://github.com/tdfoss">TDFOSS.,LTD (5 commits)</a>,
                    <a target="_blank" href="https://github.com/hoaquynhtim99">hoaquynhtim99 (2 commits)</a>
                <br><br>
                <h5>{LANG.thanks_other_version} 4.3.05</h5>
                    VINADES.,JSC (38 commits),
                    <a target="_blank" href="https://github.com/vuthao/">vuthao (2 commits)</a>,
                    <a target="_blank" href="https://github.com/mynukeviet/">mynukeviet (1 commits)</a>
                <br><br>
                <h5>{LANG.thanks_other_version} 4.3.03</h5>
                    VINADES.,JSC (24 commits),
                    <a target="_blank" href="https://github.com/hoaquynhtim99">hoaquynhtim99 (2 commits)</a>
                <br><br>
                <h5>{LANG.thanks_other_version} 4.3.02</h5>
                    VINADES.,JSC (65 commits),
                    <a target="_blank" href="https://github.com/hoaquynhtim99">hoaquynhtim99 (2 commits)</a>,
                    <a target="_blank" href="https://github.com/vuthao">vuthao (2 commits)</a>
                <br><br>
                <h5>{LANG.thanks_other_version} 4.3.01</h5>
                    VINADES.,JSC (32 commits),
                    <a target="_blank" href="https://github.com/hoaquynhtim99">hoaquynhtim99 (3 commits)</a>,
                    <a target="_blank" href="https://github.com/vuthao">vuthao (1 commits)</a>
                <br><br>
                <h5>{LANG.thanks_other_version} 4.3</h5>
                    VINADES.,JSC (132 commits),
                    <a target="_blank" href="https://github.com/anhtunguyen">anhtunguyen (17 commits)</a>,
                    <a target="_blank" href="https://github.com/anhyeuviolet">anhyeuviolet (11 commits)</a>,
                    <a target="_blank" href="https://github.com/tdfoss">tdfoss (9 commits)</a>,
                    <a target="_blank" href="https://github.com/vuthao">vuthao (8 commits)</a>,
                    <a target="_blank" href="https://github.com/mynukeviet">mynukeviet (4 commits)</a>,
                    <a target="_blank" href="https://github.com/thehung">thehung (3 commits)</a>,
                    <a target="_blank" href="https://github.com/hoaquynhtim99">hoaquynhtim99 (2 commits)</a>,
                    <a target="_blank" href="https://github.com/trinhthinhhp">trinhthinhhp (1 commits)</a>
                <br><br>
                <h5>{LANG.thanks_other_version} 4.2</h5>
                    VINADES.,JSC (238 commits),
                    <a target="_blank" href="https://github.com/anhtunguyen">anhtunguyen (64 commits)</a>,
                    <a target="_blank" href="https://github.com/vuthao">vuthao (31 commits)</a>,
                    <a target="_blank" href="https://github.com/hoaquynhtim99">hoaquynhtim99 (7 commits)</a>,
                    <a target="_blank" href="https://github.com/anhyeuviolet">anhyeuviolet (5 commits)</a>,
                    <a target="_blank" href="https://github.com/mynukeviet">mynukeviet (4 commits)</a>,
                    <a target="_blank" href="https://github.com/thehung">thehung (1 commits)</a>
                <br><br>
                <h5>{LANG.thanks_other_version} 4.1</h5>
                    VINADES.,JSC (197 commits),
                    <a target="_blank" href="https://github.com/hoaquynhtim99">hoaquynhtim99 (131 commits)</a>,
                    <a target="_blank" href="https://github.com/anhyeuviolet">anhyeuviolet (19 commits)</a>,
                    <a target="_blank" href="https://github.com/vuthao">vuthao (17 commits)</a>,
                    <a target="_blank" href="https://github.com/mynukeviet">mynukeviet (10 commits)</a>,
                    <a target="_blank" href="https://github.com/thuvp1995">thuvp1995 (9 commits)</a>,
                    <a target="_blank" href="https://github.com/thangbv">thangbv (6 commits)</a>,
                    <a target="_blank" href="https://github.com/ngocphan12031995">ngocphan12031995 (5 commits)</a>,
                    <a target="_blank" href="https://github.com/htuyen9x">htuyen9x (4 commits)</a>,
                    <a target="_blank" href="https://github.com/hiidemo">hiidemo (2 commits)</a>,
                    <a target="_blank" href="https://github.com/trinhthinhhp">trinhthinhhp (1 commits)</a>,
                    <a target="_blank" href="https://github.com/duyetdev">duyetdev (1 commits)</a>,
                    <a target="_blank" href="https://github.com/phongaz">phongaz (1 commits)</a>,
                    <a target="_blank" href="https://github.com/webvang">webvang (1 commits)</a>
                <br><br>
                <h5>{LANG.thanks_other_version} 4.0</h5>
                    VINADES.,JSC (713 commits)
                    <a target="_blank" href="https://github.com/vuthao">vuthao (1360 commits)</a>,
                    <a target="_blank" href="https://github.com/hoaquynhtim99">hoaquynhtim99 (547 commits)</a>,
                    <a target="_blank" href="https://github.com/mynukeviet">mynukeviet (219 commits)</a>,
                    <a target="_blank" href="https://github.com/trinhthinhhp">trinhthinhhp (37 commits)</a>,
                    <a target="_blank" href="https://github.com/anhyeuviolet">anhyeuviolet (25 commits)</a>,
                    <a target="_blank" href="https://github.com/dangdlinhtu">dangdlinhtu (23 commits)</a>,
                    <a target="_blank" href="https://github.com/htuyen9x">htuyen9x (21 commits)</a>,
                    <a target="_blank" href="https://github.com/thangbv">thangbv (15 commits)</a>,
                    <a target="_blank" href="https://github.com/PhamQuocTien132">PhamQuocTien132 (9 commits)</a>,
                    mabubeo1990 at gmail.com (8 commits)</a>,
                    <a target="_blank" href="https://github.com/phongaz">phongaz (6 commits)</a>,
                    <a target="_blank" href="https://github.com/tkhuyenbk">tkhuyenbk (2 commits)</a>,
                    <a target="_blank" href="https://github.com/tuanta">tuanta (1 commits)</a>,
                    <a target="_blank" href="https://github.com/thethao">thethao (1 commits)</a>,
                    <a target="_blank" href="https://github.com/truongdacngoc1993">truongdacngoc1993 (1 commits)</a>,
                    <a target="_blank" href="https://github.com/webvang">webvang (1 commits)</a>
                <br><br>
                <h5>{LANG.thanks_other_version} 3.4</h5>
                    VINADES.,JSC (74 commits),
                    <a target="_blank" href="https://github.com/hoaquynhtim99">hoaquynhtim99 (245 commits)</a>,
                    <a target="_blank" href="https://github.com/vuthao">vuthao (60 commits)</a>,
                    <a target="_blank" href="https://github.com/thehung">thehung (23 commits)</a>,
                    <a target="_blank" href="https://github.com/anhtunguyen">anhtunguyen (5 commits)</a>
                <br><br>
                <h5>{LANG.thanks_other_version} 3.3</h5>
                    VINADES.,JSC (29 commits),
                    <a target="_blank" href="https://github.com/hoaquynhtim99">hoaquynhtim99 (24 commits)</a>,
                    <a target="_blank" href="https://github.com/anhtunguyen">anhtunguyen (20 commits)</a>,
                    <a target="_blank" href="https://github.com/thehung">thehung (5 commits)</a>
                <br><br>
                <h5>{LANG.thanks_other_version} 3.2</h5>
                    VINADES.,JSC (56 commits)</a>,
                    <a target="_blank" href="https://github.com/vuthao">vuthao (98 commits)</a>,
                    <a target="_blank" href="https://github.com/hoaquynhtim99">hoaquynhtim99 (49 commits)</a>,
                    <a target="_blank" href="https://github.com/thehung">thehung (17 commits)</a>,
                    <a target="_blank" href="https://github.com/anhtunguyen">anhtunguyen (13 commits)</a>,
                    trankhuyen81 at gmail.com (10 commits)</a>
                <br><br>
                <h5>{LANG.thanks_other_version} 3.1</h5>
                    <a target="_blank" href="https://github.com/vuthao">vuthao (316 commits)</a>,
                    <a target="_blank" href="https://github.com/anhtunguyen">anhtunguyen (134 commits)</a>,
                    dinhpc86 at gmail.com (45 commits)</a>,
                    <a target="_blank" href="https://github.com/thehung">thehung (27 commits)</a>,
                    nguyenhung2904 at gmail.com (23 commits)</a>,
                    <a target="_blank" href="https://github.com/hoaquynhtim99">hoaquynhtim99 (9 commits)</a>,
                    tamahari at gmail.com (5 commits)</a>,
                    ledinhhung87 at gmail.com (1 commits)</a>,
                    mtmost.com at gmail.com (1 commits)</a>
                <br><br>
                <h5>{LANG.thanks_other_version} 3.0</h5>
                    <a target="_blank" href="https://github.com/vuthao">vuthao (283 commits)</a>,
                    <a target="_blank" href="https://github.com/anhtunguyen">anhtunguyen (115 commits)</a>,
                    <a target="_blank" href="https://github.com/thehung">thehung (12 commits)</a>,
                    nguyenhung2904 at gmail.com (3 commits)</a>,
                    hungtmit at gmail.com (2 commits)</a>
                 <br><br>
            </div>
        </div>
    </div>
</div>
<!-- END: step -->
