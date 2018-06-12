<template>
  <div id="register" class="content">
	  	<div class="container">
        <h1>Registreren</h1>
        <tabs>
          <tab name="als gebruiker" :selected="true">
            <h2>Als gebruiker</h2>
            <form @submit.prevent="onSubmit(2)">
              <section>
                <article v-bind:key="message.index" v-for="message in errors.flash" class="flash-message error">
                  <i class="fa fa-exclamation-triangle"></i>{{message}}
                </article>
              </section>
              <label for="firstname">Voornaam</label>
              <input type="text" id="profile_firstname" name="firstname" placeholder="Jouw voornaam..." v-model="user.firstname">
              <span v-if="errors.user.firstname" class="form-error"><i class="fa fa-exclamation-triangle"></i>{{errors.user.firstname}}</span>
              <label for="lastname">Naam</label>
              <input type="text" id="profile_lastname" name="lastname" placeholder="Jouw naam..." v-model="user.lastname">
              <span v-if="errors.user.lastname" class="form-error"><i class="fa fa-exclamation-triangle"></i>{{errors.user.lastname}}</span>
              <label for="dateofbirth">Geboortedatum</label>
              <input type="date" id="profile_dateofbirth" name="dateofbirth" placeholder="Jouw geboortedatum..." v-model="user.dateofbirth">
              <span v-if="errors.user.dateofbirth" class="form-error"><i class="fa fa-exclamation-triangle"></i>{{errors.user.dateofbirth}}</span>
              <label for="email">Emailadres</label>
              <input type="email" id="profile_email" name="email" placeholder="Jouw emailadres..." v-model="user.email">
              <span v-if="errors.user.email" class="form-error"><i class="fa fa-exclamation-triangle"></i>{{errors.user.email}}</span>
              <label for="pass">Wachtwoord</label>
              <input type="password" id="profile_pass" name="pass" placeholder="********" v-model="user.password">
              <span v-if="errors.user.password" class="form-error"><i class="fa fa-exclamation-triangle"></i>{{errors.user.password}}</span>
              <button type="submit" class="btn primary-btn widebtn">Registreren</button>
            </form>
          </tab>
          <tab name="als organisatie">
            <h2>Als organisatie</h2>
            <form @submit.prevent="onSubmit(3)">
              <section>
                <article v-bind:key="message.index" v-for="message in errors.flash" class="flash-message error">
                  <i class="fa fa-exclamation-triangle"></i>{{message}}
                </article>
              </section>
              <label for="name">Naam organisatie</label>
              <input type="text" id="organisation_name" name="name" placeholder="Naam organisatie..." v-model="user.name">
              <span v-if="errors.user.name" class="form-error"><i class="fa fa-exclamation-triangle"></i>{{errors.user.name}}</span>
              <label for="description">Beschrijving</label>
              <textarea id="organisation_description" name="description" placeholder="Beschrijving..." v-model="user.description"></textarea>
              <label for="email">Emailadres</label>
              <input type="email" id="organisation_email" name="email" placeholder="Emailadres..." v-model="user.email">
              <span v-if="errors.user.email" class="form-error"><i class="fa fa-exclamation-triangle"></i>{{errors.user.email}}</span>
              <label for="pass">Wachtwoord</label>
              <input type="password" id="organisation_pass" name="pass" placeholder="********" v-model="user.password">
              <span v-if="errors.user.password" class="form-error"><i class="fa fa-exclamation-triangle"></i>{{errors.user.password}}</span>
              <button type="submit" class="btn primary-btn widebtn">Registreren</button>
            </form>
          </tab>
        </tabs>
	  	</div>
  </div>
</template>

<script>
import axios from 'axios';
import Tabs from '../Components/Tabs.vue';
import Tab from '../Components/Tab.vue';

export default {
  name: 'register',
  components:{
    'tabs': Tabs,
    'tab': Tab
  },
  data () {
    return {
      user: {
        role_id: "",
        email: "",
        password: "",
        firstname: "",
        lastname: "",
        dateofbirth: "",
        name: "",
        description: "",
      },
      validationStatus: true,
      errors: {
        user: {
          email: "",
          password: "",
          firstname: "",
          lastname: "",
          dateofbirth: "",
          name: "",
        },
        flash: {

        }
      }
    }
  },
  watch: {
  },
  mounted () {
    let loggedInUser = JSON.parse(localStorage.getItem("user"));
    if (loggedInUser) {
      this.$router.push('/profiel');
    }
  },
  methods: {
    onSubmit(role_id) {
      this.errors.flash = false
      this.user.role_id = role_id;
      this.validateRegister();
      if (this.validationStatus) {
        axios({
          method: 'post',
          url: "http://localhost:8765/api/register.json",
          data: this.user,
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
            console.log(data);
        });
      }
    },
    validateRegister() {
      this.validationStatus = true;
      this.errors.user.email = false;
      this.errors.user.password = false;
      this.errors.user.firstname = false;
      this.errors.user.lastname = false;
      this.errors.user.dateofbirth = false;
      this.errors.user.name = false;

      if (this.user.role_id == 2) {
        if (!this.user.firstname) {
          this.errors.user.firstname = "Vul je voornaam in";
          this.validationStatus = false;
        }
        if (!this.user.lastname) {
          this.errors.user.lastname = "Vul je familienaam in";
          this.validationStatus = false;
        }
        if (!this.user.dateofbirth) {
          this.errors.user.lastname = "Vul je geboortedatum in";
          this.validationStatus = false;
        }
      } else if (this.user.role_id == 3) {
        if (!this.user.name) {
          this.errors.user.name = "Vul de naam van je organisatie in";
          this.validationStatus = false;
        }
      }
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

<style lang="scss"></style>