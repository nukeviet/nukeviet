<div class="list-group list-group-flush">
    {if !empty($DEPARTMENT.image)}
    <div class="list-group-item px-0">
        <div class="row g-3 align-items-start">
            <div class="col-md-3 fw-semibold text-md-end">{$LANG->getModule('image')}:</div>
            <div class="col-md-9">
                <img src="{$DEPARTMENT.image}" class="img-thumbnail" alt="">
            </div>
        </div>
    </div>
    {/if}

    <div class="list-group-item px-0">
        <div class="row g-3 align-items-start">
            <div class="col-md-3 fw-semibold text-md-end">{$LANG->getModule('part_row_title')}:</div>
            <div class="col-md-9">{$DEPARTMENT.full_name}</div>
        </div>
    </div>

    <div class="list-group-item px-0">
        <div class="row g-3 align-items-start">
            <div class="col-md-3 fw-semibold text-md-end">{$LANG->getModule('note_row_title')}:</div>
            <div class="col-md-9">{$DEPARTMENT.note}</div>
        </div>
    </div>

    {if !empty($DEPARTMENT.phone)}
    <div class="list-group-item px-0">
        <div class="row g-3 align-items-start">
            <div class="col-md-3 fw-semibold text-md-end">{$LANG->getGlobal('phonenumber')}:</div>
            <div class="col-md-9">{$DEPARTMENT.phone}</div>
        </div>
    </div>
    {/if}

    {if !empty($DEPARTMENT.fax)}
    <div class="list-group-item px-0">
        <div class="row g-3 align-items-start">
            <div class="col-md-3 fw-semibold text-md-end">Fax:</div>
            <div class="col-md-9">{$DEPARTMENT.fax}</div>
        </div>
    </div>
    {/if}

    {if !empty($DEPARTMENT.email)}
    <div class="list-group-item px-0">
        <div class="row g-3 align-items-start">
            <div class="col-md-3 fw-semibold text-md-end">{$LANG->getGlobal('email')}:</div>
            <div class="col-md-9">{$DEPARTMENT.email}</div>
        </div>
    </div>
    {/if}

    {if !empty($DEPARTMENT.address)}
    <div class="list-group-item px-0">
        <div class="row g-3 align-items-start">
            <div class="col-md-3 fw-semibold text-md-end">{$LANG->getModule('address')}:</div>
            <div class="col-md-9">{$DEPARTMENT.address}</div>
        </div>
    </div>
    {/if}

    {foreach $DEPARTMENT.others as $OTHER_TITLE => $OTHER_VALUE}
    <div class="list-group-item px-0">
        <div class="row g-3 align-items-start">
            <div class="col-md-3 fw-semibold text-md-end">{$OTHER_TITLE|ucfirst}:</div>
            <div class="col-md-9">{$OTHER_VALUE|replace:',':'<br>'}</div>
        </div>
    </div>
    {/foreach}

    {if !empty($DEPARTMENT.cats)}
    <div class="list-group-item px-0">
        <div class="row g-3 align-items-start">
            <div class="col-md-3 fw-semibold text-md-end">{$LANG->getModule('cats')}:</div>
            <div class="col-md-9">
                <ul class="mb-0 ps-3">
                    {foreach $DEPARTMENT.cats as $cat}
                    <li>{$cat}</li>
                    {/foreach}
                </ul>
            </div>
        </div>
    </div>
    {/if}

    <div class="list-group-item px-0 pb-0">
        <div class="row g-3 align-items-start">
            <div class="col-md-3 fw-semibold text-md-end">{$LANG->getModule('your_authority')}:</div>
            <div class="col-md-9">
                {if !empty($DEPARTMENT.your_authority)}
                <ul class="mb-0 ps-3">
                    {foreach $DEPARTMENT.your_authority as $authority}
                    <li>{$authority}</li>
                    {/foreach}
                </ul>
                {else}
                {$LANG->getModule('your_not_authority')}
                {/if}
            </div>
        </div>
    </div>
</div>
