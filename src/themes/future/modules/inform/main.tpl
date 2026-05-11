<div id="inform" class="card inform" data-page-url="{$PAGE_URL}">
    <div class="card-header filter-select d-flex align-items-center gap-2">
        <label class="form-label mb-0">{$LANG->getModule('filter_by_criteria')}</label>
        <select class="form-select form-select-sm w-auto" name="filter">
            <option value="">{$LANG->getModule('filter_all')}</option>
            {foreach $FILTERS as $filter}
            <option value="{$filter.key}">{$filter.title}</option>
            {/foreach}
        </select>
    </div>
    <div class="card-body p-0 load_content" id="generate_page"></div>
</div>
