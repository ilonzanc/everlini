import Vue from 'vue';
import App from './App.vue'
import router from './router/index';
import axios from 'axios';
import moment from 'moment';

import { store } from './store';


window.axios = axios;
window.moment = moment;
window.axios.defaults.headers.common = {
    //"Access-Control-Allow-Origin": "*",
};

new Vue({
  el: '#app',
  render: h => h(App),
  router: router,
  store
})