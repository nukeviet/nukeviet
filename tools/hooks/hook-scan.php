<?php

/**
 * NUKEVIET Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

set_time_limit(0);

/**
 * @param string $dir
 * @param string $base_dir
 * @return array[]|mixed[]
 */
function list_all_file($dir = '', $base_dir = '')
{
    $file_list = [];

    if (is_dir($dir)) {
        $array_filedir = scandir($dir);

        foreach ($array_filedir as $v) {
            if ($v == '.' or $v == '..') {
                continue;
            }

            if (is_dir($dir . '/' . $v)) {
                foreach (list_all_file($dir . '/' . $v, $base_dir . '/' . $v) as $file) {
                    $file_list[] = $file;
                }
            } else {
                // if( $base_dir == '' and ( $v == 'index.html' or $v == 'index.htm' ) ) continue; // Khong di chuyen index.html
                if (preg_match('/\.php$/', $v) and !preg_match('/^\/?(data|vendor)\//', $base_dir . '/' . $v)) {
                    $file_list[] = preg_replace('/^\//', '', $base_dir . '/' . $v);
                }
            }
        }
    }

    return $file_list;
}

define('NV_ROOTDIR', str_replace('\\', '/', realpath(dirname(__FILE__) . '/../../src')));

echo '<pre><code style="font-size: 16px;font-family: Courier New;">';

$debug = false;

/*
 * Xuất hướng dẫn sử dụng của hook
 */
if (isset($_GET['template']) and isset($_GET['f']) and isset($_GET['l'])) {
    $file = $_GET['f'];
    $line = $_GET['l'];

    $filecontents = file_get_contents(NV_ROOTDIR . '/' . $file);
    $filecontents = str_replace("\025", "\n", $filecontents); // IBM mainframe systems
    $filecontents = str_replace("\n\r", "\n", $filecontents); // Acorn BBC
    $filecontents = str_replace("\r\n", "\n", $filecontents); // Microsoft Windows
    $filecontents = str_replace("\r", "\n", $filecontents); // Mac OS,
    $filecontents = explode("\n", $filecontents);
    $linecontents = $filecontents[$line];
    $numberLines = sizeof($filecontents);

    if ($debug) {
        echo "<strong>Line ban đầu:</strong>\n";
        print_r($linecontents . "\n\n");
    }

    // Cắt line từ hàm nv_apply_hook về cuối
    if (($offset = strpos($linecontents, 'nv_apply_hook')) !== false) {
        $linecontents = substr($linecontents, $offset);
    } else {
        exit("Không tìm thấy hàm nv_apply_hook\n");
    }
    if ($debug) {
        echo "<strong>Cắt lấy từ hàm nv_apply_hook về sau:</strong>\n";
        print_r($linecontents . "\n\n");
    }

    // Kiểm tra xem kết thúc hàm hay chưa
    while (substr_count($linecontents, ')') < substr_count($linecontents, '(')) {
        ++$line;
        $linecontents .= "\n" . $filecontents[$line];
        if ($line >= $numberLines) {
            exit("Không tìm thấy chỗ kết thúc hàm\n");
        }
    }
    if ($debug) {
        echo "<strong>Xác định đầy đủ nội dung hàm:</strong>\n";
        print_r($linecontents . "\n\n");
    }

    // Đưa hàm về 1 dòng
    $linecontents = str_replace("\n", ' ', $linecontents);
    if ($debug) {
        echo "<strong>Đưa hàm về 1 dòng:</strong>\n";
        print_r($linecontents . "\n\n");
    }

    preg_match('/nv\_apply\_hook[\s]*\([\s]*(\$module\_name|""|\'\')[\s]*\,[\s]*(\'|")([a-zA-Z0-9\_]+)(\'|")[\s]*/', $linecontents, $m);
    if ($debug) {
        echo "<strong>Phân tách ra:</strong>\n";
        print_r($m);
        print_r("\n");
    }

    // Lấy ra tên hook
    $hook_tag = $m[3];
    if ($debug) {
        echo "<strong>Lấy được tên HOOK:</strong>\n";
        print_r($hook_tag . "\n\n");
    }

    // Cắt bớt dòng này kể từ sau tên hook
    $linecontents = trim(preg_replace('/^(.*?)' . preg_quote($m[0], '/') . '(.*?)$/', '\\2', $linecontents));

    if ($debug) {
        echo "<strong>Cắt bớt line kể từ tên HOOK:</strong>\n";
        print_r($linecontents . "\n\n");

        echo "<strong>Lấy các biến tham số đầu vào:</strong>\n";
    }

    // Lấy các biến tham số đầu vào
    $array_para = '';
    if (preg_match('/^\)/', $linecontents)) {
        if ($debug) {
            echo "Không có tham số đầu vào\n\n";
        }
    } elseif (preg_match('/^\,[\s]*(array[\s]*\(|\[)/', $linecontents, $m)) {
        // Danh sách các tham số được liệt kê trực tiếp
        if ($debug) {
            echo "<strong>Danh sách các tham số được liệt kê trực tiếp, preg_match để xác định kiểu viết array( hay là [:</strong>\n";
            print_r($m);
            print_r("\n");
        }
        $linecontents = mb_substr($linecontents, mb_strlen($m[0]));
        $_offset = 0;
        $_line_length = mb_strlen($linecontents);
        $bracket_open_count = 1;
        $bracket_close_count = 0;
        if ($m[1] == '[') {
            $bracket_open = '[';
            $bracket_close = ']';
        } else {
            $bracket_open = '(';
            $bracket_close = ')';
        }
        while (1) {
            $char = mb_substr($linecontents, $_offset, 1);
            if ($char == $bracket_open) {
                ++$bracket_open_count;
            } elseif ($char == $bracket_close) {
                ++$bracket_close_count;
            }
            if ($bracket_open_count <= $bracket_close_count) {
                break;
            }
            $array_para .= $char;
            ++$_offset;
            if ($_offset >= $_line_length) {
                break;
            }
        }
        // Nếu có tham số đầu vào thì cắt tiếp $linecontents để tìm default. Cắt đến dấu phảy tiếp theo
        if (!empty($array_para)) {
            $linecontents = preg_replace('/^' . preg_quote($array_para, '/') . '[\s]*[\)|\]]+[\s]*\,*[\s]*/', '', $linecontents);
        }
    }
    // FIXME tham số đầu vào từ 1 biến khác cần viết thêm

    if ($debug) {
        echo "<strong>Tham số đầu vào</strong>\n";
        if (empty($array_para)) {
            echo "Không có tham số đầu vào\n\n";
        } else {
            echo $array_para . "\n\n";
        }

        echo "<strong>Sau khi xác định tham số đầu vào, line còn lại là</strong>\n";
        print_r($linecontents);
        print_r("\n");
        print_r("\n");
    }

    // Lấy biến trả về
    $return_var = '';
    if ($debug) {
        echo "<strong>Biến trả về</strong>\n";
    }
    if (!empty($array_para)) {
        if (preg_match('/([\$a-zA-Z0-9\_]+)/', $linecontents, $m)) {
            if ($debug) {
                print_r($m);
            }
            $return_var = trim($m[1]);
        }
    }
    if ($debug) {
        if (empty($return_var)) {
            echo "Không có biến trả về\n\n";
        } else {
            echo $return_var . "\n\n";
        }
        print_r("\n\n\n\n");
    }

    $array_para = empty($array_para) ? [] : array_map('trim', explode(',', $array_para));

    $array_table_rows = [];
    $i = 0;
    $maxSize = 8;
    foreach ($array_para as $para) {
        $array_table_rows[$i] = $para;
        $size = strlen($para);
        if ($size > $maxSize) {
            $maxSize = $size;
        }
        ++$i;
    }
    if ($maxSize < 15) {
        $maxSize = 15;
    }
    $heading_pad = '';
    for ($i = ($maxSize - 8); $i > 0; --$i) {
        $heading_pad .= ' ';
    }

    echo '<h1 style="margin:0;padding:0;">====== ' . $hook_tag . " ======</h1>\n";
    echo "Xảy_ra_khi_nào.\n\n";
    echo "<h3 style=\"margin:0;padding:0;\">==== Tham số ====</h3>\n";
    echo '^ STT ^ Tên biến' . $heading_pad . " ^ Kiểu dữ liệu    ^ Ghi chú                ^\n";

    foreach ($array_table_rows as $ii => $row) {
        echo '| ' . str_pad($ii, 3, ' ', STR_PAD_RIGHT) . ' | ' . str_pad($row, $maxSize, ' ', STR_PAD_RIGHT) . " |                 |                        |\n";
    }

    echo "\n";
    echo "<h3 style=\"margin:0;padding:0;\">==== Dữ liệu trả về ====</h3>\n";

    if (empty($return_var)) {
        echo "Tùy người lập trình\n\n";
    } else {
        echo 'Biến \'\'' . htmlspecialchars($return_var) . '\'\'' . "\n\n";
    }

    echo "<h3 style=\"margin:0;padding:0;\">==== Ví dụ viết plugin ====</h3>\n";
    echo htmlspecialchars('<code php>') . "\n";
    echo htmlspecialchars("nv_add_hook(\$module_name, '" . $hook_tag . "', \$priority, function(\$vars) {") . "\n";

    if ($array_table_rows) {
        foreach ($array_table_rows as $ii => $row) {
            echo '    ' . $row . ' = $vars[' . $ii . "];\n";
        }
        echo "\n";
    }

    echo htmlspecialchars('    // Thực hiện code hook tại đây...') . "\n";

    if ($return_var) {
        echo htmlspecialchars("\n    return " . $return_var . ';') . "\n";
    }

    echo htmlspecialchars('});') . "\n";
    echo htmlspecialchars('</code>') . "\n";

    echo '</code></pre>';
    exit();
}

/*
 * Hiển thị dòng code hook đó
 */
$debug = true;

if (isset($_GET['f']) and isset($_GET['c'])) {
    $file = $_GET['f'];
    $code = $_GET['c'];

    $code = str_replace("\025", "\n", $code); // IBM mainframe systems
    $code = str_replace("\n\r", "\n", $code); // Acorn BBC
    $code = str_replace("\r\n", "\n", $code); // Microsoft Windows
    $code = str_replace("\r", "\n", $code); // Mac OS,

    $code = explode("\n", $code);
    $checkNumber = sizeof($code);

    $handle = @fopen(NV_ROOTDIR . '/' . $file, 'r');
    if ($handle) {
        $offset_check = 0;
        $array_buffer_out = '';
        $start_line = 0;

        $stack_bracket_str = '';

        $offset_line = -1;
        while (($buffer = fgets($handle, 4096)) !== false) {
            ++$offset_line;

            // Tìm kiếm line trùng đầu tiên
            if (!$offset_check and (($str_pos = strpos($buffer, $code[0])) !== false)) {
                $offset_check = 1;
                $start_line = $offset_line;

                $array_buffer_out .= '---[[START:HIGHLIGHT]]---' . htmlspecialchars(rtrim($buffer));
                $stack_bracket_str .= substr($buffer, $str_pos);

                // Nếu chỉ có 1 line thì đánh dấu luôn hết thúc highlight
                if ($checkNumber == 1) {
                    $array_buffer_out = str_replace('---[[START:HIGHLIGHT]]---', '<span id="highlight" style="background-color:#e01a31;"><a style="text-decoration: none;color:#fff;" href="?template=1&amp;f=' . urlencode($file) . '&amp;l=' . $start_line . '" target="_blank">', $array_buffer_out);
                    $array_buffer_out .= '</a></span>';
                    $stack_bracket_str = '';
                }
                $array_buffer_out .= "\n";
                continue;
            }
            if (!$offset_check or $checkNumber == 1) {
                // Chưa tìm thấy line bắt đầu hoặc check line trên 1 dòng thì xuất ra
                $array_buffer_out .= htmlspecialchars(rtrim($buffer)) . "\n";
                $stack_bracket_str = '';
                continue;
            }

            if ($offset_check and $checkNumber > 1) {
                $is_equal = false;

                if ($offset_check < $checkNumber - 1) {
                    // Dòng giữa thì kiểm tra bằng
                    if (rtrim($buffer) == rtrim($code[$offset_check])) {
                        $is_equal = true;
                    }
                } elseif ($offset_check == $checkNumber - 1) {
                    // Dòng cuối lại kiểm tra pos = 0, nhưng lần này kiểm tra pos phải bằng 0
                    if (strpos($buffer, $code[$offset_check]) === 0) {
                        $is_equal = true;
                    }
                } else {
                    // Vượt quá dòng kiểm tra thì chờ kết thúc hàm
                    $is_equal = true;
                }

                $array_buffer_out .= htmlspecialchars(rtrim($buffer));
                $stack_bracket_str .= $buffer;

                if ($is_equal) {
                    ++$offset_check;
                    if ($offset_check >= $checkNumber) {
                        // FIXME chỗ này đếm ký tự ( và ) nếu có inline-comment mà trong comment có xuất hiện nữa nó chưa đếm chính xác
                        if (substr_count($stack_bracket_str, ')') >= substr_count($stack_bracket_str, '(')) {
                            // Xong lượt check
                            $offset_check = 0;
                            $array_buffer_out = str_replace('---[[START:HIGHLIGHT]]---', '<span id="highlight" style="background-color:#e01a31;"><a style="text-decoration: none;color:#fff;" href="?template=1&amp;f=' . urlencode($file) . '&amp;l=' . $start_line . '" target="_blank">', $array_buffer_out);
                            $array_buffer_out .= '</a></span>';
                        }
                    }
                } else {
                    /*
                     * Sai, không phải đoạn này
                     * - Đánh dấu lại $offset_check để tìm đoạn khác
                     * - Bỏ đánh highlight các đoạn đã đánh dấu
                     */
                    $offset_check = 0;
                    $array_buffer_out = str_replace('---[[START:HIGHLIGHT]]---', '', $array_buffer_out);
                    $stack_bracket_str = '';
                }

                $array_buffer_out .= "\n";
            }
        }
        fclose($handle);

        echo $array_buffer_out;
    }

    echo '</code></pre>';
    exit();
}

/*
 * Quét tất cả các file php trên hệ thống tìm ra hook
 */
$allfiles = list_all_file(NV_ROOTDIR);

$hook_sys = [];
$hook_modules = [];
$debug = false;

foreach ($allfiles as $filepath) {
    $filecontents = file_get_contents(NV_ROOTDIR . '/' . $filepath);

    unset($m);
    preg_match_all('/nv\_apply\_hook[\s]*\([\s]*(\$module\_name|""|\'\')[\s]*\,[\s]*(\'|")([a-zA-Z0-9\_]+)(\'|")[\s]*/', $filecontents, $m);

    if ($filepath == 'index.php') {
        if ($debug) {
            print_r($m);
            exit();
        }
    }

    if (!empty($m[1])) {
        foreach ($m[1] as $k => $v) {
            $hook_tag = $m[3][$k];
            $hook_data = [
                'file' => $filepath,
                'code' => $m[0][$k]
            ];
            if ($m[1][$k] == '$module_name') {
                $hook_module = explode('/', $filepath);
                $hook_module = $hook_module[1];
                if (!isset($hook_modules[$hook_module])) {
                    $hook_modules[$hook_module] = [];
                }
                $hook_modules[$hook_module][$hook_tag] = $hook_data;
            } else {
                // Hook hệ thống
                $hook_sys[$hook_tag] = $hook_data;
            }
        }
    }
}

echo '<h1 style="margin:0;padding:0;">====== Danh sách các hook của NukeViet ======</h1>
> Khái niệm hook chỉ có từ NukeViet 5 trở đi

<h2 style="margin:0;padding:0;">===== Hook của hệ thống =====</h2>
';

ksort($hook_sys);
ksort($hook_modules);
foreach ($hook_modules as $mod => $modData) {
    ksort($hook_modules[$mod]);
}

foreach ($hook_sys as $tag => $data) {
    echo '  * [[<a style="text-decoration: none;" href="?f=' . urlencode($data['file']) . '&amp;c=' . urlencode($data['code']) . '#highlight" target="_blank">nukeviet5:codex:hooks-reference:' . $tag . '|' . $tag . "</a>]]\n";
}

foreach ($hook_modules as $module => $datas) {
    echo "\n<h2 style=\"margin:0;padding:0;\">===== Hook của module " . $module . " =====</h2>\n";
    foreach ($datas as $tag => $data) {
        echo '  * [[<a style="text-decoration: none;" href="?f=' . urlencode($data['file']) . '&amp;c=' . urlencode($data['code']) . '#highlight" target="_blank">nukeviet5:codex:hooks-reference:' . $module . ':' . $tag . '|' . $tag . "</a>]]\n";
    }
}

echo "\n";
echo '</code></pre>';
