<script setup lang="ts">
import DefaultTheme from 'vitepress/theme'
import DraftNotice from './DraftNotice.vue'

const { Layout } = DefaultTheme
</script>

<template>
    <Layout>
        <template #home-hero-after>
            <div class="draft-notice-home">
                <DraftNotice />
            </div>
        </template>
        <template #doc-before>
            <DraftNotice />
        </template>
    </Layout>
</template>

<style scoped>
.draft-notice-home {
    max-width: 1152px;
    margin: 0 auto;
    padding: 0 24px;
}

@media (min-width: 640px) {
    .draft-notice-home {
        padding: 0 48px;
    }
}

@media (min-width: 960px) {
    .draft-notice-home {
        padding: 0 64px;
    }
}
</style>
