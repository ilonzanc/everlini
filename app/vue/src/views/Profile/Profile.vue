<template>
  <div id="profile" class="content">
    <div class="container">
      <h1 v-if="this.$parent.session.profile">{{ this.$parent.session.profile.firstname }}</h1>
      <h1 v-if="this.$parent.session.organisation">{{ this.$parent.session.organisation.name }}</h1>
      <router-link v-if="this.$parent.session.organisation" to="/profiel/jouw-events" class="btn primary-btn">Jouw events</router-link>
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