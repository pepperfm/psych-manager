import axios from '../../classes/AxiosWrapper';
const url = '/api/v1';


const state = {
    clients: [],
    calendarSessions: [],
};

const getters = {
    clients: state => state.clients,
    calendarSessions: state => state.calendarSessions,
};

const actions = {
    async getClients({ commit }) {
        const response = await axios.get(`${url}/session-clients`);
        commit('setClients', response.data.data.clients);
    },

    async getCalendarSessions({ commit }) {
        let response = await axios.get(`${url}/calendar-sessions`)
        commit('setCalendarSessions', response.data.data.sessions);
    },


};

const mutations = {
    setClients: (state, clients) => state.clients = clients,
    setCalendarSessions: (state, calendarSessions) => state.calendarSessions = calendarSessions,

};

export default {
    namespaced: true,
    state, getters, actions, mutations
};