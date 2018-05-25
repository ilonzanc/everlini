<template>
  <div id="profile-events">
    <div class="container">
      <h1>Jouw events</h1>
      <router-link to="/profiel/nieuw-event" class="btn primary-btn"><i class="fa fa-plus"></i> Nieuw event</router-link>
      <p>Je hebt nog geen evenementen aangemaakt :(</p>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'profile-events',
    date() {
      return {

      }
    },
    mounted () {
      console.log("Profile Event Vue Component mounted");
    }
  }
</script>