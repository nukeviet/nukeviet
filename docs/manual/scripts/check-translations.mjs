/**
 * Kiểm tra bản dịch tiếng Anh so với bản gốc tiếng Việt
 *
 * - Trang tiếng Việt chưa có bản tiếng Anh tương ứng trong en/
 * - Trang tiếng Việt được commit sau bản tiếng Anh (bản dịch có thể đã cũ)
 *
 * Chỉ in cảnh báo, luôn thoát với mã 0 để không chặn PR.
 * Chạy trong GitHub Actions thì in theo định dạng annotation ::warning
 */

import { execFileSync } from 'node:child_process'
import { existsSync, readdirSync, readFileSync } from 'node:fs'
import { join, relative, sep } from 'node:path'
import { fileURLToPath } from 'node:url'

const root = fileURLToPath(new URL('..', import.meta.url))
const skipDirs = new Set(['en', 'node_modules', 'public', 'scripts', 'tools', '.vitepress'])

/**
 * Trang sinh tự động từ mã nguồn, chỉ có bản tiếng Việt
 */
function isGenerated(file) {
    return readFileSync(file, 'utf8').includes('<!-- generated:')
}
const isCi = process.env.GITHUB_ACTIONS === 'true'

/**
 * Liệt kê file .md tiếng Việt, đường dẫn tương đối so với root, dùng dấu /
 */
function listViPages(dir) {
    const pages = []
    for (const entry of readdirSync(dir, { withFileTypes: true })) {
        const full = join(dir, entry.name)
        if (entry.isDirectory()) {
            if (dir === root && skipDirs.has(entry.name)) {
                continue
            }
            if (entry.name === 'node_modules') {
                continue
            }
            pages.push(...listViPages(full))
        } else if (entry.name.endsWith('.md') && !isGenerated(full)) {
            pages.push(relative(root, full).split(sep).join('/'))
        }
    }
    return pages
}

function git(args) {
    return execFileSync('git', args, { cwd: root, encoding: 'utf8' }).trim()
}

/**
 * Thời điểm commit cuối của file, 0 nếu file chưa được commit
 */
function lastCommitTime(rel) {
    const out = git(['log', '-1', '--format=%ct', '--', rel])
    return out === '' ? 0 : parseInt(out, 10)
}

const prefix = git(['rev-parse', '--show-prefix'])
let warnings = 0

function warn(rel, message) {
    warnings++
    if (isCi) {
        console.log(`::warning file=${prefix}${rel}::${message}`)
    } else {
        console.log(`[cảnh báo] ${rel}: ${message}`)
    }
}

for (const rel of listViPages(root)) {
    const enRel = `en/${rel}`
    if (!existsSync(join(root, enRel))) {
        warn(rel, `Chưa có bản dịch ${enRel}`)
        continue
    }

    const viTime = lastCommitTime(rel)
    const enTime = lastCommitTime(enRel)
    if (viTime > 0 && enTime > 0 && viTime > enTime) {
        warn(rel, `Bản tiếng Việt được sửa sau ${enRel}, cần rà lại bản dịch`)
    }
}

console.log(warnings === 0 ? 'Bản dịch đầy đủ.' : `Có ${warnings} cảnh báo về bản dịch.`)
