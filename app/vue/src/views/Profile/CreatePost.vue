<template>
  <div id="create-post" class="content">
    <div class="container">
      <h1>Nieuwe blogpost toevoegen</h1>
      <form method="POST" action="http://localhost:8765/api/posts/add.json" @submit.prevent="onSubmit">
        <label for="title">Titel</label>
        <input type="text" id="title" name="title" required v-model="post.title" placeholder="Titel van je blogpost...">
        <label for="body">Inhoud</label>
        <textarea id="body" name="body" required v-model="post.body" placeholder="Inhoud van je blogpost..."></textarea>
        <div class="form-input"><input type="checkbox" id="published" name="published" v-model="post.published"> Online</div>
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
        event_id: this.$route.params.id,
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
      var self = this;
      axios({
        method: 'post',
        url: "http://localhost:8765/api/posts/add.json",
        data: self.post,
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