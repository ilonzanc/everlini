<template>
  <div id="create-event" class="content">
    <div class="container">
      <router-link to="/dashboard" class="breadcrumbs">terug naar dashboard</router-link>
      <h1>Nieuwe organisatie toevoegen</h1>
      <form @submit.prevent="onSubmit">
        <section>
          <article v-bind:key="message.index" v-for="message in errors.flash" class="flash-message error">
            <i class="fa fa-exclamation-triangle"></i>{{message}}
          </article>
        </section>
        <label for="name">Naam organisatie</label>
        <input type="text" id="name" name="name" v-model="organisation.name" placeholder="Naam van je organisatie...">
        <label for="description">Beschrijving</label>
        <textarea id="description" name="description" v-model="organisation.description" placeholder="Vertel wat meer over je organisatie..."></textarea>
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
      organisation: {
        user_id: "",
        name: "",
        description: "",
        username: ""
      },
      errors: {
        flash: []
      }
    }
  },
  mounted() {
    console.log('Create Organisation Component Mounted');

    this.organisation.user_id = this.$parent.session.id;
    this.organisation.username = this.$parent.session.username;
  },
  methods: {
    onSubmit() {
      this.errors.flash = [];
      axios({
        method: 'post',
        url: apiurl + "organisations/add.json",
        data: this.organisation,
      })
      .then((response) => {
        console.log(response);
        if(response.data.error != null) {
          this.errors.flash.push(response.data.error);
        } else {
          this.$router.push('/dashboard/' + response.data.organisation.slug + '/' + response.data.organisation.id );
        }

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