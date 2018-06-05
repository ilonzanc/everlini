<template>
  <div id="home" class="content">
    <div class="hero-image"></div>
    <div class="container">

      <h1>Ontdek evenementen bij jou in de buurt die matchen met jouw interesses</h1>
      <form method="POST" action="" @submit.prevent="onSubmit">
        <div class="row">
          <div class="column column-sm-12 column-lg-6">
            <label for="location">Locatie</label>
            <input type="text" id="location" name="location" placeholder="Waar zullen we zoeken?" v-model="params.location">
          </div>
          <div class="column column-sm-12 column-lg-6">
            <label>Binnen <span class="radius-value">{{params.radiusValue}}</span> km</label>
            <vue-slider class="custom-slider" ref="slider" v-model="params.radiusValue" v-bind="slider_options"></vue-slider>
          </div>
        </div>
        <label>Datum</label>
        <div class="row">
          <div class="column column-sm-12 column-lg-6 timecolumn">
            <div class="tags-flexbox">
              <div class="tag">vandaag</div>
              <div class="tag">deze week</div>
              <div class="tag">deze maand</div>
            </div>
          </div>
          <div class="column column-sm-12 column-lg-6">
            <div class="time-inputs">

                <label for="startdate">Van</label>
                <input class="inline-input" type="text" id="startdate" name="startdate" placeholder="dd-mm-jjjj" v-model="params.startdate">


                <label for="enddate">Tot</label>
                <input class="inline-input" type="text" id="enddate" name="enddate" placeholder="dd-mm-jjjj" v-model="params.enddate">

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
            <div class="additional_interests" v-for="row in rows">
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
    </div>
  </div>
</template>

<script>
  import axios from "axios";
  import vueSlider from 'vue-slider-component';
  export default {
    computed: {
      searchparams() {
        return this.$store.getters.getSearch;
      }
    },
    components: {
      vueSlider
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
          location: "",
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
        }
      };
    },
    mounted() {
      this.params = this.searchparams;
    },
    methods: {
      onSubmit() {
        this.$store.commit('updateSearch', this.params);
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