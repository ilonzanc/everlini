<template>
  <div id="create-event" class="content">
    <div class="container">
      <h1>Nieuw event toevoegen</h1>
      <form method="POST" action="http://localhost:8765/api/events/add.json" @submit.prevent="onSubmit">
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
        <label for="street">Straat</label>
        <input type="text" id="street" name="street" required v-model="event.location.street" placeholder="Straat...">
        <label for="housenr">Huisnr.</label>
        <input type="text" id="housenr" name="housenr" required v-model="event.location.housenr" placeholder="Huisnr....">
        <label for="city">Stad</label>
        <input type="text" id="city" name="city" required v-model="event.location.city" placeholder="Stad...">
        <label for="postal_code">Postcode</label>
        <input type="text" id="postal_code" name="postal_code" required v-model="event.location.postal_code" placeholder="Postcode...">
        <label for="country">Land</label>
        <input type="text" id="country" name="country" required v-model="event.location.country" placeholder="Land...">
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
        user_id: this.$parent.session.id,
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
          street: "",
          housenr: "",
          city: "",
          postal_code: "",
          country: ""
        }
      }
    }
  },
  mounted() {
    console.log('Create Event Component Mounted');
  },
  methods: {
    onSubmit() {
      var self = this;
      axios({
        method: 'post',
        url: "http://localhost:8765/api/events/add.json",
        data: self.event,
      })
      .then((response) => {
          console.log(response)
          //this.$router.push('/profiel/jouw-events');
      })
      .catch((error) => {
          console.log(error);
      });
    }
  }
}
</script>

<style lang="scss">
</style>