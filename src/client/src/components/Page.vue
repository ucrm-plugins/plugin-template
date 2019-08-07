<template>
    <div id="content-container">
        <div id="content" class="container-fluid h-100 mx-0 px-0 pt-3 pb-0 ">
            <slot></slot>
        </div>
    </div>
</template>

<script>

    export default {
        name: "Page",
        data() {
            return {
                pluginName: "Plugin Template (VueJS)",
                githubRepo: "https://github.com/ucrm-plugins/plugin-template-vue"
            }
        }
    }

</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

    #content-container {
        flex: auto;
        overflow-y: hidden;
    }

    #content {
        overflow-y: auto;
    }

</style>
