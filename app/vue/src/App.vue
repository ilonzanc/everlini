<template>
  <div id="app" v-on:save-session="onSaveSession">
    <header>
		<router-link to="/"><img src="./assets/images/logo.svg" class="logo"></router-link>
		<a href="#" class="toggleBars" @click.prevent="toggleNav">
			<img src="./assets/icons/hamburger_icon.svg">
		</a>
      <nav :class="{ 'nav-open': navOpen }">
        <ul>
          <li><router-link to="/">Home</router-link></li>
          <li v-if="session.profile_id != ''"><router-link to="/jouw-profiel">Jouw profiel</router-link></li>
          <li><router-link to="/about">About</router-link></li>
          <li><router-link to="/contact">Contact</router-link></li>
          <li v-if="session.profile_id != ''"><router-link to="/" class="btn primary-btn">Uitloggen</router-link></li>
          <li v-if="session.profile_id == ''"><router-link to="/aanmelden" class="btn primary-btn">Aanmelden</router-link></li>
        </ul>
      </nav>
    </header>
    <main>
      	<router-view></router-view>
    </main>
  </div>
</template>

<script>
import Vue from 'vue';
import VueRouter from 'vue-router';
import axios from "axios";
import moment from 'moment';

import routes from './router/index.js';

Vue.use(VueRouter);
Vue.use(require('vue-moment'));

export default {
  name: 'app',
  data () {
    return {
      session: {
        profile_id: '',
        email: '',
        first_name: '',
        last_name: '',
        housnr: '',
        city: '',
        country: '',
        dateofbirth: '',
      },
	    navOpen: false,
    }
  },
  watch: {
    $route() {
      this.navOpen = false;
    }
	},
  methods: {
    onSaveSession: function (user) {
      console.log('click');
      data.session = user;
    },
    toggleNav() {
      if (this.navOpen == false) {
        this.navOpen = true;
      }
      else {
        this.navOpen = false;
      }
    },
    closeNav() {
      this.navOpen = false;
		},
  }
}
</script>

<style type="text/css" src="./assets/css/main.css"></style>