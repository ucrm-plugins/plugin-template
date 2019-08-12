<template>
    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Group Permissions</h5>
            <button class="btn p-0" data-toggle="collapse" data-target=".card-body" aria-expanded="true">
                <i class="fas fa-chevron-down"></i>
            </button>
        </div>

        <div class="card-body collapse show in">
            <div class="d-flex">

                <div class="col-5 h-100 p-0 ">
                    <div class="form-group h-100 mb-0 d-flex flex-column">
                        <label
                            for="available-groups"
                            class="d-flex mb-2"
                        >
                            Available Groups
                        </label>
                        <select
                            id="available-groups"
                            class="form-control d-flex"
                            multiple
                            @change="availableSelectionChanged"
                            @blur="blurAvailableGroups"
                        >
                            <option
                                v-for="(item, index) in available"
                                :value="item"
                            >
                                {{ item }}
                            </option>
                        </select>
                    </div>
                </div>

                <div class="col-2 d-flex flex-column">
                    <div class="d-flex">
                        <label
                            class="sr-only">
                            Controls
                        </label>
                        <label
                            class="d-flex mb-2 justify-content-center">
                            &nbsp;
                        </label>
                    </div>
                    <div class="d-flex flex-grow-1 align-items-center">
                        <div id="control-buttons" class="d-flex flex-column w-100">
                            <button class="btn btn-block btn-primary"><i class="fas fa-angle-double-right"></i></button>
                            <button
                                class="btn btn-block btn-secondary"
                                @click="addSelectedAvailableClicked"
                                :disabled="selectedAvailable.length === 0">
                                <i class="fas fa-angle-right"></i>
                            </button>
                            <button
                                class="btn btn-block btn-secondary"
                                @click="removeSelectedAllowedClicked"
                                :disabled="selectedAllowed.length === 0">
                                <i class="fas fa-angle-left"></i>
                            </button>
                            <button class="btn btn-block btn-primary"><i class="fas fa-angle-double-left"></i></button>
                        </div>
                    </div>
                </div>

                <div class="col-5 h-100 p-0 ">
                    <div class="form-group h-100 mb-0 d-flex flex-column">
                        <label
                            for="allowed-groups"
                            class="d-flex mb-2 justify-content-end">
                            Allowed Groups
                        </label>
                        <select
                            id="allowed-groups"
                            class="form-control d-flex"
                            multiple
                            @change="allowedSelectionChanged"
                            @blur="blurAllowedGroups"
                        >
                            <option
                                v-for="(item, index) in allowed"
                                :value="item"
                            >
                                {{ item }}
                            </option>
                        </select>
                    </div>
                </div>


            </div>
        </div>

        <!--
        <div class="card-footer">

        </div>
        -->

    </div>
</template>

<script>




    export default {
        name: "PermissionGroups",

        props: {

            value: {
                type: Array,
                default: function() { return [ { value: 1, label: "Admin Group" } ]; },
            },

            items: {
                type: Array,
                default: function() { return [ { value: 1, label: "Admin Group" } ]; },
            }


        },

        watch: {

            value: function(current, previous)
            {
                this.allowed = current;
                this.$emit("input", current);
            },

            items: function(current, previous)
            {
                // Wait until the actual SELECT children are updated and then adjust the control's height.
                this.$nextTick(this.autoSelectHeight);
                this.$emit("update:items", current);
            },





            selectedAvailable: function(current, previous)
            {
                this.$nextTick(function()
                {
                    $("#available-groups").val(current);
                });
            },

            selectedAllowed: function(current, previous)
            {
                this.$nextTick(function()
                {
                    $("#allowed-groups").val(current);
                });
            },



        },

        computed: {






        },


        data: function()
        {
            return {

                available: [],
                allowed: [],

                selectedAvailable: [],
                selectedAllowed: [],



            }
        },


        methods: {

            //#region Event Handlers

            availableSelectionChanged: function()
            {
                let $available = $("#available-groups");
                let $selected = $available.children("option:selected");
                this.selectedAvailable = $available.val();
            },

            allowedSelectionChanged: function()
            {
                let $allowed = $("#allowed-groups");
                let $selected = $allowed.children("option:selected");
                this.selectedAllowed = $allowed.val();
            },



            addSelectedAvailableClicked: function()
            {
                let available = [];
                let allowed = [];
                let selected = [];

                this.available.forEach(
                    $.proxy(
                        function(item, index, items)
                        {
                            if (this.selectedAvailable.includes(item))
                            {
                                selected.push(item);
                                allowed.push(item);
                            }
                            else
                                available.push(item);
                        },
                        this
                    )
                );

                this.available = available;
                this.allowed = this.allowed.concat(allowed);

                this.selectedAvailable = [];
                this.selectedAllowed = selected;

                $("#allowed-groups").focus();
            },

            removeSelectedAllowedClicked: function()
            {
                let available = [];
                let allowed = [];
                let selected = [];

                this.allowed.forEach(
                    $.proxy(
                        function(item, index, items)
                        {
                            if (this.selectedAllowed.includes(item))
                            {
                                selected.push(item);
                                available.push(item);
                            }
                            else
                                allowed.push(item);
                        },
                        this
                    )
                );

                this.available = this.available.concat(available);
                this.allowed = allowed;

                this.selectedAvailable = selected;
                this.selectedAllowed = [];

                $("#available-groups").focus();
            },


            blurAvailableGroups: function(event)
            {
                //$(event.target).val([]);

                //this.selectedAvailable = [];

            },

            blurAllowedGroups: function(event)
            {
                //$(event.target).val([]);

                //this.selectedAllowed = [];
            },



            //#endregion


            autoSelectHeight: function()
            {
                let $available = $("#available-groups");
                let $allowed = $("#allowed-groups");
                let $controls = $("#control-buttons");

                if($available.children().length === 0)
                {
                    // Default input/control height!
                    $available.css("height", $controls.height() + "px");
                    $allowed.css("height", $controls.height() + "px");
                    return;
                }

                //let extraHeight = $select.outerHeight() -  $select.height();
                let height = $available.outerHeight() -  $available.height();
                //console.log("*", height);

                $available.children().each(function(index, option, options)
                {
                    let $option = $(option);
                    height += $option.outerHeight(true);
                });

                if(height < $controls.height())
                    height = $control.height();

                $available.css("height", height + "px");
                $allowed.css("height", height + "px");

                console.log("sized");
            },

        },


        mounted: function()
        {
            this.available = this.items;
            this.allowed = this.value;



            $(function()
            {



            });


            //let self = this;




            this.$nextTick(this.autoSelectHeight);


        },

    }
</script>

<style scoped>

</style>
