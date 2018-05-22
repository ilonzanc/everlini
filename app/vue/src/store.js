import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

export const store = new Vuex.Store({
    state: {
        searchparams: {
            location: "",
            startdate: "",
            enddate: "",
            interests: []
        }
    },
    getters: {
        getSearch(state) {
            return state.searchparams;
        }
    },
    mutations: {
        updateSearch(state, newparams) {
            state.searchparams.location = newparams.location;
            state.searchparams.startdate = newparams.startdate;
            state.searchparams.enddate = newparams.enddate;
            state.searchparams.interests = newparams.interests;
        }
    }
})