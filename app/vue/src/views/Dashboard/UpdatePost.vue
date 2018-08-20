<template>
  <div id="update-post" class="content">
    <div class="container">
      <router-link :to='"/dashboard/" + $route.params.name + "/" + $route.params.id + "/events/" + $route.params.eventid' class="breadcrumb"><i class="fa fa-caret-left"></i> terug naar dashboard</router-link>
      <h1>Blogpost bewerken</h1>
      <form @submit.prevent="onSubmit">
        <label for="title">Titel</label>
        <input type="text" id="title" name="title" required v-model="post.title" placeholder="Titel van je blogpost...">
        <label for="body">Inhoud</label>
        <textarea id="body" name="body" required v-model="post.body" placeholder="Inhoud van je blogpost..."></textarea>
        <div class="form-input"><input type="checkbox" id="published" name="published" v-model="post.published"> Online</div>
        <button class="btn primary-btn" type="submit">Bewerken</button>
      </form>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
export default {
  name: "update-post",
  data () {
    return {
      post: {
        event_id: "",
        title: "",
        body: "",
        published: ""
      }
    }
  },
  mounted() {
    console.log('Update Event Component Mounted');
      axios({
        method: 'get',
        url: apiurl + "posts/" + this.$route.params.postid + ".json",
      })
      .then((response) => {
          this.post = response.data.post;
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
        url: apiurl + "posts/" + this.$route.params.postid + "/edit.json",
        data: this.post,
      })
      .then((response) => {
          this.$router.push('/profiel/jouw-events/' + this.$route.params.id);
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