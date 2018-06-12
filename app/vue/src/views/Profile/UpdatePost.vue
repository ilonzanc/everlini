<template>
  <div id="update-post" class="content">
    <div class="container">
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
    const apiurldev = "http://localhost:8765";
    const apiurlprod = "https://ilonaapi.3.web.codedor.online";
      axios({
        method: 'get',
        url: apiurldev + "/api/posts/" + this.$route.params.postid + ".json",
      })
      .then((response) => {
          console.log(response)
          this.post = response.data.post;
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
        url: apiurldev + "/api/posts/" + this.$route.params.postid + "/edit.json",
        data: this.post,
      })
      .then((response) => {
          console.log(response)
          this.$router.push('/profiel/jouw-events/' + this.$route.params.id);
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