<template>
  <div id="update-event" class="content">
    <div class="container">
      <h1>Event bewerken</h1>
      <form method="PUT" :action="'http://localhost:8765/api/events/' + this.$route.params.id + '/edit.json'" @submit.prevent="onSubmit">
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
        <input type="text" id="street" name="street" required v-model="event.street" placeholder="Straat...">
        <label for="housenr">Huisnr.</label>
        <input type="text" id="housenr" name="housenr" required v-model="event.housenr" placeholder="Huisnr....">
        <label for="city">Stad</label>
        <input type="text" id="city" name="city" required v-model="event.city" placeholder="Stad...">
        <label for="postal_code">Postcode</label>
        <input type="text" id="postal_code" name="postal_code" required v-model="event.postal_code" placeholder="Postcode...">
        <label for="country">Land</label>
        <input type="text" id="country" name="country" required v-model="event.country" placeholder="Land...">
        <button class="btn primary-btn" type="submit">Toevoegen</button>
      </form>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
export default {
  name: "update-event",
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
        street: "",
        housenr: "",
        city: "",
        postal_code: "",
        country: ""
      }
    }
  },
  mounted() {
    console.log('Update Event Component Mounted');
    var self = this;
      axios({
        method: 'get',
        url: "http://localhost:8765/api/events/" + this.$route.params.id + ".json",
      })
      .then((response) => {
          console.log(response)
          self.event = response.data.event;
          moment().utcOffset(-2);
          let fullstartdate = response.data.event.startdate;
          let startdate = moment(String(fullstartdate)).utcOffset(0).format('DD-MM-YYYY');
          let starttime = moment(String(fullstartdate)).utcOffset(0).format('HH:mm');
          self.event.startdate = JSON.parse('{ "time":"' + starttime + '", "date":"' + startdate + '"}');

          let fullenddate = response.data.event.enddate;
          let enddate = moment(String(fullenddate)).utcOffset(0).format('DD-MM-YYYY');
          let endtime = moment(String(fullenddate)).utcOffset(0).format('HH:mm');
          self.event.enddate = JSON.parse('{ "time":"' + endtime + '", "date":"' + enddate + '"}');
      })
      .catch((error) => {
          console.log(error);
      });
  },
  methods: {
    onSubmit() {
      var self = this;
      axios({
        method: 'put',
        url: "http://localhost:8765/api/events/" + this.$route.params.id + "/edit.json",
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