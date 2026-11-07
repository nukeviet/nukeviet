# Concepts

Terms used throughout the NukeViet 5 developer docs. Each section covers: definition, how it works, rules for writing code.

A site page is built as follows:

1. The **module** receives the request, the matching **function** (op) builds the HTML of the content area
2. The **theme** wraps the content into the **layout** assigned to that function
3. Each **block position** in the layout is replaced with the HTML of the **blocks** placed there
4. **Plugins** step in at the **hooks** placed along all the steps above

**Cronjobs** run on a schedule, separate from page building.

## Module

A module is an extension that handles one specific feature of the system: news (`news`), members (`users`), contact (`contact`)... Each module has its own site pages, admin pages, database tables and language files. It works fairly independently but runs inside the system framework: it shares routing, permissions, themes and configuration.

NukeViet 5 ships with a set of modules. Other modules are installed from the extension store at Admin → Extensions, or removed from the system.

- Code: `modules/<name>/`. The module's templates live in the theme: `themes/<theme>/modules/<name>/`
- Declaration: `version.php`, array `$module_version`
- Management, enable/disable: Admin → Modules

### Original module and virtual module

A module installed by placing code in `modules/` is an **original module**. If the original module allows it, the system can clone it into more modules with the same features, called **virtual modules**. Each virtual module has its own name, URL, data tables and upload directory but shares the code of the original module, without writing a single line of code.

::: info Example
A default install has `about` (About) and `siteterms` (Terms) as two virtual modules of the original module `page`.
:::

- Requirement: `version.php` of the original module has `'virtual' => 1`
- Create: Admin → Modules → Add virtual module, choose the Original module and enter the Module name
- Every module, original or virtual, is recorded in the table `<prefix>_<lang>_modules`

When handling a request, the router reads the module record and sets 4 variables; none of them is derived from another ([src/index.php#L177](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/index.php#L177)):

| Variable | Used for | Virtual module `about` |
|---|---|---|
| `$module_name` | URL, configuration `$module_config[$module_name]` | `about` |
| `$module_file` | Code path `modules/$module_file/` | `page` |
| `$module_data` | Table name `<prefix>_<lang>_$module_data` | `about` |
| `$module_upload` | Directory `uploads/$module_upload/` | `about` |

::: warning
Do not hard-code the module name in table names or paths. `NV_PREFIXLANG . '_page'` works for the original module but makes a virtual module read the original module's table.
:::

### System module

A module with `'is_sysmod' => 1` in `version.php` cannot be deleted.

::: info Example
System modules in a default install: `users`, `page`, `menu`, `banners`, `feeds`, `seek`, `inform`, `myapi`, `two-step-verification`. `news` and `contact` are not system modules.
:::

## Function (op)

A function is one feature of a module, matching one page or one action: the `news` module has functions to list articles, view a category, view an article, output RSS... In URLs and code, a function is called `op`. When no op is given, it defaults to `main`.

- Site: each function is a file `modules/<module>/funcs/<op>.php`
- Admin: each page is a file `modules/<module>/admin/<op>.php`, the menu is declared in `admin.menu.php`

Site functions must be declared in the `modfuncs` key of `version.php`. This list is written to the table `<prefix>_<lang>_modfuncs` when the module is installed. Each function has its own layout and its own set of blocks.

::: info Example
Declaration of `news` ([src/modules/news/version.php](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/modules/news/version.php)):

```php
'modfuncs' => 'main,viewcat,topic,groups,author,detail,search,content,tag,rss',
'change_alias' => 'topic,groups,content,rss',
```
:::

### Alias

The op in the URL is the function's alias. Functions listed in the `change_alias` key of `version.php` can have their alias changed by the administrator; the file stays the same. The router maps the alias back to the file name and stores it in `$op_file`.

When building a link to a function, get the alias from `$module_info['alias']['<function-name>']`; do not hard-code the function name.

### `$array_op`

When the op matches no alias, the router splits it by `/` ([src/index.php#L188](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/index.php#L188)):

```php
$array_op = [];
if ($op != 'main' and !isset($module_info['funcs'][$op])) {
    $array_op = explode('/', $op);
    $op = (isset($module_info['funcs'][$array_op[0]])) ? $array_op[0] : 'main';
}
```

| URL | `$op` | `$array_op` |
|---|---|---|
| `/vi/news/` | `main` | `[]` |
| `/vi/news/rss/` | `rss` | `[]` |
| `/vi/news/rss/the-thao/` | `rss` | `['rss', 'the-thao']` |
| `/vi/news/the-thao/bai-viet-123.html` | `main` | `['the-thao', 'bai-viet-123.html']` |

The module parses `$array_op` itself in `functions.php` and may change `$op`, because the router only resolves `$op_file` after loading that file.

::: info Example
`news` uses this for category and article URLs: `functions.php` reads `$array_op` and changes `$op` from `main` to `viewcat` or `detail`.
:::

## Theme

A theme decides how the website is presented. The system can use many themes; the administrator switches themes without affecting data or features.

NukeViet 5 ships with two system themes:

| Theme | Used for |
|---|---|
| `default` | Site, responsive for desktop and mobile screens |
| `admin_default` | Admin area |

When another theme lacks a component, that component is taken from the matching system theme. So never rename or edit these two themes; they are also overwritten on upgrade. Change the look with a new theme that only contains the files that differ from `default`.

A theme is chosen at three levels:

1. Whole site: the default theme
2. Per module: each module can use its own theme, including a separate theme for mobile devices
3. Per function: each function uses its own layout within the theme

Structure:

- Code: `themes/<name>/`, information and the list of block positions are declared in `config.ini`
- Templates: `.tpl` files written in Smarty, rendered through `NukeViet\Template\NVSmarty`. The system themes are built on Bootstrap 5
- Module templates: `themes/<theme>/modules/<module>/`. The function `get_module_tpl_dir()` looks in the current theme first, then in the system theme ([src/includes/core/filesystem_functions.php](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/includes/core/filesystem_functions.php))

## Layout and block position

A layout is one arrangement of a theme: content only, content with one sidebar, content between two sidebars... The theme designer decides which layouts a theme has.

- Files: `themes/<theme>/layout/layout.*.tpl`. Theme `default` has `layout.content.tpl`, `layout.content-esbar.tpl`, `layout.ssbar-content-esbar.tpl`...
- Layouts are assigned per function of each module, at Admin → Themes → Layout setup. Set them up right after installing a theme, before activating it

A block position is a place reserved in the layout for blocks, written as a `[NAME]` tag: `[HEADER]`, `[START_SIDEBAR]`, `[END_SIDEBAR]`...

- Declared in the `<positions>` section of `config.ini`
- When the page is output, each tag is replaced with the HTML of the blocks placed in that position

## Block

Blocks are small content boxes placed around the main content: latest news, menus, contact info, QR code... Blocks let a page show extra data from many modules, and can be enabled, disabled and moved between positions without touching code.

Blocks fall into two groups by where the file lives:

| Group | Directory | Scope |
|---|---|---|
| Module block | `modules/<module>/blocks/` | Depends on the file name prefix, see the table below |
| Theme block | `themes/<theme>/blocks/` | Any position of that theme |

The file name prefix decides where a block can appear:

| File name | Appears on |
|---|---|
| `global.<name>.php` | Every page of every module |
| `module.<name>.php` | Only pages of the owning module |

Each block added to the site is a record in the table `<prefix>_<lang>_blocks_groups`, holding the theme, position, groups allowed to view, whether bots see it, and display time (regular, specific time range, daily, weekly...). The same block file can be added several times with different settings.

How it works:

- `.json` file with the same name: display name per language, default settings, the name of the function that builds the settings form (`datafunction`) and the one that saves it (`submitfunction`)
- The system `include`s the block file and then reads the variable `$content`
- Module block templates are resolved by the current theme like module templates, so each theme can present the same block its own way
- Management: Admin → Themes → Blocks management

::: info Example
End of the file [src/modules/page/blocks/global.page_list.php](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/modules/page/blocks/global.page_list.php):

```php
if (defined('NV_SYSTEM')) {
    $content = nv_page_list($block_config);
}
```
:::

## Plugin and hook

A hook is an extension point placed in the code by the core or a module, where outside code may modify data or be notified of an event. A plugin is a PHP file that registers a handler at a hook. This is how you change the behavior of the core and other modules without editing their code.

- Place a hook: `nv_apply_hook($module, $tag, $args, $default)`. An empty `$module` means a system hook; otherwise it is that module's hook and only runs while the module is active
- Register: `nv_add_hook()` in the plugin file

::: info Example
System hook in [src/includes/footer.php#L39](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/includes/footer.php#L39), allowing the HTML to be modified before it is sent:

```php
[$contents, $headers] = nv_apply_hook('', 'change_site_buffer', [$global_config, [$contents, $headers]], [$contents, $headers]);
```

Plugin handling this hook:

```php
$callback = function ($vars, $from_data, $receive_data) {
    [$contents, $headers] = $vars[1];
    // modify $contents
    return [$contents, $headers];
};
nv_add_hook($module_name, 'change_site_buffer', $priority, $callback, $hook_module, $pid);
```
:::

- The callback receives 3 arguments: the hook's `$args` array (plus `pid`), info about the emitting module, info about the receiving module
- `$module_name`, `$priority`, `$hook_module`, `$pid` are provided by the system when it loads the file, taken from the record in the table `<prefix>_plugins`
- **Filter** type: return a value to replace the default, return `null` to skip. **Event** type: only signals that something happened; the return value is not used

| File location | Registration |
|---|---|
| `includes/plugin/<name>.php` | Activate manually at Admin → Configuration → Set up Plugin |
| `modules/<module>/hooks/<name>.php` | Registered automatically when the module is installed ([src/admin/modules/setup.php#L62](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/admin/modules/setup.php#L62)) |

::: info Example
Event hook `user_delete`: the `users` module emits it when a member is deleted ([src/modules/users/functions.php#L412](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/modules/users/functions.php#L412)). Other modules register a plugin at this hook to delete that member's data.
:::

::: warning
Plugins are loaded before the database connection. A callback can only use data that exists at the moment the hook is called. See [Request lifecycle](./request-lifecycle.md).
:::

Details: [Part 5: Hooks](/dev/hooks/index.md). List: [Hook list](/dev/hooks/list.md) (Vietnamese only for now).

## Cronjob

A cronjob (automatic process) is a task the system runs on a schedule without user action: clearing cache, deleting old logs, backing up the database, deleting accounts on request...

- Code: a PHP function in `includes/cronjobs/<name>.php`
- Management: Admin → Configuration → Automatic process (table `<prefix>_cronjobs`): start time, repeat interval, parameters
- The function must return `true` when done, even if there was nothing to do. Returning `false` **disables the cronjob automatically** ([src/includes/core/cronjobs.php](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/includes/core/cronjobs.php))

Two ways to trigger (setting `cronjobs_launcher`):

| Value | Mechanism | Use when |
|---|---|---|
| `system` | Site visits trigger a background run through `sload.php` | Default; on low-traffic sites jobs run late |
| `server` | The server's cron calls a URL with a `loadcron` parameter holding a secret key | Production sites that need exact timing |

::: tip
Cronjobs run before modules are loaded, so they cannot call functions from a module's `functions.php`. Put the logic in a class under `Shared/` (namespace `NukeViet\Module\<module>\Shared`, autoloaded); the cronjob file only calls that class.
:::

## Languages

A NukeViet site can run several languages, each with its own data: modules, articles, blocks, configuration. The system separates two notions:

| Constant | Decides |
|---|---|
| `NV_LANG_DATA` | Which tables data is read from: `<prefix>_vi_...` or `<prefix>_en_...` |
| `NV_LANG_INTERFACE` | Language of labels, buttons, messages |

The two constants differ when a member picks their own interface language in their account, or when an administrator uses the English interface to edit Vietnamese data ([src/includes/language.php](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/includes/language.php)).

- Per-language tables: `NV_PREFIXLANG`. Shared tables: `$db_config['prefix']`
- Language files: `includes/language/<lang>/` (shared), `modules/<module>/language/<lang>.php` (module). The files declare the array `$lang_module[...]`
- Read in code: `$nv_Lang->getModule('key')`, `$nv_Lang->getGlobal('key')`

## Groups and permissions

Member groups control view permissions: modules, blocks, news categories... are assigned to one or more groups, and only members of those groups see them. Admin permissions are a separate system, assigned to administrators by level.

System groups have fixed IDs ([src/modules/users/action_mysql.php](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/modules/users/action_mysql.php)):

| ID | Group |
|---|---|
| 1 | Super-Admin |
| 2 | General-Admin |
| 3 | Module-Admin |
| 4 | Users: official members |
| 5 | Guest: visitors |
| 6 | All: everyone |
| 7 | New-Users: new members |

Check group membership: `nv_user_in_groups('4,10')`.

Admin permissions are by **level**, separate from group IDs: 1 super administrator, 2 general administrator, 3 administrator of assigned modules.

Identity constants, set after the session is checked ([src/includes/core/is_admin.php#L58](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/includes/core/is_admin.php#L58)):

| Constant | Defined when |
|---|---|
| `NV_IS_USER` | A member is logged in with a valid session |
| `NV_IS_1STEP_USER` | Logged in but has not enabled two-step verification while the site or account requires it |
| `NV_IS_ADMIN` | An administrator is logged in |
| `NV_IS_SPADMIN` | Level 1 or 2 administrator |
| `NV_IS_GODADMIN` | Level 1 administrator of the main site |
| `NV_IS_MODADMIN` | Has admin rights on the current module |

::: warning
Check login with `defined('NV_IS_USER')`, not `!empty($user_info)`. In the `NV_IS_1STEP_USER` state, `$user_info` already holds data ([src/includes/core/is_user.php](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/includes/core/is_user.php)).
:::

## Differences from NukeViet 4

NukeViet 4 docs and sample code do not apply to the following:

| NukeViet 4 | NukeViet 5 |
|---|---|
| XTemplate: `new XTemplate(...)`, `<!-- BEGIN: ... -->` blocks | Smarty through `NVSmarty`. The core no longer uses XTemplate |
| Language read through `$lang_module['key']`, `$lang_global['key']` | Read through `$nv_Lang->getModule()`, `$nv_Lang->getGlobal()` |
| Bootstrap 3 | Bootstrap 5 |
| Ships a separate `mobile_default` theme, default theme types `r,d,m` | No mobile theme, `default` is responsive; default theme types `r,d` |
