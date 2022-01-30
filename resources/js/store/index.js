import Vue from 'vue'
import Vuex from 'vuex'
import staticData from "./modules/static-data";
import sessions from "./modules/sessions";
Vue.use(Vuex)

export default new Vuex.Store({
    modules: {
        staticData,
        sessions,
    }
})
