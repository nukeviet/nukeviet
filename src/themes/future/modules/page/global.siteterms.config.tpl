<div class="row mb-3">
    <label class="col-sm-3 col-form-label text-sm-end text-truncate fw-bold">
        {$LANG->getModule('links')}:
    </label>
    <div class="col-sm-9 list">
        {foreach $TERM_DATA as $term}
        <div class="input-group mb-2 item">
            {if !empty($LIST)}
            <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"></button>
            <ul class="dropdown-menu">
                {foreach $LIST as $item}
                <li><a class="dropdown-item" href="javascript:void(0);" data-url="{$item.url}" data-bs-toggle="sample_term">{$item.title}</a></li>
                {/foreach}
            </ul>
            {/if}

            <input type="text" name="term_names[]" value="{$term.name}"placeholder="{$LANG->getModule('term_name')}" class="form-control">
            <input type="text" name="term_queries[]" value="{$term.query}" placeholder="{$LANG->getModule('term_query')}" class="form-control w-25">
            <button class="btn btn-danger ms-1" type="button" data-bs-toggle="del_term" title="{$LANG->getGlobal('delete')}">
                <i class="bi bi-trash">&times;</i>
            </button>
            <button class="btn btn-primary ms-1" type="button" data-bs-toggle="add_term" title="{$LANG->getGlobal('add')}">
                <span>+</span>
            </button>
        </div>
        {/foreach}
    </div>
</div>

<script type="text/javascript">
$(function() {
    // Xử lý xóa dòng
    $('body').on('click', '[data-bs-toggle="del_term"]', function(e) {
        e.preventDefault();
        var $list = $(this).closest('.list');
        var $item = $(this).closest('.item');

        if ($('.item', $list).length > 1) {
            $item.remove();
        } else {
            $item.find('input').val('');
        }
    });

    // Xử lý thêm dòng mới
    $('body').on('click', '[data-bs-toggle="add_term"]', function(e) {
        e.preventDefault();
        var $item = $(this).closest('.item');
        var $newitem = $item.clone();

        $newitem.find('input').val('');
        $item.after($newitem);
    });

    // Xử lý khi chọn mẫu từ dropdown lấy link bài viết
    $('body').on('click', '[data-bs-toggle="sample_term"]', function(e) {
        e.preventDefault();
        var $item = $(this).closest('.item');
        var name = $(this).text().trim();
        var url = $(this).data('url');

        var $nameInput = $item.find('[name^="term_names"]');
        if ($nameInput.val() === '') {
            $nameInput.val(name);
        }

        $item.find('[name^="term_queries"]').val(url);
    });
});
</script>
