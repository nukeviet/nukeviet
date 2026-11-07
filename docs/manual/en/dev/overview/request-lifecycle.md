# Request lifecycle

The request lifecycle is the sequence of steps the system performs from receiving a request to returning the result to the browser. Knowing this order tells you when your module code runs, which variables and constants already exist, and what data each hook can use.

Each step links to the matching line of code on the `nukeviet5.0` branch.

## Overview

<div style="overflow-x:auto;margin:16px 0">
<svg viewBox="0 0 680 570" width="100%" role="img" aria-label="Request lifecycle diagram: shared bootstrap, then the 7-step site flow and the admin flow" style="max-width:680px;min-width:560px;display:block;margin:0 auto;font-family:var(--vp-font-family-base);font-size:13px">
<defs><marker id="lc-arrow-en" viewBox="0 0 10 10" refX="9" refY="5" markerWidth="7" markerHeight="7" orient="auto-start-reverse"><path d="M0,0 L10,5 L0,10 z" style="fill:var(--vp-c-text-2)"/></marker></defs>
<rect x="60" y="10" width="300" height="36" rx="18" style="fill:var(--vp-c-brand-soft);stroke:var(--vp-c-brand-1)"/>
<text x="210" y="33" text-anchor="middle" style="fill:var(--vp-c-text-1);font-weight:600">Request from the browser</text>
<line x1="210" y1="46" x2="210" y2="68" style="stroke:var(--vp-c-text-2)" marker-end="url(#lc-arrow-en)"/>
<rect x="20" y="70" width="380" height="48" rx="6" style="fill:var(--vp-c-bg-soft);stroke:var(--vp-c-divider)"/>
<text x="36" y="90" style="fill:var(--vp-c-text-1);font-weight:600">1. Shared bootstrap</text>
<text x="36" y="108" style="fill:var(--vp-c-text-2);font-family:var(--vp-font-family-mono);font-size:12px">includes/mainfile.php</text>
<line x1="210" y1="118" x2="210" y2="132" style="stroke:var(--vp-c-text-2)" marker-end="url(#lc-arrow-en)"/>
<text x="218" y="129" style="fill:var(--vp-c-text-2);font-size:11px">index.php</text>
<rect x="20" y="134" width="380" height="48" rx="6" style="fill:var(--vp-c-bg-soft);stroke:var(--vp-c-divider)"/>
<text x="36" y="154" style="fill:var(--vp-c-text-1);font-weight:600">2. Identify the member</text>
<text x="36" y="172" style="fill:var(--vp-c-text-2);font-family:var(--vp-font-family-mono);font-size:12px">core/is_user.php → NV_IS_USER</text>
<line x1="210" y1="182" x2="210" y2="196" style="stroke:var(--vp-c-text-2)" marker-end="url(#lc-arrow-en)"/>
<rect x="20" y="198" width="380" height="48" rx="6" style="fill:var(--vp-c-bg-soft);stroke:var(--vp-c-divider)"/>
<text x="36" y="218" style="fill:var(--vp-c-text-1);font-weight:600">3. Resolve module, check view permission</text>
<text x="36" y="236" style="fill:var(--vp-c-text-2);font-family:var(--vp-font-family-mono);font-size:12px">$site_mods → $module_info, $module_file...</text>
<line x1="210" y1="246" x2="210" y2="260" style="stroke:var(--vp-c-text-2)" marker-end="url(#lc-arrow-en)"/>
<rect x="20" y="262" width="380" height="48" rx="6" style="fill:var(--vp-c-bg-soft);stroke:var(--vp-c-divider)"/>
<text x="36" y="282" style="fill:var(--vp-c-text-1);font-weight:600">4. Resolve function</text>
<text x="36" y="300" style="fill:var(--vp-c-text-2);font-family:var(--vp-font-family-mono);font-size:12px">$op, $array_op</text>
<line x1="210" y1="310" x2="210" y2="324" style="stroke:var(--vp-c-text-2)" marker-end="url(#lc-arrow-en)"/>
<rect x="20" y="326" width="380" height="48" rx="6" style="fill:var(--vp-c-bg-soft);stroke:var(--vp-c-divider)"/>
<text x="36" y="346" style="fill:var(--vp-c-text-1);font-weight:600">5. Choose theme, layout</text>
<text x="36" y="364" style="fill:var(--vp-c-text-2);font-family:var(--vp-font-family-mono);font-size:12px">themes/&lt;theme&gt;/theme.php</text>
<line x1="210" y1="374" x2="210" y2="388" style="stroke:var(--vp-c-text-2)" marker-end="url(#lc-arrow-en)"/>
<rect x="20" y="390" width="380" height="48" rx="6" style="fill:var(--vp-c-bg-soft);stroke:var(--vp-c-divider)"/>
<text x="36" y="410" style="fill:var(--vp-c-text-1);font-weight:600">6. Load module files</text>
<text x="36" y="428" style="fill:var(--vp-c-text-2);font-family:var(--vp-font-family-mono);font-size:12px">functions.php → theme.php → funcs/&lt;op_file&gt;.php</text>
<line x1="210" y1="438" x2="210" y2="452" style="stroke:var(--vp-c-text-2)" marker-end="url(#lc-arrow-en)"/>
<rect x="20" y="454" width="380" height="48" rx="6" style="fill:var(--vp-c-bg-soft);stroke:var(--vp-c-divider)"/>
<text x="36" y="474" style="fill:var(--vp-c-text-1);font-weight:600">7. Build and send the page</text>
<text x="36" y="492" style="fill:var(--vp-c-text-2);font-family:var(--vp-font-family-mono);font-size:12px">header.php → nv_site_theme() → footer.php</text>
<line x1="210" y1="502" x2="210" y2="516" style="stroke:var(--vp-c-text-2)" marker-end="url(#lc-arrow-en)"/>
<rect x="60" y="518" width="300" height="36" rx="18" style="fill:var(--vp-c-brand-soft);stroke:var(--vp-c-brand-1)"/>
<text x="210" y="541" text-anchor="middle" style="fill:var(--vp-c-text-1);font-weight:600">HTML to the browser</text>
<line x1="400" y1="94" x2="438" y2="94" style="stroke:var(--vp-c-text-2);stroke-dasharray:4 3"/>
<rect x="440" y="70" width="220" height="48" rx="6" style="fill:none;stroke:var(--vp-c-text-3);stroke-dasharray:4 3"/>
<text x="452" y="90" style="fill:var(--vp-c-text-2);font-size:12px">Hook: check_server, sector1,</text>
<text x="452" y="108" style="fill:var(--vp-c-text-2);font-size:12px">modify_global_config, sector2</text>
<path d="M400,108 H420 V206 H438" style="fill:none;stroke:var(--vp-c-text-2)" marker-end="url(#lc-arrow-en)"/>
<rect x="440" y="140" width="220" height="150" rx="6" style="fill:var(--vp-c-bg-soft);stroke:var(--vp-c-divider)"/>
<text x="452" y="162" style="fill:var(--vp-c-text-1);font-weight:600">Admin: admin/index.php</text>
<text x="452" y="184" style="fill:var(--vp-c-text-2);font-size:12px">Check admin login</text>
<text x="452" y="204" style="fill:var(--vp-c-text-2);font-size:12px">Resolve module or area</text>
<text x="452" y="224" style="fill:var(--vp-c-text-2);font-family:var(--vp-font-family-mono);font-size:11px">admin.menu.php</text>
<text x="452" y="240" style="fill:var(--vp-c-text-2);font-family:var(--vp-font-family-mono);font-size:11px">admin.functions.php</text>
<text x="452" y="260" style="fill:var(--vp-c-text-2);font-size:12px">Check op in $allow_func</text>
<text x="452" y="280" style="fill:var(--vp-c-text-2);font-family:var(--vp-font-family-mono);font-size:11px">admin/&lt;op&gt;.php → nv_admin_theme()</text>
<line x1="400" y1="414" x2="438" y2="414" style="stroke:var(--vp-c-text-2)" marker-end="url(#lc-arrow-en)"/>
<rect x="440" y="390" width="220" height="48" rx="6" style="fill:none;stroke:var(--vp-c-text-3);stroke-dasharray:4 3"/>
<text x="452" y="410" style="fill:var(--vp-c-text-2);font-size:12px">JSON request:</text>
<text x="452" y="428" style="fill:var(--vp-c-text-2);font-family:var(--vp-font-family-mono);font-size:12px">nv_jsonOutput() → exit</text>
<line x1="400" y1="478" x2="438" y2="478" style="stroke:var(--vp-c-text-2);stroke-dasharray:4 3"/>
<rect x="440" y="454" width="220" height="48" rx="6" style="fill:none;stroke:var(--vp-c-text-3);stroke-dasharray:4 3"/>
<text x="452" y="474" style="fill:var(--vp-c-text-2);font-size:12px">Hook: change_site_buffer</text>
<text x="452" y="492" style="fill:var(--vp-c-text-2);font-size:12px">modify HTML, headers before sending</text>
</svg>
</div>

Dashed boxes are hooks and branches. A site request goes through these steps:

| Step | Work | Handled in |
|---|---|---|
| 1 | Shared bootstrap | `includes/mainfile.php` |
| 2 | Identify the member | `includes/core/is_user.php` |
| 3 | Resolve the module, check view permission | `index.php` |
| 4 | Resolve the function: `$op`, `$array_op` | `index.php` |
| 5 | Choose theme, layout | `index.php` |
| 6 | Load module files | the module's `functions.php`, `theme.php`, `funcs/<op>.php` |
| 7 | Build and send the page | Function file, `nv_site_theme()`, `includes/footer.php` |

The admin area shares step 1, then follows its own flow; see [Admin area](#admin-area).

## 1. Shared bootstrap

Every entry point file (`index.php`, `admin/index.php`, `api.php`, `sload.php`, `mload.php`...) defines a constant marking the request type, then loads `includes/mainfile.php` ([src/index.php#L12](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/index.php#L12)). This file builds the shared environment in a fixed order:

| Order | Work | Available afterwards | Code line |
|---|---|---|---|
| 1 | Load constants, pre-generated configuration, Composer autoload | System constants, `$global_config` from file | [L30](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/includes/mainfile.php#L30) |
| 2 | Load `config.php`. If missing, redirect to the installer | `$db_config` | [L57](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/includes/mainfile.php#L57) |
| 3 | Load core function files | Shared functions | [L139](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/includes/mainfile.php#L139) |
| 4 | Parse friendly URLs into `$_GET`, create the request object | `$nv_Request` | [L151](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/includes/mainfile.php#L151) |
| 5 | Determine the language | `NV_LANG_DATA`, `NV_LANG_INTERFACE`, `$nv_Lang` | [L194](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/includes/mainfile.php#L194) |
| 6 | Load registered plugins, call hook `check_server` | Plugins | [L218](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/includes/mainfile.php#L218) |
| 7 | Cache, device detection, captcha, flood protection, hook `sector1` | `$nv_Cache`, `$client_info` | [L257](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/includes/mainfile.php#L257) |
| 8 | Connect to the database, read configuration | `$db`, full `$global_config`, `$module_config` | [L329](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/includes/mainfile.php#L329) |
| 9 | Run cronjobs when due | | [L458](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/includes/mainfile.php#L458) |
| 10 | Check the admin session, close the site during maintenance or upgrade | `$admin_info`, `NV_IS_ADMIN`... | [L501](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/includes/mainfile.php#L501) |
| 11 | Hook `modify_global_config`, read the module list, hook `sector2` | `$sys_mods` | [L536](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/includes/mainfile.php#L536) |

::: warning
Plugins are loaded at order 6, before the database exists. A callback can only use what exists when its hook is called:

| Hook | Available | Not yet available |
|---|---|---|
| `check_server` | Request, language | Cache, database |
| `sector1` | Cache, device info | Database |
| `modify_global_config` | Database, configuration, admin session | `$sys_mods` |
| `sector2` | `$sys_mods` | Current module |
:::

## 2. Identify the member

The system checks the login cookie against the session stored in the database. Only when valid are `$user_info` and the constant `NV_IS_USER` set ([src/index.php#L41](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/index.php#L41)).

A logged-in administrator is also treated as a site member; `$user_info` is taken from `$admin_info` ([src/includes/core/is_user.php](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/includes/core/is_user.php)).

## 3. Resolve the module and check view permission

1. Read the module name from the `nv` parameter. If absent, use the home module from configuration ([L86](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/index.php#L86))
2. Build `$site_mods`: the modules the visitor may access, based on the groups in each module's view permission ([L138](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/index.php#L138))
3. Module not in `$site_mods` ([L141](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/index.php#L141)):
    - Members only (group 4) and the visitor is a guest: redirect to the login page
    - Administrators only (groups 1, 2) and the visitor is not an administrator: show an error
    - Otherwise: 404 error
4. Set the module variables: `$module_info`, `$module_file`, `$module_data`, `$module_upload` ([L177](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/index.php#L177))

## 4. Resolve the function

Read the op from the `op` parameter, default `main`. When the op matches no alias of the module, it is split by `/` into `$array_op` ([L188](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/index.php#L188)). Details: [Concepts → `$array_op`](./concepts.md#array-op).

Visitors with admin rights on the current module also get the constant `NV_IS_MODADMIN` ([L209](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/index.php#L209)).

## 5. Choose theme and layout

1. Load the module's language file ([L232](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/index.php#L232))
2. Choose the theme in this order: the theme the visitor picked (if allowed), the module's own theme, the site theme. Mobile devices use the mobile theme when configured ([src/includes/core/user_functions.php#L1082](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/includes/core/user_functions.php#L1082))
3. Read the layouts assigned to each function of the module
4. Create `$csrf_key`, used for the module's anti-CSRF tokens
5. Read block positions, load `themes/<theme>/theme.php` and the theme's language ([L279](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/index.php#L279))

## 6. Load module files

Fixed order ([L295](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/index.php#L295)):

1. `modules/<module_file>/functions.php`
2. Resolve `$op_file` from `$op`. This runs after `functions.php`, so the module can change `$op` in step 1
3. `theme.php`: `themes/<theme>/modules/<module>/theme.php` first, otherwise `modules/<module_file>/theme.php`
4. `modules/<module_file>/funcs/<op_file>.php`, or `mobile/<op_file>.php` when a mobile theme is in use and that file exists

Function files run in global scope, so they can use `$db`, `$nv_Request`, `$module_info`, `$op`... directly. Inside your own functions, declare them `global`.

## 7. Build and send the page

The function file calls the last three steps itself:

1. `includes/header.php`: starts output buffering
2. `nv_site_theme($contents)`: wraps `$contents`, the module's HTML, into the function's layout; replaces position tags with block HTML; adds meta tags, CSS, JS
3. `includes/footer.php`: rewrites links to friendly URLs, calls hook `change_site_buffer` ([src/includes/footer.php#L39](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/includes/footer.php#L39)), sends headers and content, ends the request

::: info Example
End of the file [src/modules/page/funcs/main.php#L240](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/modules/page/funcs/main.php#L240):

```php
include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
```
:::

Requests returning JSON skip these three steps: the function file calls `nv_jsonOutput($data)` and exits.

## Admin area

A request to `admin/index.php` shares the bootstrap step, then follows its own flow ([src/admin/index.php](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/admin/index.php)):

1. Not logged in as administrator: show the login form and stop ([L19](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/admin/index.php#L19))
2. Read the system admin areas allowed for the admin level, and the modules assigned to the administrator
3. Read the module or area name from the `nv` parameter, defaulting to the administrator's main module. Read the op from the `op` parameter, default `main`
4. Determine the files to load ([L74](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/admin/index.php#L74)):
    - Module: `modules/<module_file>/admin.functions.php`, `admin.menu.php` and `admin/<op>.php`
    - System area: `admin/<area>/functions.php`, `admin.menu.php` and `admin/<area>/<op>.php`
5. When both files exist, define `NV_IS_MODADMIN` ([L100](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/admin/index.php#L100))
6. Load the admin theme, `admin.menu.php`, `admin.functions.php` ([L128](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/admin/index.php#L128))
7. An op not listed in the `$allow_func` declared by `admin.menu.php` gets an error ([L173](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/admin/index.php#L173))
8. Load the op file. It ends with `header.php`, `nv_admin_theme($contents)`, `footer.php`

::: tip `NV_IS_FILE_ADMIN`
The system does not define this constant. Each module defines it in `admin.functions.php`, and every file in the module's `admin/` checks it at the top to block direct access.
:::

::: info Example
The `page` module defines the constant in [src/modules/page/admin.functions.php#L26](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/modules/page/admin.functions.php#L26); the file [src/modules/page/admin/main.php#L12](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/modules/page/admin/main.php#L12) checks it:

```php
if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}
```
:::
