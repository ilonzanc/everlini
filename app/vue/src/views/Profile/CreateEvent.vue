<template>
  <div id="create-event" class="content">
    <div class="container">
      <h1>Nieuw event toevoegen</h1>
      <form @submit.prevent="onSubmit">
        <label for="name">Naam</label>
        <input type="text" id="name" name="name" required v-model="event.name" placeholder="Naam van je event...">
        <label for="description">Beschrijving</label>
        <textarea id="description" name="description" required v-model="event.description" placeholder="Vertel wat meer over je event..."></textarea>
        <h2>Tijdstip  </h2>
        <label for="startdate">Start event</label>
        <input type="date" id="startdate" name="startdate" required placeholder="dd-mm-jjjj" v-model="event.startdate.date">
        <input type="text" id="startdate" name="startdate" required placeholder="00:00" v-model="event.startdate.time">
        <label for="enddate">Eind event</label>
        <input type="date" id="enddate" name="enddate" required placeholder="dd-mm-jjjj" v-model="event.enddate.date">
        <input type="text" id="enddate" name="enddate" required placeholder="00:00" v-model="event.enddate.time">
        <h2>Locatie</h2>
        <input id="pac-input" type="text" name="location" placeholder="Locatie van je evenement..." v-model="event.location.name" @keyup="initMap">
        <button class="btn primary-btn" type="submit">Toevoegen</button>
      </form>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
export default {
  name: "create-event",
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
    console.log('Create Event Component Mounted');
    this.loggedInUser = JSON.parse(localStorage.getItem("user"));
    this.event.user_id = this.loggedInUser.id
  },
  methods: {
    onSubmit() {
      axios({
        method: 'post',
        url: apiurl + "/api/events/add.json",
        data: this.event,
      })
      .then((response) => {
          this.$router.push('/profiel/jouw-events/' + response.data.event.id);
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
  }
}
</script>

<style lang="scss">
</style>