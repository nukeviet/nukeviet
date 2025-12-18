
<div class="position-relative{if count($DEPARTMENTS) lt 2} d-none{/if}">
    <ul class="nav nav-pills mb-3 overflow-auto flex-nowrap gap-2 px-1 pe-5 tabs-scroll position-relative" role="tablist">
        {foreach from=$DEPARTMENTS item=dep}
        <li class="nav-item">
            <a class="nav-link rounded-pill px-3 py-2 fw-semibold text-nowrap{if $dep.active} active{/if}" href="#dep-{$dep.id}" role="tab" data-bs-toggle="tab" aria-controls="dep-{$dep.id}" aria-selected="{if $dep.active}true{else}false{/if}">{$dep.full_name}</a>
        </li>
        {/foreach}
    </ul>
    <div data-role="fade-right" class="position-absolute top-0 bottom-0 end-0" style="width:48px;background:linear-gradient(to left, var(--bs-body-bg, #fff), rgba(255,255,255,0));pointer-events:none;"></div>
</div>

<script>
(function(){
  var wrap = document.currentScript.previousElementSibling;
  if(!wrap) return;
  var nav = wrap.querySelector('.nav');
  var fade = wrap.querySelector('[data-role="fade-right"]');
  if(!nav || !fade) return;

  var reveal = function(el){
    if(!el) return;
    // Tính toán vị trí center dựa trên offsetLeft (khi nav có position-relative)
    var scrollLeft = el.offsetLeft - (nav.clientWidth / 2) + (el.clientWidth / 2);
    nav.scrollTo({ left: scrollLeft, behavior: 'smooth' });
  };

  var updateFade = function(){
    var hide = nav.scrollLeft + nav.clientWidth >= nav.scrollWidth - 1;
    var need = nav.scrollWidth > nav.clientWidth;
    fade.style.opacity = (!need || hide) ? '0' : '1';
  };

  window.requestAnimationFrame(function(){
    reveal(nav.querySelector('.nav-link.active'));
    updateFade();
  });
  nav.addEventListener('shown.bs.tab', function(e){ reveal(e.target); updateFade(); });
  // Center on click (before shown), similar to React example
  nav.addEventListener('click', function(e){
    var a = e.target && e.target.closest ? e.target.closest('a.nav-link') : null;
    if(!a || !nav.contains(a)) return;
    reveal(a);
    updateFade();
  });
  nav.addEventListener('scroll', updateFade);
  window.addEventListener('resize', updateFade);
})();
</script>

<div class="tab-content">
    {foreach from=$DEPARTMENTS item=dep}
    <div class="tab-pane fade{if $dep.active} show active{/if}" id="dep-{$dep.id}" role="tabpanel">
        {foreach from=$SUPPORTERS[$dep.id] item=supporter}
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <img src="{$supporter.image}" class="rounded-circle border" style="width:48px;height:48px;object-fit:cover;" alt="{$supporter.full_name}">
                    <div class="ms-3">
                        <h6 class="mb-0 fw-bold">{$supporter.full_name}</h6>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    {if $supporter.has_call}
                    <a href="{$supporter.call_href}" class="btn btn-light rounded-3 px-4 d-inline-flex align-items-center justify-content-center flex-fill">
                        <i class="fa-solid fa-phone me-2 text-primary"></i>
                        <span>Gọi</span>
                    </a>
                    {/if}
                    {if $supporter.has_email}
                    <a href="{$supporter.email_href}" class="btn btn-light rounded-3 px-4 d-inline-flex align-items-center justify-content-center flex-fill">
                        <i class="fa-solid fa-envelope me-2 text-primary"></i>
                        <span>Email</span>
                    </a>
                    {/if}
                </div>
            </div>
        </div>
        {/foreach}
    </div>
    {/foreach}
</div>
