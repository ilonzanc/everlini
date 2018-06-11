import Vue from 'vue';
import Vuex from 'vuex';
import createPersistedState from "vuex-persistedstate";

Vue.use(Vuex);

export const store = new Vuex.Store({
    state: {
        searchparams: {
            location: {
                name: "",
                lat: "",
                lng: "",
            },
            startdate: "",
            enddate: "",
            interests: [],
            radiusValue : ""
        },
        eventIsMeetup: false,
        currentMeetUpEvent: {
            id: "",
            groupname: ""
        }
    },
    getters: {
        getSearchValues(state) {
            return state.searchparams;
        },
        getEventIsMeetup(state) {
            return state.eventIsMeetup;
        },
        getCurrentMeetUpEvent(state) {
            return state.currentMeetUpEvent;
        },
    },
    mutations: {
        updateSearchValues(state, newparams) {
            state.searchparams.location.name = newparams.location.name;
            state.searchparams.location.lat = newparams.location.lat;
            state.searchparams.location.lng = newparams.location.lng;
            state.searchparams.radiusValue = newparams.radiusValue;
            state.searchparams.startdate = newparams.startdate;
            state.searchparams.enddate = newparams.enddate;
            state.searchparams.interests = newparams.interests;
        },
        updateEventIsMeetup(state, newparams) {
            state.eventIsMeetup = newparams;
        },
        updateMeetUpEvent(state, newparams) {
            state.currentMeetUpEvent = newparams;
        }
    },
    plugins: [createPersistedState()]
})