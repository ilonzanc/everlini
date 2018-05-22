//import Vue from 'vue';

import Vue from 'vue';
import App from './App.vue'
import router from './router/index';
import axios from 'axios';
import moment from 'moment';

import { store } from './store';


window.axios = axios;
window.moment = moment;
/* window.axios.defaults.headers.common = {
    //'X-CSRF-TOKEN': window.Laravel.csrfToken,
    'X-Requested-With': 'XMLHttpRequest',
    'Access-Control-Allow-Origin': '*'
} */

//require('./bootstrap');

new Vue({
  el: '#app',
  render: h => h(App),
  router: router,
  store
})