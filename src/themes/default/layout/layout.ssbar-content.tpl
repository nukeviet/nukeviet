{include file='header_only.tpl'}
{include file='header_extended.tpl'}
<div class="main-content">
    <div class="container g-4 py-4">
        [THEME_ERROR_INFO]
        [HEADER]
        <div class="row g-4">
            <aside class="main-start-lg">
                <div class="vstack vstack-blocks">
                    [START_SIDEBAR]
                </div>
            </aside>
            <main class="main-content-lg">
                {$MODULE_CONTENT}
            </main>
        </div>
    </div>
</div>
{include file='footer_extended.tpl'}
{include file='footer_only.tpl'}
