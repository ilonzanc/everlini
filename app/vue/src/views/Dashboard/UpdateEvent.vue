<template>
  <div id="update-event" class="content">
    <div class="container">
      <router-link :to='"/dashboard/" + $route.params.name + "/" + $route.params.id + "/events/" + $route.params.eventid' class="breadcrumbs">terug naar dashboard</router-link>
      <h1>Event bewerken</h1>
      <div class="row">
        <div class="column column-sm-12 column-lg-6">
          <form @submit.prevent="onSubmit">
            <label for="name">Naam</label>
            <input type="text" id="name" name="name" required v-model="event.name" placeholder="Naam van je event...">
            <label for="description">Beschrijving</label>
            <textarea id="description" name="description" required v-model="event.description" placeholder="Vertel wat meer over je event..."></textarea>
            <h2>Tijdstip  </h2>
            <label for="startdate">Start event</label>
            <input type="text" id="startdate" name="startdate" required placeholder="dd-mm-jjjj" v-model="event.startdate.date">
            <input type="text" id="startdate" name="startdate" required placeholder="00:00" v-model="event.startdate.time">
            <label for="enddate">Eind event</label>
            <input type="text" id="enddate" name="enddate" required placeholder="dd-mm-jjjj" v-model="event.enddate.date">
            <input type="text" id="enddate" name="enddate" required placeholder="00:00" v-model="event.enddate.time">
            <h2>Locatie</h2>
            <input id="pac-input" type="text" name="location" placeholder="Locatie van je evenement..." v-model="event.location.name" @keyup="initMap">
            <label class="required" for="interests">Tags</label>
            <ul v-if="event.interests.length > 0">
              <li v-for="interest in event.interests" v-bind:key="interest.index" class="tag removable-tag" @click.prevent="deleteInterest($event)">{{ interest.name }}</li>
            </ul>
            <div class="interests_input">
              <input type="text" name="0" id="interest_input" v-model="currentInterest" placeholder="Eigen interesse toevoegen...">
              <i class="fa fa-plus" @click.prevent="addInterest"></i>
            </div>
            <h2>Header</h2>
            <div class="upload-btn-wrapper">
              <button class="btn upload-btn ghost">Upload a file</button>
              <input id="header_image" ref="file" type="file" @change="uploadImage()">
            </div>
            <button class="btn primary-btn" type="submit">Bewerken</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import moment from 'moment';
export default {
  name: "update-event",
  data () {
    return {
      currentInterest: null,
      event: {
        user_id: "",
        name: "",
        description: "",
        startdate: {
          time: "",
          date: ""
        },
        enddate: {
          time: "",
          date: ""
        },
        location: {
          name: "",
          lat: "",
          lng: "",
        }
      },
    }
  },
  mounted() {
    console.log('Update Event Component Mounted');
      axios({
        method: 'get',
        url: apiurl + "events/" + this.$route.params.eventid + ".json",
      })
      .then((response) => {
          this.event = response.data.event[0];
          moment().utcOffset(-2);
          let fullstartdate = response.data.event[0].startdate;
          let startdate = moment(String(fullstartdate)).utcOffset(0).format('DD-MM-YYYY');
          let starttime = moment(String(fullstartdate)).utcOffset(0).format('HH:mm');
          this.event.startdate = JSON.parse('{ "time":"' + starttime + '", "date":"' + startdate + '"}');

          let fullenddate = response.data.event[0].enddate;
          let enddate = moment(String(fullenddate)).utcOffset(0).format('DD-MM-YYYY');
          let endtime = moment(String(fullenddate)).utcOffset(0).format('HH:mm');
          this.event.enddate = JSON.parse('{ "time":"' + endtime + '", "date":"' + enddate + '"}');
          this.event = Object.assign({}, this.event, { location: null });
          this.event.location = Object.assign({}, this.event.location, {  name: response.data.event[0].address, lat: response.data.event[0].lat, lng: response.data.event[0].lng  });
      })
      .catch((error) => {
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
  },
  methods: {
    onSubmit() {
      axios({
        method: 'put',
        url: apiurl + "events/" + this.$route.params.eventid + "/edit.json",
        data: this.event,
      })
      .then((response) => {
          this.$router.push('/dashboard/' + this.$route.params.name + '/' + this.$route.params.id  + '/events/' + response.data.event.id);
      })
      .catch((error) => {
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
        this.event.location.name = place.formatted_address;
        this.event.location.lat = place.geometry.location.lat();
        this.event.location.lng = place.geometry.location.lng();
      });
    },
    addInterest() {
        this.profile.user.interests.push(this.currentInterest);
        this.currentInterest = null;
      },
      deleteInterest(event) {
        let interestIndex = this.profile.user.interests.indexOf(event.target.innerHTML);
        this.profile.user.interests.splice(interestIndex, 1);
      }
  }
}
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
</style>