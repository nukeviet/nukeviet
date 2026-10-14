# Công cụ tăng giảm cỡ chữ khi đọc bài

Áp dụng cho giao diện ngoài site. Phần xử lý JS có sẵn trong `src/assets/js/site.js`, module và giao diện chỉ cần thêm HTML và CSS.

Ví dụ đang dùng:

- `src/themes/default/modules/news/detail.tpl`
- `src/themes/default/modules/page/detail.tpl`

## Thêm vào template của module

1. Thêm `data-area="nv-read-area"` vào các khối nội dung cần đổi cỡ chữ, có thể gắn cho nhiều khối:

```html
<div class="article-intro" data-area="nv-read-area">{$DATA.description}</div>
<div class="article-body" data-area="nv-read-area">{$DATA.bodytext}</div>
```

2. Thêm 3 nút vào vị trí tùy chọn trên trang, bọc trong điều kiện trang có nội dung:

```html
{if not empty($DATA.bodytext) or not empty($DATA.description)}
<div>
    <button type="button" class="nv-font-size-btn link-secondary" data-toggle="nv-font-size" data-step="-1" aria-label="{$LANG->getGlobal('font_decrease')}" data-bs-toggle="tooltip" data-bs-trigger="hover" title="{$LANG->getGlobal('font_decrease')}">A<sup>-</sup></button>
    <button type="button" class="nv-font-size-btn link-secondary" data-toggle="nv-font-size" data-step="0" aria-label="{$LANG->getGlobal('font_reset')}" data-bs-toggle="tooltip" data-bs-trigger="hover" title="{$LANG->getGlobal('font_reset')}">A</button>
    <button type="button" class="nv-font-size-btn link-secondary" data-toggle="nv-font-size" data-step="1" aria-label="{$LANG->getGlobal('font_increase')}" data-bs-toggle="tooltip" data-bs-trigger="hover" title="{$LANG->getGlobal('font_increase')}">A<sup>+</sup></button>
</div>
{/if}
```

## Thuộc tính

| Thuộc tính | Giá trị | Gắn vào |
| --- | --- | --- |
| `data-area="nv-read-area"` | | Khối nội dung đổi cỡ chữ |
| `data-toggle="nv-font-size"` | | Nút bấm |
| `data-step` | `-1` giảm, `0` về mặc định, `1` tăng | Nút bấm |
| `aria-disabled="true"` | Tự thêm khi nút hết tác dụng | Nút bấm |

Ngôn ngữ dùng chung: `$LANG->getGlobal('font_decrease')`, `getGlobal('font_reset')`, `getGlobal('font_increase')`.

## Tích hợp vào giao diện khác default

1. Thêm CSS vào SCSS của giao diện rồi build lại:

```scss
[data-area="nv-read-area"] {
  font-size: calc(var(--#{$prefix}body-font-size) * var(--nv-font-scale, 1));
}

.nv-font-size-btn {
  padding: 0 .125rem;
  font-weight: $font-weight-semibold;
  line-height: 1;
  background: none;
  border: 0;
  appearance: none;

  &[aria-disabled="true"] {
    cursor: default;
    opacity: .4;
  }
}
```

Giao diện không dùng Bootstrap 5 thì thay `var(--#{$prefix}body-font-size)` bằng cỡ chữ gốc của giao diện, ví dụ `14px`.

2. Giao diện Bootstrap 3 hoặc 4: bỏ `data-bs-toggle="tooltip"` và `data-bs-trigger="hover"` khỏi nút, khởi tạo tooltip trong JS của giao diện:

```js
$('[data-toggle="nv-font-size"]').tooltip({
    trigger: 'hover'
});
```
