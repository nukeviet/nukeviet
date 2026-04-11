<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_VOTING')) {
    exit('Stop!!!');
}

/**
 * @param array $voting
 * @return string
 */
function voting_result($voting)
{
    global $nv_Lang;

    $xtpl = new XTemplate('detail.tpl', get_module_tpl_dir('detail.tpl'));
    $xtpl->assign('PUBLTIME', $voting['pubtime']);
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('VOTINGQUESTION', $voting['question']);

    if (!empty($voting['note'])) {
        $xtpl->assign('VOTINGNOTE', $voting['note']);
        $xtpl->assign('VOTINGVID', $voting['row'][0]['vid']);
        if ($voting['is_error']) {
            $xtpl->parse('main.content.note.error');
        } else {
            $xtpl->parse('main.content.note.info');
        }
        $xtpl->parse('main.content.note');
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
            $xtpl->assign('TOTAL_TITLE', $nv_Lang->getModule('voting_total2', $voting['pubtime']));
            if ($voting_i['title']) {
                $xtpl->parse('main.content.result');
            }
            ++$a;
        }
    }

    $xtpl->parse('main.content');
    if ($voting['ajax']) {
        return $xtpl->text('main.content');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * @param array $votings
 * @return string
 */
function voting_main(array $votings): string
{
    global $page_url, $module_captcha, $nv_Lang, $global_config;

    $xtpl = new XTemplate('main.tpl', get_module_tpl_dir('main.tpl'));
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('FORM_ACTION', $page_url);

    foreach ($votings as $rows) {
        $xtpl->assign('VOTING', $rows);

        foreach ($rows['items'] as $row) {
            if (!empty($row['url'])) {
                $row['title'] = '<a target="_blank" href="' . $row['url'] . '">' . $row['title'] . '</a>';
            }
            $xtpl->assign('RESULT', $row);
            if ($rows['accept'] > 1) {
                $xtpl->parse('main.loop.resultn');
            } else {
                $xtpl->parse('main.loop.result1');
            }
        }

        if ($rows['active_captcha']) {
            if ($module_captcha == 'recaptcha' and $global_config['recaptcha_ver'] == 3) {
                $xtpl->parse('main.loop.recaptcha3');
            } elseif (($module_captcha == 'recaptcha' and $global_config['recaptcha_ver'] == 2) or $module_captcha == 'captcha') {
                if ($module_captcha == 'recaptcha' and $global_config['recaptcha_ver'] == 2) {
                    $xtpl->assign('RECAPTCHA_ELEMENT', 'recaptcha' . nv_genpass(8));
                    $xtpl->assign('N_CAPTCHA', $nv_Lang->getGlobal('securitycode1'));
                    $xtpl->parse('main.loop.has_captcha.recaptcha');
                } else {
                    $xtpl->assign('N_CAPTCHA', $nv_Lang->getGlobal('securitycode'));
                    $xtpl->parse('main.loop.has_captcha.basic');
                }
                $xtpl->parse('main.loop.has_captcha');
            } elseif ($module_captcha == 'turnstile') {
                $xtpl->parse('main.loop.turnstile');
            }
        }

        $xtpl->parse('main.loop');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}
