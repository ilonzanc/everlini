<template>
  <div id="login">
	  	<div class="container">
			<h1>Aanmelden</h1>
            <form method="POST" action="" @submit.prevent="onSubmit">
				<label for="name">Gebruikersnaam</label>
				<input type="text" id="name" name="name" placeholder="Jouw emailadres..." required v-model="user.email">
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
        email: "newuser@mail.be",
		    password: "secret"
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
      console.log("user: ");
      console.log(this.user);
      axios({
        method: 'post',
        url: "",
        headers: {
          'X-CSRF-Token': '2c40cb58d445214db32376ff083425edfe99ec502e238dcf45c0c78f8e12201ca360fcabfa5252a4e676fb281294ce4914d5665976960e3c9aef1cde977aca5d'
        },
        data: self.user
      })
      .then((response) => {
          console.log(response.data);
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

<style lang="scss"></style>