<template>
  <div id="create-event" class="content">
    <div class="container">
      <h1>Nieuwe organisatie toevoegen</h1>
      <form @submit.prevent="onSubmit">
        <label for="name">Naam organisatie</label>
        <input type="text" id="name" name="name" v-model="event.name" placeholder="Naam van je organisatie...">
        <label for="description">Beschrijving</label>
        <textarea id="description" name="description" v-model="event.description" placeholder="Vertel wat meer over je organisatie..."></textarea>
        <button class="btn primary-btn" type="submit">Toevoegen</button>
      </form>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
export default {
  name: "create-organisation",
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
    console.log('Create Organisation Component Mounted');
    this.loggedInUser = JSON.parse(localStorage.getItem("user"));
    this.event.user_id = this.loggedInUser.id
  },
  methods: {
    onSubmit() {
      axios({
        method: 'post',
        url: apiurl + "/api/organisations/add.json",
        data: this.event,
      })
      .then((response) => {
        console.log(response);
          //this.$router.push('/profiel/jouw-events/' + response.data.event.id);
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
  }
}
</script>

<style lang="scss">
</style>