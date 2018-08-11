<template>
  <div id="create-post" class="content">
    <div class="container">
      <router-link to="/dashboard" class="breadcrumb">terug naar dashboard</router-link>
      <h1>Nieuwe blogpost toevoegen</h1>
      <form @submit.prevent="onSubmit">
        <label for="title">Titel</label>
        <input type="text" id="title" name="title" required v-model="post.title" placeholder="Titel van je blogpost...">
        <label for="body">Inhoud</label>
        <textarea id="body" name="body" required v-model="post.body" placeholder="Inhoud van je blogpost..."></textarea>
        <div class="form-input"><input type="checkbox" id="published" name="published" v-model="post.published"> <label for="published">Online</label></div>
        <button class="btn primary-btn" type="submit">Toevoegen</button>
      </form>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
export default {
  name: "create-post",
  data () {
    return {
      post: {
        event_id: this.$route.params.eventid,
        title: "",
        body: "",
        published: ""
      }
    }
  },
  mounted() {
    console.log('Create Post Component Mounted');
  },
  methods: {
    onSubmit() {
      axios({
        method: 'post',
        url: apiurl + "posts/add.json",
        data: this.post,
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
    }
  }
}
</script>

<style lang="scss">
</style>