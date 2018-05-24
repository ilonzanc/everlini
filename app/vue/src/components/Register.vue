<template>
  <div id="register">
	  	<div class="container">
			<h1>Registreren</h1>
            <form method="POST" action="http://localhost:8765/api/register.json" @submit.prevent="onSubmit">
                <input type="hidden" id="role_id" name="role_id" required v-model="user.role_id">
                <label for="firstname">Voornaam</label>
				<input type="text" id="firstname" name="firstname" placeholder="Jouw voornaam..." required v-model="user.firstname">
                <label for="lastname">Naam</label>
				<input type="text" id="lastname" name="lastname" placeholder="Jouw naam..." required v-model="user.lastname">
                <label for="dateofbirth">Geboortedatum</label>
				<input type="text" id="dateofbirth" name="dateofbirth" placeholder="Jouw geboortedatum..." required v-model="user.dateofbirth">
				<label for="email">Emailadres</label>
				<input type="email" id="email" name="email" placeholder="Jouw emailadres..." required v-model="user.email">
				<label for="pass">Wachtwoord</label>
				<input type="password" id="pass" name="pass" placeholder="********" required v-model="user.password">
				<button type="submit" class="btn widebtn">Registreren</button>
			</form>
	  	</div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'register',
  data () {
    return {
      user: {
        role_id: 2,
        email: "",
        password: "",
        firstname: "",
        lastname: "",
        dateofbirth: "",
        organisation: 0,
      },
      register_type : ""
    }
  },
  mounted () {
    let loggedInUser = JSON.parse(localStorage.getItem("user"));
    if (loggedInUser) {
      this.$router.push('/profiel');
    }
    console.log('Register Component Mounted');
  },
  methods: {
    onSubmit() {
      var self = this;
      axios({
        method: 'post',
        url: "http://localhost:8765/api/register.json",
        data: self.user,
      })
      .then((response) => {
          self.user = response.data;
          localStorage.setItem("user", JSON.stringify(response.data));
          this.$parent.session = JSON.parse(localStorage.getItem("user"));
          this.$router.push('/profiel');
      })
      .catch((error) => {
          console.log(error);
      });
    },
      onLoginUser: function () {
          console.log('click');
          this.$parent.session = "hey";
      }
  }
}
</script>

<style lang="scss"></style>