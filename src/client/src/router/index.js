import Vue from "vue";
import Router from "vue-router";
import PageNotFound from "../components/views/404";

import EditorDemo from "../components/views/EditorDemo";
import QueryBuilderDemo from "../components/views/QueryBuilderDemo";
import HtmlDesignerDemo from "../components/views/HtmlDesignerDemo";
import History from "../components/views/History";
import Logs from "../components/views/Logs";
import Settings from "../components/views/Settings";


Vue.use(Router);

const router = new Router({

    routes: [
        {
            path: "/",
            name: "Home",
            redirect: "/editor"
        },
        {
            path: "/editor",
            name: "Editor",
            component: EditorDemo
        },
        {
            path: "/query",
            name: "QueryBuilder",
            component: QueryBuilderDemo
        },
        {
            path: "/designer",
            name: "HtmlDesigner",
            component: HtmlDesignerDemo
        },
        {
            path: "/logs",
            name: "Logs",
            component: Logs
        },
        {
            path: "/settings",
            name: "Settings",
            component: Settings
        },

        //
        // Add any new routes here...
        //


        // And finally, the catch all route for HTTP 404 (Page Not Found) errors.
        {
            path: "*",
            name: "404",
            component: PageNotFound
        }
    ]
});

/*
router.beforeEach(function(to, from, next)
{
    console.log(to);
    //to.fullPath = to.fullPath.replace("?%2F", "");

    next();
});
*/



export default router;
