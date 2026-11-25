# File and directory structure

NukeViet 5 source code lives in the `src/` directory of the repository. When installed on a server, the contents of `src/` are the website's root directory. All paths in these docs are relative to that root.

```text
src/
├── index.php                 Site entry point
├── api.php                   API entry point
├── sload.php                 Background tasks: cronjob, email, captcha
├── mload.php                 AJAX to module functions
├── robots.php, error.php
├── config.php                Database connection, created on install
├── admin/
│   ├── index.php             Admin entry point
│   └── <area>/               settings, modules, themes, authors...
├── includes/
│   ├── mainfile.php          System bootstrap
│   ├── constants.php
│   ├── functions.php
│   ├── header.php, footer.php
│   ├── core/                 Functions by area, authentication, cronjob
│   ├── language/<lang>/
│   ├── plugin/               System plugins
│   ├── cronjobs/
│   ├── api/, uapi/
│   └── vendor/
│       └── vinades/nukeviet/ Core classes NukeViet\...
├── modules/
│   └── <module>/
│       ├── version.php
│       ├── functions.php
│       ├── admin.functions.php
│       ├── admin.menu.php
│       ├── action_mysql.php
│       ├── funcs/, admin/
│       ├── blocks/, language/
│       ├── Shared/, hooks/
│       └── ...
├── themes/
│   ├── default/              Site theme
│   │   ├── config.ini
│   │   ├── theme.php
│   │   ├── layout/, blocks/, system/
│   │   ├── modules/<module>/ Module templates
│   │   └── css/, js/, images/
│   └── admin_default/        Admin theme
├── assets/                   Shared JS, CSS, libraries
├── data/                     config/, cache/, logs/, tmp/...
├── uploads/<module>/
└── install/
```

The sections below describe each directory.

## Root directory

The root directory holds the entry point files, where browser requests start, and the directories for code, themes and data.

### Entry point files

Each entry point file defines a constant marking the request type, then loads `includes/mainfile.php` to bootstrap the system.

| File | Constant | Handles |
|---|---|---|
| `index.php` | `NV_SYSTEM` | Site pages |
| `admin/index.php` | `NV_ADMIN` | Admin area |
| `api.php` | `NV_SYSTEM` | API for external applications (Api) and for members (Uapi) |
| `sload.php` | `NV_SYS_LOAD` | System background tasks: running cronjobs, sending email asynchronously, captcha images, checking notifications |
| `mload.php` | `NV_MOD_LOAD` | AJAX requests to a module function, without rendering the theme. The URL must carry a valid `mhash`, generated with `mload_url_generate()` |
| `robots.php` | `NV_SYSTEM` | Generates the content of `robots.txt` |
| `error.php` | `NV_SYSTEM` | HTTP error page |

### Directories

| Directory, file | Contents |
|---|---|
| `includes/` | System core: bootstrap, shared functions, core classes, third-party libraries |
| `admin/` | System admin areas |
| `modules/` | Modules |
| `themes/` | Site and admin themes |
| `assets/` | Shared JS, CSS, fonts, images and libraries, independent of themes |
| `data/` | Data generated at runtime: configuration, cache, logs |
| `uploads/` | Uploaded files, one subdirectory per module |
| `install/` | Installer and upgrade data |
| `config.php` | Database connection details, created on install |

When `config.php` does not exist, the system redirects to the installer in `install/` ([src/includes/mainfile.php#L57](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/includes/mainfile.php#L57)).

## `includes/` - system core

Holds all shared system code. Modules and themes call the functions and classes in this directory; they do not modify it.

| Path | Contents |
|---|---|
| `mainfile.php` | System bootstrap, loaded by every entry point |
| `constants.php` | Fixed constants: directory names, URL variable names, system themes |
| `functions.php` | System-wide shared functions |
| `core/` | Functions and handlers by area: `admin_functions.php`, `user_functions.php`, `theme_functions.php`, `filesystem_functions.php`, authentication `is_user.php`, `is_admin.php`, cronjob runner `cronjobs.php`... |
| `header.php`, `footer.php` | Start and end page output. `footer.php` calls the last hook and sends headers and content to the browser |
| `language/<lang>/` | Shared language files and those of the system admin areas |
| `plugin/` | System plugins |
| `cronjobs/` | Files holding cronjob functions |
| `api/`, `uapi/` | System API commands |
| `vendor/vinades/nukeviet/` | Core classes, namespace `NukeViet\`: `Core`, `Cache`, `Http`, `Template`, `Files`, `Seo`, `Api`, `Uapi`... |
| `vendor/` | Third-party libraries, managed by Composer through `includes/composer.json` |

Composer autoload already declares namespaces, including one for modules ([src/includes/composer.json](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/includes/composer.json)):

| Namespace | Directory |
|---|---|
| `NukeViet\` | `includes/vendor/vinades/nukeviet/` |
| `NukeViet\Api\` | `includes/api/` |
| `NukeViet\Uapi\` | `includes/uapi/` |
| `NukeViet\Module\` | `modules/` |

::: info Example
Class `NukeViet\Template\NVSmarty` is at `includes/vendor/vinades/nukeviet/Template/NVSmarty.php`. Class `NukeViet\Module\news\Shared\Posts` is at `modules/news/Shared/Posts.php`.
:::

Function reference: [Part 7: Reference](/dev/reference/index.md) (Vietnamese only for now).

## `admin/` - system admin areas

Each subdirectory is a system admin area. These are not modules: they cannot be installed, removed or cloned, and have no site pages.

| Directory | Admin menu label |
|---|---|
| `authors` | Administrators |
| `database` | Database |
| `emailtemplates` | Email templates |
| `extensions` | Extensions |
| `language` | Language, Region |
| `modules` | Modules |
| `seotools` | SEO tool |
| `settings` | Configuration |
| `siteinfo` | Informations |
| `themes` | Themes |
| `upload` | Upload |
| `webtools` | Webtools |

Each area has `admin.menu.php`, `functions.php` and one `<op>.php` file per page, like the admin part of a module ([src/admin/index.php#L94](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/admin/index.php#L94)).

## `modules/` - modules

Each subdirectory is the code of one original module. Virtual modules have no directory of their own.

Modules shipped with NukeViet 5: `banners`, `comment`, `contact`, `feeds`, `inform`, `menu`, `myapi`, `news`, `page`, `seek`, `statistics`, `two-step-verification`, `users`, `voting`.

### Structure of a module

Required files:

| File | Contents |
|---|---|
| `version.php` | Module info: name, version, list of functions, whether virtual modules are allowed |
| `functions.php` | Loaded on the site. Defines the constant `NV_IS_MOD_<NAME>`, shared functions, parses `$array_op`. Must not be deleted, even if empty |
| `admin.functions.php` | Loaded in the admin area. Defines the constant `NV_IS_FILE_ADMIN` |
| `admin.menu.php` | Admin menu and the list of allowed admin pages (`$allow_func`) |

Common files and directories:

| File, directory | Contents |
|---|---|
| `action_mysql.php` | SQL to create tables on install (`$sql_create_module`) and drop them on removal (`$sql_drop_module`) |
| `funcs/` | One `<op>.php` file per site function |
| `admin/` | One `<op>.php` file per admin page |
| `theme.php` | Functions building site HTML |
| `global.functions.php` | Functions shared by site and admin. Not loaded automatically: the module `require`s it in `functions.php` and `admin.functions.php` |
| `language/` | `vi.php`, `en.php`... shared by site and admin |
| `blocks/` | Module blocks: `global.*.php`, `module.*.php`, with a `.json` file of the same name |
| `Shared/` | PHP classes, namespace `NukeViet\Module\<module>\Shared`, autoloaded |

Integration files, only added when the module needs the matching feature:

| File, directory | Contents |
|---|---|
| `hooks/` | Module plugins, registered automatically when the module is installed |
| `Api/`, `Uapi/` | API commands for external applications and for members |
| `widgets/` | Widgets on the admin Informations page |
| `siteinfo.php` | Statistics on the admin Informations page |
| `notification.php` | Displays admin notifications created by the module |
| `comment.php` | Handling after a new comment on the module's content |
| `search.php` | Takes part in the site-wide search of the `seek` module |
| `rssdata.php` | Provides feed sources to the `feeds` module |
| `menu.php` | Provides items to pick when building a menu in the `menu` module |
| `mobile/` | Replacements for files in `funcs/` when a mobile theme is used |

::: warning
`search.php`, `rssdata.php`, `menu.php`, `notification.php`, `siteinfo.php` do not run on their own. Another module or an admin area `include`s them and passes variables in. The available variables differ per file; see [Part 4: Programming](/dev/programming/index.md) (Vietnamese only for now) before writing them.
:::

::: info Example
The `page` module has only the required files, `action_mysql.php`, `funcs/`, `admin/`, `theme.php`, `global.functions.php`, `language/`, `blocks/`, `Shared/`, `search.php`, `menu.php`. The `news` module uses almost every file in the tables above.
:::

## `themes/` - themes

Each subdirectory is a theme. NukeViet 5 ships with `default` (site) and `admin_default` (admin).

| File, directory | Contents |
|---|---|
| `config.ini` | Theme info, default layout, list of block positions |
| `config_default.php`, `config.php` | Default values and handling of theme customization in the admin area |
| `theme.php` | `nv_site_theme()` wraps module HTML into the layout, `nv_block_theme()` wraps blocks, `nv_error_theme()` error pages |
| `layout/` | `layout.*.tpl`, page header and footer, block wrappers `block.*.tpl` |
| `blocks/` | Theme blocks |
| `modules/<module>/` | `.tpl` templates of each module |
| `system/` | Shared templates: alerts, error pages, email, admin toolbar |
| `css/`, `js/`, `images/`, `webfonts/` | Theme assets |
| `language/` | Theme-specific language strings |

The `admin_default` theme has a similar structure and uses `nv_admin_theme()` instead of `nv_site_theme()`.

::: info Example
Module CSS in the `default` theme is split by theme type: `news.d.css` for the fixed desktop type (`d`), `news.r.css` for the responsive type (`r`), plus `.rtl.css` variants for right-to-left languages.
:::

## `data/` - runtime data

The system must be able to write to this directory. Do not put its contents under version control.

| Path | Contents |
|---|---|
| `data/config/` | Configuration generated from the database, e.g. `config_global.php`. Do not edit by hand; the system rewrites it when configuration is saved |
| `data/cache/` | Cache. Can be deleted at any time |
| `data/logs/` | Error logs, system logs |
| `data/tmp/` | Temporary files |
| `data/ip/`, `data/ip6/` | IP-to-country lookup data (GeoLite2) |
| `data/certs/` | Root certificates used for outgoing HTTPS calls |

## Where to put project code

Projects built on NukeViet only add new files and never modify the core's existing files, so NukeViet can still be upgraded later.

| Need | Put it in |
|---|---|
| New feature | New module in `modules/<project-module>/` |
| Another copy of an existing feature | Virtual module, no code needed |
| Change the behavior of the core or another module | Plugin at an existing hook, in `modules/<project-module>/hooks/` |
| Change the look | New theme, containing only files that differ from `default` |
| Scheduled task | New file in `includes/cronjobs/`, calling a class in the module's `Shared/` |
