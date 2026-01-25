{if $SENDINFO}
{$LANG->getModule('sendinfo')} <a href="{$SITE_URL}">{$SITE_NAME}</a>.<br />
{$LANG->getModule('sendinfo2')}:<br />
<ul>
{if !empty($FEEDBACK.category)}
    <li>{$LANG->getModule('cat')}: {$FEEDBACK.category}</li>
{/if}
    <li>{$LANG->getModule('part')}: {$PART}</li>
    <li>{$LANG->getModule('fullname')}: {$FEEDBACK.sender_name}</li>
    <li>{$LANG->getModule('email')}: {$FEEDBACK.sender_email}</li>
{if !empty($FEEDBACK.filter_sender_phone)}
    <li>{$LANG->getModule('phone')}: {$FEEDBACK.filter_sender_phone}</li>
{/if}
    <li>IP: {$IP}</li>
</ul>
-------------------------------------<br /><br />
{else}
{$LANG->getModule('hello')} {$FEEDBACK.sender_name},<br />
{$LANG->getModule('mysendinfo')} <a href="{$SITE_URL}">{$SITE_NAME}</a>.<br />
{$LANG->getModule('mysendinfo2')}:<br />
<ul>
{if !empty($FEEDBACK.category)}
    <li>{$LANG->getModule('cat')}: {$FEEDBACK.category}</li>
{/if}
    <li>{$LANG->getModule('part')}: {$PART}</li>
    <li>{$LANG->getModule('fullname')}: {$FEEDBACK.sender_name}</li>
    <li>{$LANG->getModule('email')}: {$FEEDBACK.sender_email}</li>
{if !empty($FEEDBACK.filter_sender_phone)}
    <li>{$LANG->getModule('phone')}: {$FEEDBACK.filter_sender_phone}</li>
{/if}
</ul>
-------------------------------------<br /><br />
{/if}
<strong>{$FEEDBACK.filter_title}</strong><br /><br />
{$FEEDBACK.filter_content}<br /><br />
