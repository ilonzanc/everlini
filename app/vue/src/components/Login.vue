<template>
  <div id="login">
	  	<div class="container">
			<h1>Aanmelden</h1>
            <form method="POST" action="http://localhost:8765/api/login.json" @submit.prevent="onSubmit">
				<label for="name">Gebruikersnaam</label>
				<input type="text" id="name" name="name" placeholder="Jouw gebruikersnaam..." required v-model="user.name">
				<label for="pass">Wachtwoord</label>
				<input type="password" id="pass" name="pass" placeholder="********" required v-model="user.pass">
				<button type="submit" class="btn widebtn">Inloggen</button>
				<p>Nog geen account?</p>
				<p><router-link to="/registreren">Maak hier een gratis account aan!</router-link></p>
			</form>
	  	</div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'login',
  data () {
    return {
      user: {
        name: "",
		    pass: ""
      },
    }
  },
  mounted () {
    console.log('Login Component Mounted');
    //console.log(csrf_field());
  },
  methods: {
    onSubmit() {
      var self = this;
      axios({
        method: 'post',
        url: "http://localhost:8765/api/login.json",
        headers: {
            //'Accept': 'application/hal+json',
            //'Content-Type': 'application/hal+json',
            "X-CSRF-Token": "T48cuYVRu1CRiXoV7-O35YUNV5A_j7Ro9jT5z5St0OA",
            //'X-Requested-With': 'XMLHttpRequest'
        },
        data: self.user
      })
      .then((response) => {
          console.log(response.data);
          localStorage.setItem('loggedInUser', JSON.stringify(response.data));
          localStorage.setItem('password', self.user.pass);
          location.href = '/profiel/' + response.data.current_user.uid;
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