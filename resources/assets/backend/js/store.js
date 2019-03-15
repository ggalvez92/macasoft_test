import Vue from "vue";
import Vuex from 'vuex';

Vue.use(Vuex);

// state
export const state = {
    roles: []
}

// getters
export const getters = {
    getRoles: state => {
        return state.roles;
    },
}

// mutations
export const mutations = {
    updateRoles: function( state, _roles ) {
        state.roles = _roles;
    },
}
// actions
export const actions = {
}

export default new Vuex.Store({
    state: state,
    getters: getters,
    mutations: mutations,
    actions: actions,
})
