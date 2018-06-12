<template>
  <div id="login" class="content">
	  	<div class="container">
        <div class="row">
          <div class="column column-sm-12 column-lg-6">
            <h1>Aanmelden</h1>
            <form method="POST" action="http://localhost:8765/api/login.json" @submit.prevent="onSubmit">
              <section>
                <article v-bind:key="message.index" v-for="message in errors.flash" class="flash-message error">
                  <i class="fa fa-exclamation-triangle"></i>{{message}}
                </article>
              </section>
              <label for="email">Emailadres</label>
              <input type="email" id="email" name="email" placeholder="Jouw emailadres..." v-model="user.email">
              <span v-if="errors.user.email" class="form-error"><i class="fa fa-exclamation-triangle"></i>{{errors.user.email}}</span>
              <label for="pass">Wachtwoord</label>
              <input type="password" id="pass" name="pass" placeholder="********" v-model="user.password">
              <span v-if="errors.user.password" class="form-error"><i class="fa fa-exclamation-triangle"></i>{{errors.user.password}}</span>
              <button type="submit" class="btn primary-btn">Inloggen</button>
              <p>Nog geen account?</p>
              <p><router-link to="/registreren">Maak hier een gratis account aan!</router-link></p>
            </form>
          </div>
        </div>
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
      validationStatus: true,
      errors: {
        user: {
          email: "",
          password: ""
        },
        flash: {

        }
      }
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
      this.validateLogin();
      this.errors.flash = false
      if (this.validationStatus) {
        axios({
          method: 'post',
          url: "http://localhost:8765/api/login.json",
          data: this.user
        })
        .then((response) => {
            console.log(response);
            if (response.data.errors) {
              this.errors.flash = response.data.errors;
              this.validationStatus = false;
            } else {
              this.user = response.data;
              localStorage.setItem("user", JSON.stringify(response.data));
              this.$parent.session = JSON.parse(localStorage.getItem("user"));
              this.$router.push('/profiel');
            }
        })
        .catch((error) => {
            console.log(error);
        });
      }
    },
    onLoginUser: function () {
        console.log('click');
        this.$parent.session = "hey";
    },
    validateLogin() {
      this.validationStatus = true;
      this.errors.user.email = false;
      this.errors.user.password = false;

      if (this.user.email.indexOf('@') <= 0 || this.user.email.lastIndexOf('.') < this.user.email.indexOf('@') || this.user.email.lastIndexOf('.') == this.user.email.length  ) {
        this.errors.user.email = "Vul een geldig emailadres in";
        this.validationStatus = false;
      }
      if (!this.user.password) {
        this.errors.user.password = "Vul een wachtwoord in";
        this.validationStatus = false;
      }
    }
  }
}
</script>

<style lang="scss" scoped>
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

@media (min-width: 42.5rem) {
  #login .column {
    margin: auto;
    float: none;
  }
}
</style>