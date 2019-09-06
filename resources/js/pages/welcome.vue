<template>
    <div>
        Home page {{ $t('auth.failed')}}
    </div>
</template>

<script>
    export default {
        layout: 'default',

        metaInfo () {
            return { title: this.$t('auth.failed') }
        },

        data: () => ({
            title: window.config.appName
        }),
    }
</script>
