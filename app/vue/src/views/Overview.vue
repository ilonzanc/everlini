<template>
  <div id="overview__comp" class="content">
    <div class="container">
      <h1>Overzicht</h1>
      <div class="row">
        <div class="column column-lg-4">
          <aside class="aside__filter">
        <form @submit.prevent="onSubmit">
          <label for="location">Locatie</label>
            <input id="pac-input" type="text" name="location" placeholder="Waar zullen we zoeken?" v-model="params.location.name" @keyup="initMap">
            <span v-if="errors.location.name" class="form-error"><i class="fa fa-exclamation-triangle"></i>{{errors.location.name}}</span>
          <label>Binnen <span class="radius-value">{{params.radiusValue}}</span> km</label>
          <vue-slider class="custom-slider" ref="slider" v-model="params.radiusValue" v-bind="slider_options"></vue-slider>
          <label>Datum</label>
          <div class="time-inputs">
              <div>
              <label for="startdate">Van</label>
              <input class="inline-input" type="date" :min="getDateOfToday()" id="startdate" name="startdate" placeholder="dd-mm-jjjj" v-model="params.startdate">
              </div>
              <div>
              <label for="enddate">Tot</label>
              <input class="inline-input" type="date" id="enddate" name="enddate" placeholder="dd-mm-jjjj" v-model="params.enddate">
              </div>
          </div>
          <label class="required" for="interests">Interesses</label>
            <ul v-if="params.userInterests.length > 0 || params.defaultInterests.length > 0">
              <li v-for="interest in params.defaultInterests" v-bind:key="interest.index" class="tag removable-tag" @click.prevent="deleteInterest($event, 'default')">{{ interest }}</li>
              <li v-for="interest in params.userInterests" v-bind:key="interest.index" class="tag removable-tag" @click.prevent="deleteInterest($event, 'user')">{{ interest }}</li>
            </ul>
          <div class="interests_input">
            <input type="text" name="0" v-model="currentInterest" placeholder="Eigen interesse toevoegen...">
            <i class="fa fa-plus" @click.prevent="addInterest"></i>
          </div>
          <button type="submit" class="btn primary-btn">Zoeken</button>
        </form>
      </aside>
        </div>
        <div class="column column-lg-8">
          <section class="overview-event-list">
        <div v-if="events.length > 0">
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
      </div>
      <div v-if="meetupevents.length > 0">
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
      </div>
      <section v-if="events.length == 0 && meetupevents.length == 0">
        <p>Geen evenementen gevonden :(</p>
      </section>
      </section>
        </div>
      </div>



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
        currentInterest: null,
        events: [],
        meetupevents: [],
        allInterests: [],
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
        },
        errors: {
          location: {
            name: false
          },
          startdate: false,
          enddate: false,
          interests: false
        },
        validationStatus: true,
      }
    },
    mounted() {
      this.params = this.searchparams;
      this.eventIsMeetup = this.meetupeventstate;
      this.$store.commit('clearMeetUpEvent', null);
      axios({
        method: "post",
        url: apiurl + "events.json",
        headers: { },
        data: this.searchparams
      })
      .then(response => {
        this.events = response.data.events;
        //this.events.push(response.data.additionalEvents);
      })
      .catch(error => {
        if (error.response) {
          console.log(error.response.status);
          console.log(error.response.headers);
          console.log(error.response.data.message);

          let errors = [];

        } else if (error.request) {
          console.log(error.request);
        } else {
          console.log('Error', error.message);
        }
        console.log(error.config);
      });

      let floatLat = parseFloat(this.searchparams.location.lat);
      let floatLng = parseFloat(this.searchparams.location.lng);

      let formattedStartDate = moment(String(this.searchparams.startdate)).format('YYYY-MM-DDTHH:MM:SS');
      let formattedEndDate = moment(String(this.searchparams.enddate)).format('YYYY-MM-DDTHH:MM:SS');

      this.allInterests = this.params.defaultInterests;
      this.params.userInterests.forEach(interest => {
        this.allInterests.push(interest);
      })
      //interests.push(this.searchparams.defaultInterests);

      for (let i = 0; i < this.allInterests.length; i++) {
        let interest = this.allInterests[i];
        this.$jsonp('https://api.meetup.com/find/upcoming_events?key=766033144c453b4d295465e352538&sign=true&photo-host=public&lon=' + floatLng +
          '&page=20&radius=' + this.searchparams.radiusValue +
          '&lat=' + floatLat +
          '&start_date_range=' + formattedStartDate +
          '&end_date_range=' + formattedEndDate +
          '&fields=*, group_category' +
          '&text=' + interest
        )
        .then(json => {
          for (let j = 0; j < json.data.events.length; j ++) {
            this.meetupevents.push(json.data.events[j]);
          }

        }).catch(err => {
        })
      }


    },
    methods: {
      onSubmit() {
        this.$store.commit('updateSearchValues', this.params);
        this.validateSearchQuery();
        if (this.validationStatus) {
          this.$router.go();
        }
      },
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
      },
      initMap() {
        let input = document.getElementById('pac-input');
        let autocomplete = new google.maps.places.Autocomplete(input);

        autocomplete.addListener('place_changed', () => {
          var place = autocomplete.getPlace();
          if (!place.geometry) {
            // User entered the name of a Place that was not suggested and
            // pressed the Enter key, or the Place Details request failed.
            window.alert("No details available for input: '" + place.name + "'");
            return;
          }
          this.params.location.name = place.formatted_address;
          this.params.location.lat = place.geometry.location.lat();
          this.params.location.lng = place.geometry.location.lng();
        });
      },
      validateSearchQuery() {
        this.validationStatus = true;
        this.errors.location.name = false;
        this.errors.startdate = false;
        this.errors.enddate = false;
        this.errors.interests = false;

        if (!this.params.location.name || !this.params.location.lat || !this.params.location.lng) {
          this.errors.location.name = "Vul een geldige locatie in";
          this.validationStatus = false;
        }
        if (!this.params.startdate) {
          this.errors.startdate = "Vul een startdatum in";
          this.validationStatus = false;
        }
        if (!this.params.enddate) {
          this.errors.enddate = "Vul een enddatum in";
          this.validationStatus = false;
        }
        if (this.params.userInterests.length == 0 && this.params.defaultInterests.length == 0) {
          this.errors.interests = "Interesses mogen niet leeg zijn";
          this.validationStatus = false;
        }
      },
      addInterest() {
        this.params.userInterests.push(this.currentInterest);
        this.currentInterest = null;
      },
      deleteInterest(event, type) {
        if (type == "user") {
          let interestIndex = this.params.userInterests.indexOf(event.target.innerHTML);
          this.params.userInterests.splice(interestIndex, 1);
        } else if (type == "default") {
          let interestIndex = this.params.defaultInterests.indexOf(event.target.innerHTML);
          this.params.defaultInterests.splice(interestIndex, 1);
        }

      }
    }
  };
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style lang="scss">
  .interests_input {
    position: relative;
    .fa-plus {
      position: absolute;
      right: 0;
      top: 0;
      padding: 15px 17px 16px;
      background: #FECA57;
      color: #fff;
      border-top-right-radius: 5px;
      border-bottom-right-radius: 5px;
      cursor: pointer;
    }
  }

  @media (min-width: 42.5rem) {
    .timecolumn {
      border-right: 1px solid rgba(#424242, 0.3);
    }

    .time-inputs {
      .inline-input {
        width: 170px;
      }

      label:first-child {
        padding-left: 0;
      }
    }
  }
</style>