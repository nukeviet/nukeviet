<div id="inform" class="panel panel-default inform" data-page-url="{$PAGE_URL}">
    <div class="panel-heading filter-select">
        <label>{$LANG->getModule('filter_by_criteria')}</label>
        <select class="form-control" name="filter">
            <option value="">{$LANG->getModule('filter_all')}</option>
{foreach $FILTERS as $key => $name}
            <option value="{$key}">{$name}</option>
{/foreach}
        </select>
    </div>
    <div class="load_content" id="generate_page"></div>
</div>