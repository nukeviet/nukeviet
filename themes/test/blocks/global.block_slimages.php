<?php
if (!nv_function_exists('slide_image')) {
    function slide_image()
    {
        $xtpl = new XTemplate('global.block_slimages.tpl', NV_ROOTDIR . '/themes/test/blocks');
        $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
        $xtpl->assign('NV_ASSETS_DIR', NV_ASSETS_DIR);  
        $data = array();
        for ($i = 0 ; $i < 15 ; $i++){
            $data['link']  = '/nukeviet/data/tmp/news_15_chuc-mung-nukeviet-thong-tu-20-bo-tttt_500-0.jpg';
            $xtpl -> assign('DATA',$data);
            $xtpl -> parse('main.loop');
        }
        $xtpl->parse('main');
        return $xtpl->text('main');
    }
}
    $content = slide_image();
?>