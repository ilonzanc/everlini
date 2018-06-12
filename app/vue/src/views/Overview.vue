<template>
  <div id="overview__comp" class="content">
    <div class="container">
      <h1>Overzicht</h1>
      <aside class="aside__filter">
        <form @submit.prevent="onSubmit">
          <label for="location">Locatie</label>
          <input type="text" id="location" name="location" placeholder="Waar zullen we zoeken?" v-model="params.location.name">
          <label>Binnen <span class="radius-value">{{params.radiusValue}}</span> km</label>
          <vue-slider class="custom-slider" ref="slider" v-model="params.radiusValue" v-bind="slider_options"></vue-slider>
          <label>Datum</label>
          <tabs :tabsClassName="dateClass">
            <tab name="vandaag" ></tab>
            <tab name="deze week"></tab>
            <tab name="deze maand"></tab>
          </tabs>
          <div class="time-inputs">
              <label for="startdate">Van</label>
              <input class="inline-input" type="date" :min="getDateOfToday()" id="startdate" name="startdate" placeholder="dd-mm-jjjj" v-model="params.startdate">
              <label for="enddate">Tot</label>
              <input class="inline-input" type="date" id="enddate" name="enddate" placeholder="dd-mm-jjjj" v-model="params.enddate">
          </div>
          <label>Interesses</label>
          <div class="interests_input">
            <input type="text" name="0" v-model="params.interests[0]" placeholder="Eigen interesse toevoegen...">
            <i class="fa fa-plus" @click.prevent="addRow"></i>
          </div>
          <div class="additional_interests" v-bind:key="row.index" v-for="row in rows">
            <div class="interests_input">
              <input type="text" :name="currentInputIndex" v-model="params.interests[row.index + 1]" placeholder="Nog eentje...">
              <i class="fa fa-plus" @click.prevent="addRow"></i>
            </div>
          </div>
          <button type="submit" class="btn primary-btn">Zoeken</button>
        </form>
      </aside>
      <router-link :to="'/evenementen/' + event.id" v-bind:key="event.id" v-for="event in events">
        <section class="event" >
          <div class="event-date">
            <div class="event-day">{{event.startdate | moment("DD")}}</div>
            <div class="event-month">{{event.startdate | moment("MMM")}}</div>
          </div>
          <div class="event-details">
            <h3>{{ event.name }}</h3>
          </div>
        </section>
      </router-link>
      <a
        href="#"
        data-event-type="meetup"
        v-if="meetupevent.visibility == 'public'"
        v-bind:key="meetupevent.id"
        v-for="meetupevent in meetupevents"
        @click.prevent="saveMeetupEvent(meetupevent.id, meetupevent.group.urlname)"
      >
        <section class="event" >
          <div class="event-date">
            <div class="event-day">{{meetupevent.local_date | moment("DD")}}</div>
            <div class="event-month">{{meetupevent.local_date | moment("MMM")}}</div>
          </div>
          <div class="event-details">
            <h3>{{ meetupevent.name }}</h3>
          </div>
        </section>
      </a>
      <section v-if="events.length == 0 && meetupevents.length == 0">
        <p>Geen evenementen gevonden :(</p>
      </section>
    </div>
  </div>
</template>

<script>
  //import axios from "axios";
  import moment from 'moment';
  import vueSlider from 'vue-slider-component';
  import Tabs from '../Components/Tabs.vue';
  import Tab from '../Components/Tab.vue';

  export default {
    name: "overview",
    computed: {
			searchparams() {
        return this.$store.getters.getSearchValues;
      }
		},
    components: {
      vueSlider,
      'tabs': Tabs,
      'tab': Tab
    },
    data() {
      return {
        events: [],
        meetupevents: [],
        currentInputIndex: 0,
        inputs: [ 'fullname', 'email'],
        rows: [],
        params: {
          location: {
            name: "",
            lat: "",
            lng: ""
          },
          startdate: "",
          enddate: "",
          interests: [],
          radiusValue: 0,

        },
        slider_options: {
          min: 0,
          max: 25,
          interval: 5,
          piecewise: true,
          piecewiseLabel: true,
          tooltip: false,
          data: [
            "0",
            "5",
            "10",
            "15",
            "20",
            "25",
          ],
          dotSize: 25,
        },
        dateClass: "dateTabs",
        asideOpen: false,
        eventIsMeetup: false,
        currentMeetUpEvent: {
            id: "",
            groupname: ""
        }
      }
    },
    mounted() {
      const apiurldev = "http://localhost:8765";
      const apiurlprod = "https://ilonaapi.3.web.codedor.online";
      this.params = this.searchparams;
      this.eventIsMeetup = this.meetupeventstate;
      this.$store.commit('clearMeetUpEvent', null);
      axios({
        method: "post",
        url: apiurldev + "/api/events.json",
        headers: { },
        data: this.searchparams
      })
      .then(response => {
        console.log(response);
        this.events = response.data.events;
      })
      .catch(error => {
        console.log(error);
      });

      let floatLat = parseFloat(this.searchparams.location.lat);
      let floatLng = parseFloat(this.searchparams.location.lng);

      let formattedStartDate = moment(String(this.searchparams.startdate)).format('YYYY-MM-DDTHH:MM:SS');
      let formattedEndDate = moment(String(this.searchparams.enddate)).format('YYYY-MM-DDTHH:MM:SS');

      let interests = this.searchparams.interests;

      for (let i = 0; i < interests.length; i++) {
        let interest = interests[i];
        this.$jsonp('https://api.meetup.com/find/upcoming_events?key=766033144c453b4d295465e352538&sign=true&photo-host=public&lon=' + floatLng +
          '&page=20&radius=' + this.searchparams.radiusValue +
          '&lat=' + floatLat +
          '&start_date_range=' + formattedStartDate +
          '&end_date_range=' + formattedEndDate +
          '&fields=*, group_category' +
          '&text=' + interest
        )
        .then(json => {
          console.log(json);
          for (let j = 0; j < json.data.events.length; j ++) {
            this.meetupevents.push(json.data.events[j]);
          }

        }).catch(err => {
          console.log(err);
        })
      }


    },
    methods: {
      getDateOfToday() {
        let today = new Date();
        today = moment(String(today)).utcOffset(0).format('YYYY-MM-DD');
        return today;
      },
      saveMeetupEvent(id, groupname) {
        console.log('clicked on meetup event');
        this.currentMeetUpEvent.id = id;
        this.currentMeetUpEvent.groupname = groupname;
        this.$store.commit('updateMeetUpEvent', this.currentMeetUpEvent);
        this.$router.push('/evenementen/' + id);
      }
    }
  };
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style>
</style>