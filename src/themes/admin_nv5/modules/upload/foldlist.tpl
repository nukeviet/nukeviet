{function name=printListFolder}
{if not empty($dir)}
    {assign var="pattern" value="/^`$dir|pregquote`\/([^\/]+)$/" nocache}
{else}
    {assign var="pattern" value="/^([^\/]+)$/" nocache}
{/if}
{assign var="dirlistsKey" value=$ALL_FOLDERS|arrayKeys nocache}
{assign var="dirlists" value=$pattern|pregGrep:$dirlistsKey nocache}
{if not empty($dirlists)}
<span class="toggle"><i class="fas"></i></span></a>
<ul>
    {foreach from=$dirlists item=_dir}
    {assign var="dataAllowed" value=$_dir|isAllowed nocache}
    {if not empty($dataAllowed)}
    <li class="{if $_dir eq $CURRENTPATH} active{/if}{if $_dir eq $CURRENTPATH or $CURRENTPATH|strpos:"`$_dir`/" === 0} open{/if}">
        <a href="#" data-folder="{$_dir}" title="{$_dir}" class="{$dataAllowed|getClassOfDir} pos{$_dir|getClassDisplayDirName}"><i class="icon fas fa-folder-open"></i>{$_dir|getDirName}
        {call name=printListFolder dir=$_dir}
    </li>
    {/if}
    {/foreach}
</ul>
{else}
</a>
{/if}
{/function}
<li class="open{if $PATH eq $CURRENTPATH} active{/if}">
    <a href="#" data-folder="{$DATA.title}" title="{$DATA.title}" class="{$DATA.class}"><i class="icon fas fa-folder-open"></i>{$DATA.titlepath}
    {call name=printListFolder dir=$PATH}
    <span class="hidden" id="fmFCPath" data-value="{$PATH}"></span>
    <span class="hidden" id="fmFCFolder" data-value="{$CURRENTPATH}"></span>
    <span class="hidden" id="fmFCAllowedViewFiles" data-value="{$VIEW_DIR}"></span>
    <span class="hidden" id="fmFCAllowedCreatDir" data-value="{$CREATE_DIR}"></span>
    <span class="hidden" id="fmFCAllowedReThumb" data-value="0"></span>
    <span class="hidden" id="fmFCAllowedRenameDir" data-value="{$RENAME_DIR}"></span>
    <span class="hidden" id="fmFCAllowedDeleteDir" data-value="{$DELETE_DIR}"></span>
    <span class="hidden" id="fmFCAllowedUpload" data-value="{$UPLOAD_FILE}"></span>
    <span class="hidden" id="fmFCAllowedCreatFile" data-value="{$CREATE_FILE}"></span>
    <span class="hidden" id="fmFCAllowedRenameFile" data-value="{$RENAME_FILE}"></span>
    <span class="hidden" id="fmFCAllowedDeleteFile" data-value="{$DELETE_FILE}"></span>
    <span class="hidden" id="fmFCAllowedMoveFile" data-value="{$MOVE_FILE}"></span>
    <span class="hidden" id="fmFCAllowedCropFile" data-value="{$CROP_FILE}"></span>
    <span class="hidden" id="fmFCAllowedRorateFile" data-value="{$ROTATE_FILE}"></span>
</li>
