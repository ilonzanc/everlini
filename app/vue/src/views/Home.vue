<template>
  <div id="home" class="content">
    <div class="hero-image"></div>
    <div class="container">
      <h1>Ontdek evenementen bij jou in de buurt die matchen met jouw interesses</h1>
      <form @submit.prevent="onSubmit">
        <i class="fa fa-refresh refresh-params-button" @click.prevent="refreshParams()" aria-hidden="true"></i>
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
                <label for="enddate">Tot</label>
                <input class="inline-input" type="date" id="enddate" name="enddate" placeholder="dd-mm-jjjj" v-model="params.enddate">
            </div>
            <div class="time-inputs-errors">
              <span v-if="errors.startdate" class="form-error"><i class="fa fa-exclamation-triangle"></i>{{errors.startdate}}</span>
              <span v-if="errors.enddate" class="form-error"><i class="fa fa-exclamation-triangle"></i>{{errors.enddate}}</span>
            </div>
          </div>
        </div>
        <label>Interesses</label>
        <section class="default-interest-section">
          <button v-if="defaultInterests.length > 0" v-for="(interest, key) in defaultInterests" v-bind:key="key" @click.prevent="toggleInterest($event, key)" v-bind:class="['btn interest-btn', {'selected': interest.selected}]">{{ interest.name }}</button>
        </section>
        <div class="row">
          <div class="column column-sm-12 column-lg-12">
            <ul v-if="params.userInterests.length > 0">
              <li v-for="interest in params.userInterests" v-bind:key="interest.index" class="tag removable-tag" @click.prevent="deleteInterest($event)">{{ interest }}</li>
            </ul>
          </div>
        </div>
        <div class="row">
          <div class="column column-sm-12 column-lg-6">
            <div class="interests_input">
              <input type="text" name="0" id="interest_input" v-model="currentInterest" placeholder="Eigen interesse toevoegen...">
              <i class="fa fa-plus" @click.prevent="addInterest"></i>
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
        currentInterest: null,
        params: {
          location: {
            name: "",
            lat: "",
            lng: ""
          },
          startdate: "",
          enddate: "",
          userInterests: [],
          defaultInterests: [],
          radiusValue: 5,
        },
        defaultInterests: [ ],
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
        validationStatus: true,
        interests: []
      };
    },
    mounted() {
      this.params = this.searchparams;
      this.getInterests();
    },
    methods: {
      onSubmit() {
        // TODO save selected default interests and filled in user interests in 1 interests variable
        for (let k = 0; k < this.params.defaultInterests.length; k++) {
          if (this.params.defaultInterests[k].length < 3 ) {
            this.params.defaultInterests.splice(k);
          }
        }
        this.$store.commit('updateSearchValues', this.params);
        this.validateSearchQuery();
        if (this.validationStatus) {
          this.$router.push('/evenementen');
        }
      },
      addRow() {
        this.rows.push({value: this.params.userInterests[this.currentInputIndex], index: this.currentInputIndex});

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
        if (this.params.userInterests.length == 0 && this.params.defaultInterests.length == 0) {
          this.errors.interests = "Interesses mogen niet leeg zijn";
          this.validationStatus = false;
        }
      },
      getInterests() {
        axios({
          method: 'get',
          url: apiurl + "interests.json"
        })
        .then(response => {
          this.interests = response.data.interests;
          response.data.interests.forEach(interest => {
            let defaultInterest = {};
            defaultInterest.name = interest.name;
            defaultInterest.selected = false;
            if (this.defaultInterests.length < 8) {
              this.defaultInterests.push(defaultInterest);
            }
          });
        })
        .catch(error => {

        })
      },
      toggleInterest(event, index) {
        if (event) {
          if (this.defaultInterests[index].selected == true) {
            this.defaultInterests[index].selected = false;
            this.deleteInterests(this.defaultInterests[index].name);
          } else {
            this.defaultInterests[index].selected = true;
            this.addInterests(this.defaultInterests[index].name);
          }
        }
      },
      addInterests(interestname){
        this.interests.forEach(interest => {
          if(interest.name == interestname) {
            this.params.defaultInterests.push(interest.name);
            this.addChildInterests(interest);
          }
        });
      },
      addChildInterests(parent) {
        if(parent.children.length > 0) {
          parent.children.forEach(child => {
            this.params.defaultInterests.push(child.name);
            this.addChildInterests(child);
          });
        }
      },
      deleteInterests(interestname){
        this.interests.forEach(interest => {
          if(interest.name == interestname) {
            let interestIndex = this.defaultInterests.indexOf(interest.name);
            this.params.defaultInterests.splice(interestIndex, 1);
            this.deleteChildInterests(interest);
          }
        });
      },
      deleteChildInterests(parent) {
        if(parent.children.length > 0) {
          parent.children.forEach(child => {
            let childIndex = this.interests.indexOf(child.name);
            this.params.defaultInterests.splice(childIndex, 1);
            this.deleteChildInterests(child);
          });
        }
      },
      refreshParams() {
        this.$store.commit('clearSearchValues');
      },
      addInterest() {
        this.params.userInterests.push(this.currentInterest);
        this.currentInterest = null;
      },
      deleteInterest(event) {
        let interestIndex = this.params.userInterests.indexOf(event.target.innerHTML);
        this.params.userInterests.splice(interestIndex, 1);
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