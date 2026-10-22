<script setup lang="ts">
import { useData } from 'vitepress'

// Ghi chú tài liệu chưa hoàn thiện, hiện ở đầu mọi trang
const { lang } = useData()
const issuesUrl = 'https://github.com/nukeviet/nukeviet/issues'
</script>

<template>
    <div class="custom-block warning draft-notice">
        <p v-if="lang === 'en'">
            <strong>Note:</strong> This documentation is incomplete and still being updated, so some content may be
            inaccurate or missing. If you find a mistake, please report it via
            <a :href="issuesUrl" target="_blank" rel="noreferrer">GitHub Issues</a> or send a Pull Request.
        </p>
        <p v-else>
            <strong>Lưu ý:</strong> Đây là bộ tài liệu chưa hoàn thiện, đang trong quá trình cập nhật nên nội dung có
            thể không đúng hoặc chưa đủ. Nếu phát hiện sai lệch, xin báo lại qua
            <a :href="issuesUrl" target="_blank" rel="noreferrer">GitHub Issues</a> hoặc gửi Pull Request để sửa.
        </p>
    </div>
</template>

<style scoped>
.draft-notice {
    margin: 0 0 24px;
    padding: 12px 16px;
    font-size: 14px;
    line-height: 1.6;
}

.draft-notice p {
    margin: 0;
}
</style>
