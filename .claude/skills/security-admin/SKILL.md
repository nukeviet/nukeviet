---
name: security-admin
description: Deep security audit and code quality check for a specific NukeViet 5 admin module function
argument-hint: <module/func>
disable-model-invocation: false
allowed-tools: List, Glob, Read, Grep, Write, Edit, Run
---

# security-admin — NukeViet 5

Comprehensive security audit + auto-fix for a specific NukeViet 5 admin module function
Respond in **Vietnamese**.

---

### ⚡ Token Control
1. **Immediate Code**: Output blocks FIRST. No intro/pre-analysis text
2. **Zero Filler**: No prose. No explaining obvious logic
3. **Segments Only**: Output ONLY changed code/functions
4. **Final Report**: Extremely brief. Unresolved issues ONLY

---

### 🛠️ Tool Usage Rules (Windows — terminal may hang)
- **Search/inspect code** → use `grep_search` + `view_file`. Never use `run_command` for grep/find/cat/ls.
- **`run_command` only** for operations with no alternative (e.g. `php src/private/ClearCache.php`).
- If `grep_search` returns empty but file visually has content → encoding issue. Use `view_file` with known line numbers from audit report instead.

---

## [PHASE 1] Scope Identification

### 1. Identify the target
From `$ARGUMENTS` (format: `{module}/{file}`), split by `/`:
- Part before the first `/` = module name (e.g. `myapi`)
- Part after the first `/` = file name without extension (e.g. `main`)

Example: `$ARGUMENTS = "myapi/main"` → module=`myapi`, file=`main`

If `$ARGUMENTS` is empty or has no `/`, ask the user for input in `{module}/{file}` format

### 2. Trace related files

**PHP files — lazy-load strategy:**

**Step 1 — Read the main file first:**
- `src/modules/$0/admin/$1.php` — main file (primary path)
- If not found → fallback: `src/admin/$0/$1.php`

**Step 2 — Read additional files only if needed:**
After reading file #1, check whether any called functions or logic are **not defined within file #1** (e.g. helper functions, shared queries):

- **If main file came from `src/modules/$0/admin/$1.php` (primary):**
  - If missing → read `src/modules/$0/admin.functions.php` (file #2)
  - If still missing → read `src/modules/$0/global.functions.php` (file #3)

- **If main file came from `src/admin/$0/$1.php` (fallback):**
  - If missing → read `src/admin/$0/functions.php` (file #2)

- If file #1 is self-contained → **skip** additional files, do not read them

**Function Discovery Best Practices (Crucial):**
- **No Early Exit**: If a function is called but its definition is not in the current file, you **must** find where it is defined. Do not conclude it is "missing" or "deleted" based on a single failed search
- **Robust Searching**:
    - Use `grep_search` with `IsRegex: false` and `CaseInsensitive: true` to avoid syntax or case-matching errors
    - Search for just the function name (e.g., `my_function`) instead of a full regex like `function my_function`
    - If searching specific files fails, search the entire module directory: `src/modules/$0/`
- **Large File Handling**: If a file is known to house helpers (e.g., `global.functions.php`) but search returns no results, read the file in chunks (up to 800 lines/chunk) to manually verify or use cursor context if provided by the user

**Templates & JS Discovery:**
1. **Analyze PHP source**: Search `src/modules/$0/admin/$1.php` for template loading (e.g. `setTemplateFile`, `fetch`) and JS additions (e.g. `nv_html_add_js`)
2. **Standard Guess Paths** (if not explicitly overridden in PHP):
   - Admin Future (preferred): `src/themes/admin_future/modules/$0/$1.tpl`, `src/themes/admin_future/modules/$0/$1-*.tpl`, `src/themes/admin_future/js/$0.js`
   - Admin Default (fallback): `src/themes/admin_default/modules/$0/$1.tpl`, `src/themes/admin_default/js/$0.js`

Audit only **one** theme following priority order. Only fix JS files that are actually used/linked in the target function

**JS Logic Discovery Best Practices (Crucial for Large Files):**
- **Broad Keyword Search**: Do not search ONLY for the exact `data-toggle` name found in TPL. Also search for broad keywords like the module name, feature name (e.g. `report`), or the `action` name (e.g. `multidel`, `delete`)
- **Scan Large Files (>1000 lines)**: If JS files are large, read them in 800-line chunks to manually verify event handlers instead of relying solely on `grep_search`
- **Verify Before Adding**: Always assume an existing feature has JS logic somewhere. Exhaustive search is required before deciding to write "new" handler code to prevent logic duplication

**Build PHP_FILES list:** Identify the exact paths of PHP files actually read above (up to 3) for use in Phase 5 security scans

---

## [PHASE 2] Migrate SQL to PDO Prepared Statements

- **General rule:** Replace string-concatenated variables in SQL with `:placeholder` + `bindValue()`
- **Database variable:**
  - **Always use `$db`** for ALL operations (SELECT, INSERT, UPDATE, DELETE).
  - **Replace `$db_slave`**: If the original code uses `$db_slave`, you **MUST** replace it with `$db` (NukeViet 5 has removed direct $db_slave support, it is now an alias for $db).
- **Do not parameterize:** Table name constants (`NV_PREFIXLANG`, `$db_config['prefix']`, `NV_USERS_GLOBALTABLE`, etc.) — these are static table names
- **Remove Query Builder:** Completely drop Query Builder patterns (e.g. `$db->sqlreset()->select(...)->from(...)`) and replace with standard SQL strings + PDO Prepared Statements (`$db->prepare('SELECT ...')`) for optimization, code consistency, and readability
- **Remove `$db->insert_id()`:** Replace the legacy NukeViet helper `$db->insert_id($sql, 'id', $data_array)` with standard PDO `prepare` / `bindValue` / `execute` / `lastInsertId()`. This helper is non-standard, untestable, and bypasses the Prepared Statement pipeline
  - ❌ Wrong: `$db->insert_id('INSERT INTO table (a) VALUES (:a)', 'id', ['a' => $val])`
  - ✅ Correct:
    ```php
    $stmt = $db->prepare('INSERT INTO table (a) VALUES (:a)');
    $stmt->bindValue(':a', $val, PDO::PARAM_STR);
    $stmt->execute();
    $new_id = $db->lastInsertId();
    ```
- **String quoting:** Prefer single quotes `'` around the SQL string for performance. However, if the SQL contains static values wrapped in single quotes (e.g. `WHERE status = 'active'`), use double quotes `""` to avoid escaping with `\'`
  - **Concatenation case:** When concatenating with table name constants (e.g. `NV_PREFIXLANG`), if the following static string contains single quotes, use double quotes `""` around all string segments for consistency and readability
  - ❌ Wrong: `$db->prepare('UPDATE ' . NV_PREFIXLANG . "_table SET type='admin'")`
  - ✅ Correct: `$db->prepare("UPDATE " . NV_PREFIXLANG . "_table SET type='admin'")`

### Static SQL or no parameters
Keep `$db->query()` / `$db->exec()` when the statement contains no input variables (only system table constants):
- `$db->query()`: Statements with a result set to fetch, no parameters — `SELECT`, `OPTIMIZE TABLE`, `SHOW ...`
- `$db->exec()`: Statements with no result set, no parameters — `TRUNCATE`, `DROP`, `ALTER`, `CREATE`

### LIMIT — Must use PDO::PARAM_INT
When using `LIMIT` in a Prepared Statement, offset and count values **must** be bound with `PDO::PARAM_INT`. Binding as string (default) will cause a syntax error on MariaDB/MySQL
```php
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
```

### Pagination — Remove SQL_CALC_FOUND_ROWS
NukeViet 5 prioritizes broad compatibility. Instead of `SQL_CALC_FOUND_ROWS`, use two separate queries: one to count total rows (`SELECT COUNT(*)`) and one to fetch data with `LIMIT`

### SELECT / fetchColumn / fetchAll / while-fetch
```php
// SELECT (Always use $db)
$stmt = $db->prepare('SELECT * FROM ' . NV_PREFIXLANG . '_table WHERE id = :id AND lang = :lang');
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->bindValue(':lang', $lang, PDO::PARAM_STR);
$stmt->execute();

$row   = $stmt->fetch();           // Fetch one row
$rows  = $stmt->fetchAll();        // Fetch all rows
$count = $stmt->fetchColumn();     // Fetch a single value
```

Resource Management:
1. **`fetchColumn()`**: Do NOT call `closeCursor()` after this
2. **`fetch()` (single row)**: **REQUIRED** — call `$stmt->closeCursor()` immediately after
3. **`fetchAll()`**: PDO closes the cursor automatically — do NOT call `closeCursor()` after this
4. **`while ($row = $stmt->fetch())` loop**: **REQUIRED** — call `$stmt->closeCursor()` immediately after the loop block ends

```php
// Loop: fetch directly from $stmt, NEVER assign $result = $stmt
while ($row = $stmt->fetch()) {
    // ...
}
$stmt->closeCursor(); // Required after loop
```

### Loop Variable Efficiency
Minimize creating temporary variables inside loops just to re-assign values from `$_row`. Prefer using array elements directly for cleaner code and to avoid variable conflicts

- ❌ **Wrong (redundant temporaries):**
```php
while ($_row = $stmt->fetch()) {
    $id = (int)$_row['id'];
    $publtime = (int)$_row['publtime'];
    // ... logic using $id, $publtime
}
```

- ✅ **Correct (clean & professional):**
```php
while ($_row = $stmt->fetch()) {
    // Use $_row['id'], $_row['publtime'] directly in logic below
}
```
*Note: Only create a temporary variable when the value requires complex processing before use (e.g. `preg_replace` or heavy computation) and is reused multiple times within the block*

### Cache Queries ($nv_Cache->db)
For queries using `$nv_Cache->db()`, never concatenate variables into the SQL string. Use the `bind` parameter at the end of the function call in strict format

**`$bind` rule:** Each element is a sub-array of 3 values: `[placeholder_name, value, data_type]`

```php
// ❌ Wrong: Direct variable concatenation
$sql = 'SELECT * FROM ' . NV_USERS_TABLE . ' WHERE active = ' . $active;
$list = $nv_Cache->db($sql, 'userid', 'users');

// ✅ Correct: Use $bind array
$sql = 'SELECT * FROM ' . NV_USERS_TABLE . ' WHERE active = :active';
$bind = [
    [':active', $active, PDO::PARAM_INT]
];
$list = $nv_Cache->db($sql, 'userid', 'users', '', 0, $bind);
```

Formatting notes:
  - Separate (with one blank line) consecutive independent PDO statement groups (from `prepare` to `execute`/`fetch`) for readability
  - Only add one blank line when followed by new logic code
  - NEVER add a blank line if the next line is a closing `}`

### Array Destructuring (`[...]` / `list()`)
Do not use destructuring directly inside `while` conditions — PHP does not guarantee the return value is always an array, causing `Warning: Cannot destructure non-array value`

**Fix — use a named variable, access keys directly instead of splitting into independent variables:**
```php
// ❌ Wrong
while ([$layout, $in_module, $func_name] = $result->fetch(PDO::FETCH_NUM)) {

// ✅ Correct
while ($_row_file = $result->fetch()) {
    // use $_row_file['layout'], $_row_file['in_module'], $_row_file['func_name']
}
```

Variable naming for fetch:
- `$row` is valid for a single, non-nested loop
- With **nested loops**, always use semantic names (`$_row_cat`, `$_row_user`, `$_row_file`, `$_row_module`...) to avoid variable collisions
- Always use `$result->fetch()` — NukeViet 5 sets `PDO::FETCH_ASSOC` as default in `Database.php`, no parameter needed. Never use `PDO::FETCH_NUM` — avoids silent bugs when column order in `SELECT` changes

### INSERT — PDO standard
Replace any `$db->insert_id()` legacy helper or plain `$db->query('INSERT ...')` with the standard pattern:
```php
$stmt = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_table (title, body) VALUES (:title, :body)');
$stmt->bindValue(':title', $title, PDO::PARAM_STR);
$stmt->bindValue(':body', $body, PDO::PARAM_STR);
$stmt->execute();
$new_id = $db->lastInsertId();
```

### UPDATE / DELETE
```php
// Execute only → prepare/execute
$stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_table SET status = :status WHERE id = :id');
$stmt->bindValue(':status', $status, PDO::PARAM_INT);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();

// Need affected row count → rowCount()
$stmt = $db->prepare('DELETE FROM ' . NV_PREFIXLANG . '_table WHERE userid = :userid');
$stmt->bindValue(':userid', $userid, PDO::PARAM_INT);
$stmt->execute();
$affected = $stmt->rowCount();
```

### Unique Placeholders
**REQUIRED:** Each placeholder in a Prepared Statement must be unique, even when the bound value is the same. Reusing the same placeholder (e.g. `:time`) in one SQL string causes `SQLSTATE[HY093]: Invalid parameter number` when `PDO::ATTR_EMULATE_PREPARES` is `false` (NukeViet 5 default)

```php
// ❌ Wrong: Reusing :time
$sql = 'INSERT INTO table (time1, time2) VALUES (:time, :time)';
$stmt = $db->prepare($sql);
$stmt->bindValue(':time', NV_CURRENTTIME, PDO::PARAM_INT);

// ✅ Correct: Use distinct placeholder names
$sql = 'INSERT INTO table (time1, time2) VALUES (:time1, :time2)';
$stmt = $db->prepare($sql);
$stmt->bindValue(':time1', NV_CURRENTTIME, PDO::PARAM_INT);
$stmt->bindValue(':time2', NV_CURRENTTIME, PDO::PARAM_INT);
```

### LIKE — append `%` to the value, not the placeholder
```php
$stmt->bindValue(':kw', '%' . $keyword . '%', PDO::PARAM_STR);
```

### IN — integer array (no dynamic placeholders needed)
```php
// intval() eliminates injection risk → concatenate directly into SQL
$ids  = implode(', ', array_map('intval', $id_array));
$stmt = $db->prepare('SELECT * FROM ' . NV_PREFIXLANG . '_table WHERE id IN (' . $ids . ')');
$stmt->execute();
```

### IN — string array (dynamic placeholders required)
```php
$values       = array_values($str_array); // ensure keys are sequential integers 0,1,2...
$placeholders = implode(', ', array_map(fn($k) => ':v' . $k, array_keys($values)));
$stmt         = $db->prepare('SELECT * FROM ' . NV_PREFIXLANG . '_table WHERE slug IN (' . $placeholders . ')');
foreach ($values as $k => $v) {
    $stmt->bindValue(':v' . $k, $v, PDO::PARAM_STR);
}
$stmt->execute();
```

### Warning: "Invalid parameter number" error with array_diff / unset / array_filter
When using a `foreach ($array as $k => $val)` loop to calculate `bindValue` indexes (e.g., `$k + 1`), you **MUST** call `$array = array_values($array);` if the array was previously filtered by `array_diff`, `array_filter`, or `unset`.
**Reason:** These functions **preserve original keys**, resulting in an array with missing indexes (e.g., from `0, 1, 2` to `[1 => 1, 2 => 2]`). Adding `$k` with an integer inside the loop will be misaligned, pointing to non-sequential parameter indexes like `3, 4` (missing `2`), causing an `SQLSTATE[HY093]: Invalid parameter number` error.

```php
// ❌ Wrong: Old keys (1, 2...) are preserved -> (k + 2) binds to wrong positions
$array_id = array_diff($array_id, [0]);
foreach ($array_id as $k => $id_val) {
    $stmt->bindValue(($k + 2), $id_val, PDO::PARAM_INT); // Bug!
}

// ✅ Correct: Reset all keys to standard sequential 0, 1, 2...
$array_id = array_diff($array_id, [0]);
$array_id = array_values($array_id); 
foreach ($array_id as $k => $id_val) {
    $stmt->bindValue(($k + 2), $id_val, PDO::PARAM_INT);
}
```

### Notes
- **Reusing prepared statements in a loop:** Call `prepare()` OUTSIDE the loop, then use `bindValue()` + `execute()` INSIDE the loop. Never use `bindParam` inside loops — references may be altered by inner loop logic causing hard-to-detect bugs
- Prefer `bindValue` in all cases
- **Preserve logic & comments:** NEVER delete comments or variable initialization lines (e.g. `$array = [];`) from original code during SQL refactoring. Only replace the query execution portion
- Keep `intval()`, `strip_tags()`, etc. — they serve business-logic validation, not SQL concerns
- Ensure `global $db;` is declared inside functions when needed

---

## [PHASE 3] Standardize CSRF Tokens

Token Variable Rules ($csrf_key & $_csrf_key)
- **`$csrf_key`**: Global variable (already defined in `admin/index.php`)
  - *Remove* manual definitions: `$csrf_key = $admin_info['admin_id'] . '_' . $module_name . '_' . $op;`
- **`$_csrf_key`**: Use when linking across files or handling AJAX
  - **Static**: `$_csrf_key = $admin_info['admin_id'] . '_' . $module_name . '_OriginalActionName';`
  - **Dynamic (per ID)**: `$row['checkss'] = csrf_create($admin_info['admin_id'] . '_' . $module_name . '_' . $row['id']);`
- **Global**: Must declare `global $csrf_key;` (or `$_csrf_key`) when used inside a function

### Step 1: Update PHP
1. **Check & Handle Token**:
   - Prefer using `$csrf_key` directly. Only use intermediate variable `$_csrf_key` when genuinely needed (linked in multiple places)
   - If used once, pass the string directly: `csrf_create($admin_info[...] . '_content')`

2. **`csrf_check` Logic**:
   - **Required** inside `if (!...)` to handle failure
   - Use system language: `$nv_Lang->getGlobal('error_checkss')`
   - **AJAX**:
     ```php
     if (!csrf_check($nv_Request->get_string('checkss', 'post'), $_csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
     }
     ```
   - **Form/Link**: Use `nv_info_die` or `nv_redirect_location` depending on context

3. **Pass token to template**:
   Assign inline directly — **do not create an intermediate variable** whether used once or multiple times:
   ```php
   $tpl->assign('CHECKSS', csrf_create($csrf_key));
   ```

4. **Verify token** (`csrf_check`):
   - **Used once** → read input directly in the call, **no intermediate variable**:
     ```php
     if (!csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key)) {
     ```
   - **Used multiple times** → assign a variable then reuse:
     ```php
     $checkss = $nv_Request->get_string('checkss', 'post');
     if (!csrf_check($checkss, $csrf_key)) { ... }
     ```

### Step 2: Update Templates (TPL)
Prefer editing files in `admin_future`, then `admin_default`
- **Variable replacement**: Replace `NV_CHECK_SESSION` with `CHECKSS`
- **`data-checkss` attribute (AJAX)**:
  - If already present: **Keep its position** to avoid unnecessary diff
  - If adding new: Place at **end of tag** (before `>`)

### Step 3: Update AJAX JS
Read token from element instead of using the global `checksess` on body:
```javascript
data: { checkss: btn.data('checkss'), ... }
```

### Cross-file CSRF Logic Review
**REQUIRED:** If the file being edited creates a token (`csrf_create`) to submit a request (POST/GET/AJAX) to another file for processing (e.g. a dedicated AJAX handler), proactively review and update the verification (`csrf_check`) at that handler file to ensure synchronization and avoid breaking system logic

---

## [PHASE 4] Modernization & Error Handling

Refactor codebase to comply with NukeViet 5 core functions and stable PHP standards

### 4.1 Standardize Core Functions
- **`unserialize()`**: **Always** use `NV_UNSERIALIZE_SAFE` as the second argument for secure deserialization
  ```php
  $data = unserialize($cache, NV_UNSERIALIZE_SAFE);
  ```
- **`json_encode()`**:
  - For API response or Database/Cache storage: Use `json_encode($data, NV_JSON_ENCODE)`
  - For embedding inside a `<script>` tag (HTML context): Use `json_encode($data, NV_JSON_ENCODE_SCRIPT)` to prevent XSS

### 4.2 Error Handling (try...catch)
Optional. Do not automatically add `try...catch` unless strictly necessary
- **Rule**: If used (existing or new), MUST catch `Throwable $e` and call `trigger_error($e)`
```php
try {
    // Critical logic
} catch (Throwable $e) {
    trigger_error($e);
}
```

### 4.3 Activity Logging (`nv_insert_logs`)
Use `nv_insert_logs` **strictly** for recording significant administrative events related to modifying the database (Create, Update, Delete)
- **Rule**: Do not use for general system errors (use `trigger_error` for those)
- **Format**: `nv_insert_logs(lang, module, action_title, content, userid)`
- **Example**:
  ```php
  nv_insert_logs(NV_LANG_DATA, $module_name, 'Edit article', 'Art ID: ' . $id, $admin_info['userid']);
  ```

### 4.4 Clean up dead code
Remove intermediate variables no longer needed after PDO migration (e.g. old `$result`, `$sql`)

---

## [PHASE 5] Security Analysis

All PHP_FILES were already read in Phase 1. Analyze directly from context — do NOT run any shell commands

### 5.1 Exposed Secrets
Look for hardcoded values in the already-read files:
- API keys, passwords, tokens, private keys (`BEGIN RSA`, `BEGIN PRIVATE`)
- Hardcoded IPs (excluding `127.0.0.1`, `0.0.0.0`, `255.255.x.x`)
- Third-party service credentials (`AWS_`, `STRIPE_`, `TWILIO_`, `SENDGRID_`)

### 5.2 OWASP Top 10 — In-context Review

| # | Type | What to look for |
|---|---|---|
| A01 | Broken Access Control | Missing `defined('NV_IS_ADMIN')` guard |
| A02 | Cryptographic Failures | `md5()`/`sha1()` used for passwords |
| A03 | SQLi | Covered in Phase 2 |
| A04 | Insecure Design | Flawed permission or role logic |
| A05 | Security Misconfiguration | `display_errors = On` or `ini_set('display_errors', 1)` |
| A06 | Vulnerable Components | Outdated inline libraries (CDN links to old versions) |
| A07 | Auth Failures | Insecure session/cookie handling, `NV_CHECK_SESSION` misuse |
| A08 | Logging Failures | Critical actions missing `nv_insert_logs` |
| A09 | SSRF | `file_get_contents`/`curl` calls without `nv_is_url`/`nv_check_url` |

### 5.3 Dependencies
Note any CDN links or inline third-party libraries found in the already-read `.php` / `.tpl` / `.js` files. Flag outdated or suspicious versions

### 5.4 File Upload (if applicable)
If upload logic exists in the read files, verify:
- File extension validated by **whitelist** (not blacklist)
- MIME type checked with `finfo`
- Filename sanitized before storage

### 5.5 Logic Vulnerabilities & Code Execution
Look for dangerous function calls in the read files:
- `eval(`, `extract(`, `exec(`, `passthru(`, `system(`, `shell_exec(`
- Remaining raw superglobals: `$_GET`, `$_POST`, `$_REQUEST`, `$_COOKIE`

### 5.6 Filesystem Safety (LFI / Path Traversal)
Look for `include`, `require`, `file_get_contents` calls where the path contains a variable. Verify the variable is validated with `nv_check_path_vNV`


---

## Cache Clearing

**MANDATORY — Notify the user after Phase 5 completes. Do NOT use run_command**

> 🧹 **Xóa cache:** Vui lòng chạy lệnh sau để áp dụng thay đổi:
> ```bash
> php src/private/ClearCache.php
> ```

After displaying the cache instruction, immediately write the Summary & Final Report

---

## Summary & Final Report

**ONLY** report unresolved issues that require further manual action or were outside the scope of auto-refactoring. **DO NOT** list successful fixes from Phases 2-4

**🔴 REMAINING CRITICAL ISSUES:**
- Vulnerabilities that were NOT fixed (e.g. too complex for auto-fix, exposed secrets, etc.) + manual fix instructions

**🟡 REMAINING CODE SMELLS & PERFORMANCE:**
- Unresolved PSR-12, SQL in loops (if not safe to fix automatically), or structural issues that still exist after the refactor

**💡 IMPROVEMENT SUGGESTIONS:**

---

## Important Notes
- **All reports must be in Vietnamese** — the audit rules and skill instructions are in English, but output to the user in Vietnamese
- **Never** run `composer install` or install additional libraries during an audit
- **Preserve business logic** — only modify security-related parts
- If a phase produces large changes, **stop and confirm** with the user before moving to the next phase
- Exposed secrets must be handled **manually** and removed from git history — do not auto-process them

## ✅ Mandatory Completion Checklist
Before submitting the final Summary & Report, confirm ALL items below are done:

- [ ] **PHASE 1** — Target identified, all related PHP/TPL/JS files traced and read
- [ ] **PHASE 2** — All string-concatenated SQL queries migrated to PDO Prepared Statements; Query Builder removed
- [ ] **PHASE 3** — All CSRF tokens standardized in PHP + TPL + JS; cross-file handlers verified
- [ ] **PHASE 4** — Error handling, `json_encode`, `unserialize`, dead code cleaned up
- [ ] **PHASE 5** — OWASP Top 10 reviewed, secrets scanned, dependencies noted
- [ ] **REPORT** — Final Summary written with only unresolved issues
