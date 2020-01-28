// require('./bootstrap');
window.Vue = require('vue');
Vue.config.productionTip = false;

import App from "./App.vue";

new Vue({
    el: "#root",
    components: {
        App,
    }
});
