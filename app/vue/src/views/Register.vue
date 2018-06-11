<template>
  <div id="register" class="content">
	  	<div class="container">
        <h1>Registreren</h1>
        <tabs>
          <tab name="als gebruiker" :selected="true">
            <h2>Als gebruiker</h2>
            <form @submit.prevent="onSubmit(2)">
              <input type="text" id="profile_role_id" name="role_id" required v-model="user.role_id">
              <label for="firstname">Voornaam</label>
              <input type="text" id="profile_firstname" name="firstname" placeholder="Jouw voornaam..." required v-model="user.firstname">
              <label for="lastname">Naam</label>
              <input type="text" id="profile_lastname" name="lastname" placeholder="Jouw naam..." required v-model="user.lastname">
              <label for="dateofbirth">Geboortedatum</label>
              <input type="text" id="profile_dateofbirth" name="dateofbirth" placeholder="Jouw geboortedatum..." required v-model="user.dateofbirth">
              <label for="email">Emailadres</label>
              <input type="email" id="profile_email" name="email" placeholder="Jouw emailadres..." required v-model="user.email">
              <label for="pass">Wachtwoord</label>
              <input type="password" id="profile_pass" name="pass" placeholder="********" required v-model="user.password">
              <button type="submit" class="btn primary-btn widebtn">Registreren</button>
            </form>
          </tab>
          <tab name="als organisatie">
            <h2>Als organisatie</h2>
            <form @submit.prevent="onSubmit(3)">
              <input type="text" id="organisation_role_id" name="role_id" required v-model="user.role_id">
              <label for="name">Naam organisatie</label>
              <input type="text" id="organisation_name" name="name" placeholder="Naam organisatie..." required v-model="user.name">
              <label for="description">Beschrijving</label>
              <textarea id="organisation_description" name="description" placeholder="Beschrijving..." required v-model="user.description"></textarea>
              <label for="email">Emailadres</label>
              <input type="email" id="organisation_email" name="email" placeholder="Emailadres..." required v-model="user.email">
              <label for="pass">Wachtwoord</label>
              <input type="password" id="organisation_pass" name="pass" placeholder="********" required v-model="user.password">
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
    }
  },
  watch: {
    isActive() {
      alert('hey');
    }
  },
  mounted () {
    let loggedInUser = JSON.parse(localStorage.getItem("user"));
    if (loggedInUser) {
      this.$router.push('/profiel');
    }
  },
  methods: {
    onSubmit() {
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
          console.log(data);
      });
    }
  }
}
</script>

<style lang="scss"></style>