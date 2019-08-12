<template>
    <div class="hello">
        <h3>Settings</h3>

        <!--
        <input type="text" class="form-control" value="Test" />

        <TagsInput v-model="selected" :tags="tags" ></TagsInput>
        -->

        <PermissionGroups
            v-model="allowed"
            :items="items"
            :start-expanded="true"
        ></PermissionGroups>

        <button class="btn btn-primary" @click="items = items2">Change</button>

    </div>
</template>

<script>

    import axios from "axios";

    import PermissionGroups from "../components/PermissionBuilder/PermissionGroups";

    export default {

        name: "Settings",
        components: {
            PermissionGroups
        },

        watch: {


        },


        data () {
            return {
                selected: [ "Admin Group", "Technicians", "Operators", "Assistants", "Secretaries", "Admin Group" ],
                tags: [ "Admin Group", "Operators", "Technicians" ],


                selectedIndex: -1,

                allowed: [],

                items: [
                    "Admin Group",
                    "Managers",
                    "Technicians",
                    "Operators",
                    "Installers",
                    "Assistants",
                    "Secretaries",
                    "Enterprise Clients",
                    "Interns",
                ],

                items2: [
                    "Admin Group",
                    "Managers",
                    "Technicians",
                    "Enterprise Clients",
                    "Interns",
                ],

            }
        },


        created: function()
        {





        },


    }

</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>



</style>
