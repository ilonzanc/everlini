<template>
  <div id="profile">
    <div class="container">
      <h1>profiel</h1>
      <p> {{ this.$parent.session.profiles[0].firstname }} </p>
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