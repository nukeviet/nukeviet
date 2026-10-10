<div class="p-3 pt-0">
    {if $ROWS}
    <div class="card">
        <div class="card-body">
            <div class="table-responsive-lg table-card">
                <table class="table table-striped align-middle mb-0">
                    <thead>
                        <tr>
                            {if $ACCESS_VIEWLIST}
                            <th class="text-nowrap" style="width:8%">
                                <a href="{$ORDER_ID.url}">ID {if $ORDER_ID.class == 'asc'}<i class="fa-solid fa-sort-up"></i>{elseif $ORDER_ID.class == 'desc'}<i class="fa-solid fa-sort-down"></i>{else}<i class="fa-solid fa-sort text-muted"></i>{/if}</a>
                            </th>
                            <th class="text-nowrap" style="width:22%">
                                <a href="{$ORDER_USERNAME.url}">{$LANG->getGlobal('username')} {if $ORDER_USERNAME.class == 'asc'}<i class="fa-solid fa-sort-up"></i>{elseif $ORDER_USERNAME.class == 'desc'}<i class="fa-solid fa-sort-down"></i>{else}<i class="fa-solid fa-sort text-muted"></i>{/if}</a>
                            </th>
                            <th class="text-nowrap">
                                <a href="{$ORDER_EMAIL.url}">{$LANG->getModule('email')} {if $ORDER_EMAIL.class == 'asc'}<i class="fa-solid fa-sort-up"></i>{elseif $ORDER_EMAIL.class == 'desc'}<i class="fa-solid fa-sort-down"></i>{else}<i class="fa-solid fa-sort text-muted"></i>{/if}</a>
                            </th>
                            <th class="text-nowrap" style="width:18%">
                                <a href="{$ORDER_REGDATE.url}">{$LANG->getModule('regdate')} {if $ORDER_REGDATE.class == 'asc'}<i class="fa-solid fa-sort-up"></i>{elseif $ORDER_REGDATE.class == 'desc'}<i class="fa-solid fa-sort-down"></i>{else}<i class="fa-solid fa-sort text-muted"></i>{/if}</a>
                            </th>
                            {else}
                            <th class="text-nowrap" style="width:8%">ID</th>
                            <th class="text-nowrap" style="width:22%">{$LANG->getGlobal('username')}</th>
                            <th class="text-nowrap">{$LANG->getModule('email')}</th>
                            <th class="text-nowrap" style="width:18%">{$LANG->getModule('regdate')}</th>
                            {/if}
                            <th class="text-center text-nowrap" style="width:10%">{$LANG->getModule('select')}</th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach from=$ROWS item=row}
                        <tr>
                            <td><strong>{$row.userid}</strong></td>
                            <td>{$row.username}</td>
                            <td>{$row.email}</td>
                            <td>{$row.regdate}</td>
                            <td class="text-center">
                                <a href="#"
                                class="btn btn-sm btn-primary"
                                data-toggle="select-user"
                                data-value="{$row.return}"
                                data-area="{$AREA}">{$LANG->getModule('select')}</a>
                            </td>
                        </tr>
                        {/foreach}
                    </tbody>
                </table>
            </div>
        </div>
        {if $ACCESS_VIEWLIST and $PAGINATION}
        <div class="card-footer border-top">
            <div class="d-flex justify-content-end">
                <div class="pagination-wrap">{$PAGINATION}</div>
            </div>
        </div>
        {/if}
    </div>
    {else}
    <div class="alert alert-warning mt-2 mb-0">{$LANG->getModule('noresult')}</div>
    {/if}
</div>
