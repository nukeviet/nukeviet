Tìm dòng 1789:
##########
        $last           = strtolower(substr($v_memory_limit, -1));
##########
Thay bằng:
##########
        $last           = strtolower(substr($v_memory_limit, -1));
        $v_memory_limit = preg_replace('/\s*[KkMmGg]$/', '', $v_memory_limit);
##########
Tìm dòng 2594-2595:
##########
                    // ----- Read the file content
                    $v_content = @fread($v_file, $p_header['size']);
##########
Thay bằng:
##########
                    // ----- Read the file content
                    if ($p_header['size'] <= 0) {
                        // File rỗng
                        $v_content = '';
                    } else {
                        $v_content = @fread($v_file, $p_header['size']);
                    }
##########