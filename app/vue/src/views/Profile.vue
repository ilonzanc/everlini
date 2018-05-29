<template>
  <div id="profile" class="content">
    <div class="container">
      <h1>{{ this.$parent.session.profile.firstname }}</h1>
      <router-link to="/profiel/jouw-events" class="btn primary-btn">Jouw events</router-link>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'profile',
    date() {
      return {

      }
    },
    mounted () {
      console.log("Profile Vue Component mounted");
    }
  }
</script>