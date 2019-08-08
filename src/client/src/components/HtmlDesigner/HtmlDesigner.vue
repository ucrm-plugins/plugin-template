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

    import webpage from "grapesjs-preset-webpage"


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
                panels: null,

                styleManager: {},

                plugins: [
                    'gjs-preset-webpage'
                    //blocks

                ],

                pluginsOpts: {
                    'gjs-preset-webpage': {
                        // options
                    }
                }



            });





            $(function()
            {
                $(".gjs-radio-item-label[for='float-none']")
                    .text("")
                    .addClass("gjs-sm-icon")
                    .addClass("fa")
                    .addClass("fa-times");
                $(".gjs-radio-item-label[for='float-left']")
                    .text("")
                    .addClass("gjs-sm-icon")
                    .addClass("fa")
                    .addClass("fa-align-left");
                $(".gjs-radio-item-label[for='float-right']")
                    .text("")
                    .addClass("gjs-sm-icon")
                    .addClass("fa")
                    .addClass("fa-align-right");



            });



        }
    }
</script>

<style lang="scss">
    //@import "./plugins/grapesjs-preset-webpage/style/main.scss";
</style>

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

        .gjs-pn-devices-c
            z-index 6

        .gjs-pn-options
            z-index 5
            right calc(40% - ( 35px * 2 ) + 5px )

        /*
        .gjs-pn-btn.fa.fa-square-o
            position relative
            right -( (35px * 9) + 5px)
        .gjs-pn-btn.fa.fa-eye
            position relative
            right -( (35px * 7) + 5px)
        */



    @media screen and (min-width: 768px)
        .gjs-pn-views, .gjs-pn-views-container
            width 40%
        .gjs-pn-options
            right 40%
        .gjs-cv-canvas, .gjs-pn-commands
            width 60%

        .gjs-pn-devices-c
            z-index 3
        .gjs-pn-options
            z-index 4
            right 40%


        /*
        .gjs-pn-btn.fa.fa-square-o
            position inherit
            right 0
        .gjs-pn-btn.fa.fa-eye
            position inherit
            right 0
        */


    //@media screen and (min-width: 992px)
    //@media screen and (min-width: 1200px)


    .gjs-devices-c
        width 170px
        //padding 0


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


    //.gjs-editor
        //font-size 0.75rem

    .gjs-editor select
        background-image unset
        height auto

    .gjs-editor input[type]
        height: auto

    .gjs-editor input[type=radio]+label
        margin-left auto
        //font-size 1rem
        line-height normal
        margin-bottom 0






    //.gjs-pn-btn.fa.fa-undo
    //    position absolute
    //    right -20px
    //    z-index -1000





</style>
