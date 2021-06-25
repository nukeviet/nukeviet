<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_VOTING')) {
    exit('Stop!!!');
}

/**
 * voting_result()
 *
 * @param array $voting
 * @return string
 */
function voting_result($voting)
{
    global $module_info;

    $xtpl = new XTemplate('result.voting.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('PUBLTIME', $voting['pubtime']);
    $xtpl->assign('LANG', $voting['lang']);
    $xtpl->assign('VOTINGQUESTION', $voting['question']);

    if (!empty($voting['note'])) {
        $xtpl->assign('VOTINGNOTE', $voting['note']);
        $xtpl->parse('main.note');
    }
    if (isset($voting['row'])) {
        $a = 1;
        $b = 0;
        foreach ($voting['row'] as $voting_i) {
            if ($voting['total']) {
                $width = ($voting_i['hitstotal'] / $voting['total']) * 100;
                $width = round($width, 2);
            } else {
                $width = 0;
            }

            if ($width) {
                ++$b;
            }

            $xtpl->assign('VOTING', $voting_i);
            $xtpl->assign('BG', (($b % 2 == 1) ? 'background-color: rgb(0, 102, 204);' : ''));
            $xtpl->assign('ID', $a);
            $xtpl->assign('WIDTH', $width);
            $xtpl->assign('TOTAL', $voting['total']);
            if ($voting_i['title']) {
                $xtpl->parse('main.result');
            }
            ++$a;
        }
    }
    $xtpl->parse('main');

    return $xtpl->text('main');
}
