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
            userInterests: [],
            defaultInterests: [],
            radiusValue : "",
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
            state.searchparams.userInterests = newparams.userInterests;
            state.searchparams.defaultInterests = newparams.defaultInterests;
        },
        clearSearchValues(state) {
            state.searchparams.location.name = null;
            state.searchparams.location.lat = null;
            state.searchparams.location.lng = null;
            state.searchparams.radiusValue = null;
            state.searchparams.startdate = null;
            state.searchparams.enddate = null;
            state.searchparams.userInterests = [];
            state.searchparams.defaultInterests = [];
            state.searchparams.interests = [];
        },
        updateEventIsMeetup(state, newparams) {
            state.eventIsMeetup = newparams;
        },
        updateMeetUpEvent(state, newparams) {
            state.currentMeetUpEvent = newparams;
        },
        clearMeetUpEvent(state, newparams) {
            state.currentMeetUpEvent.id = null;
            state.currentMeetUpEvent.groupname = null
        }
    },
    plugins: [createPersistedState()]
})