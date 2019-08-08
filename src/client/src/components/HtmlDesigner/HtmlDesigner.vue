<template>

    <div :id="'html-designer-' + _uid + '-container'" class="h-100">

        <div class="h-100 d-flex d-sm-none justify-content-center align-items-center">
            <div class="w-75 alert alert-warning text-center">This editor is not supported on devices with a screen width of less than 576 pixels!</div>
        </div>

        <div :id="'html-designer-' + _uid + '-editor'" class="h-100 d-none d-sm-block border border-dark ">
            <slot></slot>
        </div>

    </div>

</template>

<script>

    import grapes from "grapesjs";



    export default {
        name: "HtmlDesigner",

        components: {
        },

        props: {

        },

        data () {
            return {

                editor: null,

            }
        },

        computed: {

        },

        mounted: function()
        {




            this.editor = grapes.init({

                container: "#html-designer-" + this._uid + "-editor",
                fromElement: true,
                //height: "100%",
                //width: "auto",
                storageManager: { type: null },
                //panels: { defaults: [] },

                blockManager: {
                    appendTo: '#blocks',
                    blocks: [
                        {
                            id: 'section', // id is mandatory
                            label: '<b>Section</b>', // You can use HTML/SVG inside labels
                            attributes: { class:'gjs-block-section' },
                            content: `
                                <section>
                                    <h1>This is a simple title</h1>
                                    <div>This is just a Lorem text: Lorem ipsum dolor sit amet</div>
                                </section>
                            `,
                        },
                        {
                            id: 'text',
                            label: 'Text',
                            content: `
                                <div data-gjs-type="text">Insert your text here</div>
                            `,
                        },
                        {
                            id: 'image',
                            label: 'Image',
                            // Select the component once it's dropped
                            select: true,
                            // You can pass components as a JSON instead of a simple HTML string,
                            // in this case we also use a defined component type `image`
                            content: {
                                type: 'image'
                            },
                            // This triggers `active` event on dropped components and the `image`
                            // reacts by opening the AssetManager
                            activate: true,
                        }
                    ]
                },


            })





        }
    }
</script>

<!--suppress CssFloatPxLength, CssUnusedSymbol -->
<style lang="scss">
    @import "../../../node_modules/grapesjs/dist/css/grapes.min.css";

    :root {
        --content-min-width: calc(576px - 2rem - 2px); /* 546px */
        --sidebar-min-width: calc(576px - 2rem - 2px - 320px); /* 226px */
        --sidebar-width: calc( ( (576px - 2rem - 2px - 320px) / (576px - 2rem - 2px) ) * (576px - 2rem - 2px) );



    }

    /* Override some of the editor's default CSS in favor of our own! */
    .gjs-devices-c {
        padding: 0;
    }
    .gjs-pn-views, .gjs-pn-views-container {
        //min-width: calc(576px - 2rem - 2px - 320px);
        width: var(--sidebar-width);
    }
    .gjs-cv-canvas, .gjs-pn-commands {
        //max-width: calc(100% - var(--sidebar-min-width));
        width: var(--sidebar-width);
    }
    .gjs-pn-options {
        //right: var(--sidebar-min-width);
        right: var(--sidebar-width);
    }


    .gjs-pn-buttons
    {
        justify-content: flex-end;
    }

    .gjs-pn-buttons:last-child
    {
        margin-right: 0;
    }



    /* Override the template's min-width! */
    #content-container {
        //min-width: 495px !important;

    }

    .gjs-am-file-uploader form
    {
        height: 325px; /* Match the right panel? */
        margin-bottom: 0;
    }

    #gjs-am-uploadFile {
        position: relative;
        height: 100%;
        border: none; /* The form handles this above! */
    }

    /* TODO: Fix buttons in Style Manager! */

    .gjs-editor select
    {
        background-image: unset;
    }


</style>
