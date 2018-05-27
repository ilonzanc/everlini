<template>
  <div id="login">
	  	<div class="container">
			<h1>Aanmelden</h1>
            <form method="POST" action="http://localhost:8765/api/login.json" @submit.prevent="onSubmit">
				<label for="email">Emailadres</label>
				<input type="email" id="email" name="email" placeholder="Jouw emailadres..." required v-model="user.email">
				<label for="pass">Wachtwoord</label>
				<input type="password" id="pass" name="pass" placeholder="********" required v-model="user.password">
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
        email: "",
		    password: ""
      },
    }
  },
  mounted () {
    let loggedInUser = JSON.parse(localStorage.getItem("user"));
    if (loggedInUser) {
      this.$router.push('/profiel');
    }
    console.log('Login Component Mounted');
  },
  methods: {
    onSubmit() {
      var self = this;
      axios({
        method: 'post',
        url: "http://localhost:8765/api/login.json",
        data: self.user
      })
      .then((response) => {
          self.user = response.data;
          localStorage.setItem("user", JSON.stringify(response.data));
          this.$parent.session = JSON.parse(localStorage.getItem("user"));
          this.$router.push('/profiel');
      })
      .catch((error, request) => {
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

<style lang="scss">
#login {
  p {
    text-align: center;
    a {
      font-weight: 700;
    }
  }
}
</style>