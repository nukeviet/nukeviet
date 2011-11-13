<!-- BEGIN: main -->
<!-- BEGIN: error --> 
<div style="padding:10px; text-align:center;font-weight:bold; background:#FFE6F2;">
{error}
</div>
<meta http-equiv="Refresh" content="1;URL={redirect}">
<!-- END: error -->
<form action="" method='post'>
    <table class="tab1" style="margin-bottom: 8px;">
        <tbody class="second">
            <tr>
                <td  align="right"  width="230px">{LANG.weblink_config_imgwidth}</td>
                <td><input type="text" name="imgwidth" value="{DATA.imgwidth}" style="width:50px"/> px</td>
            </tr>
        </tbody>
        <tbody>
            <tr>
                <td  align="right"  width="230px">{LANG.weblink_config_imgheight}</td>
                <td><input type="text" name="imgheight" value="{DATA.imgheight}" style="width:50px"/> px</td>
            </tr>
        </tbody>
        <tbody class="second">
            <tr>
                <td  align="right">{LANG.config_per_page}</td>
                <td>
                    <input type="text" name="per_page" value="{DATA.per_page}" style="width:50px"/>
                </td>
            </tr>
        </tbody>
        <tbody>
            <tr>
            <td  align="right">{LANG.weblink_config_sort}</td>
            <td>
                <input type="radio" name="sort" {DATA.asc} value="asc" /> {LANG.weblink_asc}  
                <input type="radio" name="sort" {DATA.des} value="des" /> {LANG.weblink_des}
            </td>
            </tr>
        </tbody>
        <tbody class="second">
            <tr>
                <td  align="right">{LANG.weblink_config_sortoption}</td>
                <td>
                    <input type="radio" name="sortoption" id="sapxepoption_0" {DATA.byid} value="byid"/>{LANG.weblink_config_sortbyid}  
                    <input type="radio" name="sortoption" id="sapxepoption_1" {DATA.byrand} value="byrand"/>{LANG.weblink_config_sortbyrand}
                    <input type="radio" name="sortoption" id="sapxepoption_2" {DATA.bytime} value="bytime"/>{LANG.weblink_config_sortbytime}
                    <input type="radio" name="sortoption" id="sapxepoption_3" {DATA.byhit} value="byhit"/>{LANG.weblink_config_sortbyhit}
                </td>
            </tr>
        </tbody>
        <tbody>
            <tr>
                <td  align="right">{LANG.weblink_config_showimagelink}</td>
                <td><input type="checkbox"  value="1" name="showlinkimage" {DATA.ck_showlinkimage} /></td>
            </tr>
        </tbody>
        <tbody class="second">
            <tr>
            <td colspan="2" align="center"><input type="submit" name="submit" value="{LANG.weblink_submit}"/></td>
            </tr>
        </tbody>
    </table>
</form>
<!-- END: main -->
