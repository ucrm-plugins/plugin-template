<template>
    <div class="card mb-3">

        <div class="card-header d-flex justify-content-between align-items-center">

            <h5 class="mb-0">Group Permissions</h5>

            <button
                ref="collapseButton"
                class="collapse-button btn p-0"
                data-toggle="collapse"
                :data-target="'#collapse-' + _uid"
                aria-expanded="true"
                @click="collapseClicked">
                <i
                    ref="collapseIcon"
                    class="collapse-icon fas"
                    :class="[ 'fa-chevron-' + (startExpanded ? 'down' : 'up') ]">
                </i>
            </button>

        </div>

        <div
            ref="collapse"
            :id="'collapse-' + _uid"
            class="collapse in show">

            <div class="card-body">
                <div class="d-flex">

                    <div class="col-5 h-100 p-0 ">
                        <div class="form-group h-100 mb-0 d-flex flex-column">
                            <label
                                for="available-groups"
                                class="d-flex mb-2"
                            >
                                Available Groups
                            </label>

                            <div>
                                <div
                                    v-if="availableLoading"
                                    id="available-groups-loading"
                                    class="d-flex justify-content-center align-items-center">
                                    <i class="fas fa-spinner fa-spin fa-2x"></i>
                                </div>

                                <select
                                    id="available-groups"
                                    class="form-control d-flex"
                                    multiple
                                    @change="availableSelectionChanged"
                                    @blur="blurAvailableGroups"
                                >
                                    <option
                                        v-for="(item, index) in sortedAvailable"
                                        :value="item"
                                    >
                                        {{ item }}
                                    </option>
                                </select>
                            </div>
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
                                <button
                                    id="allAvailableButton"
                                    class="btn btn-block btn-primary"
                                    @click="allSelectedAvailableClicked"
                                    :disabled="available.length === 0">
                                    <i class="fas fa-angle-double-right"></i>
                                </button>
                                <button
                                    id="addAvailableButton"
                                    class="btn btn-block btn-secondary"
                                    @click="addSelectedAvailableClicked"
                                    :disabled="selectedAvailable.length === 0">
                                    <i class="fas fa-angle-right"></i>
                                </button>
                                <button
                                    id="addAllowedButton"
                                    class="btn btn-block btn-secondary"
                                    @click="addSelectedAllowedClicked"
                                    :disabled="selectedAllowed.length === 0">
                                    <i class="fas fa-angle-left"></i>
                                </button>
                                <button
                                    id="allAllowedButton"
                                    class="btn btn-block btn-primary"
                                    @click="allSelectedAllowedClicked"
                                    :disabled="allowed.length === 0">
                                    <i class="fas fa-angle-double-left"></i>
                                </button>
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

                            <div>
                                <div
                                    v-if="allowedLoading"
                                    id="allowed-groups-loading"
                                    class="d-flex justify-content-center align-items-center">
                                    <i class="fas fa-spinner fa-spin fa-2x"></i>
                                </div>

                                <select
                                    id="allowed-groups"
                                    class="form-control d-flex"
                                    multiple
                                    @change="allowedSelectionChanged"
                                    @blur="blurAllowedGroups"
                                >
                                    <option
                                        v-for="(item, index) in sortedAllowed"
                                        :value="item"
                                        :disabled="item === 'Admin Group'"
                                    >
                                        {{ item }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>


                </div>
            </div>

            <div class="card-footer d-flex justify-content-between">

                <button
                    ref="defaultButton"
                    class="btn btn-secondary">
                    Default
                </button>

                <button
                    ref="updateButton"
                    class="btn btn-primary">
                    Update
                </button>

            </div>

        </div>

    </div>
</template>

<script>

    import axios from "axios";




    export default {
        name: "PermissionGroups",

        props: {

            value: {
                type: Array,
                default: function() { return [ { value: 1, label: "Admin Group" } ]; },
            },

            /*
            items: {
                type: Array,
                default: function() { return [ { value: 1, label: "Admin Group" } ]; },
            },
            */

            startExpanded: {
                type: Boolean,
                default: true,
            },


        },

        watch: {

            value: function(current, previous)
            {
                this.allowed = current;
                this.$emit("input", current);
            },


            items: function(current, previous)
            {
                this.available = current;

                // Wait until the actual SELECT children are updated and then adjust the control's height.
                this.$nextTick(this.autoSelectHeight);
                this.$emit("update:items", current);
            },



            available: function(current, previous)
            {
            },

            allowed: function(current, previous)
            {
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

            sortedAvailable: function()
            {
                let sorted = [];

                this.items.forEach(
                    $.proxy(
                        function(item, index, items)
                        {
                            if(this.available.includes(item))
                                sorted.push(item);
                        },
                        this
                    )
                );

                return sorted;

            },

            sortedAllowed: function()
            {
                let sorted = [];

                this.items.forEach(
                    $.proxy(
                        function(item, index, items)
                        {
                            if(this.allowed.includes(item))
                                sorted.push(item);
                        },
                        this
                    )
                );

                return sorted;
            },


        },


        data: function()
        {
            return {

                available: [],
                allowed: [],

                selectedAvailable: [],
                selectedAllowed: [],

                items: [],

                availableLoading: false,
                allowedLoading: false,

            }
        },


        methods: {

            moveToAllowed: function(groups, selected = false)
            {
                this.selectedAvailable = groups;

                this.$nextTick(
                    $.proxy(
                        function()
                        {
                            $("#addAvailableButton").click();

                            if(!selected)
                                this.selectedAllowed = [];
                        },
                        this
                    )
                );

            },

            moveToAvailable: function(groups, selected = false)
            {
                this.selectedAllowed = groups;

                this.$nextTick(
                    $.proxy(
                        function()
                        {
                            $("#addAllowedButton").click();

                            if(!selected)
                                this.selectedAvailable = [];
                        },
                        this
                    )
                );

            },


            //#region Event Handlers

            availableSelectionChanged: function()
            {
                let $available = $("#available-groups");
                this.selectedAvailable = $available.val();
            },

            allowedSelectionChanged: function()
            {
                let $allowed = $("#allowed-groups");
                this.selectedAllowed = $allowed.val();
            },


            allSelectedAvailableClicked: function()
            {
                this.selectedAvailable = this.available;
                this.$nextTick(function()
                {
                    $("#addAvailableButton").click();
                });
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

                //console.log(selected);

                $("#allowed-groups").focus();
            },

            getAllAllowed: function(disabled = false)
            {
                let allAllowed = [];

                this.allowed.forEach(function(group)
                {
                    if(group !== "Admin Group" || disabled)
                        allAllowed.push(group);
                });

                return allAllowed;
            },

            getAllAvailable: function(disabled = false)
            {
                let allAvailable = [];

                this.available.forEach(function(group)
                {
                    if(group !== "Admin Group" || disabled)
                        allAvailable.push(group);
                });

                return allAvailable;
            },

            allSelectedAllowedClicked: function()
            {
                this.moveToAvailable(this.getAllAllowed());
            },

            addSelectedAllowedClicked: function()
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


            collapseClicked: function()
            {
                let $button = $(this.$refs.collapseButton);
                let $icon = $(this.$refs.collapseIcon);

                if($button.hasClass("collapsed"))
                    $icon.css("transform", this.startExpanded ? "rotate(0deg)" : "rotate(-180deg)");
                else
                    $icon.css("transform", this.startExpanded ? "rotate(180deg)" :  "rotate(0deg)");
            },


            autoSelectHeight: function()
            {
                let $available = $("#available-groups");
                let $allowed = $("#allowed-groups");
                let $controls = $("#control-buttons");

                let $availableLoading = $("#available-groups-loading");
                let $allowedLoading = $("#allowed-groups-loading");

                if($available.children().length === 0)
                {
                    // Default input/control height!
                    $available.css("height", $controls.height() + "px");
                    $allowed.css("height", $controls.height() + "px");
                    $availableLoading.css("height", $controls.height() + "px");
                    $allowedLoading.css("height", $controls.height() + "px");
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
                    height = $controls.height();

                $available.css("height", height + "px");
                $allowed.css("height", height + "px");
                $availableLoading.css("height", height + "px");
                $allowedLoading.css("height", height + "px");
                //console.log("sized");
            },

        },

        created: function()
        {
            let self = this;

            this.availableLoading = true;

            axios
                .get("public.php?/api/permissions/groups")
                .then(function(response)
                {
                    let names = [];

                    response.data.forEach(function(group)
                    {
                        names.push(group.name);
                    });

                    self.items = names;
                    self.availableLoading = false;
                })
                .catch(function(error)
                {
                    console.log(error);
                });


            this.allowedLoading = true;

            axios
                .get("public.php?/api/permissions/groups/allowed")
                .then(function(response)
                {
                    let names = [];

                    response.data.forEach(function(group)
                    {
                        if(self.available.includes(group))
                            names.push(group);
                    });

                    self.moveToAllowed(names);

                    self.allowedLoading = false;
                })
                .catch(function(error)
                {
                    console.log(error);
                });





        },

        mounted: function()
        {
            //this.available = this.items;

            this.allowed = this.value;

            let self = this;

            $(function()
            {


            });


            //let self = this;




            this.$nextTick(
                $.proxy(
                    function()
                    {
                        this.autoSelectHeight();

                        let $button = $(this.$refs.collapseButton);
                        let $icon = $(this.$refs.collapseIcon);
                        let $collapse = $(this.$refs.collapse);

                        if(!this.startExpanded)
                        {
                            $button
                                .attr("aria-expanded", false)
                                .addClass("collapsed");

                            $collapse.removeClass("show");
                        }




                    },
                    this
                )
            );






        },

    }
</script>

<style lang="stylus" scoped>

.collapse-button
    box-shadow none
    i.collapse-icon
        transition transform 0.3s ease-in-out


#available-groups-loading, #allowed-groups-loading
    position absolute
    width 100%


</style>
