import axios from '../../classes/AxiosWrapper';
const url = '/api/v1/static-data';


const state = {
    categories: [],
    connection_types: [],
    meeting_types: [],

};

const getters = {
    categories: state => state.categories,
    connection_types: state => state.connection_types,
    meeting_types: state => state.meeting_types,
};

const actions = {
    async getCategories({ commit }) {
        const response = await axios.get(`${url}/categories`);
        commit('setCategories', response.data.data.categories);
    },
    async getConnectionTypes({ commit }) {
        const response = await axios.get(`${url}/connection-types`);
        commit('setConnectionTypes', response.data.data.connection_types);
    },
    async getMeetingTypes({ commit }) {
        const response = await axios.get(`${url}/meeting-types`);
        commit('setMeetingTypes', response.data.data.meeting_types);
    },
    // async getServicesByCategoryId({ commit }, {id}) {
    //     const response = await Vue.request().get('categories/' + id + '/services');
    //     commit('setServices', response.data);
    // },
};

const mutations = {
    setCategories: (state, categories) => state.categories = categories,
    setConnectionTypes: (state, connection_types) => state.connection_types = connection_types,
    setMeetingTypes: (state, meeting_types) => state.meeting_types = meeting_types,

};

export default {
    namespaced: true,
    state, getters, actions, mutations
};