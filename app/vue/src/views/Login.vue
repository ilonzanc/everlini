<template>
  <div id="login" class="content">
	  	<div class="container">
			<h1>Aanmelden</h1>
      <section v-if="fbloginstatus != 'connected'" class="facebook-login">
        <div class="fb-login-button" data-max-rows="1" data-size="large" data-width="100%" data-button-type="continue_with" data-show-faces="false" data-auto-logout-link="false" data-use-continue-as="false"></div>
        <div class="separator"></div>
      </section>
      <form method="POST" action="http://localhost:8765/api/login.json" @submit.prevent="onSubmit">
				<label for="email">Emailadres</label>
				<input type="email" id="email" name="email" placeholder="Jouw emailadres..." required v-model="user.email">
				<label for="pass">Wachtwoord</label>
				<input type="password" id="pass" name="pass" placeholder="********" required v-model="user.password">
				<button type="submit" class="btn primary-btn">Inloggen</button>
				<p>Nog geen account?</p>
				<p><router-link to="/registreren">Maak hier een gratis account aan!</router-link></p>
			</form>
	  	</div>
  </div>
</template>

<script>
import axios from 'axios';
import '../bundle';

export default {
  name: 'login',
  data () {
    return {
      user: {
        email: "",
		    password: ""
      },
      fbloginstatus: ""
    }
  },
  mounted () {
    let loggedInUser = JSON.parse(localStorage.getItem("user"));
    if (loggedInUser) {
      this.$router.push('/profiel');
    }
    console.log('Login Component Mounted');
    this.initializeFbLogin();
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
    },
    initializeFbLogin() {
      let self = this;
      window.fbAsyncInit = () => {
        FB.init({
            appId: '776745922450044',
            autoLogAppEvents: true,
            xfbml: true,
            version: 'v2.11'
        });

        FB.getLoginStatus((response) => {
            if (response.status === 'connected') {
                // the user is logged in and has authenticated your
                // app, and response.authResponse supplies
                // the user's ID, a valid access token, a signed
                // request, and the time the access token
                // and signed request each expire
                console.log('connected');
                this.fbloginstatus = 'connected';
                let uid = response.authResponse.userID;
                let accessToken = response.authResponse.accessToken;
                this.$parent.fbAccesToken = accessToken;
            } else if (response.status === 'not_authorized') {
                // the user is logged in to Facebook,
                // but has not authenticated your app
                this.fbloginstatus = 'not authorized';
                console.log('not_authorized');
            } else {
                // the user isn't logged in to Facebook.
                this.fbloginstatus = 'not logged in';
                console.log('not logged in')
            }


        });
    };
    }
  }
}
</script>

<style lang="scss">
#login {
  p {
    text-align: center;
  }
  .fb-login-button {
    margin-bottom: 1rem;
  }

  .btn {
    margin-bottom: 1rem;
  }

  .separator {
    margin: 2rem 0;
    border-top: 1px solid rgba(#424242, 0.2);
  }
}
</style>