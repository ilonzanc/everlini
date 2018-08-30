<template>
  <div id="register" class="content">
	  <div class="container">
      <h1>Registreren</h1>
      <form @submit.prevent="onSubmit()">
        <div class="row">
          <div class="column column-sm-12 column-lg-6">
            <section>
              <article v-bind:key="message.index" v-for="message in errors.flash" class="flash-message error">
                <i class="fa fa-exclamation-triangle"></i>{{message}}
              </article>
            </section>
            <label class="required" for="username">Gebruikersnaam</label>
            <input type="text" id="profile_username" name="username" placeholder="Jouw gebruikersnaam..." v-model="user.username">
            <span v-if="errors.user.username" class="form-error"><i class="fa fa-exclamation-triangle"></i>{{errors.user.username}}</span>
            <label for="firstname">Voornaam</label>
            <input type="text" id="profile_firstname" name="firstname" placeholder="Jouw voornaam..." v-model="user.firstname">
            <span v-if="errors.user.firstname" class="form-error"><i class="fa fa-exclamation-triangle"></i>{{errors.user.firstname}}</span>
            <label for="lastname">Naam</label>
            <input type="text" id="profile_lastname" name="lastname" placeholder="Jouw naam..." v-model="user.lastname">
            <span v-if="errors.user.lastname" class="form-error"><i class="fa fa-exclamation-triangle"></i>{{errors.user.lastname}}</span>
            <label for="dateofbirth">Geboortedatum</label>
            <input type="date" id="profile_dateofbirth" name="dateofbirth" placeholder="Jouw geboortedatum..." v-model="user.dateofbirth">
            <span v-if="errors.user.dateofbirth" class="form-error"><i class="fa fa-exclamation-triangle"></i>{{errors.user.dateofbirth}}</span>
            <label class="required" for="email">Emailadres</label>
            <input type="email" id="profile_email" name="email" placeholder="Jouw emailadres..." v-model="user.email">
            <span v-if="errors.user.email" class="form-error"><i class="fa fa-exclamation-triangle"></i>{{errors.user.email}}</span>
            <label class="required" for="pass">Wachtwoord</label>
            <input type="password" id="profile_pass" name="pass" placeholder="********" v-model="user.password">
            <span v-if="errors.user.password" class="form-error"><i class="fa fa-exclamation-triangle"></i>{{errors.user.password}}</span>
            <label class="required" for="confirm_pass">Bevestig wachtwoord</label>
            <input type="password" id="profile_confirm_pass" name="confirm_pass" placeholder="********" v-model="user.confirm_password">
            <span v-if="errors.user.confirm_password" class="form-error"><i class="fa fa-exclamation-triangle"></i>{{errors.user.confirm_password}}</span>
          </div>
          <div class="column column-sm-12 column-lg-6">
            <label class="required" for="interests">Interesses</label>
            <ul v-if="profile.user.interests.length > 0">
              <li v-for="interest in profile.user.interests" v-bind:key="interest.index" class="tag removable-tag" @click.prevent="deleteInterest($event)">{{ interest }}</li>
            </ul>
            <div class="interests_input">
              <input type="text" name="0" id="interest_input" v-model="currentInterest" placeholder="Eigen interesse toevoegen...">
              <i class="fa fa-plus" @click.prevent="addInterest"></i>
            </div>
            <span v-if="errors.interests" class="form-error"><i class="fa fa-exclamation-triangle"></i>{{errors.profile.user.interests}}</span>
            <button type="submit" class="btn primary-btn widebtn">Registreren</button>
          </div>
        </div>
      </form>
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
      currentInputIndex: 0,
      inputs: [ 'fullname', 'email'],
      rows: [],
      user: {
        role_name: 'user',
        email: "",
        password: "",
        firstname: "",
        lastname: "",
        dateofbirth: "",
        confirm_password: "",
        interests: []
      },
      validationStatus: true,
      errors: {
        user: {
          email: "",
          password: "",
          firstname: "",
          lastname: "",
          dateofbirth: "",
        },
        flash: {

        }
      }
    }
  },
  watch: {
  },
  mounted () {
    if (this.$parent.session != null) {
      this.$router.push('/profiel');
    }
  },
  methods: {
    onSubmit() {
      this.errors.flash = false
      this.validateRegister();
      if (this.validationStatus) {
        axios({
          method: 'post',
          url: apiurl + "register.json",
          data: this.user,
        })
        .then((response) => {
          if (response.data.errors) {
            this.errors.flash = response.data.errors;
            this.validationStatus = false;
          } else {
            this.user = response.data;
            localStorage.setItem("user", JSON.stringify(response.data));
            this.$parent.session = response.data;
            this.$router.push('/profiel/' + response.data.username);
          }
        })
        .catch((error) => {
          if (error.response) {
            console.log(error.response.status);
            console.log(error.response.headers);
            console.log(error.response.data.message);

            let errors = [];

          } else if (error.request) {
            console.log(error.request);
          } else {
            console.log('Error', error.message);
          }
          console.log(error.config);
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
      if (this.user.email.indexOf('@') <= 0 || this.user.email.lastIndexOf('.') < this.user.email.indexOf('@') || this.user.email.lastIndexOf('.') == this.user.email.length  ) {
        this.errors.user.email = "Vul een geldig emailadres in";
        this.validationStatus = false;
      }
      if (!this.user.password) {
        this.errors.user.password = "Vul een wachtwoord in";
        this.validationStatus = false;
      }
    },
    addRow() {
      this.rows.push({value: this.user.interests[this.currentInputIndex], index: this.currentInputIndex});
      this.currentInputIndex++;
    },
  }
}
</script>

<style lang="scss">
.interests_input {
  position: relative;
  .fa-plus {
    position: absolute;
    right: 0;
    top: 0;
    padding: 15px 17px 16px;
    background: #FECA57;
    color: #fff;
    border-top-right-radius: 5px;
    border-bottom-right-radius: 5px;
    cursor: pointer;
  }
}
@media (min-width: 42.5rem) {
}
</style>