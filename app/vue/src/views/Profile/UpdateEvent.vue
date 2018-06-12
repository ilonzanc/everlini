<template>
  <div id="update-event" class="content">
    <div class="container">
      <h1>Event bewerken</h1>
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
        <button class="btn primary-btn" type="submit">Bewerken</button>
      </form>
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
      loggedInUser: {}
    }
  },
  mounted() {
    console.log('Update Event Component Mounted');
    const apiurldev = "http://localhost:8765";
    const apiurlprod = "https://ilonaapi.3.web.codedor.online";
    this.loggedInUser = JSON.parse(localStorage.getItem("user"));
    this.event.user_id = this.loggedInUser.id;
      axios({
        method: 'get',
        url: apiurldev + "/api/events/" + this.$route.params.id + ".json",
      })
      .then((response) => {
          console.log(response)
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
          console.log(error);
      });
  },
  methods: {
    onSubmit() {
      const apiurldev = "http://localhost:8765";
      const apiurlprod = "https://ilonaapi.3.web.codedor.online";
      axios({
        method: 'put',
        url: apiurldev + "/api/events/" + this.$route.params.id + "/edit.json",
        data: this.event,
      })
      .then((response) => {
          console.log(response)
          this.$router.push('/profiel/jouw-events');
      })
      .catch((error) => {
          console.log(error);
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
        console.log(this.params);
        this.event.location.name = place.formatted_address;
        this.event.location.lat = place.geometry.location.lat();
        this.event.location.lng = place.geometry.location.lng();
        console.log(place);
      });
    },
  }
}
</script>

<style lang="scss">
</style>