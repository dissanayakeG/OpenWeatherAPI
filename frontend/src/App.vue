<template>
    <div class="content">
        <router-view></router-view>
    </div>
</template>

<script>

    export default {
        name: 'App',

        mounted() {
            this.$router.push({name: 'dashboard'}).catch(() => {
            });
        }
    }
</script>

<style scoped>
    .content {
        display: flex;
        flex-direction: row;
    }
</style>
