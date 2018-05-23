<template>
  <div id="profile">
    <div class="container">
      <h1>profiel</h1>
      <p> {{ this.$parent.session.username }} </p>
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