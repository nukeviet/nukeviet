# Threat model - NukeViet CMS 5.x

## What this project does and where untrusted input enters

NukeViet is a PHP/MySQL content management system used for news portals, company sites and
Vietnamese government sites (NukeViet eGovernment). Deployments are internet-facing; every HTTP
request is untrusted unless noted.

Web root is `src/`. Entry points:

- `src/index.php` - public site, routes to `src/modules/<module>/` (`?nv=<module>&op=<func>`).
  Anonymous visitors and logged-in members.
- `src/admin/index.php` - admin panel (`src/admin/<area>/` and `src/modules/<module>/admin/`).
  Requires an admin session; admins have levels (super admin, general admin, module admin).
- `src/api.php` - remote API, POST only, authenticated by API key and secret
  (`src/includes/api/`, `src/modules/*/Api/`).
- `src/sload.php`, `src/mload.php` - AJAX/system loaders, `src/robots.php`, `src/error.php`.
- `src/install/` - installer and updater. Locked once `src/config.php` exists.
- File uploads (`src/admin/upload/`, user avatars, module attachments) end up under `src/uploads/`.

Core libraries live in `src/includes/` and `src/includes/vendor/vinades/nukeviet/` (namespace
`NukeViet\`). All input is meant to go through `NukeViet\Core\Request` (`$nv_Request`), SQL through
PDO prepared statements, and state-changing admin
actions through CSRF tokens (`csrf_create()` / `csrf_check()`). A finding that bypasses one of
these is the most valuable kind.

## Components that matter most / least

- Most: authentication, sessions, cookies, 2FA (`src/modules/users/`,
  `src/modules/two-step-verification/`, `src/includes/core/`), `NukeViet\Core\Request`,
  file upload and file manager, the API layer, and the public-facing modules
  (news, users, comment, contact, seek, voting, banners, feeds, page).
- Less: admin-only configuration screens, statistics, theme/block configuration.
- Out of scope: third-party libraries in `src/includes/vendor/` other than `vinades/`, bundled
  JavaScript/CSS libraries in `src/assets/` and `src/themes/*/js`, and everything outside `src/`
  (`tests/`, `tools/`, `scripts/`, `docs/`, `.oss-scanner/`). Report a vendored library only when
  NukeViet uses it in a way that makes it exploitable.

## How to exercise it

The image built by `.oss-scanner/Dockerfile` contains an installed site:

- Run `/src/.oss-scanner/start.sh` to start MariaDB, PHP-FPM and Nginx, then browse
  `http://localhost/` and `http://localhost/admin/`.
- Admin account: `admin` / `Nukeviet@Scan2026`. Database: `nukeviet` / `nukeviet`, table prefix
  `nv5`.
- `.oss-scanner/nginx.conf` mirrors the access rules NukeViet recommends for production
  (blocked folders and files, `api.php` POST only). A finding that only works when those rules
  are missing should say so.
- Unit tests: `php vendor/bin/codecept run Unit` from `/src` (services must be running).

## How we rate severity

We use CVSS 3.1 plus these rules:

- Critical: RCE, SQL injection or authentication bypass reachable without authentication;
  privilege escalation from anonymous or member to admin.
- High: stored XSS that a visitor or member can plant and that runs for other users or admins;
  privilege escalation between admin levels or from member to another member's account;
  path traversal reading sensitive files (`config.php`, `data/config/`); SSRF reaching internal
  networks; SQL injection that needs a member account.
- Medium: issues behind two or more barriers (admin-only, disabled by default, needs a
  non-default config, needs several attacker steps); reflected XSS needing user interaction;
  CSRF on non-critical actions.
- Low: self-XSS, missing security headers, minor information disclosure.

Anything that requires super admin rights is at most Medium, because a super admin can already
install modules and edit themes, which is code execution by design. Findings under the admin
panel should state the minimum admin level they need.

## How reports and patches should look

- One report per root cause; group the same flaw across several modules into one report.
- Include the exact HTTP request(s) as a proof of concept, the minimum role needed, and which
  entry point is affected.
- A minimal patch is preferred, using the existing helpers (`$nv_Request`, prepared statements,
  `nv_htmlspecialchars()`, `csrf_check()`). Code style is PSR-12, comments may be Vietnamese.

## Anything to leave alone

- Do not report the installer being reachable before installation is completed.
- Do not report that a super admin can execute code through module/theme installation.
- Do not report hard-coded credentials in `.oss-scanner/`; they exist only for this image.
- Do not report issues that require `nv_debug` or developer mode to be enabled.
