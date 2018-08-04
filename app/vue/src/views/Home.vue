<template>
  <div id="home" class="content">
    <div class="hero-image"></div>
    <div class="container">

      <h1>Ontdek evenementen bij jou in de buurt die matchen met jouw interesses</h1>
      <form @submit.prevent="onSubmit">
        <div class="row">
          <div class="column column-sm-12 column-lg-6">
            <label for="location">Locatie</label>
            <input id="pac-input" type="text" name="location" placeholder="Waar zullen we zoeken?" v-model="params.location.name" @keyup="initMap">
            <span v-if="errors.location.name" class="form-error"><i class="fa fa-exclamation-triangle"></i>{{errors.location.name}}</span>
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
                <span v-if="errors.startdate" class="form-error"><i class="fa fa-exclamation-triangle"></i>{{errors.startdate}}</span>
                <label for="enddate">Tot</label>
                <input class="inline-input" type="date" id="enddate" name="enddate" placeholder="dd-mm-jjjj" v-model="params.enddate">
                <span v-if="errors.enddate" class="form-error"><i class="fa fa-exclamation-triangle"></i>{{errors.enddate}}</span>
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
            <span v-if="errors.interests" class="form-error"><i class="fa fa-exclamation-triangle"></i>{{errors.interests}}</span>
          </div>
          <div class="column column-sm-12 column-lg-6">
            <button type="submit" class="btn primary-btn">Zoeken</button>
          </div>
        </div>
      </form>
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
        test: {},
        params: {
          location: {
            name: "",
            lat: "",
            lng: ""
          },
          startdate: "",
          enddate: "",
          interests: [],
          radiusValue: 5,
        },
        slider_options: {
          min: 5,
          max: 30,
          interval: 5,
          piecewise: true,
          piecewiseLabel: true,
          tooltip: false,
          data: [
            "5",
            "10",
            "15",
            "20",
            "25",
            "30",
          ],
          dotSize: 25,
        },
        dateClass: "dateTabs",
        errors: {
          location: {
            name: false
          },
          startdate: false,
          enddate: false,
          interests: false
        },
        validationStatus: true
      };
    },
    mounted() {
      this.params = this.searchparams;
    },
    methods: {
      onSubmit() {
        for (let k = 0; k < this.params.interests.length; k++) {
          if (this.params.interests[k].length < 3 ) {
            this.params.interests.splice(k);
          }
        }
        this.$store.commit('updateSearchValues', this.params);
        this.validateSearchQuery();
        if (this.validationStatus) {
          axios({
            method: "post",
            url: apiurl + "/api/events.json",
            headers: { },
            data: this.searchparams
          })
          .then(response => {
            this.events = response.data.events;
            this.$router.push('/evenementen');
          })
          .catch(error => {
              this.errors = error.response.data;
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
              for (let j = 0; j < json.data.events.length; j ++) {
                this.meetupevents.push(json.data.events[j]);
              }

            }).catch(err => {
            })
          }
        }
      },
      addRow() {
        this.rows.push({value: this.params.interests[this.currentInputIndex], index: this.currentInputIndex});

        this.currentInputIndex++;
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
        if (this.params.interests.length == 0) {
          this.errors.interests = "Interesses mogen niet leeg zijn";
          this.validationStatus = false;
        }
      },
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