import Vue from 'vue'
import App from './App.vue'

import router from './route.js'

import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap';

import axiosApi from 'axios'

const axios = axiosApi.create({
    baseURL:`http://localhost:8000/api`,
    headers:{ header:'' }
});

Vue.prototype.axios = axios;

Vue.config.productionTip = false

new Vue({
    render: h => h(App),
    router
}).$mount('#app')
