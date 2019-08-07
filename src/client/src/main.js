import Vue from 'vue';
import App from './App.vue';
import router from "./router";
//import Router from "vue-router";
import path from "path";


Vue.config.productionTip = false;

Vue.prototype.$url = {

    href: window.location.href,
    hostname: window.location.hostname,
    pathname: window.location.pathname,
    protocol: window.location.protocol,


};




new Vue({
    router,
    render: h => h(App),
    mounted: function()
    {
        //let q = this.$route;
        //console.log(this.$route);
    },
}).$mount('#app');


/* eslint-disable no-new */
/*
new Vue({
    el: '#app',
    router,
    components: { App },
    template: '<App/>'
});
*/
