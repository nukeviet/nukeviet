import { defineConfig, type DefaultTheme } from 'vitepress'

/**
 * Đường dẫn gốc của site, CI truyền vào theo phiên bản (VD: /5.0/).
 * Chạy local không truyền thì dùng /5.0/
 */
const base = process.env.DOCS_BASE || '/5.0/'

/**
 * Nhánh mã nguồn tương ứng, dùng cho link "Sửa trang này"
 */
const branch = process.env.DOCS_BRANCH || 'nukeviet5.0'
const editPattern = `https://github.com/nukeviet/nukeviet/edit/${branch}/docs/manual/:path`

/**
 * Bỏ dấu tiếng Việt để tìm "cau hinh" vẫn ra "cấu hình"
 */
function processTerm(term: string): string {
    return term
        .normalize('NFD')
        .replace(/[̀-ͯ]/g, '')
        .replace(/đ/g, 'd')
        .replace(/Đ/g, 'D')
        .toLowerCase()
}

function sidebarVi(): DefaultTheme.Sidebar {
    return {
        '/guide/': [
            {
                text: 'Hướng dẫn sử dụng',
                items: [
                    { text: 'Mục lục', link: '/guide/' },
                    { text: 'Phần 1: Giới thiệu và khái niệm', link: '/guide/concepts/' },
                    { text: 'Phần 2: Cài đặt', link: '/guide/install/' },
                    { text: 'Phần 3: Quản trị hệ thống', link: '/guide/admin/' },
                    { text: 'Phần 4: Quản trị các module', link: '/guide/modules/' },
                    { text: 'Phần 5: Vận hành', link: '/guide/operation/' }
                ]
            }
        ],
        '/dev/': [
            {
                text: 'Tài liệu kỹ thuật',
                items: [
                    { text: 'Mục lục', link: '/dev/' },
                    { text: 'Phần 1: Tổng quan', link: '/dev/overview/' },
                    { text: 'Phần 2: Cấu hình nâng cao', link: '/dev/config/' },
                    { text: 'Phần 3: Quy chuẩn', link: '/dev/standards/' },
                    { text: 'Phần 4: Lập trình', link: '/dev/programming/' },
                    {
                        text: 'Phần 5: Hook',
                        link: '/dev/hooks/',
                        collapsed: true,
                        items: [{ text: 'Danh sách hook', link: '/dev/hooks/list' }]
                    },
                    { text: 'Phần 6: API', link: '/dev/api/' },
                    {
                        text: 'Phần 7: Tham chiếu',
                        link: '/dev/reference/',
                        collapsed: true,
                        items: [
                            { text: 'Hàm toàn hệ thống', link: '/dev/reference/functions/system' },
                            { text: 'Hàm chuỗi UTF-8', link: '/dev/reference/functions/utf8' },
                            { text: 'Hàm thao tác file, thư mục', link: '/dev/reference/functions/filesystem' },
                            { text: 'Hàm khu vực quản trị', link: '/dev/reference/functions/admin' },
                            { text: 'Hàm ngoài site', link: '/dev/reference/functions/site' },
                            { text: 'Hàm giao diện', link: '/dev/reference/functions/theme' }
                        ]
                    },
                    { text: 'Phần 8: Quy trình phát triển', link: '/dev/workflow/' }
                ]
            }
        ]
    }
}

function sidebarEn(): DefaultTheme.Sidebar {
    return {
        '/en/guide/': [
            {
                text: 'User guide',
                items: [{ text: 'Introduction', link: '/en/guide/' }]
            }
        ],
        '/en/dev/': [
            {
                text: 'Developer docs',
                items: [{ text: 'Introduction', link: '/en/dev/' }]
            }
        ]
    }
}

export default defineConfig({
    base,
    title: 'NukeViet 5',
    cleanUrls: false,
    lastUpdated: true,

    locales: {
        root: {
            label: 'Tiếng Việt',
            lang: 'vi',
            description: 'Tài liệu NukeViet 5',
            themeConfig: {
                nav: [
                    { text: 'Hướng dẫn sử dụng', link: '/guide/' },
                    { text: 'Tài liệu kỹ thuật', link: '/dev/' },
                    { text: 'Phiên bản cũ', link: 'https://wiki.nukeviet.vn/' }
                ],
                sidebar: sidebarVi(),
                outline: { label: 'Trên trang này', level: [2, 3] },
                docFooter: { prev: 'Trang trước', next: 'Trang sau' },
                lastUpdated: { text: 'Cập nhật lần cuối' },
                editLink: {
                    pattern: editPattern,
                    text: 'Sửa trang này trên GitHub'
                },
                darkModeSwitchLabel: 'Giao diện',
                sidebarMenuLabel: 'Menu',
                returnToTopLabel: 'Lên đầu trang',
                langMenuLabel: 'Ngôn ngữ'
            }
        },
        en: {
            label: 'English',
            lang: 'en',
            link: '/en/',
            description: 'NukeViet 5 documentation',
            themeConfig: {
                nav: [
                    { text: 'User guide', link: '/en/guide/' },
                    { text: 'Developer docs', link: '/en/dev/' },
                    { text: 'Older versions', link: 'https://wiki.nukeviet.vn/' }
                ],
                sidebar: sidebarEn(),
                editLink: {
                    pattern: editPattern,
                    text: 'Edit this page on GitHub'
                }
            }
        }
    },

    themeConfig: {
        socialLinks: [{ icon: 'github', link: 'https://github.com/nukeviet/nukeviet' }],
        search: {
            provider: 'local',
            options: {
                miniSearch: {
                    options: { processTerm }
                },
                locales: {
                    root: {
                        translations: {
                            button: { buttonText: 'Tìm kiếm', buttonAriaLabel: 'Tìm kiếm' },
                            modal: {
                                displayDetails: 'Hiển thị chi tiết',
                                resetButtonTitle: 'Xóa từ khóa',
                                backButtonTitle: 'Đóng',
                                noResultsText: 'Không có kết quả cho',
                                footer: {
                                    selectText: 'chọn',
                                    navigateText: 'di chuyển',
                                    closeText: 'đóng'
                                }
                            }
                        }
                    }
                }
            }
        }
    }
})
