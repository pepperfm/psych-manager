import Vue from 'vue'
import VueRouter from 'vue-router'
import router from './router'
import store from './store'

import Identity from "./classes/Identity";
import axios from "./classes/AxiosWrapper"

// import VCalendar from 'v-calendar'
import VueCal from 'vue-cal'
import 'vue-cal/dist/vuecal.css'
import 'vue-cal/dist/i18n/ru.js'

import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';

import 'bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';

import lang from 'element-ui/lib/locale/lang/ru-RU'
import locale from 'element-ui/lib/locale'

locale.use(lang)

Vue.component('vue-cal', VueCal)

Vue.use(VueRouter);
Vue.use(ElementUI, { size: 'small'});
// Vue.use(VCalendar);
Vue.use(VueCal);

async function init() {
  try {
    let token = localStorage.getItem('accessToken')
    let headers = { Pragma: 'no-cache', Authorization: `Bearer ${token}` }
    let response = await axios.get('/api/v1/users', {
      withCredentials: true,
      headers: headers,
    })
    window.$identity = Vue.prototype.$identity = new Identity(response.data.data.user)
  } catch (e) {
    console.log(e);
    if (!e.response) {
      throw e
    }
    window.$identity = Vue.prototype.$identity = new Identity
  } finally {
  }

  Vue.prototype.$http = axios
  Vue.prototype.$localStorage = localStorage;

  new Vue({
    el: '#app',
    router,
    store,
    methods: {}
  })//.$mount('#app');
}

init()
