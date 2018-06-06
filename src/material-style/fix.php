<?php

// Xac dinh thu muc goc cua site
define('NV_ROOTDIR', pathinfo(str_replace(DIRECTORY_SEPARATOR, '/', __file__), PATHINFO_DIRNAME));

$list = scandir(NV_ROOTDIR);
foreach ($list as $_filename) {
    if (preg_match('/\.html$/', $_filename)) {
        $contents = file_get_contents(NV_ROOTDIR . '/' . $_filename);
        $contents = str_replace('href="assets/', 'href="/themes/smarty3-bootstrap4/assets/', $contents);
        $contents = str_replace('src="assets/img/demo/', 'src="/uploads/demo/', $contents);
        $contents = str_replace('src="assets/', 'src="/themes/smarty3-bootstrap4/assets/', $contents);
        file_put_contents(NV_ROOTDIR . '/' . $_filename, $contents);
    }
}
die('Thực hiện xong');