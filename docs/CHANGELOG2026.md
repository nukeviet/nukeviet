# Các thay đổi lớn trong NukeViet 5.0

## Tháng 3 năm 2026

### Quy định về cách dùng json_encode

Đã định nghĩ trong src/includes/constants.php

```php
// JSON encode cho API response và lưu DB
define('NV_JSON_ENCODE', JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

// JSON encode nhúng trong <script> tag HTML
define('NV_JSON_ENCODE_SCRIPT', JSON_HEX_TAG | JSON_HEX_AMP | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
```

Ví dụ
```php
// JSON cho API response
header('Content-Type: application/json; charset=utf-8');
echo json_encode($data, NV_JSON_ENCODE);

// JSON lưu DB / Cache
$cache = json_encode($data, NV_JSON_ENCODE);

// JSON nhúng trong <script> tag HTML
echo json_encode($data, NV_JSON_ENCODE_SCRIPT);
```

### Quy định về cách dùng unserialize

Đã định nghĩ trong src/includes/constants.php

```php
// Option an toàn cho unserialize — chỉ cho phép array/scalar, không cho phép object
define('NV_UNSERIALIZE_SAFE', ['allowed_classes' => false]);
```

Ví dụ
```php
// Unserialize an toàn
$data = unserialize($cache, NV_UNSERIALIZE_SAFE);
```
