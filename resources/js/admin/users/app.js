import Vue from 'vue'
import VueRouter from 'vue-router'
import axios from 'axios'
// import VCalendar from 'v-calendar'
import VueCal from 'vue-cal'
import 'vue-cal/dist/vuecal.css'
import 'vue-cal/dist/i18n/ru.js'

import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';

import router from './router.js';

import lang from 'element-ui/lib/locale/lang/ru-RU'
import locale from 'element-ui/lib/locale'

locale.use(lang)

Vue.component('vue-cal', VueCal)

Vue.use(VueRouter);
Vue.use(ElementUI);
// Vue.use(VCalendar);
Vue.use(VueCal);

// let accessToken = document.getElementById('access_token').value
// localStorage.setItem('accessToken', accessToken)

Vue.prototype.$http = axios.create({
    withCredentials: true,
    headers: {
        Pragma: 'no-cache',
        Authorization: `Bearer ${localStorage.getItem('psych_token')}`,
        // 'X-Api-Key': localStorage.getItem('accessToken'),
    }
})

Vue.prototype.$localStorage = localStorage;

async function init() {
    try {
        // let response = await axios.get('/check-auth-user')
        // Vue.prototype.$user = response.data

        const app = new Vue({
            el: "#app",
            router
        });
    } catch (e) {

    } finally {

    }
}

init()
