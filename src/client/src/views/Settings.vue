<template>
    <div class="hello">
        <h3>Settings</h3>

        <!--
        <input type="text" class="form-control" value="Test" />

        <TagsInput v-model="selected" :tags="tags" ></TagsInput>
        -->

        <PermissionGroups v-model="allowed" :items="items"></PermissionGroups>

        <button class="btn btn-primary" @click="allowed = [ { value: 1, label: 'Admin Group' } ]">Default</button>

    </div>
</template>

<script>

    import axios from "axios";

    import PermissionGroups from "../components/Permissions/PermissionGroups";

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


            }
        },


        created: function()
        {
            axios
                .get("public.php?/api/psql/user-groups")
                .then(function(response)
                {
                    console.log(response.data);
                })
                .catch(function(error)
                {
                    console.log(error);
                });




        },


    }

</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>



</style>
