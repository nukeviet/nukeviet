# Các công cụ quét và chuyển đổi lang NukeViet 4 sang NukeViet 5

`php ConvertAll.php` => Chuyển đổi tất cả các cách dùng lang cũ sang lang mới.

`php ConvertLangBlock.php` => Chuyển đổi các block sang kiểu dùng ngôn ngữ mới.

`php CheckOldLang.php` => Kiểm tra lại còn sót cách dùng lang cũ ví dụ `$lang_module['key']`, `$lang_global['key']`. Nếu không hiển thị file nào tức là đã được chuyển hết sang cách dùng mới. Nếu hiển thị cần mở các file đó ra chỉnh sửa thủ công.

`php CheckNewLangError.php` => Kiểm tra lại xem quá trình chuyển đổi có gây ra lỗi cú pháp không ví dụ. `$nv_Lang->getModule('key') = 'xxx'` khi đó cần sửa lại tương ứng `$nv_Lang->setModule('key', 'xxx')`.

LangCompare.php => Chạy trên website, không chạy qua command line mục đích gộp lang admin các module vào một file lang duy nhất.
Chú ý khi chạy file này thì mở nó lên sửa

```
$currentModule = 'Users';
$lang = 'fr';
```

Thành module và ngôn ngữ cần làm
