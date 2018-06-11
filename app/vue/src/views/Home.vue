<template>
  <div id="home" class="content">
    <div class="hero-image"></div>
    <div class="container">

      <h1>Ontdek evenementen bij jou in de buurt die matchen met jouw interesses</h1>
      <form method="POST" action="" @submit.prevent="onSubmit">
        <div class="row">
          <div class="column column-sm-12 column-lg-6">
            <label for="location">Locatie</label>
            <input id="pac-input" type="text" name="location" placeholder="Waar zullen we zoeken?" v-model="params.location.name" @keyup="initMap">
          </div>
          <div class="column column-sm-12 column-lg-6">
            <label>Binnen <span class="radius-value">{{params.radiusValue}}</span> km</label>
            <vue-slider class="custom-slider" ref="slider" v-model="params.radiusValue" v-bind="slider_options"></vue-slider>
          </div>
        </div>
        <label>Datum</label>
        <div class="row">
          <div class="column column-sm-12 column-lg-6 timecolumn">
            <tabs :tabsClassName="dateClass">
              <tab name="vandaag" ></tab>
              <tab name="deze week"></tab>
              <tab name="deze maand"></tab>
            </tabs>
          </div>
          <div class="column column-sm-12 column-lg-6">
            <div class="time-inputs">
                <label for="startdate">Van</label>
                <input class="inline-input" type="date" :min="getDateOfToday()" id="startdate" name="startdate" placeholder="dd-mm-jjjj" v-model="params.startdate">
                <label for="enddate">Tot</label>
                <input class="inline-input" type="date" id="enddate" name="enddate" placeholder="dd-mm-jjjj" v-model="params.enddate">

            </div>
          </div>
        </div>
        <label>Interesses</label>
        <div class="row"></div>
        <div class="row">
          <div class="column column-sm-12 column-lg-6">
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
          </div>
          <div class="column column-sm-12 column-lg-6">
            <button type="submit" class="btn primary-btn">Zoeken</button>
          </div>
        </div>
      </form>
<!--       <div class="column column-sm-12 column-lg-6">
        <button @click.prevent="getFBEventsByLocation" type="submit" class="btn primary-btn">Zoeken</button>
        <ul class="events_list"></ul>
      </div> -->
      <div id="map"></div>
    </div>
  </div>
</template>

<script>
  import axios from "axios";
  import vueSlider from 'vue-slider-component';
  import Tabs from '../Components/Tabs.vue';
  import Tab from '../Components/Tab.vue';
  import moment from 'moment';

  export default {
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
    name: "home",
    props: {
      currentPage: "home"
    },
    data() {
      return {
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
        dateClass: "dateTabs"
      };
    },
    mounted() {
      this.params = this.searchparams;
    },
    methods: {
      onSubmit() {
        this.$store.commit('updateSearchValues', this.params);
        this.$router.push('/evenementen');
      },
      addRow: function() {
        this.rows.push({value: this.params.interests[this.currentInputIndex], index: this.currentInputIndex});

        this.currentInputIndex++;
      },
      getFBEventsByLocation() {
        let eventslist = document.querySelector('.events_list')
        while (eventslist.firstChild) {
          eventslist.removeChild(eventslist.firstChild);
        }

        let EventSearch = require("facebook-events-by-location-core");

        let es = new EventSearch();

        es.search({
          "lat": 51.0535,
          "lng": 3.7304,
          "accessToken": this.$parent.fbAccesToken,
          "distance": 2500
        })
        .then(function (response) {
          let events = response.events;

          for (i = 0; i < events.length; i++) {
            console.log("Searching....");
            const event = document.createElement('li');
            const event_name = document.createTextNode(events[i].name);
            event.appendChild(event_name);
            eventslist.appendChild(event);
          }
        })
        .catch(function (error) {
          console.error(JSON.stringify(error));
        });
      },
      getDateOfToday() {
        let today = new Date();
        today = moment(String(today)).utcOffset(0).format('YYYY-MM-DD');
        return today;
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
          console.log(this.params);
          this.params.location.name = place.formatted_address;
          this.params.location.lat = place.geometry.location.lat();
          this.params.location.lng = place.geometry.location.lng();
          console.log(place);
        });
      }
    }
  };
</script>

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