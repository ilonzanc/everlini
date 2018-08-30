import Vue from 'vue';
import App from './App.vue'
import router from './router/index';
import axios from 'axios';
import moment from 'moment';

import VueResource from 'vue-resource';
Vue.use(VueResource);


import VueJsonp from 'vue-jsonp';
Vue.use(VueJsonp)

import { store } from './store';

import './assets/sass/main.scss'
import './assets/css/main.css'

Vue.config.productionTip = false


window.axios = axios;
window.moment = moment;
window.axios.defaults.headers.common = {
    //"Access-Control-Allow-Origin": "*",
};

// dev
window.apiurl = "http://localhost:8765/api/";

// prod
//window.apiurl = "http://ilonazancaner.be/everlini/admin/api/";

new Vue({
  el: '#app',
  render: h => h(App),
  router: router,
  store,
  VueResource,
  VueJsonp
})