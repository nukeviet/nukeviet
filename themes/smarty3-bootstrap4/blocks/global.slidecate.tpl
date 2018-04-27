<div id="carousel-home-blog" class="carousel ms-carousel slide card" data-ride="carousel">
    <!-- Indicators -->
    <ol class="carousel-indicators">
        <li data-target="#carousel-home-blog" data-slide-to="0" class="active"></li>
        {foreach $l as $l1}
        <li data-target="#carousel-home-blog" data-slide-to="$l1"></li>
        {/foreach}
    </ol>
    <!-- Wrapper for slides -->
    <div class="carousel-inner" role="listbox">
        <div class="carousel-item active">
            <img src="/uploads/news/{$row1.homeimgfile}" class="img-fluid" alt="...">
            <div class="carousel-caption-blog">
                <h2 class="color-primary">{$row1.title}</h2>
                <p class="d-none d-md-block">{$row1.hometext}</p>
            </div>
        </div>
        {foreach $row as $rows}
        <div class="carousel-item">
            <img src="/uploads/news/{$rows.homeimgfile}" class="img-fluid" alt="...">
            <div class="carousel-caption-blog">
                <h2 class="color-primary">{$rows.title}</h2>
                <p class="d-none d-md-block">{$rows.hometext}</p>
            </div>
        </div>
        {/foreach}
    </div>
</div>