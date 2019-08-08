<template>

    <div :id="'html-designer-' + _uid + '-container'" class="h-100">

        <div class="h-100 d-flex d-sm-none justify-content-center align-items-center">
            <div class="w-75 alert alert-warning text-center">This editor is not supported on devices with a screen width of less than 576 pixels!</div>
        </div>

        <div :id="'html-designer-' + _uid + '-editor'" class="h-100 d-none d-sm-block border border-dark ">
            <slot></slot>
        </div>

        <!--<div id="#blocks"></div>-->




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
                    //appendTo: '#blocks',
                    blocks: [
                        /*
                        {
                            id: 'section', // id is mandatory
                            label: '<b>Section</b>', // You can use HTML/SVG inside labels
                            attributes: { class:'gjs-block-section' },
                            content:
                                `<section>
                                    <h1>This is a simple title</h1>
                                    <div>This is just a Lorem text: Lorem ipsum dolor sit amet</div>
                                </section>`,
                        },

                         */
                        {
                            id: 'text',
                            label: 'Text',
                            content: '<div data-gjs-type="text">Insert your text here</div>',
                            category: "Test",
                            attributes: {
                                //class: "gjs-fonts gjs-f-b1",
                                //class: "gjs-fonts gjs-f-b2",
                                //class: "gjs-fonts gjs-f-b3",
                                //class: "gjs-fonts gjs-f-b37",
                                class: "gjs-fonts gjs-f-text",
                                //class: "fa fa-link",
                                //class: "gjs-fonts gjs-f-image",
                                //class: "fa fa-youtube-play",
                                //class: "fa fa-map-o",
                                //class: "fa fa-youtube-play",
                                //class: "fa fa-quote-right",
                                //class: "gjs-fonts gjs-f-h1p",

                                // For others, inspect code on: https://grapesjs.com/demo.html
                                // Also check out: https://github.com/artf/grapesjs-blocks-basic
                            }

                        },
                        /*
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
                        */

                    ]
                },


            })





        }
    }
</script>

<!--suppress CssFloatPxLength, CssUnusedSymbol -->
<style lang="stylus">
    @import "../../../node_modules/grapesjs/dist/css/grapes.min.css";

    rem2px(value)
        unit(value) is "rem" ? unit(value * 16, "px") : unit(value, unit(value))

    editor-min-width        = 576px - rem2px(2rem) + 2px                            // 546px
    canvas-min-width        = 320px                                                 // 320px
    panels-min-width        = editor-min-width - canvas-min-width                   // 226px
    canvas-min-percentage   = unit( ( 320px / editor-min-width ) * 100, "%")        // 58.60805860805861%
    panels-min-percentage   = 100% - canvas-min-percentage                          // 41.39194139194139%

    @media screen and (min-width: 576px)
        .gjs-pn-views, .gjs-pn-views-container
            //min-width panels-min-width
            width panels-min-percentage
        .gjs-pn-options
            right panels-min-percentage
        .gjs-cv-canvas, .gjs-pn-commands
            min-width canvas-min-width
            width canvas-min-percentage

    @media screen and (min-width: 768px)
        .gjs-pn-views, .gjs-pn-views-container
            width 40%
        .gjs-pn-options
            right 40%
        .gjs-cv-canvas, .gjs-pn-commands
            width 60%

    //@media screen and (min-width: 992px)
    //@media screen and (min-width: 1200px)


    .gjs-devices-c
        width 170px
        padding 0


    .gjs-pn-buttons
        justify-content flex-end
        > span:last-child
            margin-right 0


    .gjs-am-file-uploader form
        height 325px
        margin-bottom 0

    #gjs-am-uploadFile
        position relative
        height 100%
        border none


    /* TODO: Fix buttons in Style Manager! */

    .gjs-editor select
    {
        background-image: unset;
    }


</style>
